<?php
include "includes/functions.php";
include "compat/compat.php";

function license_fingerprint($license)
{
	return md5($license["license_no"].$license["domain"].$license["name"].$license["expire"].$license["type"].$license["issue_date"].$license["modules"].$license["access_key"].$license["version"] . ($license["N"] ? $license["N"] : ''));
}

# Simple crypt function. Returns an encrypted version of argument.
# Does not matter what type of info you encrypt, the function will return
# a string of ASCII chars representing the encrypted version of argument.
# Note: text_crypt returns string, which length is 2 time larger
#
function text_crypt_symbol($c) {
# $c is ASCII code of symbol. returns 2-letter text-encoded version of symbol

        return chr(101 + ($c & 240) / 16).chr(101 + ($c & 15));
} 

function text_crypt($s) {
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

/**
* Creates and encrypts a license file.
* @param string $license_no  	The license unique nomber.
* @param string $domain  		Domain name. Subdomains are allowed, like www.$domain. If empty, this is a trial license.
* @param int $expire			Expiration date. Zero (0) means never.
* @param string $type			License type, either 'basic' or 'development'.
* @param int $issue_date		Date when the license was issued.
* @param string $access_key		A unique private key for forcing license [de]activation.
* @param Array modules			Array of licensed module names and their expiration dates. Each element is in the form of array("name"=>name, "expiration"=>date or 0 (never)).
*/
function create_license($license_no, $domain, $name = "", $expire, $type, $issue_date, $access_key, $modules = array(), $version, $N, $rsa_key, $filename = "LICENSE", $demo=false)
{
	if ($demo) {
		$license_no = "litecommerce-demo";
		$domain = "demo.litecommerce.com,www.litecommerce.com,litecommerce.com";
//		$license_no = "sales-n-stats.com";
//		$domain = "demo.sales-n-stats.com,www.sales-n-stats.com,sales-n-stats.com";
		$name = "";
		$expire = 0;
	}
	$modulesString = array();
	foreach ($modules as $module) {
		$modulesString[] = $module["name"].":".$module["expiration"];
	}
	$modulesString = join(',', $modulesString);
    $asd = array(
		'license_no' => $license_no, 
		'domain' => $domain,
		'expire' => $expire,
		'type' => $type,
		'issue_date' => $issue_date,
		'access_key' => $access_key,
		'modules' => $modulesString,
		'version' => $version);
    if ($name) {
        $asd["name"] = $name;
    }
    if ($N) {
        $asd["N"] = $N;
    }
	$fp = license_fingerprint($asd);
	if (!class_exists("rsa")) {
		require_once("classes/kernel/RSA.php");
	}
	$rsa = new RSA;
	$key = $rsa->encKeyFromString($rsa_key);
	$signature = $rsa->encryptMD5($key, $fp);
	//print "signature=$signature fp=$fp\n";print_r($key);
	$license =<<<EOT
license_no=$license_no
domain=$domain
name=$name
expire=$expire
type=$type
issue_date=$issue_date
modules=$modulesString
access_key=$access_key
version=$version
signature=$signature
EOT;
    if ($name) {
        $license .= "\nname=$name";
    }
    if ($N) {
        $license .= "\nN=$N";
    }
	$license = text_crypt($license);
	// break down into lines
	$lines = array();
	for ($i=0; $i<strlen($license)+69; $i+=70) {
		$lines[] = substr($license, $i, 70);
	}
	$license = implode("\r\n", $lines);
	if ($expire) {
		$expireStr = @strftime("%m/%d/%Y", $expire);
	} else {
		$expireStr = "Never";
	}

	$license =
"-------------- LiteCommerce License Certificate. DO NOT EDIT. -----------------\r\n".	
"License #   $license_no\r\n".
($name ? "Issued for: $name\r\n" : "") .
"Domain(s):  $domain\r\n".
"Expires:    $expireStr\r\n".
"Version:    $version\r\n".
($N?
"Maximum number of shops:   $N\r\n" : "").
"-------------------------------------------------------------------------------\r\n".
$license.
"-------------------------------------------------------------------------------\r\n";
    if (is_null($filename)) {
//        header("Content-type: application/force-download");
//        header("Content-Disposition: filename=LICENSE");
        print "<pre>".$license."</pre>";
    } else {
        $handle = fopen($filename, "w") or die("Unable to write license data");
        fwrite($handle, $license);
        fclose($handle);
    }
}

$modules = array(
	array("name" => "Affiliate", "expiration" => 0),
	array("name" => "2CheckoutCom", "expiration" => 0),
	array("name" => "AuthorizeNet", "expiration" => 0),
	array("name" => "AutoUpdateCatalog", "expiration" => 0),
	array("name" => "WholesaleTrading", "expiration" => 0),
	array("name" => "Intershipper", "expiration" => 0),
	array("name" => "InventoryTracking", "expiration" => 0),
	array("name" => "PayPal", "expiration" => 0),
	array("name" => "ProductOptions", "expiration" => 0),
	array("name" => "UPS", "expiration" => 0),
	array("name" => "USPS", "expiration" => 0),
	array("name" => "Promotion", "expiration" => 0),
	array("name" => "GiftCertificates", "expiration" => 0),
	array("name" => "AccountingPackage", "expiration" => 0),
	array("name" => "Echo", "expiration" => 0),
	array("name" => "eSelect", "expiration" => 0),
	array("name" => "Ogone", "expiration" => 0),
	array("name" => "eWAYxml", "expiration" => 0),
	array("name" => "NetRegistry", "expiration" => 0),
	array("name" => "LinkPoint", "expiration" => 0),
	array("name" => "VeriSign", "expiration" => 0),
	array("name" => "ProtxDirect", "expiration" => 0),
	array("name" => "Protx", "expiration" => 0),
	array("name" => "SagePay", "expiration" => 0),
	array("name" => "BankOfAmerica", "expiration" => 0),
	array("name" => "WellsFargo", "expiration" => 0),
	array("name" => "HSBC", "expiration" => 0),
	array("name" => "ePDQ", "expiration" => 0),
	array("name" => "PaySystems", "expiration" => 0),
	array("name" => "TrustCommerce", "expiration" => 0),
	array("name" => "WorldPay", "expiration" => 0),
	array("name" => "PlugnPay", "expiration" => 0),
	array("name" => "CyberSource", "expiration" => 0),
	array("name" => "Egoods", "expiration" => 0),
	array("name" => "EcommerceReports", "expiration" => 0),
	array("name" => "Newsletters", "expiration" => 0),
	array("name" => "SkipJack", "expiration" => 0),
    array("name" => "Netbilling", "expiration" => 0),
    array("name" => "SecureTrading", "expiration" => 0),
	array("name" => "VerisignLink", "expiration" => 0),
	array("name" => "CardinalCommerce", "expiration" => 0),
	array("name" => "NetworkMerchants", "expiration" => 0),
    array("name" => "Nochex", "expiration" => 0),
    array("name" => "ProductAdviser", "expiration" => 0),
    array("name" => "WishList", "expiration" => 0),
    array("name" => "CanadaPost", "expiration" => 0),
    array("name" => "LayoutOrganizer", "expiration" => 0),
    array("name" => "PayPalPro", "expiration" => 0),
    array("name" => "PHPCyberSource", "expiration" => 0),
    array("name" => "AustraliaPost", "expiration" => 0),
    array("name" => "AOM", "expiration" => 0),
	array("name" => "ChronoPay", "expiration" => 0),
	array("name" => "BeanStream", "expiration" => 0),
	array("name" => "PayFlowLink", "expiration" => 0),
	array("name" => "PayFlowPro", "expiration" => 0),
	array("name" => "FlyoutCategories", "expiration" => 0),
	array("name" => "GoogleCheckout", "expiration" => 0),
	array("name" => "UPSOnlineTools", "expiration" => 0),
);

/*
 * CLI mode
 */

if (!isset($_SERVER['REQUEST_METHOD'])) {
    require_once "PEAR.php";
    require_once "Console/Getopt.php";

    $license_file = "LICENSE.ORIG";
    $version = "1.2.0";
    $key = "B5BDC3ED09B69B78B86E7434E29DC05B89B04A2633ABD3AF53F757A4D1FC92B 70A923855A14194D9CFB959732556F3965EEACA3E06D660E1F097C7A62D115D";
    $trial = true;

    $argv = Console_Getopt::readPHPArgv();
    $options = Console_Getopt::getopt($argv, "h?tnf:v:k:");
    if (PEAR::isError($options)) {
        usage($options);
    }

    foreach ($options[0] as $opt) {
        $param = $opt[1];
        switch($opt[0]) {
            case 'f':
                $license_file = $param;
                break;
            case 't':
                $trial = true;
                break;
            case 'n':
                $trial = false;
                break;
            case 'v':
                $version = $param;
                break;
            case 'k':
                $key = $param;
                break;
            case 'h':
            case '?':
            default:
                usage();
                break;
        }
    }
    
    if ($trial) {
        echo "Creating trial license file $license_file, please wait... ";
        create_license("30 DAYS TRIAL", "", "", time()+31*24*3600, "basic", time(), "secret", array(), $version, $key, $license_file); 
        echo "[OK]\n";
    } else {
        die("NOT IMPLEMENTED");
    }
    exit();
}

function usage($options = null) {
}

/*
 * WEB mode
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// trial licence
//create_license('123','','',0, 'basic', time() - 29 * 24 * 3600, '123', $modules);
// basic license
//create_license('123','rrf.ru','',0, 'basic', time(), '123', $modules);
// basic wrong domain
//create_license('123','asd','',0, 'basic', time() - 29 * 24 * 3600, '123', $modules);
// expired license
//create_license('123','rrf.ru','',time() - 29 * 24 * 3600, 'basic', time(), '123', $modules);
// wrong system time
//create_license('123','rrf.ru','',time() + 24 * 3600, 'basic', time() + 25 * 3600, '123', $modules);
// development license
//create_license('123','rrf.ru','',time() + 24 * 3600, 'development', time(), '123', $modules);
// incorrect license
//create_license('123','rrf.ru','',time() + 24 * 3600, 'development', time(), '123', $modules);
    $license_no = $_POST["license_no"];
    $expire = 0;
    if (!empty($_POST["expire_dd"]) && !empty($_POST["expire_mm"]) && !empty($_POST["expire_yyy"])) {
        $expire = @mktime(0, 0, 0, $_POST["expire_mm"], $_POST["expire_dd"], $_POST["expire_yyy"]);
    }
    $domain = $_POST["domain"];
    $name = $_POST["name"];
    $access_key = md5($_POST["access_key"]);
    $license_modules = array();
    $type = $_POST["type"];
    $filename = isset($_POST["save_to_file"]) ? "LICENSE" : null;
    foreach ($modules as $module) {
        if (in_array($module["name"], $_POST["modules"])) {
            $license_modules[] = $module;
        }
    }
	$rsa_key = $_POST["rsa_key"];
	$version = $_POST["version"];
	$demo = (isset($_POST["demo"]) && !empty($_POST["demo"])) ? true : false;
    create_license($license_no, $domain, $name, $expire, $type, time(), $access_key, $license_modules, $version, $_POST["N"], $rsa_key, $filename, $demo); 
    if ($filename) header("Location: create_license.php?success");
    exit();
}
?>
<html>
<head><title>Create license for LiteCommerce</title></head>
<body>

<?php if (isset($_REQUEST["success"])) { ?>
<p><font color=green>License saved</font>
<?php } ?>

<p>Enter license key properties:
<form method=post>
<table border=0 cellspacing=5 cellpadding=3>
<tr>
    <td>License#</td>
    <td><input type=text name="license_no" value="local-dev"></td>
</tr>
<tr>
    <td>Domain:</td>
    <td><input type=text name="domain" size="65" value="crtdev.local,neo.crtdev.local,seraphim.crtdev.local,morpheus.crtdev.local,niobe.crtdev.local,pe.crtdev.local,dev.x-cart.com,trinity.x-cart.com,lite412.crtdev.local,lite423.crtdev.local,lite433.crtdev.local,lite434.crtdev.local,lite435.crtdev.local,lite436.crtdev.local,lite438.crtdev.local,lite4310.crtdev.local,lite4310z.crtdev.local,lite4311.crtdev.local,lite442.crtdev.local,lite510.crtdev.local,lite520.crtdev.local,lite521.crtdev.local,lite523.crtdev.local,lite523z.crtdev.local,lite524.crtdev.local,lite525.crtdev.local,demetra.crtdev.local,localhost,xcart.crtdev.local,luna.crtdev.local,luna,lu,luna5.crtdev.local,luna5,lu5,neo5.crtdev.local,neo5,neo53.crtdev.local,neo53"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=checkbox name="demo" value="1"> Online-demo</td>
</tr>
<tr>
    <td>Issued for:<br><font size=-1>(delevopment license only)</font></td>
    <td><input type=text name="name" value="Qualiteam development"></td>
</tr>
<tr>
    <td>Expires dd/mm/yyyy:</td>
    <td>
        <input type=text name="expire_dd" value="01" size=2 maxlength=2> /
        <input type=text name="expire_mm" value="06" size=2 maxlength=2> /
        <input type=text name="expire_yyy" value="2009" size=4 maxlength=4>
    </td>
</tr>
<tr>
    <td>License type:</td>
    <td><select name="type">
            <option value=basic>Basic</option>
            <option value=development>Development</option>
        </select>
    </td>    
</tr>
<tr>
    <td valign=top>Modules:</td>
    <td>
        <select name="modules[]" multiple size=10>
            <option value="">- no modules -</option>
<?php foreach ($modules as $module) { ?>
            <option value=<?= $module["name"] ?> selected><?= $module["name"] ?></option> 
<?php } ?>
        </select>
    </td>
</tr>
<tr>
    <td>Version:</td>
    <td><input type=text name="version" value="2.2"></td>
</tr>
<tr>
    <td>Activation/deactivation key:</td>
    <td><input type=text name="access_key" value="thesecretaccesskey"></td>
</tr>
<tr>
    <td>Maximum number of shops (ASP):</td>
    <td><input type=text name="N" value=""></td>
</tr>

<tr>
    <td>RSA encryption key:</td>
    <td><input type=text name="rsa_key" size="120" value="B5BDC3ED09B69B78B86E7434E29DC05B89B04A2633ABD3AF53F757A4D1FC92B 70A923855A14194D9CFB959732556F3965EEACA3E06D660E1F097C7A62D115D"></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=checkbox name="save_to_file" checked> Save license to file</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit value=Create></td>
</tr>
</table>
</form>
</body>
</html>
