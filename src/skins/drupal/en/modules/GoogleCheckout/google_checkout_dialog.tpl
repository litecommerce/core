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
<span IF="action=#checkout#">
<font class="ErrorMessage">Order processing error !</font><br><br>
Checkout cannot be started as it is impossible to redirect to the Google Checkout server.<br>
<span IF="googleError"><br><b>Error: </b>{googleError}<br></span>
<br>
<widget class="\XLite\View\Button" label="Go back" href="cart.php?target=cart" font="FormButton">
