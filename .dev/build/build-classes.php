<?php
define('OPERATION_START_TIME', microtime(true));

if (php_sapi_name() != 'cli') {
	echo ('Only CLI mode!' . "\n");
	exit (1);
}

require_once ('./includes/prepend.php');
require_once ('./includes/functions.php');
require_once ('./includes/decoration.php');

$d = new Decorator();
$d->rebuildCache(true);

$duration = microtime(true) - OPERATION_START_TIME;
echo ('Operation duration time: ' . round($duration, 3) . ' sec.' . "\n");

exit (0);
