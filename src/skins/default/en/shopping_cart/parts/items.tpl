{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart items block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="cart.children", weight="10")
 * @ListChild (list="checkout.cart", weight="10")
 *}
<table class="selected-products" cellspacing="0">

  <tr>
    <list name="cart.items.header" />
  </tr>

  <tr class="selected-product" FOREACH="cart.getItems(),item">
    <list name="cart.item" item="{item}" />
  </tr>

  <tr class="selected-product additional-item" FOREACH="getViewList(#cart.items#),w">
    {w.display()}
  </tr>

</table>
