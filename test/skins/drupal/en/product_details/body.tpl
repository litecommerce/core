{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="XLite_View_Form_Product_AddToCart" name="add_to_cart" product="{product}" className="product-details hproduct" />

  <div IF="previousProduct|nextProduct" class="sibliding-links">
    <a IF="previousProduct" class="previous" href="{buildURL(#product#,##,_ARRAY_(#product_id#^previousProduct.product_id))}" title="{previousProduct.name}">Previous product</a>
    <span IF="previousProduct&nextProduct">|</span>
    <a IF="nextProduct" class="next" href="{buildURL(#product#,##,_ARRAY_(#product_id#^nextProduct.product_id))}" title="{nextProduct.name}">Next product</a>
  </div>

  <div IF="product.hasImage()" class="product-thumbnail">
    <div IF="!product.getHasZoom()" class="product-thumbnail-box">
      <widget class="XLite_View_Image" image="{product.getImage()}" className="photo product-thumbnail" id="product_image_{product.product_id}" maxWidth="100" />
      <widget class="XLite_View_SaveMark" product="{product}" />
<script type="text/javascript">
<!--
$(document).ready(
  function() {
    var e = $('.product-thumbnail-box');
    var i = $('.product-thumbnail-box img');
    e.width(i.width()).height(i.height());
  }
);
-->
</script>
    </div>

    <widget module="DetailedImages" class="XLite_Module_DetailedImages_View_Zoom" product="{product}" />
    <widget module="DetailedImages" class="XLite_Module_DetailedImages_View_Gallery" product="{product}" />

    <hr class="line" />

    <widget module="WishList" class="XLite_Module_WishList_View_SendToFriendLink" product="{product}" />
  </div>

  <div class="product-body">

    <h2 class="fn" style="display: none;">{product.name:h}</h2>

    <div IF="{product.sku}" class="identifier product-sku">
      <span class="type">SKU:</span>
      <span class="value">{product.sku}</span>
    </div>

    <widget module="InventoryTracking" template="modules/InventoryTracking/stock_label.tpl" visible="{product.inventory.found}" />

    <widget class="XLite_View_Price" product="{product}" />

    <widget module="WholesaleTrading" class="XLite_Module_WholesaleTrading_View_Prices" product="{product}" />

    <widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_PriceNotifyLink" product="{product}" />

    <widget module="ProductOptions" class="XLite_Module_ProductOptions_View_ProductOptions" product="{product}" />

    <widget module="WholesaleTrading" class="XLite_Module_WholesaleTrading_View_Amount" product="{product}" IF="isAvailableForSale()" />

    <div IF="availableForSale" class="buttons-row">
      <widget class="XLite_View_Button_Submit" label="Add to Cart" style="bright-button big-button add2cart-button" />
      <span class="separator">or</span>
      <widget module="WishList" class="XLite_Module_WishList_View_Button_AddToWishlist" product="{product}" style="link-button" />
    </div>

  </div>

  <h3>Description</h3>

  <table IF="{product.getExtraFields(true)|product.weight}" class="product-extra-fields">

    <tr IF="{!product.weight=0}">
      <th>Weight:</th>
      <td>{product.weight} {config.General.weight_symbol}</td>
    </tr>

    <widget class="XLite_View_ExtraFields" product="{product}" />

  </table>

  <div class="description product-description">{description:h}</div>

  <widget module="WholesaleTrading" class="XLite_Module_WholesaleTrading_View_ExtendedOptions" product="{product}" />

<widget name="add_to_cart" end />
