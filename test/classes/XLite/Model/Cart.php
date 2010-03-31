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
* Class Cart implements the Shopping Cart functionality.
*
* @package Kernel
* @version $Id$
*/
class XLite_Model_Cart extends XLite_Model_Order implements XLite_Base_ISingleton
{
	public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    function __construct($id = null)
    {
        parent::__construct($id);

        $this->fields["status"] = "T";
        if ($this->session->isRegistered("order_id")) {
            $this->set("order_id", $this->session->get("order_id"));
            if (!$this->is("exists")) {
                $this->set("order_id", null);
            }
        }
        if ($this->get("status") == "T") {
            if ($this->auth->get("logged")) {
                if ($this->auth->getComplex('profile.profile_id') != $this->get("profile_id")) {
                    $this->set("profile", $this->auth->get("profile"));
                    $this->calcTotals();
                    if ($this->isPersistent) {
                        $this->update();
                    }
                }
            } else if ($this->get("profile_id")) {
                $this->set("profile",  null);
                $this->calcTotals();
            }        
        }
    }

    /**
    * Saves the shopping cart content to session.
    *
    * @access public
    */
    function create()
    {
        $this->set("date", time());
        $this->set("status", "T");
        parent::create();
        $this->session->set("order_id", $this->get("order_id"));
    }

    function update()
    {
        $this->set("date", time());
        parent::update();
    }

    /**
    * Clears the shopping cart.
    *
    * @access public
    */
    function clear()
    {
        $this->session->set("order_id", null);
        $this->_items = array();
    }

    function delete()
    {
        $this->set("profile", null);
        parent::delete();
        $this->session->set("order_id", null);
    }

    /**
    * This method is called during checkout before payment processor
    * is called. 
    * The default implementation
    * copies the current user profile into the order and sets checkout date. 
    */
    function checkout() // {{{
    {
        if ($this->get("status") == "T") {
            $this->set("date", time());

            if ($this->auth->getComplex('profile.order_id')) {
                // anonymous checkout:
                // use the current profile as order profile
                $this->set("profile_id", $this->getComplex('profile.profile_id')); 
            } else {
                $this->set("profileCopy", $this->auth->get("profile"));
            }
            $this->set("status", "I");

            $this->update();
        }
    } // }}}
}

