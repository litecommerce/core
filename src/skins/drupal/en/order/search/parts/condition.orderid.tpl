{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders search Order ID condition
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="orders.search.conditions", weight="10")
 *}
<tr class="order-id">
  <td>Order id:</td>
  <td><input type="text" name="order_id" value="{getCondition(#order_id#)}" /></td>
  <td IF="!isDefaultConditions()" class="button-cell reset"><a href="{buildUrl(#order_list#,#reset#)}">See all orders</a></td>
</tr>
