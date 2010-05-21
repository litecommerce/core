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
<form name="warning_form" action="admin.php" method="post">
<input type="hidden" name="target" value="product_list">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="pageID" value="{pageID}">
<input type="hidden" name="substring" value="{substring:r}">
<input type="hidden" name="search_category" value="{search_category}">
<input type="hidden" name="search_productid" value="{search_productid}">
<input type="hidden" name="search_productsku" value="{search_productsku:r}">
<input type="hidden" name="product_id" value="{product_id}">
Do you really want to delete the product #{product_id}:
<strong>{product.name} ?</strong>
<p>
<input type="submit" name="confirmed" value=" Yes ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="confirmed" value=" No ">
</form>
