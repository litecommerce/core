<?php
require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "kernel/RSA.php";

class RSATest extends PHPUnit_TestCase
{
    function RSATest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->rsa = new RSA;
    }

    function tearDown()
    {
    }

	function test_random()
	{
		print "random numbers are " ;
		for ($i=0; $i<20; $i++) {
			print gmp_strval($this->rsa->random(128, ""), 16) . " ";
		}
		print "\n";
	}

	function test_genPrime()
	{
		print "prime random numbers are " ;
		for ($i=0; $i<10; $i++) {
			$prime = $this->rsa->genPrime(31, "");
			print " " . gmp_strval($prime) . " ";
			$this->assertTrue(gmp_prob_prime($prime));
		}	
	}
	
	function test_genKey()
	{
		$key = $this->rsa->genKey(256, "123");
		// try to encrypt/decrypt a number
		print "n=" . gmp_strval($key['n']). ", v=".gmp_strval($key['v']).", e=".gmp_strval($key['e'])."\n";
		$m = gmp_init("12312");
		$enc = gmp_powm($m, $key['v'], $key['n']);
		$dec = gmp_powm($enc, $key['e'], $key['n']);
		$this->assertFalse(gmp_cmp($m, $dec));
	}

	function test_checkMD5()
	{
		$message = "Private text";
	
		$key = array(
	'n' => gmp_init("21755637000606091302023443207875611949768147953246362732059943438314620036999"), 
	'v' => gmp_init("4802562198265705599278082880107476202923690401675582860858110784828707937233"),
	'e' => gmp_init("14792441753716871718498247685497522798074467858191138040881696424189548619105"));
		
		$signature = $this->rsa->encryptMD5($key, md5($message));
		$decr = $this->rsa->decryptMD5($key, $signature);
		$this->assertTrue($this->rsa->checkMD5($key, md5($message), $decr));
		
		$key = array(
			'n' => gmp_init("0xB5BDC3ED09B69B78B86E7434E29DC05B89B04A2633ABD3AF53F757A4D1FC92B"),
			'e' =>  gmp_init("0x70A923855A14194D9CFB959732556F3965EEACA3E06D660E1F097C7A62D115D"),
			'v' => gmp_init("0x6A9F09E291A44E9438CC14391F034B38489313CCB007649806CE7AF3F7C857518C5"));
		$signature = $this->rsa->encryptMD5($key, md5($message));
		$decr = $this->rsa->decryptMD5($key, $signature);
		$this->assertTrue($this->rsa->checkMD5($key, md5($message), $decr));
	}
}


$suite = new PHPUnit_TestSuite("RSATest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
