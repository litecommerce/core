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
<table border="0" cellpadding=0 cellspacing="0" width="100%">
{if:absentOptions}
	Sorry, but some options of "{invalidProductName:h}" do not exist anymore and you can not add this product to the cart. 
	<br> </br>
	<tr>
		<td><img src="skins/default/en/images/but_left.gif" width="8" height="22" border="0" alt=""></td>
		<td class="CommonButtonBG" nowrap>&nbsp;&nbsp;<a href="javascript: history.go(-1)" target="" class="ButtonLink"><font  class="Button">Go back</font></a>&nbsp;&nbsp;</td>
		<td><img src="skins/default/en/images/but_right.gif" width="8" height="22" border="0" alt=""></td>
		<td width="100%"></td>
	</tr>
{else:}
	{if:invalidOptions}
		Sorry, but options of "{invalidProductName:h}" are invalid. You coudn't add product to cart.
		<br> </br>
		<tr>
			<td><img src="skins/default/en/images/but_left.gif" width="8" height="22" border="0" alt=""></td>
			<td class="CommonButtonBG" nowrap>&nbsp;&nbsp;<a href="javascript: history.go(-1)" target="" class="ButtonLink"><font  class="Button">Go back</font></a>&nbsp;&nbsp;</td>
			<td><img src="skins/default/en/images/but_right.gif" width="8" height="22" border="0" alt=""></td>
			<td width="100%"></td>
		</tr>
	{else:}
		{if:getItems()}
			<tr FOREACH="getItems(),key,item">
				<td><widget template="modules/CDev/WishList/item.tpl" key="{key}" item="{item}"></td>
			</tr>
			<tr>
				<td><hr color="#E0E1E4"></td>
			</tr>
			<tr>
				<td><widget template="modules/CDev/WishList/send_wishlist.tpl"></td>
			</tr>
		{else:}
			<tr>
				<td>
					Your Wish List is empty.
				</td>
			</tr>
		{end:}
	{end:}
{end:}
</table>
