#!/usr/uniq/bin/php
<?
include "includes/script.php";
include "tests/classes/simulator.phpt";
system("./restoredb");
$sim = new Simulator;
if (isset($argv[0]) && $argv[1] == 'module') {
    $sim->moduleTest($argv[2]);
} else {
    $sim->functionTest();
}
?>
