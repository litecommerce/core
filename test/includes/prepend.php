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

// It's the feature of PHP 5. We need to explicitly define current time zone.
// See also http://bugs.php.net/bug.php?id=48914
@date_default_timezone_set(@date_default_timezone_get());

define('LC_DIR', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));

define('LC_DS', DIRECTORY_SEPARATOR);
define('LC_ROOT_DIR', rtrim(LC_DIR, LC_DS) . LC_DS);

define('LC_EXT_LIB_DIR', LC_ROOT_DIR . 'lib' . LC_DS);

define('LC_CONFIG_DIR', LC_ROOT_DIR . 'etc' . LC_DS);

define('LC_CLASSES_DIR', LC_ROOT_DIR . 'classes' . LC_DS);
define('LC_LIB_DIR', LC_CLASSES_DIR . 'XLite' . LC_DS);
define('LC_MODULES_DIR', LC_LIB_DIR . 'Module' . LC_DS);

define('LC_VAR_DIR', LC_ROOT_DIR . 'var' . LC_DS);

define('LC_COMPILE_DIR', LC_VAR_DIR . 'run' . LC_DS);
define('LC_CLASSES_CACHE_DIR', LC_COMPILE_DIR . 'classes' . LC_DS);
define('LC_SKINS_CACHE_DIR', LC_COMPILE_DIR . 'skins' . LC_DS);

define('LC_TMP_DIR', LC_VAR_DIR . 'tmp' . LC_DS);

define('LC_SKINS_DIR', LC_ROOT_DIR . 'skins' . LC_DS);
define('LC_CUSTOMER_AREA_SKIN', LC_SKINS_DIR . 'default' . LC_DS . 'en' . LC_DS);
define('LC_ADMIN_AREA_SKIN', LC_SKINS_DIR . 'admin' . LC_DS . 'en' . LC_DS);

// OS
define('LC_OS_NAME', substr(php_uname(), 0, strpos(php_uname(),' ')));
define('LC_OS_CODE', strtolower(substr(LC_OS_NAME, 0, 3)));
define('LC_OS_IS_WIN', LC_OS_CODE === 'win');

// Session type
define('LC_SESSION_TYPE', 'Sql');

// Common end-of-line
define('LC_EOL', 'cli' == php_sapi_name() ? "\n" : "<br />\n");

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . LC_EXT_LIB_DIR
);

// Some common functions
require_once (LC_ROOT_DIR . 'includes' . LC_DS . 'functions.php');

if (!defined('XLITE_INSTALL_MODE')) {
    // Check and (if needed) rebild classes cache
    require_once (LC_ROOT_DIR . 'includes' . LC_DS . 'decoration.php');
}

// Set default memory limit
func_set_memory_limit('32M');

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
    if (0 === strncmp($className, 'XLite', 5)) {
        $fn = defined('XLITE_INSTALL_MODE')
            ? LC_CLASSES_DIR
            : LC_CLASSES_CACHE_DIR;

        $fn .= str_replace('_', LC_DS, $className) . '.php';

        if (file_exists($fn)) {
            require_once ($fn);
        }
    }
}

spl_autoload_register('__lc_autoload');
