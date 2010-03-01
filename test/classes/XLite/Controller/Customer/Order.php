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
* Dialog_Order description.
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Customer_Order extends XLite_Controller_Customer_Abstract
{	
    public $params = array("target", "order_id");	
    public $order = null;	
    public $isAccessDenied = false;


	/**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

		$this->locationPath->addNode(new XLite_Model_Location('Search orders', $this->buldURL('order_list')));
    }

	/**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getLocation()
    {   
        return 'Order details';
    }


    function getTemplate()
    {
        if ($this->get("mode") == "invoice") {
            // print invoice
            return "common/print_invoice.tpl";
        }
        return parent::getTemplate();
    }

    function handleRequest()
    {
        // security check
        if ($this->session->get("last_order_id") == $this->get("order_id")) {
            parent::handleRequest();
            return;
        } else {
            if ($this->auth->is("logged") && $this->auth->getComplex('profile.profile_id') == $this->getComplex('order.orig_profile_id')) {
                parent::handleRequest();
                return;
            }    
        }
        $this->redirect("cart.php?mode=accessDenied");
    }

    function getOrder()
    {
        if (is_null($this->order)) {
            $this->order = new XLite_Model_Order($this->get("order_id"));
        }
        return $this->order;
    }

	function getCharset()
	{
		$charset = $this->getComplex('order.profile.billingCountry.charset');
		return ($charset) ? $charset : parent::getCharset();
	}

    function getSecure()
    {
        return $this->getComplex('config.Security.customer_security');
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
