{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Column with checkboxes
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.order.admin.search.columns", weight="10")
 *}

<td><input type="checkbox" class="checkbox {order.getOrderId()}" value="1" name="{getNameToDelete(order.getOrderId())}" /></td>
