<table>
<form action="admin.php" method="GET" name="order_search_form">
<input type="hidden" name="target" value="order_list">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="action" value="">
<TBODY>
<TR>
<TD class=FormButton noWrap height=10>Order id</TD>
<TD height=10><INPUT size=6 name=order_id1 value="{order_id1}"> - <INPUT size=6 name=order_id2 value="{order_id2}"> </TD></TR>
<tr>
	<td class=FormButton noWrap height=10>E-mail:</td>
	<td><input type="text" name="login" value="{login:r}"></td>
</tr>
<TR>
<TD class=FormButton noWrap height=10>Order status:</TD>
<TD height=10>
<widget class="XLite_View_StatusSelect" field="status" allOption>
</TD></TR>
<widget module="AntiFraud" template="modules/AntiFraud/orders/search_form.tpl">
<TR>
<TD class=FormButton noWrap height=10>Order date from:</TD>
<TD height=10>
<widget class="XLite_View_Date" field="startDate">
</TD></TR>
<TR>
<TD class=FormButton noWrap height=10>Order date through:</TD>
<TD height=10>
<widget class="XLite_View_Date" field="endDate">
</TD></TR>
<TR>
<TD class=FormButton width=78>&nbsp;</TD>
<TD height=30><INPUT type="button" value=" Search " class="DialogMainButton" onClick="document.order_search_form.mode.value='search'; document.order_search_form.action.value=''; document.order_search_form.submit()">&nbsp;&nbsp;&nbsp;<INPUT type="button" value=" Export to.. " onclick="document.order_search_form.action.value=document.order_search_form.export_format.value;document.order_search_form.submit()">&nbsp;
<select name="export_format">
<option value="default" selected>- select export format -</option>
<option FOREACH="exportFormats,format,description" value="{format}">{description}</option>
</select>

&nbsp; </TD></TR></FORM></TBODY></TABLE>&nbsp; 
