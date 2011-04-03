<?php

// Request params
/*define('PARAM_ACTION', 'Action');

// Call withot an action is prohibited
if (empty($_REQUEST[PARAM_ACTION])) {
	exit(0);
}

// Output
define('LAST_AVAILABLE_CORE_VERSION', '1.2.3beta');
define('ADDONS_LIST_XML', __DIR__ . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'Response.xml');

// Perform action
switch ($_REQUEST[PARAM_ACTION]) {

	case 'get_last_core_version':
		echo (LAST_AVAILABLE_CORE_VERSION);
		break;

	case 'get_addons_list':
		echo (file_get_contents(ADDONS_LIST_XML));
        break;

	default:
		// ...
}*/


$allowedTargets = array(
    'addons',
	'addon',
	'license',
    'version',
	'info_by_key',
);

$allowedActions = array(
	'get',
);

if (
	!in_array($_GET['target'], $allowedTargets)
	|| !in_array($_GET['action'], $allowedActions)
) {
   header('HTTP/1.0 404 Not Found');
   header('HTTP/1.1 404 Not Found');
   header('Status: 404 Not Found');
   die();
}


if ('addons' === $_GET['target']) {

	$outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'Response.xml';

	if (file_exists($outFile) && is_readable($outFile)) {
    	echo file_get_contents($outFile);
	}

} elseif ('addon' === $_GET['target']) {

	if (isset($_GET['key'])) {

	    // Paid module retrieving
    	// TODO MAke DB request for a key
	    $outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'paid-modules' . DIRECTORY_SEPARATOR
    	    . basename($_GET['author']) . '_' . basename($_GET['module']) . '.phar';

		// TODO after changing to DB:
		// Only 7 downloads in a week are allowed for one key [9 demand]

		if (true === verifyKey($_GET['key'], $_GET['author'], $_GET['module'])) {
                
    		echo file_get_contents($outFile);

	    } else { 

        	echo 'Wrong key!';
    	}

	} else {

		// Free module retrieving
		$outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
			. basename($_GET['author']) . '_' . basename($_GET['module']) . '.phar';

		if (file_exists($outFile)) {

    	    echo file_get_contents($outFile);

	    } else {

			echo 'No module';
		}
	}

} elseif ('license' === $_GET['target']) {

	$outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'licenses' . DIRECTORY_SEPARATOR  
        . basename($_GET['author']) . '_' . basename($_GET['module']);

    if (file_exists($outFile) && is_readable($outFile)) {

        echo file_get_contents($outFile);

    } else {

		echo ('No license');
	}

} elseif ('version' === $_GET['target']) {

	$outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'version';

    if (file_exists($outFile) && is_readable($outFile)) {
        echo file_get_contents($outFile);
    }

} elseif ('info_by_key' === $_GET['target']) {

	$key = $_GET['license_key'];

	$info = getInfoByKey($key);

	$doc = new DOMDocument('1.0');

	$doc->formatOutput = true;

	$root = $doc->createElement('root');
	$doc->appendChild($root);

	if (false !== $info) {

		$author = $doc->createElement('author', $info[0]);
		$module = $doc->createElement('module', $info[1]);

		$root->appendChild($module);
		$root->appendChild($author);

	} else {

		$error = $doc->createElement('error', 'No such license key');

		$root->appendChild($error);
	}

	echo $doc->saveXML();
}


// TODO Must be removed when DB is used!!!
function getLicenseKeys()
{
	$licenseKeysFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'paid-modules' . DIRECTORY_SEPARATOR . 'License_Keys';

	$keys = file($licenseKeysFile, FILE_IGNORE_NEW_LINES);

	$new = array();

	foreach ($keys as $key) {

		$elem = explode('|', $key);

		$new[$elem[0]] = array(
			$elem[1],
			$elem[2],
		);
	}

	return $new;
}

function getInfoByKey($key)
{
	$keys = getLicenseKeys();

	return isset($keys[$key]) ? $keys[$key] : false;
}

// TODO : Add forbidden key check!!!!
function verifyKey($key, $author, $module)
{
	$info = getInfoByKey($key);

	return false !== $info && $info[0] === $author && $info[1] === $module;
}

