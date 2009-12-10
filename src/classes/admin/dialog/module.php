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
* @version $Id: module.php,v 1.10 2008/10/23 11:45:03 sheriff Exp $
*
*/
class Admin_Dialog_module extends Admin_Dialog
{
    var $params = array("target", "page");
    
    function _InvalidModule()
    {
		$this->set("target", "modules");
		$this->set("page", null);
		$this->redirect();
		exit;
    }

    function init()
    {
    	if (!isset($_REQUEST["page"])) {
    		$this->_InvalidModule();
    	}

    	$activeModules = $this->xlite->mm->getActiveModules();
    	if (!isset($activeModules[$_REQUEST["page"]]) || (isset($activeModules[$_REQUEST["page"]]) && !$activeModules[$_REQUEST["page"]])) {
    		$this->_InvalidModule();
    	}
    	$modules =& $this->xlite->mm->getModules();
    	foreach ($modules as $module) {
    		if ($module->get("name") == $_REQUEST["page"]) {
    			if (!$module->isLicenseValid()) {
					$this->_InvalidModule();
				}
    		}
    	}

        parent::init(); 
    }

    function &getOptions()
    {
        $config =& func_new("Config"); 
        return $config->getByCategory($this->page);
    }

    function action_update()
    {
        $options =& $this->get("options");
        for ($i=0; $i<count($options); $i++) {
            $name = $options[$i]->get("name");
            $type = $options[$i]->get("type");
            if ($type=='checkbox') {
                if (empty($_POST[$name])) {
                    $val = 'N';
                } else {
                    $val = 'Y';
                }
            } elseif ($type == "serialized" && is_array($_POST[$name])) {
                $val = serialize($_POST[$name]);
            } else {
                $val = trim($_POST[$name]);
            }
            $options[$i]->set("value", $val);
        }

        // write changes
        for ($i=0; $i<count($options); $i++) {
            $options[$i]->update();
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
