<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
* Tabber is a component allowing to organize your dialog into pages and 
* switch between the page using Tabs at the top.
*
* @package View
* @access public
* @version $Id: Tabber.php,v 1.3 2007/05/21 11:53:29 osipov Exp $
*/
class CTabber extends Widget
{
    var $tabPages = "pages"; // name of dialog's array with "tab" => "head"
    var $switch;
    var $template = "common/tabber.tpl";
    var $tabPagesInfo = array();

    function &getPages()
    {
        $pages = array();
        $dialog =& $this->get("dialog");
        $url = $dialog->get("url");
        $dialogPages = $dialog->get($this->get("tabPages"));
        if (is_array($dialogPages)) {
            foreach ($dialogPages as $page => $header) {
                $p =& func_new("Object");
                $pageURL = preg_replace("/$this->switch=(\w+)/", $this->switch."=".$page, $url);
                $p->set("url", $pageURL);
                $p->set("header", $header);
                $page_switch = sprintf("$this->switch=$page"); 
                $p->set("selected", (preg_match("/" . preg_quote($page_switch) . "(\Z|&)/Ss", $url)));
                $pages[] = $p;
            }
        }
        // if there is only one tab page, set it as a seleted with the default URL
        if (count($pages) == 1) {
            $pages[0]->set("selected", $url);
        }
        return $pages;
    }

    function &getSplittedPages($splitParameter=null)
    {
		$pages =& $this->getPages();
		$pagesHeadersTotalLength = 0;
    	foreach($pages as $page) {
			$pagesHeadersTotalLength += strlen($page->header);
    	}

    	if (isset($splitParameter) && intval($splitParameter) > 1) {
    		$splitParameter = intval($splitParameter);

    		$pagesCurrentLength = 0;
    		$pagesNumber = 0;
        	foreach($pages as $page) {
    			$pagesCurrentLength += strlen($page->header);
    			if ($pagesCurrentLength > $splitParameter) {
    				break;
    			}
    			$pagesNumber ++;
        	}

        	$splitParameter = $pagesNumber;

    		$pages = $this->split($pages, $splitParameter);
    		krsort($pages);
    	}

		$pagesHeadersLengthMax = 0;
    	foreach($pages as $page_idx => $pagesArray) {
    		$pagesHeadersLength = 0;
    		foreach($pagesArray as $page) {
    			$pagesHeadersLength += strlen($page->header);
    			if ($pagesHeadersLength > $pagesHeadersLengthMax) {
    				$pagesHeadersLengthMax = $pagesHeadersLength;
    			}
    		}
    		$this->tabPagesInfo[$page_idx] = array("headersLength" => $pagesHeadersLength, "headersLengthMax" => 0, "headersFullness" => 0);
    	}
    	foreach($this->tabPagesInfo as $page_idx => $pagesInfo) {
    		$this->tabPagesInfo[$page_idx]["headersLengthMax"] = $pagesHeadersLengthMax; 
    		$this->tabPagesInfo[$page_idx]["headersFullness"] = ceil($this->tabPagesInfo[$page_idx]["headersLength"]*100/$this->tabPagesInfo[$page_idx]["headersLengthMax"]);
    	}

    	return $pages;
    }

    function isHeaderWider($page_idx, $widthPercents=100)
    {
    	if (!isset($page_idx) || count($this->tabPagesInfo) == 0 || !isset($this->tabPagesInfo[$page_idx])) {
    		return false;
    	}

    	return ($this->tabPagesInfo[$page_idx]["headersFullness"] > $widthPercents) ? true : false;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
