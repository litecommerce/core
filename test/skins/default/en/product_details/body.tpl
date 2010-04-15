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
<script type="text/javascript">
<!--
function isValid()
{   
    return true;
}
-->
</script>

<widget class="XLite_View_Form_Product_AddToCart" name="add_to_cart" product="{product}" />

  <table cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td IF="product.hasImage()" valign="top" align="left" width="100">
        <img src="{product.imageURL}" border=0 alt="">
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

          <widget class="XLite_View_ExtraFields" product="{product}" />

          <tbody>

            <tr IF="{!product.weight=0}">
              <td width="30%" class="ProductDetails">Weight:</td>
              <td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td>
            </tr>

            <widget class="XLite_View_Price" product="{product}" template="common/price_table.tpl">
            <widget module="ProductAdviser" template="modules/ProductAdviser/PriceNotification/product_button.tpl" visible="{!priceNotificationSaved}">
            <widget module="ProductOptions" class="XLite_Module_ProductOptions_View_ProductOptions" product="{product}" IF="!product.showExpandedOptions" />
            <widget module="WholesaleTrading" template="modules/WholesaleTrading/expanded_options.tpl" IF="product.hasOptions()&product.showExpandedOptions"/>
        		<widget module="WholesaleTrading" template="modules/WholesaleTrading/extra.tpl">

            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>

            <tr IF="availableForSale" id="addToCartButton">
              <td><widget class="XLite_View_Button_Submit" label="Add to Cart" /></td>
        			<td IF="!config.General.add_on_mode">
        				<widget module="WishList" class="XLite_Module_WishList_View_Button_AddToWishlist" product="{product}" />
              </td>
            </tr>

          </tbody>
        </table>
      </td>
    </tr>    
  </table>

<widget name="add_to_cart" end />

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_NotifyForm" product="{product}" />
<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm" product="{product}" />
