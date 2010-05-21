{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product
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

<widget class="XLite_View_Form_Product_AddToCart" name="add_to_cart" product="{product}" className="product-details" />

  <table cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td IF="product.hasImage()" valign="top" align="left" width="100">
        <widget class="XLite_View_Image" image="{product.getImage()}" className="product-thumbnail" id="product_image_{product.product_id}" maxWidth="100" />
      </td>
      <td valign="top">

        <div IF="{product.sku}" class="product-sku">
          <span>SKU:</span>
          <span>{product.sku}</span>
        </div>

        <widget module="InventoryTracking" template="modules/InventoryTracking/stock_label.tpl" visible="{product.inventory.found}" />

        <widget class="XLite_View_Price" product="{product}" />

        <widget module="WholesaleTrading" class="XLite_Module_WholesaleTrading_View_Prices" product="{product}" />

        <widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyLink" product="{product}" />

        <widget module="ProductOptions" class="XLite_Module_ProductOptions_View_ProductOptions" product="{product}" />

        <widget module="WholesaleTrading" class="XLite_Module_WholesaleTrading_View_Amount" product="{product}" IF="isAvailableForSale()" />

      <div IF="availableForSale" class="buttons-row">
        <widget class="XLite_View_Button_Submit" label="Add to Cart" />
        <widget module="WishList" class="XLite_Module_WishList_View_Button_AddToWishlist" product="{product}" />
      </div>

      </td>
    </tr>

    <tr>
      <td colspan="2">

        <h3>Description</h3>

        <table IF="{product.getExtraFields(true)|product.weight}" class="product-extra-fields">

          <tr IF="{!product.weight=0}">
            <th>Weight:</th>
            <td>{product.weight} {config.General.weight_symbol}</td>
          </tr>

          <widget class="XLite_View_ExtraFields" product="{product}" />

        </table>

        <div class="product-description">{description:h}</div>

        <widget module="WholesaleTrading" class="XLite_Module_WholesaleTrading_View_ExtendedOptions" product="{product}" />

      </td>
    </tr>
    
  </table>

<widget name="add_to_cart" end />

<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_NotifyForm" product="{product}" />
<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyForm" product="{product}" />
