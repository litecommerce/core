<?php

$allowedTargets = array(
    'addons',
	'addon',
	'license',
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

	// FREE MODULES !!!!! ONLY!!!!!
	// TODO !!! PAID MODULES RETRIVING!!!

	$outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
		. basename($_GET['author']) . '_' . basename($_GET['module']) . '.phar';

	if (file_exists($outFile) && is_readable($outFile)) {
        echo file_get_contents($outFile);
    }

} elseif ('license' === $_GET['target']) {

	$outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'licenses' . DIRECTORY_SEPARATOR  
        . basename($_GET['author']) . '_' . basename($_GET['module']);

    if (file_exists($outFile) && is_readable($outFile)) {
        echo file_get_contents($outFile);
    }
}


exit(0);
?>
