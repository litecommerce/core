#!/usr/local/bin/php -Cq
<?php

/*
* $Id: ion_caller.php,v 1.3 2009/08/14 12:30:39 fundaev Exp $
*/
echo "Encode with ioncube... ";
define("SERVICE_URL", "http://jaguar.crtdev.local:8080/ioncube/ion.php");

$includes  = "." . DIRECTORY_SEPARATOR . "classes" . PATH_SEPARATOR;
$includes .= "." . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR;
ini_set("include_path", $includes);

require_once "PEAR.php";
require_once "HTTP/Request.php";
require_once "Console/Getopt.php";

error_reporting(E_ALL ^ E_NOTICE);

$argv = Console_Getopt::readPHPArgv();
$options = Console_Getopt::getopt($argv, "h?s:d:o:p:");
if (PEAR::isError($options)) {
    usage($options);
}

$isGoodParams = 0;
$preamble = null;
foreach ($options[0] as $opt) {
    $param = $opt[1];
        switch($opt[0]) {
            case 's':
                $sourse = $param;
                $isGoodParams ++;
            break;
            case 'd':
                $destination = $param;
                $isGoodParams ++;
            break;
            case 'o':
                $encOptions = str_replace("_", " ", $param);
                $isGoodParams ++;
            break;
            case 'p':
                $preamble = $param;
            break;
            case 'h':
            case '?':
            default:
                usage();
            break;
        }
}

if ($isGoodParams != 3) {
    echo "WARNING: Wrong usage.\n";
	usage();
}

$content = @file_get_contents($sourse) or die("ERROR: Source $sourse not found\n");
if (isset($preamble)) {
	$preambleContent = @file_get_contents($preamble) or die("ERROR: Source of preamble $preamble not found\n");
}

$http = new HTTP_Request(SERVICE_URL); 
$http->_timeout = 10;
$http->_method = HTTP_REQUEST_METHOD_POST;
$http->addPostData("action", "encode");
$http->addPostData("options", $encOptions);
$http->addPostData("source_data", base64_encode(serialize($content)));
if (isset($preamble)) {
	$http->addPostData("preamble_data", base64_encode(serialize($preambleContent)));
}

$result = @$http->sendRequest();
if (PEAR::isError($result)) {
	$_this->errMessage = "ERROR: Connection error.";
	die("[ FAILED ]\n____________________\n\n".$_this->errMessage."\n____________________\n\n");
}
$result = $http->getResponseBody();

$result = explode("\n", $result);
if (!(is_array($result) && count($result) >= 4)) {
	$_this->errMessage = "ERROR: Bad data.\n";
	die("[ FAILED ]\n____________________\n\n".$_this->errMessage."\n____________________\n\n");
}

list($status, $length, $crc, $data) = $result;

if ($status != "OK") {
	$_this->errMessage = $status."\n";
	die("[ FAILED ]\n____________________\n\n".$_this->errMessage."\n____________________\n\n");
}
if (!(strlen($data) == $length && md5($data) == $crc)) {
	$_this->errMessage = "ERROR: Corrupted data of the server's response.";
	die("[ FAILED ]\n____________________\n\n".$_this->errMessage."\n____________________\n\n");
}

$data = unserialize(base64_decode($data));
if (!isset($data["encoded_data"])) {
	$_this->errMessage = "ERROR: Wrong format of the server's response.";
	die("[ FAILED ]\n____________________\n\n".$_this->errMessage."\n____________________\n\n");
}

$handle = @fopen($destination, "wb") or die("[ FAILED ]\n____________________\n\nERROR: Unable to open $destination\n____________________\n\n");
fwrite($handle, $data["encoded_data"]);
fclose($handle);

echo "[ OK ]\n";

exit(0);

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

function usage($obj = null) {
    if ($obj !== null) {
        print $obj->getMessage()."\n";
    }
    $usage =<<<EOT
Usage: ion_caller.php [-h] <-o options> [-p file] <-s file> <-d file>
Options:
    -o options The options for encoding source file
    -p file The preamble file for encoding source file
    -s file The source file
    -d file The destiantion file
    -h, -?  this help/usage


EOT;
    print $usage;
    exit(1);
}
?>
