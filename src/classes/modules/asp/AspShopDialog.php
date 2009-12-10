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
* @package Dialog
* @access public
* @version $Id: AspShopDialog.php,v 1.11 2008/11/27 06:35:06 sheriff Exp $
*/
class AspShopDialog extends Dialog
{
    function init()
    {
        $target = isset($_REQUEST["target"]) ? strtolower($_REQUEST["target"]) : "main";
        $action = isset($_REQUEST["action"]) ? strtolower($_REQUEST["action"]) : "default";
        $mode = isset($_REQUEST["mode"]) ? strtolower($_REQUEST["mode"]) : null;
        if ($this->isDeniedAction($target, $action)) {
            die("<h2 align=center>Your access policy does not allow you to perform this action.</h2><center><a href='javascript:history.go(-1)'>Go back</a></center>");
        }    

        parent::init();
    }

    function isDeniedAction($target, $action, $mode = null)
    {
        // default deny rules
        if ($target == "license" || ($target == "upgrade" && $action != "version") || $target == "test") {
            return true;
        }
        
        global $accessPolicy;

        // check admin zone targets
        if (isset($accessPolicy)) {
            // Import HTML design
            if ($target == "wysiwyg" && $action != "default" && strpos($accessPolicy, "wysiwyg") === false) {
                return true;
            }
            // Web based Advanced Template editor
            elseif ($target == "template_editor" && $action != "default" && $action != "extra_pages" && $action != "advanced" && $action != "advanced_edit" && $action != "page_edit" && strpos($accessPolicy, "template_editor") === false) {
                return true;
            }
            // Backup/Restore database
            elseif ($target == "db" && $action != "default" && strpos($accessPolicy, "db_backup") === false) {
                return true;
            }
            // Backup/Restore shop files
            elseif ($target == "files" && strpos($accessPolicy, "shop_backup") === false) {
                return true;
            }
            // edit CSS
            elseif ($target == "css_edit" && $action == "save" && strpos($accessPolicy, "css_edit") === false) {
                return true;
            }
            // images editing
            elseif ($target == "image_edit" && $action != "default" && strpos($accessPolicy, "image_edit") === false) {
                return true;
            }
            // batch users importing
            elseif ($target == "import_users" && $action != "default" && strpos($accessPolicy, "import_users") === false) {
                return true;
            }
            // Batch product processing
            elseif (($target == "import_catalog" || $target == "export_catalog" || $target == "update_inventory") && $action != "default" && strpos($accessPolicy, "batch_product") === false) {
                return true;
            }
            // Storing images on a file system
            elseif ($target == "image_files" && $action == "move_to_filesystem" && strpos($accessPolicy, "image_files_fs") === false) {
                return true;
            }
            // Storing images in a database
            elseif ($target == "image_files" && $action == "move_to_database" && strpos($accessPolicy, "image_files_db") === false) {
                return true;
            }
        }

        return false;
    }    
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
