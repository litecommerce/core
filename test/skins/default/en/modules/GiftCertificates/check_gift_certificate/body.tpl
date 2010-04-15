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
If you have an existing Gift certificate you can check its current amount and status using the form below.
<br><br>
<font class="ErrorMessage" IF="notFound">The specified Gift certificate is not found.</font>
<FORM name="gccheckform" action="{buildURL(#check_gift_certificate#)}" method="GET">
<table border="0"><tr><td>
Gift certificate:&nbsp;
</td><td><INPUT type="text" size="25" maxlength="16" name="gcid" value="{foundgc.gcid:r}">
</td><td><widget class="XLite_View_Button" label="Find" href="javascript: document.gccheckform.submit();">
</td></tr></table>
</FORM>

<table border="0" IF="found">
<tr><td colspan="2">
<hr size="1" noshade>
</td></tr>
<tr>
<td><b>Gift Certificate ID:</b></td>
<td>{foundgc.gcid}</td>
</tr>
<tr>
<td><b>Amount:</b></td>
<td>{price_format(foundgc,#amount#):h}</td>
</tr>
<tr>
<td><b>Available:</b></td>
<td>{price_format(foundgc,#debit#):h}</td>
</tr>
<tr>
<td><b>Status:</b></td>
<td>
<span IF="isSelected(foundgc,#status#,#P#)">Pending</span>
<span IF="isSelected(foundgc,#status#,#A#)">Active</span>
<span IF="isSelected(foundgc,#status#,#D#)">Disabled</span>
<span IF="isSelected(foundgc,#status#,#U#)">Used</span>
</td>
</tr>
</table>
