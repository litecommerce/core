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
* Class Config provides access to configuration parameters stored in DB
*
* @package Kernel
* @version $Id: Config.php,v 1.37 2008/10/23 11:47:58 sheriff Exp $
*/
class Config extends Base
{
    var $fields = array(
        'category' => '',
        'name' => '',
        'comment' => '',
        'value' => '',
        'category' => '',
        'orderby' => '0',
        'type' => 'text');
    
    var $primaryKey = array('category', 'name');
    var $alias = 'config';
    var $defaultOrder = "orderby";
    var $configClass = "Config";
    
    // GET methods {{{
    function getCategories()
    {
        return array("General", "Company", "Email", "Security","AdminIP", "Captcha", "Environment");
    }

    function getCategoryNames()
    {
        return array("General", "Company", "Email", "Security","Admin IP protection", "Captcha protection", "Environment");
    }
    
    function &getByCategory($category)
    {
        return $this->findAll("category='$category'", "orderby");
    }
    // }}}

    // IS methods {{{
    function isText()
    {
        return $this->get("type") == "text";
    }

    function isCheckbox()
    {
        return $this->get("type") == "checkbox";
    }

    function isCountry()
    {
        return $this->get("type") == "country";
    }

    function isState()
    {
        return $this->get("type") == "state";
    }

    function isChecked()
    {
        return $this->get("value") == 'Y';
    }

    function isSelect()
    {
        return $this->get("type") == "select";
    }
    
    function isSelected($val)
    {
        return parent::isSelected("value", $val);
    }

    function isName($name)
    {
        return $this->get("name") == $name;
    }

    function isTextArea()
    {
        return $this->get("type") == "textarea";
    }
    
    function isSeparator()
    {
        return $this->get("type") == "separator";
    }
    
    // }}}

    /**
    * Read config variables
    */
    function &readConfig() // {{{
    {
        $config = func_new("Object");
		$row = func_new($this->configClass);
        $r = $row->iterate();
        while ($row->next($r)) {
            $category = $row->get("category");
            if (!isset($config->$category)) {
                $config->$category = func_new("Object");
            }
            $name = $row->get("name");
            if ($row->get("type") == "checkbox") {
                $config->$category->$name = $row->get("value") == 'Y' ? true : false;
            } else if ($row->get("type") == "serialized") {
                $config->$category->$name = unserialize($row->get("value"));
            } else {    
                $config->$category->$name = $row->get("value");
            }    
        }
        $config->Company->locationCountry =& func_new("Country", $config->Company->location_country);
        $config->Company->locationState =& func_new("State", $config->Company->location_state);
		if ($config->Company->locationState->get("state_id") == -1) {
			$config->Company->locationState->set("state", $config->Company->get("custom_location_state"));
		}
        $config->General->defaultCountry =& func_new("Country", $config->General->default_country);
		$config->Memberships->memberships = array();
		if (isset($config->Memberships->membershipsCollection)) {
			if (is_array($config->Memberships->membershipsCollection)) {
    			foreach($config->Memberships->membershipsCollection as $membership) {
    				$config->Memberships->memberships[] = $membership['membership'];
    			}
    		} else {
				$config->Memberships->membershipsCollection = array();
    		}
		} else {
			$config->Memberships->membershipsCollection = array();
		}
        return $config;
    } // }}}

    function createOption($category, $name, $value, $type = null, $comment = null, $orderby = null) // {{{
    {
        $config =& func_new($this->configClass);
        if ($config->find("name='$name' AND category='$category'")) {
            $config->set("value", $value);
            if (!is_null($type)) {
                $config->set("type", $type);
            }
            if (!is_null($comment)) {
                $config->set("comment", $comment);
            }
            if (!is_null($orderby)) {
                $config->set("orderby", $orderby);
            }
            $config->update();
        } else {
            $config->set("name", $name);
            $config->set("category", $category);
            $config->set("value", $value);
            if (!is_null($type)) {
                $config->set("type", $type);
            }
            if (!is_null($comment)) {
                $config->set("comment", $comment);
            }
            if (!is_null($orderby)) {
                $config->set("orderby", $orderby);
            }
            $config->create();
        }
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
