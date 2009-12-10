<html>
<body>
<form action="genkey.php" method="POST">
Bits: <input type="text" name="bits" size="10" value="256"><br>
Arbitrary text: <textarea name="randomText" cols="80" rows="5">
</textarea><br>
<input type="submit" value="Gen key">
</form>
<?
flush();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	set_time_limit(1000);
	ignore_user_abort(false);
	require_once "classes/kernel/RSA.php";
	$rsa = new RSA;
	$key = $rsa->genKey($_REQUEST["bits"], $_REQUEST["randomText"]);
?>
<br>
Encryption/sign key: <?=$rsa->encKeyToString($key)?><br>
Decryption/check key: <?=$rsa->decKeyToString($key)?><br>
Test encrypt/decrypt:
<?
$message = "123456789abcdef0";
$signature = $rsa->encryptMD5($key, $message);
$time = explode(' ', microtime());
$time = $time[0] + $time[1];
$msg1 = $rsa->decryptMD5($key, $signature);
$time1 = explode(' ', microtime());
$time1 = $time1[0] + $time1[1];
if ($rsa->checkMD5($key, $message, $msg1)) {
	print "OK";
} else {
	print "mismatch";
}
print " time = ". ($time1-$time);
}
?>
</body>
