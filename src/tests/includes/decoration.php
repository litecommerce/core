<?php
//require_once "tests/classes/config.php";
require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "includes/functions.php";
require_once "includes/decoration.php";

class FunctionsTest extends PHPUnit_TestCase
{
    function FunctionsTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function test_func_define_class()
    {
        system("rm -Rf var/run/tests/*");
        global  $xlite_defined_classes, $xlite_class_deps, $xlite_class_files, $options, $xlite_class_decorators;
        $xlite_defined_classes = array();
        $xlite_class_deps = array('test_class1'=>'test_class2', 'test_class2'=>'test_class4');
        $xlite_class_files = array(
            'test_class1'=>'includes/test_class1.php',
            'test_class2'=>'includes/test_class2.php',
            'test_class5'=>'includes/test_class5.php',
            'test_class3'=>'includes/test_class3.php',
            'test_class4'=>'includes/test_class4.php');
        $xlite_class_decorators = array('test_class4' => array('test_class5','test_class3'));
        global $options;
        $options["decorator_details"]["compileDir"] = "var/run/";
        $this->assertEquals('test_class1__', func_define_class('test_class1', 'tests/'));
        $this->assertTrue(class_exists('test_class1__'));
        $this->assertEquals('test_class3__', func_define_class('test_class4', 'tests/'));
        $this->assertTrue(class_exists('test_class3__'));
        $this->assertEquals('test_class5__', get_parent_class('test_class3__'));
        $this->assertEquals('test_class4__', get_parent_class('test_class5__'));
        $this->assertEquals('test_class3__', get_parent_class('test_class2__'));
    }
}

$suite = new PHPUnit_TestSuite("FunctionsTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
