<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*
* LiteCommerce customer front-end.
*
* $Id: admin.php,v 1.20 2008/11/27 12:13:03 sheriff Exp $
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

// defines interface
$GLOBALS["XLITE_SELF"] = "admin.php";
$options = parse_ini_file("config.php", true);
if (empty($options)) {
    die("Unable to read/parse configuration file(s)");
}
$s = $options["primary_installation"]["path"];
$enc = 105 ^ ((ord(substr($s, 0, 1)) - 101)*16 + ord(substr($s, 1, 1)) - 101);
$result = '';
for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
    $result .= chr((((ord(substr($s, $i, 1)) - 101)*16 + ord(substr($s, $i+1, 1)) - 101) ^ $enc+=11)&0xff);
}
$primaryDir = $result;
$shopDir = getcwd();
$compileDir = $options["decorator_details"]["compileDir"];
$lockDir = $options["decorator_details"]["lockDir"];
$primaryInstallation = '.';
chdir ($primaryDir);

/////////////////////////////////////////////////////////////////////

// replace database for license check
$options_shop = $options;
$server_http_host_shop = $_SERVER["HTTP_HOST"];
$options = array();

// reads configuration files
include_once "./classes/modules/asp/prepend.php";

// reads configuration file(s)
$options_main  = parse_ini_file("./etc/config.php", true);
if (file_exists("./etc/config.local.php")) {
    $options_local = @parse_ini_file("./etc/config.local.php", true);
    $options       = @array_merge($options_main, $options_local);
} else {
    $options = $options_main;
}
if (empty($options)) {
    die("Unable to read/parse configuration file(s)");
}    
$s = $options["database_details"]["password"];
$enc = 105 ^ ((ord(substr($s, 0, 1)) - 101)*16 + ord(substr($s, 1, 1)) - 101);
$result = '';
for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
    $result .= chr((((ord(substr($s, $i, 1)) - 101)*16 + ord(substr($s, $i+1, 1)) - 101) ^ $enc+=11)&0xff);
}
$options["database_details"]["password"] = $result;
// replace server name and http_host for license check
$_SERVER["HTTP_HOST"] = $options["host_details"]["http_host"];

func_read_classes("modules/asp");
$xlite_defined_classes = array();
$options["decorator_details"]["compileDir"] = $compileDir;
$options["decorator_details"]["lockDir"] = $lockDir;
$xlite =& func_new("XLite");
$xlite->set("adminZone", true);
func_new("Object"); // license check
// check that the shop is enabled
$DSN = $options["database_details"];
$connection = mysql_connect($DSN["hostspec"], $DSN["username"], $DSN["password"]);
mysql_select_db($DSN["database"], $connection);
$shopDirChecking = $shopDir;
if (LC_OS_IS_WIN) {
	$shopDirChecking = strtolower($shopDirChecking);
	$shopDirChecking = str_replace("\\", "/", $shopDirChecking);
}
$res = mysql_query("select * from xlite_asp_shops where path='" . addslashes($shopDirChecking) . "' and enabled=1", $connection);
$shop = mysql_fetch_assoc($res);
if (!$shop) {
    @readfile('shop_closed.html');
    die("<!-- This installation is inactive -->");
}
@ini_set("memory_limit", (($shop["memory_limit"]) ? $shop["memory_limit"] : "16M"));

// read profile
if (isset($shop["profile"])) {
    $res = mysql_query("select rules from xlite_asp_profiles where name='" . $shop["profile"] . "'", $connection);
    list($accessPolicy) = mysql_fetch_row($res);
}
$res = mysql_query("select count(*) from xlite_asp_shops", $connection);
list($shopCount) = mysql_fetch_row($res);
$data = license_check(true);
if ($shopCount>$data["N"]) {
    @readfile('shop_closed.html');
    die("<!-- The number of installations exceeds the license limit of $data[N] -->");
}

mysql_close($connection);

// restore database and host
$options = $options_shop;
$_SERVER["HTTP_HOST"] = $server_http_host_shop;

$primaryInstallation = $primaryDir;
chdir($shopDir);

/////////////////////////////////////////////////////////////////////

$xlite->initFromGlobals();
$xlite->set("layout.skin", "admin");

// runs LiteCommerce
$xlite->run();
$xlite->done();

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
