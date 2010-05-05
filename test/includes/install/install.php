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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */


/**
 * LiteCommerce installation procedures
 * 
 * @package LiteCommerce
 * @see     ____class_see____
 * @since   3.0.0
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}

if (!function_exists('version_compare') || version_compare(phpversion(), '5.0.0') < 0) {
    die('LiteCommerce cannot start on PHP version earlier than 5.0.0 (' . phpversion(). ' is currently used)');
}

require_once realpath(dirname(__FILE__) . '/../..') . '/includes/prepend.php';

require_once constant('LC_ROOT_DIR') . 'includes/install/install_settings.php';

if (version_compare(phpversion(), '5.3.0') >= 0) {
    error_reporting(E_ALL ^ E_DEPRECATED);

} else {
    error_reporting(E_ALL);
}

ini_set('display_errors', true);

// suphp mode
define('LC_SUPHP_MODE', get_php_execution_mode());


/*
 * Checking requirements section
 */


/**
 * Perform the requirements checking
 * 
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function doCheckRequirements()
{ 
    $checkRequirements = array();

    $checkRequirements['lc_install_script'] = array(
        'title'    => 'Installation script',
        'critical' => true,
    );

    $checkRequirements['lc_loopback'] = array(
        'title'    => 'Loopback test',
        'critical' => true,
        'depends' => 'lc_install_script'
    );

    $checkRequirements['lc_php_version'] = array(
        'title'    => 'PHP version',
        'critical' => true,
    );

    $checkRequirements['lc_php_safe_mode'] = array(
        'title'    => 'PHP safe_mode',
        'critical' => true,
    );

    $checkRequirements['lc_php_magic_quotes_sybase'] = array(
        'title'    => 'magic_quotes_sybase',
        'critical' => true,
    );

    $checkRequirements['lc_php_sql_safe_mode'] = array(
        'title'    => 'sql.safe_mode',
        'critical' => true,
    );

    $checkRequirements['lc_php_disable_functions'] = array(
        'title'    => 'Disabled functions',
        'critical' => true,
    );

    $checkRequirements['lc_php_memory_limit'] = array(
        'title'    => 'Memory limit',
        'critical' => true,
    );

    $checkRequirements['lc_php_file_uploads'] = array(
        'title'    => 'File uploads',
        'critical' => true,
    );

    $checkRequirements['lc_php_mysql_support'] = array(
        'title'    => 'MySQL support',
        'critical' => true,
    );

    $checkRequirements['lc_php_pdo_mysql'] = array(
        'title'    => 'PDO extension',
        'critical' => true,
        'depends'  => 'lc_php_mysql_support'
    );

    $checkRequirements['lc_php_upload_max_filesize'] = array(
        'title'    => 'Upload file size limit',
        'critical' => false,
    );

    $checkRequirements['lc_php_allow_url_fopen'] = array(
        'title'    => 'allow_url_fopen',
        'critical' => false,
    );

    $checkRequirements['lc_mem_allocation'] = array(
        'title'    => 'Memory allocation test',
        'critical' => false,
        'depends'  => 'lc_loopback'
    );

    $checkRequirements['lc_recursion_test'] = array(
        'title'    => 'Recursion test',
        'critical' => false,
        'depends'  => 'lc_loopback'
    );

    $checkRequirements['lc_file_permissions'] = array(
        'title'    => 'File permissions',
        'critical' => true,
    );

    $checkRequirements['lc_mysql_version'] = array(
        'title'    => 'MySQL version',
        'critical' => true,
        'depends'  => 'lc_php_mysql_support'
    );

    $checkRequirements['lc_php_gdlib'] = array(
        'title'    => 'GDlib extension',
        'critical' => false,
    );

    $checkRequirements['lc_https_bouncer'] = array(
        'title'    => 'HTTPS bouncers',
        'critical' => false,
    );

    $checkRequirements['lc_xml_support'] = array(
        'title'    => 'XML extensions support',
        'critical' => false,
    );

    $passed = array();

    while (count($passed) < count($checkRequirements)) {

        foreach ($checkRequirements as $reqName => $reqData) {

            // Requirement has been already checked
            if (in_array($reqName, $passed)) {
                continue;
            }

            if (isset($reqData['depends'])) {

                // Skip checking if requirement depended on unchecked requirement
                if (!in_array($reqData['depends'], $passed)) {
                    continue;

                // Skip checking if requirement depends on failed requirement
                } elseif ($checkRequirements[$reqData['depends']]['status'] === false || isset($checkRequirements[$reqData['depends']]['skipped'])) {
                    $checkRequirements[$reqName]['status'] = ($checkRequirements[$reqData['depends']]['critical'] && $checkRequirements[$reqData['depends']]['status'] === false) || !$checkRequirements[$reqName]['critical'];
                    $checkRequirements[$reqName]['skipped'] = true;
                    $checkRequirements[$reqName]['value'] = '';
                    $checkRequirements[$reqName]['description'] = $checkRequirements[$reqName]['title'] . ' failed';
                    $passed[] = $reqName;
                    continue;
                }
            }

            // Prepare checking function name
            $reqNameComponents = explode('_', $reqName);
            $funcName = 'check';
            foreach($reqNameComponents as $part) {
                if (strtolower($part) != 'lc') {
                    $part[0] = strtoupper($part[0]);
                    $funcName .= $part;
                }
            }

            if (!function_exists($funcName)) {
                die("Internal error: function $funcName() does not exists");
            }

            // Check requirement and init its properies
            $errorMsg = $value = null;
            $checkRequirements[$reqName]['status'] = $funcName($errorMsg, $value);
            $checkRequirements[$reqName]['description'] = $errorMsg;
            $checkRequirements[$reqName]['value'] = $value;

            $passed[] = $reqName;
        }
    }

    return $checkRequirements;
}

/**
 * Check if install.php file exists
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkInstallScript(&$errorMsg, $value = null)
{
    $result = @file_exists(LC_ROOT_DIR . 'install.php');
    
    if (!$result) {
        $errorMsg = 'LiteCommerce installation script not found. Restore it  and try again';
    }

    return $result;
}

/**
 * Check an ability to do HTTP requests to the server where LiteCommerce located
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkLoopback(&$errorMsg, $value = null)
{
    $result = true;

    $response = inst_http_request_install("action=loopback_test");

    if (strpos($response, "LOOPBACK-TEST-OK") === false) {
        $result = false;
        $errorMsg = "Loopback test failed. Response:\n" . $response;
    }

    return $result;
}

/**
 * Check PHP version
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpVersion(&$errorMsg, &$value)
{
    global $lcSettings;

    $result = true;

    $value = $currentPhpVersion = phpversion();

    if (func_version_compare($currentPhpVersion, constant('LC_PHP_VERSION_MIN')) < 0) {
        $result = false;
        $errorMsg = 'PHP Version must be ' . constant('LC_PHP_VERSION_MIN') . ' as a minimum';
    }

    if ($result && constant('LC_PHP_VERSION_MAX') != '' && func_version_compare($currentPhpVersion, constant('LC_PHP_VERSION_MAX')) > 0) {
        $result = false;
        $errorMsg = 'PHP Version must be not greater than ' . constant('LC_PHP_VERSION_MAX');
    }
    
    if ($result && isset($lcSettings['forbidden_php_versions']) && is_array($lcSettings['forbidden_php_versions'])) {

        foreach ($lcSettings['forbidden_php_versions'] as $fpv) {

            if (func_version_compare($currentPhpVersion, $fpv['min']) >= 0) {
        
                $result = false;
    
                if (isset($fpv['max']) && func_version_compare($currentPhpVersion, $fpv['max']) > 0) {
                    $result = true;

                } else {
                    $errorMsg = 'Unsupported PHP version detected';
                    break;
                }
            }
        }
    }

    return $result;
}

/**
 * Check if PHP option safe_mode is on/off
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpSafeMode(&$errorMsg, &$value)
{
    $result = true;

    $value = (ini_get('safe_mode') ? 'On' : 'Off');

    // PHP Safe mode must be Off if PHP is earlier 5.3

    if (func_version_compare(phpversion(), '5.3.0') <= 0 && 'off' != strtolower($value)) {
        $result = false;
        $errorMsg = 'PHP safe_mode option value should be Off if PHP is earlier 5.3.0';
    }

    return $result;
}

/**
 * Check if PHP option magic_quotes_sybase is on/off
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpMagicQuotesSybase(&$errorMsg, &$value)
{
    $result = true;

    $value = (ini_get('magic_quotes_sybase') ? 'On' : 'Off');

    // PHP Safe mode must be Off if PHP is earlier 5.3

    if (func_version_compare(phpversion(), '5.3.0') < 0 && 'off' != strtolower($value)) {
        $result = false;
        $errorMsg = 'PHP option magic_quotes_sybase value should be Off if PHP is earlier 5.3.0';
    }

    return $result;
}

/**
 * Check if PHP option sql.safe_mode is on/off
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpSqlSafeMode(&$errorMsg, &$value)
{
    $result = true;

    $value = (ini_get('sql.safe_mode') ? 'On' : 'Off');

    // PHP Safe mode must be Off if PHP is earlier 5.3

    if ('off' != strtolower($value)) {
        $result = false;
        $errorMsg = 'PHP option sql.safe_mode value should be Off';
    }

    return $result;
}

/**
 * Check if php.ini file disabled some functions
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpDisableFunctions(&$errorMsg, &$value)
{
    $result = true;

    $value = @ini_get('disable_functions');

    if (!empty($value)) {
        $result = false;
        $errorMsg = 'Disabled functions discovered (' . $value . ') that must be enabled';

    } else {
        $value = 'none';
    }

    return $result;
}

/**
 * Check PHP option memory_limit
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpMemoryLimit(&$errorMsg, &$value)
{
    $result = true;
    
    $value = @ini_get("memory_limit");

    if (is_disabled_memory_limit()) {
        $value = 'Unlimited';

    } else {

        $result = check_memory_limit($value, constant('LC_PHP_MEMORY_LIMIT_MIN'));

        if (!$result) {
            $errorMsg = 'PHP memory_limit option value should be ' . constant('LC_PHP_MEMORY_LIMIT_MIN') . ' as a minimum';
        }
    }

    return $result;
}

/**
 * Check if PHP option file_uploads is on/off
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpFileUploads(&$errorMsg, &$value)
{
    $result = true;

    $value = (ini_get('file_uploads') ? 'On' : 'Off');

    if ('off' == strtolower($value)) {
        $result = false;
        $errorMsg = 'PHP file_uploads option value should be On';
    }

    return $result;
}

/**
 * Check if MySQL support is turned on in PHP settings
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpMysqlSupport(&$errorMsg, &$value)
{
    $result = true;

    if (!function_exists('mysql_connect')) {
        $result = false;
        $value = 'Off';
        $errorMsg = 'Support MySQL is disabled in PHP. It must be enabled.';

    } else {
        $value = 'On';
    }

    return $result;
}

/**
 * Check if PDO extension and PDO MySQL driver installed
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpPdoMysql(&$errorMsg, &$value)
{
    $result = true;

    $info = get_info();

    $value = $info['pdo_drivers'];

    if (!preg_match('/mysql/', $info['pdo_drivers']) || !class_exists('PDO')) {
        $result = false;
        $errorMsg = 'PDO extension with MySQL support must be installed.';
    }

    return $result;
}

/**
 * Check if PHP option upload_max_filesize presented 
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpUploadMaxFilesize(&$errorMsg, &$value)
{
    $result = true;

    $value = @ini_get("upload_max_filesize");

    if (empty($value)) {
        $result = false;
        $errorMsg = 'PHP option upload_max_filesize should contain a value. It is empty currently.';
    }

    return $result;
}

/**
 * Check if PHP option allow_url_fopen is on/off
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpAllowUrlFopen(&$errorMsg, &$value)
{
    $result = true;

    $value = (ini_get('allow_url_fopen') ? 'On' : 'Off');

    if ('off' == strtolower($value)) {
        $result = false;
        $errorMsg = 'PHP allow_url_fopen option value should be On';
    }

    return $result;
}

/**
 * Check the memory allocation
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkMemAllocation(&$errorMsg, &$value)
{
    $result = true;
    
    $sizes = array(16, 32, 48, 64);
    
    foreach ($sizes as $size) {
        
        $response = inst_http_request_install("action=memory_test&size=$size");
        
        if (!(strpos($response, "MEMORY-TEST-SKIPPED") === false)) {
            $value = 'MEMORY-TEST-SKIPPED';
            break;
        }
        
        if (strpos($response, "MEMORY-TEST-OK") === false) {
            $status = false;
            $errorMsg = "Memory allocation test failed. Response:\n" . substr($response, 0, 255);
            break;
        }
        
        $value = $size . 'M';
    }

    return $result;
}

/**
 * Check the recursion depth 
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkRecursionTest(&$errorMsg, &$value)
{
    $result = true;

    $response = inst_http_request_install("action=recursion_test");

    if (strpos($response, "RECURSION-TEST-OK") === false) {
        $result = false;
        $errorMsg = 'Recursion test failed.';
        $value = constant('MAX_RECURSION_DEPTH');
    }

    return $result;
}

/**
 * Check file permissions
 * 
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkFilePermissions(&$errorMsg, &$value)
{
    global $lcSettings;

    $result = true;

    $perms = array(); 

    if (constant('LC_SUPHP_MODE') == "0") {

        $array = array();

        if (!@is_writable(constant('LC_ROOT_DIR'))) {
            $array[constant('LC_ROOT_DIR')] = '0777';
        }

        foreach ($lcSettings['mustBeWritable'] as $object) {
            $array = array_merge($array, checkPermissionsRecursive(constant('LC_ROOT_DIR') . $object));
        }

        if (!empty($array)) {

            foreach ($array as $file => $perm) {

                if (LC_OS_CODE === 'win') {
                    $perms[] = $file;

                } else {
                    $perms[] = 'chmod ' . $perm . ' ' . $file;
                }

                if (count($perms) > 25) {
                    break;
                }
            }
        }
    }

    if (count($perms) > 0) {
        $result = false;
        if (LC_OS_CODE === 'win') {
            $errorMsg = "Permissions checking failed. Please make sure that the following files have writable permissions:\n<br /><br /><i>" . implode("<br />\n", $perms) . '</i>';

        } else {
            $errorMsg = "Permissions checking failed. Please make sure that the following file permissions are assigned (UNIX only):\n<br /><br /><i>" . implode("<br />\n", $perms) . '</i>';
        }
    }

    return $result;
}

/**
 * Check MySQL version
 * 
 * @param string   $errorMsg   Error message if checking failed
 * @param string   $value      Actual value of the checked parameter
 * @param resource $connection MySQL connection link
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkMysqlVersion(&$errorMsg, &$value, $connection = null)
{
    $result = true;
    $value = 'unknown';

    $_connection = $connection;

    if (!is_resource($_connection) && defined('DB_URL') && constant('DB_URL')) {

        $url = parse_url(constant('DB_URL'));

        $host = urldecode($url['host']);
        $port = isset($url['port']) ? urldecode($url['port']) : '';
        $user = urldecode($url['user']);
        $pass = isset($url['pass']) ? urldecode($url['pass']) : NULL;

        $_connection = @mysql_connect($host . $port, $user, $pass);
    }

    if (is_resource($_connection)) {

        $version = @mysql_get_server_info($_connection);

        if (strpos($version, '-') !== false) {
            $value = $version = substr($version, 0, strpos($version, "-"));
        }

        if (func_version_compare($version, constant('LC_MYSQL_VERSION_MIN')) < 0) {
            $result = false;
            $errorMsg = 'MySQL version must be ' . constant('LC_MYSQL_VERSION_MIN') . ' as a minimum';

        } elseif ((func_version_compare($version, "5.0.50") >= 0 && func_version_compare($version, "5.0.52") < 0)) {
            $result = false;
            $errorMsg = 'The version of MySQL which is currently used contains known bugs, that is why LiteCommerce may operate incorrectly. It is recommended to update MySQL to a more stable version.';
        }

    } elseif (is_resource($connection)) {
        $result = false;
        $errorMsg = 'Cannot connect to MySQL server.';
    }

    return $result;
}

/**
 * Check GDlib extension
 * 
 * @param string   $errorMsg   Error message if checking failed
 * @param string   $value      Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPhpGdlib(&$errorMsg, &$value)
{
    $result = false;

    if (extension_loaded('gd') && function_exists("gd_info")) {
        $gdConfig = gd_info();
        $value = $gdConfig['GD Version'];
        $result = preg_match('/[^0-9]*2\./',$gdConfig['GD Version']);
    }

    if (!$result) {
        $errorMsg = 'GDlib extension v.2.0 or later required for some modules.';
    }

    return $result;
}

/**
 * Check https bouncers presence
 * 
 * @param string   $errorMsg   Error message if checking failed
 * @param string   $value      Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkHttpsBouncer(&$errorMsg, &$value)
{
    $result = false;

    $https = new XLite_Model_HTTPS();
    $httpsBouncer = $https->detectSoftware();

    if ($httpsBouncer !== false) {
        $result = true;
        $value = $httpsBouncer;
    }

    if (!$result) {
        $errorMsg = 'No HTTPS bouncers found. It\'s required for some modules.';
    }

    return $result;
}

/**
 * Check GDlib extension
 * 
 * @param string   $errorMsg   Error message if checking failed
 * @param string   $value      Actual value of the checked parameter
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkXmlSupport(&$errorMsg, &$value)
{
    $result = false;
    $ext = array();

    if (function_exists('xml_parse')) {
        $ext[] = 'XML Parser';
    }

    if (function_exists('dom_import_simplexml')) {
        $ext[] = 'DOM/XML';
    }

    if (!empty($ext)) {
        $value = implode(', ', $ext);
        if (count($ext) > 1) {
            $result = true;
        }
    }

    if (!$result) {
        $errorMsg = 'XML/Expat and DOM extensions are required for some modules.';
    }

    return $result;
}

/*
 * End of Checking requirements section
 */


/**
 * Installing the database
 * 
 * @param string $trigger    Flag: 'base', 'demo' or 'all'
 * @param array  $params     Database access data and other parameters
 * @param bool   $silentMode Do not display any output during installing
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function doInstallDatabase($trigger, &$params, $silentMode = false)
{
    global $lcSettings;

    $result = false;

    if (!in_array($trigger, array('base', 'demo', 'all'))) {
        $errMsg = (!$silentMode ? 'Internal error: wrong trigger parameter. Stopped' : '');
        die($errMsg);
    }

    $configUpdated = true;

    $isConnected = ($connection = @mysql_connect($params['mysqlhost'] . (!empty($params['mysqlport']) ? ':' . $params['mysqlport'] : ''), $params['mysqluser'], $params['mysqlpass'])) && ($dbSelected = @mysql_select_db($params['mysqlbase']));

    if ($isConnected) {

        $_sql = array();
        $_queries = array();

        if (in_array($trigger, array('base', 'all'))) {

            $_sql = $lcSettings['sql_files']['base'];

            $modulesDir = opendir(constant('LC_ROOT_DIR') . 'classes/XLite/Module');

            while (($dir = readdir($modulesDir)) !== false) {

                if ($dir{0} != '.' && is_dir(constant('LC_ROOT_DIR') . 'classes/XLite/Module/' . $dir)) {

                    include_once constant('LC_ROOT_DIR') . 'classes/XLite/Module/' . $dir . '/Main.php';

                    $class = 'XLite_Module_' . $dir . '_Main';
                    $_queries[] = 'REPLACE INTO xlite_modules SET name = \'' . $dir . '\', enabled = \'' . intval(in_array($dir, $lcSettings['enable_modules'])). '.\', mutual_modules = \'' . implode(',', call_user_func(array($class, 'getMutualModules'))) . '\', type = \'' . call_user_func(array($class, 'getType')). '\'';
                    
                    $_moduleSqlFile = 'classes/XLite/Module/' . $dir . '/install.sql';

                    if (file_exists(constant('LC_ROOT_DIR') . $_moduleSqlFile)) {
                        $_sql[] = $_moduleSqlFile;
                    }
                }
            }
        
            closedir($modulesDir);

            // Write parameters into the config file
            if (@is_writable(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'))) {

                if (!$silentMode) {
                    echo "<br /><b>Updating " . constant('LC_CONFIG_FILE') . " file... </b><br />\n"; flush();
                }

                $configUpdated = change_config($params);

            } else {
                $configUpdated = false;
            }
            
            if ($configUpdated !== true && !$silentMode) {
                fatal_error('Cannot open configuration file "' . constant('LC_CONFIG_FILE') . '" for writing. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.');
            }

        }
        
        if (in_array($trigger, array('demo', 'all'))) {

            if (isset($params['states']) && !empty($params['states'])) {
                $countryCodes = explode('-', $params['states']);
        
                foreach($countryCodes as $countryCode) {
                    $_sql[] = $lcSettings['sql_files']['states'][$countryCode];
                }
            }

            $_sql = array_merge($_sql, $lcSettings['sql_files']['demo']);
        }

        if (true === $configUpdated) {

            if (!$silentMode) {
               echo "<br /><b>Updating database... </b><br />\n"; flush();
            }

            @mysql_query('SET sql_mode="MYSQL40"');

            foreach ($_sql as $_sqlFile) {

                $output = 'Uploading ' . $_sqlFile . '...';
                ob_start();
                $result = query_upload(constant('LC_ROOT_DIR') . $_sqlFile, $connection, false);
                $output .= (!$result ? ob_get_contents() : '<br />');
                ob_end_clean();

                if (!$silentMode) {
                    echo $output;
                    flush();
                }

                if (!$result) {
                    break;
                }
            }

            if ($result && !empty($_queries)) {

                if (!$silentMode) {
                   echo "<br /><b>Enabling modules... </b><br />\n"; flush();
                }

                foreach ($_queries as $_query) {
                    ob_start();
                    mysql_query($_query, $connection);
                    $myerr = mysql_error();

                    if (!empty($myerr)) {
                        $result = false;
                        query_upload_error($myerr, false);
                    }

                    $output = ob_get_contents();
                    ob_end_clean();

                    if (!$silentMode) {
                        echo $output;
                        flush();
                    }

                    if (!$result) {
                        break;
                    }
                }
            }
        }

    } elseif (!$silentMode) {

        if (!$connection) {
            fatal_error('Cannot connect to MySQL server. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.');

        } elseif (!$dbSelected) {
            fatal_error('Cannot find database "' . $params['mysqlbase'] . '". This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.');
        }
    }

    return $result;
}

/**
 * Rebuild LiteCommerce cache 
 * 
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function doRebuildCache()
{
    include_once constant('LC_ROOT_DIR') . 'includes/decoration.php';
    $decorator = new Decorator;
    $decorator->rebuildCache(true, false);
}

/**
 * Create required directories and files
 * 
 * @param array  $params     Database access data and other parameters
 * @param bool   $silentMode Do not display any output during installing
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function doInstallDirs($params, $silentMode = false)
{
    global $error, $lcSettings;

    $result = true;

    if ($silentMode) {
        ob_start();
    }

    echo "<BR><B>Creating directories...</B><BR>\n";

    $result = create_dirs($lcSettings['directories_to_create']);

    if ($result) {
        chmod_others_directories($lcSettings['writable_directories']);
        echo "<BR><B>Creating .htaccess files...</B><BR>\n";
        $result = create_htaccess_files($lcSettings['files_to_create']);
    }

    if ($result) {
        echo "<BR><B>Copying templates...</B><BR>\n";
        $result = copy_files(constant('LC_TEMPLATES_REPOSITORY'), "", constant('LC_TEMPLATES_DIRECTORY'));
    }

    if ($silentMode) {

        if (!$result) {
            $output = ob_get_contents();
        }

        ob_end_clean();

    } else {

        if (!$result) {
            fatal_error("Fatal error encountered while creating directories, probably because of incorrect directory permissions. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");
        }
    }

    return $result;
}

/**
 * Moving the images to file system (directory 'images')
 * 
 * @param array  $params     Database access data and other parameters
 * @param bool   $silentMode Do not display any output during installing
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function doMoveImagesToFs(&$params, $silentMode = false)
{
    $result = true;

    if (!empty($params['images_to_fs'])) {

        if ($silentMode) {
            ob_start();
        }

        echo '<br /><b>Moving product images to the file system...</b><br />';
        move_images_to_fs('xlite_products', 'product_id', 'image', 'pi_');


        echo '<br /><b>Moving product thumbnails to the file system...</b><br />';
        move_images_to_fs('xlite_products', 'product_id', 'thumbnail', 'pt_');


        echo '<br /><b>Moving category images to the file system...</b><br />';
        move_images_to_fs('xlite_categories', 'category_id', 'image', 'ci_');

        if ($silentMode) {
            ob_end_clean();
        }
    }

    return $result;
}

/**
 * Create an administrator account
 *
 * @param array  $params     Database access data and other parameters
 * @param bool   $silentMode Do not display any output during installing
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function doCreateAdminAccount(&$params, $silentMode = false)
{
    global $error;

    $result = true;

    if ($silentMode) {
        ob_start();
    }

    $login = get_magic_quotes_gpc() ? trim(stripslashes($params['login'])) : $params['login'];
    $password = get_magic_quotes_gpc() ? trim(stripslashes($params["password"])) : $params["password"];

    if (empty($login) || empty($password)) {
        $result = false;
        $errorMsg = fatal_error('Login and password can\'t be empty.');

    } else {
        $password = md5($password);
    }

    $data = @parse_ini_file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'));

    if ($result && ($mylink = @mysql_connect($data['hostspec'] . (!empty($data['port']) ? ':' . $data['port'] : ''), $data['username'], $data['password'])) && mysql_select_db($data['database'])) {

        @mysql_query("SET sql_mode='MYSQL40'");

        // check for profile
        $sql = "SELECT COUNT(*) FROM xlite_profiles WHERE login = '$login'";
        $query = '';

        if ($res = @mysql_query($sql)) {

            $data = mysql_fetch_row($res);

            // Account already exists
            if ($data[0]) {

                $sql = "SELECT profile_id FROM xlite_profiles WHERE login='$login'";

                if ($res = @mysql_query($sql)) {

                    $data = mysql_fetch_row($res);

                    // update profile
                    if ($data[0]) {  // account already exists
                        $profile_id = $data[0];
                        $query = "UPDATE xlite_profiles SET password='$password', access_level='100', order_id='0', status='E' WHERE profile_id='$profile_id'";

                    } else {
                        $query = "UPDATE xlite_profiles SET password='$password', access_level='100', order_id='0', status='E' WHERE login='$login'";
                    }

                    echo "<BR><B>Updating primary administrator profile...</B><BR>\n";

                } else {
                    $result = false;
                    $errorMsg = fatal_error("Invalid SQL query: $sql");
                }

            // Register default admin account
            } else {
                $query = "INSERT INTO xlite_profiles (login, password, access_level, status) VALUES ('$login', '$password', 100, 'E')";
                echo "<BR><B>Registering primary administrator profile...</B><BR>";
            }
        
            if (@mysql_query($query)) {
                echo "<FONT color=green>[OK]</FONT>";

            } else {
                // an error has occured
                echo "<FONT color=red>[FAILED]</FONT>";
                $result = false;
                $errorMsg = fatal_error("Invalid SQL query: $sql");
            }

        } else {
            $result = false;
            $errorMsg = fatal_error("Invalid SQL query: $sql");
        }

    } else {
        $result = false;
        $errorMsg = fatal_error("Can't connect to MySQL server or select database you specified. Press 'BACK' button and review MySQL server settings you provided.");
    }

    if ($silentMode) {
        ob_end_clean();
    }

    return $result;
}

/**
 * Do some final actions
 * 
 * @param array  $params     Database access data and other parameters
 * @param bool   $silentMode Do not display any output during installing
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function doFinishInstallation(&$params, $silentMode = false)
{
    global $lcSettings, $error;

    $result = true;

    // Save authcode for the further install runs
    $authcode = save_authcode($params);

    $install_name = rename_install_script();

    if ($install_name ) {

        // Text for email notification
        $install_rename_email =<<<OUT
To ensure the security of your LiteCommerce installation, the file "install.php" has been renamed to "{$install_name}".

Now, if you choose to re-install LiteCommerce, you should rename the file "{$install_name}" back to "install.php" and open the following URL in your browser:
     http://{$params['xlite_http_host']}{$params['xlite_web_dir']}/install.php
OUT;

        // Text for confirmation web page
        $install_rename =<<<OUT
<P>To ensure the security of your LiteCommerce installation, the file "install.php" has been renamed to "{$install_name}".</P>

<P>Now, if you choose to re-install LiteCommerce, you should rename the file "{$install_name}" back to "install.php"</P>
OUT;

    } else {
        $install_rename = '<P><font color="red"><b>WARNING!</b> The install.php script could not be renamed! To ensure the security of your LiteCommerce installation and prevent the unallowed use of this script, you should manually rename or delete it.</font></P>';
        $install_rename_email = strip_tags($install_rename);
    }

    // Prepare files permissions recommendation text
    $perms = '';

    if (!(LC_OS_CODE === 'win')) {

        $_perms = array();

        if (@is_writable(LC_ROOT_DIR)) {
            $_perms[] = 'chmod 755 ' . LC_ROOT_DIR;
        }

        if (@is_writable(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'))) {
            $_perms[] = "chmod 644 " . LC_CONFIG_DIR . constant('LC_CONFIG_FILE');
        }

        if (!@is_writable(LC_ROOT_DIR . 'cart.html')) {
            $_perms[] = 'chmod 666 ' . LC_ROOT_DIR . 'cart.html';
        }

        if (!empty($_perms)) {
            $perms = implode("<br />\n", $_perms);
            $perms =<<<OUT
<P>Before you proceed using LiteCommerce shopping system software, please set the following secure file permissions:<BR><BR>

<FONT color="darkblue">$perms</FONT>
OUT;
        }
    }

    // Prepare email notification text
    $perms_no_tags = strip_tags($perms);

    $message =<<<EOF
Congratulations!

LiteCommerce software has been successfully installed and is now available at the following URLs:

CUSTOMER ZONE (FRONT-END)
     http://{$params['xlite_http_host']}{$params['xlite_web_dir']}/cart.php

ADMINISTRATOR ZONE (BACKOFFICE)
     http://{$params['xlite_http_host']}{$params['xlite_web_dir']}/admin.php
     Login (e-mail): {$params['login']}
     Password:       {$params['password']}

{$perms_no_tags}

{$install_rename_email}

Auth code for running install.php script is: {$authcode}

Thank you for choosing LiteCommerce shopping system!

--
LiteCommerce Installation Wizard


EOF;

    // Send email notification to the admin account email
    @mail($params["login"], "LiteCommerce installation complete", $message,
        "From: \"LiteCommerce software\" <" . $params["login"] . ">\r\n" .
        "X-Mailer: PHP");

    if (!$silentMode) {

?>

<CENTER>
<H3><?php message('Installation complete.'); ?></H3>
</CENTER>

LiteCommerce software has been successfully installed and is now available at the following URLs:
<BR />

<OL>
<LI><U><A href="cart.php" style="COLOR: #000055; TEXT-DECORATION: underline;" target="_blank"><b>CUSTOMER ZONE (FRONT-END): cart.php</b></A></U></LI>
<P>
<LI><U><A href="admin.php" style="COLOR: #000055; TEXT-DECORATION: underline;" target="_blank"><b>ADMINISTRATOR ZONE (BACKOFFICE): admin.php</b></A></U><BR></LI>
<P>
<LI><A href="quickstart/index.html" target="_blank" style="COLOR: #000055; TEXT-DECORATION: underline;"><b>QUICK START WIZARD</b></A>&nbsp;will help you prepare your store for going live by guiding you through the main steps LiteCommerce shipping system setup.<BR></LI>
</OL>

<br />

<P>
<?php echo $perms; ?>

<br /><br />

<?php echo $install_rename; ?>

<P>Your auth code for running install.php in the future is: <B><?php print get_authcode(); ?></b><br />
PLEASE WRITE THIS CODE DOWN UNLESS YOU ARE GOING TO REMOVE "<?php echo $install_name; ?>"</P>

<?php

    }

    return $result;
}


/*
 * Service functions section
 */


/**
 * Create directories 
 * 
 * @param array $dirs Array of directory names
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function create_dirs($dirs)
{
    $result = true;

    $dir_permission = 0777;

    $data = @parse_ini_file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'));

    if(constant('LC_SUPHP_MODE') != 0) {
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;

    } else {
        $dir_permission = isset($data['nonprivileged_permission_dir']) ? base_convert($data['nonprivileged_permission_dir'], 8, 10) : 0755;
    }

    foreach ($dirs as $val) {
        echo "Creating directory: [$val] ... ";

        if (!@file_exists(constant('LC_ROOT_DIR') . $val)) {
            $res = @mkdir(constant('LC_ROOT_DIR') . $val, $dir_permission);
            $result &= $res;
            echo status($res);

        } else {
            echo '<font color="blue">[Already exists]</font>';
        }

        echo "<BR>\n"; flush();
    }

    return $result;
}

/**
 * Set permissions on directories
 * 
 * @param array $dirs Array of directory names
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function chmod_others_directories($dirs)
{
    if (constant('LC_SUPHP_MODE') != 0) {
        $data = @parse_ini_file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'));
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;

        foreach($dirs as $dir) {
            @chmod(constant('LC_ROOT_DIR') . $dir, $dir_permission);
        }
    }
}

/**
 * Create .htaccess files 
 * 
 * @param array $files_to_create Array of file names
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function create_htaccess_files($files_to_create)
{
    $result = true;

    if (is_array($files_to_create)) {

        foreach($files_to_create as $file=>$content) {

            echo 'Creating file: [' . $file . '] ... ';

            if ($fd = @fopen(constant('LC_ROOT_DIR') . $file, 'w')) {
                @fwrite($fd, $content);
                @fclose($fd);
                echo status(true);
                $result &= true;

            } else {
                echo status(false);
                $result = false;
            }

            echo "<BR>\n";
            flush();
        }
    }

    return $result;
}

/**
 * Check writable permissions for specified object (file or directory) recusrively
 * 
 * @param string Object path
 *  
 * @return array
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function checkPermissionsRecursive($object)
{
    $dirPermissions = '777';
    $filePermissions = '666';

    $result = array();

    if (is_dir($object)) {

        if (!is_writable($object)) {
            $result[$object] = $dirPermissions;

        } else {

            if ($handle = @opendir($object)) {

                while (($file = readdir($handle)) !== false) {

                    // Skip '.', '..', '.htaccess' and other files those names starts from '.'
                    if (preg_match('/^\./', $file)) {
                        continue;
                    }

                    $fileRealPath = $object . LC_DS . $file;

                    if (!is_writable($fileRealPath)) {
                        $result[$fileRealPath] = (is_dir($fileRealPath) ? $dirPermissions : $filePermissions);

                    } elseif (is_dir($fileRealPath)) {
                        $result = array_merge($result, checkPermissionsRecursive($fileRealPath));
                    }
            
                }
            }
        }

    } elseif (!is_writable($object)) {
        $result[$object] = $filePermissions;
    }
    
    return $result;
}

/**
 * Function to copy directory tree from skins_original to skins 
 * 
 * @param string $source_dir      Source directory name
 * @param string $parent_dir      Parent directory name
 * @param string $destination_dir Destination directory name
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function copy_files($source_dir, $parent_dir, $destination_dir)
{
    $result = true;

    $dir_permission = 0777;

    if (constant('LC_SUPHP_MODE') != 0) {
        $data = @parse_ini_file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'));
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;
    }

    if ($handle = @opendir(constant('LC_ROOT_DIR') . $source_dir)) {

        while (($file = readdir($handle)) !== false) {

            $sourceFile = constant('LC_ROOT_DIR') . $source_dir . '/' . $file;
            $destinationFile = constant('LC_ROOT_DIR') . $destination_dir . '/' . $parent_dir . '/' . $file;

            // .htaccess files must be already presented in the destination directory and they should't have writable permissions for web server user
            if (@is_file($sourceFile) && $file != '.htaccess') {

                if (!@copy($sourceFile, $destinationFile)) {
                    echo "Copying $source_dir$parent_dir/$file to $destination_dir$parent_dir/$file ... " . status(false) . "<BR>\n";
                    $result = false;
                }

                flush();

            } elseif (@is_dir($sourceFile) && $file != '.' && $file != '..') {

                echo "Creating directory $destination_dir$parent_dir/$file ... ";

                if (!@file_exists($destinationFile)) {

                    if (!@mkdir($destinationFile, $dir_permission)) {
                        echo status(false);
                        $result = false;

                    } else {
                        echo status(true);
                    }

                } else {
                    echo '<font color="blue">[Already exists]</font>';
                }

                echo "<BR>\n";

                flush();

                $result &= copy_files($source_dir . '/' . $file, $parent_dir . '/' . $file, $destination_dir);
            }
        }

        closedir($handle);

    } else {
        echo status(false) . "<BR>\n";
        $result = false;
    }

    return $result;
}

/**
 * Prepare content for writing to the config.php file
 * 
 * @param array $params
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function change_config(&$params) {
    global $installation_auth_code;

    // check whether config file is writable
    clearstatcache();
    if (!@is_readable(LC_CONFIG_DIR . constant('LC_CONFIG_FILE')) || !@is_writable(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'))) {
        return false;
    }

    // read file content
    if (!$config = file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'))) return false;

    // fixing the empty xlite_https_host value
    if (!isset($params['xlite_https_host']) || $params['xlite_https_host'] == '') {
        $params['xlite_https_host'] = $params['xlite_http_host'];
    }

    if (!isset($params['mysqlport'])) {
        $params['mysqlport'] = '';
    }

    // check whether the authcode is set in params. 

    $new_config = '';

    // change config file ..
    foreach ($config as $num => $line) {

        $patterns = array(
            '/^hostspec.*=.*/',
            '/^database.*=.*/',
            '/^username.*=.*/',
            '/^password.*=.*/',
            '/^port.*=.*/',
            '/^http_host.*=.*/',
            '/^https_host.*=.*/',
            '/^web_dir.*=.*/'
        );

        $replacements = array(
            'hostspec = "' . $params['mysqlhost'] . '"',
            'database = "' . $params['mysqlbase'] . '"',
            'username = "' . $params['mysqluser'] . '"',
            'password = "' . $params['mysqlpass'] . '"',
            'port     = "' . $params['mysqlport'] . '"',
            'http_host = "' . $params['xlite_http_host'] . '"',
            'https_host = "' . $params['xlite_https_host'] . '"',
            'web_dir = "' . $params['xlite_web_dir'] . '"'
        );

        // check whether skin param is specified: not used at present
        if (isset($params['skin'])) {
            $patterns[] = '/^skin.*=.*/';
            $replacements[] = 'skin = "' . $params['skin'] . '"';
        }

        $new_config .= preg_replace($patterns, $replacements, $line);
    }

    return save_config($new_config);

 }

/**
 * Save content to the config.php file
 * 
 * @param string $content
 *  
 * @return mixed
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function save_config($content)
{
    $handle = fopen(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'), 'wb');
    fwrite($handle, $content);
    fclose($handle);
    return $handle ? true : $handle;
}

/**
 * Returns some information from phpinfo()
 * 
 * @return array
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function get_info()
{
    static $info;

    if (!isset($info)) {
        $info = array(
            "thread_safe"     => false,
            "debug_build"     => false,
            "php_ini_path"    => '',
            'no_mem_limit'    => true,
            'commands_exists' => false,
            'php_ini_path_forbidden' => false,
            'pdo_drivers'     => false
        );

    } else {
        return $info;
    }

    ob_start();
    phpinfo();
    $php_info = ob_get_contents();
    ob_end_clean();

    $dll_sfix = ((LC_OS_CODE === 'win') ? '.dll' : '.so');

    foreach (explode("\n",$php_info) as $line) {

        if (preg_match('/command/i',$line)) {
            $info['commands_exists'] = true;

            if (preg_match('/--enable-memory-limit/i', $line)) {
                $info['no_mem_limit'] = false;
            }
            continue;
        }

        if (preg_match('/thread safety.*(enabled|yes)/i', $line)) {
            $info["thread_safe"] = true;
        }

        if (preg_match('/debug.*(enabled|yes)/i', $line)) {
            $info["debug_build"] = true;
        }

        if (preg_match("/configuration file.*(<\/B><\/td><TD ALIGN=\"left\">| => |v\">)([^ <]*)(.*<\/td.*)?/i",$line,$match)) {
            $info["php_ini_path"] = $match[2];

            // If we can't access the php.ini file then we probably lost on the match
            if (!@ini_get("safe_mode") && !@file_exists($info["php_ini_path"])) {
                $info["php_ini_path_forbidden"] = true;
            }
        }

        if (preg_match("/PDO drivers.*<\/td><td([^>]*)>([^<]*)/i", $line, $match)) {
            $info['pdo_drivers'] = $match[2];
        }
    }

    return $info;
}

/**
 * Move images from required field to the filesystem 
 * 
 * @param string $table       
 * @param string $id          
 * @param string $prefix      
 * @param string $file_prefix 
 * @param string $path        
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function move_images_to_fs($table, $id, $prefix, $file_prefix, $path = 'images/')
{
    $result = true;

    if (!@file_exists(constant('LC_ROOT_DIR') . $path)) {
        $result = @mkdir(constant('LC_ROOT_DIR') . $path, 0777);
    }

    if ($result) {

        $data = @parse_ini_file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'));

        if (@mysql_connect($data['hostspec'] . (!empty($data['port']) ? ':' . $data['port'] : ''), $data['username'], $data['password']) && mysql_select_db($data['database'])) {

            @mysql_query("SET sql_mode='MYSQL40'");
            $my_query = mysql_query("SELECT $id, {$prefix}_type, $prefix FROM $table WHERE {$prefix}_source = 'D'");

            // Fetch rows
            $iteration = 0;

            while($row = @mysql_fetch_array($my_query, MYSQL_ASSOC))
            {
                $fileName = $file_prefix . $row[$id] . '.' . get_image_extension($row[$prefix . '_type']);
                $filePath = constant('LC_ROOT_DIR') . $path . $fileName;

                $content = $row[$prefix];

                if (empty($content))
                    continue;

                // put image content to the file
                if (($fd = fopen($filePath, "wb"))) {
                    fwrite($fd, $content);
                    fclose($fd);

                } else {
                    continue;
                }

                $sql = "UPDATE $table SET $prefix ='$fileName', {$prefix}_source = 'F' WHERE $id ='{$row[$id]}'";
                @mysql_query($sql);

                if ($iteration++ % 2) {
                    echo '.';
                    flush();
                }
            }

        } else {
            $result = false;
        }
    }

    if (!$result) {
        status($result);
    }

    return $result;
}

/**
 * Get image extension
 * 
 * @param string $type
 *  
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function get_image_extension($type)
{
    $image_type = "gif";

    $image_types = array(
        "gif"  => "image/gif",
        "jpeg" => "image/jpeg",
        "png"  => "image/png",
        "swf"  => "image/swf",
        "psd"  => "image/psd",
        "bmp"  => "image/bmp",
        "tiff" => "image/tiff",
        "jpc"  => "image/jpc",
        "jp2"  => "image/jp2",
        "jpx"  => "image/jpx",
        "swc"  => "image/swc",
        "iff"  => "image/iff"
    );

    foreach ($image_types as $k => $v) {
        if ($v == $type) {
            $image_type = $k;
            break;
        }
    }

    return $image_type;
}

/**
 * Do an HTTP request to the install.php
 * 
 * @param string $action_str 
 *  
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function inst_http_request_install($action_str)
{
    $url = getLiteCommerceUrl();
    $url_request = $url . '/install.php?target=install' . (($action_str) ? '&' . $action_str : '');

    return inst_http_request($url_request);
}

/**
 * Returns LiteCommerce URL
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function getLiteCommerceUrl()
{
    $host = 'http://' . $_SERVER['HTTP_HOST'];
    $len = strlen($host) - 1;
    $host = ($host{$len} == '/') ? substr($host, 0, $len) : $host;

    $uri = (defined('LC_URI') ? constant('LC_URI') : $_SERVER['PHP_SELF']);

    $web_dir = preg_replace('/\/install(\.php)*/', '', $uri);
    $len = strlen($web_dir) - 1;
    $url = $host . (($web_dir{$len} == '/') ? substr($web_dir, 0, $len) : $web_dir);

    return $url;
}

/**
 * Do an HTTP request
 * 
 * @param string $url_request
 *  
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function inst_http_request($url_request)
{
    $response = '';

    $error = '';

    $url = parse_url($url_request);
    $errno = null;
    if ($fp = fsockopen($url['host'], (!empty($url['port']) ? $url['port'] : 80), $errno, $error, 3)) {
        fputs($fp, "GET ".$url_request." HTTP/1.0\r\n");
        fputs($fp, "Host: ".$url['host']."\r\n");
        fputs($fp, "User-Agent: Mozilla/4.5 [en]\r\n");

        fputs($fp,"\r\n");

        while (!feof($fp)) {
            $response .= fgets($fp, 4096);
        }
    }
    
    if (!empty($error)) {
        $response = $error . "\n" . $response;
    }

    return $response;
}

/**
 * Check if memory_limit is disabled
 * 
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function is_disabled_memory_limit()
{
    $info = get_info();
    
    $result = (($info['no_mem_limit'] &&
                $info['commands_exists'] &&
                !function_exists('memory_get_usage') &&
                func_version_compare(phpversion(), '4.3.2') >= 0 &&
                strlen(@ini_get('memory_limit')) == 0 ) || 
                @ini_get('memory_limit') == '-1');

    return $result;
}

/**
 * Check memory_limit option value
 * 
 * @param string $current_limit  
 * @param string $required_limit 
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function check_memory_limit($current_limit, $required_limit)
{
    $result = true;

    $limit = convert_ini_str_to_int($current_limit);
    $required = convert_ini_str_to_int($required_limit);

    if ($limit < $required) {

		// workaround for http://bugs.php.net/bug.php?id=36568
        if (!(LC_OS_CODE == 'win' && func_version_compare(phpversion(), '5.1.0') < 0)) {
            @ini_set('memory_limit', $required_limit);
            $limit = ini_get('memory_limit');
        }

        $result = (strcasecmp($limit, $required_limit) == 0);
    }

    return $result;
}

/**
 * Check if current PHP version is 5 or higher
 * 
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function is_php5()
{
    return func_version_compare(@phpversion(), '5.0.0') >= 0;
} 

/**
 * Convert php_ini int string to int
 * 
 * @param string $string 
 *  
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function convert_ini_str_to_int($string)
{
    $string = trim($string);

    $last = strtolower(substr($string,strlen($string)-1));
    $number = intval($string);

    switch($last) {
        case 'k':
            $number *= 1024;
            break;

        case 'm':
            $number *= 1024*1024;
            break;

        case 'g':
            $number *= 1024*1024*1024;
    }

    return $number;
}

/**
 * Do recursion depth testing
 * 
 * @param int $index 
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function recursion_depth_test($index)
{
    if ($index <= MAX_RECURSION_DEPTH) {
        recursion_depth_test(++$index);
    }
}

/**
 * Preparing text of the configuration checking report
 * 
 * @param array $requirements 
 *  
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function make_check_report($requirements)
{
    $phpinfo_disabled = false;

    $report = array();
    $report[] = 'LiteCommerce version ' . constant('LC_VERSION');
    $report[] = 'Report time stamp: ' . date('d, M Y  H:i');
    $report[] = '';

    foreach ($requirements as $reqName => $reqData) {

        $report[] = '[' . $reqData['title'] . ']';
        $report[] = 'Check result  - ' . (isset($reqData['skipped']) ? 'SKIPPED' : (($reqData['status']) ? 'OK' : 'FAILED'));
        $report[] = 'Critical  - ' . (($reqData['critical']) ? 'Yes' : 'No');

        if (!empty($reqData['value'])) {
            $report[] = $reqData['title'] . ' - ' . $reqData['value'];
        }

        if (!$reqData['status'] || isset($reqData['skipped'])) {
            $report[] = $reqData['description'];
        }

        $report[] = '';
    }

    $report[] = '';
    $report[] = '============================= PHP info =============================';
    $report[] = '';

    $report = strip_tags(implode("\n", $report));

    if (function_exists('phpinfo')) {
        // display PHP info
        ob_start();
        phpinfo();
        $phpinfo = ob_get_contents();
        ob_end_clean();

        // prepare phpinfo 
        $phpinfo = preg_replace("/<td[^>]+>/i", " | ", $phpinfo);
        $phpinfo = preg_replace("/<[^>]+>/i", "", $phpinfo);
        $phpinfo = preg_replace("/(?:&lt;)((?!&gt;).)*?&gt;/i", "", $phpinfo);

        $pos = strpos($phpinfo, 'PHP Version');
        if ($pos !== false) {
            $phpinfo = substr_replace($phpinfo, "", 0, $pos);
        }

        $pos = strpos($phpinfo, 'PHP License');
        if ($pos !== false) {
            $phpinfo = substr($phpinfo, 0, $pos);
        }
    } else {
        $phpinfo .= "phpinfo() disabled.\n";
    }

    $report .= $phpinfo;

    return $report;
}

/**
 * Return status message 
 * 
 * @param bool   $status Status to display: true or false
 * @param string $code   Code of section with status details (<div id='$code'>)
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function status($status, $code = null)
{
    global $first_error;

    if ($code != null) {

        if ($first_error == null && !$status) {
            $first_error = $code;
        }

        $return = ($status ? '<FONT color=green>[OK]</FONT>' : '<a href="javascript: showDetails(\'' . $code  . '\');" onClick=\'this.blur();\' title=\'Click here to see more detailes\'><FONT color=red style="text-decoration : underline" id="failed_' . $code . '">[FAILED]</FONT></a>');

    } else {
        $return = ($status ? '<FONT color=green>[OK]</FONT>' : '<FONT color=red>[FAILED]</FONT>');
    }

    return $return;
}

/**
 * Return status 'skipped' message
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function status_skipped() 
{
    return '<FONT color=blue>[SKIPPED]</FONT>';
}

/**
 * Display fatal_error message
 * 
 * @param string $txt 
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function fatal_error($txt) {
?>
<CENTER>
<P>
 <B><FONT color=red>Fatal error: <?php echo $txt ?>.<BR>Please correct the error(s) before proceeding to the next step.</FONT></B>
</P>
</CENTER>
<?php
}

/**
 * Display warning_error message
 * 
 * @param string $txt 
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function warning_error($txt) {
?>
<CENTER>
<P>
 <B><FONT color=red>Warning: <?php echo $txt ?>.</FONT></B>
</P>
</CENTER>
<?php
}

/**
 * Display message 
 * 
 * @param string $txt 
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function message($txt) {
?>
<B><FONT class=WelcomeTitle><?php echo $txt ?></FONT></B>
<?php
}

/**
 * Replace install.php script to random filename 
 * 
 * @return mixed
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function rename_install_script()
{
    $install_name = md5(uniqid(rand(), true)) . '.php';
    @rename(LC_ROOT_DIR . 'install.php', LC_ROOT_DIR . $install_name);
    @clearstatcache();

    return (!@file_exists(LC_ROOT_DIR . 'install.php') && @file_exists(LC_ROOT_DIR . $install_name) ? $install_name : false);
}

/**
 * Check if current protocol is HTTPS 
 * 
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function isHTTPS()
{
    $result = ((isset($_SERVER['HTTPS']) && 
                (strtolower($_SERVER['HTTPS'] == 'on') || $_SERVER['HTTPS'] == '1')) ||
                (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443'));

    return $result;
}

/**
 * Get number for StepBack button
 * 
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function getStepBackNumber()
{
    global $current, $params;

    $back = 0;

    switch ($current) {
        case 4:
            $back = 2;
            break;

        case 6:
            $back = 4;
            break;

        default:
            $back = ($current > 0 ? $current - 1 : 0);
            break;
    }

    if (isset($params['start_at']) && (($params['start_at'] === '4' && $back < 4) || ($params['start_at'] === '6' && $back < 6))) {
        $back = 0;
    }

    return $back;
}

/**
 * Default navigation button handler: default_js_back 
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function default_js_back()
{
?>
    function step_back() {
        document.ifrm.current.value = "<?php echo getStepBackNumber(); ?>";
        document.ifrm.submit();
        return true;
    }
<?php
}

/**
 * Default navigation button handler: default_js_next 
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function default_js_next()
{
?>
    function step_next() {
        return true;
    }
<?php
}

/**
 * Generate Auth code 
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function generate_authcode()
{
    // see include/functions.php
    return generate_code();
}

/**
 * Check Auth code (exit if wrong)
 * 
 * @param array $params 
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function check_authcode(&$params)
{
    $authcode = get_authcode();

    // if authcode IS NULL, then this is probably the first install, skip
    // authcode check
    if (is_null($authcode)) {
        return;
    }

    if (!isset($params['auth_code']) || trim($params['auth_code']) != $authcode) {
        message('Incorrect auth code! You cannot proceed with the installation.');
        exit();
    }
}

/**
 * Read config file and get Auth code 
 * 
 * @return mixed
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function get_authcode()
{
    if (!$data = @parse_ini_file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'))) {
        die('<font color=red>ERROR: config file not found (' . constant('LC_CONFIG_FILE') . ')</font>');
    }

    return !empty($data['auth_code']) ? $data['auth_code'] : null;
}

/**
 * Save Auth code 
 * 
 * @param array $params 
 *  
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function save_authcode(&$params) {

    // if authcode set in request, don't change the config file
    if (isset($params['auth_code']) && trim($params['auth_code']) != '') {
        return $params['auth_code'];
    }

    // generate new authcode
    $auth_code = generate_authcode();

    if (!@is_writable(LC_CONFIG_DIR . constant('LC_CONFIG_FILE')) || !$config = file(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'))) {
        message('Cannot open config file (' . constant('LC_CONFIG_FILE') . ') for writing!');
        exit();
    }

    $new_config = '';

    foreach ($config as $num => $line) {
        $new_config .= preg_replace('/^auth_code.*=.*/', 'auth_code = "'.$auth_code.'"', $line);
    }

    if (!save_config($new_config)) {
        message('Config file "' . constant('LC_CONFIG_FILE') . '" write failed!');
        exit();
    }

    return get_authcode();
}

/**
 * Get step by module name
 * 
 * @param string $name
 *  
 * @return int
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function get_step($name)
{
    global $modules;

    $result = 0;

    foreach ($modules as $step => $module_data) {

        if ($module_data['name'] == $name) {
            $result = $step;
            break;
        }
    }

    return $result;
}

/**
 * Display form element
 * 
 * @param string $fieldName
 * @param array  $fieldData
 * @param int    $clrNumber
 *  
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function displayFormElement($fieldName, $fieldData, $clrNumber)
{
    $fieldType = (isset($fieldData['type']) ? $fieldData['type'] : 'text');
    $fieldValue = (isset($fieldData['value']) ? $fieldData['value'] : $fieldData['def_value']);

    switch ($fieldType) {

        // Drop down box
        case 'select': {

            $formElement =<<<OUT
        <select name="params[{$fieldName}]">
OUT;

            if (is_array($fieldData['select_data'])) {
                foreach ($fieldData['select_data'] as $key => $value) {
                    $_selected = ($value == $fieldValue ? ' selected="selected"' : '');
                    $formElement .=<<<OUT
            <option value="{$key}"{$_selected}>{$value}</OPTION>
OUT;
                }
            }

            $formElement .=<<<OUT
        </select>
OUT;

            break;
        }

        // Checkbox
        case 'checkbox': {

            $_checked = !empty($fieldValue) ? 'checked="checked" ' : '';
            $formElement =<<<OUT
        <input type="checkbox" name="params[{$fieldName}]" value="Y" {$_checked}/>
OUT;
            break;
        }

        // Static text (not for input)
        case 'static': {
            $formElement = $fieldValue;
            break;
        }

        // Input text
        case 'text':
        case 'password' :
        default: {

            $fieldType = (in_array($fieldType, array('text', 'password')) ? $fieldType : 'text');
            $formElement =<<<OUT
        <input type="{$fieldType}" name="params[{$fieldName}]" size="30" value="{$fieldValue}" />
OUT;
        }
    }

    $output =<<<OUT
    <tr class="Clr{$clrNumber}">
        <td><b>{$fieldData['title']}</b><br />{$fieldData['description']}</td>
        <td>{$formElement}</td>
    </tr>
OUT;

    echo $output;
}


/*
 * End of Service functions section 
 */


/*
 * Modules section
 */



/**
 * Default module. Shows Terms & Conditions
 * 
 * @param array $params 
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_default(&$params)
{
    global $error;

    include LC_ROOT_DIR . 'includes/install/templates/step0_copyright.tpl.php';

    return false;
}

/**
 * 'Next' button handler. Checking if an 'Agree' checkbox was ticked
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_default_js_next()
{
?>
    function step_next() {
        if (document.ifrm.agree.checked) {
            return true;
        } else {
            alert("You must accept the License Agreement to proceed with the installation. If you do not agree with the terms of the License Agreement, do not install the software.");
        }
        return false;
    }
<?php
}


/**
 * Configuration checking module 
 * 
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_check_cfg()
{
    global $first_error, $error, $report_uid, $reportFName;

    $requirements = doCheckRequirements();

    $errorsFound = false;
    $warningsFound = false;

    $sections = array(
        'A' => 'Environment checking',
        'B' => 'Inspecting server configuration'
    );

    $steps = array(
        1 => array(
            'title'        => 'Verification steps',
            'section'      => 'A',
            'requirements' => array(
                'lc_loopback'
            )
        ),
        2 => array(
            'title'        => 'Checking critical dependencies',
            'section'      => 'B',
            'requirements' => array(
                'lc_php_version',
                'lc_php_safe_mode',
                'lc_php_magic_quotes_sybase',
                'lc_php_sql_safe_mode',
                'lc_php_disable_functions',
                'lc_php_memory_limit',
                'lc_php_mysql_support',
                'lc_php_pdo_mysql',
                'lc_file_permissions'
            )
        ),
        3 => array(
            'title'        => 'Checking non-critical dependencies',
            'section'      => 'B',
            'requirements' => array(
                'lc_php_file_uploads',
                'lc_php_upload_max_filesize',
                'lc_php_allow_url_fopen', 
                'lc_mem_allocation',
                'lc_recursion_test',
                'lc_php_gdlib',
                'lc_https_bouncer',
                'lc_xml_support'
            )
        )
    );

    require_once LC_ROOT_DIR . 'includes/install/templates/step1_chkconfig.tpl.php';

    $error = $errorsFound || $warningsFound;

    return false;
}


/**
 * Do step of gathering of the database configuration
 * 
 * @param array $params 
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_cfg_install_db(&$params)
{
    global $error, $lcSettings; 
    global $report_uid, $reportFName;

    $clrNumber = 2;

    // Remove report file if it was created on the previous step
    if (@file_exists($reportFName)) {
        @unlink($reportFName);
        $report_uid = '';
    }

    $paramFields = array(
        'xlite_http_host'  => array(
            'title'       => 'Web server name',
            'description' => 'Hostname of your web server (E.g.: www.example.com).',
            'def_value'   => $_SERVER['HTTP_HOST'],
            'required'    => true
        ),
        'xlite_https_host' => array(
            'title'       => 'Secure web server name',
            'description' => 'Hostname of your secure (HTTPS-enabled) web server (E.g.: secure.example.com). If omitted, it is assumed to be the same as the web server name.',
            'def_value'   => $_SERVER['HTTP_HOST'],
            'required'    => true
        ),
        'xlite_web_dir'    => array(
            'title'       => 'LiteCommerce web directory',
            'description' => 'Path to LiteCommerce files within the web space of your web server (E.g.: /shop).',
            'def_value'   => preg_replace('/\/install(\.php)*/', '', $_SERVER['PHP_SELF']),
            'required'    => false
        ),
        'mysqlhost'        => array(
            'title'       => 'MySQL server name',
            'description' => 'Hostname or IP address of your MySQL server.',
            'def_value'   => 'localhost',
            'required'    => true
        ),
        'mysqlbase'        => array(
            'title'       => 'MySQL database name',
            'description' => 'The name of the existing database to use (if the database does not exist on the server, you should create it to continue the installation).',
            'def_value'   => '',
            'required'    => true
        ),
        'mysqluser'        => array(
            'title'       => 'MySQL username',
            'description' => 'MySQL username. The user must have full access to the database specified above.',
            'def_value'   => '',
            'required'    => true
        ),
        'mysqlpass'        => array(
            'title'       => 'MySQL password',
            'description' => 'Password for the above MySQL username.',
            'def_value'   => '',
            'required'    => false
        ),
        'states'           => array(
            'title'       => 'Geographic areas',
            'description' => 'Specify which geographic areas you want to be listed in the database.',
            'def_value'   => 'US',
            'required'    => false,
            'step'        => 2,
            'type'        => 'select',
            'select_data' => $lcSettings['states_list'],
        ),
        'demo'             => array(
            'title'       => 'Install sample catalog',
            'description' => 'Specify whether you would like to setup sample categories and products?',
            'def_value'   => '1',
            'required'    => false,
            'step'        => 2,
            'type'        => 'select',
            'select_data' => array(1 => 'Yes', 0 => 'No')
        )
    );

    $messageText = '';
    $bottomMessage = '';

    $displayConfigForm = false;

    foreach ($paramFields as $fieldName => $fieldData) {

        // Prepare first step data if we came from the second step back
        if (isset($_POST['go_back']) && $_POST['go_back'] === '1') {
            if (empty($fieldData['step']) && isset($params[$fieldName])) {
                $paramFields[$fieldName]['def_value'] = $params[$fieldName];
                unset($params[$fieldName]);
            }
        }

        // Unset parameter if its empty
        if (isset($params[$fieldName]) && strlen(trim($params[$fieldName])) == 0) {
            unset($params[$fieldName]);
        }

        // Check if all required parameters presented
        if (!isset($params[$fieldName])) {
            $displayConfigForm = $displayConfigForm || $fieldData['required'];
        }
    }

    // Display form to enter host data and database settings
    if ($displayConfigForm) {

        ob_start();

        foreach ($paramFields as $fieldName => $fieldData) {

            if (isset($fieldData['step']) && $fieldData['step'] != 1) {
                continue;
            }

            $fieldData['value'] = (isset($params[$fieldName]) ? $params[$fieldName] : $fieldData['def_value']);

            displayFormElement($fieldName, $fieldData, $clrNumber);
            $clrNumber = ($clrNumber == 2) ? 1 : 2;
        }

?>

<input type="hidden" name="cfg_install_db_step" value="1" />

<?php

        $output = ob_get_contents();
        ob_end_clean();

        $messageText =<<<OUT
<p>
 <b><font color="darkgreen">The Installation Wizard needs to know your web server and MySQL database details:</font></b>
</p>
OUT;

        $bottomMessage = 'Push the "Next" button below to continue';


    // Display second step: review parameters and enter additional data
    } else {

        // Now checking if database named $params[mysqlbase] already exists

        $checkError = false;

        // Check if web server host and web_dir provided are valid
        $url = 'http://' . $params['xlite_http_host'] . $params['xlite_web_dir'] . '/COPYRIGHT';

        if (@file_get_contents('./COPYRIGHT') !== @file_get_contents($url)) {
            fatal_error('The web server name and/or web drectory is invalid! Press \'BACK\' button and review web server settings you provided');
            $checkError = true;

        // Check if database settings provided are valid
        } else {
 
            $connection = @mysql_connect($params['mysqlhost'] . (!empty($params['mysqlport']) ? ':' . $params['mysqlport'] : ''), $params['mysqluser'], $params['mysqlpass']);

            if ($connection) {

                // Check MySQL version
                $mysqlVersionErr = $currentMysqlVersion = '';

                if (!checkMysqlVersion($mysqlVersionErr, $currentMysqlVersion, $connection)) {
                    warning_error($mysqlVersionErr);
                }

                // Check if config.php file is writeable
                if (!@is_writable(LC_CONFIG_DIR . constant('LC_CONFIG_FILE'))) {
                    fatal_error('Cannot open file "' . constant('LC_CONFIG_FILE') . '" for writing. To install the software, please correct the problem and start the installation again...');
                    $checkError = true;

                } else {

                    if (!@mysql_select_db($params['mysqlbase'])) {

                        @mysql_query('SET sql_mode="MYSQL40"');

                        if (!@mysql_query('CREATE DATABASE ' . $params['mysqlbase'])) {
                            fatal_error('Installer couldn\'t create database "' . $params['mysqlbase'] . '". Please create it manually or ask your hosting provider to do it for you.');
                            $checkError = true;

                        }
            
                    } else {
            
                        @mysql_query('SET sql_mode="MYSQL40"');
                        $mystring = '';
                        $first = true;
            
                        $res = @mysql_list_tables($params['mysqlbase']);

                        while ($row = @mysql_fetch_row($res)) {

                            $ctable = $row[0];

                            if ($ctable == 'xlite_products') {
                                warning_error('Installation Wizard has detected that the specified database has existing LiteCommerce tables. If you continue with the instalaltion, the tables will be purged.');
                                break;
                            }
                        }

                        @mysql_close ($mylink);
                    }
                }

            } else {
                fatal_error('Can\'t connect to MySQL server specified. Press \'BACK\' button and review MySQL server settings you provided.');
                $checkError = true;
            }
        } 

        if (!$checkError) {

            ob_start();

            foreach ($paramFields as $fieldName => $fieldData) {

                if (!isset($fieldData['step']) || $fieldData['step'] != 2) {
                    $fieldData['type'] = 'static';
                    $fieldData['value'] = (isset($params[$fieldName]) ? $params[$fieldName] : '');
                }

                displayFormElement($fieldName, $fieldData, $clrNumber);
                $clrNumber = ($clrNumber == 2) ? 1 : 2;
            }

            $output = ob_get_contents();
            ob_end_clean();

        } else {
            $output = '';
        }

        if (!$checkError) {
            $bottomMessage = 'Push the "Next" button below to begin the installation';

        } else {
            $error = true;
        }

    }

?>

<center>
<?php echo $messageText; ?>
<table width="100%" border="0" cellpadding="4">

<?php echo $output; ?>

</table>

<br />

<?php message($bottomMessage); ?>

</center>

<br />

<?php

    return $displayConfigForm;
}


/**
 * Output Javascript handler: module_cfg_install_db_js_back 
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_cfg_install_db_js_back()
{
    global $params;

    // 1 - step back; 2 - step 2/initial form
    $goBack = (!isset($_POST['cfg_install_db_step']) ? '1' : '2');

?>
    function step_back() {
        document.ifrm.current.value = "<?php echo $goBack; ?>";
        document.ifrm.submit();

        return true;
    }
<?php
}

/**
 * Output Javascript handler: module_cfg_install_db_js_next 
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_cfg_install_db_js_next()
{
?>
    function step_next() {
        for (var i = 0; i < document.ifrm.elements.length; i++) {

            if (document.ifrm.elements[i].name.search("xlite_http_host") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert("You must provide web server name");
                    return false;
                }
            }

            if (document.ifrm.elements[i].name.search("mysqlhost") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert ("You must provide MySQL server name");
                    return false;
                }
            }

            if (document.ifrm.elements[i].name.search("mysqluser") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert ("You must provide MySQL username");
                    return false;
                }
            }

            if (document.ifrm.elements[i].name.search("mysqlbase") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert ("You must provide MySQL database name");
                    return false;
                }
            }
        }
        return true;
    }
<?php
}


/**
 * Database installing module
 * 
 * @param array $params 
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_install_db(&$params)
{
    global $error;
    $clrNumber = 2;

?>
</TD>
</TR>
</TABLE>

<SCRIPT language="javascript">
    loaded = false;

    function refresh() {
        window.scroll(0, 100000);

        if (loaded == false)
            setTimeout('refresh()', 1000);
    }

    setTimeout('refresh()', 1000);
</SCRIPT>

<?php

    $result = doInstallDatabase('all', $params);

    if ($result) {
        $result = doMoveImagesToFs($params);
    }

?>

<table class="TableTop" width="100%" border="0" cellspacing="0" cellpadding="0">

<tr>
    <td>

        <center>
<?php if ($result) { ?><BR><?php message("Push the \"Next\" button below to continue"); } ?>
        </center>

        <br />

<script language="javascript">
    loaded = true;
</script>

<?php

    $error = !$result;

    return false;

} 


/**
 * Install_dirs module
 * 
 * @param array $params 
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_install_dirs(&$params)
{
    global $error, $lcSettings;

    $clrNumber = 2;

?>
</TD>
</TR>
</TABLE>

<SCRIPT language="javascript">
    loaded = false;

    function refresh() {
        window.scroll(0, 100000);

        if (loaded == false)
            setTimeout('refresh()', 1000);
    }

    setTimeout('refresh()', 1000);
</SCRIPT>

<?php

    $result = doInstallDirs($params);

    if (!$result) {
        fatal_error("Fatal error encountered while creating directories, probably because of incorrect directory permissions. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");
    }

?>

<table class="TableTop" width="100%" border="0" cellspacing="0" cellpadding="0">

<tr>
  <td>

<center>
<?php

     if ($result) {

?>
            <br />
<?php
        message('Push the "Next" button below to continue'); 
     }
?>

</center>

<input type="hidden" name="ck_res" value="<?php echo intval($result); ?>">

<?php

    if (is_null($params['new_installation'])) {

?>

<tr>
    <td colspan="2">
        <input type="hidden" name="params[force_current]" value="<?php echo get_step('install_done'); ?>" />
    </td>
</tr>

<?php
    }
?>    
    
<br />

<script language="javascript">
    loaded = true;
</script>

<?php

    $error = !$result;

    return false;
}


/**
 * Output form for gathering admi account data
 * 
 * @param array $params 
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_cfg_create_admin(&$params)
{
    global $error;

    $paramFields = array(
        'login'             => array(
            'title'       => 'E-mail',
            'description' => 'E-mail address of the store administrator',
            'def_value'   => isset($params['login']) ? $params['login'] : '',
            'required'    => true,
            'type'        => 'text'
        ),
        'password'          => array(
            'title'       => 'Password',
            'description' => '',
            'def_value'   => isset($params['password']) ? $params['password'] : '',
            'required'    => true,
            'type'        => 'password'
        ),
        'confirm_password'  => array(
            'title'       => 'Confirm password',
            'description' => '',
            'def_value'   => isset($params['confirm_password']) ? $params['confirm_password'] : '',
            'required'    => true,
            'type'        => 'password'
        ),
    );

    $clrNumber = 1;

?>

<CENTER>
<P>
 <B><FONT color="darkgreen">Creating administrator profile</FONT></B>
</P>


<TABLE width="60%" border=0 cellpadding=4>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
   <td colspan=2>
<p align=justify>E-mail and password that you provide on this screen will be used to create primary administrator profile. Use them as credentials to access the Administrator Zone of your online store.</p>
   <p>
   </td>
 </tr>

<?php

    foreach ($paramFields as $fieldName => $fieldData) {
        displayFormElement($fieldName, $fieldData, $clrNumber);
        $clrNumber = ($clrNumber == 2) ? 1 : 2;
    }

    if (is_null($params["new_installation"])) {

?>
    <TR>
        <TD colspan="2">
            <input type="hidden" name="params[force_current]" value="<?php echo get_step("install_done")?>">    
        </TD>
    </TR>

<?php
    }
?>

</TABLE>
<P>

<?php
}

/**
 * cfg_create_admin module "Next" button validator 
 * 
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_cfg_create_admin_js_next()
{
?>
    function step_next() {

        // validate login
        //
        if (!checkEmailAddress(document.ifrm.elements['params[login]'])) {
            return false;
        }

        // validate password and password confirm
        //
        if (document.ifrm.elements['params[password]'].value == "") {
            alert('Please, enter non-empty password');
            return false;
        }    
        if (document.ifrm.elements['params[confirm_password]'].value == "") {
            alert('Please, enter non-empty password confirmation');
            return false;
        }    
        if (document.ifrm.elements['params[password]'].value != document.ifrm.elements['params[confirm_password]'].value) {
            alert("Password doesn't match confirmation!");
            return false;
        }
        return true;
    }

    function checkEmailAddress(field) {

        var goodEmail = field.value.search(/^(\S+@)[^\.][A-Za-z0-9_\-\.]+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\.biz)|(\.us)|(\.bizz)|(\.coop)|(\..{2,2}))[ ]*$/gi);

        if (goodEmail != -1) {
            return true;
        } else {
            alert("Please, specify a valid e-mail address!");
            field.focus();
            field.select();
            return false;
    }
}

<?php
}


/**
 * Install_done module
 * 
 * @param array $params 
 *  
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  3.0.0
 */
function module_install_done(&$params)
{
    // if authcode IS NULL, then this is probably the first install, skip
    $checkParams = array('auth_code', 'login', 'password', 'confirm_password');

    $accountParams = true;

    // Check parameters for creating an administrator account
    foreach ($checkParams as $paramValue) {
        $accountParams = $accountParams && (isset($paramValue) && strlen(trim($paramValue)) > 0);
    }

    // create/update admin account from the previous step
    if ($accountParams) {
        doCreateAdminAccount($params);
    }

    doFinishInstallation($params);

    return false;
}

/*
 * End of Modules section
 */

