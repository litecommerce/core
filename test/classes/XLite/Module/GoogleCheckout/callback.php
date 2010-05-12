<?php

global $gcheckout_timestamp;
list($usec, $sec) = explode(" ", microtime());
$gcheckout_timestamp = ((float)$usec + (float)$sec);

$_REQUEST['target'] = $_POST['target'] = "payment_method";
$_REQUEST['action'] = $_POST['action'] = "callback";
$_REQUEST['payment_method'] = $_POST['payment_method'] = "google_checkout";

// FIXME - is this needed?
// chdir('../../..');

include LC_ROOT_DIR . 'admin.php';

?>
