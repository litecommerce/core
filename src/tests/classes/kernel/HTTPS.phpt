<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class HTTPSTest extends PHPUnit_TestCase
{
    function HTTPSTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->https =& func_new("HTTPS");
		$this->https->url = "https://rrf.ru/~ndv/test.php";
    }

    function tearDown()
    {
    }

	function testRequestLibCurl()
	{
		if (extension_loaded("curl")) {
			global $config;
			$config->Security->httpsClient = "libcurl";
			$this->request();

			$this->https->url = "https://rrf.ru/~ndv/test.php";
			$this->assertEquals(HTTPS_ERROR, $this->https->request());
			$this->assertEquals("Unsupported protocol: hs", $this->https->error);
			
		}
	}	

	function testRequestCurl()
	{
		global $config;
		$config->Security->httpsClient = "curl";
		$this->request();
	}	

	function testRequestOpenSSL()
	{
		global $config;
		$config->Security->httpsClient = "openssl";
		$this->request();
	}	

	function request()
	{
		$this->https->data = array("a"=>"1", "b"=>"2");
		$this->assertEquals(HTTPS_SUCCESS, $this->https->request());
		$this->assertEquals(3, $this->https->response);
		$this->assertEquals("", $this->https->error);
	}
}


$suite = new PHPUnit_TestSuite("HTTPSTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
