{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item position
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.table.admin.search.columns", weight="50")
 *}

<td><input type="text" class="pos" size="5" name="{getNamePostedData(#pos#,product.getProductId())}" value="{product.getOrderBy():r}" /></td>
