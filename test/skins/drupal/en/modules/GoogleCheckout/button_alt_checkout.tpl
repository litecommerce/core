{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table border="0" cellpadding="0" cellspacing="0">
<tr><td align="center">
{* ORIGINAL BUTTON TEMPLATE *}
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<TD><IMG src="images/but_left.gif" width="8" height="22" border="0" alt=""></TD>
<TD IF="img" class="CommonButtonBG" nowrap><a href="{href}" target="{hrefTarget:r}"><IMG src="images/{img}" width="11" height="12" border="0" alt="{label:h}"></a></TD>
<TD class="CommonButtonBG" nowrap>&nbsp;&nbsp;<a href="{href}" target="{hrefTarget:r}" class="ButtonLink"><FONT IF="label" class="Button">{label:h}</FONT></a>&nbsp;&nbsp;</TD>
<TD><IMG src="images/but_right.gif" width="8" height="22" border="0" alt=""></TD>
</TR>
</TABLE>
{* /ORIGINAL BUTTON TEMPLATE *}
</td></tr>
<tr><td align="center">or use</td></tr>
<tr>
	<td align="center" IF="googleAllowPay"><a href="cart.php?target=googlecheckout&action=checkout" onClick="this.blur()"><img src="{googleCheckoutButtonUrl}" width="160" height="43" border="0" alt="" /></a></td>
	<td align="center" IF="!googleAllowPay"><img src="{googleCheckoutButtonUrl}" width="160" height="43" border="0" alt="" /></td>
</tr>
</table>
