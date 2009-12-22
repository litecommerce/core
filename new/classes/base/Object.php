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

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* Base abstract class for the most of the project classes.
*
* @package Base
* @access public
* @version $Id$
*/
class Object
{
    var $xlite;
    var $logger;

    function constructor() // {{{
    {
        // Application
        global $xlite;
		static $__ignoreConstructor;
        if (isset($xlite)) {
            $this->xlite = $xlite;
            $this->auth = $xlite->get('auth');
            $this->session = $xlite->get('session');
            $this->config = $xlite->get('config');
            $this->db = $xlite->get('db');
            $this->logger = $xlite->get('logger');
        } else {
			$xlite = true;
        }
        if (function_exists('memory_get_usage')) {
            $GLOBALS['memory_usage'] = max(isset($GLOBALS['memory_usage']) ? $GLOBALS['memory_usage'] : 0, memory_get_usage()) / 1024 / 1024; // MB used
        }
    } // }}}

    /**
    * "internal" logger
    *
    * @access public
    */
    function log($message, $priority = LOG_DEBUG) // {{{
    {
    } // }}}

    /**
    * Maps the specified associative array to this object properties.
    *
    * @access public
    * @param array $assoc The associative array
    */
    function setProperties($assoc) // {{{
    { 
        if (!is_array($assoc)) {
            $this->_die("argument must be an array");
        }
        foreach ($assoc as $key => $value) {
            $this->set($key, $value);
        }
    } // }}}
    
    /**
    * Get list of this class parent (debug)
    */
    function _parentList() // {{{
    {
        $parents = "";
        $class = get_class($this);
        do {
            $parents .= $class." ";
            $class = get_parent_class($class);
        } while ($class);    
        return trim ($parents);
    } // }}}
    
    function _die($message) // {{{
    {
        func_die($message);
    } // }}}

    /**
    * Returns boolean property value named $name.
    * If no property found, returns null.
    */
    function is($name) // {{{
    {
        return (bool) $this->get($name);
    } // }}}
    
    /**
    * Returns property value named $name.
    * If no property found, returns null.
    * The value is returned by reference.
    */
    function get($name=null) // {{{
    {
        if (!isset($name)) {
        	$this->_die("argument \$name must be present");
        }
        if (strpos($name, '.')) {
            $obj = $this;
            foreach (explode('.', $name) as $n) {
            	if (isset($a)) {
                	unset($a);
                }
                if (is_array($obj)) {
                    $a = $obj[$n];
                    $obj = $a;
                } else {
                    if (!method_exists($obj,'get')) {
                        if (is_a($obj, 'stdClass') && isset($obj->$n)) {
                            return $obj->$n;
                        }
                        return null;
                    }
                    $a = $obj->get($n);
                    $obj = $a;
                }
                if (is_null($obj)) {
                    return null;
                }
            }
            return $obj;
        }
        if (method_exists($this, 'get' . $name)) {
            $func = 'get' . $name;
            return $this->$func();
        }
        if (method_exists($this, 'is' . $name)) {
            $func = 'is' . $name;
            return $this->$func();
        }
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    } // }}}

    function set($name, $value) // {{{
    {
        if (strpos($name, '.')) {
            $obj = $this;
            $names = explode('.', $name);
            $last = array_pop($names);
            foreach ($names as $n) {
                if (is_array($obj)) {
                    $obj = $obj[$n];
                } else {
                    $prevObj = $obj;
                    $obj = $obj->get($n);
                    $prevVal = $obj;
                    $prevProp = $n;
                }
                if (is_null($obj)) {
                    return;
                }
            }
            if (is_array($obj)) {
                $obj[$last] = $value;
                $prevObj->set($prevProp, $prevVal);
            } else {
                if (!method_exists($obj,'set')) {
                    $this->_die("No setter for " . get_class($this) . "." . $name);
                }
                $obj->set($last, $value);
            }
        } else if (method_exists($this, 'set' . $name)) {
            $func = 'set' . $name;
            $this->$func($value);
        } else {
            $this->$name = $value;
        }
    } // }}}

    function call($name) // {{{
    {
        if (strpos($name, '.')) {
            $obj = $this;
            $names = explode('.', $name);
            $last = array_pop($names);
            foreach ($names as $n) {
                $obj = $obj->get($n);
                if (is_null($obj)) {
                    return null;
                }
            }
            if (method_exists($obj, $last)) {
                $params = func_get_args();
                array_shift($params);
                return call_user_func_array(array(&$obj, $last), $params);
            }
            return null;
        } else if (method_exists($this, $name)){
            $params = func_get_args();
            array_shift($params);
            return call_user_func_array(array(&$this, $name), $params);
        }
        return null;
    } // }}}


    function semaphoreCreate($name)
    {
    	$status = $this->semaphoreGet($name);
    	$this->xlite->set($name, true);
    	return $status;
    }

    function semaphoreRemove($name)
    {
    	$this->xlite->set($name, false);
    	return $this->semaphoreGet($name);
    }

    function semaphoreGet($name)
    {
    	return $this->xlite->get($name);
    }

	function getLiteCommerceLinkDescriptions()
	{
		// WWW.LITECOMMERCE.COM link {
		$descriptions = array("ecommerce software", "e-commerce software");
		// } WWW.LITECOMMERCE.COM link 
		return $descriptions;
	}

	function getLiteCommerceLinkDescription()
	{
		$result = "ecommerce software";

		$descriptions = $this->getLiteCommerceLinkDescriptions();
		if (!is_array($descriptions) || count($descriptions) <= 0) {
			return $result;
		}

		$choice = $this->config->get("Version.lc_link");
		if (isset($descriptions[$choice]) && strlen($descriptions[$choice]) > 0) {
			return $descriptions[$choice];
		}

		$choice = array_rand($descriptions);
		if (isset($descriptions[$choice]) && strlen($descriptions[$choice]) > 0) {
			$cfg = func_new("Config");
			$cfg->createOption("Version", "lc_link", $choice, "text", 'Link text for "powered by LiteCommerce"');
			$result = $descriptions[$choice];
		}
		return $result;
	}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
