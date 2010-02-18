{* SVN $Id$ *}
<widget template="common/dialog.tpl" body="order/search_form.tpl" head="Search orders">

<span class="Text" IF="mode=#search#&count">
  <widget class="XLite_View_OrderList" template="common/dialog.tpl">
</span>
<span class="Text" IF="mode=#search#&!count">No orders found</span>
