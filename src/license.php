<?php

/*
* $Id: license.php,v 1.87 2007/05/15 12:26:59 sheriff Exp $
*/

// seed with microseconds
list($usec, $sec) = explode(' ', microtime());
srand((float) $sec + ((float) $usec * 100000));

if (!function_exists("license_check")) {

// define license functions

function close_shop($reason, $additional=false) // {{{
{
    if (!$additional) {
		$GLOBALS["license_warning"] = $reason;
	}
    global $xlite;
    // current script name
    if ($GLOBALS["XLITE_SELF"] == "cart.php" || substr($_SERVER["PHP_SELF"], -9) != "admin.php" && substr($_SERVER["PHP_SELF"], -10) != "cpanel.php" || substr($_SERVER["PATH_TRANSLATED"], -8) == "cart.php" || !is_object($xlite) || !($xlite->is("adminZone") || $xlite->is("aspZone")) || $additional) {
        @readfile('shop_closed.html');
    	if (!$additional) {
        	die("<!-- $reason -->");
        } else {
        	die($reason);
        }
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
    $key = $rsa->decKeyFromString("B5BDC3ED09B69B78B86E7434E29DC05B89B04A2633ABD3AF53F757A4D1FC92B 6A9F09E291A44E9438CC14391F034B38489313CCB007649806CE7AF3F7C857518C5");

	$decr = $rsa->decryptMD5($key, $license['signature']);
    return $decr;
} // }}}

function license_signature_check($license) // {{{
{
    global $options;
	global $xlite;

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
            	@mysql_query("REPLACE INTO xlite_config (value,name,category) VALUES ('','license','License')");
            	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
            	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
            		$license_check_debug = false;
            	}
                close_shop("license_inactive" . (($license_check_debug)?"1":""));
            } else {
            	@mysql_query("REPLACE INTO xlite_config (value,name,category) VALUES ('$decr','license','License')");
            }
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
        @mysql_connect($options["database_details"]["hostspec"], $options["database_details"]["username"], $options["database_details"]["password"]) or (!is_object($xlite) || (is_object($xlite) && !is_object($xlite->xlite))) ? close_shop("<hr><font color=red><b>FATAL ERROR:</b> " . mysql_error() . "</font>", true) : die;
        @mysql_select_db($options["database_details"]["database"]) or (!is_object($xlite) || (is_object($xlite) && !is_object($xlite->xlite))) ? close_shop("<hr><font color=red><b>FATAL ERROR:</b> " . mysql_error() . "</font>", true) : die;
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
            	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
            	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
            		$license_check_debug = false;
            	}
                close_shop("license_invalid_domain" . (($license_check_debug)?"1":""));
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
    global $options;

    if (!isset($xlite)) {
        return;
    }
    if (!isset($data)) {
        // read license file
        $license = @file("LICENSE");
        if (!$license) {
        	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
        	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
        		$license_check_debug = false;
        	}
			// TODO - license checking is partially disabled - reworked
            // close_shop("license_inactive" . (($license_check_debug)?"2":""));
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
        	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
        	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
        		$license_check_debug = false;
        	}
            close_shop("license_inactive" . (($license_check_debug)?"3":""));
        }

        // check deactivation
        if (filemtime("classes/base/Object.php") == @filemtime("LICENSE")) {
        	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
        	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
        		$license_check_debug = false;
        	}
            close_shop("license_inactive" . (($license_check_debug)?"4":""));
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
            	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
            	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
            		$license_check_debug = false;
            	}
                close_shop("license_expired" . (($license_check_debug)?"1":""));
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
    	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
    	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
    		$license_check_debug = false;
    	}
        close_shop("license_expired" . (($license_check_debug)?"2":""));
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
    	$license_check_debug = (isset($options["log_details"]) && isset($options["log_details"]["license_check"]) && $options["log_details"]["license_check"] == 1);
    	if (isset($xlite) && is_object($xlite) && $xlite->is("adminZone")) {
    		$license_check_debug = false;
    	}
        close_shop("license_invalid_domain" . (($license_check_debug)?"2":""));
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

    } else if ($data["type"] == "development") {
        if (($_SERVER['REQUEST_METHOD'] != 'POST' || $_GET) && isset($_GET["target"]) && $_GET["target"] != "image") {
            // show a message periodically
            if (rand() % 25 == 0) {
                die("This license is granted for development purpose only and should not be used on commercial base. Please send an e-mail to <a href='mailto: piracy@x-cart.com?Subject=PIRACY WARNING!'><u>piracy@x-cart.com</u></a><br><b>Refresh this page to get rid of this message");
            }
        }
    }

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
