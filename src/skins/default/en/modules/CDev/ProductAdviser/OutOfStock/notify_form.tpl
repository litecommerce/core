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
<div IF="productNotificationEnabled&rejectedItem">

<script type="text/javascript">
<!--
function NotifyMe()
{
	document.product_notify_form.submit();
}
-->
</script>

  <form action="{getShopUrl(#cart.php#)}" method="POST" name="product_notify_form">

    <input type="hidden" name="target" value="notify_me">
    <input type="hidden" name="action" value="notify_product">
    <input type="hidden" name="mode" value="{target}">
    <input type="hidden" name="url" value="{url}">
    <input type="hidden" name="category_id" value="{category_id}">
    <input type="hidden" name="product_id" value="{rejectedItem.product_id}">

    <div IF="rejectedItem.productOptions">
      <div FOREACH="rejectedItem.productOptions,option">
        <input type="hidden" name="product_options[{option.class:h}][option_id]" value="{option.option_id}">
        <input type="hidden" name="product_options[{option.class:h}][option]" value="{option.option}">
      </div>
    </div>

    <input type="hidden" name="amount" value="{rejectedItem.amount}">
  </form>

</div>
