<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "compat/gmp.php";

class gmptest extends PHPUnit_TestCase
{

	function test_add()
	{
		$this->assertEquals(gmp_init("2411071108441"), gmp_add(gmp_init("12342345676"), gmp_init("2398728762765")));
	}

	function test_sub()
	{
		$r = gmp_sub(gmp_init("2411071108441"), gmp_init("2398728762765"));
		$this->assertEquals(gmp_init("12342345676"), $r);
	}

	function test_mul()
	{
		$this->assertEquals(gmp_init("42656943494124848694588"), gmp_mul(gmp_init("123412345346"), gmp_init("345645675678")));
	}
	function test_strval()
	{
		$n = "123234345";
		$this->assertEquals($n, gmp_strval(gmp_init($n)));
	}

	function test_gmp_shift_right()
	{
		$a = gmp_init(666666666);
		gmp_shift_right($a);
		$this->assertEquals(gmp_init(333333333), $a);
	}

	function test_div_qr()
	{
		list($q, $r) = gmp_div_qr(gmp_init(33), gmp_init(5));
		$this->assertEquals(gmp_init(6), $q);
		$this->assertEquals(gmp_init(3), $r);
		list($q, $r) = gmp_div_qr(gmp_init(333333333), gmp_init(555555));
		$this->assertEquals(gmp_init(600), $q);
		$this->assertEquals(gmp_init(333), $r);
		$this->assertEquals(array(gmp_init(), gmp_init(555555)), gmp_div_qr(gmp_init(555555), gmp_init(333333333)));
		list($q) = gmp_div_qr(gmp_init("2387528934659234659826348957629384756238976592836459823649578623984592387465872349586234985239845938459872345836495823984582374958234587239489327658349587634985623984756892375"), gmp_init("27272738563876458634785636860911991919109298299229982866666666722277726872872727272727271"));
		$this->assertEquals(gmp_init("87542691360727033566690145934730508565439019474333398257598186052340852732012232756747"), $q);
	}
    function test_1()
    {
        $n = gmp_init('0x46f4855da3070db86634e2b04f0ecd194409578cf15c80ffcec0f6b5552f7669');
        $v = gmp_init('0xd550038e1e0f4b960040f519b286cdbf2e5ce51d77f15080c1d6b208a5a46dff7');

    }

	/*	
		function test_and()
		{
		$this->assertEquals(array(123), gmp_and(array(123,123), array(255)));
		}

		function test_init()
		{
		$this->assertEquals(array(123), gmp_init(123));
		$this->assertEquals(array(), gmp_init(0));
		$this->assertEquals(array(255,255), gmp_init(65535));
		$this->assertEquals(array(0,0,0,0,2), gmp_init("8589934592"));
		$this->assertEquals(array(1,2), gmp_init("0x201"));
		}

		function test_div_int_qr()
		{
		$this->assertEquals(array(array(1,1),0), gmp_div_int_qr(array(2,2), 2));
		$i = gmp_init("12345678901234567890");
		list($q, $r) = gmp_div_int_qr($i, 123);
		$this->assertEquals(gmp_init("100371373180768844"), $q);
		$this->assertEquals(78, $r);
		}
	 */	
	function test_count_leading_zeros()
	{
		$this->assertEquals(GMP_LIMB_BITS-8, count_leading_zeros(255));
		$this->assertEquals(GMP_LIMB_BITS, count_leading_zeros(0));
		$this->assertEquals(GMP_LIMB_BITS-1, count_leading_zeros(1));
	}
	function test_gmp_gcdext()
	{
		$this->check_gcd("0xffffffff", "0xfffeffff");
		$this->check_gcd("1", "2");
		$this->check_gcd("1000", "2000");
		$this->check_gcd("1234", "234");
		$this->check_gcd("234", "1235");
		$t = time();
		$this->check_gcd("11111111111111111111111111111111111111111111111111111111111111111111111111111111", "3333333333333333333455555555555555555555555555555555555555555555555555555555555577777777777777777777777777777777777777777777777777777777777777777777777777777777");
		print " time=" . (time()-$t);

		$this->check_gcd("1112342342341111111111167867811111111111111111111111111111111111111111111111111111111111111", "33333333333333333334555555555555555555555555555555555555555555555555555555555555777777777777777777777777777777777777777777777777777777777777777777777777779998789767856977");
	}

	function check_gcd($a, $b)
	{
		$a = gmp_init($a);
		$b = gmp_init($b);
		list($g, $s) = gmp_gcdext($a, $b);
		$x = gmp_mul($a, $s);
		list($q, $r) = gmp_div_qr($x, $b);
		$this->assertTrue(gmp_cmp($g, $r) == 0);
	}
	/*	function test_modmul_int()
		{
		$this->assertEquals(gmp_init("11111111111111111111111111111092"), gmp_modmul_int(gmp_init("11111111111111111111111111111111"), 20, gmp_init("11111111111111111111111111111112")));
		$this->assertEquals(gmp_init("22222222222222222222220"), gmp_modmul_int(gmp_init("1111111111111111111111"), 20, gmp_init("11111111111111111111111111111112")));
		$this->assertEquals(gmp_init(222), gmp_modmul_int(gmp_init(123), 234, gmp_init(255)));
		}

		function test_mod()
		{
		$this->assertEquals(gmp_init("46198830440"), gmp_mod(gmp_init("111111111111111111111111111"), gmp_init("211111111111")));
		}

		function test_modmul()
		{
		$this->assertEquals(gmp_init(1), gmp_modmul(gmp_init("1111"), gmp_init("1111"), gmp_init("1112")));
		$this->assertEquals(gmp_init(1), gmp_modmul(gmp_init("11111111111111111111111111111111"), gmp_init("11111111111111111111111111111111"), gmp_init("11111111111111111111111111111112")));
		}*/

	function test_sqr()
	{
		$this->assertEquals(gmp_init("14051014878167100089369142290244"), gmp_sqr(gmp_init("3748468337623662")));
		$this->assertEquals(gmp_init("493827160493827160493827160493827160493827160493827160493827160483950617283950617283950617283950617283950617283950617283950617284"), gmp_sqr(gmp_init("22222222222222222222222222222222222222222222222222222222222222222")));
	}

	function test_gmp_powm()
	{
		$this->assertEquals(gmp_init(2400),(gmp_powm(gmp_init(1230), gmp_init(2340), gmp_init(3450))));
		$time = microtime();
		for ($i=0; $i<1; $i++) { // make 3 passes
			gmp_powm(gmp_init("12301111111111111111"), gmp_init("0xB5BDC3ED09B69B78B86E7434E29DC05B89B04A2633ABD3AF53F757A4D1FC92B"), gmp_init("0x6A9F09E291A44E9438CC14391F034B38489313CCB007649806CE7AF3F7C857518C5"));
			sleep(1);
		}
		list($a, $b) = explode(' ', microtime());
		list($a1, $b1) = explode(' ', $time);

		print "time = ".(($a-$a1+$b-$b1-3)/1)."\n";

	}
	
	function test_prob_prime()
	{
		$probes = array(100000001, 0, 100000003, 0, 100000005, 0, 100000007, 1, 100000009, 0, 100000011, 0, 100000013, 0, 100000015, 0, 100000017, 0, 100000019, 0, 100000021, 0, 100000023, 0, 100000025, 0, 100000027, 0, 100000029, 0, 100000031, 0, 100000033, 0, 100000035, 0, 100000037, 1, 100000039, 1, 100000041, 0, 100000043, 0, 100000045, 0, 100000047, 0, 100000049, 1, 100000051, 0, 100000053, 0, 100000055, 0, 100000057, 0, 100000059, 0, 100000061, 0, 100000063, 0, 100000065, 0, 100000067, 0, 100000069, 0, 100000071, 0, 100000073, 1, 100000075, 0, 100000077, 0, 100000079, 0, 100000081, 1, 100000083, 0, 100000085, 0, 100000087, 0, 100000089, 0, 100000091, 0, 100000093, 0, 100000095, 0, 100000097, 0, 100000099, 0, 100000101, 0, 100000103, 0, 100000105, 0, 100000107, 0, 100000109, 0);
		for ($i=0; $i<count($probes); $i+=2) {
			$this->assertEquals($probes[$i+1], gmp_prob_prime(gmp_init($probes[$i])));
		}
	}
}

$suite = new PHPUnit_TestSuite("gmptest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
