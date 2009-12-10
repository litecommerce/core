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
* Displays the top of main mape.
*
* @package View
* @access public
* @version $Id: Pager.php,v 1.3 2007/05/21 11:53:29 osipov Exp $
*/
class CPager extends Component
{
    var $data = array();
    var $template = "common/pager.tpl";
    var $pageID = 0;
    var $params = array("pageID");
    var $itemsPerPage = 10;

    function initView()
    {
        parent::initView();
        if ($this->get("pageID") === "") {
            $this->set("pageID", 0);
        } else if ($this->get("pageID") && count($this->get("pages")) <= $this->get("pageID")) {
            $this->set("pageID", count($this->get("pages")) - 1);
        }
    }
    
    function &getPages()
    {
        if (is_null($this->pages)) {
            $this->pages = array_chunk($this->get("data"), $this->get("itemsPerPage"));
        }
        return $this->pages;
    }

    function &getPageData()
    {
    	if (!isset($this->_baseObj)) {
    		$this->_baseObj =& func_new("Base");
    	}
        $pages = $this->get("pages");
        $pageData = $pages[$this->get("pageID")];
        if (is_array($pageData)) {
        	for($i=0; $i<count($pageData); $i++) {
        		if ($this->_baseObj->isObjectDescriptor($pageData[$i])) {
        			$pageData[$i] = $this->_baseObj->descriptorToObject($pageData[$i]);
        		}
        	}
        }
        return $pageData;
    }

    function &getPageUrls()
    {
        $result = array();
        $params = $this->get("dialog.allParams");
        $dialog =& $this->get("dialog");
        for ($i = 0; $i < count($this->get("pages")); $i++) {
            if ($i == 0) {
                $params["pageID"] = null; // exclude pageID for the first page
            } else {
                $params["pageID"] = $i;
            }    
            $result[$i+1] = $dialog->getUrl($params);
        }
        return $result;
    }

    function isMoreThanOnePage()
    {
        return count($this->get("pages")) > 1;
    }

    function isCurrentPage($num)
    {
        return $this->get("pageID") + 1 == $num;
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
