<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

func_define('ORDER_CRYPTED_MESSAGE', '-- This data is encrypted. Please enter master password to view it --');

/**
* Module_AdvancedSecurity_Order description.
*
* @package $Package$
* @version $Id$
*/
class XLite_Module_AdvancedSecurity_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{	
    public $gpg;	
	public $_detailsModified = false; // shows if the order details were modified

	protected $_secureDetails = null;

    public function __construct($id = null)
    {
        $this->fields['secureDetails'] = ''; // GPG encrypted order details
		$this->fields['secureDetailsText'] = ''; // GPG encrypted order details for sending to admin via email
        parent::__construct($id);
        $this->gpg = new XLite_Module_AdvancedSecurity_Model_GPG();
    }

    function setDetails($value)
    {   
		$this->_detailsModified = true;
        parent::setDetails($value);
        $this->_secureDetails = null;
    }

    function getDetails()
    {
        $details = parent::getDetails();
        $oldDetails = $details;
        if ($this->xlite->is("adminZone") && (!$this->_detailsModified)) {
            if (!is_null($this->session->get("masterPassword"))) {
                $details = $this->getSecureDetails();
            }
        } elseif (!$this->xlite->is("adminZone") && !is_null($this->_secureDetails)) {
            $details = $this->_secureDetails;
        }
        if (!(isset($oldDetails) && is_array($oldDetails))) {
        	$oldDetails = array();
        }
        if (!(isset($details) && is_array($details))) {
        	$details = array();
        }
		$details = array_merge($oldDetails, $details);
        return $details;
    }

    function getSecureDetails() 
    {
        if (is_null($this->_secureDetails)) {
            $d = parent::get("secureDetails");
            if ($d == '') {
                $this->_secureDetails = parent::getDetails();
            } else {
                // decrypt order secure details with a secret key
                $this->_secureDetails = unserialize($this->gpg->decrypt($d));
                if ($this->_secureDetails === false) { // decrypt failed
                    $this->_secureDetails = parent::getDetails();
                } 
            }
        }
        return $this->_secureDetails;
    } 

    function setSecureDetails($value) 
    {
        $this->_secureDetails = $value;
        // encrypt details with a public key
        parent::set("secureDetails", $this->gpg->encrypt(serialize($value)));
		$this->_secureDetailsText = $this->prepareSecureDetailsText($value);
		parent::set("secureDetailsText", $this->gpg->encrypt($this->_secureDetailsText));
    } 

	function prepareSecureDetailsText($details)
	{
		if (empty($details)) return "";
		$text = "Secure Order Details:\n";
		$text.= "---------------------\n";
		foreach ((array)$details as $name=>$value) {
			$title = ucwords(str_replace("cc", "credit card", str_replace("_", " ", $name)));
			$text .= sprintf("%-25s %s\n", "$title:", $value);
		}
		return $text;
	}

	function __clone()
	{
		$clone = parent::__clone();

		$clone->set('details', $this->get('details'));
		// if the master password is not enterred, the secure details are copied as is from the original order
		$clone->properties['secureDetails'] = $this->properties['secureDetails'];
		$clone->update();
		return $clone;
	}

    function update()
    {
        $details = $this->get("details");
        if (!empty($details) && $this->get("payment_method") == "CreditCard" && $this->getComplex('config.AdvancedSecurity.gpg_crypt_db')) {
            if (!$this->xlite->is("adminZone")) { // customer is placing order
                $this->setSecureDetails($details);
                // check if GnuPG failed to encrypt data (invalid pubkey?)
                $check = parent::get("secureDetails");
                if (empty($check)) {
                    $this->set("status", "F");
                    return;
                }
				$labels = $this->getDetailLabels();
                foreach ($labels as $label => $value) {
                    $details[$label] = ORDER_CRYPTED_MESSAGE;
                }
                $this->set("details", $details);
            } elseif (!is_null($this->session->get("masterPassword"))) {
                $this->setSecureDetails($details);
				$labels = $this->getDetailLabels();
                foreach ($labels as $label => $value) {
                    $details[$label] = ORDER_CRYPTED_MESSAGE;
                }
                $this->set("details", $details);
            }
        }    
        parent::update();
		// order details are not changed anymore:
		$this->_detailsModified = false; 
    }

    function encrypt()
    {
        $secureDetails = parent::get("secureDetails");
        if ($this->gpg->isEncoded($secureDetails)) {
            return;
        }
		$labels = $this->getDetailLabels();
        $details = parent::getDetails();
        $this->setSecureDetails($details);
        foreach ($labels as $label => $value) {
            $details[$label] = ORDER_CRYPTED_MESSAGE;
        }
        parent::setDetails($details);
        parent::update();
    }

    function decrypt($passphrase)
    {
        $secureDetails = parent::get("secureDetails");
        if (!$this->gpg->isEncoded($secureDetails)) {
            return;
        }
        parent::setDetails(unserialize($this->gpg->decrypt($secureDetails, $passphrase)));
        parent::set("secureDetails", "");
        parent::update();
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
