#!/usr/local/bin/php -Cq
<?php

/**

NEW:

class:file:ext1


OLD:

1. array xlite_class_files
    "class" => "file_name"  -> lowercase

2. array xlite_class_deps
    "class" => "ext_class" -> lowercase

3. array xlite_class_module_id
    "class" => "module_id"  -> lowercase


*/

$includes  = "./classes" . PATH_SEPARATOR;
$includes .= "./lib" . PATH_SEPARATOR;
ini_set("include_path", $includes . ".");

require_once "System.php";

$dir = "classes";
$list = "classes.lst";

print "Building classes list..\n";
chdir($dir) or die("Unable to chdir($dir)!\n");

$struct = System::_multipleToStruct(".");
if (isset($argv[1])) {
    $module = $argv[1];
} else {
    $module = "";
}
foreach($struct['files'] as $file) {
    $file = strtr($file, '\\', '/');
    // strip leading ./
    $fileName = $file;
    if (substr($file, 0, 2) == "./") {
        $fileName = substr($file, 2, strlen($file));
    }

    if (substr($fileName, -4) != ".php") continue;

    if ($module) {
        if (substr($fileName, 0, 7) == "modules") {
            list($m, $name) = split("/", $fileName);
            if ($name == $module) {
                grep($fileName, "modules/$name/$list");
            }
        }
    } else {
        if (substr($fileName, 0, 7) == "modules") {
            // get module name
            list($m, $name) = split("/", $fileName);
            grep($fileName, "modules/$name/$list");
        } else {
            grep($fileName, $list);
        }
    }
}

print "DONE\n";

function grep($file, $list) {
    static $files;
    if (!isset($files)) {
        $files = array();
    }
 
    $debug = false;

    // search ..
    $classes = array();
    $content = file($file);
    foreach ($content as $line) {
        chop($line);
        $matches = array(null, null, null);
        if (preg_match("/^class (\w+)/", $line, $matches)) {
            if ($debug) print "Class: $matches[1]\n";
            $classes[$matches[1]] = array();
            foreach ($content as $ext) {
                chop($ext);
                if (preg_match("/^class \w+ extends\s+(\w+)/", $ext, $m)) {
                    if ($debug) print "> Ext: $m[1]\n";
                    $classes[$matches[1]][] = $m[1];
                }
            }
        }
    }

    // save results
    if (!empty($classes)) {
        // remove old list file
        if (!isset($files[$list])) {
            @unlink($list);
            $files[$list] = "OK";
        }
        $fh = fopen($list, "a+") or die("fopen($list) failed!\n");
        foreach (array_keys($classes) as $class) {
            $str = strtolower($class) . ":" . $file . ":" . strtolower(join(",", $classes[$class])) . "\n";;
            fwrite($fh, $str);
        }
        fclose($fh);
    }
}

?>
