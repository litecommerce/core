<?php

$allowedTargets = array(
    'addons',
	'addon',
	'license',
    'version',
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

	    $keyFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'paid-modules' . DIRECTORY_SEPARATOR
    	    . basename($_GET['author']) . '_' . basename($_GET['module']) . '.key';

		$refundKeyFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'paid-modules' . DIRECTORY_SEPARATOR
            . basename($_GET['author']) . '_' . basename($_GET['module']) . '.refund.key'; 

		// Refund keys must be stored separately to prevent repeated use in main KEY set
		$refundKeys = file_exists($refundKeyFile) ? file($refundKeyFile, FILE_IGNORE_NEW_LINES) : array();

		$keys = file_exists($keyFile) ? file($keyFile, FILE_IGNORE_NEW_LINES) : array();

		// TODO after changing to DB:
		// Only 7 downloads in a week are allowed for one key [9 demand]

	    if (
			in_array($_GET['key'], $keys)
			&& !in_array($_GET['key'], $refundKeys)
		) {
                
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

}


exit(0);
?>
