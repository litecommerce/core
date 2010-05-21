{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<!-- [begin] -->
<HTML>
<HEAD>
    <title>LiteCommerce online store builder</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; {charset}">
    <meta name="ROBOTS" content="NOINDEX">
    <meta name="ROBOTS" content="NOFOLLOW">
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
</HEAD>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" {if:mode=#print#}OnLoad="window.print();"{end:}>
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
<TR>
<TD valign=top>
    <TABLE border="0" width="100%" cellpadding="0" cellspacing="0" valign=top>
    <TR class="displayPageHeader" height="18">
        <TD align=left class="displayPageHeader" valign=middle width="50%">&nbsp;&nbsp;&nbsp;LiteCommerce</TD>
        <TD align=right class="displayPageHeader" valign=middle width="50%">Version: {config.Version.version}&nbsp;&nbsp;&nbsp;</TD>
    </TR>
    </TABLE>
</TD>
</TR>
<TR>
<TD height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<TR>
<TD class="displayPageHeader" height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<tr>
    <td>&nbsp;</td>
</tr>
</TABLE>

<!-- [main_view] -->
{if:mode=#help#}
<table border="0" width="100%" cellpadding="5" cellspacing="0" align="center">
	<tr>
	<td>
<b>Account Type description</b>
<p>
UPS has several classifications based on your account-type choice:
<p>
<i>Daily Pickup</i> - a UPS driver will come to your location each day (weekly service charge applies)
<p>
<i>Occasional Shipper</i> - It is the responsibility of the merchant to drop the package into the UPS System
<p>
<i>Suggested Retail Rate</i> - Paying for the shipments at the UPS Store (Label is actually created by the UPS Store)
<p>
With a Daily Pickup Account, a UPS driver will make a regular stop at your location each day, Monday through Friday, to pick up all package types, including:
<ul>
	<li>Ground shipments</li>
	<li>Air shipments</li>
	<li>International shipments</li>
</ul>
<b>NOTE:</b> A weekly service charge applies, and Daily Rates will be billed to your UPS Account (see the <b>UPS Rates</b> section under Terms and Conditions of Service in the online UPS Service Guide).
<p>
With an Occasional Account, you decide if and when you need to schedule a UPS driver to pickup your shipments. Once you have prepared your shipments, you have the following options:
<p>
<ul>
	<li>Schedule an On Call Pickup to have your shipment picked up by a UPS driver for a nominal fee.</li>
	<li>Hand your shipments to any UPS driver in your area.</li>
	<li>Take your shipments to The UPS Store, a UPS Customer Center, or an Authorized Shipping Outlet.</li>
	<li>Drop off your Air, Ground or International shipments at one of over 60,000 UPS locations.</li>
</ul>
<b>NOTE:</b> No weekly service charge applies. UPS On Demand rates will be billed to your UPS Account (see the <b>UPS Rates</b> section under Terms and Conditions of Service in the online UPS Service Guide).
	</td>
</tr>
<tr>
	<td align="right"><input type="submit" value="Close window" class="DialogMainButton" OnClick="window.close();"></td>
</tr>
</TABLE>
{else:}
{licenseText:h}
{end:}
<!-- [/main_view] -->

<br>

</BODY>
</HTML>
<!-- [/end] -->
