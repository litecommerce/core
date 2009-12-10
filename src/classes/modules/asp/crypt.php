<?php
# Simple crypt function. Returns an encrypted version of argument.
# Does not matter what type of info you encrypt, the function will return
# a string of ASCII chars representing the encrypted version of argument.
# Note: text_crypt returns string, which length is 2 time larger
#

function text_crypt_symbol($c)
{
# $c is ASCII code of symbol. returns 2-letter text-encoded version of symbol

        return chr(101 + ($c & 240) / 16).chr(101 + ($c & 15));
} 

function text_crypt($s)
{
    if ($s == "")
        return $s;
    $enc = rand(1,255); # generate random salt.
    $result = text_crypt_symbol($enc); # include salt in the result;
    $enc ^= 105;
    for ($i = 0; $i < strlen($s); $i++) {
        $r = ord(substr($s, $i, 1)) ^ ($enc+=11);
        $result .= text_crypt_symbol($r);
    }
    return $result;
}

function text_decrypt($s)
{
	$enc = 105 ^ ((ord(substr($s, 0, 1)) - 101)*16 + ord(substr($s, 1, 1)) - 101);
	$result = '';
	for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
		$result .= chr((((ord(substr($s, $i, 1)) - 101)*16 + ord(substr($s, $i+1, 1)) - 101) ^ $enc+=11)&0xff);
	}

	return $result;
}

function func_generate_crc($s) 
{
	return strtolower(bin2hex(base64_encode(md5(serialize($s)))));
}

?>
