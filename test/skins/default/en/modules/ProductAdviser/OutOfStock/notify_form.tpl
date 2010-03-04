{* SVN $Id$ *}
<div IF="productNotificationEnabled&rejectedItem">

<script type="text/javascript">
<!--
function NotifyMe()
{
	document.product_notify_form.submit();
}
-->
</script>

  <form action="{shopURL(#cart.php#)}" method="POST" name="product_notify_form">

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
