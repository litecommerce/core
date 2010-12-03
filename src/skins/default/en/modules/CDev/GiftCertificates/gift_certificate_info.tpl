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
<table border=0 width=100% cellpadding=2 cellspacing=1>
<tr>
    <td width=20% align=right>ID:</td>
    <td>&nbsp;</td>
    <td width=80% align=left>
        <b>{gc.gcid}</b>
    </td>
</tr>

<tr>
    <td width=20% align=right>From:</td>
    <td>&nbsp;</td>
    <td width=80% align=left>
        <b>{gc.purchaser}</b>
    </td>
</tr>

<tr>
    <td align=right>To:</td>
    <td>&nbsp;</td>
    <td align=left>
        <b>{gc.recipient}</b>
    </td>
</tr>

<tr>
    <td align=right>Message:</td>
    <td>&nbsp;</td>
    <td align=left>
        <table border=0 cellpadding=0 cellspacing=0><tr><td bgcolor=#CCCCCC>
        <table border=0 cellpadding=5 cellspacing=1 width=100%><tr><td bgcolor=#FFFFFF>
        {gc.message}
        </td></tr></table>
        </td></tr></table>
    </td>
</tr>

<tr>
    <td align=right>Amount:</td>
    <td>&nbsp;</td>
    <td align=left>
        <b>{price_format(gc,#amount#):h}</b>
    </td>
</tr>

<tr><td colspan=3>&nbsp;</td></tr>

<tr IF="isSelected(gc,#send_via#,#E#)">
    <td nowrap align=right>E-mail:</td>
    <td>&nbsp;</td>
    <td align=left>
        <b>{gc.recipient_email}</b>
    </td>
</tr>

<tbody IF="isSelected(gc,#send_via#,#P#)">
    <tr valign=middle>
        <td height=20 colspan=3><b>Postal Address</b><hr size=1 noshade></td>
    </tr>

    <tr>
        <td nowrap align=right>First name:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_firstname}</b>
        </td>
    </tr>

    <tr>
        <td nowrap align=right>Last name:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_lastname}</b>
        </td>
    </tr>

    <tr>
        <td nowrap align=right>Address:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_address}</b>
        </td>
    </tr>

    <tr>
        <td nowrap align=right>City:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_city}</b>
        </td>
    </tr>

    <tr>
        <td nowrap align=right>ZIP code:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_zipcode}</b>
        </td>
    </tr>

    <tr>
        <td nowrap align=right>State:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_state_name}</b>
        </td>
    </tr>

    <tr>
        <td nowrap align=right>Country:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_country_name}</b>
        </td>
    </tr>

    <tr>
        <td nowrap align=right>Phone:</td>
        <td>&nbsp;</td>
        <td align=left>
            <b>{gc.recipient_phone}</b>
        </td>
    </tr>
</tbody>
</table>

<p>
<center>
<a href="javascript: history.go(-1)"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Go back</a>
</center>
