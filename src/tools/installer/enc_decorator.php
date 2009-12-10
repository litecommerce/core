#!/usr/local/bin/php -Cq
<?php

/*
* $Id: enc_decorator.php,v 1.2 2007/04/24 12:57:40 osipov Exp $
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
$options = Console_Getopt::getopt($argv, "h?d:");
if (PEAR::isError($options)) {
	usage($options);
}

$work_dir = "";

foreach ($options[0] as $opt) {
    $param = $opt[1];
        switch($opt[0]) {
            case 'd':
                $work_dir = $param;
            break;
            case 'h':
            case '?':
            default:
                usage();
            break;
        }
}

$classes_dir = (($work_dir) ? "$work_dir/classes" : "classes");
$file_classes_list = "$classes_dir/classes.lst";

$classes = array();

// parse classes file list
if ($handle = @fopen($file_classes_list, "r")) {
	while ($columns = fgetcsv($handle, 65535, ":")) {
		$classes[] = array(
			"class"		=> $columns[0],
			"file"		=> $columns[1],
			"extends"	=> $columns[2]
		);
	}

	fclose($handle);
} else {
	echo "Cannot open file: $file_classes_list";
	return 1;
}


$enc_classes = array();
$classes_length = count($classes);
$skip_classes_list = array("xlite", "object", "base");

// find classes chains
for ($i = 0; $i < $classes_length; $i++) {
	$classA =& $classes[$i];

	// skip base classes
	if (!$classA["extends"])
		continue;

	if (in_array($classA["class"], $skip_classes_list))
		continue;

	$is_rename = false;
	$base_class_file = "";
	$base_class_name = "";
	$extends_class_name = "";
	$extends_class_files = array();

	for ($j = 0; $j < $classes_length; $j++) {
		if ($i == $j)
			continue;

		$classB =& $classes[$j];

		if (in_array($classB["class"], $skip_classes_list))
			continue;


		// inheritance check
		$extends = explode(",", $classB["extends"]);
		if (in_array($classA["class"], $extends)) {
			$class_name = $classA["class"];

			$extends_class_files[] = $classB["file"];

			if ($is_rename)
				continue;

			$base_class_file = $classA["file"];
			$base_class_name = $class_name;
			$extends_class_name = "enc_".$class_name;

			$is_rename = true;
		}

	}

	if ($is_rename) {
		$enc_classes[] = array(
			"base_class_file"		=> $base_class_file,
			"base_class_name"		=> $base_class_name,
			"extends_class_name"	=> $extends_class_name,
			"extends_class_files"	=> $extends_class_files
		);
	}

}

//print_r($enc_classes);
//die;


//***** Process found classes result *****

// correct 'extends' class names
foreach ($enc_classes as $class) {
	foreach ($class["extends_class_files"] as $file_name) {
		$file_name = "$classes_dir/".$file_name;
		$content = file_get_contents($file_name);

		$content = preg_replace("/(class\s+[\w]+\s+extends\s+)(".$class["base_class_name"].")/i", '\1'.$class["extends_class_name"], $content);

		if ($handle = @fopen($file_name, "w")) {
			fwrite($handle, $content);
			fclose($handle);
		} else {
			die ("Cannot open file: $file_name");
		}
	}
}


// create fake classes
foreach ($enc_classes as $class) {
	$file_name = "$classes_dir/".$class["base_class_file"];
	$content = file_get_contents($file_name);

	$pathinfo = pathinfo($file_name);
	$enc_file_name = $pathinfo["dirname"]."/enc_".$pathinfo["basename"];

//	$decore_file_name = $file_name.".decore";
	$decore_file_name = $file_name;

	if ($handle = @fopen($enc_file_name, "w")) {
		fwrite($handle, $content);
		fclose($handle);
	} else {
		die ("Cannot create file: $enc_file_name");
	}


	if ($handle = @fopen($decore_file_name, "w")) {
		$template = get_decore_file_template();
		$class_declaration = "class ".$class["extends_class_name"]." extends ".$class["base_class_name"];

		$decore_content = str_replace("%CLASS_DECLARATION%", $class_declaration, $template);

		fwrite($handle, $decore_content);
		fclose($handle);
//		unlink($file_name);
	} else {
		die ("Cannot create file: $decore_file_name");
	}
}

exit(0);


function get_decore_file_template()
{
	$template = <<<EOT
<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

%CLASS_DECLARATION%
{
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
EOT;

	return $template;
}


function usage($obj = null) {
    if ($obj !== null) {
        print $obj->getMessage()."\n";
    }
    $usage =<<<EOT
Usage: enc_decorator.php [-h] [-d work directory]
Options:
    -d specify work directory
    -h, -?  this help/usage


EOT;
    print $usage;
    exit(1);
}


?>
