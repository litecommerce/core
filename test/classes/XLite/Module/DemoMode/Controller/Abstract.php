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
*
* @package Module_DemoMode
* @access public
* @version $Id$
*/
class XLite_Module_DemoMode_Controller_Abstract extends XLite_Controller_Abstract implements XLite_Base_IDecorator
{
	// FIXME
    function init()
    {
        $target = isset($_REQUEST["target"]) ? strtolower($_REQUEST["target"]) : "main";
        $action = isset($_REQUEST["action"]) ? strtolower($_REQUEST["action"]) : "default";
        if ($this->isDeniedAction($target, $action) && !$this->session->get("superUser")) {
            $this->redirect(XLite::ADMIN_SELF . "?target=demo_mode");
            die();
        }

        parent::init();
    }

    function isDeniedAction($target, $action)
    {
        return
        (
            $target == "catalog" && $action == "build" ||
            $target == "catalog" && $action == "clear" ||
            $target == "users" && $action == "delete" ||
            (($target == "category" || $target == "categories") && ($action == "delete" || $action == "delete_all")) ||
            $target == "wysiwyg" && $action != "default" ||
            $target == "import_catalog" && $action == "import_products" && isset($_REQUEST["delete_products"]) ||
            $target == "profile" && $action == "delete" ||
            $target == "db" && $action != "default" ||
			$target == "image_files" && $action != "default" ||
			$target == "image_edit" && $action != "default" ||
			$target == "css_edit" && $action == "save" ||
			$target == "css_edit" && $action == "restore_default" ||
			$target == "xcart_import" && $action != "default" || 
			$target == "files" || $target == "test" ||
            $target == "advanced_security" && $action != "default" ||
            $target == "template_editor" && $action != "default" && $action != "extra_pages" && $action != "advanced" && $action != "advanced_edit" && $action != "page_edit" ||
            ($target == "modules" && ($action == "install" || $action == "uninstall")) ||
            ($target == "module" && $action == "update" && $_REQUEST["page"] == "Egoods") ||
            ($target == "settings" && $action == "phpinfo") ||
            ($target == "ups_online_tool" && $action == "next" && $this->session->get("ups_step") == 2)
    	);
    }

    protected function redirect($url = null)
    {
        if (!$this->xlite->is("adminZone")) {
            $forward = $this->xlite->session->get("forwardUrl");
            if (isset($forward)) {
        		$currentUrl = $this->getUrl();
        		if (strpos($currentUrl, $forward) === false) {
                    $this->xlite->session->set("forwardUrl", null);
                    $this->xlite->session->writeClose();
        		}
        	}
        }

        parent::redirect($url);
    }
}
