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
<font class="ErrorMessage">Order processing error !</font><br>
Payment processor declined your order: {order.details.error:h}<br>
Please review your data and try again.<br>
<br>
<widget class="XLite_View_Button" label="Go back" href="cart.php?target=checkout&cart=checkout" font="FormButton">
