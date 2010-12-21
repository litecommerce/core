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


if (!(basename(__FILE__) === 'install.php')) { // it is not install.php file
    die();
}

define ('XLITE_INSTALL_MODE', 1);
define('LC_DO_NOT_REBUILD_CACHE', true);

if (version_compare(phpversion(), '5.3.0') < 0) {
    die('LiteCommerce cannot start on PHP version earlier than 5.3.0 (' . phpversion(). ' is currently used)');
}

// Init installation process
if (!file_exists($includeFuncsFile = realpath(dirname(__FILE__)) . '/Includes/install/init.php')) {
    die('Fatal error: Couldn\'t find file ' . $includeFuncsFile);
}

require_once $includeFuncsFile;


// Include script with main installation functions
if (!file_exists($includeFuncsFile = realpath(dirname(__FILE__)) . '/Includes/install/install.php')) {
    die('Fatal error: Couldn\'t find file ' . $includeFuncsFile);
}

require_once $includeFuncsFile;


// Link auto-globals
if (empty($HTTP_SERVER_VARS)) {
	$HTTP_GET_VARS = &$_GET;
	$HTTP_POST_VARS = &$_POST;
	$HTTP_SERVER_VARS = &$_SERVER;
}

/*
 * Prepare data for report file
 */

// Prepare report ID
if (isset($_REQUEST['ruid']) && $_REQUEST['ruid']) {
    $report_uid = $_REQUEST["ruid"];

} else {
	$report_uid = substr(md5(uniqid(time())), 0, 12);
}

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

$reportFName = $tmpdir . LC_DS . 'check_report_' . $report_uid . '.txt';

// Installation modules (steps)
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
                'xlite_http_host',
                'xlite_https_host',
                'xlite_web_dir',
                'mysqlhost',
                'mysqlbase',
                'mysqluser',
                'mysqlpass',
                'mysqlport',
                'mysqlsock',
            )
		),
	array( // 2
			"name"          => "cfg_install_db",
			"comment"       => "Preparing to install LiteCommerce database",
            "auth_required" => true,
			"js_back"       => 1,
			"js_next"       => 1,
		    "remove_params" => array(
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


/*
 * Process service requests
 */
if (isset($HTTP_GET_VARS['target']) && $HTTP_GET_VARS['target'] == 'install') {

	// Loopback action
	if (isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'loopback_test') {
		die('LOOPBACK-TEST-OK');
	}

    if (isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'http_host') {
        die($_SERVER['HTTP_HOST']);
    }

	// Memory test action
	if (isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'memory_test' && isset($HTTP_GET_VARS['size'])) {
        $size = intval($HTTP_GET_VARS['size']);

		if ($size <= 0 || $size > 64) {
			die('MEMORY-TEST-INVALID-PARAMS');
		}

		if (!function_exists('memory_get_usage')) {
			die("MEMORY-TEST-SKIPPED\nReason: memory_get_usage() is disabled on your hosting.");
		}

		// check memory limit set
        $res = @ini_get('memory_limit');

        if (!check_memory_limit($res, $size . 'M')) {
            die('MEMORY-TEST-LIMIT-FAILED');
        }

		$size -= (ceil(memory_get_usage() / (1024*1024)) + 1);

        $array = array();

		for ($i = 0; $i < $size; $i++) {
			$array[] = str_repeat('*', 1024 * 1024);
		}

		die('MEMORY-TEST-OK');
	}

    // Recursion test action
	if (isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'recursion_test') {
		recursion_depth_test(1);
		die('RECURSION-TEST-OK');
	}

    // Send report
	if (isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'send_report') {
		$is_original = true;
		$report = '';
        $report = @file_get_contents($reportFName);

		if (!$report) {
			$is_original = false;
        }

        include_once LC_ROOT_DIR . 'Includes/install/templates/step1_report.tpl.php';

		die();
	}
}

// First error flag
$first_error = null;

// Error flag
$error = false;

// Check copyright file
define('COPYRIGHT_FILE', './LICENSE.txt');
define('COPYRIGHT_EXISTS', @file_exists(COPYRIGHT_FILE));

$current = 0;
$params = array();

if (COPYRIGHT_EXISTS) {
    $current = (isset($_POST['current']) ? intval($_POST['current']) : 0);
    $params = (isset($_POST['params']) && is_array($_POST['params']) ? $_POST['params'] : array());
}

// Process 'Go back' action: remove params
if (isset($_POST['go_back']) && $_POST['go_back'] === '1') {

    for ($i = $current; $i < count($modules); $i++) {

        for ($j = 0; $j < count($modules[$i]['remove_params']); $j++) {

            if(isset($params[$modules[$i]['remove_params'][$j]])) {
                unset($params[$modules[$i]['remove_params'][$j]]);
            }
        }
    }
}

// Force current step processing
if (isset($params['force_current']) && !isset($params['start_at']) ) {
    $params['start_at'] = $params['force_current'];
}

if (isset($params['force_current']) && $params['force_current'] == get_step('check_cfg')) {
	$params['new_installation'] = $params['force_current'];
	unset($params['force_current']);
}

if ($current < 0 || $current >= count($modules)) {
	die('Fatal error: Invalid current step. Stopped.');
}

// check for the pre- and post- methods

if ($current) {

    if (isset($modules[$current - 1]['post_func'])) {

		check_authcode($params);
        $func = 'module_' . $modules[$current - 1]['name'] . '_post_func';

        if (function_exists($func)) {
            $func();

        } else {
            die('Internal error: function ' . $func . '() not found');
        }
    }
}

// should the current be set here?
if (isset($params['force_current']) && (isset($_POST['go_back']) && $_POST['go_back'] === '0') ) {
	$current = $params['force_current'];
	check_authcode($params);
	unset($params['force_current']);
}

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

    $protocolType = (isHTTPS() ? 'https://' : 'http://');
?>

.background {
	BACKGROUND-COLOR: #FFFFFF; BACKGROUND-IMAGE: URL("<?php echo $protocolType; ?>www.litecommerce.com/img/logo_lite.gif");
}

<?php

} else {

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
    FONT-SIZE: 20px; COLOR: #2d69ab; TEXT-DECORATION: none;
}
.HeadSteps {
    FONT-SIZE: 11px; TEXT-DECORATION: none;
}
.WelcomeTitle {
    FONT-SIZE: 11px;
    COLOR: #000000;
    TEXT-DECORATION: none;
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

<?php include LC_ROOT_DIR . 'Includes/install/templates/common_js_code.js.php'; ?>

<SCRIPT language="javascript">

<?php
// show module's pertinent scripts

// 'back' button's script
switch ($modules[$current]['js_back']) {
	case 0:
		default_js_back();
		break;
	case 1:
		$func = 'module_' . $modules[$current]['name'] . '_js_back';
		$func();
		break;
	default:
		die('Fatal error: Invalid js_back value for module ' . $modules[$current]['name']);
}

// 'next' button's script
switch ($modules[$current]['js_next']) {
	case 0:
		default_js_next();
		break;
	case 1:
		$func = 'module_' . $modules[$current]['name'] . '_js_next';
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

<!-- [top] -->
<table cellspacing="0" width="100%" style="background-color: #f4f4f4; border-bottom: 1px solid #e9ecf3;">
<tr>
   <td style="padding: 10px;"><img src="skins_original/admin/en/images/logo.png" alt="" /></td>
    <td style="white-space: nowrap;">
      <div style="font-size: 24px;"><span style="color: #2d69ab;">Lite</span><span style="color: #676767;">Commerce</span></div>
      <div>Version: <?php echo LC_VERSION; ?></div>
    </td>
   <td align="right" valign="middle" nowrap="nowrap" width="100%" style="padding-right: 20px;">
    <span class="HeadTitle">Installation Wizard</span><br />
   <span class="HeadSteps">Step <?php echo $current ?>: <?php echo $modules[$current]['comment'] ?></span>
   </td>
</tr>
</table>

<br />
<!-- [/top] -->

<?php

/* common header */

?>

<NOSCRIPT>
    <br>
    <DIV class="ErrorMessage">This installer requires JavaScript to function properly.<br>Please enable Javascript in your web browser.</DIV>
    <br>
</NOSCRIPT>

<TABLE class="TableTop" width="90%" border=0 cellspacing=0 cellpadding=0 align=center>

<?php

/* common form */


// check whether the form encoding type is set
$enctype = (isset($modules[$current]['form_enctype']) ? 'enctype="' . $modules[$current]['form_enctype'] . '"'  : '');

?>

<FORM method="POST" name="ifrm" action="<?php echo $HTTP_SERVER_VARS['REQUEST_URI'] ?>" <?php print $enctype ?>>

<TR>
<TD valign="middle">

<?php

// get full function's name to call the corresponding module
$func = 'module_' . $modules[$current]['name'];

// check the auth code if required
if ($modules[$current]['auth_required']) {
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

if (!$res) {
    $current += 1;
}

if ($current < count($modules)) {

?>

<TR>
 <TD align="center">

<?php

    if (!empty($params)) {

	    foreach ($params as $key => $val) {

?>

  <INPUT type=hidden name="params[<?php echo $key ?>]" value="<?php echo $val ?>">

<?php
    
        }
    }

    if ($report_uid) {

?>

    <INPUT type="hidden" name="ruid" value="<?php echo $report_uid; ?>" />

<?php
    }
?>

  <INPUT type="hidden" name="go_back" value="0" />
  <INPUT type=hidden name="current" value="<?php echo $current ?>" />
  <INPUT type=button value="&lt; Back"<?php echo ($prev > 0 ? '' : ' disabled') ?> onClick="javascript:document.ifrm.go_back.value='1'; return step_back();" />

<?php

    if (isset($tryAgain) && true == $tryAgain) {

 ?>

  <INPUT name="try_again" type="button" value="Try again" onClick="javascript:document.ifrm.go_back.value='1'; document.ifrm.current.value='1'; document.ifrm.submit();" />

<?php
       
    }

?>

  <INPUT name="next_button" type="button" value="Next &gt;"<?php echo ($error || $current == get_step('check_cfg') ? ' disabled="disabled"' : ''); ?> onClick="javascript: if (step_next()) { ifrm.submit(); return true; } else { return false; }" />

 </td>
</TR>

<?php

}

?>

</FORM>

<?php

/* common bottom */

?>

</TABLE>

</TD>
</TR>
</TR>
<TD valign="bottom">

<HR size=1 noshade>
<DIV ALIGN=right style="margin-bottom: 8px;">
  <FONT size=1>Copyright &copy; 2003 - 2010 <A href="http://www.qtmsoft.com/">Creative Development</A>&nbsp;&nbsp;</FONT>
</DIV>

</TD>
</TR>
</TABLE>

</BODY>
</HTML>

