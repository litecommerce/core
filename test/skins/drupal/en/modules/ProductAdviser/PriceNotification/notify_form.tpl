{* SVN $Id$ *}
<form action="{buildURL(#notify_me#,#notify_price#,_ARRAY_(#mode#^target,#url#^getCurrentURL(),#product_id#^product.product_id,#category_id#^category_id,#product_price#^product.price))}" method="POST" name="product_price_notify_{product.product_id}_form" id="product_price_notify_{product.product_id}_form">
  <input FOREACH="buildURLArguments(#notify_me#,#notify_price#,_ARRAY_(#mode#^target,#url#^getCurrentURL(),#product_id#^product.product_id,#category_id#^category_id,#product_price#^product.price)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />
</form>
