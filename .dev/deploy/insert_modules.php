<?php
/*
 * $Id$
 *
 * Inserting modules information into the database
 *
 * @param string baseDir LiteCommerce root directory
 * @param string configFile LiteCommerce config file name (only filename, w/o path)
 */


function _do_query_upload($sqlfile, $connection = null, $ignoreErrors = false) {
	echo "+++ $sqlfile\n";
	ob_start();
	query_upload($sqlfile, $connection, $ignoreErrors);
	$output = ob_get_contents();
	ob_end_clean();
	$output = strip_tags($output);
	if (!empty($output) && preg_match("/\[NOTE:/", $output)) {
		echo "\n$output\n";
	}
}

if (isset($argv[1])) {
	$baseDir = trim($argv[1]);
}

if (isset($argv[2])) {
	$configFile = trim($argv[2]);
}

// TODO[INCLUDES]
include $baseDir . "/Includes/functions.php";

error_reporting(E_ALL ^ E_NOTICE);
$config = parse_ini_file($baseDir . '/etc/' . $configFile);

$config['hostspec'] .= empty($config['socket'])
	? (empty($config['port']) ? '' : ':' . $config['port'])
	: ':' . $config['socket'];
$connection = mysql_connect($config["hostspec"], $config["username"], $config["password"]);
mysql_select_db($config["database"]);

require_once $baseDir . '/classes/XLite/Base.php';
require_once $baseDir . '/classes/XLite/Model/Abstract.php';
require_once $baseDir . '/classes/XLite/Model/Module.php';
require_once $baseDir . '/classes/XLite/Module/Abstract.php';

// modules
$modules = opendir($baseDir . '/classes/XLite/Module');

while (($dir = readdir($modules)) !== false) {
	if ($dir{0}!='.' && is_dir($baseDir . '/classes/XLite/Module/' . $dir)) {

		require_once $baseDir . '/classes/XLite/Module/' . $dir . '/Main.php';
		$class = 'XLite_Module_' . $dir . '_Main';

		mysql_query('REPLACE INTO xlite_modules SET name = \'' . $dir . '\', enabled = 1, mutual_modules = \'' . implode(',', call_user_func(array($class, 'getMutualModules'))) . '\', type = \'' . call_user_func(array($class, 'getType')). '\'');
	}
}

closedir($modules);

mysql_close($connection);

