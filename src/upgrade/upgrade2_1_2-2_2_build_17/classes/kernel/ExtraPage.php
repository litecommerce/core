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
* @version $Id: ExtraPage.php,v 1.1 2006/07/11 06:38:21 sheriff Exp $
*/
class ExtraPage extends Object
{
    var $templatePrefix; // = "skins/$zone/en/";
    var $page; // a page identifier
    var $title;
    var $content; // a page content
    var $tamplate; // the page's template
    var $pageLine = "<widget template=\"common/dialog.tpl\" body=\"%s.tpl\" head=\"%s\" visible=\"{page=#%s#}\">";

    var $isRead = false;

    function constructor()
    {
        parent::constructor();
        $this->pagesTemplate = $this->get("templatePrefix") . "pages.tpl";
        $this->locationTemplate = $this->get("templatePrefix") . "location.tpl";
        $this->menuTemplate = $this->get("templatePrefix") . "help/body.tpl";
        $this->linksTemplate = $this->get("templatePrefix") . "pages_links.tpl";
    }
    
    function &getZone()
    {
        if (is_null($this->zone)) {
            $this->zone = $this->get("xlite.options.skin_details.skin");
        }
        return $this->zone;
    }

    function &getTemplatePrefix()
    {
        if (is_null($this->templatePrefix)) {
            $zone = $this->get("zone");
            $this->templatePrefix = "skins/$zone/en/";
        }
        return $this->templatePrefix;
    }

    /**
    * Returns an array of ExtraPage.
    */
    function getPages()
    {
        $pages = array();
        foreach (file($this->pagesTemplate) as $line) {
            //
            // Example:
            //   <widget template="common/dialog.tpl" body="test_page.tpl" head="Test page" visible="{page=#test_page#}">
            //
            // OLD: if (preg_match("/{([^.]*)\.display\(#([^#]*)#\)/", $line, $matches)) {
            if (preg_match("/<widget (\S+) body=\"(\w+)\.tpl\" head=\"([^\"]+)\" (\S+)/", $line, $matches)) {
                
                list($line, $template, $page, $title) = $matches;
                $p = func_new("ExtraPage");
                $p->page = $page;
                $p->title = $title;
                $p->template = func_new("Template",$page . ".tpl");
                $pages[] = $p;
            }
        }
        return $pages;
    }

    /**
    * Find a page by ID
    */
    function &findPage($id)
    {
        foreach ($this->getPages() as $page) {
            if ($page->page == $id) {
                return $page;
            }
        }
    }

    /**
    * Add the extra page.
    */
    function add()
    {
        $this->page = $this->_createUniqueName($this->title);
		$this->title = preg_replace("/([^&]|^)#/S", "\\1 ", $this->title);
        $this->addLine("<span IF=\"target=#main#&page=#$this->page#\" class=\"NavigationPath\">&nbsp;::&nbsp;$this->title</span>", $this->locationTemplate);
        $this->addLine(sprintf($this->pageLine, $this->page, $this->title, $this->page), $this->pagesTemplate);
        $this->addLine("<FONT class=\"SidebarItems\"><a href=\"cart.php?page=$this->page\" class=\"SidebarItems\">$this->title</a></FONT><br>", $this->menuTemplate);
        $this->addLine("| <a href=\"cart.php?page=$this->page\"><font class=\"BottomMenu\">$this->title</font></a>", $this->linksTemplate);
        $this->createFile($this->get("templatePrefix") . $this->page . ".tpl", $this->content);
    }

    /**
    * Modify the extra page.
    */
    function modify()
    {
        $this->title = strtr($this->title, "#", " ");
        $this->replaceLine("/IF=\"target=#main#&page=#$this->page#\"/", "<span IF=\"target=#main#&page=#$this->page#\" class=\"NavigationPath\">&nbsp;::&nbsp;$this->title</span>", $this->locationTemplate);
        $this->replaceLine("/body=\"$this->page\.tpl\"/", sprintf($this->pageLine, $this->page, $this->title, $this->page), $this->pagesTemplate);
        $this->replaceLine("/cart\\.php\\?page=$this->page\"/", "<FONT class=\"SidebarItems\"><a href=\"cart.php?page=$this->page\" class=\"SidebarItems\">$this->title</a></FONT><br>", $this->menuTemplate);
        $this->replaceLine("/cart\\.php\\?page=$this->page\"/", "| <a href=\"cart.php?page=$this->page\"><font class=\"BottomMenu\">$this->title</font></a>", $this->linksTemplate);
        $this->createFile($this->get("templatePrefix") . $this->page . ".tpl", $this->content);
    }

    function remove()
    {
        $this->replaceLine("/IF=\"target=#main#&page=#$this->page#\"/", "", $this->locationTemplate);
        $this->replaceLine("/body=\"$this->page\.tpl\"/", "", $this->pagesTemplate);
        $this->replaceLine("/cart\\.php\\?page=$this->page\"/", "", $this->menuTemplate);
        $this->replaceLine("/cart\\.php\\?page=$this->page\"/", "", $this->linksTemplate);
        @unlink($this->get("templatePrefix") . $this->page . ".tpl");
    }   

    function addLine($line, $file)
    {
        $c = file_get_contents($file);
        if (substr($c, -1) != "\n") {
            $line = "\n$line";
        }
        $fd = fopen($file, "ab");
        fwrite($fd, $line . "\n");
        fclose($fd);
        @chmod($file, 0666);
    }

    function replaceLine($pattern, $line, $file)
    {
        $lines = file($file);
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
        // write the file
        $this->createFile($file, join('', $lines));
    }

    function createFile($file, $content = '')
    { 
        $fd = fopen($file, "wb");
        fwrite($fd, str_replace("\r", '', $content));
        fclose($fd);
        @chmod($file, 0666);
    }    
    
    function _createUniqueName($title)
    {
        $name = strtolower($title);
        $name = preg_replace("/[^a-zA-Z0-9_]/", "_", $name);
        $name = preg_replace("/^([0-9])/", "_\\1", $name);
        
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
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
