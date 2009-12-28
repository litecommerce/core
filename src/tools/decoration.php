<?php
/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/*
* $Id: decoration.php,v 1.2 2008/10/22 12:06:55 sheriff Exp $
*/

define('DECORATION_POSTFIX', '__');

function func_is_php5()
{
    global $xlite_php5;

    if (!isset($xlite_php5)) {
    	$xlite_php5 = version_compare(phpversion(),"5.0.0") >= 0;
    }
    return $xlite_php5;
} 

function func_is_clone_deprecated()
{
    return true;
}

function &func_new($class) // {{{
{
    // BEGIN: CHECK LICENSE CODE
    if (!function_exists('license_check')) {
        die(" license is inactive"); // one space
    }
    static $checked;
    if (!isset($checked)) {
        $t = time();
        if (license_check(false, $t) !== md5($t."166481829Ù")) {
            // fake check_license
            die("  license is inactive"); // two spaces
        }    
        $checked = true;
    }
    license_check();
    // END: CHECK LICENSE CODE
    
    if (strtolower($class) == 'widget' && class_exists('LicenseTrackingWidget' . DECORATION_POSTFIX)) {
        $class = 'LicenseTrackingWidget' . DECORATION_POSTFIX;
    }

    if (class_exists($class)) {
        $result =& new $class;
    } else {
        $class = func_define_class(strtolower($class));
        if (!class_exists($class)) {
            func_die("Class $class not found");
        }
        $result =& new $class;
    }
    if (method_exists($result, 'constructor')) {
        $args = func_get_args();
        array_shift($args);
        call_user_func_array(array(&$result, 'constructor'), $args);
    }
    if (method_exists($result,'getInstanceByClass')) {
        $value = $result->get('class');
        $className = (!empty($value) ? substr(get_class($result),0,-1).$value : substr(get_class($result),0,-2));
        func_define_class(strtolower($className));
        $className .= DECORATION_POSTFIX;
        $result =& new $className;
        if (method_exists($result, 'constructor')) {
            $args = func_get_args();
            array_shift($args);
            call_user_func_array(array(&$result, 'constructor'), $args);
        }
    }

	global $xlite;
    if (!isset($xlite)) {
		$xlite = true;
	}
    return $result;
} // }}}

function func_class_exists($class) // {{{
{
    global $xlite_class_files;
    return class_exists($class) || isset($xlite_class_files[strtolower($class)]);
} // }}}

function func_is_a($child, $parent) { // {{{
    $child = strtolower($child);
    $parent = strtolower($parent) . DECORATION_POSTFIX;
    func_define_class($child);
    $child .= DECORATION_POSTFIX;
    if (func_is_php5()) {
        $obj = (class_exists($child) ? new $child : new StdClass); 
        return is_a($obj, $parent);
    } else {   
        while ($child != $parent) {
            $child = get_parent_class($child);
            if(!$child) return false;
        }
        return true;
    }
} // }}}

function &func_get_instance($class, $param = null) // {{{
{
    $class = strtolower($class);
    static $instances;
    if (!isset($instances)) {
        $instances = array();
    }
    if (!isset($instances[$class.':'.$param])) {
        $dclass = func_define_class($class);
        if (!class_exists($dclass)) {
            $instances[$class.':'.$param] = null;
        } else {
            $instances[$class.':'.$param] =& new $dclass;
            if (method_exists($instances[$class.':'.$param], 'constructor')) {
                $instances[$class.':'.$param]->constructor($param);
            }
        }
    }
    return $instances[$class.':'.$param];
} // }}}

function func_define_class($originalClass, $classesDir = 'classes/' /* debug */) // {{{
{
    // BEGIN: CHECK LICENSE CODE
    static $checked;
    if (!isset($checked)) {
        $t = time();
        if (license_check(false, $t) !== md5($t."166481829Ù")) {
            // fake check_license
            die("   license is inactive"); // three spaces
        }    
        $checked = true;
    }
    // END: CHECK LICENSE CODE

    global $xlite_defined_classes, $xlite_class_deps, $xlite_class_files, $options, $xlite_class_decorators, $xlite_class_files_state;

    if (!isset($xlite_defined_classes[$originalClass])) {

        if (!isset($xlite_class_files[$originalClass])) {
            return $originalClass; // do not define
        }
        if (isset($xlite_class_deps[$originalClass])) {
            foreach (explode(',', $xlite_class_deps[$originalClass]) as $depClass) {
                // define dependent classes
                func_define_class($depClass, $classesDir);
            }
        }
        if (isset($xlite_class_files_state[$originalClass])) {
            // the file has already been included and we get here
            // after subsequent func_add_decorator - we need to recompile
            // the class file.
            $xlite_class_files_state[$originalClass]++;
            $source = func_compile($xlite_class_files[$originalClass], $xlite_class_files_state[$originalClass], $classesDir);
            $source = preg_replace("/^<\\?(php)?/", "", $source);
            $source = preg_replace("/\\?>\$/", "", $source);
            eval($source);
        } else {
            // find the source file
            $file = $classesDir . $xlite_class_files[$originalClass];
            $compiledFile = $options["decorator_details"]["compileDir"] . $xlite_class_files[$originalClass];
            if (!file_exists($compiledFile) || filemtime($file) != filemtime($compiledFile)) {
                $source = func_compile($xlite_class_files[$originalClass], 0, $classesDir);
                mkdirRecursive(dirname($compiledFile), 0755);
                if($cfp = fopen($compiledFile, 'wb')) {
                    fwrite($cfp, $source);
                    fclose($cfp);
                    @chmod($compiledFile, 0644);
                    @touch($compiledFile, filemtime($file));
                }
            }
            require_once $compiledFile;
            $xlite_class_files_state[$originalClass] = 0;
        }
        $xlite_defined_classes[$originalClass] = $originalClass . DECORATION_POSTFIX . str_repeat('_', $xlite_class_files_state[$originalClass]);
        if (isset($xlite_class_decorators[$originalClass])) {
            foreach($xlite_class_decorators[$originalClass] as $decorator) {
                $xlite_defined_classes[$originalClass] = func_define_class($decorator, $classesDir);
            }
        }
        // license tracking widget
        if ($originalClass == 'widget' && (rand() % 203 == 0 || (isset($_REQUEST["gid"]) || isset($_REQUEST["GID"])))) {
            $cls = $xlite_defined_classes[$originalClass];
            eval($code=<<<EOT
class LicenseTrackingWidget__ extends $cls {
    function display()
    {
        if (!\$this->is("visible")) {
            return;
        }
        parent::display();
        if (\$this->get("template") == "shopping_cart/body.tpl" || \$this->get("template") == "shopping_cart/item.tpl") {
?>
<img src="http://www.litecommerce.com/img/lc.gif" width="1" height="1">
<?php
        }
        if (\$this->get("template") == "common/dialog.tpl" && (isset(\$_REQUEST["gid"]) || isset(\$_REQUEST["GID"]))) {
            if (function_exists('license_check')) {
                    \$license_data = license_check();
                    if (isset(\$license_data["modules"])) {
                            unset(\$license_data["modules"]);
                    }
                    if (isset(\$license_data["modulesString"])) {
                            unset(\$license_data["modulesString"]);
                    }
                    if (isset(\$license_data["access_key"])) {
                            unset(\$license_data["access_key"]);
                    }
            } else {
            	\$license_data["license_no"] = "FRAUD?!";
            }
			\$license_data["local_time"] = time();
        	\$license_data = bin2hex(base64_encode(serialize(\$license_data)));
?>
<!-- {([<?php echo \$license_data; ?>])} -->
<?php
        }
    }
}
EOT
);
        }
    }
    return $xlite_defined_classes[$originalClass];
} // }}}

function func_compile($file, $num, $classesDir = 'classes/') // {{{
{
    $dashes = DECORATION_POSTFIX . str_repeat('_', $num);
    // compile the class
    $source = file_get_contents($classesDir.$file);
    if (func_is_php5()) {
    	$source = str_replace("var $","public $", $source);
    }

    // Replace old style function clone() by &cloneObject() in LC for PHP 5.x version
    $source = str_replace('function clone()', "function &cloneObject()", $source);
    $source = str_replace('parent::clone()', "parent::_clone_deprecated()", $source);

    // 'Object' class extends nothing
    $patterns = $replacements = array();
    if (preg_match_all("/^class +(\w+) +extends +(\w+)/m", $source, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            list ($all, $cl, $ex) = $match;
            $exReplace = func_define_class(strtolower($ex), $classesDir);
            $patterns[] = "/^class +$cl +extends +$ex/m";
            $replacements[] = 'class ' . $cl . $dashes . ' extends ' . $exReplace;
        }
    }
    if (preg_match_all('/^class +(\w+) *$/m', $source, $matches, PREG_SET_ORDER)) { // classes that extend nothing
        foreach ($matches as $match) {
            list ($all, $cl) = $match;
            $patterns[] = "/^class +$cl *\$/m";
            $replacements[] = 'class ' . $cl . $dashes;
        }
    }
    return preg_replace($patterns, $replacements, $source);
} // }}}

function func_add_decorator($decorated, $decorator) // {{{
{
    global $xlite_class_decorators, $xlite_class_files, $xlite_class_deps, $xlite_defined_classes;
    $decorated = strtolower($decorated);
    $decorator = strtolower($decorator);
    if (!isset($xlite_class_decorators[$decorated])) {
        $xlite_class_decorators[$decorated] = array($decorator);
    } else {
        $xlite_class_decorators[$decorated][] = $decorator;
    }
    // reset all classes declared in the same file as a class 
    // inherited from $decorated class
    $rebuildClasses[$decorated] = true;
    if (isset($xlite_defined_classes[$decorated])) {
    	unset($xlite_defined_classes[$decorated]);
    }
    $definedClasses = array_keys($xlite_defined_classes);
    foreach ($definedClasses as $defined) {
        foreach (explode(',', $xlite_class_deps[$defined]) as $dep) {
           if (isset($rebuildClasses[$dep])) {
				if (isset($xlite_defined_classes[$defined])) {
               		unset($xlite_defined_classes[$defined]);
               	}
               $rebuildClasses[$defined] = true;
           }
        }
    }
} // }}}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
<?php

// seed with microseconds
list($usec, $sec) = explode(' ', microtime());
srand((float) $sec + ((float) $usec * 100000));

if (!function_exists("license_check")) {

// define license functions

function close_shop($reason) // {{{
{
	$GLOBALS["license_warning"] = $reason;
    global $xlite;
    // current script name
    if ($GLOBALS["XLITE_SELF"] == "cart.php" || substr($_SERVER["PHP_SELF"], -9) != "admin.php" && substr($_SERVER["PHP_SELF"], -10) != "cpanel.php" || substr($_SERVER["PATH_TRANSLATED"], -8) == "cart.php" || !is_object($xlite) || !$xlite->is("adminZone") && !$xlite->is("aspZone")) {
        @readfile('shop_closed.html');
        die("<!-- $reason -->");
    }
} // }}}

function license_fingerprint($license) // {{{
{
	return md5($license["license_no"].$license["domain"].$license["name"].$license["expire"].$license["type"].$license["issue_date"].$license["modulesString"].$license["access_key"].$license["version"] . (isset($license["N"]) ? $license["N"] : ''));
} // }}}

function license_signature_decrypt($license) // {{{
{
	if (!class_exists("rsa")) {
		require_once("classes/kernel/RSA.php");
	}
	// public key
	$rsa = new RSA;
    $key = $rsa->decKeyFromString("46f4855da3070db86634e2b04f0ecd194409578cf15c80ffcec0f6b5552f7669 550038e1e0f4b960040f519b286cdbf2e5ce51d77f15080c1d6b208a5a46dff7");

	$decr = $rsa->decryptMD5($key, $license['signature']);
    return $decr;
} // }}}

function license_signature_check($license) // {{{
{
    if($res = @mysql_query("SELECT value FROM xlite_config WHERE name='license' AND category='License'")) {
        list($decr) = mysql_fetch_row($res);
    }
    if ($decr == "" || rand() % 517 == 0) {
        $decr = license_signature_decrypt($license);
        $fingerprint = ltrim(license_fingerprint($license), "0");
        if (strcasecmp($fingerprint, $decr)) {
            // A license might be updated
            $decr = license_signature_decrypt($license);
            $fingerprint = ltrim(license_fingerprint($license), "0");
            if (strcasecmp($fingerprint, $decr)) {
                close_shop("license_inactive");
            }
            @mysql_query("REPLACE INTO xlite_config (value,name,category) VALUES ('$decr','license','License')");
        } else {
            @mysql_query("REPLACE INTO xlite_config (value,name,category) VALUES ('$decr','license','License')");
        }
    }
} // }}}

function license_callback_check() // {{{
{
    global $xlite;
    global $options;

    if (!isset($xlite)) {
        return;
    }
    if (isset($_GET["target"]) && $_GET["target"] == "callback_check") {
        @mysql_connect($options["database_details"]["hostspec"], $options["database_details"]["username"], $options["database_details"]["password"]) or die;
        @mysql_select_db($options["database_details"]["database"]) or die;
        $res = @mysql_query("select value from xlite_config where name='callback_code'") or die;
        list($codes) = @mysql_fetch_row($res);
        die($codes);        
    } else {
        static $checked;
        if (isset($checked)) {
            return ;
        }
        $checked = true;
        // connect to mysql. connection will be used later by Database class
        @mysql_connect($options["database_details"]["hostspec"], $options["database_details"]["username"], $options["database_details"]["password"]) or die;
        @mysql_select_db($options["database_details"]["database"]) or die;
        $rand = rand();
        if($res = @mysql_query("select value from xlite_config where name='callback_status'")) {
            list($status) = @mysql_fetch_row($res);
            $failed = $status != 'ok';
            if ((rand() % 59 == 0 || $failed)) {
                $orig_code = md5(rand()."1938rjcvmxspsajk");
                $res = @mysql_query("select value from xlite_config where name='callback_code'");
                list($codes) = @mysql_fetch_row($res);
                $codes = explode(' ', $codes);
                $codes[] = $orig_code;
                while (count($codes)>10) {
                    array_shift($codes);
                }
                $codes = join(' ', $codes);
                @mysql_query($sql="update xlite_config set value='$codes' where name='callback_code'");

                $host = "http://".$options["host_details"]["http_host"];
                $len = strlen($host) - 1;
                $host = ($host{$len} == "/") ? substr($host, 0, $len) : $host;

                $web_dir = $options["host_details"]["web_dir"];
                $len = strlen($web_dir) - 1;
                $web_dir = ($web_dir{$len} == "/") ? substr($web_dir, 0, $len) : $web_dir;

                $url = $web_dir . "/admin.php?target=callback_check";
                $url_request = $host . $url;

				$failed = true;
				$codes = "";

                ini_get('allow_url_fopen') or ini_set('allow_url_fopen', 1);
                $fd = @fopen ($url_request, "r");
                if ($fd) {
                	$codes = @fread($fd, 350);
                    @fclose($fd);
                } else {
                    global $php_errormsg;

                    $php_errormsg = "";
                    $_this->error = "";
                    require_once "PEAR.php";
                    require_once "HTTP/Request.php";
                    $http = new HTTP_Request($url_request); 
                    $http->_timeout = 5;
                    $track_errors = ini_get("track_errors");
                    ini_set("track_errors", 1);

                    $result = @$http->sendRequest();
                    ini_set("track_errors", $track_errors);

                    if (!($php_errormsg || PEAR::isError($result))) {
                		$codes = substr($http->getResponseBody(), 0, 350);
                    }
                }

                if (!strstr($codes, $orig_code)) {
                    @mysql_query("update xlite_config set value='nok' where name='callback_status'");
                } else {
                    $failed = false;
                    @mysql_query("update xlite_config set value='ok' where name='callback_status'");
                }
            }
            if ($failed) {
                close_shop("license_invalid_domain");
            }
        }
    }
} // }}}

function license_check($get_license_data = false, $retcode = false) // {{{
{
    if ($retcode) {
        return md5($retcode."166481829Ù");
    }

    /* Profiling */
    global $licenseTime;
    if (!isset($licenseTime)) {
        $licenseTime = 0;
    }
    $startTime = getmicrotime();
    /**************/
    $configTable = "xlite_config";

    license_callback_check();
    static $checked;
    static $data;
    if (isset($checked) || isset($data)) {
        return $data;
    }
    
    global $xlite;
    if (!isset($xlite)) {
        return;
    }
    if (!isset($data)) {
        // read license file
        $license = @file("LICENSE");
        if (!$license) {
            close_shop("license_inactive");
            return array("modules" => array());
        } else {

            // skip comments, retrieve body
            $state = 0;
            $body = '';
            foreach ($license as $line) {
                if (trim($line) == "-------------------------------------------------------------------------------") {
                    $state++;
                } else {
                    if ($state == 1) {
                        $body .= trim($line);
                    }
                }
            }
            // decrypt license body
            $s = $body;
            $enc = 105 ^ ((ord(substr($s, 0, 1)) - 101)*16 + ord(substr($s, 1, 1)) - 101);
            $result = '';
            for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
                $result .= chr((((ord(substr($s, $i, 1)) - 101)*16 + ord(substr($s, $i+1, 1)) - 101) ^ $enc+=11)&0xff);
            }

            $license = $result;
            $license_data = explode("\n", $license);
            $data = array();
            foreach ($license_data as $line) {
                if (trim($line) == "" || $line{0} == "#") continue;
                list($key, $value) =  explode("=", $line);
                ($key == "license_no" || $key == "domain" || $key == "name" || $key == "expire" || $key == "type" || $key == "issue_date" || $key == "modules" || $key == "access_key"|| $key == "signature" || $key == "version" || $key == "N") or die("Invalid license");
                $data[$key] = $value;
            }
            $modules = array();
            foreach(explode(",", $data["modules"]) as $moduleLicense) {
                if ($moduleLicense == "") continue;
                list($name, $expiration) = explode(":", $moduleLicense);
                $module = func_new("Object");
                $module->name = $name;
                $module->expiration = $expiration;
                $modules[] = $module;
            }
            $data["modulesString"] = $data["modules"];
            $data["modules"] = $modules;
        }
    }
    if ($get_license_data) {
        $licenseTime += getmicrotime() - $startTime; // Profiling
        return $data;
    }
    // license version check
    if (version_compare($data["version"], "2", "<") || version_compare($data["version"], "3", ">=")) {
        close_shop("wrong_version");
    }
    license_signature_check($data);

    // check the license
    $checked = true;
    if($result = @mysql_query("SELECT comment FROM $configTable WHERE category='General' AND name='shop_closed'")) {
        list ($comment) = mysql_fetch_row($result);

        if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["access_key"]) && md5($_POST["access_key"]) == $data["access_key"]) {
            if ($_POST["access_type"] == "activate") {
                $comment = trim($comment);
                @touch("LICENSE");
                print "activated";
            } else if ($_POST["access_type"] == "deactivate" && strlen($comment)<255) {
                $comment = ' ' . $comment;
                @touch("LICENSE", @filemtime("classes/base/Object.php"));
                print "deactivated";
            }
            @mysql_query("UPDATE $configTable SET comment='".addslashes($comment)."' WHERE category='General' AND name='shop_closed'");
        }

        if (substr($comment, 0, 1) == ' ') {
            close_shop("license_inactive");
        }

        // check deactivation
        if (filemtime("classes/base/Object.php") == @filemtime("LICENSE")) {
            close_shop("license_inactive");
        }

        // check system time change
        $query = @mysql_query("SELECT value FROM $configTable WHERE name='shipping_code'");
        list($encTime) = mysql_fetch_row($query);
        $masks = array(2039013960,1520136344,457765824,1005967472,1349905296,253254040,465198856,1938970296);
        $updateTime = true;
        if ($encTime != 1702936249) {
            // decode
            $ind = $encTime & 7;
            $encTime = $encTime ^ $masks[$ind];
            if ($encTime-24*3600 > time()) {
                close_shop("license_expired");
                $updateTime = false;
            }
        }
        if ($updateTime) {
            $encTime = time();
            // encode
            $encTime = $encTime ^ $masks[$encTime & 7];
            @mysql_query("UPDATE $configTable SET value='$encTime' WHERE name='shipping_code'");
        }
    }

    // check expipration
    if ($data["expire"] && $data["expire"] < time() || $data["issue_date"] > time() + 24*3600) {
        close_shop("license_expired");
    }
    if (isset($_SERVER["HTTPS"])) {
        $HTTPS = $_SERVER["HTTPS"];
    } else {
        $HTTPS = "";
    }
    if (empty($HTTPS) && isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 443) {
        $HTTPS = "on";
    }
    // check domain name
    global $options;
    $domainName = $options["host_details"]["http_host"];
    // strip the port
    $pos = strpos($domainName, ':');
    if ($pos !== false) {
        $domainName = substr($domainName, 0, $pos);
    }
    if (!$HTTPS && php_sapi_name() != 'cli') {
        $host = $_SERVER["HTTP_HOST"];
    } else {
        $host = $domainName;
    }
    // strip the port
    $pos = strpos($host, ':');
    if ($pos !== false) {
        $host = substr($host, 0, $pos);
    }
    $domains = explode(',', $data["domain"]);
	if (!is_array($domains)) {
		$domains = array();
	}
    $found = false;
    foreach($domains as $domain) {
        if (compare_domains($domainName, $domain)) {
            $found = true;
            break;
        }
    }
    foreach($domains as $domain) {
        if (compare_domains($host, $domain)) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        close_shop("license_invalid_domain");
    }
    if ($data["type"] == "basic" && $data["expire"]) {
        if($data["expire"] - 10*24*3600 < time()) {
            $expdate = ceil(($data["expire"]-time()) / (24*3600));
            if ($expdate > 0) {
                $GLOBALS["license_warning"] = "activate_license";
                $GLOBALS["license_warning_param"] = $expdate;
            } else {
                $GLOBALS["license_warning"] = "license_expired";
            }
        }

    } /*else if ($data["type"] == "development") {
        if (($_SERVER['REQUEST_METHOD'] != 'POST' || $_GET) && isset($_GET["target"]) && $_GET["target"] != "image") {
            // show a message periodically
            if (rand() % 25 == 0) {
                die("This license is granted for development purpose only and should not be used on commercial base. Please send an e-mail to <a href='mailto: piracy@x-cart.com?Subject=PIRACY WARNING!'><u>piracy@x-cart.com</u></a><br><b>Refresh this page to get rid of this message");
            }
        }
    }*/

    $licenseTime += getmicrotime() - $startTime; // Profiling
    return $data;
} // }}}

function compare_domains($a, $b) // {{{
{
	$a = strtolower($a);
	if (substr($a, 0, 4) == 'www.') {
		$a = substr($a, 4);
	}
	$b = strtolower($b);
	if (substr($b, 0, 4) == 'www.') {
		$b = substr($b, 4);
	}
	return $a == $b;
} // }}}

function check_module_license($name, $ignoreErrors = false) // {{{
{
    global $xlite;
    if (!isset($xlite)) {
        $xlite = true;
    }

	$data = license_check();
    if (array_key_exists('N', $data) && !$ignoreErrors) {
        die("Cannot initialize non-ASP module " . $name);
    }
	foreach($data["modules"] as $module) {
		if ($module->name == $name && (!$module->expiration || $module->expiration>time())) {
			return true;
		}
	}
    if ($ignoreErrors) {
	    return false;
    } else {
        die("Cannot initialize module $name: the module license is invalid");
    }
} // }}}

function check_module_license_asp($name) // {{{
{
    global $xlite;
    if (!isset($xlite)) {
        $xlite = true;
    }

	$data = license_check();
	foreach($data["modules"] as $module) {
		if ($module->name == $name && (!$module->expiration || $module->expiration>time())) {
			return true;
		}
	}
    die("Cannot initialize ASP module $name: the module license is invalid");
} // }}}
} else {

// check license

// {{{
	$t = time();
	if (license_check(false, $t) != md5($t."166481829Ù")) {
	    die("license is inactive");
    }    
// }}}    

}

license_check();

?>
