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

<widget class="\XLite\View\Form\Product\AddToCart" name="add_to_cart" product="{product}" className="product-details" />

  <table cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td IF="product.hasImage()" valign="top" align="left" width="100">
        <widget class="\XLite\View\Image" image="{product.getImage()}" className="product-thumbnail" id="product_image_{product.product_id}" maxWidth="100" />
      </td>
      <td valign="top">

        <div IF="{product.sku}" class="product-sku">
          <span>SKU:</span>
          <span>{product.sku}</span>
        </div>

        <widget module="CDev\InventoryTracking" template="modules/CDev/InventoryTracking/stock_label.tpl" IF="{product.inventory.found}" />

        <widget class="\XLite\View\Price" product="{product}" />

        <widget module="CDev\WholesaleTrading" class="\XLite\Module\CDev\WholesaleTrading\View\Prices" product="{product}" />

        <widget module="CDev\ProductAdviser" class="\XLite\Module\CDev\ProductAdviser\View\PriceNotifyLink" product="{product}" />

        <widget module="CDev\ProductOptions" class="\XLite\Module\CDev\ProductOptions\View\ProductOptions" product="{product}" />

        <widget module="CDev\WholesaleTrading" class="\XLite\Module\CDev\WholesaleTrading\View\Amount" product="{product}" IF="isAvailableForSale()" />

      <div IF="availableForSale" class="buttons-row">
        <widget class="\XLite\View\Button\Submit" label="Add to Cart" />
        <widget module="CDev\WishList" class="\XLite\Module\CDev\WishList\View\Button\AddToWishlist" product="{product}" />
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

          <widget class="\XLite\View\ExtraFields" product="{product}" />

        </table>

        <div class="product-description">{description:h}</div>

        <widget module="CDev\WholesaleTrading" class="\XLite\Module\CDev\WholesaleTrading\View\ExtendedOptions" product="{product}" />

      </td>
    </tr>
    
  </table>

<widget name="add_to_cart" end />

<widget module="CDev\ProductAdviser" class="\XLite\Module\CDev\ProductAdviser\View\NotifyForm" product="{product}" />
<widget module="CDev\ProductAdviser" class="\XLite\Module\CDev\ProductAdviser\View\PriceNotifyForm" product="{product}" />
