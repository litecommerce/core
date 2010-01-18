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
* Template editors.
*
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Controller_Admin_TemplateEditor extends XLite_Controller_Admin_Abstract
{
	protected $locale = null;                                                 

    protected $zone = null;
	
    public $params = array('target', 'editor', 'zone');	
    public $editor = "basic";	
    public $basicTemplatesRO = array();	

    public $pages = array(
            'basic' => 'Basic templates',
            'mail' => 'Mail templates',
            'extra_pages' => 'User-defined pages',
            'advanced' => 'Advanced templates'
            );	
    
    public $pageTemplates = array(
            "advanced" => "template_editor/advanced.tpl",
            "extra_pages" => "template_editor/extra_pages.tpl",
            "basic" => "template_editor/basic.tpl",
            "mail" => "template_editor/mail.tpl",
            );	

    public $welcome_page = array(
            "3-columns_classic",
            "3-columns_modern"
            );	

	public $default_templates = array(
			"welcome.tpl",
			"news.tpl",
			"phones.tpl",
			"privacy_statement.tpl",
			"terms_conditions.tpl",
			"checkout/success_message.tpl"
			);

    function getLocale() // {{{
    {
        if (is_null($this->locale)) {
            $this->locale = XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }
        return $this->locale;
    } // }}}

    function getZone() // {{{
    {
        if (is_null($this->zone)) {
            $this->zone = XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        }
        return $this->zone;
    } // }}}
    
    function getTreePages() // {{{
    {
        $zone = $this->getZone();
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
    
    function getUrl($params = null) // {{{
    {
        if ($this->get("editor") != "advanced") {
            $this->set("zone", null);
            $this->set("locale", null);
        }
        return parent::getUrl();
    } // }}}

    // BASIC templates editor methods {{{

    /**
    */
    function getBasicTemplates() // {{{
    {
        $zone   = $this->get("zone");
        $locale = $this->get("locale");
		$templates = array();
		if (is_readable("skins/$zone/$locale/templates.ini")) {
			$templates_ini = parse_ini_file("skins/$zone/$locale/templates.ini", true);
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
			$pages[] = new XLite_Model_FileNode("skins/$zone/$locale/".$name, null, SHOW_FULL_PATH);
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
        $zone   = $this->get("zone");
        $locale = $this->get("locale");
        if ($zone == "mail") {
            $shortcuts = array( new XLite_Model_FileNode("skins/mail/$locale/order_processed/body.tpl", null, SHOW_FULL_PATH), 
                    );
        } elseif ($zone == "admin") {
        } else {
			$shortcuts = array(
				new FileNode("skins/$zone/$locale/main.tpl", null, SHOW_FULL_PATH),
				new FileNode("skins/$zone/$locale/category_products.tpl", null, SHOW_FULL_PATH),
				new FileNode("skins/$zone/$locale/category_subcategories.tpl", null, SHOW_FULL_PATH),
				new FileNode("skins/$zone/$locale/product_details.tpl", null, SHOW_FULL_PATH),
				new FileNode("skins/$zone/$locale/checkout", "Checkout pages", SHOW_FULL_PATH),
				new FileNode("skins/$zone/$locale/common/invoice.tpl", "Invoice form template", SHOW_FULL_PATH),
				new FileNode("skins/$zone/$locale/common/print_invoice.tpl", "Printable Invoice form template", SHOW_FULL_PATH),
				new FileNode("cart.html", null, SHOW_FULL_PATH),
				new FileNode("shop_closed.html", "Shop is closed warning template", SHOW_FULL_PATH),
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
            $t = new XLite_Model_FileNode($path);
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

    function getMailTemplates()
    {
        $node = $_REQUEST["node"];
        $path = $_REQUEST["path"];
        $data["subject"] = new XLite_Model_FileNode("$node/subject.tpl");
        $data["body"] = new XLite_Model_FileNode("$node/body.tpl");
        $data["signature"] = new XLite_Model_FileNode("$path/signature.tpl");
        return $data;
    }

    function action_update_mail()
    {
    	$writePermitted = false;
        $node = $_POST["node"];
        $path = $_POST["path"];
        $s = new XLite_Model_FileNode("$node/subject.tpl");
        $s->set("content", $_POST["subject"]);
        $s->update();
        if ($s->writePermitted) {
			$writePermitted = true;
        	$this->set("subjectWriteError", true);
        }
        $b = new XLite_Model_FileNode("$node/body.tpl");
        $b->set("content", $_POST["body"]);
        $b->update();
        if ($b->writePermitted) {
			$writePermitted = true;
        	$this->set("bodyWriteError", true);
        }
        $sig = new XLite_Model_FileNode("$path/signature.tpl");
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

    function getExtraPage() // {{{
    {
        if (is_null($this->extraPage)) {
            $this->extraPage = new XLite_Model_ExtraPage();
            if (isset($_REQUEST["page"]) && !empty($_REQUEST["page"])) {
                $this->extraPage = $this->extraPage->findPage($_REQUEST["page"]);
            }    
        }
        return $this->extraPage;
    } // }}}
    
    function getExtraPages() // {{{
    {
        if (is_null($this->extraPages)) {
            $this->extraPages = $this->get("extraPage.pages");
        }    
        return $this->extraPages;
    } // }}}

    function action_reupdate_pages() 
    {
    	$this->getExtraPages();
    	if (is_array($this->extraPages)) {
			foreach ($this->extraPages as $ep) {
				$page = $ep->get("page");
				$title = $ep->get("title");
				$content = $ep->get("template.content");

				$_REQUEST["page"] = $page;
				$this->extraPage = null;
				$this->getExtraPage();
				$this->action_page_remove();

				$_REQUEST["page"] = "";
				$_POST["page"] = "";
				$_POST["title"] = $title;
				$_POST["content"] = $content;
				$this->extraPage = null;
				$this->getExtraPage();
				$this->action_update_page();
			}
    	}
    }

    function action_update_page() // {{{
        {
        $page = trim($_POST["page"]);
        $this->extraPage = new XLite_Model_ExtraPage();
        $this->set("extraPage.page", $page);
        $this->set("extraPage.title", trim($_POST["title"]));
        $this->set("extraPage.content", trim($_POST["content"]));
        if ($this->new_page) {
            $this->extraPage->add();
        } else {
            $this->extraPage->modify();
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
        $extraPage = $this->get("extraPage");
        if (!is_null($extraPage) && is_object($extraPage)) {
            $extraPage->remove();
        }
    } // }}}

    // }}}

    // ADVANCED templates editor methods // {{{

    function getFile() // {{{
    {
        $path = isset($_REQUEST["file"]) ? $_REQUEST["file"] : null;
        $file = new XLite_Model_FileNode($path);
        if (isset($_REQUEST["content"])) {
            $file->set("content", $_REQUEST["content"]);
        }
        return $file;
    } // }}}

    function action_advanced_update() // {{{
    {
        $file = $this->get("file");
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
        $file = new XLite_Model_FileNode($_REQUEST["selected_file"]);
        $file->remove();
        $this->afterAdvanced();
    } // }}}

    function action_copy() // {{{
    {   
        $file = new XLite_Model_FileNode($_REQUEST["selected_file"]);
        $basename = dirname($file->path);
        $file->set("newPath", $basename . "/" . $_REQUEST["new_name"]);
        $file->copy();
        $this->afterAdvanced();
    } // }}}

    function action_rename() // {{{
    {   
        $file = new XLite_Model_FileNode($_REQUEST["selected_file"]);
        $basename = dirname($file->path);
        $file->set("newPath", $basename . "/" . $_REQUEST["new_name"]);
        $file->rename();
        $this->afterAdvanced();
    } // }}}

    function action_new_file() // {{{
    {
        $file = $this->get("file");
        $path   = $file->get("path");
        $zone   = $this->get("zone");
        $locale = $this->get("locale");
        if ($file->get("node") == ".") {
            $path = (is_null($this->node) || $this->node == "") ?
                                   "skins/$zone/$locale/$path" :
                                            "$this->node/$path";
            $file->set("path", $path);
        }
        $file->create();
        $this->set("returnUrl", $this->get("url") . "&mode=edit&file=" . $path);
    } // }}}

    function action_new_dir() // {{{
    {   
        $file = $this->get("file");
        $path   = $file->get("path");
        $zone   = $this->get("zone");
        $locale = $this->get("locale");
        if ($file->get("node") == ".") {
            $path = (is_null($this->node) || $this->node == "") ? 
                                   "skins/$zone/$locale/$path" :
                                            "$this->node/$path";
            $file->set("path", $path);
        }
        $file->createDir();
        $this->afterAdvanced();
    } // }}}

    function action_restore_all() // {{{
    {
        $file = $this->get("file");
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
        $file = $this->get("file");
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
