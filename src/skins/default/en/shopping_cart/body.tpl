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
<widget class="\XLite\View\Form\Cart\Main" name="cart_form" />

  <p align=justify>The items in your shopping cart are listed below. To remove any item click "Delete Item". To place your order, please click "CHECKOUT".</p>

  <div FOREACH="cart.items,cart_id,item">
    <p>
      <widget template="shopping_cart/item.tpl" />

      {* TODO: add method to replace the the "shopping_cart/item.tpl" template *}
      {* <widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/item.tpl" IF="item.gcid" />*}
  </div>
  <img src="images/spacer.gif" class="DialogBorder" width="100%" height="1" alt="" />

  <widget template="shopping_cart/delivery.tpl">

  <table width="100%">

	  <tr>
      <td>
    		<widget template="shopping_cart/totals.tpl">
	    </td>
    </tr>

  </table>

  <img src="images/spacer.gif" class="DialogBorder" width="100%" height="1" alt="" />

  <widget template="shopping_cart/buttons.tpl">
  <widget module="CDev\GoogleCheckout" template="modules/CDev/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">

<widget name="cart_form" end />

<widget module="CDev\ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/notify_form.tpl" IF="{xlite.PA_InventorySupport}">
