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


/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* New list description.
*
* @package Module_Newsletters
* @access public
* @version $Id$
*/
class NewsList extends Base
{
    var $fields = array(
            "list_id"  => 0,
            "name" => "",
            "description"  => "",
            "show_as_news" => 0,
            "enabled" => 1
            );

    var $autoIncrement = "list_id";
    var $alias = "newslists";

    function filter()
    {
        if ($this->xlite->is("adminZone")) {
            return parent::filter();
        } else {
            return $this->is("enabled");
        }
    }

    function delete()
    {
        // delete newsletters..
        $nl = func_new("NewsLetter");
        foreach ((array)$nl->findAll("list_id=".$this->get("list_id")) as $nsl) {
            $nsl->delete();
        }

        // delete subscribers..
        $ns = func_new("NewsSubscriber");
        foreach ((array)$ns->findAll("list_id=".$this->get("list_id")) as $subscriber) {
            $subscriber->unsubscribe();
        }

        // delete list..
        parent::delete();
    }

    function getSubscribers()
    {
        if (is_null($this->subscribers)) {
            $ns = func_new("NewsSubscriber");
            $this->subscribers = $ns->findAll("list_id=".$this->get("list_id"));
        }
        return $this->subscribers;
    }

    function getMessages()
    {
        if (is_null($this->messages)) {
            $nm = func_new("NewsLetter");
            $this->messages = $nm->findAll("list_id=".$this->get("list_id"));
        }
        return $this->messages;
    }

    function getShowedListsNumber()
    {
        if (is_null($this->xlite->showed_lists_number)) {
            $this->xlite->showed_lists_number = $this->count("show_as_news=1 AND enabled=1");
        }
        return $this->xlite->showed_lists_number;
    }
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
