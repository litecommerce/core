#!/usr/local/bin/php -Cq
<?php

/**
* Console script to insert MD5 signature of essential files into installer script. 
*
* $Id: make_installmd5_2.php,v 1.1 2007/06/18 07:26:14 sheriff Exp $
*/

ini_set("memory_limit", "32M");

$curdir = getcwd();
$installer_dir = $curdir . "/";
$installer_script = $installer_dir . "install.php";

$includes  = "./classes" . PATH_SEPARATOR;
$includes .= "./lib" . PATH_SEPARATOR;
ini_set("include_path", $includes);

require_once "PEAR.php";

error_reporting(E_ALL ^ E_NOTICE);

$installer_script_body = @file_get_contents($installer_script);
if ($installer_script_body === false) {
	die("Unable to read $installer_script\n");
}

$installer_script_body = explode("\n", $installer_script_body);
$essentialFilesFound = null;
foreach ($installer_script_body as $lineIdx => $line) {
	if (strpos($line, "\$essentialFiles = array(") !== false) {
		$essentialFilesFound = $lineIdx;
	}
}

if (!isset($essentialFilesFound)) {
	die("Unable to find pattern for patching\n");
}

eval($installer_script_body[$essentialFilesFound]);

foreach($essentialFiles as $efile => $efileMD5) {
	$newFileMD5 = ltrim(md5(@file_get_contents($installer_dir . $efile)), "0");
	if ($newFileMD5 != $efileMD5) {
		echo "    $efile has been updated\n";
	}
	$essentialFiles[$efile] = $newFileMD5;
}

$enc_sources_str = array();
foreach($essentialFiles as $efile => $efileMD5) {
	$enc_sources_str[] = "\"" . $efile . "\" => \"" . $efileMD5 . "\"";
}

$enc_sources_str = implode(", ", $enc_sources_str);
$enc_sources_str = "    \$essentialFiles = array(" . $enc_sources_str . ");";

$installer_script_body[$essentialFilesFound] = $enc_sources_str;
$installer_script_body = implode("\n", $installer_script_body);

$handle = @fopen($installer_script, "wb") or die("Unable to open $installer_script\n");
fwrite($handle, $installer_script_body);
fclose($handle);

?>
