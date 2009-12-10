<?php
include "includes/functions.php";
$error = "";
$result = func_parse_csv("\"\"\"Quoted string\"\"\";unquoted", ";", "\"", $error);
print_r($result);
print($error);
$result = func_parse_csv(";\"\"\"Quoted string\"\"\";unquoted;", ";", "\"", $error);
print_r($result);
print($error);
$result = func_parse_csv("", ";", "\"", $error);
print_r($result);
print($error);
$result = func_parse_csv("\"", ";", "", $error);
print_r($result);
print($error);
$line = '"123","1234","Books","1234","","","","123.00","","1.00","0","1"'."\n";
$result = func_parse_csv($line, ",", '"', $error);
print_r($result);
print($error);
print func_construct_csv(array("\"Quoted\"", "unquoted\n\r"), ";", "\"")."\n";
?>
