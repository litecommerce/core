#!/usr/local/bin/php -Cq
<?php

/*
* $Id: he_trial.php,v 1.2 2006/06/09 13:04:38 sheriff Exp $
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

$content = @file_get_contents($sourse) or die("Sourse $sourse not found\n");
//echo $sourse . " ";
$content = str_replace("/* TRIAL LICENSE START", "/* TRIAL LICENSE START */", $content);
$content = str_replace("TRIAL LICENSE END */", "/* TRIAL LICENSE END */", $content);
$content = str_replace("/* NORMAL LICENSE START */", "/* NORMAL LICENSE START", $content);
$content = str_replace("/* NORMAL LICENSE END */", "NORMAL LICENSE END */", $content);

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
