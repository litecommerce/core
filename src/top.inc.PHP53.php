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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

// It's the feature of PHP 5. We need to explicitly define current time zone.
// See also http://bugs.php.net/bug.php?id=48914
@date_default_timezone_set(@date_default_timezone_get());

// Timestamp of the application start
define('LC_START_TIME', time());

// Namespaces
define('LC_NAMESPACE',          'XLite');
define('LC_NAMESPACE_INCLUDES', 'Includes');
define('LC_MODEL_NS',           LC_NAMESPACE . '\Model');
define('LC_MODEL_PROXY_NS',     LC_MODEL_NS . '\Proxy');

// Paths
define('LC_DIR',               realpath(__DIR__));
define('LC_DIR_ROOT',          rtrim(LC_DIR, LC_DS) . LC_DS);
define('LC_DIR_CLASSES',       LC_DIR_ROOT . 'classes' . LC_DS);
define('LC_DIR_VAR',           LC_DIR_ROOT . 'var' . LC_DS);
define('LC_DIR_LIB',           LC_DIR_ROOT . 'lib' . LC_DS);
define('LC_DIR_SKINS',         LC_DIR_ROOT . 'skins' . LC_DS);
define('LC_DIR_IMAGES',        LC_DIR_ROOT . 'images' . LC_DS);
define('LC_DIR_CONFIG',        LC_DIR_ROOT . 'etc' . LC_DS);
define('LC_DIR_INCLUDES',      LC_DIR_ROOT . LC_NAMESPACE_INCLUDES . LC_DS);
define('LC_DIR_MODULES',       LC_DIR_CLASSES . LC_NAMESPACE . LC_DS . 'Module' . LC_DS);
define('LC_DIR_COMPILE',       LC_DIR_VAR . 'run' . LC_DS);
define('LC_DIR_CACHE_CLASSES', LC_DIR_COMPILE . 'classes' . LC_DS);
define('LC_DIR_CACHE_SKINS',   LC_DIR_COMPILE . 'skins' . LC_DS);
define('LC_DIR_CACHE_MODEL',   LC_DIR_CACHE_CLASSES . LC_NAMESPACE . LC_DS . 'Model' . LC_DS);
define('LC_DIR_CACHE_PROXY',   LC_DIR_CACHE_MODEL . 'Proxy' . LC_DS);
define('LC_DIR_BACKUP',        LC_DIR_VAR . 'backup' . LC_DS);
define('LC_DIR_DATA',          LC_DIR_VAR . 'data' . LC_DS);
define('LC_DIR_TMP',           LC_DIR_VAR . 'tmp' . LC_DS);
define('LC_DIR_LOCALE',        LC_DIR_VAR . 'locale');
define('LC_DIR_DATACACHE',     LC_DIR_VAR . 'datacache');
define('LC_DIR_LOG',           LC_DIR_VAR . 'log' . LC_DS);
define('LC_DIR_CACHE_IMAGES',  LC_DIR_VAR . 'images' . LC_DS);

// Disabled xdebug coverage for Selenium-based tests [DEVELOPMENT PURPOSE]
if (isset($_COOKIE) && !empty($_COOKIE['no_xdebug_coverage']) && function_exists('xdebug_stop_code_coverage')) {
    @xdebug_stop_code_coverage();
}

// Autoloading routines
require_once (LC_DIR_INCLUDES . 'Autoloader.php');
\Includes\Autoloader::registerAll();

// Fire the error if LC is not installed
if (!defined('XLITE_INSTALL_MODE')) {
    \Includes\ErrorHandler::checkIsLCInstalled();
}

// So called "developer" mode. Set it to "false" in production mode!
define('LC_DEVELOPER_MODE', (bool) \Includes\Utils\ConfigParser::getOptions(array('performance', 'developer_mode')));

// Fatal error and exception handlers
register_shutdown_function(array('\Includes\ErrorHandler', 'shutdown'));
set_exception_handler(array('\Includes\ErrorHandler', 'handleException'));

// :FIXME: to remove
require_once (LC_DIR_INCLUDES . 'prepend.php');

// Safe mode
if (!defined('XLITE_INSTALL_MODE')) {
    \Includes\SafeMode::initialize();
}

// Check and (if needed) rebuild classes cache
if (!defined('LC_DO_NOT_REBUILD_CACHE')) {
    \Includes\Decorator\Utils\CacheManager::rebuildCache();
}
