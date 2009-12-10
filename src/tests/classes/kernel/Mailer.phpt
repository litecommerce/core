<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class MailerTest extends PHPUnit_TestCase
{
    function MailerTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->mailer = func_new("Mailer");
    }

    function testmail()
    {
        $this->mailer->compose("bit-bucket@rrf.ru", "novik@rrf.ru", "test_mail");
        $this->mailer->send();
    }
}


$suite = new PHPUnit_TestSuite("MailerTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
