{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Register step
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="shopping-cart" IF="!cart.isEmpty()">

  <widget module="ProductOptions" template="modules/ProductOptions/selected_options_js.tpl">

  <widget template="shopping_cart/items.tpl" />

  <div class="cart-totals">
    <span IF="!auth.isLogged()">Subtotal: {price_format(cart,#subtotal#):h}</span>
    <widget IF="auth.isLogged()" template="shopping_cart/totals.tpl" />
  </div>

  <widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}" />

</div>

<div class="clear">&nbsp;</div>

<div class="checkout-profile">
  {if:isExported()}
    {getRegisterFormPlaceholder():r}
  {else:}
    <widget mode="register" class="XLite_View_Model_Profile" />
  {end:}
</div>
