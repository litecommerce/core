<?php

// test targets
$targets = array("catalog", "cart", "profile", "checkout");

// prase commandline
$include_path = ini_get("include_path");
ini_set("include_path", ".:./lib");
require_once "PEAR.php";
require_once "Console/Getopt.php";
ini_set("include_path", $include_path);

// get list of targets
$argv = Console_Getopt::readPHPArgv();
if (isset($argv[1]) && sizeof($argv[1])) {
    $targets = explode(',', $argv[1]);
}

// suppress storefront display
$nodisplay = true;

// testing asserts
$asserts = array();

// send initial request
include "cart.php";

// check that session started
isset($session) or die("FAILED: session not started\n");

// get current dir
$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;

// configure assert
assert_options (ASSERT_ACTIVE, 1);
assert_options (ASSERT_WARNING, 0);
assert_options (ASSERT_CALLBACK, 'assertHandler');

foreach ($targets as $target) {
    echo "\ntest $target ";
    $d = $dir . $target . DIRECTORY_SEPARATOR;
    if (is_dir($d) && ($dh = opendir($d))) {
        while (($file = readdir($dh)) !== false) {
            $file = $d . $file;
            if (filetype($file) === "file" && substr($file, -4) == ".php") {
                // run test case
                include $file;
            }    
        }
        closedir($dh);
    } else {
        say("WARNING: unable to open directory $d");
    }
}

// cleanup

// delete session
$session->destroy();

// dump diagnostic messages
say("\n\n");
if (count($asserts)) {
    say("FAILED!\n");
    say(implode("\n", $asserts)."\n");
} else {
    say("   OOO     OK   OK");
    say(" OK   OK   OK  OK");
    say("OK     OK  OKOK");
    say(" OK   OK   OK  OK");
    say("   OOO     OK   OK");
    say("\n\n");
}

// ******************** functions  *******************

function assertHandler($file, $line, $code) {
    global $asserts;
    
    echo "x";
    //say("Assert at $file, $line");
    $asserts[] = "error at $file, $line";
}

function sendRequest($target = null, $action = null, $get = null, $post = null, $cookie = null) {
    global $xlite;
    global $session;

    echo ".";
    // reset request arrays
    $_GET     = is_null($get)    ? array() : $get;
    $_POST    = is_null($post)   ? array() : $post;
    $_COOKIE  = is_null($cookie) ? array() : $cookie;
    $_REQUEST = array_merge($_GET, $_POST, $_COOKIE);

    // save session cookie for the further calls
    $_REQUEST[$session->getName()] = $session->getID();
    // set target/action pair
    $_REQUEST["target"] = $target;
    $_REQUEST["action"] = $action;
    // run lite
    $xlite->init(new View_Main());
    $xlite->run();
    $xlite->done();
    echo ".";
}

// says something to somebody..
function say($what) {
    echo $what . "\n";
}
?>
