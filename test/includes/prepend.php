<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL:                                                        |
| http://www.litecommerce.com/software_license_agreement.html                  |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*
* LiteCommerce pre-requirements loader.
*
* $Id$
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

define('LC_DIR', realpath(dirname(dirname(__FILE__))));

// It's the feature of PHP 5. We need to explicitly define current time zone.
// See also http://bugs.php.net/bug.php?id=48914
date_default_timezone_set(@date_default_timezone_get());

// Display all errors and warnings
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);	

define('LC_DS', DIRECTORY_SEPARATOR);
define('LC_ROOT_DIR', rtrim(LC_DIR, LC_DS) . LC_DS);

define('LC_EXT_LIB_DIR', LC_ROOT_DIR . 'lib' . LC_DS);

define('LC_CLASSES_DIR', LC_ROOT_DIR . 'classes' . LC_DS);
define('LC_LIB_DIR', LC_CLASSES_DIR . 'XLite' . LC_DS);
define('LC_MODULES_DIR', LC_LIB_DIR . 'Module' . LC_DS);

define('LC_COMPILE_DIR', LC_ROOT_DIR . 'var' . LC_DS . 'run' . LC_DS);
define('LC_CLASSES_CACHE_DIR', LC_COMPILE_DIR . 'classes' . LC_DS);
define('LC_SKINS_CACHE_DIR', LC_COMPILE_DIR . 'skins' . LC_DS);

define('LC_SKINS_DIR', LC_ROOT_DIR . 'skins' . LC_DS);
define('LC_CUSTOMER_AREA_SKIN', LC_SKINS_DIR . 'default' . LC_DS . 'en' . LC_DS);
define('LC_ADMIN_AREA_SKIN', LC_SKINS_DIR . 'admin' . LC_DS . 'en' . LC_DS);

// OS
define('LC_OS_NAME', substr(php_uname(), 0, strpos(php_uname(),' ')));
define('LC_OS_CODE', strtolower(substr(LC_OS_NAME, 0, 3)));
define('LC_OS_IS_WIN', LC_OS_CODE === 'win');

// Session type
define('LC_SESSION_TYPE', 'Sql');


// Some common functions
require_once LC_ROOT_DIR . 'includes' . LC_DS . 'functions.php';

// Check and (if needed) rebild classes cache
require_once LC_ROOT_DIR . 'includes' . LC_DS . 'decoration.php';


/**
 * Class autoload function
 * 
 * @param string $className class name to use
 *  
 * @return void
 * @see    ____func_see____
 * @since  1.0.0
 */
function __lc_autoload($className)
{
	// FIXME - remove checks
	if (0 === strpos($className, 'XLite')) {
		$fn = LC_CLASSES_CACHE_DIR . str_replace('_', LC_DS, $className) . '.php';
		if (file_exists($fn)) {
			include_once (LC_CLASSES_CACHE_DIR . str_replace('_', LC_DS, $className) . '.php');
		}
	}
}

spl_autoload_register('__lc_autoload');
