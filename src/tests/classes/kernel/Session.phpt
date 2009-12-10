<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class SessionTest extends PHPUnit_TestCase
{
    var $session;
    var $id;

    function SessionTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function testSetID()
    {
        global $xlite;
        $xlite->session->setID("abc123");
        $this->assertEquals("abc123", $xlite->session->getID());
    }

    function testSetName()
    {
        global $xlite;
        $xlite->session->set("name", "NAME");
        $this->assertEquals("NAME", $xlite->session->get("name"));
    }

    function testSetVar()
    {
        global $xlite;
        $name = "JOHN";
        $res = $xlite->session->set("NAME", $name);
        $result = $xlite->session->get("NAME");
        $this->assertEquals($name, $result);
    }

    function testWriteClose()
    {
        $session = func_new("Session_sql");
        $session->setID("abc123");
        $session->set("var", "val");
        if (!$session->isExists()) {
            $session->create();
        } else {
            $session->writeClose();
        }
        $session = func_new("Session_sql");
        $session->setID("abc123");
        $session->_fetchData();
        $this->assertEquals("val", $session->get("var"));
    }
}


$suite = new PHPUnit_TestSuite("SessionTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
