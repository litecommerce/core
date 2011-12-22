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

/**
 * Quick access helper
 *
 * @param boolean $restart Restart helper
 *
 * @return \XLite\Core\QuickAccess
 * @see    ____func_see____
 * @since  1.0.0
 */
function xlite($restart = false)
{
    static $obj = null;

    if (!isset($obj) || $restart) {
        $obj = new \XLite\Core\QuickAccess();
    }

    return $obj;
}

/**
* Prints Javascript code to refresh the browser output page.
*/
function func_refresh_start($display = true)
{
    $output = <<<EOT
<script typee="text/javascript">
<!--
var loaded = false;

function refresh() {
    window.scroll(0, 10000000);

    if (loaded == false) {
        setTimeout('refresh()', 500);
    }
}

setTimeout('refresh()', 1000);
-->
</script>
EOT;

    if ($display) {
        echo $output;
    }

    return $output;
}

function func_refresh_end($display = true)
{
    $output = <<<EOT
<script type="text/javascript">
<!--
var loaded = true;
-->
</script>
EOT;

    if ($display) {
        echo $output;
    }

    return $output;
}

/*
* Executable lookup
* Return false if not executable.
*/
function func_find_executable($filename)
{
    $directories = explode(PATH_SEPARATOR, getenv('PATH'));
    array_unshift($directories, './bin', '/usr/bin', '/usr/local/bin');

    $result = false;

    foreach ($directories as $dir) {
        $file = $dir . '/' . $filename;
        if (func_is_executable($file)) {
            $result = @realpath($file);
            break;
        }

        $file .= '.exe';
        if (func_is_executable($file)) {
            $result = @realpath($file);
            break;
        }
    }

    return $result;
}

/*
* Emulator for the is_executable function if it doesn't exists (f.e. under windows)
*/
function func_is_executable($file)
{
    return function_exists('is_executable')
        ? (file_exists($file) && is_executable($file))
        : (is_file($file) && is_readable($file));
}

function func_define($name, $value) {
    if (!defined($name)) {
        define($name, $value);
    }
}

function get_php_execution_mode() {

    $options = \Includes\Utils\ConfigParser::getOptions(); //XLite::getInstance()->getOptions();

    return isset($options['filesystem_permissions']['permission_mode'])
        ? $options['filesystem_permissions']['permission_mode']
        : 0;
}

// define actual permissions
// mode - one of 0777, 0755, 0666, 0644
function get_filesystem_permissions($mode, $file = null) {
    static $mode0777, $mode0755, $mode0666, $mode0644, $mode0666_fnp, $mode0644_fnp;

    // try to setup values from config
    if (
        (!isset($mode0777) || !isset($mode0755) || !isset($mode0666) || !isset($mode0644))
        && \Includes\Utils\ConfigParser::getOptions('filesystem_permissions')
    ) {

        $options = \Includes\Utils\ConfigParser::getOptions('filesystem_permissions');
        $phpExecutionMode = get_php_execution_mode();

        // 0777
        if (!isset($mode0777)) {
            if ($phpExecutionMode != 0) {
                if (isset($options['privileged_permission_dir'])) {
                    $mode0777 = base_convert(
                        $options['privileged_permission_dir'],
                        8,
                        10
                    );
                }

            } elseif (isset($options['nonprivileged_permission_dir_all'])) {
                $mode0777 = base_convert($options['nonprivileged_permission_dir_all'], 8, 10);
            }
        }

        // 0755
        if (!isset($mode0755)) {
            if ($phpExecutionMode != 0) {
                if (isset($options['privileged_permission_dir'])) {
                    $mode0755 = base_convert(
                        $options['privileged_permission_dir'],
                        8,
                        10
                    );
                }

            } elseif (isset($options['nonprivileged_permission_dir'])) {
                $mode0755 = base_convert($options['nonprivileged_permission_dir'], 8, 10);
            }
        }

        // 0666
        if (!isset($mode0666)) {
            if ($phpExecutionMode != 0) {
                if (isset($options['privileged_permission_file'])) {
                    $mode0666 = base_convert($options['privileged_permission_file'], 8, 10);

                    if (isset($options['privileged_permission_file_nonphp'])) {
                        $mode0666_fnp = base_convert($options['privileged_permission_file_nonphp'], 8, 10);

                    } else {
                        $mode0666_fnp = $mode0666;
                    }
                }

            } elseif (isset($options['nonprivileged_permission_file_all'])) {
                $mode0666 = base_convert($options['nonprivileged_permission_file_all'], 8, 10);
                $mode0666_fnp = $mode0666;
            }
        }

        // 0644
        if (!isset($mode0644)) {
            if ($phpExecutionMode != 0) {
                if (isset($options['privileged_permission_file'])) {
                    $mode0644 = base_convert($options['privileged_permission_file'], 8, 10);
                    if (isset($options['privileged_permission_file_nonphp'])) {
                        $mode0644_fnp = base_convert($options['privileged_permission_file_nonphp'], 8, 10);
                    } else {
                        $mode0644_fnp = $mode0644;
                    }
                }

            } elseif (isset($options['nonprivileged_permission_file'])) {
                $mode0644 = base_convert($options['nonprivileged_permission_file'], 8, 10);
                $mode0644_fnp = $mode0644;
            }
        }
    }


    if (($mode == 0777) && (isset($mode0777))) {
        $mode = $mode0777;

    } elseif (($mode == 0755) && (isset($mode0755))) {
        $modet = $mode0755;

    } elseif (($mode == 0666) && (isset($mode0666))) {

        if (isset($file) && @is_file($file)) {
            $path_parts = @pathinfo($file);
            $mode = 'php' == strtolower($path_parts['extension'])
                ? $mode0666
                : $mode0666_fnp;

        } else {
            $mode = $mode0666;
        }

    } elseif (($mode == 0644) && (isset($mode0644))) {
        if (isset($file) && @is_file($file)) {
            $path_parts = @pathinfo($file);
            $mode = 'php' == strtolower($path_parts['extension'])
                ? $mode0644
                : $mode0644_fnp;

        } else {
            $mode = $mode0644;
        }
    }

    return $mode;
}


// copy single file and set permissions
function copyFile($from, $to, $mode = 0666)
{

    if ($mode == 0666) {
        $mode = get_filesystem_permissions(0666, $from);

    } elseif ($mode == 0644) {
        $mode = get_filesystem_permissions(0644, $from);
    }

    $result = false;

    if (@is_file($from)) {
        $result = @copy($from, $to);
        if (!$result) {
            \Includes\Utils\FileManager::mkdirRecursive(dirname($to));
            $result = @copy($from, $to);
        }
        @umask(0000);
        $result = $result && @chmod($to, $mode);
    }

    return $result;
}

function copyRecursive($from, $to, $mode = 0666, $dir_mode = 0777)
{
    $orig_dir_mode = $dir_mode;

    if ($dir_mode == 0777) {
        $dir_mode = get_filesystem_permissions(0777);

    } elseif ($dir_mode == 0755) {
        $dir_mode = get_filesystem_permissions(0755);
    }

    $orig_mode = $mode;

    if ($mode == 0666) {
        $mode = get_filesystem_permissions(0666, $from);

    } elseif ($mode == 0644) {
        $mode = get_filesystem_permissions(0644, $from);
    }

    if (@is_file($from)) {
        @copy($from, $to);
        @umask(0000);
        @chmod($to, $mode);

    } elseif (@is_dir($from)) {
        if (!@file_exists($to)) {
            @umask(0000);
            $attempts = 5;
            while (!@mkdir($to, $dir_mode)) {
                \Includes\Utils\FileManager::unlinkRecursive($to);
                $attempts --;
                if ($attempts < 0) {
                    echo "Can't create directory $to: permission denied";
                    die;
                }
            }
        }

        if ($handle = @opendir($from)) {
            while (false !== ($file = @readdir($handle))) {
                if (!($file == "." || $file == "..")) {
                    copyRecursive($from . '/' . $file, $to . '/' . $file, $orig_mode, $orig_dir_mode);
                }
            }
            @closedir($handle);
        }

    } else {
        return 1;
    }
}

/**
* Parses the hostname specification. Converts the FQDN hostname
* to dotted hostname, for example
*
*    www.hosting.com:81 -> .hosting.com
*
*/
function func_parse_host($host)
{
    // parse URL
    if (substr(strtolower($host), 0, 7) != 'http://') {
        $host = 'http://' . $host;
    }

    $url_details = func_parse_url($host);
    $host = isset($url_details["host"]) ? $url_details["host"] : $host;

    // strip WWW hostname
    if (substr(strtolower($host), 0, 4) == 'www.') {
        $host = substr_replace($host, '', 0, 3);
    }

    return $host;
}

function func_parse_url($url)
{

    $options = \Includes\Utils\ConfigParser::getOptions();

    $parts_default = array(
        'scheme'   => 'http',
        'host'     => $options['host_details']['http_host'],
        'port'     => '',
        'user'     => '',
        'pass'     => '',
        'path'     => $options['host_details']['web_dir'],
        'query'    => '',
        'fragment' => ''
    );

    $parsed_parts = @parse_url($url);
    if (!is_array($parsed_parts)) {
        $parsed_parts = array();
    }

    return array_merge($parts_default, $parsed_parts);
}

/**
* Uploads SQL patch into the database. If $connection is not defined, uses
* mysql_query($sql) syntax, otherwise mysql_query($sql, $connection);
* If $ignoreErrors is true, it will display all SQL errors and proceed.
*/
function query_upload($filename, $connection = null, $ignoreErrors = false, $is_restore = false)
{
    $fp = @fopen($filename, 'rb');
    if (!$fp) {
        echo '<font color="red">[Failed to open $filename]</font></pre>' . "\n";
        return false;
    }

    $command = '';
    $counter = 1;

    while (!feof($fp)) {
        $c = '';

        // read SQL statement from file
        do {
            $c .= fgets($fp, 1024);
            $endPos = strlen($c) - 1;
        } while (substr($c, $endPos) != "\n" && !feof($fp));
        $c = chop($c);

        // skip comments
        if (substr($c, 0, 1) == '#' || substr($c, 0, 2) == '--') {
            continue;
        }

        // parse SQL statement
        $command .= $c;
        if (substr($command, -1) == ';') {
            $command = substr($command, 0, strlen($command)-1);

            $table_name = '';
            if (preg_match('/^CREATE TABLE ([_a-zA-Z0-9]*)/i', $command, $matches)) {
                $table_name = $matches[1];
                echo 'Creating table [' . $table_name . '] ... ';

            } elseif (preg_match('/^ALTER TABLE ([_a-zA-Z0-9]*)/i', $command, $matches)) {
                $table_name = $matches[1];
                echo 'Altering table [' . $table_name . '] ... ';

            } elseif (preg_match('/^DROP TABLE IF EXISTS ([_a-zA-Z0-9]*)/i', $command, $matches)) {
                $table_name = $matches[1];
                echo 'Deleting table [' . $table_name . '] ... ';

            } else {
                $counter ++;
            }

            // execute SQL
            if (is_resource($connection)) {
                mysql_query($command, $connection);

            } else {
                mysql_query($command);
            }

            if (is_resource($connection)) {
                $myerr = mysql_error($connection);

            } else {
                $myerr = mysql_error();
            }

            // check for errors
            if (!empty($myerr)) {
                query_upload_error($myerr, $ignoreErrors);
                if (!$ignoreErrors) {
                    break;
                }

            } elseif ($table_name != "") {
                echo '<font color="green">[OK]</font><br />' . "\n";

            } elseif (!($counter % 20)) {
                echo '.';
            }

            $command = '';
            flush();
        }
    }

    fclose($fp);
    if ($counter>20) {
        print "\n";
    }

    return (!$is_restore && $ignoreErrors) ? true : empty($myerr);
}

function query_upload_error($myerr, $ignoreErrors)
{
    if (empty($myerr)) {
        echo "\n";
        echo '<font color="green">[OK]</font>' . "\n";

    } elseif ($ignoreErrors) {
        echo '<font color="blue">[NOTE: ' . $myerr . ']</font>' . "\n";

    } else {
        echo '<font color="red">[FAILED: ' . $myerr . ']</font>' . "\n";
    }
}

/**
* Generates a code consisting of $length characters from the set [A-Z0-9].
* Used as GC & discount coupon code, as well as installation auth code, etc.
*/
function generate_code($length = 8)
{
    $salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    srand(microtime(true) * 1000000);
    $i = 0;

    $code = '';
    while ($i < $length) {
        $num = rand() % 35;
        $tmp = substr($salt, $num, 1);
        $code = $code . $tmp;
        $i++;
    }

    return $code;
}

/**
* Strips slashes and trims the specified array values
* (strips from strings only)
*
* @access private
* @param  array $array The array to strip slashes
*/
function func_strip_slashes(&$array)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            func_strip_slashes($array[$key]);

        } elseif (is_string($value)) {
            $array[$key] = trim(stripslashes($value));
        }
    }
}

function func_htmldecode($encoded)
{
    return strtr($encoded, array_flip(get_html_translation_table(HTML_ENTITIES)));
}

function func_starts_with($str, $start)
{
    return 0 === strncmp($str, $start, strlen($start));
}

//
// This function create file lock in temporaly directory
// It will return file descriptor, or false.
//
function func_lock($lockname, $ttl = 15, $cycle_limit = 0)
{
    global $_lock_hash;

    $options = \Includes\Utils\ConfigParser::getOptions();

    if (empty($lockname)) {
        return false;
    }

    if (!empty($_lock_hash[$lockname])) {
        return $_lock_hash[$lockname];
    }

    $lockDir = $options["decorator_details"]["lockDir"];
    // remove last '/'
    if ($lockDir{strlen($lockDir) - 1} == '/') {
        $lockDir = substr($lockDir, 0, strlen($lockDir)-1);
    }
    if (!is_dir($lockDir)) {
        \Includes\Utils\FileManager::mkdirRecursive($lockDir);
    }
    $fname = $lockDir."/".$lockname.".lock";

    // Generate current id
    $id = md5(uniqid(rand(0, substr(floor(microtime(true) * 1000), 3)), true));
    $_lock_hash[$lockname] = $id;

    $file_id = false;
    $limit = $cycle_limit;
    while (($limit-- > 0 || $cycle_limit <= 0)) {
        if (!file_exists($fname)) {

            # Write locking data
            $fp = @fopen($fname, "w");
            if ($fp) {
                @fwrite($fp, $id.time());
                fclose($fp);
            }
        }

        $fp = @fopen($fname, "r");
        if (!$fp)
            return false;

        $tmp = @fread($fp, 43);
        fclose($fp);

        $file_id = substr($tmp, 0, 32);
        $file_time = (int) substr($tmp, 32);

        if ($file_id == $id)
            break;

        if ($ttl > 0 && time() > $file_time+$ttl) {
            @unlink($fname);
            continue;
        }

        sleep(1);
    }

    return $file_id == $id ? $id : false;
}

//
// This function releases file lock which is previously created by func_lock
//
function func_unlock($lockname) {
    global $_lock_hash;

    $options = \Includes\Utils\ConfigParser::getOptions();

    if (empty($lockname) || empty($_lock_hash[$lockname])) {
        return false;
    }

    $lockDir = $options["decorator_details"]["lockDir"];
    // remove last '/'
    if ($lockDir{strlen($lockDir)-1} == '/') {
        $lockDir = substr($lockDir, 0, strlen($lockDir)-1);
    }
    if (!is_dir($lockDir)) {
        \Includes\Utils\FileManager::mkdirRecursive($lockDir);
    }
    $fname = $lockDir."/".$lockname.".lock";
    if (!file_exists($fname)) {
        return false;
    }

    $fp = fopen($fname, "r");
    if (!$fp) {
        return false;
    }

    $tmp = fread($fp, 43);
    fclose($fp);

    $file_id = substr($tmp, 0, 32);
    $file_time = (int) substr($tmp, 32);

    if ($file_id == $_lock_hash[$lockname]) {
        @unlink($fname);
    }

    unset($_lock_hash[$lockname]);

    return true;
}

//
// This function checks, whether the lock is active
//
function func_is_locked($lockname, $ttl = 15) {
    global $_lock_hash;

    $options = \Includes\Utils\ConfigParser::getOptions();

    if (empty($lockname)) {
        return false;
    }

    $lockDir = $options["decorator_details"]["lockDir"];
    // remove last '/'
    if ($lockDir{strlen($lockDir)-1} == '/') {
        $lockDir = substr($lockDir, 0, strlen($lockDir)-1);
    }
    $fname = $lockDir."/".$lockname.".lock";
    if (!file_exists($fname)) {
        if (!file_exists($fname)) {
            return false;
        }
    }

    $fp = fopen($fname, "r");
    if (!$fp) {
        return false;
    }

    $tmp = fread($fp, 43);
    fclose($fp);

    $file_id = substr($tmp, 0, 32);
    $file_time = (int) substr($tmp, 32);

    if ($ttl > 0 && time() > $file_time+$ttl) {
        @unlink($fname);
        return false;
    }

    return true;
}

function func_parse_csv($line, $delimiter, $q, &$error) {
    $line = trim($line);
    if (empty($q)) {
        return explode($delimiter, $line);
    }

    $arr = array();
    $state = "outside";
    $field = "";
    $error = "";
    for ($i=0; $i<=strlen($line); $i++) {
        if ($i==strlen($line)) $char = "EOL";
        else $char = $line{$i};
        if ($state == "outside") {
            if ($char == $q) {
                $state = "inside";
                $field = "";
            } elseif ($char == $delimiter || $char == "EOL") {
                // empty field
                $arr[] = "";
            } else {
                $state = "field";
                $field = $char;
            }
        } elseif ($state == "inside") {
            if ($char == $q) {
                $state = "quote inside";
            } else if ($char == "EOL") {
                $error = "Unexpected end of line; $q expected";
                return null;
            } else {
                $field .= $char;
            }
        } elseif ($state == "quote inside") {
            if ($char == $q) { // double-quote
                $state = "inside";
                $field .= $q;
            } elseif ($char == $delimiter || $char == "EOL") {
                $arr[] = $field;
                $state = "outside";
            } else {
                $error = "Unexpected character $char outside quotes: $q expected (pos $i)";
                return null;
            }
        } elseif ($state == "field") {
            if ($char == $delimiter || $char == "EOL") {
                $state = "outside";
                $arr[] = $field;
            } else {
                $field .= $char;
            }
        }
    }
    return $arr;
}

function func_construct_csv($fields, $delimiter, $q) {
    $test = '';
    $fs = array();
    foreach ($fields as $f) {
        if (empty($q)) {
            $fs[] = strtr($f, "\n\r", "  ");

        } else {
            $fs[] = $q . strtr(str_replace($q, $q . $q, $f), "\n\r", "  ").$q;
        }
    }
    return implode($delimiter, $fs);
}

function func_convert_to_byte($file_size) {
    $val = trim($file_size);
    $last = strtolower(substr($val, -1));

    switch ($last) {
        case 'g':
            $val *= 1024;

        case 'm':
            $val *= 1024;

        case 'k':
            $val *= 1024;
    }

    return $val;
}

function func_check_memory_limit($current_limit, $required_limit) {
    $limit = func_convert_to_byte($current_limit);
    $required = func_convert_to_byte($required_limit);
    if ($limit < $required) {
        # workaround for http://bugs.php.net/bug.php?id=36568
        if (LC_OS_IS_WIN && version_compare(phpversion(), '5.1.0') < 0) {
            return true;
        }

        @ini_set('memory_limit', $required_limit);
        $limit = @ini_get('memory_limit');
        return 0 === strcasecmp($limit, $required_limit);
    }

    return true;
}

function func_set_memory_limit($new_limit) {
    $current_limit = @ini_get('memory_limit');

    return func_check_memory_limit($current_limit, $new_limit);
}

function func_htmlspecialchars($str) {
    $str = preg_replace(
        '/&(?!(?:amp|#\d+|#x\d+|euro|copy|pound|curren|cent|yen|reg|trade|lt|gt|lte|gte|quot);)/Ss',
        '&amp;',
        $str
    );

    return str_replace(
        array('"', '\'', '<', '>'),
        array('&quot;', '&#039;', '&lt;', '&gt;'),
        $str
    );
}

/**
 * Check if LiteCommerce installed
 *
 * :FIXME: check this carefully
 *
 * @param string $dbURL Database Url string (e.g. mysql://username:password@localhost/databasename)
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function isLiteCommerceInstalled($dbURL = null, &$message)
{
    // Check by template and config.php file
    $checkResult = file_exists(LC_DIR_SKINS . 'admin/en/welcome.tpl')
        && (file_exists(LC_DIR_CONFIG . 'config.php') || file_exists(LC_DIR_CONFIG . 'config.local.php'));

    if ($checkResult) {

        // Get database options from config.php
        $configData = \Includes\Utils\ConfigParser::getOptions('database_details');

        if (is_array($configData)) {

            // Check if host, dbname and username is not empty
            $checkResult = !empty($configData['hostspec'])
                && !empty($configData['database'])
                && !empty($configData['username']);

            if ($checkResult) {

                if (isset($dbURL)) {

                    // Support of Drupal 6 installation
                    if (is_array($dbURL)) {
                        $data = $dbURL;

                    } else {
                        $data = parseDbURL($dbURL);
                    }

                    if (!empty($data)) {

                        // Compare database options from config and from parameter
                        $checkResult = $configData['hostspec'] == $data['mysqlhost']
                            && $configData['username'] == $data['mysqluser']
                            && $configData['password'] == $data['mysqlpass']
                            && $configData['database'] == $data['mysqlbase']
                            && (!isset($data['mysqlport']) || $configData['port'] == $data['mysqlport'])
                            && (!isset($data['mysqlsock']) || $configData['socket'] == $data['mysqlsock']);

                        if (!$checkResult) {
                            $message = 'Database parameters (specified in Drupal and LiteCommerce configs) comparison failed';
                        }

                    } else {
                        $message = '$dbURL passed but hasn\'t any data or corrupted';
                        $checkResult = false;
                    }

                } else {
                    $data = null;
                }

                if ($checkResult) {
                    // Check if connection works
                    $checkResult = dbConnect($data, $errorMsg);

                    if ($checkResult) {
                        $res = dbFetchColumn('SELECT profile_id from xlite_profiles LIMIT 1', $errorMsg);

                        if (empty($res)) {
                            $message = 'There are no profiles found in the database';
                            $checkResult = false;

                        } elseif (\Includes\Decorator\Utils\CacheManager::isRebuildNeeded(\Includes\Decorator\Utils\CacheManager::STEP_THIRD)) {
                            $message = 'Cache isn\'t built yet';
                            $checkResult = false;
                        }

                    } else {
                        $message = 'Cannot connect to the database';
                    }
                }

            } else {
                $message = 'Host, username or database name are empty';
            }

        } else {
            $message = 'Corrupted LiteCommerce config file';
            $checkResult = false;
        }

    } else {
        $message = 'config.php or admin/en/welcome.tpl files are not found';
    }

    return $checkResult;
}

/**
 * Parse database access string
 *
 * @param string $dbURL Database Url string
 * examples:
 *   mysql://username:password@localhost/databasename
 *   mysql://username:password@localhost:3306/databasename
 *   mysql://username:password@host%3A3306%2Ftmp%2Fmysql.sock/databasename
 *   mysql://username:password@host%3A%2Ftmp%2Fmysql.sock/databasename
 *   (host must be urlencoded if mysql works via non-standard socket)
 *
 * @return array
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function parseDbURL($dbURL)
{
    $data = array();

    $url = parse_url($dbURL);

    if (is_array($url)) {

        $data['mysqlhost'] = urldecode($url['host']);

        if (isset($url['port'])) {
            $data['mysqlport'] = urldecode($url['port']);

        } else {

            $hostData = parse_url($data['mysqlhost']);

            if (isset($hostData['host'])) {
                $data['mysqlhost'] = $hostData['host'];
                $data['mysqlport'] = isset($hostData['port']) ? $hostData['port'] : '';
                $data['mysqlsock'] = isset($hostData['path']) ? $hostData['path'] : '';

            } elseif (isset($hostData['scheme'])) {
                $data['mysqlhost'] = $hostData['scheme'];
                $data['mysqlport'] = isset($hostData['port']) ? $hostData['port'] : '';
                $data['mysqlsock'] = isset($hostData['path']) ? $hostData['path'] : '';
            }
        }

        $data['mysqluser'] = urldecode($url['user']);
        $data['mysqlpass'] = isset($url['pass']) ? urldecode($url['pass']) : NULL;
        $data['mysqlbase'] = ltrim(urldecode($url['path']), '/');
    }

    return $data;
}

/**
 * Do connection to the database with params user specified
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function dbConnect ($data = null, &$errorMsg = null)
{
    $result = true;

    if (!empty($data) && is_array($data)) {

        $fields = array(
            'hostspec' => 'mysqlhost',
            'port'     => 'mysqlport',
            'socket'   => 'mysqlsock',
            'username' => 'mysqluser',
            'password' => 'mysqlpass',
            'database' => 'mysqlbase',
        );

        $dbParams = array();

        foreach ($fields as $key => $value) {
            if (isset($data[$value])) {
                $dbParams[$key] = $data[$value];
            }
        }

        // Set db options
        try {
            $connect = \Includes\Utils\Database::setDbOptions($dbParams);

        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }

        $result = isset($connect);

    } else {
        // Reset db options
        \Includes\Utils\Database::resetDbOptions();
    }

    return $result;
}

/**
 * Execute SQL query and return the first column of first row of the result
 *
 * @return mixed
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function dbFetchColumn($sql, &$errorMsg = null)
{
    $result = null;

    try {
        $result = \Includes\Utils\Database::fetchColumn($sql);

    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }

    return $result;
}

/**
 * Execute SQL query and return the result as an associated array
 *
 * @return array
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function dbFetchAll($sql, &$errorMsg = null)
{
    $result = null;

    try {
        $result = \Includes\Utils\Database::fetchAll($sql);

    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }

    return $result;
}

/**
 * Execute SQL query
 *
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function dbExecute($sql, &$errorMsg = null)
{
    try {
        \Includes\Utils\Database::execute($sql);

    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}

/**
 * Execute a set of SQL queries from file
 *
 * @param string $fileName     The name of file which contains SQL queries
 * @param bool   $ignoreErrors Ignore errors flag
 * @param bool   $is_restore   ?
 *
 * @return bool
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function uploadQuery($fileName, $ignoreErrors = false, $is_restore = false)
{
    $fp = @fopen($fileName, 'rb');

    if (!$fp) {
        echo '<font color="red">[Failed to open ' . $fileName . ']</font></pre>' . "\n";
        return false;
    }

    $command = '';
    $counter = 1;

    while (!feof($fp)) {

        $c = '';

        // read SQL statement from file
        do {
            $c .= fgets($fp, 1024);
            $endPos = strlen($c) - 1;
        } while (substr($c, $endPos) != "\n" && !feof($fp));

        $c = chop($c);

        // skip comments
        if (substr($c, 0, 1) == '#' || substr($c, 0, 2) == '--') {
            continue;
        }

        // parse SQL statement

        $command .= $c;

        if (substr($command, -1) == ';') {

            $command = substr($command, 0, strlen($command)-1);
            $table_name = '';

            if (preg_match('/^CREATE TABLE `?([_a-zA-Z0-9]*)`?/i', $command, $matches)) {
                $table_name = $matches[1];
                echo 'Creating table [' . $table_name . '] ... ';

            } elseif (preg_match('/^ALTER TABLE `?([_a-zA-Z0-9]*)`?/i', $command, $matches)) {
                $table_name = $matches[1];
                echo 'Altering table [' . $table_name . '] ... ';

            } elseif (preg_match('/^DROP TABLE IF EXISTS `?([_a-zA-Z0-9]*)`?/i', $command, $matches)) {
                $table_name = $matches[1];
                echo 'Deleting table [' . $table_name . '] ... ';

            } else {
                $counter ++;
            }

            // Execute SQL query
            dbExecute($command, $myerr);

            // check for errors
            if (!empty($myerr)) {

                showQueryStatus($myerr, $ignoreErrors);

                if (!$ignoreErrors) {
                    break;
                }

            } elseif ($table_name != "") {
                echo '<font color="green">[OK]</font><br />' . "\n";

            } elseif (!($counter % 5)) {
                echo '.';
            }

            $command = '';

            flush();
        }
    }

    fclose($fp);

    if ($counter > 20) {
        print "<br />\n";
    }

    return (!$is_restore && $ignoreErrors) ? true : empty($myerr);
}

/**
 * Show a error status
 *
 * @param string $myerr        Error message
 * @param bool   $ignoreErrors Ignore errors flag
 *
 * @return void
 * @access public
 * @see    ____func_see____
 * @since  1.0.0
 */
function showQueryStatus($myerr, $ignoreErrors)
{
    if (empty($myerr)) {
        echo "\n";
        echo '<font color="green">[OK]</font>' . "\n";

    } elseif ($ignoreErrors) {
        echo '<font color="blue">[NOTE: ' . $myerr . ']</font>' . "\n";

    } else {
        echo '<font color="red">[FAILED: ' . $myerr . ']</font>' . "\n";
    }
}

/**
 * Alternative debug backtrace assembler
 *
 * @return array
 * @see    ____func_see____
 * @since  1.0.0
 */
function func_debug_backtrace()
{
    $trace = array();

    foreach (debug_backtrace(false) as $l) {
        $parts = array();

        if (isset($l['file'])) {

            $parts[] = 'file ' . $l['file'];

        } elseif (isset($l['class']) && isset($l['function'])) {

            $parts[] = 'method ' . $l['class'] . '::' . $l['function'];

        } elseif (isset($l['function'])) {

            $parts[] = 'function ' . $l['function'];

        }

        if (isset($l['line'])) {
            $parts[] = $l['line'];
        }

        if ($parts) {
            $trace[] = implode(' : ', $parts);
        }
    }

    return array_slice($trace, 1);
}

/**
 * Alternative debug backtrace printer
 *
 * @return void
 * @see    ____func_see____
 * @since  1.0.0
 */
function func_debug_print_backtrace()
{
    print (implode(PHP_EOL, func_debug_backtrace()));
}

/**
 * Returns LiteCommerce tables prefix
 *
 * @return string
 * @see    ____func_see____
 * @since  1.0.0
 */
function get_xlite_tables_prefix()
{
    return \Includes\Utils\ConfigParser::getOptions(array('database_details', 'table_prefix'));
}

// Emulation
if (!function_exists('mb_stripos')) {

/**
 * Find position of first occurrence of a case-insensitive string
 *
 * @param string  $haystack The string to search in 
 * @param string  $needle   The string to find in haystack
 * @param integer $offset   The position in haystack  to start searching OPTIONAL
 *
 * @return integer
 * @see    ____func_see____
 * @since  1.0.6
 */
function mb_stripos($haystack, $needle, $offset = 0)
{
    return stripos($haystack, $needle, $offset);
}

}
