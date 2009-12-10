#!/usr/local/bin/php -Cq
<?php

/**
* Console script to insert MD5 signature of essential files into installer script. 
*
* $Id: make_installmd5_asp.php,v 1.1 2008/11/27 06:49:49 sheriff Exp $
*/

ini_set("memory_limit", "32M");

$curdir = getcwd();
$installer_dir = $curdir . "/../../asp/";
$installer_script = $installer_dir . "install.php";

$includes  = "./../../classes" . PATH_SEPARATOR;
$includes .= "./../../lib" . PATH_SEPARATOR;
$includes .= "./lib" . PATH_SEPARATOR;
ini_set("include_path", $includes);

require_once "PEAR.php";

error_reporting(E_ALL ^ E_NOTICE);

$enc_sources = array
(
	"includes/decoration.php" => 0,
	"includes/prepend.php" => 0,
	"compat/gmp.php" => 0,
	"classes/base/Object.php" => 0,
	"classes/base/LObject.php" => 0,
	"classes/modules/asp/decoration.php" => 0,
	"classes/modules/asp/crypt.php" => 0,
	"classes/modules/asp/cart.php" => 0,
	"classes/modules/asp/admin.php" => 0,
	"classes/kernel/font.png" => 0,
);

$installer_script_body = @file_get_contents($installer_script);
if ($installer_script_body === false) {
	die("Unable to read $installer_script\n");
}

if (strpos($installer_script_body, "\$essentialFiles = array();") === false) {
	die("Unable to find pattern for patching\n");
}

foreach($enc_sources as $efile => $efileMD5) {
	$enc_sources[$efile] = ltrim(md5(@file_get_contents($installer_dir . $efile)), "0");
}

$enc_sources_str = array();
foreach($enc_sources as $efile => $efileMD5) {
	$enc_sources_str[] = "\"" . $efile . "\" => \"" . $efileMD5 . "\"";
}

$enc_sources_str = implode(", ", $enc_sources_str);
$enc_sources_str = "\$essentialFiles = array(" . $enc_sources_str . ");";

$installer_script_body = str_replace("\$essentialFiles = array();", $enc_sources_str, $installer_script_body);

$handle = @fopen($installer_script, "wb") or die("Unable to open $installer_script\n");
fwrite($handle, $installer_script_body);
fclose($handle);

?>
