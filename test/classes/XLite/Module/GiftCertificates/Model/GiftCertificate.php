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
	var $alias = "giftcerts";
	var $fields = array(
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
	var $primaryKey = array("gcid");
    var $ecard = null;
    var $recipientState = null;
    var $recipientCountry = null;

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
        if ($name == "status" && $value == "A" && $this->get("status") != "A" && $this->get("send_via") == "E") {
            // send GC by e-mail
            $mail = new XLite_Model_Mailer();
            $mail->gc = $this;
            $mail->compose(
                $this->config->get("Company.site_administrator"), 
                $this->get("recipient_email"),
                "modules/GiftCertificates");
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
        if (!$this->is("exists")) {
            return GC_DOESNOTEXIST;
        }
        
		$estimated_expiration = $this->get("add_date") + $this->config->get("GiftCertificates.expiration") * 30 * 24 * 3600;
        if (($this->get("status") == 'E') || (time() > $this->get("expirationDate"))) {
            if ($this->get("status") != 'E') {
                $this->set("status", "E");
                $this->update();
            }
            return GC_EXPIRED;
        }
        if ($this->get("status") != "A") {
            return GC_DISABLED;
        }
        return GC_OK;
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
        if (is_null($this->ecard)) {
            if ($this->get("ecard_id")) {
                $this->ecard = func_new("ECard",$this->get("ecard_id"));
            } else {
                $this->ecard = null;
            }
        }
        return $this->ecard;
    }

    function showECardBody()
    {
        $c = func_new("CECard");
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
        $layout = func_get_instance("Layout");
        $borderFile = "skins/mail/" . $layout->get("locale") . "/modules/GiftCertificates/ecards/borders/" . $this->get("border") . ($bottom?"_bottom":"") . ".gif";
        if (is_readable($borderFile)) {
            list($w, $h) = getimagesize($borderFile);
        } else {
            $h = 0;
        }
        return $h;
    }
    function getBottomBorderHeight()
    {
        return $this->getBorderHeight(true);
    }

    function getBordersDir()
    {
        $layout = func_get_instance("Layout");
        return $this->xlite->shopURL("skins/mail/" . $layout->get("locale") . "/modules/GiftCertificates/ecards/borders/");
    }

    function getImagesDir()
    {
        return $this->xlite->shopURL("");
    }

	function getDefaultExpirationPeriod()
	{
		$expiration = $this->xlite->get("config.GiftCertificates.expiration");
		return $expiration;
	}

	function getProfile()
	{
		if (is_null($this->_profile)) {
			$this->_profile = new XLite_Model_Profile($this->get("profile_id"));
		}
		return $this->_profile;
	}

	function getExpirationDate()
	{
		$date = $this->get("expiration_date");
		if ($date <= 0) {
			$estimated_expiration = $this->get("add_date") + $this->get("defaultExpirationPeriod")  * 30 * 24 * 3600;
			$date = $estimated_expiration;
		}
		return $date;
	}

	function isDisplayWarning()
	{
		$days = $this->xlite->get("config.GiftCertificates.expiration_warning_days");
		$warn_time = $days * 24 * 3600;
		$exp_date = $this->getExpirationDate();
		$warn_date = $exp_date - $warn_time;
		if ((time() >= $warn_date) && (time() <= $exp_date)) {
			if ($this->xlite->get("config.GiftCertificates.expiration_email") && (!$this->get("exp_email_sent"))) {
				if (($this->get("debit") > 0) && ($this->get("status") == "A")) {
					// send warning notification
					$mailer = new XLite_Model_Mailer();
					$mailer->cert = $this;
					$mailer->compose(
						$this->xlite->get("config.Company.site_administrator"),
						$this->get("recipient_email"),
						'modules/GiftCertificates/expiration_notification'
					);
					$mailer->send();

					$this->set("exp_email_sent", 1);
					$this->update();
				}
			}
			return true;
		}
		return false;
	}

    function getExpirationConditions()
    {
        $now = time();
        $warning_days = $this->xlite->get("config.GiftCertificates.expiration_warning_days");
        $exp_time = $now + ($warning_days * 24 * 3600);
        $where = array();
        $where[] = "expiration_date > '$now' AND expiration_date < '$exp_time'";
        $where[] = "debit > 0";
        $where[] = "exp_email_sent = 0";
        $where[] = "status = 'A'";
        return $where;
    }
}

class XLite_Module_GiftCertificates_Model_GiftCertificate extends XLite_View
{
    var $gc = null;

    function getTemplate()
    {
        return "modules/GiftCertificates/ecards/" . $this->get("gc.ecard.template") . ".tpl";
    }

    function getTemplateFile()
    {
        $layout = func_get_instance("Layout");
        return "skins/mail/" . $layout->get("locale") . "/" . $this->get("template");
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
