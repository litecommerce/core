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
<div align="right"><table cellpadding="0" cellspacing="0">
<tr>
	<td valign="top" style="padding-top: 3px; padding-right: 4px;" IF="!google_checkout_profile"><a href="cart.php?target=checkout" onClick="this.blur()"><img src="images/modules/GoogleCheckout/checkout{googleCheckoutButtonImgNum}.gif" width="79" height="19" border="0" alt=""></a></td>
	<td valign="top" style="padding-top: 5px;" IF="google_checkout_profile">
	<span IF="mode=#login#">
	<b>Login to your account</b>
	</span>
	<span IF="target=#login#">
	<b>Login to your account</b>
	</span>
	<span IF="mode=#register#">
	<b>Create a customer account</b>
	</span>
	</td>
	<td>&nbsp;&nbsp;</td>
	<td valign="top" style="padding-top: 5px;">or use</td>
	<td>&nbsp;&nbsp;</td>
	<td><a href="cart.php?target=googlecheckout&action=checkout" onClick="this.blur()"><img src="{googleCheckoutButtonUrl}" width="160" height="43" border="0" alt="" /></a></td>
</tr>
</table>
</div>
