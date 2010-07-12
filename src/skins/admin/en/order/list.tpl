{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders list template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<script language="Javascript">
<!-- 

function setChecked(form, input, check)
{
    var elements = document.forms[form].elements[input];

    for (var i = 0; i < elements.length; i++) {
        elements[i].checked = check;
    }
}

function setHeaderChecked()
{
	var Element = document.getElementById("activate_orders");
    if (Element && !Element.checked) {
    	Element.checked = true;
    }
}

// -->
</script>

<widget class="\XLite\View\Pager\Common" data="{orders}" name="pager" itemsPerPage="{config.General.orders_per_page}">

<br>

<form name="order_form" action="admin.php" method="POST">

  <input type="hidden" foreach="allParams,_name,_value" name="{_name}" value="{_value}" />
  <input type="hidden" name="action" value="delete" />

  <table border=0>

    <tr class="TableHead">
      <th valign="top" width=10 align=center><input id="activate_orders" type="checkbox" onClick="this.blur();setChecked('order_form','order_ids',this.checked);"></th>
      <th valign="top" nowrap>Order #</th>
      <th valign="top" align=left>Status</th>
    	<widget module="AntiFraud" template="modules/AntiFraud/orders/label.tpl">
      <th valign="top" nowrap align=left>Date</th>
      <th valign="top" nowrap align=left>Customer</th>
      <th valign="top" align=center>Total</th>
      <th valign="top" nowrap>&nbsp;</th>
    </tr>

    <tr FOREACH="namedWidgets.pager.pageData,oid,order" class="{getRowClass(oid,##,#TableRow#)}">
      <td width=10 align=center><input id="order_ids" type="checkbox" name="order_ids[{order.order_id}]" onClick="this.blur()"></td>
      <td>&nbsp;<a href="admin.php?target=order&order_id={order.order_id}" onClick="this.blur()"><u>{order.order_id}</u></a></td>
      <td nowrap><widget template="common/order_status.tpl"></td>
    	<widget module="AntiFraud" template="modules/AntiFraud/orders/factor.tpl">
      <td nowrap><a href="admin.php?target=order&order_id={order.order_id}" onClick="this.blur()">{time_format(order.date)}</a></td>
      <td nowrap>

        <table border=0 cellpadding=0 cellspacing=0 width=100%>

      		<tr>
        		<td width=90% nowrap><a href="admin.php?target=order&order_id={order.order_id}" onClick="this.blur()">{order.profile.billing_title:h} {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}</a></td>
        		<td width=10% nowrap align=right>(<a href="admin.php?target=order&order_id={order.order_id}" onClick="this.blur()">{order.profile.login}</a>)</td>
		      </tr>
        </table>

      </td>
      <td align=right>{price_format(order,#total#):h}</td>
      <td nowrap align=right>&nbsp;<a href="admin.php?target=order&order_id={order.order_id}" onClick="this.blur()"><u>details</u>&nbsp;&gt;&gt;</a></td>
    </tr>

    <tr>
      <td colspan=7>&nbsp;</td>
    </tr>

    <tr>
      <td colspan=7 align=left><input type="button" value=" Delete selected " onclick="javascript: if (confirm('All related information will also be deleted from database!\n\nAre you sure you want to delete the selected orders?')) document.order_form.submit();"></td>
    </tr>

  </table>

</form>

