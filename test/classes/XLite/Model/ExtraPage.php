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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* ExtraPage allows to add, edit, remove and display extra pages;
* The pages are availabale under the following URL:
*   cart.php?page=PAGE_NAME. 
* The class contains means for automatically generate PAGE_NAME from its title
* by replacing invalid characters with underscore '_'.
* Each page adds the following information about itself:
* 1. a file PAGE_NAME.tpl in skins/$zone/en, containing the page's content;
* 2. an optional link in the "Help" zone;
* 3. a location title in the location.tpl template;
* 4. an entry in the pages.tpl file, specifying its dialog title;
*
* A list of current extra pages is obtained from pages.tpl.
*
* @package kernel
* @access public
* @version $Id$
*/
class XLite_Model_ExtraPage extends XLite_Base
{
    var $templatePrefix; // = "skins/$zone/en/";
    var $page; // a page identifier
    var $title;
    var $content; // a page content
    var $template; // the page's template
    var $pageLine = "<widget template=\"common/dialog.tpl\" body=\"%s.tpl\" head=\"%s\" visible=\"{page=#%s#}\">";

    var $isRead = false;

	protected $zone = null;

	protected $locale = null;

    public function __construct()
    {
        parent::__construct();
        $this->pagesTemplate = $this->get("templatePrefix") . "pages.tpl";
        $this->locationTemplate = $this->get("templatePrefix") . "location.tpl";
        //$this->menuTemplate = $this->get("templatePrefix") . "help/body.tpl";
        $this->menuTemplate = $this->get("templatePrefix") . "help/pages_links.tpl";
        $this->menuTemplateDef = $this->get("templatePrefix") . "help/pages_links_def.tpl";
        $this->linksTemplate = $this->get("templatePrefix") . "pages_links.tpl";
    }

    function getLocale() // {{{
    {
        if (is_null($this->locale)) {
            $this->locale = $this->get("xlite.options.skin_details.locale");
        }
        return $this->locale;
    } // }}}

    function getZone()
    {
        if (is_null($this->zone)) {
            $this->zone = $this->get("xlite.options.skin_details.skin");
        }
        return $this->zone;
    }

    function getTemplatePrefix()
    {
        if (is_null($this->templatePrefix)) {
            $zone   = $this->get("zone");
            $locale = $this->get("locale");
            $this->templatePrefix = "skins/$zone/$locale/";
        }
        return $this->templatePrefix;
    }

    /**
    * Returns an array of ExtraPage.
    */
    function getPages()
    {
        $pages = array();
        $pagesList = @file($this->pagesTemplate);
        if (is_array($pagesList)) {
            foreach (file($this->pagesTemplate) as $line) {
                //
                // Example:
                //   <widget template="common/dialog.tpl" body="test_page.tpl" head="Test page" visible="{page=#test_page#}">
                //
                // OLD: if (preg_match("/{([^.]*)\.display\(#([^#]*)#\)/", $line, $matches)) {
                if (preg_match("/<widget (\S+) body=\"(\w+)\.tpl\" head=\"([^\"]+)\" (\S+)/", $line, $matches)) {
                    
                    list($line, $template, $page, $title) = $matches;
                    $p = new XLite_Model_ExtraPage();
                    $p->page = $page;
                    $p->title = func_htmldecode($title);
                    $fd = @fopen($this->get("templatePrefix") . $page . ".tpl", "rb");
                    if ($fd) {
                    	fclose($fd);
                    	$p->template = new XLite_Model_Template($page . ".tpl");
                    }
                    $pages[] = $p;
                }
            }
        }
        return $pages;
    }

    /**
    * Find a page by ID
    */
    function findPage($id)
    {
        foreach ($this->getPages() as $page) {
            if ($page->page == $id) {
                return $page;
            }
        }
    }

    function getPageLinkAttributes()
    {
    	return array("page_link", "page_title");
    }

    function getPageLinkContent($tpl = null)
    {
        $title = htmlspecialchars($this->title);
        $title = preg_replace("/{/S", "&#123;", $title);
        $title = preg_replace("/}/S", "&#125;", $title);
        switch($tpl) {
            case "location":
                $line = "<span IF=\"target=#main#&page=#$this->page#\" class=\"NavigationPath\">&nbsp;::&nbsp;$title</span>";
                break;
            case "pages":
                $line = sprintf($this->pageLine, $this->page, $title, $this->page);
                break;
            case "menu":
                $this->getCustomerLayout();
                $template = new XLite_Base();
                $template->set("template", $this->getRelativeTemplatePath($this->menuTemplateDef));
                $template->set("skinPath", $this->customerLayout->getPath());
                $template->set("page_link", "cart.php?page=" . $this->page);
                $template->set("page_title", $this->title);
                $line = $this->compile($template);
                $line = preg_replace("/{/S", "&#123;", $line);
                $line = preg_replace("/}/S", "&#125;", $line);
                break;
            case "links":
                $line = "| <a href=\"cart.php?page=$this->page\"><font class=\"BottomMenu\">$title</font></a>";
                break;
            default:
                $line = "";
                break;
        }
		return $line;
    }

    function getPageLinkPattern($tpl = null)
    {
        switch($tpl) {
            case "location":
                $line = "/IF=\"target=#main#&page=#$this->page#\"/";
                break;
            case "pages":
                $line = "/body=\"$this->page\.tpl\"/";
                break;
            case "menu":
                $line = "/cart\\.php\\?page=$this->page\"/";
                break;
            case "links":
                $line = "/cart\\.php\\?page=$this->page\"/";
                break;
            default:
                $line = "";
                break;
        }
		return $line;
    }

    /**
    * Add the extra page.
    */
    function add()
    {
        $this->page = $this->_createUniqueName($this->title, trim($this->page));
        $this->addLine($this->getPageLinkContent("location"), $this->locationTemplate);
        $this->addLine($this->getPageLinkContent("pages"), $this->pagesTemplate);
        $this->addLine($this->getPageLinkContent("menu"), $this->menuTemplate);
        $this->addLine($this->getPageLinkContent("links"), $this->linksTemplate);
        $this->createFile($this->get("templatePrefix") . $this->page . ".tpl", $this->content);
    }

    /**
    * Modify the extra page.
    */
    function modify()
    {
        $this->replaceLine($this->getPageLinkPattern("location"), $this->getPageLinkContent("location"), $this->locationTemplate);
        $this->replaceLine($this->getPageLinkPattern("pages"), $this->getPageLinkContent("pages"), $this->pagesTemplate);
        $this->replaceLine($this->getPageLinkPattern("menu"), $this->getPageLinkContent("menu"), $this->menuTemplate);
        $this->replaceLine($this->getPageLinkPattern("links"), $this->getPageLinkContent("links"), $this->linksTemplate);
        $this->createFile($this->get("templatePrefix") . $this->page . ".tpl", $this->content);
    }

    function remove()
    {
        $this->replaceLine($this->getPageLinkPattern("location"), "", $this->locationTemplate);
        $this->replaceLine($this->getPageLinkPattern("pages"), "", $this->pagesTemplate);
        $this->replaceLine($this->getPageLinkPattern("menu"), "", $this->menuTemplate);
        $this->replaceLine($this->getPageLinkPattern("links"), "", $this->linksTemplate);
        @unlink($this->get("templatePrefix") . $this->page . ".tpl");
    }   

    function addLine($line, $file)
    {
        $c = @file_get_contents($file);
        if (substr($c, -1) != "\n") {
            $line = "\n$line";
        }
        $fd = @fopen($file, "ab");
        if ($fd) {
            fwrite($fd, $line . "\n");
            fclose($fd);
            @chmod($file, get_filesystem_permissions(0666));
        }
    }

    function replaceLine($pattern, $line, $file)
    {
        $lines = @file($file);
        if (is_array($lines)) {
            // find a line 
            for ($i=0; $i<count($lines); $i++) {
                if (preg_match($pattern, $lines[$i])) {
                    // replace and stop looking
                    $lines[$i] = $line . "\n";
                    if ($line === '') {
                    	if (isset($lines[$i])) {
                        	unset($lines[$i]); // remove the line
                        }
                    }
                    break;
                }
            }
            $lines = join('', $lines);
        }
        // write the file
        $this->createFile($file, $lines);
    }

    function createFile($file, $content = '')
    { 
		$this->writePermitted = false;
        $fd = @fopen($file, "wb");
        if ($fd) {
        	fwrite($fd, str_replace("\r", '', $content));
        	fclose($fd);
        	@chmod($file, get_filesystem_permissions(0666));
        } else {
        	$this->writePermitted = true;
        }
    }    
    
    function _createUniqueName($title, $pname = "")
    {
        if ($pname == "") {
            $name = strtolower($title);
            $name = preg_replace("/[^a-zA-Z0-9_]/", "_", $name);
            $name = preg_replace("/^([0-9])/", "_\\1", $name);
        } else {
            $name = $pname;
        }    
        $pages = $this->getPages(); 
        // ensure the page is unique
        $suff = '';
        do {
            $found = false;
            foreach ($pages as $page) {
                if ($page->page == $name.$suff) {
                    $found = true;
                }
            }
            if ($found) {
                $suff = $suff + 1;
            }
        } while ($found);
        return $name.$suff;
    }

	function getCustomerLayout()
	{
		if (!is_null($this->customerLayout)) {
			return $this->customerLayout;
		}

        $this->customerLayout = new XLite_Model_Layout();
		$this->xlite->set("adminZone", false);
        $this->customerLayout->initFromGlobals();
		$this->xlite->set("adminZone", true);

		return $this->customerLayout;
    }

    function compile($template)
    {
        // replace layout with customer layout
     	$layout = XLite_Model_Layout::getInstance();
        $skin = $layout->get("skin");
        $layout->set("skin", $this->customerLayout->get("skin"));

        $component = new XLite_View();
        
        $component->template = $template->get("template");
        $component->init();
        $component->set("data", $template);

        $attributes = $this->getPageLinkAttributes();
        foreach ($attributes as $attr) {
			$component->set($attr, $template->get($attr));
        }

        ob_start();
        $component->display();
        $text = trim(ob_get_contents());
        ob_end_clean();

        // restore old skin
        $layout->set("skin", $skin);
            
        return $text;
    }

	function getRelativeTemplatePath($file)
	{
		$skin_details = $this->xlite->get("options.skin_details");
		return str_replace("skins/" . $skin_details->get("skin") . "/" . $skin_details->get("locale") . "/", "", $file);
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
