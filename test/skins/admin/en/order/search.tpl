<widget template="common/dialog.tpl" name="searchOrdersForm" head="Search orders" body="order/search_form.tpl" />

<widget template="common/dialog.tpl" head="Recent orders" body="order/recent_orders.tpl" recent_orders="{recentOrders}" visible="{recentOrders}" mode="" />

<span IF="{getRequestParamValue(#mode#)=#search#}">

  <span class="Text" IF="{getNoSuchUser()}">No such user: {login:h}<br></span>
  <span class="Text" IF="!count">No orders found</span>
  <span class="Text" IF="count">{count} order<span IF="!count=#1#">s</span> found.</span>

  <widget template="common/dialog.tpl" head="Search results" body="order/list.tpl" visible="{count}">

</span>

<widget module="AccountingPackage" template="common/dialog.tpl" head="Export found orders to MYOB Plus" body="modules/AccountingPackage/export_myob_select.tpl" mode="export_myob" />

<widget module="AccountingPackage" template="common/dialog.tpl" head="Export found orders to Peachtree" body="modules/AccountingPackage/export_pt_select.tpl" mode="export_pt" />
