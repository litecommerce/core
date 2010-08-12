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
 * @ListChild (list="itemsList.product.table.admin.search.header", weight="25")
 *}
<span IF="!product.newArrival=#0#">
<span IF="product.newArrival=#1#">
<img src="images/modules/ProductAdviser/new.gif" width="34" height="13" border="0" alt="New">
</span>
<span IF="product.newArrival=#2#">
<img src="images/modules/ProductAdviser/new_forever.gif" width="34" height="13" border="0" alt="Forever New">
</span>
</span>
