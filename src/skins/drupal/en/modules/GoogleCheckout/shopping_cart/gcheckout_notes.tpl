{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shooping cart common notice
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.buttons.checkout", weight="40")
 *}
<div IF="cart.showGoogleCheckoutNotes">
  <p class="cart-error-message" IF="cart.showRemoveDiscountsNote">Attention! If you have used bonus points, discount coupons or gift certificates to pay for your order, they will be removed from the order to exclude the possibility of a negative subtotal. Please enter the codes for discount coupons and/or gift certificates once again on the Google Checkout page. Bonus points cannot be used to pay for orders using Google Checkout.</p>
  <p class="cart-error-message" IF="cart.showNotValidDiscountNote">An order with an applied discount coupon cannot be processed using Google Checkout. The coupon will be removed if you process the order using Google Checkout.</p>
</div>
