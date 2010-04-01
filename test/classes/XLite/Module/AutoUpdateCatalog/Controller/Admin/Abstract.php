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
* @package Module_AutoUpdateCatalog
* @access public
* @version $Id$
*
*/
class XLite_Module_AutoUpdateCatalog_Controller_Admin_Abstract extends XLite_Controller_Admin_Abstract implements XLite_Base_IDecorator
{	
	public $isAlreadyRedirected = false;

    function get($name)
    {
        switch(strtolower($name)) {
            case 'returnurl':
                if (!$this->isAlreadyRedirected) {
                    if ($this->auth->isAuthorized($this)) {
                        return $this->getAutoUpdateUrl();
                    }    
                } else {
					return null;
                }
            case 'url':
                if ($this->isAlreadyRedirected) {
        			return $this->url;
                }
        }
        return parent::get($name);
    }

    function init()
    {
        switch ($this->getTarget()) {
            case "product":
            case "add_product":
            case "product_list":
            case "category":
            case "categories":
            case "extra_fields":
            case "global_product_options":
    			if ($this->session->isRegistered("post")) {
                	$this->session->set("post", null);
                }
            break;
        }    
    	parent::init();
    }
    
    function getAutoUpdateUrl()
    {
        $autoUpdate = false;
        switch ($this->target) {
            case "product":
            case "add_product":
            case "product_list":
                $autoUpdate = true;
                $confirm = $this->getComplex('config.AutoUpdateCatalog.confirm_product_update');
            break;

            case "category":
            case "categories":
            case "global_product_options":
                $autoUpdate = true;
                $confirm = $this->getComplex('config.AutoUpdateCatalog.confirm_category_update');
			break;

            case "extra_fields":
            	if ($this->action != "add_field" || ($this->action == "add_field" && $this->enabled != 0)) {
                    $autoUpdate = true;
                    $confirm = $this->getComplex('config.AutoUpdateCatalog.confirm_category_update');
                }
			break;
        }    
        if ($autoUpdate) {
            $mode = $confirm ? "&mode=confirm" : "";
            $action = $confirm ? "" : "&action=update";
            $post = $_POST;
            if (isset($GLOBALS["updateLog"])) {
                // append changelog from Category & Product 
                $post["updateLog"] = $GLOBALS["updateLog"];
            }    
			if (!$this->session->isRegistered("post")) {
            	$this->session->set("post", serialize($post));
            }
        	if ($this->session->isRegistered("processedSteps")) {
        		$this->session->set("processedSteps", null);
        	}
        	if ($this->session->isRegistered("categoriesAlreadyGenerated")) {
        		$this->session->set("categoriesAlreadyGenerated", null);
        	}
        	if ($this->session->isRegistered("productsAlreadyGenerated")) {
        		$this->session->set("productsAlreadyGenerated", null);
        	}
            $this->session->writeClose();
            // auto-update catalog or confirm update
            $url = $this->get("url");
            $this->isAlreadyRedirected = true;
            if ($post["target"] == "product" && $post["action"] == "clone") {
            	$url = str_replace("admin.php?target=product&product_id=".$post["product_id"], parent::get("returnUrl"), $url);
            }
            $this->set("url", $url);
            $this->redirect("admin.php?target=autoupdate_catalog$action$mode&xlite_form_id=".$this->get('xliteFormID')."&returnUrl=" . urlencode($url));
            exit;
        }
        return parent::get("returnUrl");
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
