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
<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".</p>
<widget class="\XLite\View\Button" label="Submit order" href="javascript: document.checkout.submit();">
</form>
