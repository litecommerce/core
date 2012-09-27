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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */


/**
 * Initialization of LiteCommerce installation
 *
 * @package LiteCommerce
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}

if (version_compare(phpversion(), '5.3.0') >= 0) {
    error_reporting(E_ALL ^ E_DEPRECATED);

} else {
    die('LiteCommerce cannot start on PHP version earlier than 5.3.0 (' . phpversion(). ' is currently used)');
}

ini_set('display_errors', true);
ini_set('display_startup_errors', true);

@set_time_limit(300);

umask(0);

require_once realpath(dirname(__FILE__) . '/../..') . '/top.inc.php';

require_once constant('LC_DIR_ROOT') . 'Includes/install/install_settings.php';

// suphp mode
define('LC_SUPHP_MODE', get_php_execution_mode());
