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
<span IF="!error=#range#"> This product is not available. Please, contact the store owner.<br><br></span>
<span IF="error=#range#">
You can buy from {min} to {max} items of this product.
<br><br>
</span>
<span IF="error=#range#&added">
You have already ordered {added} items.
<br><br>
</span>
<widget class="XLite_View_Button" href="javascript: history.go(-1)" label="Go back">
