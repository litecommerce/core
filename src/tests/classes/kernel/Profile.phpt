<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ProfileTest extends PHPUnit_TestCase
{
    function ProfileTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }

    function test_clone()
    {
        $p = func_new("Profile",1);
        $p1 = $p->clone();
        $this->assertTrue($p1->get("profile_id") != $p->get("profile_id"));
        $this->assertTrue($p1->get("login") == $p->get("login"));
    }

	function test_import_row()
    {
        // import the first row
        $p = func_new("Profile");
        $p->config->set("Memberships.memberships", array());
        $options = array(
                "properties" => array(
                    "login" => "asd@dsa.dsa",
                    "billing_firstname" => "hi",
                    "membership" => "chlen"));
        $p->_import($options);
        $this->assertTrue($p->find("login='asd@dsa.dsa'"));
        $this->assertEquals("chlen", $p->get("membership"));
        $this->assertEquals(array("chlen"), $p->config->get("Memberships.memberships"));
        $options["properties"]["membership"] = "chlen1";
        $p = func_new("Profile");
        $p->_import($options);
        $this->assertTrue($p->find("login='asd@dsa.dsa'"));
        $this->assertEquals("chlen1", $p->get("membership"));
        $this->assertEquals(array("chlen", "chlen1"), $p->config->get("Memberships.memberships"));
        $p->delete();
    }
}


$suite = new PHPUnit_TestSuite("ProfileTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
