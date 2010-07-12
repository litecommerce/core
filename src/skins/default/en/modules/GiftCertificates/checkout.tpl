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
<form action="cart.php" method="POST" name="gccheckoutform">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">
<input type="hidden" name="mode" value="details">

<widget class="\XLite\Module\GiftCertificates\Validator\GCValidator" field="gcid">
<br>
Please enter the eight-character gift certificate code&nbsp;&nbsp; <font class="Star">*</font> <input type="text" size="20" name="gcid">

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<widget class="\XLite\View\Button" label="Submit order" href="javascript: document.gccheckoutform.submit();">
</form>
