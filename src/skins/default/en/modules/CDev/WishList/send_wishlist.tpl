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
<form action="{getShopUrl(#cart.php#)}" method="POST" name="wishlist{wishlist.wishlist_id}_form">
<input type=hidden name=target value="wishlist">
<input type=hidden name=action value="send">
<input type=hidden name=wishlist_id value="{wishlist.wishlist_id}">
<table cellpadding=5 cellspacing=0 border=0 width="100%">
<tr>
	<td align="left">
<widget class="\XLite\View\Button" label="Clear Wish List" href="cart.php?target=wishlist&action=clear" font="FormButton">
	</td>
    <td align="right"> 
	<b>Send entire wish list by e-mail:</b> <input type=text name="wishlist_recipient" value="{wishlist_recipient}"><br><widget class="\XLite\Validator\EmailValidator" field="wishlist_recipient">
    </td>
    <td align="left"> 
<widget class="\XLite\View\Button" label="Send" href="javascript: document.wishlist{wishlist.wishlist_id}_form.submit();" font="FormButton">
	</td>
</tr>
</table>
</form>
