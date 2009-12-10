<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
* Template editors.
*
* @package Dialog
* @access public
* @version $Id: template_editor.php,v 1.2 2006/08/23 10:13:00 sheriff Exp $
*
*/
class Admin_Dialog_template_editor extends Admin_Dialog
{
    var $params = array('target', 'editor', 'zone');
    var $editor = "basic";
    var $basicTemplatesRO = array();

    var $pages = array(
            'basic' => 'Basic templates',
            'mail' => 'Mail templates',
            'extra_pages' => 'User-defined pages',
            'advanced' => 'Advanced templates'
            );
    
    var $pageTemplates = array(
            "advanced" => "template_editor/advanced.tpl",
            "extra_pages" => "template_editor/extra_pages.tpl",
            "basic" => "template_editor/basic.tpl",
            "mail" => "template_editor/mail.tpl",
            );

    var $welcome_page = array(
            "3-columns_classic",
            "3-columns_modern"
            );

	var $default_templates = array(
			"welcome.tpl",
			"news.tpl",
			"phones.tpl",
			"privacy_statement.tpl",
			"terms_conditions.tpl",
			"checkout/success_message.tpl"
			);

    function &getZone() // {{{
    {
        if (is_null($this->zone)) {
            $this->zone = $this->get("xlite.options.skin_details.skin");
        }
        return $this->zone;
    } // }}}
    
    function &getTreePages() // {{{
    {
        $zone = $this->get("xlite.options.skin_details.skin");
        if (is_null($this->treePages)) {
            $this->treePages = array(
                    $zone => "Customer zone",
                    "mail" => "E-mail templates",
                    "admin" => "Admin zone"
                    );
        }
        return $this->treePages;
    } // }}}

    function getPageTemplate() // {{{
    {
        if (isset($this->pageTemplates[$this->get("editor")])) {
            return $this->pageTemplates[$this->get("editor")];
        }
        return null;
    } // }}}
    
    function &getUrl() // {{{
    {
        if ($this->get("editor") != "advanced") {
            $this->set("zone", null);
        }
        return parent::getUrl();
    } // }}}

    // BASIC templates editor methods {{{

    /**
    */
    function &getBasicTemplates() // {{{
    {
        $zone = $this->get("zone");
		$templates = array();
		if (is_readable("skins/$zone/en/templates.ini")) {
			$templates_ini = parse_ini_file("skins/$zone/en/templates.ini", true);
			foreach ($templates_ini as $key=>$val) {
				$filename = trim($val["filename"]);
				if (!empty($filename))
					$templates[] = $filename;
			}
		} else {
			$templates = $this->default_templates;
		}

		$pages = array();
		foreach ($templates as $name) {
			$pages[] = func_new("FileNode", "skins/$zone/en/".$name, null, SHOW_FULL_PATH);
		}

		if ($this->get("valid") == false) {
		    foreach($pages as $page_idx => $page) {
		    	if (isset($this->basicTemplatesRO[$page->path]) && $this->basicTemplatesRO[$page->path]) {
		    		$pages[$page_idx]->set("read_only_access", true);
		    	}
		    }
		}

		return $pages;
    } // }}}

    function getShortcuts() // {{{
    {
        $shortcuts = array();
        $zone = $this->get("zone");
        if ($zone == "mail") {
            $shortcuts = array(
                    func_new("FileNode","skins/mail/en/order_processed/body.tpl", null, SHOW_FULL_PATH), 
                    );
        } elseif ($zone == "admin") {
        } else {
            $shortcuts = array(
                    func_new("FileNode","skins/$zone/en/main.tpl", null, SHOW_FULL_PATH), 
                    func_new("FileNode","skins/$zone/en/category_products.tpl", null, SHOW_FULL_PATH), 
                    func_new("FileNode","skins/$zone/en/category_subcategories.tpl", null, SHOW_FULL_PATH), 
                    func_new("FileNode","skins/$zone/en/product_details.tpl", null, SHOW_FULL_PATH), 
                    func_new("FileNode","skins/$zone/en/checkout", "Checkout pages", SHOW_FULL_PATH), 
                    func_new("FileNode","skins/$zone/en/common/invoice.tpl", "Invoice form template", SHOW_FULL_PATH), 
                    func_new("FileNode","skins/$zone/en/common/print_invoice.tpl", "Printable Invoice form template", SHOW_FULL_PATH), 
                    func_new("FileNode","cart.html", null, SHOW_FULL_PATH), 
                    func_new("FileNode","shop_closed.html", "Shop is closed warning template", SHOW_FULL_PATH), 
                    );
        }    
        return $shortcuts;
    } // }}}

    /**
    * Updates basic templates from the "Basic templates" tab
    */
    function action_update_templates() // {{{
    {
        foreach ($_POST["template"] as $path => $content) {
            $t =& func_new("FileNode", $path);
            $t->set("content", $content);
            $t->update();
        	if ($t->writePermitted) {
				$this->set("valid", false);
				$this->basicTemplatesRO[$path] = true;
        	}
        }
    } // }}}

    // }}}

    // MAIL templates editor methods {{{

    function &getMailTemplates()
    {
        $node = $_REQUEST["node"];
        $path = $_REQUEST["path"];
        $data["subject"] =& func_new("FileNode", "$node/subject.tpl");
        $data["body"] =& func_new("FileNode", "$node/body.tpl");
        $data["signature"] =& func_new("FileNode", "$path/signature.tpl");
        return $data;
    }

    function action_update_mail()
    {
    	$writePermitted = false;
        $node = $_POST["node"];
        $path = $_POST["path"];
        $s =& func_new("FileNode", "$node/subject.tpl");
        $s->set("content", $_POST["subject"]);
        $s->update();
        if ($s->writePermitted) {
			$writePermitted = true;
        	$this->set("subjectWriteError", true);
        }
        $b =& func_new("FileNode", "$node/body.tpl");
        $b->set("content", $_POST["body"]);
        $b->update();
        if ($b->writePermitted) {
			$writePermitted = true;
        	$this->set("bodyWriteError", true);
        }
        $sig =& func_new("FileNode", "$path/signature.tpl");
        $sig->set("content", $_POST["signature"]);
        $sig->update();
        if ($sig->writePermitted) {
			$writePermitted = true;
        	$this->set("signatureWriteError", true);
        }

        if ($writePermitted) {
			$this->set("mode", "edit");
			$this->set("valid", false);
		}
    }
    // }}}
    
    // USER-DEFINED pages editor methods {{{

    function &getExtraPage() // {{{
    {
        if (is_null($this->extraPage)) {
            $this->extraPage =& func_new("ExtraPage");
            if (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) {
                $this->extraPage = $this->extraPage->findPage($_REQUEST["page"]);
            }    
        }
        return $this->extraPage;
    } // }}}
    
    function &getExtraPages() // {{{
    {
        if (is_null($this->extraPages)) {
            $this->extraPages = $this->get("extraPage.pages");
        }    
        return $this->extraPages;
    } // }}}

    function action_update_page() // {{{
    {
        $page = $_POST["page"];
        $this->set("extraPage.title", trim($_POST["title"]));
        $this->set("extraPage.content", trim($_POST["content"]));
        if ($page) {
            $this->set("extraPage.page", $page);
            $this->extraPage->modify();
        } else {
            $this->extraPage->add();
        }

        if ($this->extraPage->writePermitted) {
			$this->set("valid", false);
			$this->set("read_only_access", true);
        	if (!$page) {
				$this->extraPage->remove();
				$this->extraPage = null;
			} else {
				$this->set("mode", "page_edit");
			}
			$this->extraPages = null;
		}
    } // }}}

    function action_page_remove() // {{{
    {
        $extraPage =& $this->get("extraPage");
        $extraPage->remove();
    } // }}}

    // }}}

    // ADVANCED templates editor methods // {{{

    function &getFile() // {{{
    {
        $path = isset($_REQUEST["file"]) ? $_REQUEST["file"] : null;
        $file =& func_new("FileNode", $path);
        if (isset($_REQUEST["content"])) {
            $file->set("content", $_REQUEST["content"]);
        }
        return $file;
    } // }}}

    function action_advanced_update() // {{{
    {
        $file =& $this->get("file");
		$file->update();
        $this->afterAdvanced();
		$this->set("returnUrl", $this->get("url") . "&mode=edit&file=" . $file->get("path"));
		if ($file->writePermitted) {
			$this->set("valid", false);
			$this->set("mode", "edit");
			$this->set("error", "writePermitted");
		}
    } // }}}
    
    function action_remove() // {{{
    {
        $file =& func_new("FileNode", $_REQUEST["selected_file"]);
        $file->remove();
        $this->afterAdvanced();
    } // }}}

    function action_copy() // {{{
    {   
        $file =& func_new("FileNode", $_REQUEST["selected_file"]);
        $basename = dirname($file->path);
        $file->set("newPath", $basename . "/" . $_REQUEST["new_name"]);
        $file->copy();
        $this->afterAdvanced();
    } // }}}

    function action_rename() // {{{
    {   
        $file =& func_new("FileNode", $_REQUEST["selected_file"]);
        $basename = dirname($file->path);
        $file->set("newPath", $basename . "/" . $_REQUEST["new_name"]);
        $file->rename();
        $this->afterAdvanced();
    } // }}}

    function action_new_file() // {{{
    {
        $file =& $this->get("file");
        $path = $file->get("path");
        $zone = $this->get("zone");
        if ($file->get("node") == ".") {
            $path = (is_null($this->node) || $this->node == "") ?
                                   "skins/$zone/en/$path" :
                                            "$this->node/$path";
            $file->set("path", $path);
        }
        $file->create();
        $this->set("returnUrl", $this->get("url") . "&mode=edit&file=" . $path);
    } // }}}

    function action_new_dir() // {{{
    {   
        $file =& $this->get("file");
        $path = $file->get("path");
        $zone = $this->get("zone");
        if ($file->get("node") == ".") {
            $path = (is_null($this->node) || $this->node == "") ? 
                                   "skins/$zone/en/$path" :
                                            "$this->node/$path";
            $file->set("path", $path);
        }
        $file->createDir();
        $this->afterAdvanced();
    } // }}}

    function action_restore_all() // {{{
    {
        $file =& $this->get("file");
        $file->set("path", "skins_original");
        $file->set("newPath", "skins");
        $file->copy();
		$file->set("path", sprintf("schemas/templates/%s",$this->config->get("Skin.skin")));
        $file->set("newPath", "skins");
        $file->copy();
        $this->afterAdvanced();
    } // }}}

    function action_restore() // {{{
    {
        $file =& $this->get("file");
        $to = $_REQUEST["selected_file"];
		$schema_file = preg_replace("/^(skins)/", sprintf("schemas/templates/%s",$this->config->get("Skin.skin")), $to);
		$from = (file_exists($schema_file) ? $schema_file : preg_replace("/^(skins)/", "skins_original", $to));
        copyRecursive($from, $to);
        $this->afterAdvanced();
    } // }}}

    function afterAdvanced() // {{{
    {
        if (!is_null($this->node) && trim($this->node) != "") {
            $this->set("returnUrl", $this->get("url") . "&node=$this->node");
        }
    } // }}} 

    // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
