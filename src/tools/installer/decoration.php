#!/usr/local/bin/php -Cq
<?php

/*
* $Id: decoration.php,v 1.4 2006/11/22 15:16:21 osipov Exp $
*/

ini_set("memory_limit", "32M");

$curdir = getcwd();

$includes  = "./../../classes" . PATH_SEPARATOR;
$includes .= "./../../lib" . PATH_SEPARATOR;
$includes .= "./lib" . PATH_SEPARATOR;
ini_set("include_path", $includes);

require_once "PEAR.php";
require_once "Console/Getopt.php";

error_reporting(E_ALL ^ E_NOTICE);

$argv = Console_Getopt::readPHPArgv();
$options = Console_Getopt::getopt($argv, "h?s:o:");
if (PEAR::isError($options)) {
    usage($options);
}

foreach ($options[0] as $opt) {
    $param = $opt[1];
        switch($opt[0]) {
            case 's':
                $sourse = $param;
            break;
            case 'o':
                $destination = $param;
            break;
            case 'h':
            case '?':
            default:
                usage();
            break;
        }
}

//if (substr($sourse, 0, 12) == "classes/Log/") {
//if (preg_match("/^(?:dist\/)?classes\/Log/", $sourse)) {
//	die;
//}

$content = @file_get_contents($sourse) or die("Sourse $sourse not found\n");
//echo $sourse . " ";
$content = explode("\n", $content);

foreach ($content as $lineKey => $line) {
	if (preg_match("/^class\s+(\w+)\s+(?:extends)\s+(\w+)/", $line, $out)) {
		if ($out[2] != "Log") {
			for (;;) {
				$newLine = str_replace("  ", " ", $line);
				if (strlen($newLine) == strlen($line)) {
					break;
				}
				$line = $newLine;
			}
			$line = explode(" ", $newLine);
			$line[1] = strtolower($line[1]) . "__";
			$line[3] = strtolower($line[3]) . "__";
			$line = implode(" ", $line);
		}
//echo " -1- ";
	} elseif (preg_match("/^class\s+(\w+)/", $line)) {
		for (;;) {
			$newLine = str_replace("  ", " ", $line);
			if (strlen($newLine) == strlen($line)) {
				break;
			}
			$line = $newLine;
		}
		$line = explode(" ", $newLine);
		$line[1] = strtolower($line[1]) . "__";
		$line = implode(" ", $line);
//echo " -2- ";
	}

	$content[$lineKey] = $line;
}

$content = implode("\n", $content);

//echo $destination . "\n";
$handle = fopen($destination, "w") or die("Unable to open $destination\n");
fwrite($handle, $content);
fclose($handle);

function usage($obj = null) {
    if ($obj !== null) {
        print $obj->getMessage()."\n";
    }
    $usage =<<<EOT
Usage: decoration.php [-h] [-s file] [-o file]
Options:
    -s file The source file
    -o file The destiantion file
    -h, -?  this help/usage


EOT;
    print $usage;
    exit(1);
}
?>
