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

class CPAPager extends CPager
{
    function initView()
    {
    	if (parent::get("pageIDX") && !in_array(parent::get("pageIDX"), $this->params)) {
    		$this->params[] = parent::get("pageIDX");
    	}
    	if (parent::get("extraParameter") && !in_array(parent::get("extraParameter"), $this->params)) {
    		$this->params[] = parent::get("extraParameter");
    	}
        parent::initView();
    }

    function get($name)
    {
    	$result = parent::get($name); 

    	if ($name == "pageID" && parent::get("pageIDX")) {
    		$result = isset($_REQUEST[parent::get("pageIDX")]) ? $_REQUEST[parent::get("pageIDX")] : 0; 
        	if (count($this->get("pages")) <= $result) {
            	$result = count($this->get("pages")) - 1;
            }
    	}

    	return $result;
    }

    function set($name, $value)
    {
    	if ($name == "pageID" && parent::get("pageIDX")) {
    		parent::set(parent::get("pageIDX"), $value);
    	} else {
    		parent::set($name, $value);
    	}
    }

    function getPageUrls()
    {
    	if (parent::get("pageIDX")) {
            $result = array();
            $params = $this->get("dialog.allParams");
			$params["pageID"] = null;
			if (parent::get("extraParameter") && isset($_REQUEST[parent::get("extraParameter")])) {
				$params[parent::get("extraParameter")] = $_REQUEST[parent::get("extraParameter")];
			}
            $dialog = $this->get("dialog");
            for ($i = 0; $i < count($this->get("pages")); $i++) {
                if ($i == 0) {
                    $params[parent::get("pageIDX")] = null; // exclude pageID for the first page
                } else {
                    $params[parent::get("pageIDX")] = $i;
                }    
                $result[$i+1] = $dialog->getUrl($params);
            }
    	} else {
            $result = array();
            $params = $this->get("dialog.allParams");
			if (parent::get("extraParameter") && isset($_REQUEST[parent::get("extraParameter")])) {
				$params[parent::get("extraParameter")] = $_REQUEST[parent::get("extraParameter")];
			}
            $dialog = $this->get("dialog");
            for ($i = 0; $i < count($this->get("pages")); $i++) {
                if ($i == 0) {
                    $params["pageID"] = null; // exclude pageID for the first page
                } else {
                    $params["pageID"] = $i;
                }    
                $result[$i+1] = $dialog->getUrl($params);
            }
        }
        return $result;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
