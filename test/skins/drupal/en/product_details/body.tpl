{* SVN $Id$ *}
<script type="text/javascript">
<!--
function isValid()
{   
    return true;
}
-->
</script>

<div IF="previousProduct|nextProduct" class="sibliding-links">
  <a IF="previousProduct" class="previous" href="{buildURL(#product#,##,_ARRAY_(#product_id#^previousProduct.product_id))}" alt="{previousProduct.name}">Previous product</a>
  <span IF="previousProduct&nextProduct">/</span>
  <a IF="nextProduct" class="next" href="{buildURL(#product#,##,_ARRAY_(#product_id#^nextProduct.product_id))}" alt="{nextProduct.name}">Next product</a>
</div>

<form action="{buildURLPath(#cart#,#add#,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" method="GET" name="add_to_cart" onsubmit="javascript: return isValid();">
  <input FOREACH="buildURLArguments(#cart#,#add#,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

  <table cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td IF="product.hasImage()" valign="top" align="left" width="100">
        <img IF="!product.hasZoom" src="{product.imageURL}" id="product_image_{product.product_id}" alt="" />

        <widget module="DetailedImages" class="XLite_Module_DetailedImages_View_Zoom" product="{product}" IF="product.hasZoom">
        <widget module="DetailedImages" class="XLite_Module_DetailedImages_View_Gallery" product="{product}">

      </td>
      <td valign="top">
    
        <widget module="InventoryTracking" mode="out_of_stock" template="modules/InventoryTracking/out_of_stock.tpl" IF="product.productOptions"/>

        <!-- product details -->
        <table id="productDetailsTable" cellpadding="0" cellspacing="0" width="100%">

          <tr id="descriptionTitle">
            <td colspan="2" class="ProductDetailsTitle">Description</td>
          </tr>

          <tr>
            <td class="Line" height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt=""></td>
          </tr>

          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>

          <tr id="description">
            <td colspan="2">{description:h}</td>
          </tr>

          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
        
          <tr id="detailsTitle">
            <td colspan="2" class="ProductDetailsTitle">Details</td>
          </tr>

          <tr>
            <td class="Line" height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt=""></td>
          </tr>

          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>

          <tr IF="{product.sku}">
            <td width="30%" class="ProductDetails">SKU:</td>
            <td class="ProductDetails" nowrap>{product.sku}</td>
          </tr>
        
          <widget module="InventoryTracking" template="modules/InventoryTracking/product_quantity.tpl" IF="!product.productOptions" visible="{product.inventory.found}"/>
          <widget module="ProductOptions" template="modules/ProductOptions/product_quantity.tpl">

          <widget class="XLite_View_ExtraFields" product="{product}">

          <tbody>

            <tr IF="{!product.weight=0}">
              <td width="30%" class="ProductDetails">Weight:</td>
              <td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td>
            </tr>

            <widget class="XLite_View_Price" product="{product}" template="common/price_table.tpl">
            <widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyLink" visible="{!priceNotificationSaved}" />
            <widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
            <widget module="WholesaleTrading" template="modules/WholesaleTrading/expanded_options.tpl" IF="product.hasOptions()&product.showExpandedOptions"/>
        		<widget module="WholesaleTrading" template="modules/WholesaleTrading/extra.tpl">

            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>

            <tr IF="availableForSale" id="addToCartButton">
              <td>
                <widget class="XLite_View_Button" label="Add to Cart" type="button">
			        </td>
        			<td IF="!config.General.add_on_mode">
        				<widget module="WishList" template="modules/WishList/add.tpl">
              </td>
            </tr>

          </tbody>
        </table>
      </td>
    </tr>    
  </table>
</form>

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_NotifyForm" />
<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm" visible="{!priceNotificationSaved}" />
