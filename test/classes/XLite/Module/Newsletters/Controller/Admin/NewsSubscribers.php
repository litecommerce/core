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
* @package Module_Newsletters
* @access public
* @version $Id$
*/
class XLite_Module_Newsletters_Controller_Admin_NewsSubscribers extends XLite_Controller_Admin_Abstract
{
    var $params = array("target", "list_id", "subscriber", "sortby");

    function init()
    {
        parent::init();
        require_once "modules/Newsletters/encoded.php";
    }
    
    function getList()
    {
        if (is_null($this->list)) {
            $this->list = new XLite_Module_Newsletters_Model_NewsList($this->get("list_id"));
        }
        return $this->list;
    }

    function getSubscribers()
    {
        if (is_null($this->subscribers)) {
            $ns = new XLite_Module_Newsletters_Model_NewsSubscriber();
            $condition = array();
            $condition[] = "list_id=".$this->get("list_id");
			$email = $this->get("subscriber");
            if (isset($email)) {
            	$email = trim($email);
            	if (!empty($email)) {
            		$condition[] = "(email LIKE '%".addslashes($email)."%')";
            	}
            }
            $condition = implode(" AND ", $condition);
			$sortby = $this->get("sortby");
          	if (!(isset($sortby) && ($sortby == "email" || $sortby == "since_date"))) {
          		$sortby = "since_date";
          	}
            $this->subscribers = $ns->findAll($condition, $sortby);
        }
        return $this->subscribers;
    }

    function action_add_subscriber()
    {
        $nl = func_new("NewsSubscriber");
        if ($nl->find("list_id=".$this->get("list_id")." AND LOWER(email)='".strtolower(addslashes($this->get("email")))."'")) {
            $this->set("valid", false);
            $this->set("userExists", true);
        } else {
            $nl->subscribe(strtolower(addslashes($this->get("email"))), $this->get("list_id"));
        }
    }

    function action_unsubscribe()
    {
        foreach ((array)$this->get("emails") as $email => $one) {
            $nl = func_new("NewsSubscriber");
            $nl->unsubscribe(strtolower(addslashes($email)), $this->get("list_id"));
        }
    }

    function action_import_subscribers()
    {
        $this->startDump();
        $file = $this->getUploadedFile();
        if (!is_null($file)) {
            $list_id = $this->get("list_id");
            $ns = func_new("NewsSubscriber");
            $ns->import($list_id, $file);
            echo "<br>Subscribers imported successfully. <a href=\"admin.php?target=news_subscribers&list_id=".$this->get("list_id")."\"><u>Click here to return to admin interface</u></a>";
        } else {
            echo "<font color=red>No uploaded file</font>";
            echo '<br /><br><a href="admin.php?target=news_subscribers&list_id=' . $this->get("list_id") . '"><u>Click here to return to admin interface</u></a>';
            die;

        }
    }

    function getUploadedFile()
    {
        $file = null;
        if (is_uploaded_file($_FILES["userfile"]['tmp_name'])) {
            $file = $_FILES["userfile"]['tmp_name'];
        } elseif (is_readable($_POST["localfile"])) {
            $file = $_POST["localfile"];
        } else {
           return null;
        }
        
        // security check
        $name = $_FILES['userfile']['name'];
        
        if (strstr($name, '../') || strstr($name, '..\\')) {
            return null;
        }
        
        return $file;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
