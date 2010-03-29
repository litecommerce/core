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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Customer_Help extends XLite_Controller_Customer_Abstract
{	
    public $params = array("target", "mode");


	/**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->locationPath->addNode(new XLite_Model_Location('Help zone'));
    }

	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        $location = parent::getLocation();

        switch ($this->get('mode')) {
            case 'terms_conditions':
                $location = 'Terms & Conditions';
                break;
            case 'privacy_statement':
                $location = 'Privacy statement';
                break;
            case 'contactus':
                $location = 'Contact us';
                break;
        }

        return $location;
    }


	/**
     * Get page title
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Help section';
    }



    function fillForm()
    {
        if ($this->get("mode") == "contactus" ) {
            if ($this->auth->is("logged")) {
                // fill in contact us form with default values
                $this->set("email", $this->auth->getComplex('profile.login'));
                $this->set("firstname", $this->auth->getComplex('profile.billing_firstname'));
                $this->set("lastname", $this->auth->getComplex('profile.billing_lastname'));
                $this->set("address", $this->auth->getComplex('profile.billing_address'));
                $this->set("zipcode", $this->auth->getComplex('profile.billing_zipcode'));
                $this->set("city", $this->auth->getComplex('profile.billing_city'));
                $this->set("contactus_state", $this->auth->getComplex('profile.billing_state'));
				$this->set("contactus_custom_state", $this->auth->getComplex('profile.billing_custom_state'));
                $this->set("contactus_country", $this->auth->getComplex('profile.billing_country'));
                $this->set("phone", $this->auth->getComplex('profile.billing_phone'));
                $this->set("fax", $this->auth->getComplex('profile.billing_fax'));
            } else {
                $this->set("contactus_state", $this->config->getComplex('General.default_state'));
                $this->set("contactus_country", $this->config->getComplex('General.default_country'));
            }
        }
    }

    function getState()
    {
        $s = new XLite_Model_State($this->get("state_id"));
        return $s->get("state");
    }

    function getCountry()
    {
        $c = new XLite_Model_Country($this->get("country_id"));
        return $c->get("country");
    }
    
    function action_contactus()
    {
        $mailer = new XLite_Model_Mailer();
        $mailer->mapRequest();
        $st = new XLite_Model_State($_REQUEST["contactus_state"]);
		if ($st->get("state_id") == -1) {
			$st->set("state", $_REQUEST["contactus_custom_state"]);
		}
        $mailer->set("state", $st->get("state")); // fetch state name
        $cn = new XLite_Model_Country($_REQUEST["contactus_country"]);
        $mailer->set("country", $cn->get("country")); // fetch country name
		$mailer->set("charset", $cn->get("charset"));
        $mailer->compose($this->get("email"), $this->config->getComplex('Company.support_department'), "contactus");
        $mailer->send();
        $this->set("mode", "contactusMessage");
    }

    function getCountriesStates()
    {
        if (!isset($this->_profileDialog)) {
            $this->_profileDialog = new XLite_Controller_Customer_Profile();
        }
        return $this->_profileDialog->getCountriesStates();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
