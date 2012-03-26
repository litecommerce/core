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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

/**
 * LiteCommerce installation settings
 *
 * @package LiteCommerce
 * @see     ____class_see____
 * @since   1.0.0
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}

// Current LiteCommerce version
define('LC_VERSION', '3.x-dev');

// Minimum PHP version supported
define('LC_PHP_VERSION_MIN', '5.3.0');

// Maximum PHP version supported (none if empty)
define('LC_PHP_VERSION_MAX', '');

// Minimum memory_limit option value (php.ini)
define('LC_PHP_MEMORY_LIMIT_MIN', '128M');

// Minimum MySQL version supported
define('LC_MYSQL_VERSION_MIN', '5.0.3');

// Default config file name
define('LC_DEFAULT_CONFIG_FILE', 'config.default.php');

// Config file name
define('LC_CONFIG_FILE', 'config.php');

// Other LiteCommerce settings
global $lcSettings;

$lcSettings = array(

    // Default language code
    'default_language_code' => 'en',

    // PHP versions that are not supported
    'forbidden_php_versions' => array(),

    'mustBeWritable' => array(
        'var',
        'images',
        'files',
        'etc' . LC_DS . 'config.php'
    ),

    // The list of directories that should have writeble permissions
    'writable_directories' => array(
        'var',
        'images',
        'files',
    ),

    // The list of directories that should be created by installation script
    'directories_to_create' => array(
    ),

    // The list of files that should be created by installation script
    'files_to_create' => array(),

    // YAML files list
    'yaml_files' => array(
        'base' => array(
            'sql/xlite_data.yaml',
        ),
        'demo' => array(
            'sql/xlite_demo.yaml'
        ),
    ),

    // The list of modules that must be enabled by installation script
    'enable_modules' => array(
        'CDev' => array(
            'Bestsellers',
            'FeaturedProducts',
        ),
    ),
);

if (defined('DRUPAL_CMS_INSTALL_MODE')) {
    $lcSettings['enable_modules']['CDev'][] = 'DrupalConnector';
}
