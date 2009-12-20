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
class LObject extends Object
{
    function setProperties($assoc)
    { 
    }

    function set($name, $value)
    {
    }

	function &get($name)
    {
    	static $license_check = "1097446386679cf7eb240a0b064335fb";
		if (!function_exists("license_check")) {
			close_shop("license_invalid");
		}

        if (function_exists('license_check')) {
            foreach (license_check() as $lname => $lvalue) {
            	$skippedNames = array("signature");
            	if (!in_array($lname, $skippedNames)) {
					$this->$lname = $lvalue;
				}
            }
        }

        if ($name == "license") {
        	return $license_check;
        }

        if (strpos($name, '.')) {
            $obj =& $this;
            foreach (explode('.', $name) as $n) {
            	if (isset($a)) {
                	unset($a);
                }
                if (is_array($obj)) {
                    $a =& $obj[$n];
                    $obj =& $a;
                } else {
                    if (!method_exists($obj,'get')) {
                        if (is_a($obj, 'stdClass') && isset($obj->$n)) {
                            return $obj->$n;
                        }
                        return null;
                    }
                    $a =& $obj->get($n);
                    $obj =& $a;
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
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
