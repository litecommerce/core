<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

if (!extension_loaded('gmp')) {
   require_once LC_ROOT_DIR . 'compat' . LC_DS . 'gmp.php';
}

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class RSA {
    
    function random($bits, $randomText)
    {
        $randomText = rand() . microtime() . $randomText;
        $rand = gmp_init(0);
        $base = gmp_init(333);
        if (!extension_loaded('gmp')) {
            $nn = (int)(($bits-1)/GMP_LIMB_BITS)+1;
            $mask = (1<<($nn*GMP_LIMB_BITS-$bits)) - 1;
        } else {
            $pow2 = gmp_pow(gmp_init(2), $bits);
        }
        for ($i=0; $i < strlen($randomText); $i++) {
            $byte = ord($randomText{$i});
            $rand = gmp_add(gmp_mul($rand, $base), $byte);
            if (!extension_loaded('gmp')) {
                array_splice($rand, $nn);
                if (isset($rand[$nn-1])) {
                    $rand[$nn-1] &= $mask;
                }
            } else {
                $rand = gmp_mod($rand, $pow2);
            }
        }
        $rand = gmp_mul($rand, gmp_init('34737725553190012365348768633636637738837376363636637638178646564116161616173738645646467465'));
        if (!extension_loaded('gmp')) {
            array_splice($rand, $nn);
            if (isset($rand[$nn-1])) {
                $rand[$nn-1] &= $mask;
            }
            gmp_strip_zeros($rand);
        } else {
            $rand = gmp_mod($rand, $pow2);
        }

        return $rand;
    }
    
    /**
    * Generate a large $bits-sized random prime number.
    * Strictly speaking, the return value is not nesseccary prime.
    */
    function genPrime($bits, $randomText)
    {
        $n = $this->random($bits, $randomText);
        gmp_setbit ($n, 0);
        $two = gmp_init(2);
        while (!gmp_prob_prime($n)) {
            $n = gmp_add($n, $two);
        }
        return $n;
    }
    /**
    * @return An associative array containing n, v and e. 
    * Encryption key is (n, v), decryption key is (n, e).
    */
    function genKey($bits, $randomText)
    {
        $one = gmp_init(1);
        $p = $this->genPrime($bits / 2, $randomText);
        $q = $this->genPrime($bits / 2, $randomText);
        $n = gmp_mul($p, $q);
        // $phi = ($p - 1) * ($q - 1);
        $phi = gmp_mul(gmp_sub($p, $one), gmp_sub($q, $one));
        do {
            $v = $this->random($bits, $randomText);
            if (!extension_loaded('gmp')) {
                list ($gcd, $e) = gmp_gcdext($v, $phi);
            } else {
                $result = gmp_gcdext($v, $phi);
                $gcd = $result['g'];
                $e = $result['s'];
            }
        } while (gmp_cmp($gcd, $one));
        //print "p=".gmp_strval($p).",q=".gmp_strval($q).",phi=".gmp_strval($phi)."\n";
        if (extension_loaded('gmp')) {
            // may be negative
            if (gmp_sign($e)<0) {
                $e = gmp_add($e, $phi);
            }
        }
        return array('n' => $n, 'v' => $v, 'e' => $e);
    }
    function encKeyToString($key)
    {
        $n = gmp_strval($key['n'], 16);
        $e = gmp_strval($key['e'], 16);
        return "$n $e";
    }

    function decKeyToString($key)
    {
        $n = gmp_strval($key['n'], 16);
        $v = gmp_strval($key['v'], 16);
        return "$n $v";
    }
    function encKeyFromString($str)
    {
        list($n, $e) = explode(' ', $str);
        return array('n' => $this->gmp_init16($n), 'e' => $this->gmp_init16($e));
    }
    function decKeyFromString($str)
    {
        list($n, $v) = explode(' ', $str);
        return array('n' => $this->gmp_init16($n), 'v' => $this->gmp_init16($v));
    }

    function encryptMD5($key, $md5)
    {
        $md5 = $this->gmp_init16($md5);
        return gmp_strval(gmp_powm($md5, $key['e'], $key['n']), 16);
    }

    function decryptMD5($key, $signature)
    {
        $signature = $this->gmp_init16($signature);
        return gmp_strval(gmp_powm($signature, $key['v'], $key['n']), 16);
    }
    function checkMD5($key, $md5, $decrypted)
    {
        $md5 = $this->gmp_init16($md5);
        list($tempo, $md5) = gmp_div_qr($md5, $key['n']);
        return !gmp_cmp($this->gmp_init16($decrypted), $md5);
    }

    function gmp_init16($string) 
    {
        $val = gmp_init("0x$string");
        if ($val === false) {
            $val = gmp_init($string, 16);
        }
        return $val;
    }

}
