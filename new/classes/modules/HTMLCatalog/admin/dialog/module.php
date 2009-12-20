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
* Admin_Dialog_module_HTMLCatalog description.
*
* @package Module_HTMLCatalog
* @access public
* @version $Id$
*/
class Admin_Dialog_module_HTMLCatalog extends Admin_Dialog_module
{
	function init()
	{
		parent::init();

		if ($this->page == "HTMLCatalog") {
        	$lay =& func_get_instance("Layout");
        	$lay->addLayout("general_settings.tpl", "modules/HTMLCatalog/config.tpl");
        }
	}

    function &getCategories()
    {
       if (is_null($this->categories)) {
            $c =& func_new("Category");
            $this->categories =& $c->findAll();
            $names = array();
            $names_hash = array();
            for ($i=0; $i<count($this->categories); $i++) {
            	$name = $this->categories[$i]->getStringPath();
            	while (isset($names_hash[$name])) {
            		$name .= " ";
            	}
            	$names_hash[$name] = true;
                $names[] = $name;
            }
            array_multisort($names, $this->categories);
        }
        return $this->categories;
    }

	function action_restore()
	{
		$_POST["action"] = "update";
		if (isset($_POST["drop_catalog"])) unset($_POST["drop_catalog"]);
		if (isset($_POST["catalog_category"])) unset($_POST["catalog_category"]);
		$_POST["catalog_pages"] = "both";
		$_POST["catalog_pages_count"] = "20";
		if (isset($_POST["catalog_memory"])) unset($_POST["catalog_memory"]);
		$_POST["category_name_format"] = "category_%cid_%cname%cpage.html";
		$_POST["category_page_format"] = "_page_%page";
		$_POST["product_name_format"] = "product_%pid_%pname_cat_%cid.html";
		$this->mapRequest($_POST);
		$this->action_update();
	}
}

?>
