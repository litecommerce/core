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

{* Checkout pages: cart content *}
<widget module="ProductOptions" template="modules/ProductOptions/selected_options_js.tpl">

<div id="shopping-cart">
  <widget class="XLite_View_Form_Checkout_Main" name="checkout_form" />

  <widget template="shopping_cart/items.tpl" />

  <table width="100%" cellpadding=5>
  <tr>
    <td colspan="10">
      <hr />
      <widget template="shopping_cart/delivery.tpl" />
      <widget template="shopping_cart/totals.tpl" />
      <widget class="XLite_View_Button_Submit" label="Update" />
    </td>
  </tr>
  </table>

  <widget name="checkout_form" end />
</div>

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
