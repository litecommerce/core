<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ArrayChunkTest extends PHPUnit_TestCase
{
    function ArrayChunkTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

	function test_chunk()
	{
		$func = 'array_chunk1';
		$array = array(1,2,3,4,5,6);
		$this->assertEquals(array(array(1,2,3), array(4,5,6)), $func($array,3));
		$this->assertEquals(array(array(1,2,3,4,5), array(6)), $func($array,5));
		$array = array(1);
		$this->assertEquals(array(array(1)), $func($array,5));
		$array = array();
		$this->assertEquals(array(), $func($array,5));
	}

}


function array_chunk1($a,$l) {
	$result = array();
	for ($i=0; $i<count($a); $i+=$l) {
		$result[] = array_slice($a, $i, $l);
	}
	return $result;
}

$suite = new PHPUnit_TestSuite("ArrayChunkTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
