#!/usr/local/bin/php -q -d register_argc_argv=On
<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*
* LiteCommerce ASP Edition Control Center batch processor.
*
* $Id: batch.php,v 1.15 2009/03/17 09:57:35 fundaev Exp $
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 
*/

// ================== Batch script configuration ==========================

$cfg['host'] = ''; // Server host name where batch is running. Must be equal to 
                   // host name you were using during ASP installation.
$cfg['user'] = ''; // Control Center admin username (email)
$cfg['password'] = ''; // Control Center admin password.
$cfg['mysql_root_user'] = '';       // Privileged user to access MySQL database
$cfg['mysql_root_password'] = '';   // Use saved if empty.
$cfg['access-policy'] = 'web based'; // The default access policy.
$cfg['modules'] = ''; // The default modules set, comma delimited

// ========================================================================

// install/update/remove shop {{{

!isset($_SERVER["REQUEST_METHOD"]) or die("ACCESS DENIED");

// read and parse command line arguments
require_once "./includes/functions.php";
require_once "./includes/decoration.php";
$lib_dir = func_is_php5() ? "lib5" : "lib";
$include_path = ini_set("include_path", $lib_dir);
require_once "System.php";
$whoami = $argv[0];

$argc > 1 or usage();

$con  = new Console_Getopt;
$args = $con->readPHPArgv();
$shortopt = "u:s::p::a::m::d::N::U::C::P::l::z::r::q?";
$longopt = array("url=", "secure_url==", "path=", "access_policy==", "modules==", "database_name==", "create_database==", "database_user==", "create_user==", "database_passwd==", "login==", "password==", "admin_login==", "admin_passwd==", "db_root_user==", "db_root_passwd==", "remove_shop==", "quiet", "help");

@array_shift($args);
$options = $con->getopt($args, $shortopt, $longopt);
if (PEAR::isError($options)) {
    usage($options);
}

// Fill request properties

$_SERVER["HTTP_HOST"] = $cfg['host'];
// install request
$_REQUEST["target"] = "shops";
$_REQUEST["action"] = "install";
// auto-login admin username and password
$_POST["login"] = $cfg['user'];
$_POST["password"] = $cfg['password'];
if (!empty($cfg['access-policy'])) {
    $_POST["shop_profile"] = $cfg['access-policy'];
}
if (!empty($cfg['mysql_root_user'])) {
    $_POST["root_user"] = $cfg['mysql_root_user'];
}    
if (!empty($cfg['mysql_root_password'])) {
    $_POST["root_password"] = $cfg['mysql_root_password'];
}    
if (!empty($cfg['modules'])) {
    $_POST["shop_modules"] = explode(',', $cfg['modules']);    
}

$_POST["shop_db_database_usage"] = "exists";
$_POST["shop_db_user_usage"] = "exists";

// parse command line
foreach ($options[0] as $opt) {
    $param = $opt[1];
	if ($param{0} == '=') $param = substr($param, 1);
    switch($opt[0]) {
        case 'u':
        case '--url':
            $_POST["shop_url"] = $param;
            break;
        case 's':
        case '--secure_url':
            $_POST["shop_secure_url"];
            break;
        case 'p':
        case '--path':
            $_POST["shop_path"] = $param;
            break;
        case 'a':
        case '--access_policy':
            $_POST["shop_profile"] = $param;
            break;
        case 'm':
        case '--modules':
            $_POST["shop_modules"] = explode(',', $param);
            break;
        case 'd':
        case '--database_name':
            $_POST["shop_db_database"] = $param;
            break;
        case 'N':
        case '--create_database':
            $_POST["shop_db_database_usage"] = $param!='Y' ? "exists" : "";
            break;
        case 'U':
        case '--database_user':
            $_POST["shop_db_user"] = $param;
            break;
        case 'C':
        case '--create_user':
            $_POST["shop_db_user_usage"] = $param!='Y' ? "exists" : "";
            break;
        case 'P':
        case '--database_passwd':
            $_POST["shop_db_password"] = $param;
            break;
        case 'l':
        case '--login':
            $_POST["shop_user"] = $param;
            break;
        case 'z':
        case '--password':
            $_POST["shop_password"] = $_POST["shop_password_confirm"] = $param;
            break;
        case '--admin_login':
            $_POST["login"] = $param;
            break;
        case 'z':
        case '--admin_passwd':
            $_POST["password"] = $param;
            break;
        case '--db_root_user':
            $_POST["root_user"] = $param;
            break;
        case 'z':
        case '--db_root_passwd':
            $_POST["root_password"] = $param;
            break;
        case 'r':
        case '--remove_shop':
            $_REQUEST["action"] = "uninstall";
            $_POST["remove_files"] = "Y";
            $_POST["remove_database"] = "Y";
            break;
        case 'q':
        case '--quiet':
            $quiet = true;
            break;
        case '?':
        case '--help':
            usage();
            break;
    }                                               
}

function usage($options = null) {
    global $whoami;
    if (PEAR::isError($options)) {
        print "Error: " . $options->getMessage() . "\n\n";
    }
    $usage =<<<EOT
LiteCommerce ASPE Batch Processor v. 2.2

SYNOPSIS
	batch.php --admin_login=<value> --admin_passwd=<value> [--OPTIONS=<args>]
	batch.php --admin_login=<value> --admin_passwd=<value> -r -u=<value>

OPTIONS
Administrator credentials:
 --admin_login=...          LiteCommerce ASPE administrator login
 --admin_passwd=...         LiteCommerce ASPE administrator password
 --db_root_user=...         MySQL root username (uses saved if empty)
 --db_root_passwd=...       Password for MySQL root user (uses saved if empty)

Shop parameters:
 -u=, --url=...             URL of a new shop, including web directory
                            Example: http://www.example.com/shop1
 -s=, --secure_url=...      HTTPS URL of the new shop (optional)
                            Example: https://secure.example.com/shop1
 -p=, --path=...            Absolute file path to the new shop
 -d=, --database_name=...   MySQL database name for the new shop
 -N=, --create_database=... 'Y' if the database should be created
 -U=, --database_user=...   MySQL database username for the new shop
 -C=, --create_user=...     If this parameter has 'Y' value the user will be created
 -P=, --database_passwd=... Password for MySQL database username
 -l=, --login=...           E-mail of the administrator of the new shop
 -z=, --passwd=...          Password for the administrator of the new shop
 -a=, --access_policy=...   Access policy to assign to the new shop
 -m=, --modules=...         Comma-separated list of modules to install
                            on the new shop (optional)

Operation modes:
 -r, --remove_shop          Uninstall a shop

Other:
 -q, --quiet                Operate in quiet mode
 -?, --help                 Display this help and exit

EOT;
    print $usage;
    exit(1);
}
 
ini_set("include_path", $include_path);

// defines interface
$GLOBALS["XLITE_SELF"] = "cpanel.php";

// reads configuration
include_once "includes/prepend.php";
func_read_classes('modules/asp');

// fires up and runs the LiteCommerce admin interface

if ($quiet) {
    ob_start();
}

$xlite =& func_new("AspXLite");
func_add_decorator('Module', 'AspCPModule');
$xlite->set("aspZone", true);
$xlite->initFromGlobals();
$xlite->set("layout.skin", "admin");
$xlite->run();
$xlite->done();

if ($quiet) {
    ob_end_clean();
}

exit(isset($retcode) ? $retcode : 0);

// }}}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
