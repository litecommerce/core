<form action="cart.php" method="GET" name="order_search_form">
<input type="hidden" name="target" value="order_list">
<input type="hidden" name="mode" value="search">
<table>
<tbody>
<tr>
<TD class=FormButton noWrap height=10>Order id</TD>
<TD width=10 height=10></TD>
<TD height=10><INPUT size=6 name=order_id1 value="{order_id1}"> - <INPUT size=6 name=order_id2 value="{order_id2}"> </TD></TR>
<TR>
<TD class=FormButton noWrap height=10>Order 
status:</TD>
<TD width=10 height=10><FONT 
class=ErrorMessage>*</FONT></TD>
<TD height=10>
<widget class="XLite_View_StatusSelect"field="status" allOption>
</TD></TR>
<TR>
<TD class=FormButton noWrap height=10>Order date 
from:</TD>
<TD width=10 height=10><FONT class=ErrorMessage>*</FONT></TD>
<TD height=10>
<widget class="XLite_View_Date"field="startDate">
</TD></TR>
<TR>
<TD class=FormButton noWrap height=10>Order date 
through:</TD>
<TD width=10 height=10><FONT 
class=ErrorMessage>*</FONT></TD>
<TD height=10>
<widget class="XLite_View_Date"field="endDate">
</TD></TR>
<TR>
<TD class=FormButton width=78>&nbsp;</TD>
<TD width=10>&nbsp;</TD>
<TD height=30><widget class="XLite_View_Button"label="Search" href="javascript: document.order_search_form.submit();"> 
&nbsp; </TD></TR>
</tbody>
</table>
</form>
&nbsp; 
