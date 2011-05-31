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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

// :FIXME: must be removed

// Temporary directories
define('LC_VAR_URL', 'var');

// Skins directories
define('LC_CUSTOMER_AREA_SKIN', LC_DIR_SKINS . 'default' . LC_DS . 'en' . LC_DS);
define('LC_ADMIN_AREA_SKIN', LC_DIR_SKINS . 'admin' . LC_DS . 'en' . LC_DS);

// Images subsystem settings
define('LC_IMAGES_URL', 'images');
define('LC_IMAGES_CACHE_URL', LC_VAR_URL . '/images');

// OS
define('LC_OS_NAME', preg_replace('/^([^ ]+)/', '\\1', PHP_OS));
define('LC_OS_CODE', strtolower(substr(LC_OS_NAME, 0, 3)));
define('LC_OS_IS_WIN', LC_OS_CODE === 'win');

// Session type
define('LC_SESSION_TYPE', 'Sql');

// Common end-of-line
define('LC_EOL', "\n");

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . LC_DIR_LIB
);

// Some common functions
require_once (LC_DIR_ROOT . 'Includes' . LC_DS . 'functions.php');

// Common error reporting settings
$path = LC_DIR_VAR . 'log' . LC_DS . 'php_errors.log.' . date('Y-m-d') . '.php';
if (!file_exists(dirname($path)) && is_writable(LC_DIR_VAR)) {
    \Includes\Utils\FileManager::mkdirRecursive(dirname($path));
}

if ((!file_exists($path) || 16 > filesize($path)) && is_writable(dirname($path))) {
    file_put_contents($path, '<' . '?php die(1); ?' . '>' . "\n");
    ini_set('error_log', $path);
}

ini_set('log_errors', true);

unset($path);

// Set default memory limit
func_set_memory_limit('64M');
