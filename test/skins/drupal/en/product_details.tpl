{* SVN $Id$ *}
<script type="text/javascript">
<!--
function isValid()
{   
    return true;
}
-->
</script>

<form action="{buildURLPath(#cart#,#add#,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" method="GET" name="add_to_cart" onsubmit="javascript: return isValid();">
  <input FOREACH="buildURLArguments(#cart#,#add#,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

  <table cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td IF="product.hasImage()" valign="top" align="left" width="100">
        <img src="{product.imageURL}" alt="" />
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
            <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/product_button.tpl" visible="{!priceNotificationSaved}">
            <widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
            <widget module="WholesaleTrading" template="modules/WholesaleTrading/expanded_options.tpl" IF="product.hasOptions()&product.showExpandedOptions"/>
        		<widget module="WholesaleTrading" template="modules/WholesaleTrading/extra.tpl">

            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>

            <tr IF="availableForSale" id="addToCartButton">
              <td>
                <widget class="XLite_View_Button" label="Add to Cart" type="button" img="cart4button.gif" font="FormButton">
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

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
<widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/notify_form.tpl" visible="{!priceNotificationSaved}">
