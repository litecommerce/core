{* SVN $Id$ *}
<widget class="XLite_View_Pager" data="{orders}" name="pager">

<br />
<table cellspacing="1" cellpadding="3">

  <tr class="TableHead">
    <th nowrap>Order #</th>
    <th>Status</th>
    <th>Date</th>
    <th nowrap>Products purchased</th>
    <th>Total</th>
  </tr>

  <tr FOREACH="pager.pageData,id,order" class="{getRowClass(id,##,#BottomBox#)}">
    <td nowrap valign="top">
      <a href="{buildURL(#order#,##,_ARRAY_(#order_id#^order.order_id))}">#{order.order_id}&nbsp;<strong>details&gt;&gt;</strong></a>
    </td>
    <td valign="top">
      <widget template="common/order_status.tpl">
    </td>
    <td nowrap valign="top">
      <a href="{buildURL(#order#,##,_ARRAY_(#order_id#^order.order_id))}">{time_format(order.date)}</a>
    </td>
    <td nowrap>

      <table cellpadding="0" cellspacing="0" width="100%">
        <tr FOREACH="order.items,item">
          <td width="100%">&nbsp;&nbsp;{item.name}&nbsp;</td>
          <td>(Qty:&nbsp;</td>
          <td nowrap>{item.amount})</td>
        </tr>
      </table>

    </td>
    <td align="right" valign="top">{price_format(order,#total#):h}</td>
  </tr>

</table>
