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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

// Uncomment these lines for debug
// error_reporting(E_ALL | E_STRICT);
// ini_set('display_errors', true);

// It's the feature of PHP 5. We need to explicitly define current time zone.
// See also http://bugs.php.net/bug.php?id=48914
@date_default_timezone_set(@date_default_timezone_get());

// Short name
define('LC_DS', DIRECTORY_SEPARATOR);

// Paths
define('LC_DIR',               realpath(__DIR__));
define('LC_ROOT_DIR',          rtrim(LC_DIR, LC_DS) . LC_DS);
define('LC_CLASSES_DIR',       LC_ROOT_DIR . 'classes' . LC_DS);
define('LC_VAR_DIR',           LC_ROOT_DIR . 'var' . LC_DS);
define('LC_LIB_DIR',           LC_ROOT_DIR . 'lib' . LC_DS);
define('LC_CONFIG_DIR',        LC_ROOT_DIR . 'etc' . LC_DS);
define('LC_INCLUDES_DIR',      LC_ROOT_DIR . 'Includes' . LC_DS);
define('LC_COMPILE_DIR',       LC_VAR_DIR . 'run' . LC_DS);
define('LC_CLASSES_CACHE_DIR', LC_COMPILE_DIR . 'classes' . LC_DS);
define('LC_AUTOLOAD_DIR',      defined('XLITE_INSTALL_MODE') ? LC_CLASSES_DIR : LC_CLASSES_CACHE_DIR);

// Autoloading routines
require_once (LC_INCLUDES_DIR . 'Autoloader.php');
\Includes\Autoloader::registerAll();

// FIXME - to remove
require_once (LC_INCLUDES_DIR . 'prepend.php');

// Check and (if needed) rebild classes cache
if (\Includes\Decorator\Utils\CacheManager::isRebuildNeeded()) {
    \Includes\Decorator::getInstance()->rebuildCache();
}
