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
<a href="javascript:void(0);" class="dynamic search-orders dynamic-close"><span>Search orders</span><img src="images/spacer.gif" alt="" /></a>
<div class="orders-total">
Total: <span>{getTotalCount()}</span> orders{if:getCount()}, found: <span>{getCount()}</span> orders{end:}
</div>

<widget class="XLite_View_OrderSearch" IF="getTotalCount()" />

<widget class="XLite_View_Pager_Common" data="{getOrders()}" name="pager" />

<ul class="orders-list" IF="getTotalCount()">

  <li FOREACH="namedWidgets.pager.getPageData(),order" class="order-{order.order_id}">

    <div class="order-title">
      <a href="{buildURL(#order#,##,_ARRAY_(#order_id#^order.order_id))}" class="number">#{order.order_id}</a>
      <span>from</span>
      {time_format(order.date)}
      <div class="status-{order.status}">
        <widget template="common/order_status.tpl" />
        <a href="{buildURL(#order#,#print#,_ARRAY_(#order_id#^order.order_id))}"><img src="images/spacer.gif" alt="Print" /></a>
      </div>
    </div>

    <widget class="XLite_View_OrderItemsShort" order="{order}" />

  </li>

</ul>
