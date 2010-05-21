<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*
* This file solves compatibility issues.
*
* $Id$
*
*/
/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

define ('GMP_LIMB_BITS', 14);
define ('GMP_MASK', (1<<GMP_LIMB_BITS) -1);
define ('GMP_BASE', 1<<GMP_LIMB_BITS);
define ('GMP_BASE_SQR', GMP_BASE*GMP_BASE);
define ('GMP_BASE_SQR_P1', (GMP_BASE+1)*GMP_BASE);
define ('PRIME_TEST_PASSES', 25);

function gmp_init($int = 0)
{
    if (is_string($int)) {
        if (substr($int, 0, 2) == '0x') {
            return gmp_init_str(substr($int, 2), 16);
        } else {
            return gmp_init_str($int, 10);
        }
    } else {
        $r = array();
        while($int>GMP_MASK) {
            $r[] = $int&GMP_MASK;
            $int = $int>>GMP_LIMB_BITS;
        }
        if ($int) {
            $r[] = $int;
        }
        return $r;
    }
}
function gmp_setbit(&$n, $index)
{
    $limb = (int)($index / GMP_LIMB_BITS);
    if ($limb>=count($n)) {
        MPN_NORMALIZE($n, $limb);
    }    
    $n[$limb] |= 1<<($index%GMP_LIMB_BITS);
}
function gmp_test(&$n, $index)
{
    $limb = (int)($index / GMP_LIMB_BITS);
    if ($limb>=count($n)) {
        return 0;
    }    
    return ($n[$limb]>>($index%GMP_LIMB_BITS)) & 1;
}
function gmp_clrbit(&$n, $index)
{
    $limb = (int)($index / GMP_LIMB_BITS);
    if ($limb >= count($n)) {
        return;
    }
    $n[$limb] &= ~(1<<($index%GMP_LIMB_BITS));
    gmp_strip_zeros($n);
}

function gmp_init_str($str, $base)
{
    $n = array();
    for ($i=0; $i<strlen($str); $i++) {
        $d = ord($str{$i});
        if ($d>=ord('0') && $d<=ord('9')) {
            $d -= ord('0');
        } else if ($d>=ord('a') && $d<=ord('z')) {
            $d = $d - ord('a') + 10;
        } else if ($d>=ord('A') && $d<=ord('Z')) {
            $d = $d - ord('A') + 10;
        }
        $n = gmp_mul_int($n, $base);
        $n = gmp_add($n, $d);
    }
    return $n;
}

function gmp_strval($n, $base = 10)
{
    $str = '';
    $base = gmp_init($base);
    while (count($n)) {
        list($n, $r) = gmp_div_qr($n, $base);
        $r = gmp_intval($r);
        if ($r < 10) {
            $digit = ord('0') + $r;
        } else {
            $digit = ord('A') + $r - 10;
        }
        $str = chr($digit) . $str;
    }
    return $str;
}

function gmp_intval($n)
{
    $int = 0;
    for ($i=0; $i<count($n); $i++) {
        $int = $int*GMP_BASE + $n[$i];
    }
    return $int;
}

function gmp_add($a, $b, $starti = 0)
{
    if (is_numeric($b)) {
        $b = gmp_init($b);
    }
    if (count($a) > count($b)) {
        $t = $a;
        $a = $b;
        $b = $t;
    }
    // assert count($a)<=count($b)
    $c = 0;
    for ($i=$starti; $i<count($b); $i++) {
        if ($i<count($a)) {
            $r = $b[$i] + $a[$i] + $c;
        } else {
            $r = $b[$i] + $c;
        }
        $b[$i] = $r & GMP_MASK;
        $c = $r >> GMP_LIMB_BITS;
    }
    if ($c) {
        array_push($b, $c);
    }
    return $b;
}

function gmp_cmp($a, $b)
{
/*    if (is_numeric($b)) {
        $b = gmp_init($b);
    }*/
    $ca = count($a);
    $cb = count($b);
    if ($ca < $cb) {
        return -1;
    } else if ($ca == $cb) {
        for ($i=$ca-1; $i>=0; $i--) {
            $s = $a[$i] - $b[$i];
            if ($s) {
                return $s;
            }
        }
        return 0; // equal
    } else {
        return 1;
    }
}

function gmp_sub($a, $b)
{
    if (count($a)<count($b) || count($a)==count($b) && $a[count($a)-1]<$b[count($b)-1]) {
        return false;
    }

    // assert count($a)>=count($b)
    $c = 0;
    for ($i=0; $i<count($a); $i++) {
        if ($i<count($b)) {
            $r = $a[$i] - $b[$i] - $c;
        } else {
            $r = $a[$i] - $c;
        }
        if ($r<0) {
            $r += GMP_BASE;
            $c = 1; // заём
        } else {
            $c = 0;
        }
        $a[$i] = $r;
    }
    if ($c) {
        return false;
    }
    gmp_strip_zeros($a);
    return $a;
}

function gmp_strip_zeros(&$a)
{
    $i=count($a)-1;
    if ( $i>=0 && $a[$i] ) return;
    while ($i >= 0 && $a[$i] == 0) {
        $i--;
    }
    if ($i<count($a)-1) {
        array_splice($a, $i+1);
    }
}

function gmp_mul($a, $b)
{
    $sc = count($a)+count($b);
    if (!$sc) {
        return array();
    }
    $r = array_fill(0, $sc, 0);
    for ($i=0; $i<count($b); $i++) {
//        $c = 0;
        $bi = $b[$i];
//        for ($j=0; $j<count($a); $j++) {
        foreach ($a as $j => $aj) {
            $r[$j+$i] += $aj*$bi;
/*
            $t = $r[$j+$i] + $a[$j]*$bi+$c;
            $r[$j+$i] = $t % GMP_BASE;
            $c = $t >> GMP_LIMB_BITS;
*/
        }
/*
        if ($c) {
            $r[count($a)+$i] += $c;
            if ($r[count($a)+$i]>GMP_BASE) {
                $r[count($a)+$i+1] = (int)($r[count($a)+$i]/GMP_BASE);
                $r[count($a)+$i] = $r[count($a)+$i] % GMP_BASE;
            }
        }
*/
    }
    // care about carry
    $c = 0;
    for ($i=0; $i<$sc; $i++) {
        $t = $r[$i] + $c;
        $r[$i] = (int)($t & GMP_MASK);
        $c = (int)($t / GMP_BASE);
    }
    gmp_strip_zeros($r);
    return $r;
}
function gmp_sqr($a)
{
    $ac = count($a);
    $sc = 2*$ac;
    if (!$sc) {
        return array();
    }
    $r = array_fill(0, $sc, 0);
    for ($i=0; $i<$ac; $i++) {
        $ai = $a[$i];
        // ai ^ 2
        $r[$i*2] += $ai*$ai;
        for ($j=$i+1; $j<$ac; $j++) {
            $r[$j+$i] += 2*$a[$j]*$ai;
        }
    }
    // care about carry
    $c = 0;
    for ($i=0; $i<$sc; $i++) {
        $t = $r[$i] + $c;
        $r[$i] = (int)($t & GMP_MASK);
        $c = (int)($t / GMP_BASE);
    }

    gmp_strip_zeros($r);
    return $r;
}

function gmp_mul_int($a, $int)
{
    if ($int == 0) {
        return array();
    }
    $result = array();
    $c = 0;
    for ($i=0; $i<count($a); $i++ ){
        $r = $a[$i] * $int + $c;
        $result[] = $r & GMP_MASK;
        $c = $r>>GMP_LIMB_BITS;
    }
    if ($c) {
        $result[] = $c;
    }
    return $result;
}

function gmp_rand($limbs)
{
    $result = array();
    while ($limbs--) {
        $result[] = rand() % GMP_BASE;
    }
    return $result;
}

function gmp_prob_prime($n)
{
    $primes = array(3,5,7,11,13,17,19,23,29,31,37,41,43);
    if (($n[0] % 2) == 0) {
        return false;
    }
    for ($i=0; $i<count($primes); $i++) {
        list($q,$r) = gmp_div_qr($n, gmp_init($primes[$i]));
        if (count($r) == 0) {
            return false;
        }
    }
    // mpz_millerrabin(n,reps)
    /* Perform a Fermat test.  */
    $nm1 = gmp_sub($n, gmp_init(1));
    $two = gmp_init(2);
    $nm2 = gmp_sub($n, $two);
    $x = gmp_init(210);
    $y = gmp_powm($x, $nm1, $n);
    if (count($y)!=1 || $y[0]!=1) {
        return false;
    }
    
    /* Find q and k, where q is odd and n = 1 + 2**k * q.  */
    $k = 0;
    while ($k<GMP_LIMB_BITS && !($nm1[0]&(1<<$k))){
        $k++;
    }
    $q = array();
    // $q = $nm1/2^k
    $c = 0;
    for ($i=count($nm1)-1; $i>=0; $i--) {
        $q[$i] = ($nm1[$i]>>$k) + $c;
        $c = $nm1[$i]<<(GMP_LIMB_BITS-$k);
    }
    gmp_strip_zeros($q);
    $is_prime = true;
    for ($pass=0; $pass<PRIME_TEST_PASSES && $is_prime; $pass++) {
        $x = gmp_rand(count($n), "");
        list($tempo, $x) = gmp_div_qr($x, $nm2);
        $x = gmp_add($x, $two); // assert $x>1
        if (gmp_cmp($y, gmp_init(1)) == 0 || gmp_cmp($y, $nm1) == 0) {
            $is_prime = true;
        } else {
            
            $is_prime = false;
            for ($i = 1; $i < $k; $i++) {
                // mpz_powm_ui (y, y, 2L, n);
                $y = gmp_mul($y, $y);
                list($tempo, $y) = gmp_div_qr($y, $n);
                if (gmp_cmp($y, $nm1) == 0) {
                    $is_prime = true;
                    break;
                } else if (count($y)==1 && $y[0]==1) {
                    break;
                }
            }
        }
    }
    return $is_prime;
}

function gmp_div_qr($a, $b)
{
    $result = array();
    $j = 0;
    while(gmp_cmp($a, $b)>=0){
        array_unshift($b, 0);
        $j++;
    }
    $k = count($b)-1;
    $b0 = ($b[$k]<<GMP_LIMB_BITS) + gmp_limb($b, $k-1);
    $bnq = (int)(GMP_BASE_SQR/$b0);
    $bnr = GMP_BASE_SQR % $b0;
    $ac = count($a);
    for ($i=0; $i<=$j; $i++) {
        $k = count($b)-1;
        $ca = count($a);
        if ($ca > $k+1) {
            if ($k) {
                $qq = (int)((($a[$k+1]*GMP_BASE + $a[$k])*GMP_BASE + $a[$k-1])/$b0);
            } else {
                $qq = (int)((($a[$k+1]*GMP_BASE + $a[$k])*GMP_BASE)/$b0);
            }
        } else if ($ca > $k) {
            if ($k) {
                $qq = (int)(($a[$k]*GMP_BASE + $a[$k-1])/$b0);
            } else {
                $qq = (int)($a[$k]*GMP_BASE/$b0);
            }
        } else if ($ca > $k-1) {
            if ($k) {
                $qq = (int)($a[$k-1]/$b0);
            } else {
                $qq = 0;
            }
        } else {
            $qq = 0;
        }

        // assertion
//        $rr = $rr % $b0;
//        if ($qq*$b0+$rr != $a2*GMP_BASE_SQR+$a0) {
//            $msg = "Assertion: " . ($qq*$b0+$rr) . "!=" . ($a2*GMP_BASE_SQR+$a0);
//            die ($msg);
//        }
//        if ($qq>=GMP_BASE || $qq<0) {
//            print_r($a); print_r($b);
//            die("qq=$qq"); 
//        }
        
       
        // substract $a -= $b*$qq
        $c = 0; // заём
        $cb = count($b);
        $cm = min($cb, $ca);
//        gmp_check_num($a, "before ");
        for ($k = max(0,$j-$i-1); $k<$cm; $k++) {
            $ak = $a[$k] - $b[$k]*$qq + $c;
            $c = $ak >> GMP_LIMB_BITS;
            $a[$k] = $ak & GMP_MASK;
        }
        for (; $k<$ca && $c; $k++) {
            $ak = $a[$k] + $c;
            $c = $ak >> GMP_LIMB_BITS;
            $a[$k] = $ak & GMP_MASK;
        }
//        gmp_check_num($a, "after qq=$qq b0=$b0");
        if ($c == -1) {
            // rare case
            $c1 = 0;
            $qq--;
            for ($k = max(0,$j-$i-1); $k<count($a); $k++) {
                $a[$k] += gmp_limb($b, $k) + $c1;
                $c1 = $a[$k] >> GMP_LIMB_BITS;
                $a[$k] = $a[$k] & GMP_MASK;
            }
        } else if ($c){
            die(" c=$c qq=$qq, a2=$a2, a0=$a0, b0=$b0\n");
        }
        if ($qq || count($result)) {
            array_unshift($result, $qq);
        }
 
//        gmp_strip_zeros($a);
        
        for ($s = $ca-1; $s>=-1; $s--) {
            if ($s<0 || $a[$s]) {
                if ($s<$ca-1) {
                    array_splice($a, $s+1);
                }
                break;
            }
        }
        
        array_shift($b);
    }
    return array($result, $a);
}

function gmp_check_num($n, $msg = '')
{
    for ($i=0; $i<count($n); $i++)
    {
        if ($n[$i]>=GMP_BASE || $n[$i]<0) {
            print_r($n); die($msg);
        }
    }
}
function gmp_limb(&$n, $ind)
{
    return (count($n)>$ind && $ind>=0)? $n[$ind] : 0;
}

function gmp_shift_right(&$n)
{
    $c = 0;
    for ($i=count($n)-1; $i>=0; $i--) {
        $a = $n[$i];
        $n[$i] = ($a>>1) + ($c<<(GMP_LIMB_BITS-1));
        $c = $a&1;
    }
    gmp_strip_zeros($n);
}

function gmp_and($a, $b)
{
    $r = array();
    for ($i=0; $i<min(count($a), count($b)); $i++) {
        $r[] = $a[$i]&$b[$i];
    }
    gmp_strip_zeros($r);
    return $r;
}

function MPN_NORMALIZE(&$n, $size)
{
    while (count($n)<$size) {
        $n[] = 0;
    }
}

function count_leading_zeros($a)
{
    $i=GMP_LIMB_BITS-1;
    while($i>=0) {
        if ($a & (1<<$i)) {
            break;
        }
        $i--;
    }
    return GMP_LIMB_BITS-$i-1;
}

function gmp_gcdext_n($u, $v)
{
    $sign = 1;
    $s0 = gmp_init(1);
    $s1 = gmp_init(0);
    while (count($v) > 0) {
        $size = max(count($v), count($u));
        MPN_NORMALIZE ($v, $size);
        MPN_NORMALIZE ($u, $size);
        /* Make UH be the most significant limb of U, and make VH be
           corresponding bits from V.  */
        $uh = $u[$size-1];
        $vh = $v[$size-1];
        $cnt = min(count_leading_zeros ($uh), count_leading_zeros($vh));
        if ($cnt != 0) {
            if ($size>=2) {
                if (!isset($u[$size - 2])) {
                    print_r($u); die();
                }    
                $uh = ($uh << $cnt) | ($u[$size - 2] >> (GMP_LIMB_BITS - $cnt));
                $vh = ($vh << $cnt) | ($v[$size - 2] >> (GMP_LIMB_BITS - $cnt));
            }
        }
        $A = 1;
        $B = 0;
        $C = 0;
        $D = 1;
        $asign = false;
        for (;;) {
            if ($vh - $C == 0 || $vh + $D == 0) break;
            $q = (int)(($uh + $A) / ($vh - $C));
            if ($q != (int)(($uh - $B) / ($vh + $D))) break;
            $T = $A + $q * $C;
            $A = $C;
            $C = $T;
            $T = $B + $q * $D;
            $B = $D;
            $D = $T;
            $T = $uh - $q * $vh;
            $uh = $vh;
            $vh = $T;

            $asign = !$asign;

            if ($vh - $D == 0) break;
            $q = (int)(($uh - $A) / ($vh + $C));
            if ($q != (int)(($uh + $B) / ($vh - $D))) break;

            $T = $A + $q * $C;
            $T = $A + $q * $C;
            $A = $C;
            $C = $T;
            $T = $B + $q * $D;
            $B = $D;
            $D = $T;
            $T = $uh - $q * $vh;
            $uh = $vh;
            $vh = $T;

            $asign = !$asign;
        }
        if ($asign)
            $sign = -$sign;
        gmp_strip_zeros($u);
        gmp_strip_zeros($v);
        if ($B == 0) {
            list($w, $u) = gmp_div_qr($u, $v); // u = u mod v
            $t = $s0;
            $t = gmp_add($s0, gmp_mul($w, $s1));
            //MP_PTR_SWAP (s0p, s1p);
            $temp = $s0; $s0 = $s1; $s1 = $temp;
            //MP_PTR_SWAP (s1p, tp);
            $s1 = $t;
            //MP_PTR_SWAP (up, vp);
            $temp = $u; $u = $v; $v = $temp;
        } else {
            /* T = U*A + V*B
               W = U*C + V*D
               U = T
               V = W     */
            if ($asign) {        
                $t = gmp_sub(gmp_mul_int($v, $B), gmp_mul_int($u, $A));
                if ($t===false) { die("t==false"); }
                $w = gmp_sub(gmp_mul_int($u, $C), gmp_mul_int($v, $D));
                if ($w===false) { die("w==false"); }
            } else {
                $t = gmp_sub(gmp_mul_int($u, $A), gmp_mul_int($v, $B));
                if ($t===false) { die("t==false"); }
                $w = gmp_sub(gmp_mul_int($v, $D), gmp_mul_int($u, $C));
                if ($w===false) { die("w==false"); }
            }
            $u = $t;
            $v = $w;
            /* Compute new s0, s1 */
            $t = gmp_add(gmp_mul_int($s0, $A), gmp_mul_int($s1, $B));
            $w = gmp_add(gmp_mul_int($s1, $D), gmp_mul_int($s0, $C));
            $s0 = $t;
            $s1 = $w;
        }
    }

    $g = $u;
    return array($g, $s0, $s1, $sign);
}
function gmp_gcdext($a, $b)
{
    list($g, $s0, $s1) = gmp_gcdext_n($a, $b);
    $x = gmp_mul($s0, $a);
    list($q, $r) = gmp_div_qr($x, $b);
    if (gmp_cmp($g, $r)) {
        $s0 = gmp_sub($s1, $s0);
    }
    return array($g, $s0);
}

/*
function gmp_powm($a, $b, $n)
{
    $result = array(1);
    for ($i=count($b)-1; $i>=0; $i--) {
        for ($j=GMP_LIMB_BITS-1; $j>=0; $j--) {
            $result = gmp_mul($result, $result);
            if ($b[$i] & (1<<$j)) { // test a bit
                $result = gmp_mul($result, $a);
            }
            list($q, $result) = gmp_div_qr($result, $n);
//            array_splice($result, count($n));
        }
    }
    return $result;
}
*/
function gmp_powm($a, $b, $n)
{
    $result = array(1);
    $table = array($result, $a);
    for ($k=2; $k<16; $k++) {
        $table[] = gmp_mod(gmp_mul($table[count($table)-1], $a), $n);
    }
    // collect GMP_BASE ^ $i mod $n
//    $bases = array();
//    $bi = array(1);
//    for ($i=0; $i<=count($n)*8; $i++) {
//        $bases[$i] = $bi;
//        array_unshift($bi, 0);
//        list($q, $bi) = gmp_div_qr($bi, $n);
//    }
    for ($i=(count($b)*GMP_LIMB_BITS-1) & (~3); $i>=0; $i-=4) {
        $result = gmp_mod(gmp_sqr($result), $n);
        $result = gmp_mod(gmp_sqr($result), $n);
        $result = gmp_mod(gmp_sqr($result), $n);
        $result = gmp_mod(gmp_sqr($result), $n);
        
        $limb = (int)($i / GMP_LIMB_BITS);
        $offs = $i % GMP_LIMB_BITS;
        $bits = $b[$limb];
        if ($limb<count($b)-1) {
            $bits |= $b[$limb+1] << GMP_LIMB_BITS;
        }
        $bits4 = ($bits >> $offs) & 15;
        $result = gmp_mod(gmp_mul($result, $table[$bits4]), $n);
    }
    return $result;
}

function gmp_mod($a, &$n)
{
    list($q, $m) = gmp_div_qr($a, $n);
    return $m;
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
