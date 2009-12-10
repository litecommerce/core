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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Component base class
*
* @package Base
* @version $Id: Component.php,v 1.1 2004/11/22 09:19:48 sheriff Exp $
*/
class Component extends Widget
{
    var $params = array();
    var $components = array();
    var $valid = true;
    
    function init()
    {
        // get request params
        foreach ($this->params as $name) {
            if (isset($_REQUEST[$name])) {
                $this->set($name, $_REQUEST[$name]);
            }
        }
        parent::init();
    }

    function initView()
    {
    }

    function handleRequest()
    {
        $components =& $this->get("components");
        for ($i = 0; $i < count($components); $i++) {
            $components[$i]->handleRequest();
        }
    }

    function display()
    {
        $this->initView();
        parent::display();
    }
    
    function fillForm()
    {
        $components =& $this->get("components");
        for ($i=0; $i<count($components); $i++) {
            if ($components[$i]->is("visible")) {
                $components[$i]->fillForm();
            }
        }
    }
 
    function &getAllParams()
    {
        $result = array();
        foreach ($this->get("params") as $name) {
            if (!is_null($this->get($name))) {
                $result[$name] = $this->get($name);
            }
        }
        // merge with subcomponents
        $components =& $this->get("components");
        for ($i=0; $i<count($components); $i++) {
            if ($components[$i]->is("visible")) {
                $result = array_merge($result, $components[$i]->get("allParams"));
            }
        }
        return $result;
    }

    function &getComponents()
    {
        return $this->components;
    }
    
    function addComponent(&$component)
    {
        $this->components[] =& $component;
    }

    function &getThisVar()
    {
        return $this;
    }

    function isValid()
    {
        if (!$this->valid) {
            return false;
        }
        $components =& $this->get("components");
        for ($i=0; $i<count($components); $i++) {
            if ($components[$i]->is("visible") && !$components[$i]->is("valid")) {
                return false;
            }
        }
        return true;
    }

    function mapRequest($request = null)
    {
        if (is_null($request) || (!is_null($request) && !is_array($request))) {
            $request = $_REQUEST;
        }
        foreach($request as $name => $value) {
            if (strstr($name, '.') || $name=='auth' || $name=='xlite' || $name=='session' || $name=='config' || $name=='logger' || $name=='db' || $name=='template' || $name=='params' || isset($this->$name))
                continue;
            $this->set($name, $value);
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
