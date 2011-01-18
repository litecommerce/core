<?php

$allowedRequestTypes = array(
    'addonsList'
);

if (!in_array($request, $allowedRequestTypes)) {
   header('HTTP/1.0 404 Not Found');
   header('HTTP/1.1 404 Not Found');
   header('Status: 404 Not Found');
   die();
}

$outFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . $request . 'Response.xml';

if (file_exists($outFile) && is_readable($outFile)) {
    echo file_get_contents($outFile);
}

exit();
?>
