{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Cart
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="shopping-cart" class="checkout-cart">

  <widget module="ProductOptions" template="modules/ProductOptions/selected_options_js.tpl">

  <widget template="shopping_cart/items.tpl" />

  <div class="cart-totals">
    <span>Subtotal: {price_format(cart,#subtotal#):h}</span>
  </div>

  <widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}" />

</div>

<div class="clear">&nbsp;</div>
