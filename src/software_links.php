#!/usr/local/bin/php -Cq
<?php

/*
* $Id: software_links.php,v 1.3 2009/10/19 11:27:44 fundaev Exp $
*/

define("SERVICE_URL", "https://x-business.crtdev.local/service.php");

$includes  = "." . DIRECTORY_SEPARATOR . "classes" . PATH_SEPARATOR;
$includes .= "." . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR;
ini_set("include_path", $includes);

require_once "PEAR.php";
require_once "HTTP/Request.php";
require_once "Console/Getopt.php";

error_reporting(E_ALL ^ E_NOTICE);

$argv = Console_Getopt::readPHPArgv();
$options = Console_Getopt::getopt($argv, "h?:d:b:p:");
if (PEAR::isError($options)) {
    usage($options);
}

$destination = "";
$brandName = "LC";
$replacePattern = "";

$isGoodParams = 0;
$preamble = null;
foreach ($options[0] as $opt) {
    $param = $opt[1];
        switch($opt[0]) {
            case 'p':
                $replacePattern = $param;
                $isGoodParams++;
            break;
            case 'd':
                $destination = $param;
                $isGoodParams++;
            break;
            case 'b':
                $brandName = $param;
                $isGoodParams++;
            break;
            case 'h':
            case '?':
            default:
                usage();
            break;
        }
}

if (!$destination || !$replacePattern || !$brandName) {
    echo "WARNING: Wrong usage.\n";
	usage();
}

if (!@file_exists($destination)) {
	critical_error("ERROR: Destination file '$destination' not found.\n");
}
if (!@is_writeable($destination)) {
	critical_error("ERROR: Destination file '$destination' not writeable.\n");
}

$content = @file_get_contents($destination);
if (!$content) {
	critical_error("ERROR: Can not read '$destination' file, or file is empty.\n");
}

if (strpos($content, $replacePattern) === false) {
	critical_error("ERROR: Replace pattern not found.\n");
}


// Retrieve service data
$http = new HTTP_Request(SERVICE_URL); 
$http->_timeout = 10;
$http->_method = HTTP_REQUEST_METHOD_POST;
$http->addPostData("target", "get_software_link_names");
$http->addPostData("brand", $brandName);

$result = @$http->sendRequest();
if (PEAR::isError($result)) {
	critical_error("ERROR: Connection error.\n");
}
$result = $http->getResponseBody();

list($status, $data) = explode(":", $result);
if ($status != "OK") {
	critical_error("ERROR: Server error.\n");
}

$result = explode(",", $data);
if (!is_array($result) || count($result) <= 0) {
	critical_error("ERROR: Bad data.\n");
}

$replace = "\"".implode("\", \"", $result)."\"";
$content = str_replace($replacePattern, $replace, $content);

if ($handle = @fopen($destination, "wb")) {
	fwrite($handle, $content);
	fclose($handle);
} else {
	critical_error("ERROR: Unable to open '$destination' file for writing.\n");
}

exit(0);

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
function critical_error($message, $return_error=true)
{
	echo $message;
	exit((($return_error) ? 1 : 0));
}

function usage($obj = null) {
    if ($obj !== null) {
        print $obj->getMessage()."\n";
    }
    $usage =<<<EOT
Usage: software_links.php [-h] <-b brand name> <-p pattern> <-d file>
Options:
    -b brand name (LC - LiteCommerce)
    -p replace pattern in the destination file
    -d file The destiantion file
    -h, -?  this help/usage


EOT;
    print $usage;
    exit(1);
}
?>
