<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

define('LC_MODULES_DIR', LC_CLASSES_DIR . 'XLite' . LC_DS . 'Module' . LC_DS);

// Temporary directories
define('LC_VAR_URL', 'var');

define('LC_SKINS_CACHE_DIR', LC_COMPILE_DIR . 'skins' . LC_DS);

define('LC_TMP_DIR', LC_VAR_DIR . 'tmp' . LC_DS);

define('LC_LOCALE_DIR', LC_VAR_DIR . 'locale');

define('LC_DATACACHE_DIR', LC_VAR_DIR . 'datacache');

// Skins directories
define('LC_CUSTOMER_AREA_SKIN', LC_SKINS_DIR . 'default' . LC_DS . 'en' . LC_DS);
define('LC_ADMIN_AREA_SKIN', LC_SKINS_DIR . 'admin' . LC_DS . 'en' . LC_DS);

// Images subsystem settings
define('LC_IMAGES_DIR', LC_ROOT_DIR . 'images' . LC_DS);
define('LC_IMAGES_CACHE_DIR', LC_VAR_DIR . 'images' . LC_DS);

define('LC_IMAGES_URL', 'images');
define('LC_IMAGES_CACHE_URL', LC_VAR_URL . '/images');

// OS
define('LC_OS_NAME', substr(PHP_OS, 0, strpos(PHP_OS,' ')));
define('LC_OS_CODE', strtolower(substr(LC_OS_NAME, 0, 3)));
define('LC_OS_IS_WIN', LC_OS_CODE === 'win');

// Session type
define('LC_SESSION_TYPE', 'Sql');

// Common end-of-line
define('LC_EOL', 'cli' == PHP_SAPI ? "\n" : "<br />\n");

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . LC_LIB_DIR
);

// Some common functions
require_once (LC_ROOT_DIR . 'Includes' . LC_DS . 'functions.php');

// Common error reporting settings
$path = LC_VAR_DIR . 'log' . LC_DS . 'php_errors.log.' . date('Y-m-d') . '.php';
if (!file_exists(dirname($path))) {
    \Includes\Utils\FileManager::mkdirRecursive(dirname($path));
}

if (!file_exists($path) || 16 > filesize($path)) {
    file_put_contents($path, '<' . '?php die(1); ?' . '>' . "\n");
}

ini_set('error_log', $path);
ini_set('log_errors', 1);

unset($path);

// Set default memory limit
func_set_memory_limit('32M');

