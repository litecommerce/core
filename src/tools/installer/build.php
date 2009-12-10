#!/usr/local/bin/php -Cq
<?php

/**
* Console script to pack the archive into installer executable. 
*
* Adds the specified archive to the end of compiled win32 executable
* installer and 32 bit of archive length.
*
*   ------------------
*  | win32 executable |
*  | ---------------- |
*  | archive (tar.gz) |
*  | ---------------- |
*  | 4 bytes archive  |
*  | length           |
*   ------------------
*
* $Id: build.php,v 1.7 2006/03/14 13:06:16 sheriff Exp $
*/

ini_set("memory_limit", "32M");

$curdir = getcwd();
$installer = $curdir . "/bin/install.exe";
$archive = $curdir . "/xlite.tar.gz";
$destination = $curdir . "/install.exe";

$includes  = "./../../classes" . PATH_SEPARATOR;
$includes .= "./../../lib" . PATH_SEPARATOR;
$includes .= "./lib" . PATH_SEPARATOR;
ini_set("include_path", $includes);

require_once "PEAR.php";
require_once "Console/Getopt.php";

error_reporting(E_ALL ^ E_NOTICE);

$argv = Console_Getopt::readPHPArgv();
$options = Console_Getopt::getopt($argv, "h?s:a:o:");
if (PEAR::isError($options)) {
    usage($options);
}

foreach ($options[0] as $opt) {
    $param = $opt[1];
        switch($opt[0]) {
            case 's':
                $installer = $param;
                break;
            case 'a':
                $archive = $param;
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

echo "Build installer executable .. ";

$content = @file_get_contents($archive) or die("Archive $archive not found\n");
$filesize = filesize($archive);
@copy($installer, $destination) or die("Copying $installer => $destination FAILED\n");
$handle = fopen($destination, "ab") or die("Unable to open $destination\n");
fwrite($handle, $content);
fwrite($handle, pack("V", $filesize));
fclose($handle);

echo "[OK]\n";
echo "\ninstaller executable successfully saved to $destination\n";

function usage($obj = null) {
    if ($obj !== null) {
        print $obj->getMessage()."\n";
    }
    $usage =<<<EOT
Usage: build.php [-h] [-s file] [-a file] [-o file]
Options:
    -s file The win32 installer executable skeleton
    -a file The archive to add to executable
    -o file The destiantion installer executable
    -h, -?  this help/usage


EOT;
    print $usage;
    exit(1);
}
?>
