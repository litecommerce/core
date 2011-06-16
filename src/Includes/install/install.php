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
 * LiteCommerce installation procedures
 *
 * @package LiteCommerce
 * @see     ____class_see____
 * @since   1.0.0
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}


/**
 * Test class for checking of DocBlock feature support
 *
 * @param  Test param
 * @return Returned value
 */
class InstallTestDockblocks { }

/**
 * Returns a processed text by specified label value
 *
 * @param $label string Label value
 * @param $substitute array Array for substitution parameters in the text found by label
 *
 * @return string
 * @see    ____func_see____
 * @since  1.0.0
 */
function xtr($label, array $substitute = array())
{
    $text = getTextByLabel($label);

    if (!empty($substitute)) {
        foreach ($substitute as $key => $value) {
            $text = str_replace($key, $value, $text);
        }
    }

    return $text;
}

/**
 * Returns a text by label. If label not found in translation file then label itself will be returned
 *
 * @param $label string Label value
 *
 * @return string
 * @see    ____func_see____
 * @since  1.0.0
 */
function getTextByLabel($label)
{
    $result = $label;

    static $translation;

    if (!isset($translation)) {

        // Get language code from cookies...
        if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang'])) {
            $languageCode = $_COOKIE['lang'];

        // or from main installation settings
        } else {
            global $lcSettings;
            $languageCode = $lcSettings['default_language_code'];
        }

        // Check if language code value is satisfied to alpha-2 pattern for security reasons
        if (!preg_match('/^[a-z]{2}$/', $languageCode)) {
            $languageCode = 'en';
        }

        // Generate name of file that should contain language variables
        $labelsFile = constant('LC_DIR_ROOT') . 'Includes/install/translations/' . $languageCode . '.php';

        // Check if this file exists and include it (it must be correct php script, that is contained $translation array)
        if (file_exists($labelsFile)) {
            include_once $labelsFile;
        }
    }

    // Check if label value defined in translation array and assign this as a result
    if (!empty($translation[$label])) {
        $result = $translation[$label];
    }

    return $result;
}


/**
 * Logging functions
 */


/**
 * Write a record to log
 *
 * @param $message string The log message
 *
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */

function x_install_log($message = null)
{
    $fileName =  LC_DIR_VAR . 'log' . LC_DS . 'install_log.' . date('Y-m-d') . '.php';
    $securityHeader = "<?php die(1); ?>\n";

    if (!file_exists($fileName) || $securityHeader > filesize($fileName)) {
        @file_put_contents($fileName, $securityHeader);
    }

    $args = func_get_args();

    $message = array_shift($args);

    if (empty($message)) {
        $message = 'Debug info';
    }

    $currentDate = date(DATE_RFC822);

    $port = $_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '';

    $protocol = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http';

    $output =<<< OUT


--------------------------------------------------------------------
[$currentDate]
[{$_SERVER['REQUEST_METHOD']}, {$_SERVER['SERVER_PROTOCOL']}] {$protocol}://{$_SERVER['HTTP_HOST']}{$port}{$_SERVER['REQUEST_URI']}
[{$_SERVER['SERVER_SOFTWARE']}]
$message

OUT;

    if (!empty($args)) {

        ob_start();

        foreach ($args as $value) {
            var_export($value);
        }

        $varDump = ob_get_contents();
        ob_end_clean();

        $output .= $varDump;
    }

    @file_put_contents($fileName, $output, FILE_APPEND);
}

/**
 * Mask some private data in the $params array to avoid this to be passeded to the log
 *
 * @param $param array An array $_POST or $_GET
 *
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function x_install_log_mask_params($params)
{
    static $fieldsToMask = array(
        'auth_code',
        'mysqlpass',
        'password',
        'confirm_password',
        'pass1',
        'pass2',
    );

    foreach ($params as $key => $value) {
        if (is_array($value)) {
            $params[$key] = x_install_log_mask_params($value);

        } elseif (in_array($key, $fieldsToMask)) {
            $params[$key] = empty($value) ? '<empty>' : '<specified>';
        }
    }

    return $params;
}


/*
 * Checking requirements section
 */


/**
 * Perform the requirements checking
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function doCheckRequirements()
{
    $checkRequirements = array();

    $checkRequirements['lc_install_script'] = array(
        'title'    => xtr('Installation script'),
        'critical' => true,
    );

    $checkRequirements['lc_loopback'] = array(
        'title'    => xtr('Loopback test'),
        'critical' => false,
        'depends' => 'lc_install_script'
    );

    $checkRequirements['lc_php_version'] = array(
        'title'    => xtr('PHP version'),
        'critical' => true,
    );

    $checkRequirements['lc_php_disable_functions'] = array(
        'title'    => xtr('Disabled functions'),
        'critical' => true,
    );

    $checkRequirements['lc_php_memory_limit'] = array(
        'title'    => xtr('Memory limit'),
        'critical' => true,
    );

    $checkRequirements['lc_php_file_uploads'] = array(
        'title'    => xtr('File uploads'),
        'critical' => true,
    );

    $checkRequirements['lc_php_mysql_support'] = array(
        'title'    => xtr('MySQL support'),
        'critical' => true,
    );

    $checkRequirements['lc_php_pdo_mysql'] = array(
        'title'    => xtr('PDO extension'),
        'critical' => true,
        'depends'  => 'lc_php_mysql_support'
    );

    $checkRequirements['lc_php_upload_max_filesize'] = array(
        'title'    => xtr('Upload file size limit'),
        'critical' => false,
    );

    $checkRequirements['lc_mem_allocation'] = array(
        'title'    => xtr('Memory allocation test'),
        'critical' => false,
        'depends'  => 'lc_loopback'
    );

    $checkRequirements['lc_recursion_test'] = array(
        'title'    => xtr('Recursion test'),
        'critical' => false,
        'depends'  => 'lc_loopback'
    );

    $checkRequirements['lc_file_permissions'] = array(
        'title'    => xtr('File permissions'),
        'critical' => true,
    );

    $checkRequirements['lc_mysql_version'] = array(
        'title'    => xtr('MySQL version'),
        'critical' => true,
        'depends'  => 'lc_php_mysql_support'
    );

    $checkRequirements['lc_php_gdlib'] = array(
        'title'    => xtr('GDlib extension'),
        'critical' => false,
    );

    $checkRequirements['lc_php_phar'] = array(
        'title'    => xtr('Phar extension'),
        'critical' => false,
    );

    $checkRequirements['lc_https_bouncer'] = array(
        'title'    => xtr('HTTPS bouncers'),
        'critical' => false,
    );

    $checkRequirements['lc_xml_support'] = array(
        'title'    => xtr('XML extensions support'),
        'critical' => false,
    );

    $checkRequirements['lc_docblocks_support'] = array(
        'title'    => xtr('DocBlocks support'),
        'critical' => true,
    );


    $passed = array();

    $requirementsOk = true;

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
                die(xtr('Internal error: function :func() does not exists', array(':func' => $funcName)));
            }

            // Check requirement and init its properies
            $errorMsg = $value = null;
            $checkRequirements[$reqName]['status'] = $funcName($errorMsg, $value);
            $checkRequirements[$reqName]['description'] = $errorMsg;
            $checkRequirements[$reqName]['value'] = $value;

            $requirementsOk = $requirementsOk && $checkRequirements[$reqName]['status'];
            $passed[] = $reqName;
        }
    }

    if ($requirementsOk) {
        x_install_log(xtr('Checking requirements is successfully complete'));
        x_install_log(xtr('Requirements log'), $checkRequirements);
    }

    return $checkRequirements;
}

/**
 * Check if DocBlock feature is supported
 *
 * @param string $errorMsg Error message if checking failed
 * @param string $value    Actual value of the checked parameter
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function checkDocblocksSupport(&$errorMsg, $value = null)
{
    $rc = new ReflectionClass('InstallTestDockblocks');

    $docblock = $rc->getDocComment();

    $result = !empty($docblock) && preg_match('/@(param|return)/', $docblock);

    if (!$result) {
        $errorMsg = xtr('DockBlock is not supported message');

        if (extension_loaded('eAccelerator')) {
            $errorMsg .= ' ' . xtr('eAccelerator loaded message');
        }
    }

    return $result;
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
 * @since  1.0.0
 */
function checkInstallScript(&$errorMsg, $value = null)
{
    $result = @file_exists(LC_DIR_ROOT . 'install.php');

    if (!$result) {
        $errorMsg = xtr('LiteCommerce installation script not found. Restore it  and try again');
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
 * @since  1.0.0
 */
function checkLoopback(&$errorMsg, $value = null)
{
    $result = true;

    $response = inst_http_request_install("action=loopback_test");

    if (strpos($response, "LOOPBACK-TEST-OK") === false) {
        $result = false;
        $errorMsg = xtr('Loopback test failed. Response:') . "\n" . $response;
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
 * @since  1.0.0
 */
function checkPhpVersion(&$errorMsg, &$value)
{
    global $lcSettings;

    $result = true;

    $value = $currentPhpVersion = phpversion();

    if (version_compare($currentPhpVersion, constant('LC_PHP_VERSION_MIN')) < 0) {
        $result = false;
        $errorMsg = xtr('PHP Version must be :minver as a minimum', array(':minver' => constant('LC_PHP_VERSION_MIN')));
    }

    if ($result && constant('LC_PHP_VERSION_MAX') != '' && version_compare($currentPhpVersion, constant('LC_PHP_VERSION_MAX')) > 0) {
        $result = false;
        $errorMsg = xtr('PHP Version must be not greater than :maxver', array(':maxver' => constant('LC_PHP_VERSION_MAX')));
    }

    if ($result && isset($lcSettings['forbidden_php_versions']) && is_array($lcSettings['forbidden_php_versions'])) {

        foreach ($lcSettings['forbidden_php_versions'] as $fpv) {

            if (version_compare($currentPhpVersion, $fpv['min']) >= 0) {

                $result = false;

                if (isset($fpv['max']) && version_compare($currentPhpVersion, $fpv['max']) > 0) {
                    $result = true;

                } else {
                    $errorMsg = xtr('Unsupported PHP version detected');
                    break;
                }
            }
        }
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
 * @since  1.0.0
 */
function checkPhpDisableFunctions(&$errorMsg, &$value)
{
    $result = true;

    list($list, $allowed) = getDisabledFunctions();
    if (!empty($list)) {
        $result = false;
        $value = substr(@ini_get('disable_functions'), 0, 45) . '...';
        $errorMsg = xtr('Disabled functions discovered (:funclist) that must be enabled', array(':funclist' => implode(', ', $list)));

    } else {
        $value = 'none';
    }

    return $result;
}

/**
 * Get allowed value 'disable_functions' PHP option
 *
 * @return string
 * @see    ____func_see____
 * @since  1.0.0
 */
function getAllowedDisableFunctionsValue()
{
    list($value, $allowed) = getDisabledFunctions();

    return implode(',', $allowed);
}

/**
 * Get disabled functions lists
 *
 * @return array (unallowed & allowed)
 * @see    ____func_see____
 * @since  1.0.0
 */
function getDisabledFunctions()
{
    static $usedFunctions = array(
        'call_user_func', 'is_null', 'doubleval', 'define', 'explode', 'join', 'time', 'addslashes', 'array_keys', 'ceil',
        'preg_match', 'preg_replace', 'serialize', 'unserialize', 'is_array', 'error_reporting', 'parse_url', 'strpos', 'setcookie', 'in_array',
        'is_object', 'is_string', 'dirname', 'fopen', 'fwrite', 'fclose', 'file_get_contents', 'opendir', 'readdir', 'is_file',
        'substr', 'closedir', 'str_replace', 'array_merge', 'count', 'strcasecmp', 'urldecode', 'memory_get_usage', 'printf', 'file_exists',
        'function_exists', 'gettype', 'htmlspecialchars', 'is_readable', 'pathinfo', 'basename', 'strlen', 'strtr', 'header', 'array_unique',
        'array_values', 'is_scalar', 'stristr', 'is_writable', 'ini_get', 'ini_set', 'strtolower', 'strcspn', 'parse_ini_file', 'realpath',
        'chmod', 'current', 'implode', 'array_map', 'intval', 'filesize', 'fread', 'md5', 'is_integer', 'urlencode',
        'curl_version', 'curl_init', 'curl_setopt', 'curl_exec', 'curl_errno', 'curl_error', 'curl_close', 'exec', 'unlink', 'proc_open',
        'is_resource', 'fputs', 'feof', 'proc_close', 'trim', 'sys_get_temp_dir', 'tempnam', 'xml_get_error_code', 'xml_error_string', 'xml_get_current_byte_index',
        'xml_parser_create', 'xml_parse_into_struct', 'xml_parser_free', 'substr_count', 'str_repeat', 'preg_grep', 'is_writeable', 'strtoupper', 'array_key_exists', 'array_search',
        'fgets', 'getimagesize', 'max', 'next', 'array_shift', 'min', 'mysql_insert_id', 'print_r', 'is_numeric', 'sprintf',
        'round', 'func_get_args', 'get_class', 'split', 'umask', 'uasort', 'strcmp', 'array_multisort', 'method_exists', 'var_dump',
        'call_user_func_array', 'file', 'log', 'microtime', 'get_included_files', 'usort', 'array_sum', 'number_format', 'debug_backtrace', 'array_slice',
        'readfile', 'file_put_contents', 'glob', 'is_uploaded_file', 'rawurlencode', 'move_uploaded_file', 'copy', 'rand', 'imagecreatetruecolor', 'imagealphablending',
        'imagesavealpha', 'imagecopyresampled', 'imagedestroy', 'uniqid', 'each', 'reset', 'ip2long', 'srand', 'system', 'stripslashes',
        'array_intersect', 'preg_split', 'mysql_real_escape_string', 'array_combine', 'is_int', 'key', 'get_object_vars', 'property_exists', 'mysql_select_db', 'mysql_fetch_row',
        'mysql_free_result', 'mysql_fetch_assoc', 'mysql_query', 'mysql_errno', 'mysql_error', 'flush', 'mysql_list_fields', 'mysql_num_fields', 'mysql_field_table', 'mysql_field_name',
        'mysql_field_type', 'mysql_field_len', 'mysql_field_flags', 'strncmp', 'array_flip', 'ob_start', 'ob_end_clean', 'extension_loaded', 'ord', 'array_splice',
        'mt_rand', 'imagecolorallocate', 'imagefilledrectangle', 'imagesx', 'imagesy', 'sin', 'imagecolorat', 'floor', 'imagesetpixel', 'imagedashedline',
        'imagecreatefrompng', 'imagecopymerge', 'chr', 'is_dir', 'array_reverse', 'base64_encode', 'strval', 'class_exists', 'ucfirst', 'strrpos',
        'mkdir', 'rename', 'rtrim', 'array_unshift', 'array_push', 'hash', 'imagepng', 'mktime', 'strtotime', 'date',
        'iconv', 'set_time_limit', 'getdate', 'strftime', 'ob_get_contents', 'strip_tags', 'sort', 'array_chunk', 'mysql_get_server_info', 'mysql_get_client_info',
        'getcwd', 'phpinfo', 'gd_info', 'fileperms', 'base_convert', 'asort', 'array_diff', 'array_pop', 'strstr', 'mt_srand',
        'hash_hmac', 'pack', 'str_pad', 'version_compare', 'get_class_methods', 'defined', 'getenv', 'parse_str', 'popen', 'pclose',
        'ksort', 'floatval', 'abs', 'var_export', 'base64_decode', 'strspn', 'bin2hex', 'ltrim', 'preg_match_all',
        'sizeof', 'range', 'array_filter', 'array_fill', 'imagecreate', 'imagerectangle', 'imagestring', 'imagejpeg', 'strrev', 'htmlentities',
        'chdir', 'openssl_pkey_get_public', 'str_split', 'openssl_public_encrypt', 'openssl_get_privatekey', 'openssl_private_decrypt', 'openssl_free_key', 'mysql_connect', 'nl2br', 'escapeshellarg',
        'get_parent_class', 'ob_flush', 'ob_get_length', 'ob_end_flush', 'get_magic_quotes_gpc', 'ob_clean', 'ftp_connect', 'ftp_login', 'ftp_fput', 'ftp_quit',
        'get_html_translation_table', 'ucwords', 'is_executable', 'arsort', 'krsort', 'is_callable', 'end', 'http_build_query', 'array_intersect_key', 'array_fill_keys',
        'array_intersect_assoc', 'filemtime', 'touch', 'date_format', 'array_pad', 'gmdate', 'preg_quote', 'set_error_handler', 'constant', 'is_bool',
        'is_float', 'curl_getinfo', 'curl_setopt_array', 'stream_get_transports', 'stream_context_create', 'stream_context_set_option', 'stream_socket_client', 'stream_socket_enable_crypto', 'stream_set_timeout', 'stream_get_meta_data',
        'hexdec', 'rewind', 'gzinflate', 'unpack', 'crc32', 'gzuncompress', 'fstat', 'phpversion', 'rawurldecode', 'extract',
        'gzopen', 'bzopen', 'gzclose', 'bzclose', 'gzputs', 'bzwrite', 'gzread', 'bzread', 'gzseek', 'gztell',
        'fseek', 'ftell', 'stat', 'clearstatcache', 'gzeof', 'error_log', 'mail', 'register_shutdown_function', 'headers_sent', 'sqlite_close',
        'sqlite_escape_string', 'sqlite_unbuffered_query', 'sqlite_query', 'sqlite_num_rows', 'is_a', 'fflush', 'fsockopen', 'flock', 'octdec', 'openlog',
        'syslog', 'closelog', 'filter_var', 'escapeshellcmd', 'openssl_pkcs7_sign', 'openssl_error_string', 'get_magic_quotes_runtime', 'set_magic_quotes_runtime', 'chunk_split', 'addcslashes',
        'stream_get_filters', 'stream_filter_append', 'stream_get_contents', 'stream_filter_remove', 'html_entity_decode', 'openssl_pkey_get_private', 'openssl_sign', 'sha1', 'restore_error_handler', 'socket_set_timeout',
        'socket_get_status', 'getservbyname', 'gethostbyname', 'socket_set_blocking', 'stream_set_write_buffer', 'stream_select', 'trigger_error', 'imagecopy', 'imageconvolution',
        'natcasesort', 'ctype_xdigit', 'ob_get_clean', 'ctype_digit', 'is_infinite', 'str_ireplace', 'array_reduce', 'create_function', 'preg_last_error', 'json_encode',
        'json_decode', 'simplexml_load_string', 'strtok', 'class_parents', 'posix_isatty', 'get_defined_vars', 'getmypid', 'stripos', 'array_change_key_case', 'spliti',
        'checkdate', 'checkdnsrr', 'soundex', 'acos', 'pi', 'cos', 'func_num_args', 'func_get_arg', 'is_subclass_of', 'localeconv',
        'get_declared_classes', 'array_udiff', 'get_declared_interfaces', 'php_strip_whitespace', 'class_implements', 'interface_exists', 'rsort', 'preg_replace_callback', 'scandir', 'rmdir',
        'dir', 'array_diff_key', 'gzcompress', 'link', 'mysql_fetch_array', 'substr_replace', 'mysql_list_tables', 'mysql_close', 'php_sapi_name', 'date_default_timezone_set',
        'date_default_timezone_get', 'set_include_path', 'get_include_path', 'spl_autoload_register', 'chop', 'sleep',
    );

    $value = @ini_get('disable_functions');

    $intersect = array();
    $allowed = array();

    if (!empty($value)) {
        $list = array_map('trim', explode(',', $value));
        $list = array_unique($list);
        $intersect = array_intersect($list, $usedFunctions);
        $allowed = array_diff($list, $usedFunctions);
    }

    return array($intersect, $allowed);
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
 * @since  1.0.0
 */
function checkPhpMemoryLimit(&$errorMsg, &$value)
{
    $result = true;

    $value = @ini_get("memory_limit");

    if (is_disabled_memory_limit()) {
        $value = xtr('Unlimited');

    } else {

        $result = check_memory_limit($value, constant('LC_PHP_MEMORY_LIMIT_MIN'));

        if (!$result) {
            $errorMsg = xtr('PHP memory_limit option value should be :minval as a minimum', array(':minval' => constant('LC_PHP_MEMORY_LIMIT_MIN')));
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
 * @since  1.0.0
 */
function checkPhpFileUploads(&$errorMsg, &$value)
{
    $result = true;

    $value = (ini_get('file_uploads') ? 'On' : 'Off');

    if ('off' == strtolower($value)) {
        $result = false;
        $errorMsg = xtr('PHP file_uploads option value should be On');
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
 * @since  1.0.0
 */
function checkPhpMysqlSupport(&$errorMsg, &$value)
{
    $result = true;

    if (!function_exists('mysql_connect')) {
        $result = false;
        $value = 'Off';
        $errorMsg = xtr('Support MySQL is disabled in PHP. It must be enabled.');

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
 * @since  1.0.0
 */
function checkPhpPdoMysql(&$errorMsg, &$value)
{
    $result = true;

    $info = get_info();

    $value = $info['pdo_drivers'];

    if (!preg_match('/mysql/', $info['pdo_drivers']) || !class_exists('PDO')) {
        $result = false;
        $errorMsg = xtr('PDO extension with MySQL support must be installed.');
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
 * @since  1.0.0
 */
function checkPhpUploadMaxFilesize(&$errorMsg, &$value)
{
    $result = true;

    $value = @ini_get("upload_max_filesize");

    if (empty($value)) {
        $result = false;
        $errorMsg = xtr('PHP option upload_max_filesize should contain a value. It is empty currently.');
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
 * @since  1.0.0
 */
function checkMemAllocation(&$errorMsg, &$value)
{
    $result = true;

    $sizes = array(32, 64, 128);

    $currentMemoryLimit = convert_ini_str_to_int(@ini_get('memory_limit'));

    if (max($sizes) > $currentMemoryLimit) {

        foreach ($sizes as $size) {

            if ($size > $currentMemoryLimit) {

                $response = inst_http_request_install("action=memory_test&size=$size");

                if (!(strpos($response, "MEMORY-TEST-SKIPPED") === false)) {
                    $value = 'MEMORY-TEST-SKIPPED';
                    break;
                }

                if (strpos($response, "MEMORY-TEST-OK") === false) {
                    $status = false;
                    $errorMsg = xtr('Memory allocation test failed. Response:') . "\n" . substr($response, 0, 255);
                    break;
                }

                $value = $size . 'M';
            }
        }
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
 * @since  1.0.0
 */
function checkRecursionTest(&$errorMsg, &$value)
{
    $result = true;

    $response = inst_http_request_install("action=recursion_test");

    if (strpos($response, "RECURSION-TEST-OK") === false) {
        $result = false;
        $errorMsg = xtr('Recursion test failed.');
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
 * @since  1.0.0
 */
function checkFilePermissions(&$errorMsg, &$value)
{
    global $lcSettings;

    $result = true;

    $perms = array();

    if (constant('LC_SUPHP_MODE') == "0") {

        $array = array();

        if (!@is_writable(constant('LC_DIR_ROOT'))) {
            $perms[] = 'chmod 0777 ' . constant('LC_DIR_ROOT');
        }

        foreach ($lcSettings['mustBeWritable'] as $object) {
            $array = array_merge($array, checkPermissionsRecursive(constant('LC_DIR_ROOT') . $object));
        }

        if (!empty($array)) {

            foreach ($array as $file => $perm) {

                if (LC_OS_IS_WIN) {
                    $perms[] = $file;

                } else {

                    if (is_dir($file)) {
                        $perms[] = 'find ' . $file . ' -type d -exec chmod 0777 {} \\;';
                        $perms[] = 'find ' . $file . ' -type f -exec chmod 0666 {} \\;';

                    } else {
                        $perms[] = 'chmod ' . $perm . ' ' . $file;
                    }
                }

                if (count($perms) > 25) {
                    break;
                }
            }
        }
    }

    if (count($perms) > 0) {
        $result = false;
        if (LC_OS_IS_WIN) {
            $errorMsg = xtr("Permissions checking failed. Please make sure that the following files have writable permissions:\n<br /><br /><i>:perms</i>", array(':perms' => implode("<br />\n", $perms)));

        } else {
            $errorMsg = xtr("Permissions checking failed. Please make sure that the following file permissions are assigned (UNIX only):\n<br /><br /><i>:perms</i>", array(':perms' => implode("<br />\n", $perms)));
        }
    }

    return $result;
}

/**
 * Check MySQL version: returns false only if version is gathered and it isn't suit
 *
 * @param string   $errorMsg   Error message if checking failed
 * @param string   $value      Actual value of the checked parameter
 * @param resource $connection MySQL connection link
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function checkMysqlVersion(&$errorMsg, &$value, $isConnected = false)
{
    $result = true;
    $value = xtr('unknown');
    $pdoErrorMsg = '';

    $version = false;

    if (defined('DB_URL')) {
        // Connect via PDO and get DB version

        $data = unserialize(constant('DB_URL'));

        // Support of Drupal 6 $db_url
        if (!is_array($data)) {
            $data = parseDbURL(constant('DB_URL'));
        }

        $isConnected = dbConnect($data, $pdoErrorMsg);

        if (!$isConnected) {
            $errorMsg = xtr('Can\'t connect to MySQL server') . (!empty($pdoErrorMsg) ? ': ' . $pdoErrorMsg : '');
        }
    }

    if ($isConnected) {

        try {
            $version = \Includes\Utils\Database::getDbVersion();

        } catch (Exception $e) {
            $pdoErrorMsg = $e->getMessage();
        }

        // Check version
        if ($version) {

            if (version_compare($version, constant('LC_MYSQL_VERSION_MIN')) < 0) {
                $result = false;
                $errorMsg = xtr('MySQL version must be :minver as a minimum.', array(':minver' => constant('LC_MYSQL_VERSION_MIN')));
            }

        } else {
            $errorMsg = xtr('Cannot get the MySQL server version') . (!empty($pdoErrorMsg) ? ' : ' . $pdoErrorMsg : '.');
        }
    }

    $value = $version;

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
 * @since  1.0.0
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
        $errorMsg = xtr('GDlib extension v.2.0 or later required for some modules.');
    }

    return $result;
}

/**
 * Check Phar extension
 *
 * @param string   $errorMsg   Error message if checking failed
 * @param string   $value      Actual value of the checked parameter
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function checkPhpPhar(&$errorMsg, &$value)
{
    $result = true;

    if (!extension_loaded('Phar')) {
        $errorMsg = xtr('Phar extension is not loaded');
        $result = false;
    }

    return $result;
}

/**
 * Check https bouncers presence (libcurl only checking)
 *
 * @param string   $errorMsg   Error message if checking failed
 * @param string   $value      Actual value of the checked parameter
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function checkHttpsBouncer(&$errorMsg, &$value)
{
    $result = true;

    if (!function_exists('curl_init') || !function_exists('curl_version')) {
        $result = false;
        $errorMsg = xtr('libcurl extension is not found');

    } else {

        $version = curl_version();

        if (is_array($version)) {

            $value = 'libcurl ' . $version['version'];

            if (!empty($version['ssl_version'])) {
                $value = $value . ', ' . $version['ssl_version'];
            }

        } else {
            $value = $version;
        }

        if (
            (is_array($version) && !in_array('https', $version['protocols']))
            || (!is_array($version) && !preg_match('/ssl|tls/Ssi', $version))
        ) {
            $errorMsg = xtr('libcurl extension found but it does not support secure protocols');
            $result = false;
        }
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
 * @since  1.0.0
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
        $errorMsg = xtr('XML/Expat and DOM extensions are required for some modules.');
    }

    return $result;
}

/*
 * End of Checking requirements section
 */


/**
 * Prepare the fixtures: the list of yaml files for uploading to the database
 *
 * @param array  $params     Database access data and other parameters
 * @param bool   $silentMode Do not display any output during installing
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function doPrepareFixtures(&$params, $silentMode = false)
{
    global $lcSettings;

    $result = true;

    if (!$silentMode) {
        echo '<div style="width: 100; text-align: left; margin-bottom: 20px;">' . xtr('Preparing data for cache generation...');
    }

    $enabledModules = array();

    foreach ($lcSettings['enable_modules'] as $moduleAuthor => $modules) {

        $enabledModules[$moduleAuthor] = array();

        foreach ($modules as $moduleName) {
            $enabledModules[$moduleAuthor][$moduleName] = 1;
        }
    }

    \Includes\Utils\ModulesManager::saveModulesToFile($enabledModules);

    // Generate fixtures list
    $yamlFiles = $lcSettings['yaml_files']['base'];

    $moduleYamlFiles = array();

    foreach ($lcSettings['enable_modules'] as $author => $modules) {

        foreach ($modules as $moduleName) {

            $moduleFile = sprintf('classes/XLite/Module/%s/%s/install.yaml', $author, $moduleName);

            if (file_exists(constant('LC_DIR_ROOT') . $moduleFile)) {
                $moduleYamlFiles[] = $moduleFile;
            }
        }
    }

    sort($moduleYamlFiles, SORT_STRING);

    foreach ($moduleYamlFiles as $f) {
        // Add module fixtures
        $yamlFiles[] = $f;
    }

    if ($params['demo']) {
        // Add demo dump to the fixtures
        foreach ($lcSettings['yaml_files']['demo'] as $f) {
            $yamlFiles[] = $f;
        }
    }

    // Remove fixtures file (if exists)
    \Includes\Decorator\Plugin\Doctrine\Utils\FixturesManager::removeFixtures();

    // Add fixtures list
    foreach ($yamlFiles as $file) {
        \Includes\Decorator\Plugin\Doctrine\Utils\FixturesManager::addFixtureToList($file);
    }

    if (!$silentMode) {
        echo status($result) . '</div>';
    }

    return $result;
}

function doUpdateConfig(&$params, $silentMode = false)
{
    // Update etc/config.php file
    $configUpdated = true;

    if (!$silentMode) {
        echo '<br /><b>' . xtr('Updating config file...') . '</b><br>';
    }

    $isConnected = dbConnect($params, $pdoErrorMsg);

    if ($isConnected) {

        // Write parameters into the config file
        if (@is_writable(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'))) {
            $configUpdated = change_config($params);

        } else {
            $configUpdated = false;
        }

        if (true !== $configUpdated && !$silentMode) {
            fatal_error(xtr('config_writing_error', array(':configfile' => constant('LC_CONFIG_FILE'))));
        }

    } elseif (!$silentMode) {
        fatal_error(xtr('mysql_connection_error', array(':pdoerr', (!empty($pdoErrorMsg) ? ': ' . $pdoErrorMsg : ''))));
    }

    return $configUpdated;
}

/**
 * Prepare to remove a cache of classes
 *
 * @param array $params Database access data and other parameters
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function doRemoveCache($params)
{
    $result = true;
    $pdoErrorMsg = '';

    \Includes\Decorator\Utils\CacheManager::cleanupCacheIndicators();

    \Includes\Decorator\Utils\CacheManager::cleanupRebuildIndicator();

    // Remove all LiteCommerce tables if exists
    $connection = dbConnect($params, $pdoErrorMsg);

    if ($connection) {

        // Check if LiteCommerce tables is already exists
        $res = dbFetchAll('SHOW TABLES LIKE \'xlite_%\'');

        if (is_array($res)) {

            dbExecute('SET FOREIGN_KEY_CHECKS=0', $pdoErrorMsg);

            foreach ($res as $row) {
                $tableName = array_pop($row);
                $pdoErrorMsg = '';

                $_query = sprintf('DROP TABLE `%s`', $tableName);
                dbExecute($_query, $pdoErrorMsg);

                if (!empty($pdoErrorMsg)) {
                    $result = false;
                    break;
                }
            }

            $pdoErrorMsg2 = '';
            dbExecute('SET FOREIGN_KEY_CHECKS=1', $pdoErrorMsg2);

            if (empty($pdoErrorMsg)) {
                $pdoErrorMsg = $pdoErrorMsg2;
            }
        }

    } else {
        $result = false;
    }

    if (!$result) {
        x_install_log(xtr('doRemoveCache() failed'), $pdoErrorMsg);
    }

    return $result;
}

/**
 * Generate a cache of classes
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function doBuildCache()
{
    $result = true;

    x_install_log(xtr('Cache building...'));

    ob_start();

    try {
        define('DO_ONE_STEP_ONLY', true);
        \Includes\Decorator\Utils\CacheManager::rebuildCache();

    } catch (\Exception $e) {
        $result = false;
    }

    $message = ob_get_contents();
    ob_end_clean();

    if (!$result) {
        x_install_log(xtr('Cache building procedure failed: :message', array(':message' => $e->getMessage())));
    }

    return $result;
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
 * @since  1.0.0
 */
function doInstallDirs($silentMode = false)
{
    global $error, $lcSettings;

    $result = true;

    if ($silentMode) {
        ob_start();
    }

    if ($result && !empty($lcSettings['writable_directories'])) {
        echo '<div class="section-title">' . xtr('Checking directories permissions...') . '</div>';
        chmod_others_directories($lcSettings['writable_directories']);
    }

    if (!empty($lcSettings['directories_to_create'])) {
        echo '<div class="section-title">' . xtr('Creating directories...') . '</div>';
        $result = create_dirs($lcSettings['directories_to_create']);
    }

    if ($result && !empty($lcSettings['files_to_create'])) {
        echo '<div class="section-title">' . xtr('Creating .htaccess files...') . '</div>';
        $result = create_htaccess_files($lcSettings['files_to_create']);
    }

    if ($result) {
        echo '<div class="section-title">Creating directories process is finished</div>';
    }

    if ($silentMode) {

        if (!$result) {
            $output = ob_get_contents();
        }

        ob_end_clean();

    } else {

        if (!$result) {
            fatal_error(xtr('fatal_error_creating_dirs'));
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
 * @since  1.0.0
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
        $errorMsg = fatal_error(xtr('Login and password can\'t be empty.'));

    } else {
        $password = md5($password);
    }

    $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin($login);

    if (is_null($profile)) {
        // Register default admin account

        $profile = new \XLite\Model\Profile();
        $profile->setLogin($login);

        echo xtr('Registering primary administrator profile...');

    } else {
        // Account already exists
        echo xtr('Updating primary administrator profile...');
    }

    $profile->setPassword($password);
    $profile->setAccessLevel(100);
    $profile->enable();

    $profile->create();

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
 * @since  1.0.0
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
        $install_rename_email = xtr('script_renamed_text', array(':newname' => $install_name, ':host' => $params['xlite_http_host'], ':webdir' => $params['xlite_web_dir']));

        // Text for confirmation web page
        $install_rename = xtr('script_renamed_text_html', array(':newname' => $install_name));

    } else {
        $install_rename = xtr('script_cannot_be_renamed_text');
        $install_rename_email = strip_tags($install_rename);
    }

    // Prepare files permissions recommendation text
    $perms = '';

    if (!LC_OS_IS_WIN) {

        $_perms = array();

        if (@is_writable(LC_DIR_ROOT)) {
            $_perms[] = 'chmod 755 ' . LC_DIR_ROOT;
        }

        if (@is_writable(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'))) {
            $_perms[] = "chmod 644 " . LC_DIR_CONFIG . constant('LC_CONFIG_FILE');
        }

        if (!empty($_perms)) {
            $perms = implode("<br />\n", $_perms);
            $perms = xtr('correct_permissions_text', array(':perms' => $perms));
        }
    }

    // Prepare email notification text
    $perms_no_tags = strip_tags($perms);

    $message = xtr(
        'congratulations_text',
        array(
            ':host' => $params['xlite_http_host'],
            ':webdir' => $params['xlite_web_dir'],
            ':login' => $params['login'],
            ':password' => $params['password'],
            ':perms' => $perms_no_tags,
            ':renametext' => $install_rename_email,
            ':authcode' => $authcode,

        )
    );

    // Send email notification to the admin account email
    @mail($params["login"], "LiteCommerce installation complete", $message,
        "From: \"LiteCommerce software\" <" . $params["login"] . ">\r\n" .
        "X-Mailer: PHP");

    if (!$silentMode) {

?>

<br />
<br />

<div class="field-label"><?php echo xtr('LiteCommerce software has been successfully installed and is now available at the following URLs:'); ?></div>

<br />

<a href="cart.php" class="final-link" target="_blank"><?php echo xtr('Customer zone (front-end)'); ?>: cart.php</a>

<br />
<br />

<a href="admin.php" class="final-link" target="_blank"><?php echo xtr('Administrator zone (backoffice)'); ?>: admin.php</a>

<br />
<br />
<br />

<?php echo $perms; ?>

<br />
<br />

<?php echo $install_rename; ?>

<?php echo xtr('Your auth code for running install.php in the future is:'); ?> <code><?php echo get_authcode(); ?></code>

<br />

<?php echo xtr('PLEASE WRITE THIS CODE DOWN UNLESS YOU ARE GOING TO REMOVE ":filename"', array(':filename' => $install_name)); ?>

<?php

    }

    x_install_log(xtr('Installation complete'));

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
 * @since  1.0.0
 */
function create_dirs($dirs)
{
    $result = true;

    $failedDirs = array();

    $dir_permission = 0777;

    $data = @parse_ini_file(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'));

    if(constant('LC_SUPHP_MODE') != 0) {
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;

    } else {
        $dir_permission = isset($data['nonprivileged_permission_dir']) ? base_convert($data['nonprivileged_permission_dir'], 8, 10) : 0755;
    }

    foreach ($dirs as $val) {
        echo xtr('Creating directory: [:dirname] ... ', array(':dirname' => $val));

        if (!@file_exists(constant('LC_DIR_ROOT') . $val)) {
            $res = @mkdir(constant('LC_DIR_ROOT') . $val, $dir_permission);
            $result &= $res;
            $failedDirs[] = constant('LC_DIR_ROOT') . $val;
            echo status($res);

        } else {
            echo '<span class="status-already-exists">' . xtr('Already exists') . '</span>';
        }

        echo "<br />\n"; flush();
    }

    if (!$result) {
        x_install_log(xtr('Failed to create directories'), $failedDirs);
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
 * @since  1.0.0
 */
function chmod_others_directories($dirs)
{
    if (constant('LC_SUPHP_MODE') != 0) {
        $data = @parse_ini_file(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'));
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;

        foreach($dirs as $dir) {
            echo $dir . '... ';
            $result = @chmod(constant('LC_DIR_ROOT') . $dir, $dir_permission);
            echo status($result);
        }

    } else {
        echo status(true);
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
 * @since  1.0.0
 */
function create_htaccess_files($files_to_create)
{
    $result = true;

    $failedFiles = array();

    if (is_array($files_to_create) && 0 < count($files_to_create)) {

        foreach($files_to_create as $file=>$content) {

            echo xtr('Creating file: [:filename] ... ', array(':filename' => $file));

            if ($fd = @fopen(constant('LC_DIR_ROOT') . $file, 'w')) {
                @fwrite($fd, $content);
                @fclose($fd);
                echo status(true);
                $result &= true;

            } else {
                echo status(false);
                $result = false;
                $failedFiles[] = constant('LC_DIR_ROOT') . $file;
            }

            echo "<BR>\n";
            flush();
        }
    }

    if (!$result) {
        x_install_log(xtr('Failed to create files'), $failedFiles);
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
 * @since  1.0.0
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

                closedir($handle);
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
 * @since  1.0.0
 */
function copy_files($source_dir, $parent_dir, $destination_dir, &$failedList)
{
    $result = true;

    $dir_permission = 0777;

    if (constant('LC_SUPHP_MODE') != 0) {
        $data = @parse_ini_file(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'));
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;
    }

    if ($handle = @opendir(constant('LC_DIR_ROOT') . $source_dir)) {

        while (($file = readdir($handle)) !== false) {

            $sourceFile = constant('LC_DIR_ROOT') . $source_dir . '/' . $file;
            $destinationFile = constant('LC_DIR_ROOT') . $destination_dir . '/' . $parent_dir . '/' . $file;

            // .htaccess files must be already presented in the destination directory and they should't have writable permissions for web server user
            if (@is_file($sourceFile) && $file != '.htaccess') {

                if (!@copy($sourceFile, $destinationFile)) {
                    echo xtr(
                        'Copying: [:source_dir:parent_dir/:file] to [:destination_dir:parent_dir/:file]... ',
                        array(
                            ':source_dir' => $source_dir,
                            ':parent_dir' => $parent_dir,
                            ':file' => $file,
                            ':destination_dir' => $destination_dir,
                        )
                    )
                    . status(false) . "<BR>\n";
                    $result = false;
                    $failedList[] = sprintf('copy(%s, %s)', $sourceFile, $destinationFile);
                }

                flush();

            } elseif (@is_dir($sourceFile) && $file != '.' && $file != '..') {

                echo xtr(
                    'Creating directory: [:destination_dir:parent_dir/:file]... ',
                    array(
                        ':destination_dir' => $destination_dir,
                        ':parent_dir' => $parent_dir,
                        ':file' => $file,
                    )
                );

                if (!@file_exists($destinationFile)) {

                    if (!@mkdir($destinationFile, $dir_permission)) {
                        echo status(false);
                        $result = false;
                        $failedList[] = sprintf('mkdir(%s)', $destinationFile);

                    } else {
                        echo status(true);
                    }

                } else {
                    echo '<span class="status-already-exists">' . xtr('Already exists') . '</span>';
                }

                echo "<br />\n";

                flush();

                $result &= copy_files($source_dir . '/' . $file, $parent_dir . '/' . $file, $destination_dir, $failedList);
            }
        }

        closedir($handle);

    } else {
        echo status(false) . "<br />\n";
        $result = false;
        $failedList[] = sprintf('opendir(%s)', constant('LC_DIR_ROOT') . $source_dir);
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
 * @since  1.0.0
 */
function change_config(&$params)
{
    global $installation_auth_code;

    // check whether config file is writable
    clearstatcache();

    if (!@is_readable(LC_DIR_CONFIG . constant('LC_CONFIG_FILE')) || !@is_writable(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'))) {
        return false;
    }

    // read file content
    if (!$config = file(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'))) {
        return false;
    }

    // fixing the empty xlite_https_host value
    if (!isset($params['xlite_https_host']) || $params['xlite_https_host'] == '') {
        $params['xlite_https_host'] = $params['xlite_http_host'];
    }

    $_params = $params;

    if (!isset($_params['mysqlpass'])) {
        $_params['mysqlpass'] = '';
    }

    if (!isset($_params['mysqlport'])) {
        $_params['mysqlport'] = '';
    }

    if (!isset($_params['mysqlsock'])) {
        $_params['mysqlsock'] = '';
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
            '/^socket.*=.*/',
            '/^http_host.*=.*/',
            '/^https_host.*=.*/',
            '/^web_dir.*=.*/'
        );

        $replacements = array(
            'hostspec = "' . $_params['mysqlhost'] . '"',
            'database = "' . $_params['mysqlbase'] . '"',
            'username = "' . $_params['mysqluser'] . '"',
            'password = "' . $_params['mysqlpass'] . '"',
            'port     = "' . $_params['mysqlport'] . '"',
            'socket   = "' . $_params['mysqlsock'] . '"',
            'http_host = "' . $_params['xlite_http_host'] . '"',
            'https_host = "' . $_params['xlite_https_host'] . '"',
            'web_dir = "' . $_params['xlite_web_dir'] . '"'
        );

        // check whether skin param is specified: not used at present
        if (isset($_params['skin'])) {
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
 * @since  1.0.0
 */
function save_config($content)
{
    $handle = fopen(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'), 'wb');
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
 * @since  1.0.0
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

    $dll_sfix = (LC_OS_IS_WIN ? '.dll' : '.so');

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
 * Do an HTTP request to the install.php
 *
 * @param string $action_str
 *
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function inst_http_request_install($action_str, $url = null)
{
    if (is_null($url)) {
        $url = getLiteCommerceURL();
    }

    $url_request = $url . '/install.php?target=install' . (($action_str) ? '&' . $action_str : '');

    return inst_http_request($url_request);
}

/**
 * Returns LiteCommerce URL
 *
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function getLiteCommerceURL()
{
    $host = 'http://' . $_SERVER['HTTP_HOST'];
    $host = '/' == substr($host, -1) ? substr($host, 0, -1) : $host;

    $uri = defined('LC_URI') ? constant('LC_URI') : $_SERVER['REQUEST_URI'];

    $web_dir = preg_replace('/\/install(\.php)*/', '', $uri);
    $url = $host . ('/' == substr($web_dir, -1) ? substr($web_dir, 0, -1) : $web_dir);

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
 * @since  1.0.0
 */
function inst_http_request($url_request)
{
    $result = null;
    $adapter = null;
    $response = null;
    $error = null;

    try {

        $bouncer = new \PEAR2\HTTP\Request($url_request);

        $result = $bouncer->sendRequest();
        $adapter = $bouncer->getAdapterName();

        $response = $result->body;

    } catch (\Exception $exception) {
        $error = $exception->getMessage();
    }

    x_install_log(
        'inst_http_request() result',
        array(
            'url_request' => $url_request,
            'adapter'     => $adapter,
            'result'      => $result,
            'response'    => $response,
            'error'       => $error,
        )
    );

    return $response;
}

/**
 * Check if memory_limit is disabled
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function is_disabled_memory_limit()
{
    $info = get_info();

    $result = (($info['no_mem_limit'] &&
                $info['commands_exists'] &&
                !function_exists('memory_get_usage') &&
                version_compare(phpversion(), '4.3.2') >= 0 &&
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
 * @since  1.0.0
 */
function check_memory_limit($current_limit, $required_limit)
{
    $result = true;

    $limit = convert_ini_str_to_int($current_limit);
    $required = convert_ini_str_to_int($required_limit);

    if ($limit < $required) {

		// workaround for http://bugs.php.net/bug.php?id=36568
        if (!LC_OS_IS_WIN && version_compare(phpversion(), '5.1.0') < 0) {
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
 * @since  1.0.0
 */
function is_php5()
{
    return version_compare(@phpversion(), '5.0.0') >= 0;
}

/**
 * Convert php_ini int string to int
 *
 * @param string $string
 *
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
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
 * @since  1.0.0
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
 * @since  1.0.0
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
 * @since  1.0.0
 */
function status($status, $code = null)
{
    global $first_error;

    if ($code != null) {

        if ($first_error == null && !$status) {
            $first_error = $code;
        }

        if ($status) {
            $return = '<span class="status-ok">OK</span>';

        } else {
            $return = '<a class="status-failed-link" id="failed-' . $code . '" href="javascript: showDetails(\'' . $code  . '\');" onclick=\'this.blur();\' title=\'' . xtr('Click here to see more details') . '\'>' . xtr('Failed') . '</a>';
        }

    } else {
        $return = $status
            ? '<span class="status-ok">OK</span>'
            : '<span class="status-failed">' . xtr('Failed') . '</span>';
    }

    return $return;
}

/**
 * Return status 'skipped' message
 *
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function status_skipped()
{
    return '<span class="status-skipped">' . xtr('Skipped') . '</span>';
}

/**
 * Display fatal_error message
 *
 * @param string $txt
 *
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function fatal_error($txt) {

    x_install_log(xtr('Fatal error') . ': ' . $txt);

?>
<div class="fatal-error"><?php echo xtr('Fatal error'); ?>: <?php echo $txt ?><br /><?php echo xtr('Please correct the error(s) before proceeding to the next step.'); ?></div>
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
 * @since  1.0.0
 */
function warning_error($txt) {

    x_install_log(xtr('Warning') . ': ' . $txt);

?>

<div class="warning-text">
    <?php echo xtr('Warning'); ?>: <?php echo $txt ?>
</div>
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
 * @since  1.0.0
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
 * @since  1.0.0
 */
function rename_install_script()
{
    $install_name = md5(uniqid(rand(), true)) . '.php';
    @rename(LC_DIR_ROOT . 'install.php', LC_DIR_ROOT . $install_name);
    @clearstatcache();

    $result = (!file_exists(LC_DIR_ROOT . 'install.php') && file_exists(LC_DIR_ROOT . $install_name) ? $install_name : false);

    if ($result) {
        x_install_log(xtr('Installation script renamed to :filename', array(':filename' => $install_name)));

    } else {
        x_install_log(xtr('Warning! Installation script renaming failed'));
    }

    return $result;
}

/**
 * Check if current protocol is HTTPS
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
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
 * @since  1.0.0
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
 * @since  1.0.0
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
 * @since  1.0.0
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
 * @since  1.0.0
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
 * @since  1.0.0
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
        warning_error(xtr('Incorrect auth code! You cannot proceed with the installation.'));
        exit();
    }
}

/**
 * Read config file and get Auth code
 *
 * @return mixed
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function get_authcode()
{
    if (!$data = @parse_ini_file(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'))) {
        fatal_error(xtr('Config file not found (:filename)', array(':filename' => constant('LC_CONFIG_FILE'))));
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
 * @since  1.0.0
 */
function save_authcode(&$params) {

    // if authcode set in request, don't change the config file
    if (isset($params['auth_code']) && trim($params['auth_code']) != '') {
        return $params['auth_code'];
    }

    // generate new authcode
    $auth_code = generate_authcode();

    if (!@is_writable(LC_DIR_CONFIG . constant('LC_CONFIG_FILE')) || !$config = file(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'))) {
        message(xtr('Cannot open config file \':filename\' for writing!', array(':filename' => constant('LC_CONFIG_FILE'))));
        exit();
    }

    $new_config = '';

    foreach ($config as $num => $line) {
        $new_config .= preg_replace('/^auth_code.*=.*/', 'auth_code = "'.$auth_code.'"', $line);
    }

    if (!save_config($new_config)) {
        message(xtr('Config file \':filename\' write failed!', array(':filename' => constant('LC_CONFIG_FILE'))));
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
 * @since  1.0.0
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
 * @since  1.0.0
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
            <option value="{$key}"{$_selected}>{$value}</option>
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
        <input type="checkbox" name="params[{$fieldName}]" value="Y" {$_checked} />
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
        <input type="{$fieldType}" name="params[{$fieldName}]" class="full-width" value="{$fieldValue}" />
OUT;
        }
    }

    $output =<<<OUT
    <tr class="color-{$clrNumber}">
        <td class="table-left-column"><div class="field-label">{$fieldData['title']}</div><div class="field-notice">{$fieldData['description']}</div></td>
        <td class="table-right-column">{$formElement}</td>
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
 * @since  1.0.0
 */
function module_default(&$params)
{
    global $error;

    include LC_DIR_ROOT . 'Includes/install/templates/step0_copyright.tpl.php';

    return false;
}

/**
 * 'Next' button handler. Checking if an 'Agree' checkbox was ticked
 *
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function module_default_js_next()
{
?>
    function step_next() {
        if (document.ifrm.agree.checked) {
            return true;
        } else {
            alert("<?php echo xtr('You must accept the License Agreement to proceed with the installation. If you do not agree with the terms of the License Agreement, do not install the software.'); ?>");
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
 * @since  1.0.0
 */
function module_check_cfg()
{
    global $first_error, $error, $report_uid, $reportFName, $tryAgain, $skinsDir;
    global $requirements;

    $requirements = doCheckRequirements();

    $errorsFound = false;
    $warningsFound = false;

    $sections = array(
        'A' => xtr('Environment checking'),
        'B' => xtr('Inspecting server configuration'),
    );

    $steps = array(
        1 => array(
            'title'        => xtr('Environment'),
            'error_msg'    => xtr('Environment checking failed'),
            'section'      => 'A',
            'requirements' => array(
                'lc_loopback'
            )
        ),
        2 => array(
            'title'        => xtr('Critical dependencies'),
            'error_msg'    => xtr('Critical dependency failed'),
            'section'      => 'B',
            'requirements' => array(
                'lc_php_version',
                'lc_php_disable_functions',
                'lc_php_memory_limit',
                'lc_docblocks_support',
                'lc_php_mysql_support',
                'lc_php_pdo_mysql',
                'lc_file_permissions'
            )
        ),
        3 => array(
            'title'        => xtr('Non-critical dependencies'),
            'error_msg'    => xtr('Non-critical dependency failed'),
            'section'      => 'B',
            'requirements' => array(
                'lc_php_file_uploads',
                'lc_php_upload_max_filesize',
                'lc_mem_allocation',
                'lc_recursion_test',
                'lc_php_gdlib',
                'lc_php_phar',
                'lc_https_bouncer',
                'lc_xml_support'
            )
        )
    );

    require_once LC_DIR_ROOT . 'Includes/install/templates/step1_chkconfig.tpl.php';

    $error = $tryAgain = $errorsFound || $warningsFound;

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
 * @since  1.0.0
 */
function module_cfg_install_db(&$params)
{
    global $error, $lcSettings;
    global $report_uid, $reportFName;
    global $checkRequirements;

    $pdoErrorMsg = '';
    $output = '';

    $clrNumber = 1;

    // Remove report file if it was created on the previous step
    if (@file_exists($reportFName)) {
        @unlink($reportFName);
        $report_uid = '';
    }

    $paramFields = array(
        'xlite_http_host'  => array(
            'title'       => xtr('Web server name'),
            'description' => xtr('Hostname of your web server (E.g.: www.example.com).'),
            'def_value'   => $_SERVER['HTTP_HOST'],
            'required'    => true
        ),
        'xlite_https_host' => array(
            'title'       => xtr('Secure web server name'),
            'description' => xtr('Hostname of your secure (HTTPS-enabled) web server (E.g.: secure.example.com). If omitted, it is assumed to be the same as the web server name.'),
            'def_value'   => $_SERVER['HTTP_HOST'],
            'required'    => true
        ),
        'xlite_web_dir'    => array(
            'title'       => xtr('LiteCommerce web directory'),
            'description' => xtr('Path to LiteCommerce files within the web space of your web server (E.g.: /shop).'),
            'def_value'   => preg_replace('/\/install(\.php)*/Ss', '', $_SERVER['REQUEST_URI']),
            'required'    => false
        ),
        'mysqlhost'        => array(
            'title'       => xtr('MySQL server name'),
            'description' => xtr('Hostname or IP address of your MySQL server.'),
            'def_value'   => 'localhost',
            'required'    => true
        ),
        'mysqlport'        => array(
            'title'       => xtr('MySQL server port'),
            'description' => xtr('If your database server is listening to a non-standard port, specify its number (e.g. 3306).'),
            'def_value'   => '',
            'required'    => false
        ),
        'mysqlsock'        => array(
            'title'       => xtr('MySQL server socket'),
            'description' => xtr('If your database server is used a non-standard socket, specify it (e.g. /tmp/mysql-5.1.34.sock).'),
            'def_value'   => '',
            'required'    => false
        ),
        'mysqlbase'        => array(
            'title'       => xtr('MySQL database name'),
            'description' => xtr('The name of the existing database to use (if the database does not exist on the server, you should create it to continue the installation).'),
            'def_value'   => '',
            'required'    => true
        ),
        'mysqluser'        => array(
            'title'       => xtr('MySQL username'),
            'description' => xtr('MySQL username. The user must have full access to the database specified above.'),
            'def_value'   => '',
            'required'    => true
        ),
        'mysqlpass'        => array(
            'title'       => xtr('MySQL password'),
            'description' => xtr('Password for the above MySQL username.'),
            'def_value'   => '',
            'required'    => false,
            'type'        => 'password',
        ),
        'demo'             => array(
            'title'       => xtr('Install sample catalog'),
            'description' => xtr('Specify whether you would like to setup sample categories and products?'),
            'def_value'   => '1',
            'required'    => false,
            'type'        => 'checkbox',
        )
    );

    $messageText = '';

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

        $output = ob_get_contents();
        ob_end_clean();

?>

<input type="hidden" name="cfg_install_db_step" value="1" />

<?php


    // Display second step: review parameters and enter additional data
    } else {

        // Now checking if database named $params[mysqlbase] already exists

        $checkError = false;
        $checkWarning = false;

        if (strstr($params['xlite_http_host'], ':')) {
            list($_host, $_port) = explode(':', $params['xlite_http_host']);

        } else {
            $_host = $params['xlite_http_host'];
        }

        if (!$_host) {
            fatal_error(xtr('The web server name and/or web drectory is invalid (:host). Press \'BACK\' button and review web server settings you provided', array(':host' => $_host)));
            $checkError = true;

        // Check if database settings provided are valid
        } else {

            $connection = dbConnect($params, $pdoErrorMsg);

            if ($connection) {

                // Check MySQL version
                $mysqlVersionErr = $currentMysqlVersion = '';

                if (!checkMysqlVersion($mysqlVersionErr, $currentMysqlVersion, true)) {
                    fatal_error($mysqlVersionErr . (!empty($currentMysqlVersion) ? '<br />(current version is ' . $currentMysqlVersion . ')' : ''));
                    $checkError = true;
                }

                // Check if config.php file is writeable
                if (!@is_writable(LC_DIR_CONFIG . constant('LC_CONFIG_FILE'))) {
                    fatal_error(xtr('Cannot open file \':filename\' for writing. To install the software, please correct the problem and start the installation again...', array(':filename' => constant('LC_CONFIG_FILE'))));
                    $checkError = true;

                } else {
                    // Check if LiteCommerce tables is already exists

                    $mystring = '';
                    $first = true;

                    $res = dbFetchAll('SHOW TABLES LIKE \'xlite_%\'');

                    if (is_array($res)) {

                        foreach ($res as $row) {
                            if (in_array('xlite_products', $row)) {
                                warning_error(xtr('Installation Wizard has detected LiteCommerce tables'));
                                $checkWarning = true;
                                break;
                            }
                        }
                    }
                }

            } else {
                fatal_error(xtr('Can\'t connect to MySQL server specified:pdoerr<br /> Press \'BACK\' button and review MySQL server settings you provided.', array(':pdoerr' => (!empty($pdoErrorMsg) ? ': ' . $pdoErrorMsg : ''))));
                $checkError = true;
            }
        }

        if (!$checkError && !$checkWarning) {

            global $autoPost;

            $autoPost = true;

        } else {
            $output = '';
        }

        $error = $checkError;
    }

?>

<?php echo $messageText; ?>

<table width="100%" border="0" cellpadding="10">

<?php echo $output; ?>

</table>

<?php

    return $displayConfigForm;
}


/**
 * Output Javascript handler: module_cfg_install_db_js_back
 *
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
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
 * @since  1.0.0
 */
function module_cfg_install_db_js_next()
{
?>
    function step_next() {
        for (var i = 0; i < document.ifrm.elements.length; i++) {

            if (document.ifrm.elements[i].name.search("xlite_http_host") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert("<?php echo xtr('You must provide web server name'); ?>");
                    return false;
                }
            }

            if (document.ifrm.elements[i].name.search("mysqlhost") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert ("<?php echo xtr('You must provide MySQL server name'); ?>");
                    return false;
                }
            }

            if (document.ifrm.elements[i].name.search("mysqluser") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert ("<?php echo xtr('You must provide MySQL username'); ?>");
                    return false;
                }
            }

            if (document.ifrm.elements[i].name.search("mysqlbase") != -1) {
                if (document.ifrm.elements[i].value == "") {
                    alert ("<?php echo xtr('You must provide MySQL database name'); ?>");
                    return false;
                }
            }
        }
        return true;
    }
<?php
}


/**
 * Building cache and installing database
 *
 * @param array $params
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function module_install_cache(&$params)
{
    global $error;

    $result = doPrepareFixtures($params);

    if ($result) {
        
        doRemoveCache(null);

?>

<iframe id="process_iframe" style="padding-top: 15px;" src="cart.php?doNotRedirectAfterCacheIsBuilt&<?php echo time(); ?>" width="100%" height="300" frameborder="0" marginheight="10" marginwidth="10"></iframe>

<br />
<br />
<br />

<?php echo xtr('Building cache notice'); ?>

<script type="text/javascript">

    function isProcessComplete() {

        if (document.getElementById('process_iframe').contentWindow.document.getElementById('finish')) {
            setNextButtonDisabled(false);

        } else {
            setTimeout('isProcessComplete()', 1000);
        }
    }

    setTimeout('isProcessComplete()', 1000);

</script>

<?php

    } else {
        fatal_error(xtr('Error has encountered while creating fixtures or modules list.'));
    }

    $error = true;

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
 * @since  1.0.0
 */
function module_install_dirs(&$params)
{
    global $error, $lcSettings;

    $result = doUpdateConfig($params, true);

    if ($result) {

?>

<iframe id="process_iframe" src="install.php?target=install&action=dirs" width="100%" height="300" frameborder="0" marginheight="10" marginwidth="10"></iframe>

<?php

    }

?>

<script type="text/javascript">

    function isProcessComplete() {

        if (document.getElementById('process_iframe').contentWindow.document.getElementById('finish')) {
            setNextButtonDisabled(false);

        } else {
            setTimeout('isProcessComplete()', 1000);
        }
    }

    setTimeout('isProcessComplete()', 1000);

</script>


<input type="hidden" name="ck_res" value="<?php echo intval($result); ?>" />

<?php

    if (is_null($params['new_installation'])) {

?>

        <input type="hidden" name="params[force_current]" value="<?php echo get_step('install_done'); ?>" />

<?php
    }

    $error = true;

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
 * @since  1.0.0
 */
function module_cfg_create_admin(&$params)
{
    global $error, $skinsDir;

    $paramFields = array(
        'login'             => array(
            'title'       => xtr('E-mail'),
            'description' => xtr('E-mail address of the store administrator'),
            'def_value'   => isset($params['login']) ? $params['login'] : '',
            'required'    => true,
            'type'        => 'text'
        ),
        'password'          => array(
            'title'       => xtr('Password'),
            'description' => '',
            'def_value'   => isset($params['password']) ? $params['password'] : '',
            'required'    => true,
            'type'        => 'password'
        ),
        'confirm_password'  => array(
            'title'       => xtr('Confirm password'),
            'description' => '',
            'def_value'   => isset($params['confirm_password']) ? $params['confirm_password'] : '',
            'required'    => true,
            'type'        => 'password'
        ),
    );

    $clrNumber = 1;

?>

<div>

<div class="field-left">

<?php echo xtr('E-mail and password that you provide on this screen will be used to create primary administrator profile. Use them as credentials to access the Administrator Zone of your online store.'); ?>

<br />
<br />

<table width="100%" border="0" cellpadding="10">

<?php


    foreach ($paramFields as $fieldName => $fieldData) {
        displayFormElement($fieldName, $fieldData, $clrNumber);
        $clrNumber = ($clrNumber == 2) ? 1 : 2;
    }

?>

</table>

</div>

<div class="field-right">

    <img class="keyhole-icon" src="<?php echo $skinsDir; ?>images/keyhole_icon.png" alt="" />

</div>

</div>

<div class="clear"></div>

<?php

    if (is_null($params["new_installation"])) {

?>

<input type="hidden" name="params[force_current]" value="<?php echo get_step("install_done")?>" />

<?php
    }
?>

<?php
}

/**
 * cfg_create_admin module "Next" button validator
 *
 * @return string
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
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
            alert('<?php echo xtr('Please, enter non-empty password'); ?>');
            return false;
        }
        if (document.ifrm.elements['params[confirm_password]'].value == "") {
            alert('<?php echo xtr('Please, enter non-empty password confirmation'); ?>');
            return false;
        }
        if (document.ifrm.elements['params[password]'].value != document.ifrm.elements['params[confirm_password]'].value) {
            alert("<?php echo xtr('Password doesn\'t match confirmation!'); ?>");
            return false;
        }
        return true;
    }

    function checkEmailAddress(field) {

        var goodEmail = field.value.search(/^(\S+@)[^\.][A-Za-z0-9_\-\.]+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\.biz)|(\.us)|(\.bizz)|(\.coop)|(\..{2,2}))[ ]*$/gi);

        if (goodEmail != -1) {
            return true;
        } else {
            alert("<?php echo xtr('Please, specify a valid e-mail address!'); ?>");
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
 * @since  1.0.0
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

?>

<div style="text-align: left;">

<?php
    // create/update admin account from the previous step
    if ($accountParams) {
        doCreateAdminAccount($params);
    }

    doFinishInstallation($params);

?>

</div>

<?php

    return false;
}

/**
 * End of Modules section
 */


/**
 * Log every request to install.php
 */
$_params = ('POST' == $_SERVER['REQUEST_METHOD'] ? $_POST : $_GET);

x_install_log(null, x_install_log_mask_params($_params));
