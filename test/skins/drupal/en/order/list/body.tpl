{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="XLite_View_Pager" data="{getOrders()}" name="pager" />

<ul class="orders-list">

  <li FOREACH="namedWidgets.pager.getPageData(),order">

    <div class="order-title">
      <a href="{buildURL(#order#,##,_ARRAY_(#order_id#^order.order_id))}">#{order.order_id}</a> <span>from</span> {time_format(order.date)}
      <div class="status-{order.status}"><widget template="common/order_status.tpl" /></div>
    </div>

    <table cellspacing="0" class="form-table">
      <tr FOREACH="order.getItems(),i,item">
        <td class="name"><a href="{item.getURL()}">{item.name}</a></td>
        <td class="price">{price_format(item,#price#):h}</td>
        <td class="qty">qty:</td>
        <td class="quantity">{item.amount}</td>
        <td IF="i=#0#" class="total" rowspan="{order.getItemsCount()}">Grand total: <strong>{price_format(order,#total#):h}</strong></td>
      </tr>
    </table>

  </li>

</ul>
