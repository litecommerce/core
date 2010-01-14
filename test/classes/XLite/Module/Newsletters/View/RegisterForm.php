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

/**
* Module_Newsletters decorated class.
*
* @package Module_Newsletters
* @version $Id$
*/
class XLite_Module_Newsletters_View_RegisterForm extends XLite_View_RegisterForm implements XLite_Base_IDecorator
{
	protected $newsLists = null;

    function fillForm()
    {
        parent::fillForm();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!is_null($this->profile)) {
                $ns = new XLite_Module_Newsletters_Model_NewsSubscriber();
                $email = strtolower(addslashes($this->profile->get("login")));
                // check all subscribed lists
                foreach ($ns->findAll("LOWER(email)='".$email."'") as $subs) {
                    $this->list_ids[] = $subs->get("list_id");
                }
            } else {
                // check all available news lists in register form
                foreach ($this->get("newsLists") as $nl) {
                    $this->list_ids[] = $nl->get("list_id");
                }
            }
        }
    }
    
    function action_register()
    {
        parent::action_register();
        if ($this->is("valid")) {
            // subscribe to newsletter(s)
            $listIDs = $this->get("list_ids");
            if (is_array($listIDs) && count($listIDs) > 0) {
                foreach ($listIDs as $listID) {
                	$listID = intval($listID);
                    $nl = new XLite_Module_Newsletters_Model_NewsList();
                    if ($nl->find("list_id='".$listID."' AND enabled=1")) {
                        $ns = new XLite_Module_Newsletters_Model_NewsSubscriber();
                        $email = strtolower(addslashes($this->profile->get("login")));
                        if (!$ns->find("list_id='".$listID."' AND LOWER(email)='".$email."'")) {
                            $ns->subscribe($email, $listID, false);
                        }
                    }
                }    
            }
        } 
    }

    function action_modify()
    {
        parent::action_modify();
        if ($this->is("valid")) {
            $listIDs = (array)$this->get("list_ids");
            $ns = new XLite_Module_Newsletters_Model_NewsSubscriber();
            $email = strtolower(addslashes($this->profile->get("login")));

            $subscribedList = $ns->findAll("LOWER(email)='$email'");
            $subscribed = array();
            if (is_array($subscribedList) && count($subscribedList)>0) {
            	foreach($subscribedList as $s) {
            		$subscribed[] = $s->get("list_id");
            	}
            }
            $subscribedDel = array_diff($subscribed, $listIDs);
            $subscribedAdd = array_diff($listIDs, $subscribed);
            if (!(count($subscribedAdd) > 0 || count($subscribedDel) > 0)) {
            	return;
            }

            $nl = new XLite_Module_Newsletters_Model_NewsList();
            // walk through the all available News lists
            foreach ($nl->findAll() as $nlist) {
                $id = $nlist->get("list_id");
                if (!$ns->find("list_id=$id AND LOWER(email)='$email'") && in_array($id, $listIDs)) {
                    // request to subscribe
                    $ns->set("list_id", $id);
                    $ns->set("profile", $this->profile);
                    $ns->subscribe($email, $id);
                } elseif ($ns->find("list_id=$id AND LOWER(email)='$email'") && !in_array($id, $listIDs)) {
                    // request to unsubscribe
                    $ns->set("list_id", $id);
                    $ns->set("profile", $this->profile);
                    $ns->unsubscribe($email, $id);
                }
            }
        }
    }

    function getNewsLists()
    {
        if (is_null($this->newsLists)) {
            $nl = new XLite_Module_Newsletters_Model_NewsList();
            $this->newsLists = $nl->findAll();
        }
        return $this->newsLists;
    }

    function isListSelected($id)
    {
        return @in_array($id, $this->list_ids);
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
