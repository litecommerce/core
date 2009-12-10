<?
//include "includes/script.php";
ini_set("include_path", "lib");
include "tests/classes/simulator.phpt";
$sim = new Simulator($argv[1]);
$sim->stressTest();
//$sim->measure();

function getmicrotime() // {{{
{
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec);
} // }}}

?>
