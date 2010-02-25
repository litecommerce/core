{* SVN $Id$ *}
<form action="{buildURL(#notify_me#,#notify_product#,_ARRAY_(#mode#^target,#url#^getCurrentURL(),#category_id#^category_id,#product_id#^rejectedItem.product_id))}" method="POST" name="product_notify_{product.product_id}_form" id="product_notify_{product.product_id}_form">
  <input FOREACH="buildURLArguments(#notify_me#,#notify_product#,_ARRAY_(#mode#^target,#url#^getCurrentURL(),#category_id#^category_id,#product_id#^rejectedItem.product_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

  <span IF="rejectedItem.productOptions">
    <span FOREACH="rejectedItem.productOptions,option">
      <input type="hidden" name="product_options[{option.class:h}][option_id]" value="{option.option_id}">
      <input type="hidden" name="product_options[{option.class:h}][option]" value="{option.option}">
    </span>
  </span>

  <input type="hidden" name="amount" value="{rejectedItem.amount}">
</form>

