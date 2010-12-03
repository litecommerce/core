{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table border=0 cellspacing=0 width=100%>
<tr>
    <td align="center" width="100" rowspan="2" valign=top><center><img src="images/modules/AOM/module_logo.gif" border=0></td>
	<td>AOM module introduces advanced order management capabilities.<br><br>Using this module you can define custom order life cycles, modify order details, create new orders and review detailed order statistics.</td>
</tr>
<tr>
    <td>
    	<br><hr><br>
		<table border="0" cellspacing="0" cellpadding="5" align="center">
        <tr>
            <td width="46"><a href="admin.php?target=order_statuses" onClick="javascript: this.blur()"><img src="images/modules/AOM/icon_order_life_cycles.gif" border=0></a></td>
            <td width="45%"><a href="admin.php?target=order_statuses" onClick="javascript: this.blur()"><b>Define order life cycles</b> of your store and manage order status e-mail notifications</a></td>
            <td width="1%">&nbsp;&nbsp;</td>
            <td width="46"><a href="admin.php?target=order_list" onClick="javascript: this.blur()"><img src="images/modules/AOM/icon_orders_list.gif" border=0></a></td>
            <td width="45%"><a href="admin.php?target=order_list" onClick="javascript: this.blur()">Use advanced order search form and <b>manage all details of orders placed</b></a></td>
        </tr>
        <tr>
            <td><a href="admin.php?target=orders_stats" onClick="javascript: this.blur()"><img src="images/modules/AOM/icon_orders_statistics.gif" border=0></a></td>
            <td><a href="admin.php?target=orders_stats" onClick="javascript: this.blur()"><b>Review detailed order statistics</b></a></td>
            <td>&nbsp;&nbsp;</td>
            <td><a href="admin.php?target=create_order&action=create_order" onClick="javascript: this.blur()"><img src="images/modules/AOM/icon_create_order.gif" border=0></a></td>
            <td><a href="admin.php?target=create_order&action=create_order" onClick="javascript: this.blur()"><b>Create new orders</b> or input offline orders into your LiteCommerce store database</a></td>
        </tr>
		</table>
    </td>
</tr>
</table>

<hr>

<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">
<table cellspacing=2 cellpadding=2 border=0 width="100%">
{foreach:options,id,option}
<tr id="option_{option.name}">
	<td align="right" width="50%">{option.comment:h}: </td>
	<td>&nbsp;</td>
{if:option.isName(#order_update_notification#)}
	<td width="50%">
	    <select id="{option.name}" name="{option.name}[]" multiple>
    	    <option selected="isEmailCheckedAOM(#orders_department#)" value="orders_department">Sales department</option>
        	<option selected="isEmailCheckedAOM(#site_administrator#)" value="site_administrator">Site administrator</option>
	        <option selected="isEmailCheckedAOM(#support_department#)" value="support_department">HelpDesk/Support service</option>
    	    <option selected="isEmailCheckedAOM(#users_department#)" value="users_department">Customer relations</option>
	    </select>
    	<br>
	    <i>To (un)select more than one e-mail address,<br>Ctrl-click on them</i>
	</td>
{else:}
	<td width="50%"><widget template="modules/CDev/{page}/settings.tpl" option="{option}" dialog="{dialog}"></td>
{end:}
</tr>
{end:}

<tr>
	<td colspan="3" align="middle"><input type="submit" value="Update"></td>
</tr>
</table>
</form>

<!--
<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">
<table cellSpacing=2 cellpadding=2 border=0 width="100%" align="center">
<tr>
    <td align="center"><b>[ Order life cycles ]</b></td>
    <td>&nbsp;</td>	
</tr>
<tr>
    <td align="center" valign="center"><u><a href="admin.php?target=order_statuses">Order statuses</a></u><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"></td>
    <td>&nbsp;</td>
</tr> 
<TR>
<TD align="center"><input type="submit" value=" Update "></TD>
<td></td>
</TR>
</TABLE>
</form>
-->
