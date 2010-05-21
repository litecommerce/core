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
<span class="ErrorMessage" IF="discountCouponResult=#not_found#">
The coupon code <b>{coupon}</b> is invalid.
</span>
<span class="ErrorMessage" IF="discountCouponResult=#used#">
The coupon <b>{coupon}</b> has already been used by someone else.
</span>
<span class="ErrorMessage" IF="discountCouponResult=#disabled#">
The coupon <b>{coupon}</b> is disabled.
</span>
<span class="ErrorMessage" IF="discountCouponResult=#expired#">
The coupon <b>{coupon}</b> is expired.
</span>

<form action="cart.php" name="couponform">
<input type="hidden" name="target" value="cart">
<input type="hidden" name="action" value="discount_coupon">
<table border="0">
<tr>
<td class="FormButton">Coupon code</td>
<td><input type="text" size="32" name="coupon"></td>
</tr>
<tr>
<td></td>
<td>
<widget class="XLite_View_Button" href="javascript: document.couponform.submit()" label="Add">
</td>
</tr>
</table>
</form>
