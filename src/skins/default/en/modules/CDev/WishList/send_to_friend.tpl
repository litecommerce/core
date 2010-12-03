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
<form action="{getShopUrl(#cart.php#)}" method=POST name="send{product_id}_form">
<input type=hidden name=target value="product">
<input type=hidden name=action value="send_friend">
<input type=hidden name=product_id value="{product_id}">
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td class="NumberOfArticles">Your name:</td>
		<td width="10"><font class="Star">*</font></td>
		<td><input type="text" name="sender_name" size="32" value="{senderName}"></td>
		<td><widget class="\XLite\Validator\RequiredValidator" field="sender_name" value="{sender_name}"></td>
	</tr>
	<tr>	
        <td class="NumberOfArticles">Your e-mail:</td> 
		<td width="10"><font class="Star">*</font></td>
        <td><input type="text" name="sender_email" size="32" value="{senderEmail}"></td>
        <td><widget class="\XLite\Validator\EmailValidator" field="sender_email"></td>
	</tr>	
	<tr>
        <td class="NumberOfArticles">Friend's e-mail:</td>
		<td width="10"><font class="Star">*</font></td>
        <td><input type="text" name="recipient_email" size="32" value="{recipient_email}"></td>
        <td><widget class="\XLite\Validator\EmailValidator" field="recipient_email"></td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
        <td colspan="2"><br><widget class="\XLite\View\Button\Submit" label="Send to friend" /></td>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
