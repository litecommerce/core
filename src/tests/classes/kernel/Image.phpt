<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ImageTest extends PHPUnit_TestCase
{
    var $cat;

    function ImageTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->cat = func_new("Category",3);
    }

    function tearDown()
    {
        unset($this->category);
    }

    function testImage()
    {
        $image =& $this->cat->getImage();
        $this->assertEquals($image->get("type"), "image/gif");
        $this->assertEquals($image->get("source"), "D");
    }
    function testCopyTo()
    {
        $image =& $this->cat->getImage();
        $newImg =& $image->copyTo(-1);
        $m = "UPDATE xlite_categories SET image='GIF8";
        $this->assertEquals($m, substr($newImg->sql, 0, strlen($m)));
        $this->assertTrue(strstr($newImg->sql, "category_id='-1'"));
    }
}

$suite = new PHPUnit_TestSuite("ImageTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
