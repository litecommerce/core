<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

func_define('ORDER_CRYPTED_MESSAGE', '-- This data is encrypted. Please enter master password to view it --');

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
        if ($this->xlite->is('adminZone') && (!$this->_detailsModified)) {
            if (!is_null($this->session->get('masterPassword'))) {
                $details = $this->getSecureDetails();
            }
        } elseif (!$this->xlite->is('adminZone') && !is_null($this->_secureDetails)) {
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
            $d = parent::get('secureDetails');
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
        parent::set('secureDetails', $this->gpg->encrypt(serialize($value)));
        $this->_secureDetailsText = $this->prepareSecureDetailsText($value);
        parent::set('secureDetailsText', $this->gpg->encrypt($this->_secureDetailsText));
    }

    function prepareSecureDetailsText($details)
    {
        if (empty($details)) return "";
        $text = "Secure Order Details:\n";
        $text.= "---------------------\n";
        foreach ((array)$details as $name=>$value) {
            $title = ucwords(str_replace('cc', "credit card", str_replace('_', " ", $name)));
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
        $details = $this->get('details');
        if (!empty($details) && $this->get('payment_method') == "CreditCard" && $this->getComplex('config.AdvancedSecurity.gpg_crypt_db')) {
            if (!$this->xlite->is('adminZone')) { // customer is placing order
                $this->setSecureDetails($details);
                // check if GnuPG failed to encrypt data (invalid pubkey?)
                $check = parent::get('secureDetails');
                if (empty($check)) {
                    $this->set('status', "F");
                    return;
                }
                $labels = $this->getDetailLabels();
                foreach ($labels as $label => $value) {
                    $details[$label] = ORDER_CRYPTED_MESSAGE;
                }
                $this->set('details', $details);
            } elseif (!is_null($this->session->get('masterPassword'))) {
                $this->setSecureDetails($details);
                $labels = $this->getDetailLabels();
                foreach ($labels as $label => $value) {
                    $details[$label] = ORDER_CRYPTED_MESSAGE;
                }
                $this->set('details', $details);
            }
        }
        parent::update();
        // order details are not changed anymore:
        $this->_detailsModified = false;
    }

    function encrypt()
    {
        $secureDetails = parent::get('secureDetails');
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
        $secureDetails = parent::get('secureDetails');
        if (!$this->gpg->isEncoded($secureDetails)) {
            return;
        }
        parent::setDetails(unserialize($this->gpg->decrypt($secureDetails, $passphrase)));
        parent::set('secureDetails', "");
        parent::update();
    }
}
