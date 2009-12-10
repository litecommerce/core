<?php

error_reporting(E_ALL);

@include_once "includes/prepend.php";
// fixes compatibility issues
@include_once "compat/compat.php";

// set the include  path names for classes
ini_set("include_path", "." . PATH_SEPARATOR . "." . DIRECTORY_SEPARATOR . "classes" . PATH_SEPARATOR . "." . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR . ini_get("include_path"));

require_once "PEAR.php";

// set up environment
$config       = array();
$config       = parse_ini_file("./etc/config.php", true);
if (file_exists("./etc/config.local.php")) {
	$config_local = parse_ini_file("./etc/config.local.php", true);
	$options      = array_merge($config, $config_local);
} else {
	$options = $config;
}

if (!isset($config["database_details"])) {
    die("Unable to read/parse config file");
}
$xlite = func_new("XLite");
$xlite->initFromGlobals();
?>
