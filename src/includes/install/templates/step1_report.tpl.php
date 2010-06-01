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
 * LiteCommerce (standalone edition) web installation wizard: Report page 
 * 
 * @package LiteCommerce
 * @see     ____class_see____
 * @since   3.0.0
 */

if (!defined('XLITE_INSTALL_MODE')) {
	die('Incorrect call of the script. Stopped.');
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

<?php include LC_ROOT_DIR . 'includes/install/templates/common_js_code.js.php'; ?>

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

<?php

if (!$is_original) {

?>

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

<?php

}

?>

<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>

<?php

 /* common header */

?>

<TR>
   <TD class="Head" background="skins_original/admin/en/images/head_demo_01.gif" WIDTH=494 HEIGHT=73><IMG SRC="skins_original/admin/en/images/logo_demo.gif" BORDER="0"><br><IMG SRC="skins_original/admin/en/images/spacer.gif" WIDTH=494 HEIGHT=1 BORDER="0"></TD>
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
<?php

if (!$is_original) {
			
?>
<SCRIPT language="javascript">showWaitingAlert(true, "report_");</SCRIPT>
<?php

	ob_start();
	module_check_cfg($_POST['params']);
	ob_end_clean();

	$report = make_check_report($requirements);
}

if ($report) {
	$report = (($is_original) ? '[original report]' : '[replicated report]') . "\n\n". $report;

} else {
	$report = 'Report generation failed.';
}

?>

<FORM method="POST" name="report_form" action="https://secure.qtmsoft.com/customer.php?target=customer_info&action=install_feedback_report">

<input type="hidden" name="product_type" value="LC3" />

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
<?php

if (!$is_original) {

?>
	<SCRIPT language="javascript">showWaitingAlert(false, "report_");</SCRIPT>
<?php

}

?>
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

