<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ObjectTest extends PHPUnit_TestCase
{
    function ObjectTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->object = func_new('Object');
        $this->object->subObject = func_new('Object');
    }
    function testSetGet()
    {
        $this->object->set('asd', 'dsa');
        $this->assertEquals('dsa', $this->object->get('asd'));
        $this->object->set('subObject.asd', 'dsa');
        $this->assertEquals('dsa', $this->object->get('subObject.asd'));
    }
    function testCall()
    {
        $this->object->call('subObject.setProperties', array('a'=>'aa', 'b'=>'bb'));
        $this->assertEquals('aa', $this->object->get('subObject.a'));
        $this->assertEquals('bb', $this->object->get('subObject.b'));
        $this->object->call('setProperties', array('a'=>'aa', 'b'=>'bb'));
        $this->assertEquals('aa', $this->object->get('a'));
        $this->assertEquals('bb', $this->object->get('b'));

    }
}


$suite = new PHPUnit_TestSuite('ObjectTest');
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
