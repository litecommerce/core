<?php

$common_text = "Please visit our website at <a href=\"http://www.litecommerce.com\"><u><b>http://www.litecommerce.com</b></u></a> to purchase the full version of LiteCommerce Online Store Builder or download the latest demo version of LiteCommerce software.<br><br>If you have any questions please contact our sales team at <a href=\"mailto:sales@litecommerce.com\"><u><b>sales@litecommerce.com</b></u></a>";
$expiration_text = "<b><font color=\"red\">This evaluataion copy of LiteCommerce software has expired.</font></b><br><br>" . $common_text;
$restriction_text = "This is the demo version of LiteCommerce software.<br><br>" . $common_text . "<br><br>Refresh this page to continue.";
$modules_text = "<b><font color=\"red\">LiteCommerce data integrity check failed.</font></b><br><br>Please, reinstall the software.";

if (!function_exists('check_module_license')) {
	function check_module_license($module_name)
	{
		return true;
	}
}

if (!function_exists('decodeMD5')) {
    function decodeMD5($str)
    {
    	$strMD5 = $str;
        $str = "";

        for($i=0; $i<strlen($strMD5); $i++) {
        	$symbol = ord(substr($strMD5, $i, 1));
        	$symbol = ($symbol >= 48 && $symbol <= 57) ? ($symbol + 49) : ($symbol - 17);
        	$str .= chr($symbol);
        }

        $result = "";
        for ($i=0; $i<strlen($str); $i+=2) { 
        	$result .= chr(hexdec(substr($str, $i, 2))); 
        }

        return base64_decode($result);
    }
}

// check installed modules and expiration time {{{
	<AVAIBLE_MODULES_ARRAY>
	
	$options_main  = parse_ini_file("./etc/config.php", true);
	if (file_exists("./etc/config.local.php")) {
		$options_local = @parse_ini_file("./etc/config.local.php", true);
		$options       = @array_merge($options_main, $options_local);
	} else {
		$options = $options_main;
	}
	$modules = array();
	$modules_names = array();
	$modules_enabled = array();

	mysql_connect($options["database_details"]["hostspec"], $options["database_details"]["username"], $options["database_details"]["password"]) or die(mysql_error());
	mysql_select_db($options["database_details"]["database"]) or die(mysql_error());
	
	$ex_res = @mysql_query("SELECT value, comment FROM xlite_config WHERE name='tax_code' AND category='Tax'");
	$value = @mysql_fetch_row($ex_res);
	if (!$value) {
		die ($expiration_text);
	} else {
        $value_md5 = decodeMD5($value[1]);
        $value = hexdec($value[0]);
        global $trial_expiration;
        $trial_expiration = $value;
        if (($value < mktime (0, 0, 0, date("m"), date("d"), date("Y"))) || $value_md5 != strtolower(md5(dechex($value)))) {
        	if 
        	(
            	isset($_SERVER) && is_array($_SERVER) 
            	&& 
            	(
            		(isset($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"], "admin.php") !== false)
            		||
            		(isset($_SERVER["SCRIPT_NAME"]) && strpos($_SERVER["SCRIPT_NAME"], "admin.php") !== false)
            		||
            		(isset($_SERVER["PHP_SELF"]) && strpos($_SERVER["PHP_SELF"], "admin.php") !== false)
            	)
            ) {
            	$is_admin_zone = true;
            } else {
            	$is_admin_zone = false;
            }

            if (!$is_admin_zone) {
            	// customer zone
                die ($expiration_text);
            } else {
            	// admin zone
            	if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
            		if 
            		(!(
            			(strpos($_SERVER["HTTP_REFERER"], "target=login") !== false)
            			||
            			(strpos($_SERVER["HTTP_REFERER"], "target=export_catalog") !== false)
            			||
            			(strpos($_SERVER["HTTP_REFERER"], "target=db") !== false)
            			||
            			(strpos($_SERVER["HTTP_REFERER"], "target=wysiwyg") !== false)
            		)) {
                		die ($expiration_text);
            		}
            	} else {
            		global $expired_message;

            		$expired_message = "EXPIRED!!!";
            	}
            }
		} elseif ($value >= mktime (0, 0, 0, date("m"), date("d"), date("Y"))) {
    		global $expired_message;

    		$exp_days = floor(($value - mktime (0, 0, 0, date("m"), date("d"), date("Y")))/(3600*24));
    		if ($exp_days >= 0 && $exp_days <= 10) {
    			$expired_message = ($exp_days > 0) ? "$exp_days day(s) to expiration" : "expires tomorrow";
    		}
    	}
	}
	$res = @mysql_query("select * from xlite_modules") or die(mysql_error());
	while($row = mysql_fetch_array($res)) {
		$modules[] = $row;
	}
	foreach ($modules as $module) {
		$modules_names[] = $module['name'];
		$modules_enabled[$module['name']] = $module['enabled'];
	}
	if (count(array_diff($modules_names, $avaible_modules)) > 0 || count(array_diff($avaible_modules, $modules_names)) > 0) {
		die($modules_text);
	}
// }}}	

	$probability = 5;
	list($u,$s)=explode(' ',microtime()); srand((float)$s+((float)$u* 100000));

	if (rand(1, 1000) < $probability && $_REQUEST["target"] != "image") {
		if (($_SERVER['REQUEST_METHOD'] != 'POST' || $_GET) && isset($_GET["target"]) && $_GET["target"] != "image") {
			die ($restriction_text);
		}	
	}
?>
