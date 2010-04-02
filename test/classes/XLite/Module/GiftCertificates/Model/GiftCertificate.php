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


define('GC_DOESNOTEXIST', 1);
if (!defined('GC_OK')) {
	define('GC_OK', 2);
}
if (!defined('GC_DISABLED')) {
	define('GC_DISABLED', 3);
}
define('GC_EXPIRED', 4);

/**
* GiftCertificate description.
*
* @package Module_GiftCertificates
* @access public
* @version $Id$
*/
class XLite_Module_GiftCertificates_Model_GiftCertificate extends XLite_Model_Abstract
{	
	public $alias = "giftcerts";	
	public $fields = array(
		"gcid" => "", // GC unique ID (primary key)
		"profile_id" => "", // certificate creator
		"purchaser" => "", // 'From' field
		"recipient" => "", // 'To' field
		"send_via" => "", // 'E' (e-mail) / 'P' (post)
		"recipient_email" => "",
		"recipient_firstname" => "",
		"recipient_lastname" => "",
		"recipient_address" => "",
		"recipient_city" => "",
		"recipient_state" => "",
        "recipient_custom_state" => "",
		"recipient_zipcode" => "",
		"recipient_country" => "",
		"recipient_phone" => "",
		"message" => "",
        "greetings" => "",
        "farewell" => "",
		"amount" => "",
		"border" => "",
		"debit" => "",
		"status" => "", // A (active), D (disabled), U (used), P (pending), depending on order status, E (expired)
		"add_date" => "",
		"expiration_date" => "",
		"exp_email_sent" => 0,
        "ecard_id" => ""
		);	
	public $primaryKey = array("gcid");	
    public $ecard = null;	
    public $recipientState = null;	
    public $recipientCountry = null;

    function getFormattedMessage()
    {
         return nl2br(htmlspecialchars($this->get("message")));
    }

    function getRecipientState()
    {
        if (is_null($this->recipientState)) {
            $this->recipientState = new XLite_Model_State($this->get("recipient_state"));
        }
        return $this->recipientState;
    }

    function getRecipientCountry()
    {
        if (is_null($this->recipientCountry)) {
            $this->recipientCountry = new XLite_Model_Country($this->get("recipient_country"));
        }
        return $this->recipientCountry;
    }

    function set($name, $value)
    {
        if (
			$name == "status"
			&& $value == "A"
			&& $this->get("status") != "A"
			&& $this->get("send_via") == "E"
		) {
            // send GC by e-mail
            $mail = new XLite_Model_Mailer();
            $mail->gc = $this;
            $mail->compose(
                $this->config->Company->site_administrator, 
                $this->get("recipient_email"),
                "modules/GiftCertificates"
			);
            $mail->send();
        }

        parent::set($name, $value);
    }

	function generateGC()
	{
  	    return generate_code();
	}

    function validate()
    {
		$result = GC_OK;

        if (!$this->is("exists")) {
            $result = GC_DOESNOTEXIST;

        } elseif ($this->get("status") == 'E') {
			$result = GC_EXPIRED;

		} elseif (time() > $this->get("expirationDate")) {
   	        $this->set("status", "E");
      	    $this->update();

  	        $result = GC_EXPIRED;

      	} elseif ($this->get("status") != "A") {
           	$result = GC_DISABLED;

        }

        return $result;
    }
    /**
    * Return true if there are any e-Cards in the dataabase
    */
    function hasECards()
    {
        $ec = new XLite_Module_GiftCertificates_Model_ECard();

        return count($ec->findAll("enabled=1")) > 0;
    }
    function getECard()
    {
        if (is_null($this->ecard) && $this->get("ecard_id")) {
        	$this->ecard = new XLite_Module_GiftCertificates_Model_ECard($this->get("ecard_id"));
        }
        return $this->ecard;
    }

    function showECardBody()
    {
        $c = new XLite_Module_GiftCertificates_View_CEcard();
        $c->gc = $this;
        $c->init();
        $c->display();
    }

    /**
    * To use from template: returns the height of the upper ($bottom=false) 
    * or bottom ($bottom=true) border.
    */
    function getBorderHeight($bottom = false)
    {
        $layout = XLite_Model_Layout::getInstance();
        $borderFile = LC_ROOT_DIR
			. 'skins/mail/'
			. $layout->get("locale")
			. '/modules/GiftCertificates/ecards/borders/'
			. $this->get("border")
			. ($bottom ? '_bottom' : '')
			. '.gif';

		$h = 0;

        if (is_readable($borderFile)) {
            list($w, $h) = getimagesize($borderFile);
        }

        return $h;
    }

    function getBottomBorderHeight()
    {
        return $this->getBorderHeight(true);
    }

    function getBordersDir()
    {
        $layout = XLite_Model_Layout::getInstance();

        return $this->xlite->getShopUrl('skins/mail/' . $layout->get('locale') . '/modules/GiftCertificates/ecards/borders/');
    }

    function getBorderUrl()
    {
        return $this->getBordersDir() . $this->get('border') . '.gif';
    }

    function getImagesDir()
    {
        return $this->xlite->getShopUrl('');
    }

	function getDefaultExpirationPeriod()
	{
		return $this->config->GiftCertificates->expiration;
	}

	function getProfile()
	{
		if (is_null($this->_profile)) {
			$this->_profile = new XLite_Model_Profile($this->get("profile_id"));
		}

		return $this->_profile;
	}

	/**
	 * Get expiration date 
	 * 
	 * @return integer
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getExpirationDate()
	{
		$date = $this->get('expiration_date');
		if (0 >= $date) {
			$date = $this->get('add_date') + $this->getDefaultExpirationPeriod() * 30 * 24 * 3600;
		}

		return $date;
	}

	/**
	 * Check - display (and send) expiration warning or not
	 * 
	 * @return boolean
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function isDisplayWarning()
	{
		$result = false;

		$exp_date = $this->getExpirationDate();
		$warn_date = $exp_date - $this->config->GiftCertificates->expiration_warning_days * 24 * 3600;

		if (
			time() >= $warn_date
			&& time() <= $exp_date
		) {
			if (
				$this->config->GiftCertificates->expiration_email
				&& !$this->get('exp_email_sent')
				&& 0 < $this->get('debit')
				&& 'A' == $this->get('status')
			) {
				// send warning notification
				$mailer = new XLite_Model_Mailer();
				$mailer->cert = $this;
				$mailer->compose(
					$this->config->Company->site_administrator,
					$this->get('recipient_email'),
					'modules/GiftCertificates/expiration_notification'
				);
				$mailer->send();

				$this->set('exp_email_sent', 1);
				$this->update();
			}

			$result = true;
		}

		return $result;
	}

    /**
     * Get gift ceritficate expiration conditions 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getExpirationConditions()
    {
        $now = time();
        $exp_time = $now + $this->config->GiftCertificates->expiration_warning_days * 24 * 3600;

        return array(
        	'expiration_date > ' . $now . ' AND expiration_date < ' . $exp_time,
        	'debit > 0',
        	'exp_email_sent = 0',
        	'status = "A"',
		);
    }
}

