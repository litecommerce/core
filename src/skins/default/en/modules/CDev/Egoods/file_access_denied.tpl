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
<span IF="reason=#T#" class="OutOfStock">
<br><br>
The product link has expired.
</span>
<span IF="reason=#D#" class="OutOfStock">
<br><br>
The number of available product downloads has been exceeded.
</span>
<span IF="reason=#M#">
<br><br>
<font class="OutOfStock">You do not have a privilege to download the file.</font><br><br>Please contact the store administrator if you think that you get this message in error.
</span>
<span IF="!reason">
<br><br>
<font class="OutOfStock">The requested file has not been found.</font><br><br>Please contact the store administrator if you think that you get this message by mistake.
</span>
