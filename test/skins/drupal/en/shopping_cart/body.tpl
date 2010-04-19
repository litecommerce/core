{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="shopping-cart">

  <widget template="shopping_cart/items.tpl" />

  <table cellspacing="0" class="cart-bottom">
    <tr>
      <td>

        <div class="cart-buttons">
          <widget class="XLite_View_Form_Cart_Clear" name="clear_form" className="plain" />
            <widget class="XLite_View_Button_Submit" label="Clear cart" />
          <widget name="clear_form" end >
          <widget class="XLite_View_Button_Link" label="Continue shopping" location="{session.continueURL}" />
        </div>

        <div class="shipping-estimator">
          <widget template="shopping_cart/delivery.tpl">
        </div>

      </td>
      <td class="cart-totals">

        <div class="cart-totals">
          <widget template="shopping_cart/totals.tpl" />
          <widget template="shopping_cart/checkout_buttons.tpl" />
        </div>

        <widget module="GiftCertificates" class="XLite_Module_GiftCertificates_View_CartRow" />

      </td>
    </tr>
  </table>

</div>
