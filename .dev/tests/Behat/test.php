<?php

class SomeClass{
 public static $variable;

 public function __construct($var){
	self::$variable = $var;
	}
}

$cl = new SomeClass(12);
var_dump($cl::$variable);

$other = new SomeClass("adfasdf");

var_dump($cl::$variable);

