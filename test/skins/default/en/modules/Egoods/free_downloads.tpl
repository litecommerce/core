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
You are privileged to download this product free of charge.<br>
Click the link<span IF="!product.egoodsNumber=#1#">s</span> below to download and save the file<span IF="!product.egoodsNumber=#1#">s</span>:
<table border="0" cellspacing="3" cellpadding="3">
<tbody FOREACH="product.egoods,egood">
<tr>
	<td>&nbsp;&nbsp;</td>
	<td>&bull;</td>
	<td>
		<a href="{xlite.getShopUrl(#cart.php?target=download&action=download&file_id=#):r}{egood.file_id}" onClick="this.blur()" class="GoLink"><u>{egood.fileName}</u></a>
	</td>
</tr>
</tbody>
</table>
