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
class Admin_Dialog_update_inventory extends Admin_dialog
{
    var $params = array('target', 'page');
    var $pages = array('pricing' => 'Update pricing');
    var $pageTemplates = array('pricing' => 'product/update_inventory.tpl');
    var $page = "pricing";                   

    function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            @set_time_limit(0);
        }            
        $handler = "handleRequest$this->page";
        method_exists($this, $handler) or die("undefined handler $handler");
        $this->$handler();

        if ($this->action == "import" && !$this->checkUploadedFile()) {
        	$this->set("valid", false);
        	$this->set("invalid_file", true);
        }

        parent::handleRequest();
    }

    function handleRequestPricing()
    {
        $this->inventory =& func_get_instance("ProductInventory"); 
    }

    function action_export()
    {
        $method = "export_$this->what";
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    function action_layout($layout)
    {
        // save/update layout
        $dlg =& func_new("Admin_Dialog_import_catalog");
        $dlg->action_layout($layout);
    }

    function export_pricing()
    {
        global $DATA_DELIMITERS;
        
        // save layout
        $this->action_layout("inventory_layout");
        // export
        $this->startDownload("product_pricing.csv");
        $this->inventory->export($this->inventory_layout, $DATA_DELIMITERS[$this->delimiter]);
        exit();
    }

    function action_import()
    {
        $method = "import_$this->what";
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    function import_pricing()
    {
        $this->startDump();
        $options["file"] =  $this->getUploadedFile();
        $options["delimiter"] = $this->delimiter;
        $options["text_qualifier"] = $this->text_qualifier;
        $options["layout"] = $this->inventory_layout;
		$options["return_error"] = true;
        $this->inventory->import($options);
		$this->importError = $this->inventory->importError;

		$text = "Import process failed.";
		if (!$this->importError) $text = "Product pricing imported successfully.";
		$text = $this->importError.'<br>'.$text.' <a href="admin.php?target=update_inventory"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
		exit();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
