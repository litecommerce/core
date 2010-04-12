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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */


/**
 * LiteCommerce (standalone edition) web installation wizard
 * 
 * @package LiteCommerce
 * @see     ____class_see____
 * @since   3.0.0
 */


if (!(basename(__FILE__) === 'install.php')) { // is not install.php
    die();
}

define ('XLITE_INSTALL_MODE', 1);
define ('LC_VERSION', '3.0.0');

// fixes compatibility issues
include_once "includes/prepend.php";
@include_once "compat/compat.php";

//
// In order to run installation script, installation auth code is required. 
// The auth code is created authomatically and is stored in etc/config.php
// file. See [installer_details] section
//

// Setting runtime configuration parameters {{{
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    error_reporting (E_ALL ^ E_NOTICE);
    set_magic_quotes_runtime(0);
} else {
    error_reporting (E_ALL ^ E_NOTICE ^ E_DEPRECATED);
}
@set_time_limit(300);
umask(0);
//  }}}

// Link auto-globals {{{
if (empty($HTTP_SERVER_VARS)) {
	$HTTP_GET_VARS = &$_GET;
	$HTTP_POST_VARS = &$_POST;
	$HTTP_SERVER_VARS = &$_SERVER;
}
// }}}

if (isset($_REQUEST["ruid"]) && $_REQUEST["ruid"]) {
	$report_uid = $_REQUEST["ruid"];
} else {
	$report_uid = substr(md5(uniqid(time())), 0, 12);
}

// get working parameters
// OS {{{
define('LC_OS_NAME', substr(php_uname(),0,strpos(php_uname(),' ')));
define('LC_OS_CODE', strtolower(substr(LC_OS_NAME,0,3)));
// }}}

if (LC_OS_CODE === 'win') {
	define('MAX_RECURSION_DEPTH', 800);		// !!!!!!!!!!!!! 8-[]
} else {
	define('MAX_RECURSION_DEPTH', 10000);
}
define('REPORT_FILENAME', 'check_report_'.$report_uid.'.txt');
define('DISABLE_FUNCTIONS_DELIMITER', ",");

$upload_tmp_dir = @ini_get("upload_tmp_dir");
if (isset($_ENV['TMPDIR'])) {
    $tmpdir = $_ENV['TMPDIR'];
} elseif (@is_dir('/tmp')) { 
    $tmpdir = '/tmp';
} elseif (isset($_ENV['TMP'])) {
    $tmpdir = $_ENV['TMP'];
} elseif (isset($_ENV['TEMP'])) { 
    $tmpdir = $_ENV['TEMP'];
} elseif (!empty($upload_tmp_dir) && @is_dir($upload_tmp_dir)) {
	$tmpdir = $upload_tmp_dir;
}

$reportFName = $tmpdir . DIRECTORY_SEPARATOR . REPORT_FILENAME;

 // Predefined common variables {{{
$templates_repository = "skins_original";
$schemas_repository = "schemas";
$templates_directory = "skins";
$default_skin = "default";
$default_locale = "en";
$templates_backup = $schemas_repository.'/templates/backup';

$min_ver = "4.1.0";
$max_ver = "6.0.0";
$forbidden_versions = array
(
	array("min" => "4.2.2", "max" => "4.2.3"),
	array("min" => "5.0.0", "max" => "5.0.9"),
);

$first_error = null;

$directories_to_create = array();
$directories_to_create[] = "var/backup";
$directories_to_create[] = "var/log";
$directories_to_create[] = "var/html";
$directories_to_create[] = "var/run";
$directories_to_create[] = "var/tmp";

$others_directories = array("var", "images", "files", $templates_directory);

$suphp_mode = get_php_execution_mode();

$files_to_create = array();

$config_file = "etc/config.php";

$error = false;
// }}}


if (isset($HTTP_GET_VARS["target"]) && $HTTP_GET_VARS["target"] == "install") {

	// loopback action
	if (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "loopback_test") {
		die("LOOPBACK-TEST-OK");
	}

	// memory test action
	if (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "memory_test" && isset($HTTP_GET_VARS["size"])) {
        $size = intval($HTTP_GET_VARS["size"]);
		if ($size <= 0 || $size > 64) {
			die("MEMORY-TEST-INVALID-PARAMS");
		}

		if (!function_exists("memory_get_usage")) {
			die("MEMORY-TEST-SKIPPED\nReason: memory_get_usage() is disabled on your hosting.");
		}

		// check memory limit set
        $res = @ini_get("memory_limit");
        if (!check_memory_limit($res, $size."M")) {
            die("MEMORY-TEST-LIMIT-FAILED");
        }

		$size -= (ceil(memory_get_usage() / (1024*1024)) + 1);

		$array = array();
		for ($i = 0; $i < $size; $i++) {
			$array[] = str_repeat("*", 1024*1024);
		}

		die("MEMORY-TEST-OK");
	}

	if (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "recursion_test") {
		recursion_depth_test(1);
		die("RECURSION-TEST-OK");
	}

	if (isset($HTTP_GET_VARS["action"]) && $HTTP_GET_VARS["action"] == "send_report") {
		$is_original = true;
		$report = "";
		$report = @file_get_contents($reportFName);
		if (!$report) {
			$is_original = false;
		}

?>
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<TITLE>LiteCommerce v.<?php echo LC_VERSION; ?> Installation Wizard</TITLE>

<STYLE type="text/css">

BODY,P,DIV,TH,TD,P,INPUT,SELECT,TEXTAREA {
        FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; 
        COLOR: #000000; FONT-SIZE: 10pt;
}
BODY { 
        MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px; MARGIN-LEFT: 0px; MARGIN-RIGHT: 0px; 
        BACKGROUND-COLOR: #FFFFFF;
		HEIGHT: 100%;
}
A:link {
        COLOR: #000000; TEXT-DECORATION: none;
}
A:visited {
        COLOR: #000000; TEXT-DECORATION: none;
}
A:hover {
        COLOR: #000000; TEXT-DECORATION: underline;
}
A:active  {
        COLOR: #000000; TEXT-DECORATION: none;
}

.background {
	BACKGROUND-COLOR: #FFFFFF;
}
.TableTop {
	BACKGROUND-COLOR: #FFFFFF;
}
.Clr1 {
	BACKGROUND-COLOR: #F8F8F8;
}
.Clr2 {
	BACKGROUND-COLOR: #E3EAEF;
}
.HeadTitle {
        FONT-SIZE: 14px; COLOR: #000000; TEXT-DECORATION: none;
}
.HeadSteps {
        FONT-SIZE: 11px; COLOR: #373B3D; TEXT-DECORATION: none;
}
.WelcomeTitle {
        FONT-SIZE: 11px;
        COLOR: #00224C; TEXT-DECORATION: none;
}

DIV.warning_div {
    margin: 3px;
    padding: 5px;
    text-align: left;
    border: 2px solid red;
    background: yellow;
    z-index: 2;
    width: 300px;
    position: absolute;
    font-size: 11px;
    color: black;
}

.install_error {
    font-size: 24px;
    color: red;
}

.ErrorMessage {
    font-weight: bold;
    color: #ff0000;
    font-size: 1.1em;
    text-align: center;
}

.DialogMainButton {
	background-color: #CDD9E1;
}   
.NavigationPath {
        COLOR: #294F6C; TEXT-DECORATION: none;
}
.NavigationPath:link {
        COLOR: #294F6C; TEXT-DECORATION: none;
}
.NavigationPath:visited {
        COLOR: #294F6C; TEXT-DECORATION: none;
}
.NavigationPath:hover {
        COLOR: #082032; TEXT-DECORATION: underline;
}
.NavigationPath:active {
        COLOR: #294F6C; TEXT-DECORATION: none;
}
</STYLE>

<?php display_common_js_code(); ?>
<SCRIPT language="javascript">
function ShowNotes(status)
{
	if (status) {
    	visibleBox("notes_url1", false);
    	visibleBox("notes_url2", true);
    	visibleBox("notes_body", true);
    } else {
    	visibleBox("notes_url1", true);
    	visibleBox("notes_url2", false);
    	visibleBox("notes_body", false);
    }
}
</SCRIPT>

</HEAD>

<BODY class="background" LEFTMARGIN="0" TOPMARGIN="0" RIGHTMARGIN="0" BOTTOMMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" style="FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; COLOR: #373B3D; FONT-SIZE: 12px; MARGIN-TOP: 0 px; MARGIN-BOTTOM: 0 px; MARGIN-LEFT: 0 px; MARGIN-RIGHT: 0 px; BACKGROUND-COLOR: #FFFFFF;">

<?php if (!$is_original) { ?>
<DIV style="left: 0px; top: 0px; width: 300px; height: 100px; position: absolute; display: none;" id="report_waiting_alert">
<TABLE width=300 height=100 class="TableTop" cellpadding=2 cellspacing=2>
<TR>
<TD>
<TABLE width=300 height=100 class="Clr2" cellpadding=2 cellspacing=2>
<TR>
<TD>
<TABLE width=300 height=100 class="TableTop" cellpadding=2 cellspacing=2>
<TR>
<TD align=center><B>Inspecting your server configuration.<br>It can take several minutes, please wait.</B></TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</DIV>
<?php } ?>

<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<?php /* common header */ ?>
<TR>
   <TD class="Head" background="skins_original/admin/en/images/head_demo_01.gif" WIDTH=494 HEIGHT=73><IMG SRC="skins_original/admin/en/images/logo_demo.gif" WIDTH=275 HEIGHT=60 BORDER="0"><br><IMG SRC="skins_original/admin/en/images/spacer.gif" WIDTH=494 HEIGHT=1 BORDER="0"></TD>
   <!--  TD class="Head" background="skins_original/admin/en/images/head_demo_02.gif" WIDTH="100%">
   <IMG SRC="skins_original/admin/en/images/spacer.gif" WIDTH="100%" HEIGHT=74 BORDER="0"></TD -->
   <TD class="Head"  WIDTH="100%" background="skins_original/admin/en/images/head_demo_02.gif">
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 align=right>
<TR><TD align=right>
<FONT class=HeadTitle><B>LiteCommerce v.<?php echo LC_VERSION; ?> Installation Wizard</B></FONT>&nbsp;&nbsp;<BR>
   <IMG SRC="skins_original/admin/en/images/spacer.gif" WIDTH=339 HEIGHT=1 ALT="" border=0>&nbsp;&nbsp;
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

<center>
<?php if (!$is_original) { ?><SCRIPT language="javascript">showWaitingAlert(true, "report_");</SCRIPT><?php } ?>
<?php

		if (!$is_original) {
			global $check_list;

			ob_start();
			module_check_cfg($_POST["params"]);
			ob_end_clean();

			$report = make_check_report((array)$check_list);
		}

		if ($report) {
			$report = (($is_original) ? "[original report]" : "[replicated report]")."\n\n".$report;
		} else {
			$report = "Report generation failed.";
		}

?>

<FORM method="POST" name="report_form" action="https://secure.qtmsoft.com/customer.php?target=customer_info&action=install_feedback_report">

<input type="hidden" name="product_type" value="LC">

<table border="0" cellpadding="1" cellspacing="2" align=center width=90%>
	<tr>
		<td colspan=2><br>
   		<FONT class=HeadTitle><B>Technical problems report</B></FONT><BR>
		<br>Our testing has identified some problems. Do you want to send a report about your server configuration and test results, <br>
		so we could analyse it and fix the problems? Please fill in all the required fields below.<br>
		<br>You can find more information about LiteCommerce software at <a href="http://litecommerce.com/faqs.html" target="_blank"><u>LiteCommerce FAQs</u></a> page.
		</td>
	</tr>
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td colspan=2>
		<b>Technical problems report:</b>
		<span id="notes_url1" style="display:"><a href="javascript:ShowNotes(true);" onClick="this.blur()"><u>See details &gt;&gt;</u></a></span>
		<span id="notes_url2" style="display: none"><a href="javascript:ShowNotes(false);" onClick="this.blur()"><u>Hide details &gt;&gt;</u></a></span>
		</td>
	</tr>
	<tr id="notes_body" style="display: none">
		<td colspan=2><textarea name="report" cols=90 rows=25 style="FONT-FAMILY: Courier;" readonly><?php echo $report; ?></textarea></td>
	</tr>

	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td colspan=2><b>Additional comments:</b></td>
	</tr>
	<tr>
		<td colspan=2><textarea name="user_note" cols=50 rows=15 style="FONT-FAMILY: Courier;"></textarea></td>
	</tr>

	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td colspan=2>
        <table border="0" cellpadding="1" cellspacing="2" align=center width=100%>
        	<tr>
        		<td align=left><input type="submit" class="DialogMainButton" value="Send report (*)"></td>
        		<td align=right><input type="button" value="Close window" onClick="javascript: window.close();"></td>
        		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        	</tr>
		</table>
		</td>
	</tr>
<?php if (!$is_original) { ?><SCRIPT language="javascript">showWaitingAlert(false, "report_");</SCRIPT><?php } ?>
	<tr>
		<td colspan=2>
		<b>(*)</b> The report will be sent to our support HelpDesk. A regular support ticket will be created on your behalf. <br>
		Please login to your HelpDesk account to receive a solution to this problem. Note that it will reduce your support points balance.
		<br><br>
		</td>
	</tr>
</table>
</FORM>
</center>

</BODY>
</HTML>
<?php
		die;
	}
}

function recursion_depth_test($index)
{
	if ($index <= MAX_RECURSION_DEPTH) {
		recursion_depth_test(++$index);
	}
}

// Configure Modules array {{{
$modules = array (
	array( // 0
			"name"          => "default",
			"comment"       => "License agreement",
            "auth_required" => false,
			"js_back"       => 0,
			"js_next"       => 1,
            "remove_params" => array(
                'auth_code',
                'new_installation',
                'force_current',
                'start_at'
            )
		),
	array( // 1
			"name"          => "check_cfg",
			"comment"       => "Checking PHP configuration",
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array(
                'ftp_enabled',
                'ftp_host',
                'ftp_username',
                'ftp_password',
                'ftp_dir',
                'xlite_http_host',
                'xlite_https_host',
                'xlite_web_dir',
                'mysqlhost',
                'mysqlbase',
                'mysqluser',
                'mysqlpass'
            )
		),
	array( // 2
			"name"          => "cfg_install_db",
			"comment"       => "Preparing to install LiteCommerce database",
            "auth_required" => true,
			"js_back"       => 1,
			"js_next"       => 1,
		    "remove_params" => array(
                'states',
                'demo',
                'install_data',
                'images_to_fs'
            )
        ),
	array( // 3
			"name"          => "install_db",
			"comment"       => "Installing LiteCommerce database",
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array()
		),
	array( // 5
			"name"          => "install_dirs",
			"comment"       => "Setting up templates",
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array()
		),
	array( // 6
			"name"          => "cfg_create_admin",
			"comment"       => "Creating administrator account",
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 1,
            "remove_params" => array(
                'login',
                'password',
                'confirm_password'
            )
		),
	array( // 7
			"name"          => "install_done",
			"comment"       => "Installation complete",
            "auth_required" => true,
			"js_back"       => 0,
			"js_next"       => 0,
            "remove_params" => array()
		)
);
// }}}


$check_list = array();

// Modules manager {{{

// check copyright file {{{
define('COPYRIGHT_FILE', './LICENSE.txt');
define('COPYRIGHT_EXISTS', @file_exists(COPYRIGHT_FILE));

if (!COPYRIGHT_EXISTS) {
    $current = 0;
    $params = array();
} else {
    $current = (int)$_POST["current"];
    $params = $_POST["params"];
}
// }}}

// remove params
if (isset($_POST['go_back']) && $_POST['go_back'] === '1') {
    for ($i = $current; $i < count($modules); $i++) {
        for ($j = 0; $j < count($modules[$i]['remove_params']); $j++) {
            if(isset($params[$modules[$i]['remove_params'][$j]])) {
                unset($params[$modules[$i]['remove_params'][$j]]);
            }
        }
    }
}
// }}}

if (isset($params["force_current"]) && !isset($params['start_at']) ) {
    $params['start_at'] = $params["force_current"];
}

if (isset($params["force_current"]) && $params["force_current"] == get_step('check_cfg')) {
	$params["new_installation"] = $params["force_current"];
	unset($params["force_current"]);
}

if ($current < 0 || $current >= count($modules)) {
	die("invalid current");
}

// check for the pre- and post- methods

if ($current) {
    if (isset($modules[$current - 1]["post_func"])) {
		check_authcode($params);
        $func = "module_".$modules[$current - 1]["name"]."_post_func";
        $func();
    }
}

// should the current be set here?
if (isset($params["force_current"]) && (isset($_POST['go_back']) && $_POST['go_back'] === '0') ) {
	$current = $params["force_current"];
	check_authcode($params);
	unset($params["force_current"]);
}

// {{{
// start html output
?>
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<TITLE>LiteCommerce v.<?php echo LC_VERSION; ?> Installation Wizard</TITLE>

<STYLE type="text/css">

BODY,P,DIV,TH,TD,P,INPUT,SELECT,TEXTAREA {
        FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; 
        COLOR: #000000; FONT-SIZE: 10px;
}
BODY { 
        MARGIN-TOP: 0px; MARGIN-BOTTOM: 0px; MARGIN-LEFT: 0px; MARGIN-RIGHT: 0px; 
        BACKGROUND-COLOR: #FFFFFF;
}
A:link {
        COLOR: #000000; TEXT-DECORATION: none;
}
A:visited {
        COLOR: #000000; TEXT-DECORATION: none;
}
A:hover {
        COLOR: #000000; TEXT-DECORATION: underline;
}
A:active  {
        COLOR: #000000; TEXT-DECORATION: none;
}

<?php
if ($current == 0) {
?>
.background {
	BACKGROUND-COLOR: #FFFFFF; BACKGROUND-IMAGE: URL('<?php echo (isHTTPS() ? 'https://' : 'http://'); ?>www.litecommerce.com/img/logo_lite.gif');
}
<?php
}else {
?>
.background {
	BACKGROUND-COLOR: #FFFFFF;
}
<?php
}
?>
.TableTop {
	BACKGROUND-COLOR: #FFFFFF;
}
.Clr1 {
	BACKGROUND-COLOR: #F8F8F8;
}
.Clr2 {
	BACKGROUND-COLOR: #E3EAEF;
}
.HeadTitle {
        FONT-SIZE: 14px; COLOR: #000000; TEXT-DECORATION: none;
}
.HeadSteps {
        FONT-SIZE: 11px; COLOR: #373B3D; TEXT-DECORATION: none;
}
.WelcomeTitle {
        FONT-SIZE: 11px;
        COLOR: #00224C; TEXT-DECORATION: none;
}

.ErrorTitle {
	font-size: 14px; 
	font-weight: bold; 
	color: red
}

DIV.warning_div {
    margin: 3px;
    padding: 5px;
    text-align: left;
    border: 2px solid red;
    background: yellow;
    z-index: 2;
    width: 300px;
    position: absolute;
    font-size: 11px;
    color: black;
}

.install_error {
    font-size: 24px;
    color: red;
}

.ErrorMessage {
    font-weight: bold;
    color: #ff0000;
    font-size: 1.1em;
    text-align: center;
}
</STYLE>

<?php display_common_js_code(); ?>
<SCRIPT language="javascript">
<?php
// show module's pertinent scripts

// 'back' button's script
switch ($modules[$current]["js_back"]) {
	case 0:
		default_js_back();
		break;
	case 1:
		$func = "module_".$modules[$current]["name"]."_js_back";
		$func();
		break;
	default:
		die("Invalid js_back value for module ".$modules[$current]["name"]."!");
}

// 'next' button's script
switch ($modules[$current]["js_next"]) {
	case 0:
		default_js_next();
		break;
	case 1:
		$func = "module_".$modules[$current]["name"]."_js_next";
		$func();
		break;
	default:
}
?>

function setNextButtonDisabled(flag)
{
    document.ifrm.next_button.disabled = flag;
}
</SCRIPT>
</HEAD>

<BODY class="background" LEFTMARGIN="0" TOPMARGIN="0" RIGHTMARGIN="0" BOTTOMMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" style="FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; COLOR: #373B3D; FONT-SIZE: 12px; MARGIN-TOP: 0 px; MARGIN-BOTTOM: 0 px; MARGIN-LEFT: 0 px; MARGIN-RIGHT: 0 px; BACKGROUND-COLOR: #FFFFFF;">

<TABLE height="100%" width="100%" cellspacing="0" cellpadding="0">
<TR>
<TD valign="top">

<DIV style="left: 0px; top: 0px; width: 300px; height: 100px; position: absolute; display: none;" id="waiting_alert">
<TABLE width=300 height=100 class="TableTop" cellpadding=2 cellspacing=2>
<TR>
<TD>
<TABLE width=300 height=100 class="Clr2" cellpadding=2 cellspacing=2>
<TR>
<TD>
<TABLE width=300 height=100 class="TableTop" cellpadding=2 cellspacing=2>
<TR>
<TD align=center><B>Inspecting your server configuration.<br>It can take several minutes, please wait.</B></TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</DIV>

<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<?php /* common header */ ?>
<TR>
   <TD class="Head" background="skins_original/admin/en/images/head_demo_01.gif" WIDTH=494 HEIGHT=73><IMG SRC="skins_original/admin/en/images/logo_demo.gif" WIDTH=275 HEIGHT=60 BORDER="0"><br><IMG SRC="skins_original/admin/en/images/spacer.gif" WIDTH=494 HEIGHT=1 BORDER="0"></TD>
   <!--  TD class="Head" background="skins_original/admin/en/images/head_demo_02.gif" WIDTH="100%">
   <IMG SRC="skins_original/admin/en/images/spacer.gif" WIDTH="100%" HEIGHT=74 BORDER="0"></TD -->
   <TD class="Head"  WIDTH="100%" background="skins_original/admin/en/images/head_demo_02.gif">
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 align=right>
<TR><TD align=right>
<FONT class=HeadTitle><B>LiteCommerce v.<?php echo LC_VERSION; ?> Installation Wizard</B></FONT>&nbsp;&nbsp;<BR>
   <IMG SRC="skins_original/admin/en/images/spacer.gif" WIDTH=339 HEIGHT=1 ALT="" border=0><br>
   <FONT class=HeadSteps><B><FONT color="#000000">Step <?php echo $current ?>:</FONT> <?php echo $modules[$current]["comment"] ?></B></FONT>&nbsp;&nbsp;
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

<!--<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<?php /* common header */ ?>
<TR>
   <TD ROWSPAN=2 background="./skins_original/default/en/images/head_back1.gif" WIDTH=335 HEIGHT=74><img src="./skins_original/default/en/images/logo.gif" width="335" height="74" ALT="LiteCommerce"></TD>
   <TD WIDTH="100%">&nbsp;</TD>
   <TD COLSPAN=2 align=right valign=center background="./skins_original/default/en/images/head_back2.gif" WIDTH=339 HEIGHT=51 valign=top>
   <FONT class=HeadTitle><B>LiteCommerce Installation Wizard</B></FONT>&nbsp;&nbsp;<BR>
   <IMG SRC="images/spacer.gif" WIDTH=339 HEIGHT=1 ALT="" border=0><br>
   <FONT class=HeadSteps><B>Step <?php echo $current ?>: <?php echo $modules[$current]["comment"] ?></B></FONT>&nbsp;&nbsp;</TD>
</TR>
<TR>
   <TD COLSPAN=3 background="./skins_original/default/en/images/head_dot.gif" WIDTH="100%" HEIGHT=24><FONT color="#373B3D"><B><?php @readfile("VERSION"); ?></B></FONT></TD>
</TR>
</TABLE>
-->
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
   <TD WIDTH=665><IMG SRC="skins_original/default/en/images/head_line.gif" WIDTH=665 HEIGHT=12 ALT=""></TD>
   <TD WIDTH="100%" align=right><FONT style="font-size: 9px"><B><?php @readfile("VERSION"); ?></B></FONT>&nbsp;</TD>
</TR>
</TABLE>
<BR>

<NOSCRIPT>
    <br>
    <DIV class="ErrorMessage">This installer requires JavaScript to function properly.<br>Please enable Javascript in your web browser.</DIV>
    <br>
</NOSCRIPT>

<TABLE class="TableTop" width="90%" border=0 cellspacing=0 cellpadding=0 align=center>
<?php
    /* common form */

    // check whether the form encoding type is set
    $enctype = isset($modules[$current]["form_enctype"]) ?
               "enctype=\"".$modules[$current]["form_enctype"]."\""  : "";
?>
<FORM method="POST" name="ifrm" action="<?php echo $HTTP_SERVER_VARS["PHP_SELF"] ?>" <?php print $enctype ?>>

<TR>
<TD valign="middle">
<?php

// get full function's name to call the corresponding module
$func = "module_".$modules[$current]["name"];
 // }}}
// check the auth code if required
if ($modules[$current]["auth_required"]) {
	check_authcode($params);
}

// run module
$res = $func($params);
?>
</TD>
</TR>
<?php

// show navigation buttons
$prev = $current;

if (!$res)
	$current += 1;

if ($current < count($modules)) {
?>

<TR>
 <TD align="center">
<?php
if (!empty($params) && (!isset($_POST['go_back']) || $_POST['go_back'] !== '1')) {
	foreach ($params as $key => $val) {
?>
  <INPUT type=hidden name="params[<?php echo $key ?>]" value="<?php echo $val ?>">
<?php
	}
} elseif (isset($_POST['go_back']) && $_POST['go_back'] === '1' && isset($params['new_installation']) && $params['new_installation'] === '1') {
?>
  <INPUT type=hidden name="params[new_installation]" value="1">
<?php
}
?>
<?php if ($report_uid) {?><INPUT type="hidden" name="ruid" value="<?php echo $report_uid; ?>"><?php } ?>
  <INPUT type="hidden" name="go_back" value="0">
  <INPUT type=hidden name="current" value="<?php echo $current ?>">
  <INPUT type=button value="&lt; Back"<?php echo ($prev > 0 ? "" : " disabled") ?> onClick="javascript:document.ifrm.go_back.value='1'; return step_back();">
  <INPUT name="next_button" type=button value="Next &gt;"<?php echo ($error || $current == get_step("check_cfg") ? " disabled" : ""); ?> onClick="javascript:if (step_next()) { ifrm.submit(); return true; } else { return false; }">
 </td>
</TR>
<?php
}
?>
</FORM>

<?php /* common bottom */ ?>

</TABLE>

</TD>
</TR>
</TR>
<TD valign="bottom">

<HR size=1 noshade>
<DIV ALIGN=right style="margin-bottom: 8px;">
  <FONT size=1>Copyright &copy; 2003 - 2010 <A href="http://www.creativedevelopment.biz">Creative Development</A>&nbsp;&nbsp;</FONT>
</DIV>

</TD>
</TR>
</TABLE>

</BODY>
</HTML>
<?php

exit();

 // end: Modules manager }}}

 // COMMON FUNCTIONS {{{

function display_common_js_code()
{
?>
<SCRIPT language="javascript">
function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

var visibleBoxId = false;
function setBoxVisible(id) 
{
    var box = document.getElementById(id);
    if (box) {
        if (box.style.display == "none") {
            if (visibleBoxId) {
                setBoxVisible(visibleBoxId);
            }   
            box.style.display = "";
            visibleBoxId      = id;
        } else {
            box.style.display = "none";
            visibleBoxId      = false;
        }
    }
}

var failedCodes = new Array();
var isDOM = false;
var isDocAll = false;
var isDocW3C = false;
var isOpera = false;
var isOpera5 = false;
var isOpera6 = false;
var isOpera7 = false;
var isMSIE = false;
var isIE = false;
var isNC = false;
var isNC4 = false;
var isNC6 = false;
var isMozilla = false;
var isLayers = false;
isDOM = isDocW3C = (document.getElementById) ? true : false;
isDocAll = (document.all) ? true : false;
isOpera = isOpera5 = window.opera && isDOM;
isOpera6 = isOpera && navigator.userAgent.indexOf("Opera 6") > 0 || navigator.userAgent.indexOf("Opera/6") >= 0;
isOpera7 = isOpera && navigator.userAgent.indexOf("Opera 7") > 0 || navigator.userAgent.indexOf("Opera/7") >= 0;
isMSIE = isIE = document.all && document.all.item && !isOpera;
isNC = navigator.appName=="Netscape";
isNC4 = isNC && !isDOM;
isNC6 = isMozilla = isNC && isDOM;

function getWindowWidth(w)
{
    if ( !w ) w = self;
    if ( isMSIE  ) return w.document.body.clientWidth;
    if ( isNC || isOpera  ) return w.innerWidth;
}

function getWindowHeight(w)
{
    if ( !w ) w = self;
    if ( isMSIE  ) return w.document.body.clientHeight;
    if ( isNC || isOpera  ) return w.innerHeight;
}

function setLeft(elm, x)
{
    if ( isOpera)
    {
        elm.style.pixelLeft = x;
    }
    else if ( isNC4 )
    {
        elm.object.x = x;
    }
    else
    {
        elm.style.left = x;
    }
}

function setTop(elm, y)
{
    if ( isOpera )
    {
        elm.style.pixelTop = y;
    }
    else if ( isNC4 )
    {
        elm.object.y = y;
    }
    else
    {
        elm.style.top = y;
    }
}

function showWaitingAlert(status, prefix)
{
	id = prefix+"waiting_alert";
	var Element = document.getElementById(id);
    if (Element) {
		var posX = (getWindowWidth() - 300) / 2;
		var posY = (getWindowHeight() - 100) / 2;
    	setLeft(Element, posX);
    	setTop(Element, posY);
		visibleBox(id, status);
	}
}


function showDetails(code)
{
    if (code == "" && document.getElementById('test_passed_icon')) {
        document.getElementById('test_passed_icon').style.display = '';
        return;
    }
    failedCodes.push(code);
    var failedElementsIds = new Array("");
    var detailsElement = document.getElementById('detailsElement');
    var hiddenElement = document.getElementById(code);
    var failedElement = document.getElementById('failed_' + code);

    if(hiddenElement) {
        detailsElement.innerHTML = hiddenElement.innerHTML;
    } else {
        detailsElement.innerHTML = '';
    }

    failedElement.style.fontWeight = 'bold';
    failedElement.style.textDecoration = '';

    for(var i = 0; i < failedCodes.length; i++) {
        if(failedCodes[i] != code) {
            var failedElement = document.getElementById('failed_' + failedCodes[i]);
            if(failedElement) {
                failedElement.style.fontWeight = '';
                failedElement.style.textDecoration = 'underline';
            }
        }
    }

}
</SCRIPT>
<?php
}

function isHTTPS()
{
    if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'] == 'on') || $_SERVER['HTTPS'] == '1')) ||
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443'))
        {
            return true;
        }
    return false;
}

function is_disabled_memory_limit()
{
    $info = get_info();
    
    return 
        (
            (
                $info["no_mem_limit"] && $info["commands_exists"] &&
                (!function_exists("memory_get_usage") && version_compare(phpversion(), "4.3.2", ">=")) &&
                strlen(@ini_get("memory_limit")) == 0 
            ) || 
            @ini_get("memory_limit") == "-1"
        );
}

function is_php5()
{
    global $xlite_php5;

    if (!isset($xlite_php5)) {
    	$xlite_php5 = version_compare(@phpversion(),"5.0.0") >= 0;
    }
    return $xlite_php5;
} 

function check_memory_limit($current_limit, $required_limit)
{
    $limit    = convert_ini_str_to_int($current_limit);
    $required = convert_ini_str_to_int($required_limit);
    if ($limit < $required) {
		// workaround for http://bugs.php.net/bug.php?id=36568
		if ((LC_OS_CODE == 'win') && (version_compare(phpversion(),"5.1.0") < 0)) return true;
        @ini_set('memory_limit', $required_limit);
        $limit = ini_get('memory_limit');

        return (strcasecmp($limit, $required_limit) == 0);
    }

    return true; 
}

/**
* Convert php_ini int string to int
*/
function convert_ini_str_to_int($string)
{
    $string = trim($string);

    $last = strtolower(substr($string,strlen($string)-1));
    $number = intval($string);
    switch($last) {
        case 'k':
            $number *= 1024;
        break;
        case 'm':
            $number *= 1024*1024;
        break;
        case 'g':
            $number *= 1024*1024*1024;
        break;
    }

    return $number;
}

function getStepBackNumber()
{
    global $current, $params;
    $back = 0;
    switch ($current) {
        case 4:
            $back = 2;
        break;
        case 6:
            $back = 4;
        break;
        default:
            $back = ($current > 0 ? $current - 1 : 0);
        break;
    }
    if (isset($params['start_at'])) {
        if ( ($params['start_at'] === '4' && $back < 4) || ($params['start_at'] === '6' && $back < 6) ) {
            $back = 0;
        }
    }

    return $back;
}

function getPHPUserName() // {{{
{
    if (LC_OS_CODE === 'win') {
    	$name = get_current_user();
    } else {
        $processUser = posix_getpwuid(posix_geteuid());
        $name = $processUser['name'];
    }

    return $name;
} // }}}


function inst_http_request_install($action_str) // {{{
{
	global $HTTP_SERVER_VARS;

	$host = "http://".$HTTP_SERVER_VARS["HTTP_HOST"];
	$len = strlen($host) - 1;
	$host = ($host{$len} == "/") ? substr($host, 0, $len) : $host;

	$web_dir = ereg_replace("/install(\.php)*", "", $HTTP_SERVER_VARS["PHP_SELF"]);
	$len = strlen($web_dir) - 1;
	$web_dir = ($web_dir{$len} == "/") ? substr($web_dir, 0, $len) : $web_dir;

	$url = $web_dir . "/install.php?target=install".(($action_str) ? "&$action_str" : "");
	$url_request = $host . $url;

	return inst_http_request($url_request);
} // }}}

function inst_http_request($url_request) // {{{
{
	@ini_get('allow_url_fopen') or @ini_set('allow_url_fopen', 1);
	$handle = @fopen ($url_request, "r");

	$response = "";
	if ($handle) {
		while (!feof($handle)) {
			$response .= fread($handle, 8192);
		}

		@fclose($handle);
	} else {

		$_this->error = '';

		require_once LC_EXT_LIB_DIR . 'PEAR.php';
		require_once LC_EXT_LIB_DIR . 'HTTP' . LC_DS . 'Request2.php';

        try {
    		$http = new HTTP_Request2($url_request);
            $http->setConfig('timeout', 5);
		    $response = $http->send()->getBody();

        } catch (Exception $e) {
            $_this->error = $e->getMessage();
            $response = false;
        }

	}

	return $response;
} // }}}

function make_check_report($check_list) // {{{
{
	$phpinfo_disabled = false;

	$report = "LiteCommerce version " . LC_VERSION . "\n";
	$report .= "Report time stamp: ". date("d, M Y  H:i"). "\n\n";

	foreach ($check_list as $key=>$item) {
		$report .= "[".$item["name"]."]\n";
		$report .= "Check result  - ".(($item["passed"]) ? "OK" : "FAILED")."\n";
		$report .= "Critical  - ".(($item["critical"]) ? "Yes" : "No")."\n";

		if ($key == "mem_allocation") {
			$report .= "Size  - ".$item["allocation_size"]."M\n";
		}

		switch ($key) {
			case "integrity_files":
				$report .= "Here is a list of files that have been checked:\n";
				foreach ($item["files"] as $filename=>$res) {
					$report .= "  * $filename - [".(($res) ? "OK" : "FAILED")."]\n";
				}
				
			break;

			case "php_version":
				$report .= "PHP Version  - ".$item["version"]."\n";
			break;

			case "memory_limit":
				$report .= "Memory limit size  - ".$item["size"]."\n";
			break;

			case "upload_max_filesize":
				$report .= "Upload file max size  - ".$item["max_size"]."\n";
			break;

			case "safe_mode":
				$report .= "Safe mode  - ".$item["on_off"]."\n";
			break;

			case "disable_functions":
				$report .= "Disabled functions list:\n";
				$none = true;
				if ($item["disable_functions"]) {
					$dfunctions = explode(DISABLE_FUNCTIONS_DELIMITER, $item["disable_functions"]);
					if (is_array($dfunctions) && count($dfunctions) > 0) {
						if (in_array("phpinfo", $dfunctions)) {
							$phpinfo_disabled = true;
						}

						$report .= "  * ".implode("\n  * ", $dfunctions)."\n";
						$none = false;
					}
				}

				if ($none) {
					$report .= "  - none\n";
				}
			break;

			case "recursion_test";
				$report .= "Recursion depth: " . $item["recursion_depth"] . "\n";
			case "loopback":
			case "http_post":
			case "mem_allocation":
				$report .= "Response data:\n";
				$report .= "************************************************\n";
				$report .= $item["response"];
				$report .= "\n************************************************\n";
			break;
		}

		$report .= "\n";
	}

	$report .= "\n============================= PHP info =============================\n";
	if (!$phpinfo_disabled) {
		// display PHP info
		$copyright = $_REQUEST["copyright"];
		$_REQUEST["copyright"] = null;
		$_POST["copyright"] = null;
		ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		ob_end_clean();
		$_REQUEST["copyright"] = $copyright;
		$_POST["copyright"] = $copyright;

		// prepare phpinfo 
		$phpinfo = preg_replace("/<td[^>]+>/i", " | ", $phpinfo);
		$phpinfo = preg_replace("/<[^>]+>/i", "", $phpinfo);
		$phpinfo = preg_replace("/(?:&lt;)((?!&gt;).)*?&gt;/i", "", $phpinfo);

		$pos = strpos($phpinfo, "PHP Version");
		if ($pos !== false) {
			$phpinfo = substr_replace($phpinfo, "", 0, $pos);
		}

		$pos = strpos($phpinfo, "PHP License");
		if ($pos !== false) {
			$phpinfo = substr($phpinfo, 0, $pos);
		}
	} else {
		$phpinfo .= "phpinfo() disabled.\n";
	}

	$report .= $phpinfo;

	

/*
echo '<textarea cols=100 rows=30 style="FONT-FAMILY: Courier;">';
print_r($report);
echo "</textarea><hr><hr><p>";
die;
//*/

	return $report;
} // }}}

function ftpChmod($ftp_connection, $dir, $user, $userMod='0755', $otherMod='0777') // {{{
{
    $set = false;
    if (!@is_writable($dir)) {
        $mod = ($user == getPHPUserName() ? $userMod : $otherMod);
        ftp_site($ftp_connection, "CHMOD ".$mod." ".$dir);
        $set = true;
    }
    return $set;
} // {{{

function check_ftp() // {{{
{
	return function_exists("ftp_connect");
} // }}}	
	
function ftp_init() // {{{
{	
	global $params;

	if (empty($params['ftp_host'])||empty($params['ftp_username'])||empty($params['ftp_password'])) 
	{
		warning_error("FTP connection has not been tested because some part of access information is missing. Please correct the FTP credentials you have entered or consult the LiteCommerce reference manual for information on how to set the necessary file permissions manually.");
		return false;
	}	
	
	$ftp_connection = @ftp_connect($params['ftp_host']);
	if (!$ftp_connection) 
	{
		warning_error("Installer can't connect to your FTP server. Please check FTP settings.");
		return false;	
	}	
		
	$ftp_login = @ftp_login($ftp_connection,$params['ftp_username'],$params['ftp_password']);
	if (!$ftp_login) {
		warning_error("Login or password is incorrect. Please check FTP credentials.");
		return false;	
	}
	$ftp_mode  = @ftp_pasv($ftp_connection,TRUE);
	if (!$ftp_mode) { 
		warning_error("Login or password is incorrect. Please check FTP credentials.");
		return false;
	}
    if (ftp_size($ftp_connection,$params['ftp_dir']."/VERSION")==-1)
	{
		warning_error("Upload directory is inaccessible. Please correct this setting.");
		return false;
	}
	return $ftp_connection;

} // }}}

function ftp_chmod_recursively($ftp_connection, $dir) // {{{
{

	ftp_chdir($ftp_connection,$dir);
	@ftp_site($ftp_connection,"CHMOD 0777 ".urldecode($dir)); 
	$files = handle_rawlist(ftp_rawlist($ftp_connection,"."));
	foreach ($files as $file) {
		@ftp_site($ftp_connection,"CHMOD 0777 ".urldecode($file['file'])); 
		if ($file['dir']==1&&$file['file']!="."&&$file['file']!="..") 
			ftp_chmod_recursively($ftp_connection,$file['file']);
	}	
	ftp_cdup($ftp_connection);

} // }}} 

function handle_rawlist($files) // {{{ 
{ 

	$nlist = array();
	foreach($files as $key => $file) {
		preg_match("/^(((dr.*?)\w+)|(.*<dir>.*))/i",$file,$args) ? $nlist[$key]['dir']= 1: $nlist[$key]['dir'] = 0;
		if (preg_match("/.*\s+(.*?)$/",$file,$args))
			$nlist[$key]['file'] = $args[1];
	}
	return $nlist;
}
// }}}

function encodeMD5($str) // {{{
{
	$strMD5 = strtolower(bin2hex(base64_encode(md5($str))));
	$str = "";

	for($i=0; $i<strlen($strMD5); $i++) {
		$symbol = ord(substr($strMD5, $i, 1));
		$symbol = ($symbol >= 48 && $symbol <= 57) ? ($symbol + 17) : ($symbol - 49);
		$str .= chr($symbol);
	}

	return $str;
} // }}}

function decodeMD5($str) // {{{
{
	$strMD5 = $str;
	$str = "";

	for($i=0; $i<strlen($strMD5); $i++) {
		$symbol = ord(substr($strMD5, $i, 1));
		$symbol = ($symbol >= 48 && $symbol <= 57) ? ($symbol + 49) : ($symbol - 17);
		$str .= chr($symbol);
	}

	$result = "";
	for ($i=0; $i<strlen($str); $i+=2) { 
		$result .= chr(hexdec(substr($str, $i, 2))); 
	}

	return base64_decode($result);
} // }}}

function check_exp_date($ignoreAuthCode=false) // {{{
{
	$common_text = $common_text = "Please visit our website at <a href=\"http://www.litecommerce.com\"><u><b>http://www.litecommerce.com</b></u></a> to purchase the full version of LiteCommerce Online Store Builder or download the latest demo version of LiteCommerce software.<br><br>If you have any questions please contact our sales team at <a href=\"mailto:sales@litecommerce.com\"><u><b>sales@litecommerce.com</b></u></a>";
	$expiration_text = "<b><font color=\"red\">This evaluataion copy of LiteCommerce software has expired.</font></b><br><br>" . $common_text;

	$result = false;
	$ex_res = @mysql_query("SELECT value, comment FROM xlite_config WHERE name='tax_code' AND category='Tax'");
	$value = @mysql_fetch_row($ex_res);
	if (!$value) {
		if (get_authcode() != null) {
			if (!$ignoreAuthCode) {
				die($expiration_text);
			} else {
				$result = true;
			}
		} else {
			$result = true;
		}
	} else {
		$value_md5 = decodeMD5($value[1]);
		$value = hexdec($value[0]);
		if (($value < mktime (0, 0, 0, date("m"), date("d"), date("Y"))) || $value_md5 != strtolower(md5(dechex($value)))) {
			die($expiration_text);
		}
	}

	return $result;
} // }}}

// default navigation button handlers {{{
function default_js_back() {
?>
	function step_back() {
		document.ifrm.current.value = "<?php echo getStepBackNumber(); ?>";
		document.ifrm.submit();
        return true;
	}
<?php
}

function default_js_next() {
?>
	function step_next() {
		return true;
	}
<?php
}

// }}}

function generate_authcode() { // {{{
	return generate_code();
} // }}}

function check_authcode(&$params) { // {{{
    $authcode = get_authcode();
    // if authcode IS NULL, then this is probably the first install, skip
    // authcode check
    if (is_null($authcode)) {
        return;
    }
    if (!isset($params["auth_code"]) || trim($params["auth_code"]) != $authcode) {
	    message("Incorrect auth code! You cannot proceed with the installation.");
        exit();
    }
} // }}}

function get_authcode() { // {{{
    global $config_file;

    // read config file
    $data = @parse_ini_file($config_file) or die("<font color=red>ERROR: config file not found ($config_file)</font>");
    return !empty($data["auth_code"]) ? $data["auth_code"] : null;
} // }}}

function save_authcode(&$params) { // {{{
    global $config_file;

    // if authcode set in request, don't change the config file
    if (isset($params["auth_code"]) && trim($params["auth_code"]) != "") {
        return $params["auth_code"];
    }
    // generate new authcode
    $auth_code = generate_authcode();
    if (!@is_writable($config_file) || !$config = file($config_file)) {
        message("Cannot open config file $config_file for writing!");
        exit();
    }

    $new_config = "";
    foreach ($config as $num => $line) {
        $new_config .= preg_replace("/^auth_code.*=.*/", 'auth_code = "'.$auth_code.'"', $line);
    }
    if (!save_config($new_config)) {
        message("Config file $config_file write failed!");
        exit();
    }

    return $auth_code;
} // }}}

function get_step($name) { // {{{
    global $modules;

    foreach ($modules as $step => $module_data) {
        if ($module_data["name"] == $name) {
            return $step;
        }
    }
    return 0;
} // }}}

function fatal_error($txt) { // {{{
?>
<CENTER>
<P>
 <B><FONT color=red>Fatal error: <?php echo $txt ?>.<BR>Please correct the error(s) before proceeding to the next step.</FONT></B>
</P>
</CENTER>
<?php
	return false;
} // }}}

function warning_error($txt) { // {{{
?>
<CENTER>
<P>
 <B><FONT color=red>Warning: <?php echo $txt ?>.</FONT></B>
</P>
</CENTER>
<?php
	return false;
} // }}}

function message($txt) { // {{{
?>
<B><FONT class=WelcomeTitle><?php echo $txt ?></FONT></B>
<?php
} // }}}

function status($var, $code = null) { // {{{
    if($code != null) {
        global $first_error;
        if($first_error == null && !$var) {
            $first_error = $code;
        }
        return ($var ? "<FONT color=green>[OK]</FONT>" : "<a href=\"javascript: showDetails('" . $code  . "');\" onClick='this.blur();' title='Click here to see more detailes'><FONT color=red style='text-decoration : underline' id='failed_" . $code . "'>[FAILED]</FONT></a>");
    } else {
        return ($var ? "<FONT color=green>[OK]</FONT>" : "<FONT color=red>[FAILED]</FONT>");
    }
} // }}}

function status_disabled($var) { // {{{
	return ($var ? "<FONT color=green>[OK]</FONT>" : "<FONT color=blue>[DISABLED]</FONT>");
} // }}}

function status_skipped() { // {{{
	return "<FONT color=blue>[SKIPPED]</FONT>";
} // }}}

function on_off($var) { // {{{
	return ($var ? "On" : "Off");
} // }}}

function update_pgp() { // {{{
#	if (!@is_dir(".pgp")) {
#		if (!mkdir(".pgp", 0755))
#			return false;

		// we need to copy all files from .pgp.def to .pgp here!
		// code must be win32 safe!
#	}

	return true;
} // }}}

// Function to copy directory tree from skins_original to skins

function copy_files($source_dir, $parent_dir, $destination_dir) { // {{{

    global $suphp_mode, $config_file;
    $dir_permission = 0777;
    if($suphp_mode != 0) {
        $data = @parse_ini_file($config_file);
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;
    }


	$status = true;

	if (!$handle = @opendir($source_dir)) {
		echo status(false)."<BR>\n";
		return false;
	}

	while (($file = readdir($handle)) !== false) {
		if (@is_file($source_dir."/".$file)) {
			if (!@copy("$source_dir/$file", "$destination_dir$parent_dir/$file")) {
				echo "Copying $source_dir$parent_dir/$file to $destination_dir$parent_dir/$file ... ".status(false)."<BR>\n";
				$status &= false;
			}

			flush();

		} else if (@is_dir($source_dir."/".$file) && $file != "." && $file != "..") {
			echo "Creating directory $destination_dir$parent_dir/$file ... ";

			if(!@file_exists("$destination_dir$parent_dir/$file")) {
				if(!@mkdir("$destination_dir$parent_dir/$file", $dir_permission)) {
					echo status(false);
					$status &= false;
				} else
					echo status(true);
			} else
				echo "[Already exists]";

			echo "<BR>\n"; flush();

			$status &= copy_files($source_dir."/".$file, $parent_dir."/".$file, $destination_dir);
		}
	}

	closedir($handle);

//	echo status($status)."<BR>\n";

	return $status;
} // }}}

function create_dirs($dirs) { // {{{
	$status = true;
    global $suphp_mode, $config_file;
    $dir_permission = 0777;
    if($suphp_mode != 0) {
        $data = @parse_ini_file($config_file);
        $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;
    } else {
        $data = @parse_ini_file($config_file);
        $dir_permission = isset($data['nonprivileged_permission_dir']) ? base_convert($data['nonprivileged_permission_dir'], 8, 10) : 0755;
    }

	foreach ($dirs as $val) {
		echo "Creating directory: [$val] ... ";

		if (!@file_exists($val)) {
			$res = @mkdir($val, $dir_permission);
			$status &= $res;

			echo status($res);
		} else 
			echo "[Already exists]";

		echo "<BR>\n"; flush();
	}

	return $status;
} // }}}

function chmod_others_directories($dirs)
{
    global $suphp_mode, $config_file;

    if($suphp_mode == 0) return;

    $data = @parse_ini_file($config_file);
    $dir_permission = isset($data['privileged_permission_dir']) ? base_convert($data['privileged_permission_dir'], 8, 10) : 0711;
    foreach($dirs as $dir) {
        @chmod($dir, $dir_permission);
    }
}

function create_files($files_to_create) { // {{{
	if (is_array($files_to_create) && count($files_to_create) > 0) {
		foreach($files_to_create as $file=>$content) {
			if ($fd = @fopen($file,"w")) {
				@fwrite($fd, $content);
				@fclose($fd);
			}
			else
				return false;
		}
	}
	return true;
} // }}}

function create_htaccess_files($files_to_create) { // {{{
    $status = true;
    if (is_array($files_to_create)) {
        foreach($files_to_create as $file=>$content) {
            echo "Creating file: [$file] ... ";
            if ($fd = @fopen($file,"w")) {
                @fwrite($fd, $content);
                @fclose($fd);
                echo status(true);
                $status &= true;
            } else {
                echo status(false);
                $status &= false;
            }
            echo "<BR>\n"; flush();
        }
    }

    return $status;
} // }}}

function change_config(&$params) { // {{{
	//global $installation_auth_code;
    global $config_file;

    // check whether config file is writable
    clearstatcache();
    if (!@is_readable($config_file) || !@is_writable($config_file)) return false;

    // read file content
    if (!$config = file($config_file)) return false;

	// fixing the empty xlite_https_host value
	if (!isset($params["xlite_https_host"]) || $params["xlite_https_host"] == "") {
		$params["xlite_https_host"] = $params["xlite_http_host"];
	}

    // check whether the authcode is set in params. 

    $new_config = "";
    // change config file ..
    foreach ($config as $num => $line) {
    $patterns = array("/^hostspec.*=.*/",
                      "'^database.*=.*'",
                      "/^username.*=.*/",
                      "/^password.*=.*/",
                      "/^http_host.*=.*/",
                      "/^https_host.*=.*/",
                      "/^web_dir.*=.*/"
                      );
    $replacements = array('hostspec = "'.$params["mysqlhost"].'"',
                          'database = "'.$params["mysqlbase"].'"',
                          'username = "'.$params["mysqluser"].'"',
                          'password = "'.$params["mysqlpass"].'"',
                          'http_host = "'.$params["xlite_http_host"].'"',
                          'https_host = "'.$params["xlite_https_host"].'"',
                          'web_dir = "'.$params["xlite_web_dir"].'"'
                          );
    // check whether skin param is specified
    if (isset($params["skin"])) {
        $patterns[] = "/^skin.*=.*/";
        $replacements[] = 'skin = "'.$params["skin"].'"';
    }
    $new_config .= preg_replace($patterns, $replacements, $line);
    }
    return save_config($new_config);
 }
 // }}}

function save_config($content) { // {{{
    global $config_file;
    // save config
    $handle = fopen($config_file, 'wb');
    fwrite($handle, $content);
    fclose($handle);
    return $handle ? true : $handle;
} // }}}

function get_info() { // {{{
    static $info;

    if (!isset($info)) {
        $info = array(
            "thread_safe"     => false,
            "debug_build"     => false,
            "php_ini_path"    => '',
            'no_mem_limit'    => true,
            'commands_exists' => false,
			'php_ini_path_forbidden' => false
            );
    } else {
        return $info;
    }

    ob_start();
    phpinfo(INFO_GENERAL);
    $php_info = ob_get_contents();
    ob_end_clean();

    $dll_sfix = ((LC_OS_CODE === 'win') ? '.dll' : '.so');

    foreach (split("\n",$php_info) as $line) {
        if (eregi('command',$line)) {
            $info['commands_exists'] = true;
            if (eregi('--enable-memory-limit', $line)) {
                $info['no_mem_limit'] = false;
            }
            continue;
        }
        if (eregi('thread safety.*(enabled|yes)', $line)) {
            $info["thread_safe"] = true;
        }
        if (eregi('debug.*(enabled|yes)', $line)) {
            $info["debug_build"] = true;
        }
        if (eregi("configuration file.*(</B></td><TD ALIGN=\"left\">| => |v\">)([^ <]*)(.*</td.*)?",$line,$match)) {
            $info["php_ini_path"] = $match[2];
            //
            // If we can't access the php.ini file then we probably lost on the match
            //
            if (!@ini_get("safe_mode") && !@file_exists($info["php_ini_path"])) {
                $info["php_ini_path_forbidden"] = true;
            }
        }
    }
    return $info;
} // }}}

//recursive chmod
function chmod_R($path, $filemode)
{
    if (!@is_dir($path))
        return @chmod($path, $filemode);

    $dh = @opendir($path);
    while ($file = @readdir($dh)) {
        if($file != '.' && $file != '..') {
            $fullpath = $path.'/'.$file;
            if(!@is_dir($fullpath)) {
                if (!@chmod($fullpath, $filemode))
                    return false;
            } else {
                if (!chmod_R($fullpath, $filemode))
                    return false;
            }
        }
    }

    @closedir($dh);
    if(@chmod($path, $filemode))
        return true;
    else
        return false;
}

// Move images from required field to the filesystem
function move_images_to_fs($table, $id, $prefix, $file_prefix, $path="images/")
{
    global $config_file;

	if ( !@file_exists($path) ) {
		$res = @mkdir($path, 0777);
		if ( !$res )
			return;
	}

    $data = @parse_ini_file($config_file);
    $mysql_link = @mysql_connect($data['hostspec'],$data['username'],$data['password']);
    if (!$mysql_link) {
        return false;
    } elseif (!mysql_select_db($data['database'])) {
        return false;
    } else {
		@mysql_query("SET sql_mode='MYSQL40'");
        $my_query = mysql_query("SELECT ".$id.", ".$prefix."_type, ".$prefix." FROM ".$table." WHERE ".$prefix."_source='D'");

		// Fetch rows
		$iteration = 0;
        while($row = @mysql_fetch_array($my_query, MYSQL_ASSOC))
        {
			$fileName = $file_prefix . $row[$id] . "." . get_image_extension($row[$prefix."_type"]);
			$filePath = $path . $fileName;

			$content = $row[$prefix];
			if ( empty($content) )
				continue;

			// put image content to the file
			if (($fd = fopen($filePath, "wb"))) {
				fwrite($fd, $content);
				fclose($fd);
			} else {
				continue;
			}

			$sql = "UPDATE ".$table." SET ".$prefix."='".$fileName."', ".$prefix."_source='F' WHERE ".$id."='".$row[$id]."'";
			@mysql_query($sql);

			if ( $iteration++ % 2 ) {
				print(".");
				func_flush();
			}

        }

    }
}

function get_image_extension($type)
{
	$default_type = "gif";
	$image_types = array(
			"gif"  => "image/gif",
            "jpeg" => "image/jpeg",
            "png"  => "image/png",
            "swf"  => "image/swf",
            "psd"  => "image/psd",
            "bmp"  => "image/bmp",
            "tiff" => "image/tiff",
            "jpc"  => "image/jpc",
            "jp2"  => "image/jp2",
            "jpx"  => "image/jpx",
            "swc"  => "image/swc",
            "iff"  => "image/iff"
        );

	foreach ($image_types as $k=>$v)
		if ( $v == $type )
			return $k;

	return $default_type;
}

// }}}

// END COMMON FUNCTIONS }}}

// MODULES {{{

// Default module. Shows Terms & Conditions {{{
function module_default(&$params) {
	global $error, $templates_directory;
	//global $installation_auth_code;
    global $config_file;
	$clrNumber = 2;

?>
<CENTER>
<BR><BR><BR>
<?php message("You are about to install LiteCommerce shopping system.<BR>This installation wizard will guide you through the installation process.") ?>
<BR><BR><BR>

<?php if (COPYRIGHT_EXISTS) { ?>

<TEXTAREA name="copyright" cols="80" rows="22" style="font-family: monospace; FONT-SIZE: 9pt; border: 1px solid #888888; border-right-width: 0px;" readonly>
<?php readfile(COPYRIGHT_FILE); ?>
</TEXTAREA>

<P>
<?php

if (!is_null(get_authcode())) { ?>

<TABLE border=0>
<TR>
 <TD><input type=radio name="params[force_current]" value="<?php print get_step("check_cfg") ?>">
 <TD><b>Perform new installation</b></TD>
</TR>
<TR>
 <TD><input type=radio name="params[force_current]" value="<?php print get_step("cfg_install_dirs") ?>" checked></TD>
 <TD><b>Re-install skin files</b></TD>
</TR>
<TR>
 <TD><input type=radio name="params[force_current]" value="<?php print get_step("cfg_create_admin") ?>"></TD>
 <TD><b>Configure primary administrator account</b></TD>
</TR>
<TR><TD colspan=2><b>Auth code: </b><INPUT type=text name="params[auth_code]" size=20><BR><FONT size=1>( required for protection from unauthorized<BR> use of installation script )</FONT></TD></TR>
</TABLE>
<P>

<?php } else { ?>
<input type=hidden name="params[new_installation]" value="<?php print get_step("check_cfg") ?>">
<?php } ?>

<INPUT type=checkbox name="agree" onClick="this.blur(); setNextButtonDisabled(!this.checked);"> I accept the License Agreement

<?php
} else {
    $error = true;
?>
<P class="install_error">Could not find license agreement file.<br>Aborting installation.</P>
<?php } ?>

<BR><BR>

</CENTER>

<BR>

<?php
	return false;
}

// 'next' button handler. checks 'agree' button checked
function module_default_js_next() { // {{{
?>
	function step_next() {
		if (document.ifrm.agree.checked) {
			return true;
		} else {
			alert("You must accept the License Agreement to proceed with the installation. If you do not agree with the terms of the License Agreement, do not install the software.");
		}
		return false;
	}
<?php
} // }}}

// end default module }}}

// Check_cfg module. Gets info about current php configuration {{{
function module_check_cfg(&$params) { 
	global $min_ver, $error, $config_file, $max_ver, $forbidden_versions;
	global $HTTP_SERVER_VARS, $check_list, $report_uid;
	$clrNumber = 2;
	$warning = 0;

?>
<CENTER>
<SCRIPT language="javascript">showWaitingAlert(true, '');</SCRIPT>
<TABLE width="100%" border=0 cellspacing=0 cellpadding=2>

<TR valign=top>
 <TD width="50%" rowspan=2>

<TABLE width="100%" border=0 cellspacing=0 cellpadding=4>

<?php
    $essentialFiles = array();
	$essential_files_status = true; // = $res;	
	$loopback_test_status = true;

	$clrNumber = 0;
?>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD colspan=4 align=left><B>Environment checking</B></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD align=center><B>Verification steps</B></TD>
  <TD width="1%">&nbsp;</TD>
  <TD width="1%" align=center><B>Status</B></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>
<?php

	if (count($essentialFiles) > 0) {
    	$status = true;

        foreach($essentialFiles as $efile => $efileMD5) {
        	$checkMD5 = ltrim(md5(@file_get_contents($efile)), "0");
    		if (strcasecmp($checkMD5, $efileMD5)) {
    			$essentialFiles[$efile] = false;
        		$essential_files_status = $status = false;
        	} else {
    			$essentialFiles[$efile] = true;
        	}
        }

    	// store "integrity files" check results in the check list 
    	$check_list["integrity_files"] = array(
    		"name"		=> "Verifying the integrity of essential files",
    		"passed"	=> $essential_files_status,
    		"critical"	=> true,
    		"files"		=> $essentialFiles
    	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>Verifying the integrity of essential files ...</TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "essential_files") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>
	 
<?php
	} // if (count($essentialFiles) > 0)


	// loopback test
	$status = false;
	$response = inst_http_request_install("action=loopback_test");
	if (strpos($response, "LOOPBACK-TEST-OK") === false) {
		$loopback_test_status = false;
	} else {
		$status = true;
	}

	// store "loopback" check results in the check list
	$check_list["loopback"] = array(
		"name"		=> "Loopback test",
		"passed"	=> $loopback_test_status,
		"critical"	=> true,
		"response"	=> $response
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>Loopback test ...</TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "loopback_test") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>
<?php


?>
 <TR>
  <TD colspan=4>&nbsp;</TD>
 </TR>
<?php

	$clrNumber = 0;
?>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD colspan=4 align=left><B>Inspecting server configuration</B></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD align=center><B>Checking critical dependencies</B></TD>
  <TD width="1%">&nbsp;</TD>
  <TD width="1%" align=center><B>Status</B></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

<?php

// PHP Version must be at least $min_ver

	$ver = phpversion();
	(version_compare($ver, $min_ver, ">=") && version_compare($ver, $max_ver, "<")) ? $status = 1: $status = 0;
	$unsupported_version = $ck_res = $status;
    foreach($forbidden_versions as $fpv) {
    	if (version_compare($ver, $fpv["min"], ">=") && version_compare($ver, $fpv["max"], "<=")) {
    		$unsupported_version = $status = 0;
    		break;
    	}
    }
    if ($ck_res > 0 && $status == 0) {
    	$ck_res = 0;
    }

	// store "php_version" check results in the check list
	$check_list["php_version"] = array(
		"name"		=> "PHP Version",
		"passed"	=> $unsupported_version,
		"critical"	=> true,
		"version"	=> $ver
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>PHP Version (min <?php echo $min_ver ?> required) ... <?php echo $ver ?></TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "php_version") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

<?php
// PHP Safe mode must be Off if PHP is earlier 5.3

if (version_compare(PHP_VERSION, '5.3.0', "<")) {
	$res = @ini_get("safe_mode");
	$safe_mode_status = $status = (!empty($res) ? 0 : 1);
    if ($ck_res > 0 && $status == 0) {
    	$ck_res = 0;
    }

	// store "safe mode" check results in the check list
	$check_list["safe_mode"] = array(
		"name"		=> "PHP Safe mode",
		"passed"	=> $safe_mode_status,
		"critical"	=> true,
		"on_off"	=> on_off(!$status),
	);

?>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD>PHP Safe mode is ... <?php echo on_off(!$status) ?></TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "safe_mode") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

<?php
}

  // Disabled functions list ideally must be empty

  $res = @ini_get("disable_functions");
  $status = (empty($res) ? 1 : 0);


    $warning = !$status; // add warning

	// store "disable functions" check results in the check list
	$check_list["disable_functions"] = array(
		"name"		=> "Disabled functions",
		"passed"	=> $status,
		"critical"  => true,
		"disable_functions"	=> $res
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD>Disabled functions ... <?php echo ($status ? "none" : $res) ?></TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "disabled_functions") ?></TD>
  <TD width="1%" align=center><?php if (!$status) { ?>
    <IMG src="skins_original/default/en/images/code.gif" onClick="javascript:setBoxVisible('warning_disable_func');">
    <DIV id="warning_disable_func" class="warning_div" style="display: none" onClick="javascript:setBoxVisible('warning_disable_func');">
     <p><b>Warning!</b> Some functions are disabled on your hosting.</p>
     <p>You can continue installation, however we recommend consulting our support team for more information about correct installation.</p>
    </DIV>
<?php
    } else {
        echo '&nbsp;';
    }
?></TD>
 </TR>


<?php

// memory_limit must be >= 16M

    $res = @ini_get("memory_limit");

    if (is_disabled_memory_limit()) {
        $res = 'Unlim';
        $status = 1;
    } else {
        $status = check_memory_limit($res, '16M');
    }

    $memory_limit = $res;
    $memory_limit_status = $status;

    if ($ck_res > 0 && $status == 0) {
        $ck_res = 0;
    }

	// store "memory limit" check results in the check list
	$check_list["memory_limit"] = array(
		"name"		=> "Memory limit",
		"passed"	=> $memory_limit_status,
		"critical"	=> true,
		"size"		=> $res,
	);

?>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD>Memory limit ... <?php echo $res; ?></TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "memory_limit") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

<?php

// File uploads must be On

	$res = @ini_get("file_uploads");
	$status = (!empty($res) ? 1 : 0);
    if ($ck_res > 0 && $status == 0) {
    	$ck_res = 0;
    }

	// store "file uploads" check results in the check list
	$check_list["file_uploads"] = array(
		"name"		=> "File uploads",
		"passed"	=> $status,
		"critical"	=> true,
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD>File uploads are ... <?php echo on_off($status) ?></TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "file_upload") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

<?php

// MySQL functions must present

	$status = function_exists('mysql_connect');
    if ($ck_res > 0 && $status == 0) {
    	$ck_res = 0;
    }

	// store "file uploads" check results in the check list
	$check_list["mysql_support"] = array(
		"name"		=> "MySQL support",
		"passed"	=> $status,
		"critical"	=> true,
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD>MySQL support is ... <?php echo on_off($status) ?></TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "mysql_support") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

 <TR>
  <TD colspan=4>&nbsp;</TD>
 </TR>
	 
 <?php $clrNumber = 1; ?>
	 
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD align=center><B>Checking non-critical dependencies</B></TD>
  <TD width="1%">&nbsp;</TD>
  <TD width="1%" align=center><B>Status</B></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

<?php
	$res = @ini_get("upload_max_filesize");

	$check_list["upload_max_filesize"] = array(
		"name"		=> "Upload file size limit",
		"passed"	=> true,
		"critical"	=> false,
		"max_size"	=> $res
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD>Upload file size limit is ... <?php echo $res ?></TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($res, "upload_filesize") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>

 <?php
	    $res = @ini_get("allow_url_fopen");
		$allow_url_fopen_status = true; // = $res;	

	$check_list["allow_url_fopen"] = array(
		"name"		=> "fopen() function can open URLs",
		"passed"	=> $res,
		"critical"	=> false
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
   <TD> fopen() function can open URLs ... </TD>
   <TD width="1%">-</TD>
   <TD width="1%" align=center><?php echo status($res, "fopen") ?></TD>
   <TD width="1%" align=center>&nbsp;</TD>
 </TR>


<?php
    $res = (string) @ini_get("open_basedir");

    if($res != "") {
        $open_basedirs = explode(PATH_SEPARATOR, $res);
        $directories = explode(PATH_SEPARATOR, getenv("PATH"));
        $current_dir = getcwd();
        global $tmpdir;
        $temp_dir = array($tmpdir);
        $check_paths = array_merge($directories, $temp_dir);
        $check = true;

        foreach($check_paths as $dir) {
            $found = false;
            $dir = strtolower($dir);
            foreach($open_basedirs as $open_dir) {
            	$open_dir = strtolower($open_dir);
                if(!empty($open_dir) && strpos($dir, $open_dir) !== false) {
                    if($dir == $open_dir) {
                        $found = true;
                        break;
                    } elseif($dir{strlen($open_dir)} == DIRECTORY_SEPARATOR || (strlen($open_dir) > 0 && $open_dir{strlen($open_dir)-1} == DIRECTORY_SEPARATOR && $dir{strlen($open_dir)-1} == DIRECTORY_SEPARATOR)) {
                        $found = true;
                        break;
                    }
                }
            }

            if(!$found) {
                $check = false;
                break;
            }
        }

        if($check) {
            if(!in_array($current_dir, $open_basedirs) && !in_array(".", $open_basedirs)) {
            	$found = false;
            	$current_dir = strtolower($current_dir);
                foreach($open_basedirs as $open_dir) {
                	$open_dir = strtolower($open_dir);
                    if(!empty($open_dir) && strpos($current_dir, $open_dir) !== false) {
                        if($current_dir == $open_dir) {
                            $found = true;
                            break;
                    	} elseif($current_dir{strlen($open_dir)} == DIRECTORY_SEPARATOR || (strlen($open_dir) > 0 && $open_dir{strlen($open_dir)-1} == DIRECTORY_SEPARATOR && $current_dir{strlen($open_dir)-1} == DIRECTORY_SEPARATOR)) {
                            $found = true;
                            break;
                        }
                    }
                }

                if(!$found) {
                    $check = false;
                    break;
                }
            }
        }

        $open_basedir_restriction = $check;
    } else {
        $open_basedir_restriction = true;
    }

    
    $check_list["open_basedir_restriction"] = array(
        "name"      => "open_basedir restriction",
        "passed"    => $open_basedir_restriction,
        "critical"  => false
    );
?>
<TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
    <TD> open_basedir restriction ... </TD>
    <TD width="1%">-</TD>
    <TD width="1%" align=center><?php echo status($open_basedir_restriction, "open_basedir") ?></TD>
    <TD width="1%" align=center>&nbsp;</TD>
</TR>

<?php


	$status = false;
	$url = ((isHTTPS()) ? "https://" : "http://")."liveupdate.litecommerce.com/service.php?action=echo&timestamp=".time();
	$response = inst_http_request($url);
	if ($response !== false) {
		list($status, $length, $crc, $data) = explode("\n", $response);

		if (strpos($status, "OK") !== false) {
			$status = true;
		}
	}

	$check_list["http_post"] = array(
		"name"		=> "Test HTTP POST request",
		"passed"	=> $status,
		"critical"	=> false,
		"response"	=> $response,
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>Test HTTP POST request ...</TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo status($status, "test_http_post") ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>
<?php


	global $memory_allocation_size;

	// Memory test
	$status = is_php5();
	$memory_allocation_size = 0;
	$response = "";

    $is_skipped = is_php5();
	if ($loopback_test_status && !$is_skipped) {
		$sizes = array(16, 32, 48, 64);
		$status = true;
		foreach ($sizes as $size) {
			$response = inst_http_request_install("action=memory_test&size=$size");
            if (!(strpos($response, "MEMORY-TEST-SKIPPED") === false)) {
                $status = false;
                $is_skipped = true;
                break;
            } elseif (strpos($response, "MEMORY-TEST-OK") === false) {
				$status = false;
				break;
            }
			$memory_allocation_size = $size;
		}

		$check_list["mem_allocation"] = array(
			"name"		=> "Memory allocation",
			"passed"	=> $status,
			"critical"	=> false,
			"allocation_size" => $memory_allocation_size,
			"response"	=> $response
		);
	}
	$memory_test_status = $status;
?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>Memory allocation ...</TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo (($loopback_test_status && !$is_skipped) ? status($status, "memory_allocation") : status_skipped()) ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>
<?php


	$status = false;
	$response = "";
	if ($loopback_test_status) {
		$response = inst_http_request_install("action=recursion_test");
		if (strpos($response, "RECURSION-TEST-OK") !== false) {
			$status = true;
		}

		$check_list["recursion_test"] = array(
			"name"		=> "Recursion test",
			"recursion_depth" => MAX_RECURSION_DEPTH,
			"passed"	=>	$status,
			"critical"	=> false,
			"response"	=> $response
		);
	}
	$recursion_test_status = $status;

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>Recursion test ...</TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php echo (($loopback_test_status) ? status($status, "recursion_test") : status_skipped()) ?></TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>
<?php

	$res = check_ftp();

	$check_list["ftp_support"] = array(
		"name"		=> "PHP FTP support",
		"passed"	=> $res,
		"critical"	=> false,
		"jfyi"		=> true,
	);

?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD>PHP FTP support is ... </TD>
  <TD width="1%">-</TD>
  <TD width="1%" align=center><?php if (!$res || LC_OS_CODE === 'win') echo status_disabled($res); else { ?>
	  <input id="ftp_enabled" type=checkbox name="params[ftp_enabled]" checked onClick="this.blur();visibleBox('ftp_params',this.checked)">
<?php
	}
?>	
  </TD>
  <TD width="1%" align=center>&nbsp;</TD>
 </TR>
 </TABLE>
 <SPAN id="ftp_params" style="display: ;">
 <TABLE border=0 cellpadding=4 cellspacing=0>
<?php
	if ($res && !(LC_OS_CODE === 'win')) {
?>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Host: </TD>
  <TD width="1%"></TD>
  <TD width="1%" align=center><input type=text value="<?php if (isset($_SERVER["HTTP_HOST"])) { echo $_SERVER["HTTP_HOST"]; } ?>" name="params[ftp_host]"></TD>
  </TR>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Username: </TD>
  <TD width="1%"></TD>
  <TD width="1%" align=center><input type=text value="" name="params[ftp_username]"></TD>
  </TR>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password: </TD>
  <TD width="1%"></TD>
  <TD width="1%" align=center><input type=password value="" name="params[ftp_password]"></TD>
  </TR>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Upload directory:</TD>
  <TD width="1%"></TD>
  <TD width="1%" align=center><input type=text value="" name="params[ftp_dir]"></TD>
  </TR>
  <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD colspan=3><b>Note:</b> The 'PHP FTP support' section is <b>optional</b>  and used to automatically set the necessary file permissions on uploaded distribution files. Leave the corresponding check box empty to set file permissions manually.</TD>
  </TR>

<?php
	}	
?>
</TABLE>
 </SPAN>
<SCRIPT language="javascript">
var Element = document.getElementById("ftp_enabled");
if (Element) {
	visibleBox('ftp_params', Element.checked);
}
</SCRIPT>
</TD>
<TD width=25 rowspan=2>&nbsp;</TD> 

<TD width="50%" valign=top>
<SCRIPT language="javascript">showWaitingAlert(false, '');</SCRIPT>
<TABLE id="status" border=0 cellpadding=0 cellspacing=0>
<TR>
    <TD valign=top>

    <div id="loopback_test" style="display : none">
    <font class="ErrorTitle">Installation cannot be continued<br>because the loopback test failed to host "<?php echo $HTTP_SERVER_VARS["HTTP_HOST"]; ?>".</font>
	<br><br>
	If a firewall is installed on your system, please open access to "<?php echo $HTTP_SERVER_VARS["HTTP_HOST"]; ?>".
    </div>

    <div id="essential_files" style="display : none">
    <font class="ErrorTitle">Installation cannot be continued<br>because the essential files are damaged or missing.</font>
    <br><br>
	<b>Make sure all the files have been uploaded correctly.</b><BR><BR>

    <p>Here is a list of files that have been checked:<BR>
<?php
    foreach($essentialFiles as $efile => $efileStatus) {
    	echo "&nbsp;&nbsp;&nbsp;*&nbsp;" . $efile . "&nbsp;-&nbsp;" . status($efileStatus) . "<BR>";
    }
?>

    <br><br>You can find more information about LiteCommerce software<br>at <a href="http://litecommerce.com/faqs.html" target="_blank"><u>LiteCommerce FAQs</u></a> page.
    </div>

    <div id="php_version" style="display : none">
    <font class="ErrorTitle">Dependency failed: Unsupported version of PHP (<?php echo phpversion(); ?>)</font>
    <br><br>
	Currently versions <b>4.1.0</b> - <b>4.4.X</b>, <b>5.1.X</b> and <b>5.2.X</b> are supported. <BR><BR>

    <p>This version of LiteCommerce will work on any OS<br>where PHP/MySQL meets minimum <a href="http://www.litecommerce.com/server_requirements.html"><u>system requirements</u></a>.
    <br><br>You can find more information about LiteCommerce software<br>at <a href="http://www.litecommerce.com/faqs.html"><u>http://www.litecommerce.com/faqs.html</u></a>.
    </div>
<?php
        $info = get_info();
?>
    <div id="safe_mode" style="display : none">
    <font class="ErrorTitle">Dependency failed: Safe Mode is ON</font>
    <br><br>
	Safe Mode must be turned off for correct operation of LiteCommerce application.

    <p>To disable Safe Mode: 
    
    <p><b>1. If you have access to php.ini file</b>
    <br><br>
    Locate the
    <br><br>
    <font style="background-color: #E3EAEF;"><?php print $info["php_ini_path"] ?></font>
    <br><br>
    file, find and edit the following line within the file:
    <br><br>
    <font style="background-color: #E3EAEF;">safe_mode = "On"</font>
    <br><br>
    change to:
    <br><br>
    <font style="background-color: #E3EAEF;">safe_mode = "Off"</font>
    <br><br>
    Save the file, then restart your web server application for the changes to take effect.

    <p><b>2. If you do not have access to php.ini file</b>
    <br><br>
    Please contact the support services of your hosting provider to adjust this parameter.
    <br><br>
    </div>

<div id="open_basedir" style="display : none">
<font class="ErrorTitle">Non-critical dependency failed</font>
<br><br>
For Curl, openSSL and other external applications to work correctly with LiteCommerce, the value of open_basedir restriction variable in php.ini file must be empty or contain a valid path to external applications. A good solution is to add a valid path to external applications to the system 'PATH' variable.
<p>To adjust this parameter:
<p><b>1. If you have access to php.ini file</b>
<br><br>
Locate the
<br><br>
<font style="background-color: #E3EAEF;"><?php print $info["php_ini_path"] ?></font>
<br><br>
file, find and edit the following line within the file:<br><br>
<font style="background-color: #E3EAEF;">open_basedir = "/usr/local/php"</font>&nbsp;(for example)
<br><br>
change to:
<br><br>
<font style="background-color: #E3EAEF;">open_basedir = ""</font>
<br><br>
Save the file, then restart your web server application for the changes to take effect.

<p><b>2. If you do not have access to php.ini file</b>
<br><br>
Please contact the support services of your hosting provider to adjust this parameter.
<br><br>
</div>

<div id="fopen" style="display : none">
    <font class="ErrorTitle">Non-critical dependency failed</font>
    <br><br>
	For LiteCommerce application to work correctly, the value of allow_url_fopen variable in php.ini file must be "On".

    <p>To adjust this parameter:

    <p><b>1. If you have access to php.ini file</b>
    <br><br>
    Locate the
    <br><br>
    <font style="background-color: #E3EAEF;"><?php print $info["php_ini_path"] ?></font>
    <br><br>
    file, find and edit the following line within the file:
	<br><br>
    <font style="background-color: #E3EAEF;">allow_url_fopen = "Off"</font>
    <br><br>
    change to:
    <br><br>
    <font style="background-color: #E3EAEF;">allow_url_fopen = "On"</font>
    <br><br>
    Save the file, then restart your web server application for the changes to take effect.

    <p><b>2. If you do not have access to php.ini file</b>
    <br><br>
    Please contact the support services of your hosting provider to adjust this parameter.
	<br><br>
</div>

<div id="memory_limit" style="display : none">
<font class="ErrorTitle">Dependency failed: memory_limit</font>
    <br><br>
    For LiteCommerce application to work correctly, the value of memory_limit variable in php.ini file must be &gt;= 16M.

    <p>To adjust this parameter:

    <p><b>1. If you have access to php.ini file</b>
    <br><br>
    Locate the
    <br><br>
    <font style="background-color: #E3EAEF;"><?php print $info["php_ini_path"] ?></font>
    <br><br>
    file, find and edit the following line within the file:
    <br><br>
    <font style="background-color: #E3EAEF;">memory_limit = <?php echo $memory_limit; ?></font>
    <br><br>
    change to:
    <br><br>
    <font style="background-color: #E3EAEF;">memory_limit = 16M</font>
    <br><br>
    Save the file, then restart your web server application for the changes to take effect.

    <p><b>2. If you do not have access to php.ini file</b>
    <br><br>
    Please contact the support services of your hosting provider to adjust this parameter.
    <br><br>
</div>

<div id="memory_allocation" style="display : none">
<font class="ErrorTitle">Non-critical dependency failed</font>
<br><br>
The configuration of the server where LiteCommerce will be installed meets the Server requirements, however some server software issues have been identified which can impair LiteCommerce operation.
<br><br>
Please contact our support team for further investigation.
</div>
<div id="recursion_test" style="display : none">
<font class="ErrorTitle">Non-critical dependency failed</font>
<br><br>
The configuration of the server where LiteCommerce will be installed meets the Server requirements, however some server software issues have been identified which can impair LiteCommerce operation.
<br><br>
Please contact our support team for further investigation.
</div>

<div id="disabled_functions" style="display : none">
<font class="ErrorTitle">Dependency failed: disabled functions</font>
    <br><br>
    For LiteCommerce application to work correctly, the value of disable_functions variable in php.ini file must be empty.
    <p>To adjust this parameter:
    <p><b>1. If you have access to php.ini file</b>
    <br><br>
    Locate the
    <br><br>
    <font style="background-color: #E3EAEF;"><?php print $info["php_ini_path"] ?></font>
    <br><br>
    file, find and edit the following line within the file:
    <br><br>
    <font style="background-color: #E3EAEF;">disable_functions = <?php echo @ini_get("disable_functions"); ?></font>
    <br><br>
    change to:
    <br><br>
    <font style="background-color: #E3EAEF;">disable_functions = </font>
    <br><br>
    Save the file, then restart your web server application for the changes to take effect.
    <p><b>2. If you do not have access to php.ini file</b>
    <br><br>
    Please contact the support services of your hosting provider to adjust this parameter.
    <br><br>
</div>

<div id="file_upload" style="display : none">
<font class="ErrorTitle">Dependency failed: file uploads</font>
    <br><br>
    For LiteCommerce application to work correctly, the value of file_uploads variable in php.ini file must be 1.
    <p>To adjust this parameter:
    <p><b>1. If you have access to php.ini file</b>
    <br><br>
    Locate the
    <br><br>
    <font style="background-color: #E3EAEF;"><?php print $info["php_ini_path"] ?></font>
    <br><br>
    file and set:
    <br><br>
    <font style="background-color: #E3EAEF;">file_uploads = 1</font>
    <br><br>
    Save the file, then restart your web server application for the changes to take effect.
    <p><b>2. If you do not have access to php.ini file</b>
    <br><br>
    Please contact the support services of your hosting provider to adjust this parameter.
    <br><br>
</div>

<div id="mysql_support" style="display : none">
<font class="ErrorTitle">Dependency failed: MySQL support</font>
    <br><br>
    For LiteCommerce application to work with a database, MySQL support must be enabled.
    <br><br>
    Please contact the support services of your hosting provider to adjust this parameter.
</div>

<div id="upload_filesize" style="display : none">
<font class="ErrorTitle">Non-critical dependency failed</font>
<br><br>
The configuration of the server where LiteCommerce will be installed meets the Server requirements, however some server software issues have been identified which can impair LiteCommerce operation.
    <br><br>
	For LiteCommerce application to work correctly, the value of upload_max_filesize variable in php.ini file should contain the maximum size of the files allowed to be uploaded.
    <p>To adjust this parameter:
    <p><b>1. If you have access to php.ini file</b>
    <br><br>
    Locate the
    <br><br>
    <font style="background-color: #E3EAEF;"><?php print $info["php_ini_path"] ?></font>
    <br><br>
    file and set, for example:
    <br><br>
    <font style="background-color: #E3EAEF;">upload_max_filesize = 2M</font>
    <br><br>
    Save the file, then restart your web server application for the changes to take effect.
    <p><b>2. If you do not have access to php.ini file</b>
    <br><br>
    Please contact the support services of your hosting provider to adjust this parameter.
    <br><br>
</div>

<div id="test_http_post" style="display : none">
<font class="ErrorTitle">Non-critical dependency failed</font>
<br><br>
The configuration of the server where LiteCommerce will be installed makes sending POST requests to external servers impossible. Please contact the support services of your hosting provider to adjust this parameter.
</div>

<div id="detailsElement">

</div>

<div style="display: none; padding-top: 50px; padding-left: 50px;" id="test_passed_icon">
<img src="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/skins_original/admin/en/images/test_passed.gif" border="0" />
</div>

<script type="text/javascript">
<?php
    global $first_error;
?>
    var first_code = '<?php echo ($first_error) ? $first_error : ''; ?>';
    showDetails(first_code);
</script>


    </TD>
</TR>
</TABLE>

</TD>

</TR>

<TR>
<TD valign=bottom>

<TABLE  id="status_report" border=0 width=100% valign=bottom style="display: none;" class="TableTop" cellpadding=2 cellspacing=2>
<TR>
<TD>
<TABLE width=100% class="Clr2" cellpadding=2 cellspacing=2>
<TR>
<TD>
<TABLE width=100% class="TableTop" cellpadding=2 cellspacing=2>
<TR>
    <TD valign=middle nowrap><IMG src="skins_original/default/en/images/code.gif"></TD>
	<TD valign=middle width=100%>Our testing has identified some problems. Do you want to send a report about your server configuration and test results, so we could analyse it and fix the problems?</TD>
	<TD valign=middle nowrap><input type="button" value="Send report" onclick="javascript:window.open('install.php?target=install&action=send_report&ruid=<?php echo $report_uid; ?>','SEND_REPORT','toolbar=no,status=no,scrollbars=yes,resizable=yes,menubar=no,location=no,direction=no');"></TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>

</TD>
</TR>

<?php
$perms = array(); 
global $templates_repository, $schemas_repository;
global $others_directories;
global $suphp_mode;
if (substr(PHP_OS, 0, 3) != 'WIN') {
    if($suphp_mode == "0") {
    	if (!@is_writable(".")) $perms[] = "&gt; chmod 0777 .";
    	if (!@is_writable($config_file)) $perms[] = "&gt; chmod 0666 $config_file";
        foreach($others_directories as $dir) {
            if (!@is_writable($dir)) $perms[] = "&gt; chmod 0777 " . $dir;
        }
    }

    if ($dh = @opendir("bin")) {
        while (($file = readdir($dh)) !== false) {
            if (@filetype($file) == "file" && !@is_executable($file)) {
                $perms[] = "&gt; chmod 0755 bin/*";
                break;
            }
        }
        closedir($dh);
    }

    if (count($perms) > 0) {
?>

<TR>
<TD colspan=3>
<BR><BR>
<P>
<?php message("Before starting the installation, please make sure that the following file permissions are assigned (UNIX only):") ?>
</P>

<FONT color="darkblue">
<?php
        foreach ($perms as $p) {
            echo $p . "<BR>";
        }
?>
</FONT>
</TD>
</TR>

<?php
    }
}
?>    
</TABLE>

<?php
    if ($ck_res > 0 && (!$essential_files_status || !$loopback_test_status)) {
    	$ck_res = 0;
    }

	$test_failed = false;
	foreach ($check_list as $item) {
		if (!$item["passed"] && !(isset($item["jfyi"]) && $item["jfyi"])) {
			$test_failed = true;
			break;
		}
	}

	// save report to file
	if ($test_failed) {
		global $reportFName;

		$report = make_check_report($check_list);

		if (@file_exists($reportFName) && !@is_writeable($reportFName)) {
			@chmod($reportFName, 0755);
		}

		$report_saved = false;
		$handle = @fopen($reportFName, "wb");
		if ($handle) {
			@fwrite($handle, $report);
			@fclose($handle);
			$report_saved = true;
		}
?>
<SCRIPT language="javascript">visibleBox("status_report", true);</SCRIPT>
<?php
	}
?>

<?php if ($ck_res && count($perms) == 0) { ?><BR><?php 
        message("Push the \"Next\" button below to continue");
        if($warning && $ck_res) { ?>
<BR>
<P>Your server configuration is not optimal; this can make your LiteCommerce-based store partially or even completely inoperative. Are you sure you want to continue the installation?</P>
<INPUT type="checkbox" onClick="javascript:setNextButtonDisabled(!this.checked);">&nbsp;Yes, I want to continue the installation.
<?php 
        } 
    }
?>

</CENTER>
 
<BR>
<?php
  $error = (!$ck_res || $warning || count($perms) != 0);
  return false;
}  // }}}

// Cfg_install_db module. Gets mysql server info and check it before installing db {{{
function module_cfg_install_db(&$params) {
	global $HTTP_SERVER_VARS, $error, $schemas_repository; 
	global $report_uid, $reportFName;
	global $templates_repository, $schemas_repository;
	$clrNumber = 2;

	if (@file_exists($reportFName)) {
		@unlink($reportFName);
		$report_uid = "";
	}

	if (isset($params["xlite_http_host"]) && strlen($params["xlite_http_host"]) == 0) unset($params["xlite_http_host"]);
	if (isset($params["xlite_https_host"]) && strlen($params["xlite_https_host"]) == 0) unset($params["xlite_https_host"]);
	if (isset($params["xlite_web_dir"]) && strlen($params["xlite_web_dir"]) == 0) unset($params["xlite_web_dir"]);
	if (isset($params["mysqlhost"]) && strlen($params["mysqlhost"]) == 0) unset($params["mysqlhost"]);
	if (isset($params["mysqlbase"]) && strlen($params["mysqlbase"]) == 0) unset($params["mysqlbase"]);
	if (isset($params["mysqluser"]) && strlen($params["mysqluser"]) == 0) unset($params["mysqluser"]);
	if (isset($params["mysqlpass"]) && strlen($params["mysqlpass"]) == 0) unset($params["mysqlpass"]);
	if (check_ftp()&&isset($params['ftp_enabled'])) {
		$ftp_connection = ftp_init();
		if ($ftp_connection) {
            ftp_chdir($ftp_connection,$params["ftp_dir"]);
			
			$ftpUser = $params["ftp_username"];

    		if($suphp_mode == "0") {
                if (ftpChmod($ftp_connection, ".", $ftpUser, '0755', '0777')) {
                    $params['set_chmod'] = 1;
                }

                ftpChmod($ftp_connection, "etc/config.php", $ftpUser, '0644', '0666');
                ftpChmod($ftp_connection, "LICENSE", $ftpUser, '0644', '0666');
                ftpChmod($ftp_connection, "cart.html", $ftpUser, '0644', '0666');

            }

/*			ftp_site($ftp_connection,"CHMOD 0777 schemas");
			ftp_site($ftp_connection,"CHMOD 0777 skins_original");
			ftp_chmod_recursively($ftp_connection,"schemas");
			ftp_chmod_recursively($ftp_connection,"skins_original");*/
		}
	}
	if (is_null($params['auth_code'])) {
		//save_authcode($params); 
	?>
		<input type="hidden" name="params[auth_code]" value="<?php echo get_authcode(); ?>">
<?php	
	}

	if ((!isset($params["mysqlhost"]) || $params["mysqlhost"] == "") || (!isset($_POST['go_back']) || $_POST['go_back']==1)) {
		if (!isset($_POST['go_back']) || $_POST['go_back'] == 0) {
			$mysqlhost = "localhost";
			$mysqluser = "";
			$mysqlpass = "";
			$mysqlbase = "eshop";
		} else {
			$mysqlhost = $params['mysqlhost'];
			$mysqluser = $params['mysqluser'];
			$mysqlpass = $params['mysqlpass'];
			$mysqlbase = $params['mysqlbase'];
		}
	?>
<CENTER>
<P>
 <B><FONT color="darkgreen">The Installation Wizard needs to know your web server and MySQL database details:</FONT></B>
</P>

<TABLE width="100%" border=0 cellpadding=4>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD width="70%"><B>Web server name</B><BR>Hostname of your web server (E.g.: www.example.com).</TD>
  <TD><INPUT type="text" name="params[xlite_http_host]" size=30 value="<?php if (!isset($_POST['go_back']) || $_POST['go_back']==0) echo $HTTP_SERVER_VARS["HTTP_HOST"]; else echo $params['xlite_http_host']; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Secure web server name</B><BR>Hostname of your secure (HTTPS-enabled) web server (E.g.: secure.example.com). If omitted, it is assumed to be the same as the web server name. </TD>
  <TD><INPUT type="text" name="params[xlite_https_host]" size=30 value="<?php if (!isset($_POST['go_back']) || $_POST['go_back']==0) echo $HTTP_SERVER_VARS["HTTP_HOST"]; else echo $params['xlite_https_host']; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>LiteCommerce web directory</B><BR>Path to LiteCommerce files within the web space of your web server (E.g.: /shop).</TD>
  <TD><INPUT type="text" name="params[xlite_web_dir]" size=30 value="<?php if (!isset($_POST['go_back']) || $_POST['go_back']==0) echo ereg_replace("/install(\.php)*", "", $HTTP_SERVER_VARS["PHP_SELF"]); else echo $params['xlite_web_dir']; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL server name</B><BR>Hostname or IP address of your MySQL server.</TD>
  <TD><INPUT type="text" name="params[mysqlhost]" size=30 value="<?php echo $mysqlhost; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL database name</B><BR>The name of the existing database to use (if the database does not exist on the server, you should create it to continue the installation).</TD>
  <TD><INPUT name="params[mysqlbase]" size=30 type=text value="<?php echo $mysqlbase; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL username</B><BR>MySQL username. The user must have full access to the database specified above.</TD>
  <TD><INPUT name="params[mysqluser]" size=30 type=text value="<?php echo $mysqluser; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL password</B><BR>Password for the above MySQL username.</TD>
  <TD><INPUT name="params[mysqlpass]" size=30 type=text value="<?php echo $mysqlpass; ?>"></TD>
 </TR>

</TABLE>

<BR><?php message("Push the \"Next\" button below to continue") ?>

</CENTER>

<BR>
<?php
		return true;
	} else {

// Now checking if database named $params[mysqlbase] already exists

        global $config_file;
		$ck_res = 1;

		// Check domain and web directory
		$url = "http://$params[xlite_http_host]$params[xlite_web_dir]/COPYRIGHT";
		if (@file_get_contents("./COPYRIGHT") !== @file_get_contents($url)) {
			$ck_res &= fatal_error("The web server name and/or web drectory is invalid! Press 'BACK' button and review web server settings you provided");
		} else { // if web server settings provided ate valid
    
		$mylink = @mysql_connect($params["mysqlhost"], $params["mysqluser"], $params["mysqlpass"]);

        if($mylink) {
            $version = @mysql_get_server_info($mylink);
            if(strpos($version, "-") !== false) {
                $version = substr($version, 0, strpos($version, "-"));
            }

            if ((func_version_compare($version, "5.0.50") >= 0 && func_version_compare($version, "5.0.52") < 0)) {
                warning_error("The version of MySQL which is currently used contains known bugs, that is why LiteCommerce may operate incorrectly. We recommend to update MySQL to a more stable version.");
            }
        }

		if (!$mylink)
			$ck_res &= fatal_error("Can't connect to MySQL server specified. Press 'BACK' button and review MySQL server settings you provided.");
		else if (!@is_writable($config_file))
			$ck_res &= fatal_error("Cannot open file \"$config_file\" for writing. UNIX permissions for file \"$config_file\" need to be set to 0666");
		else if (!@mysql_select_db($params["mysqlbase"]))
		{	
			@mysql_query("SET sql_mode='MYSQL40'");
			//$ck_res &= message("Installer couldn't find database \"".$params["mysqlbase"]."\". Installation process will attempt to create it");
			if (!@mysql_query('CREATE DATABASE '.$params["mysqlbase"]))
			{
				$ck_res &= fatal_error("Installer couldn't create database \"".$params["mysqlbase"]."\". Please create it manually or ask your hosting provider to do it for you.");
			}
			else $ck_res = true;	
		}	
		else {
			@mysql_query("SET sql_mode='MYSQL40'");
			$mystring = "";
			$first = true;

			$res = @mysql_list_tables($params["mysqlbase"]);

			while ($row = @mysql_fetch_row($res)) {
				$ctable = $row[0];
				if ($ctable == "xlite_products")
					warning_error("Installation Wizard has detected that the specified database has existing LiteCommerce tables. If you continue with the instalaltion, the tables will be purged.");
			}

			@mysql_close ($mylink);
		}
		} // if web server settings provided ate valid
?>

<CENTER>
<TABLE width="100%" border=0 cellpadding=4>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD width="70%"><B>Web server name</B><BR>Hostname of your web server (E.g.: www.example.com).</TD>
  <TD><?php echo $params["xlite_http_host"] ?></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Secure web server name</B><BR>Hostname of your secure (HTTPS-enabled) web server (E.g.: secure.example.com). If omitted, it is assumed to be the same as the web server name. </TD>
  <TD><?php echo !empty($params["xlite_https_host"])?$params["xlite_https_host"]:$params["xlite_http_host"]; ?></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>LiteCommerce web directory</B><BR>Path to LiteCommerce files within the web space of your web server (E.g.: /shop).</TD>
  <TD><?php echo $params["xlite_web_dir"] ?></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL server name</B><BR>Hostname or IP address of your MySQL server.</TD>
  <TD><?php echo $params["mysqlhost"] ?></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL database name</B><BR>The name of the database to use (if the database does not exist on the server, it will be created during the installation).</TD>
  <TD><?php echo $params["mysqlbase"] ?></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL username</B><BR>MySQL username. The user must have full access to the database specified above.</TD>
  <TD><?php echo $params["mysqluser"] ?></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>MySQL password</B><BR>Password for the above MySQL username.</TD>
  <TD><?php echo $params["mysqlpass"] ?></TD>
 </TR>
 
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Geographic areas</B><BR>Specify which geographic areas you want to be listed in the database.</TD>
  <TD>
   <SELECT name="params[states]">
    <OPTION value="US" selected>USA states</OPTION>
    <OPTION value="CA">Canadian provinces</OPTION>
    <OPTION value="GB">United Kingdom counties</OPTION>
	<OPTION value="US-CA">USA states &amp; Canadian provinces</OPTION>
	<OPTION value="US-CA-GB">USA states, Canadian provinces &amp; UK counties</OPTION>
    <OPTION value="">No states</OPTION>
   </SELECT>
  </TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Install sample catalog</B><BR>Specify whether you would like to setup sample categories and products?</TD>
  <TD>
   <SELECT name="params[demo]">
    <OPTION value=1 selected>Yes</OPTION>
    <OPTION value=0>No</OPTION>
   </SELECT>
  </TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Initialize LiteCommerce database</B><BR>Populate LiteCommerce database with initial data. Uncheck this field if you want to leave LiteCommerce database untouched (your database must contain valid LiteCommerce data tables in order for your online store to operate properly).</TD>
  <TD>
    <INPUT type=checkbox name="params[install_data]" value="Y" checked onClick="this.blur()">
  </TD>
 </TR>
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Move all images to the file system</B><BR>Image files can either be placed in the 'images' sub-directory of your LiteCommerce installation or stored in the database. Storing images in the database makes it easier to backup them, while leaving them as files helps to keep the database more compact.</TD>
  <TD>
  	<INPUT type=checkbox name="params[images_to_fs]" value="Y" checked onClick="this.blur()">
  </TD>
 </TR>

</TABLE>

<?php if ($ck_res) { ?><BR><?php message("Push the \"Next\" button below to begin the installation"); } ?>

</CENTER>

<BR>
<?php
		$error = !$ck_res;
		return false;
	}
}

function module_cfg_install_db_js_back() {
	global $params;
?>
	function step_back() {
		document.ifrm.current.value = "<?php echo ((!isset($params["mysqlhost"]) || $params["mysqlhost"] == "") ? "1" : "2"); ?>";
		document.ifrm.submit();

		return true;
	}
<?php
}

function module_cfg_install_db_js_next() {
?>
	function step_next() {
		for (var i = 0; i < document.ifrm.elements.length; i++) {

			if (document.ifrm.elements[i].name.search("xlite_http_host") != -1) {
				if (document.ifrm.elements[i].value == "") {
					alert("You must provide web server name");
					return false;
				}
			}

			if (document.ifrm.elements[i].name.search("mysqlhost") != -1) {
				if (document.ifrm.elements[i].value == "") {
					alert ("You must provide MySQL server name");
					return false;
				}
			}

			if (document.ifrm.elements[i].name.search("mysqluser") != -1) {
				if (document.ifrm.elements[i].value == "") {
					alert ("You must provide MySQL username");
					return false;
				}
			}

			if (document.ifrm.elements[i].name.search("mysqlbase") != -1) {
				if (document.ifrm.elements[i].value == "") {
					alert ("You must provide MySQL database name");
					return false;
				}
			}
		}
		return true;
	}
<?php
}

// end:  Cfg_install_db module }}}

// Install_db module {{{

function module_install_db(&$params) {
	global $error;
	$clrNumber = 2;
	//global $installation_auth_code;

?>
</TD>
</TR>
</TABLE>

<SCRIPT language="javascript">
	loaded = false;

	function refresh() {
		window.scroll(0, 100000);

		if (loaded == false)
			setTimeout('refresh()', 1000);
	}

	setTimeout('refresh()', 1000);
</SCRIPT>

<?php
	$ck_res = 1;
    global $config_file;


	$mylink = @mysql_connect($params["mysqlhost"], $params["mysqluser"], $params["mysqlpass"]);
	if (!$mylink) 
		$ck_res &= fatal_error("Cannot connect to MySQL server. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");
	else if (!@mysql_select_db($params["mysqlbase"]))
		$ck_res &= fatal_error("Cannot find database \"".$params["mysqlbase"]."\". This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");
	else if (!@is_writable($config_file))
		$ck_res &= fatal_error("Cannot open file \"$config_file\" for writing. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");
	else {
		@mysql_query("SET sql_mode='MYSQL40'");

// Updating config.php file

		echo "<BR><B>Updating $config_file file... </B><BR>\n"; flush();

		$res = change_config($params);
		echo status($res)."<BR>\n";

		if (!$res) 
			fatal_error("Can't open file \"$config_file\" for reading\\writing");

		$ck_res &= $res;


		if (!empty($params["install_data"])) { 

		echo "<BR><B>Creating tables...</B><BR>\n";

		//$ck_res &= query_upload("sql/dbclear.sql");
		$ck_res &= query_upload("sql/xlite_tables.sql");

		echo "<BR><B>Importing data...</B><BR>\n"; flush();

		$ck_res &= query_upload("sql/xlite_data.sql");

// Importing states

		if ($ck_res && !empty($params["states"])) {
			echo "<BR><B>Importing geographic areas...</B><BR>\n"; flush();
			$country_codes = explode("-",$params["states"]);
			foreach($country_codes as $country_code)
				$ck_res &= query_upload("sql/states_".$country_code.".sql");
		}

// Importing sample data

		if ($ck_res && $params["demo"] == 1) {
			echo "<BR><B>Importing sample categories and products...</B><BR>\n"; flush();

			$ck_res &= query_upload("sql/xlite_demo.sql");
		}

// Importing modules

        if ($ck_res) {

            echo "<BR><B>Importing modules...</B><BR>\n"; flush();

            $modulesDir = opendir("classes/XLite/Module");

            while (($dir = readdir($modulesDir)) !== false) {

            	if ($dir{0}!='.' && is_dir("classes/XLite/Module/$dir")) {

        	    	include_once 'classes/XLite/Module/' . $dir . '/Main.php';
            		$class = 'XLite_Module_' . $dir . '_Main';

                    echo "<BR>&nbsp;&nbsp;&nbsp;<B>$dir...</B><BR>\n"; flush();

            		mysql_query('REPLACE INTO xlite_modules SET name = \'' . $dir . '\', mutual_modules = \'' . implode(',', call_user_func(array($class, 'getMutualModules'))) . '\', type = \'' . call_user_func(array($class, 'getType')). '\'');

                    if (file_exists($sqlFile = 'classes/XLite/Module/' . $dir . '/install.sql')) {
                        $ck_res &= query_upload($sqlFile);
                    }
            	}
            }
        
            closedir($modulesDir);

        }

		if (!$ck_res)
			fatal_error("Fatal error encountered while importing database. The possible reason is insufficient access rights to the database.<BR> This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");

        // Move all images to the file system XXXXX
        if ( !empty($params["images_to_fs"]) )
        {
            echo "<BR><B>Moving product images to the file system...</B><BR>";
            move_images_to_fs("xlite_products", "product_id", "image", "pi_");

            echo "<BR><B>Moving product thumbnails to the file system...</B><BR>";
            move_images_to_fs("xlite_products", "product_id", "thumbnail", "pt_");

            echo "<BR><B>Moving category images to the file system...</B><BR>";
            move_images_to_fs("xlite_categories", "category_id", "image", "ci_");
        }


		}	# if (empty($params["install_data"]))
		else {
			if (!$ck_res)
				fatal_error("Fatal error encountered while updating $config_file file. The possible reason is the wrong file permissions.<BR> This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");
            else    
                echo "<BR><BR><BR><BR><BR>";
		}
	}
?>

<TABLE class="TableTop" width="100%" border=0 cellspacing=0 cellpadding=0>

<TR>
<TD>

<CENTER>
<?php if ($ck_res) { ?><BR><?php message("Push the \"Next\" button below to continue"); } ?>
</CENTER>

<BR>

<SCRIPT language="javascript">
	loaded = true;
</SCRIPT>

<?php
	$error = !$ck_res;
	return false;
} // }}} 

// Install_dirs module {{{
function module_install_dirs(&$params) {
	global $directories_to_create, $files_to_create, $templates_repository, $schemas_repository, $error, $default_skin, $default_locale, $templates_directory, $config_file, $others_directories;
	$clrNumber = 2;

?>
</TD>
</TR>
</TABLE>

<SCRIPT language="javascript">
	loaded = false;

	function refresh() {
		window.scroll(0, 100000);

		if (loaded == false)
			setTimeout('refresh()', 1000);
	}

	setTimeout('refresh()', 1000);
</SCRIPT>

<?php
	$ck_res = 1;

	echo "<BR><B>Creating directories...</B><BR>\n";

	$ck_res &= create_dirs($directories_to_create);

    chmod_others_directories($others_directories);

    echo "<BR><B>Creating .htaccess files...</B><BR>\n";

    $ck_res &= create_htaccess_files($files_to_create);

	echo "<BR><B>Copying templates...</B><BR>\n";

		
	$ck_res &= copy_files($templates_repository, "", $templates_directory);

	if (!$ck_res) {
		fatal_error("Fatal error encountered while creating directories, probably because of incorrect directory permissions. This unexpected error has canceled the installation. To install the software, please correct the problem and start the installation again.");
    }

?>

<TABLE class="TableTop" width="100%" border=0 cellspacing=0 cellpadding=0>

<TR>
<TD>

<CENTER>
<?php if ($ck_res) { ?><BR><?php message("Push the \"Next\" button below to continue"); } ?>
</CENTER>

<INPUT type=hidden name="ck_res" value="<?php echo (int)$ck_res ?>">

<?php if (is_null($params["new_installation"])) 
	{ 
?>
<TR>
	<TD colspan="2">
	<input type="hidden" name="params[force_current]" value="<?php echo get_step('install_done')?>">	
	</TD>
</TR>	
<?php
	}	
?>	
	

<BR>

<SCRIPT language="javascript">
	loaded = true;
</SCRIPT>

<?php
	$error = !$ck_res;
	return false;
} // }}}

// Configure create_admin module {{{
function module_cfg_create_admin(&$params) {
    global $error;
	$clrNumber = 1;

    $login = isset($params["login"]) ? $params["login"] : "";
    $password = isset($params["password"]) ? $params["password"] : "";
    $confirm = isset($params["confirm_password"]) ? $params["confirm_password"] : "";
?>

<CENTER>
<P>
 <B><FONT color="darkgreen">Creating administrator profile</FONT></B>
</P>


<TABLE width="60%" border=0 cellpadding=4>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
   <td colspan=2>
<p align=justify>E-mail and password that you provide on this screen will be used to create primary administrator profile. Use them as credentials to access the Administrator Zone of your online store.</p>
   <p>
   </td>
 </tr>
 
 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD width="50%"><B>E-mail</B><BR>E-mail address of the store administrator</TD>
  <TD><INPUT type="text" name="params[login]" size=30 value="<?php echo $login; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Password</B><BR></TD>
  <TD><INPUT type="password" name="params[password]" size=30 value="<?php echo $password; ?>"></TD>
 </TR>

 <TR class="Clr<?php echo $clrNumber; $clrNumber = ($clrNumber == 2) ? 1 : 2; ?>">
  <TD><B>Confirm password</B><BR></TD>
  <TD><INPUT type="password" name="params[confirm_password]" size=30 value="<?php echo $confirm; ?>"></TD>
 </TR>

<?php 
	if (is_null($params["new_installation"])) {
?>
	<TR>
		<TD colspan="2">
	<input type="hidden" name="params[force_current]" value="<?php echo get_step("install_done")?>">	
		</TD>
	</TR>	
<?php
	}
?>	
</TABLE>
<P>

<?php
}

// cfg_create_admin module "Next" button validator {{{
function module_cfg_create_admin_js_next() {
?>
    function step_next() {

        // validate login
        //
        if (!checkEmailAddress(document.ifrm.elements['params[login]'])) {
            return false;
        }

        // validate password and password confirm
        //
        if (document.ifrm.elements['params[password]'].value == "") {
            alert('Please, enter non-empty password');
            return false;
        }    
        if (document.ifrm.elements['params[confirm_password]'].value == "") {
            alert('Please, enter non-empty password confirmation');
            return false;
        }    
        if (document.ifrm.elements['params[password]'].value != document.ifrm.elements['params[confirm_password]'].value) {
            alert("Password doesn't match confirmation!");
            return false;
        }
        return true;
    }

    function checkEmailAddress(field) {

        var goodEmail = field.value.search(/^(\S+@)[^\.][A-Za-z0-9_\-\.]+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\.biz)|(\.us)|(\.bizz)|(\.coop)|(\..{2,2}))[ ]*$/gi);

        if (goodEmail != -1) {
            return true;
        } else {
            alert("Please, specify a valid e-mail address!");
            field.focus();
            field.select();
            return false;
    }
}

<?php
} // }}}

// end cfg_create_admin module }}}

// Run create_admin module {{{
function module_create_admin(&$params) {
    global $error, $config_file;
	$clrNumber = 2;

    // check whether mysql params set
    if (!isset($params["mysqlhost"])) {
        // re-configure the default admin. Attempt to read mysql DB params
        // from config file
        if (!$options = @parse_ini_file($config_file)) {
            // report failure
            return fatal_error("Installer couldn't read MySQL config file $config_file, please, try to reinstall LiteCommerce");
        }
        // fill the params array
        $params["mysqlhost"] = $options["hostspec"];
        $params["mysqlbase"] = $options["database"];
        $params["mysqluser"] = $options["username"];
        $params["mysqlpass"] = $options["password"];
    }
    
    $mylink = @mysql_connect($params["mysqlhost"], $params["mysqluser"], $params["mysqlpass"]);
    if (!$mylink) {
        $error = mysql_error();
        return fatal_error("Can't connect to MySQL server you specified.  Press 'BACK' button and review MySQL server settings you provided.");
    } elseif (!@mysql_select_db($params["mysqlbase"])) {
        $error = mysql_error();
        return fatal_error("Can't find database \"".$params["mysqlbase"]."\". Choose another database name, create the database manually or ask your hosting provider to do it for you.");
    } else {
		@mysql_query("SET sql_mode='MYSQL40'");

        $login = get_magic_quotes_gpc() ? trim(stripslashes($params["login"])) : $params["login"];

        // check for profile
        $sql = "SELECT COUNT(*) FROM xlite_profiles WHERE login='$login'";
        $query = "";

        if (!$result = @mysql_query($sql)) {
            // an error has occured
            $error = mysql_error();
            return fatal_error("Invalid SQL query: $sql");
        }    
        $data = mysql_fetch_row($result);

        $password = md5(get_magic_quotes_gpc() ? trim(stripslashes($params["password"])) : $params["password"]);

        if ($data[0]) {  // account already exists
            $sql = "SELECT profile_id FROM xlite_profiles WHERE login='$login' AND access_level='100'";
            if (!$result = @mysql_query($sql)) {
                // an error has occured
                $error = mysql_error();
                return fatal_error("Invalid SQL query: $sql");
            }    
            $data = mysql_fetch_row($result);
            // update profile
            if ($data[0]) {  // account already exists
            	$profile_id = $data[0];
            	$query = "UPDATE xlite_profiles SET password='$password', access_level='100', order_id='0', status='E' WHERE profile_id='$profile_id'";
            } else {
            	$query = "UPDATE xlite_profiles SET password='$password', access_level='100', order_id='0', status='E' WHERE login='$login'";
            }

            echo "<BR><B>Updating primary administrator profile...</B><BR>\n";
        } else {
            // register default admin account
            $query = "INSERT INTO xlite_profiles (".
                     "    login, password, access_level, status".
                     ") VALUES ('$login', '$password', 100, 'E')";
            echo "<BR><B>Registering primary administrator profile...</B><BR>";
        }
        
        if (!$result = mysql_query($query)) {
            // an error has occured
            echo "<FONT color=red>[FAILED]</FONT>";
            $error = mysql_error();
            return fatal_error("Invalid SQL query: $sql");
        } else {
            echo "<FONT color=green>[OK]</FONT>";
        }

        mysql_close($mylink);
		/*
?>        
<CENTER>
<?php  message("Push the \"Next\" button below to continue"); ?>
</CENTER>
<BR>
<?php
*/
    }
} // }}}

// Install_done module {{{

function module_install_done(&$params) {
	global $error, $templates_repository, $config_file;
	$clrNumber = 2;

    // if authcode IS NULL, then this is probably the first install, skip
    if (isset($params["auth_code"]) && isset($params["login"]) && isset($params["password"]) && isset($params["confirm_password"]) && strlen($params["login"].$params["password"].$params["confirm_password"]) > 0) {
        // create/update admin account from the previous step
        module_create_admin($params);
    }

    // save authcode for the further install runs first
    save_authcode($params);
    $authcode = get_authcode();
    
	$install_name = md5(uniqid(rand(),true)).".php";
	@rename("install.php", $install_name);
	@clearstatcache();
	$success_rename = false;
	if (!@file_exists("install.php") && @file_exists($install_name)) {
		$success_rename = true;
	}
    
    // new new_installation
    $regime = 1;
    if (isset($params['start_at'])) {
        if ($params['start_at'] === '4') {
            $regime = 2;
        } elseif($params['start_at'] === '6') {
            $regime = 3;
        }
    }

    if ($success_rename) {
    	$install_rename = "To ensure the security of your LiteCommerce installation, the file \"install.php\" has been renamed to \"" . $install_name . "\".";
    } else {
    	$install_rename = "The install.php script could not be renamed! To ensure the security of your LiteCommerce installation and prevent the unallowed use of this script, you should manually rename or delete it.";
    }

    if ($regime === 1) { // {{{
		$message =<<<EOF
Congratulations!

LiteCommerce software has been successfully installed and is now available at the following URLs:

CUSTOMER ZONE (FRONT-END)
     http://$params[xlite_http_host]$params[xlite_web_dir]/cart.php

ADMINISTRATOR ZONE (BACKOFFICE)
     http://$params[xlite_http_host]$params[xlite_web_dir]/admin.php
     Login (e-mail): $params[login]
     Password:       $params[password]

$install_rename

Now, if you choose to change your store's skin set, add a new admin account or re-install LiteCommerce, you should rename the file "$install_name" back to "install.php" and open the following URL in your browser:
     http://$params[xlite_http_host]$params[xlite_web_dir]/install.php

Auth code for running install.php script is: $authcode


Thank you for choosing LiteCommerce shopping system!

--
LiteCommerce Installation Wizard
EOF;

	    @mail($params["login"], "LiteCommerce installation complete", $message,
	    "From: \"LiteCommerce software\" <" . $params["login"] . ">\r\n" .
 		"X-Mailer: PHP");
	
        if (check_ftp()&&isset($params['ftp_enabled'])) {
            $ftp_connection = ftp_init();
            if ($ftp_connection) {
                ftp_chdir($ftp_connection,$params['ftp_dir']);
                if (isset($params['set_chmod']) && $params['set_chmod'] == '1') {
    				if($suphp_mode == "0") {
                    	ftp_site($ftp_connection,"CHMOD 0755 .");
                    }
                }
    			if($suphp_mode == "0") {
                	ftp_site($ftp_connection,"CHMOD 0644 etc/config.php");
                	ftp_site($ftp_connection,"CHMOD 0644 LICENSE");
                }
            }
        }
    
        $perms = array();
        global $others_directories;
        global $suphp_mode;
        if (!(LC_OS_CODE === 'win')) {
            if (@is_writable(".")) $perms[] = "&gt; chmod 755 .";
            if (@is_writable($config_file)) $perms[] = "&gt; chmod 644 $config_file";
            if (!@is_writable("cart.html")) $perms[] = "&gt; chmod 666 cart.html";
        }
    } // 1 }}}
?>
<CENTER>
<H3><?php

if ($regime === 2) {
    message("The re-installation of the skin files is complete.<br><br>" . $install_rename);
} elseif($regime === 3) {
    message("The configuration of the primary administrator account is complete.<br><br>" . $install_rename);
} else {
    message("Installation complete.");
}

?></H3>
</CENTER>

<P>To ensure the security of your LiteCommerce installation, the file "install.php" has been renamed to "<?php echo $install_name; ?>".</P>

<P>Now, if you choose to change your store's skin set, add a new admin account or re-install LiteCommerce, you should rename the file "<?php echo $install_name; ?>" back to "install.php"</P>

<?php
    if ($regime === 1) { // {{{
?>
<P>Your auth code for running install.php in the future is: <B><?php print get_authcode();  ?>&nbsp;&nbsp;&nbsp;- PLEASE WRITE THIS CODE DOWN UNLESS YOU ARE GOING TO REMOVE <?php echo $install_name; ?></B></P>

<P>If you do not need this functionality, you can delete '<?php echo $install_name; ?>' installation script.</P> 

<P>
<?php

	    // remint to restore permissions
	    if (substr(PHP_OS, 0, 3) != 'WIN') {
		    if (count($perms) > 0) {
?>

<P>
Before you proceed using LiteCommerce shopping system software, please set the following secure file permissions:<BR><BR>
<FONT color="darkblue">
<?php 
			    foreach ($perms as $p) {
    			    echo $p . "<BR>";
			    }
		    }
	    }
?>
</FONT>
<?php
    } // }}}
?>
<BR>
<BR>
<?php
    if ($regime > 1) {
        echo 'You can select one of the following URLs:<BR>';
    } else {    
        echo 'LiteCommerce software has been successfully installed and is now available at the following URLs:<BR>';        
    }
?>
<OL>
<LI><U><A href="cart.php" style="COLOR: #000055; TEXT-DECORATION: underline;" target="_blank"><b>CUSTOMER ZONE (FRONT-END): cart.php</b></A></U></LI>
<P>
<LI><U><A href="admin.php" style="COLOR: #000055; TEXT-DECORATION: underline;" target="_blank"><b>ADMINISTRATOR ZONE (BACKOFFICE): admin.php</b></A></U><BR></LI>
<P>
<LI><A href="quickstart/index.html" target="_blank" style="COLOR: #000055; TEXT-DECORATION: underline;"><b>QUICK START WIZARD</b></A>&nbsp;will help you prepare your store for going live by guiding you through the main steps LiteCommerce shipping system setup.<BR></LI>
</OL>
<?php
	return false;
} // }}}

// END MODULES }}}

?>
