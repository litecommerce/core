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
* Values returned by habdleRequest(&$order)
*/
define('PAYMENT_SILENT', 1); // disable output
define('PAYMENT_SUCCESS', 2); // show success page
define('PAYMENT_FAILURE', 3); // show error page

/**
* Class PaymentMethod provides access to payment method details.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_PaymentMethod extends XLite_Model_Abstract
{
    // {{{ properties
    var $alias = "payment_methods";
    var $primaryKey = array(
        "payment_method"
        );
    var $defaultOrder = "orderby";
    var $fields = array(
            'payment_method'  => '',  
            'name'            => '',
            'details'         => '',
            'class'           => '',
            'params'          => '',
            'orderby'         => '',
            'enabled'         => 1
        );
    var $params = null; 
    var $hasConfigurationForm = false;
    // }}}

    function constructor($id = null) // {{{
    {
        parent::constructor($id);
        if (isset($this->_range)) {
            $this->_range .= " AND ";
        } else {
            $this->_range = "";
        }    
        global $_registered_methods;
        $this->_range .= "payment_method IN ('".join("','", $_registered_methods)."')";
        if ($id) {
            $this->read();
        }
    } // }}}

    /**
    * A method which registers a new payment method $name.
    * A payment method won't be visible untill you register it.
    * Re-create this object after you call this method, like this:
    * $pm = new XLite_Model_PaymentMethod();
    * $pm->registerMethod('my_method');
    * $pm = new XLite_Model_PaymentMethod();
    * $pm->getActiveMethods();
    */
    function registerMethod($name) // {{{
    {
        global $_registered_methods;
        $_registered_methods[] = $name;
		$this->xlite->_paymentMethodRegistered = 1;
    } // }}}

    function isRegisteredMethod($name)
    {
        global $_registered_methods;
        foreach($_registered_methods as $method) {
        	if ($method == $name) {
        		return true;
        	}
        }
        return false;
    } // }}}

    /**
    * Returns a copy of the class 'PaymentMethod_$class' instance
    */
    function getInstanceByClass($class) // {{{
    {
        static $instances;
        if (!isset($instances)) {
            $instances = array();
        }

        if (!isset($instances[$class])) {
            $ClassName = "PaymentMethod_" . $class;
            if (func_class_exists($ClassName)) {
                $instances[$class] = new $ClassName();
            } else {
                $instances[$class] = new XLite_Model_PaymentMethod();
            }
        }
        return $instances[$class];
    } // }}}

    function getActiveMethods() // {{{
    {
        static $instances;

        if (!isset($instances)) {
            $instances = array();
            $p = new XLite_Model_PaymentMethod();
            foreach ($p->findAll() as $method) {
				if ($method->is("enabled")) {
                	$instances[$method->get("payment_method")] = $method;
				}
            }
        }
        return $instances;
    } // }}}

    function handleConfigRequest() // {{{
    {
        $this->set("params", $_POST["params"]);
        $this->update();
        return '';
    } // }}}
    
    function _updateProperties(array $properties = array()) // {{{
    {
		parent::_updateProperties($properties);

		$payment = $this->getInstanceByClass($properties["payment_method"]);
		$payment->isPersistent = true;
		$payment->isRead = true;

        return $payment;
    } // }}}

    function get($name) // {{{
    {
        $result = parent::get($name);
        if ($name == "params") {
            if (is_null($this->params) && !empty($result)) {
                $this->params = unserialize($result);
                if(is_object($this->params)) { // backward compatibility
                    $this->params = get_object_vars($this->params);
                }
            }
            $result = $this->params;
        }
        return $result;
    } // }}}

    function set($name, $val) // {{{
    {
        if ($name == "params") {
            $this->params = $val;
            $val = serialize($val);
        }
        parent::set($name, $val);
    } // }}}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
