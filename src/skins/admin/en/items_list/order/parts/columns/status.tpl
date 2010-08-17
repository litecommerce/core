{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.order.admin.search.columns", weight="30")
 *}

<td class="status">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr><widget class="\XLite\View\FormField\Select\OrderStatus" fieldName="{getNamePostedData(#status#,order.getOrderId())}" value="{order.getStatus()}" /></tr>
  </table>
</td>
