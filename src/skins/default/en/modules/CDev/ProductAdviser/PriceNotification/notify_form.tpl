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
<div IF="priceNotificationEnabled">
<script type="text/javascript">
function PriceNotifyMe(product_id,price)
{
  var form = document.product_price_notify_form;
	if (product_id) {
		document.product_price_notify_form.product_id.value = product_id;
	}
	if (price) {
		document.product_price_notify_form.product_price.value = price;
	}
	document.product_price_notify_form.submit();
}
</script>
<form action="{getShopUrl(#cart.php#)}" method="POST" name="product_price_notify_form">
<input type="hidden" name="target" value="notify_me">
<input type="hidden" name="action" value="notify_price">
<input type="hidden" name="mode" value="{target}">
<input type="hidden" name="url" value="{url}">
<input type="hidden" name="product_id" value="{product.product_id}">
<input type="hidden" name="category_id" value="{category_id}">
<input type="hidden" name="product_price" value="{product.price}">
</form>
</div>
