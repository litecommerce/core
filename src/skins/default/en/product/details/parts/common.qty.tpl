{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Quantity input box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="product.details.page.info.buttons.cart-buttons", weight="10")
 * @ListChild (list="product.details.page.info.buttons-added.cart-buttons", weight="10")
 * @ListChild (list="product.details.quicklook.info.buttons.cart-buttons", weight="10")
 * @ListChild (list="product.details.quicklook.info.buttons-added.cart-buttons", weight="10")
 *}

<span class="product-qty">
  {t(#Qty#)}: <widget class="\XLite\View\Product\QuantityBox" product="{product}" />
</span>
