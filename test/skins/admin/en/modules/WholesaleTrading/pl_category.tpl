<FORM name="price_list_form" action="admin.php" method="GET">
<input type="hidden" name="target" value="price_list"/>
<input type="hidden" name="mode" value="preview">

<TABLE border=0>
	<TR>
		<TD class="FormButton" noWrap height=10>In category</TD>
		<TD width=10 height=10><FONT class="ErrorMessage">*</FONT></TD>
		<TD height=10>
            <widget class="XLite_View_CategorySelect" fieldName="category" allOption>
        </TD>
	</TR>
	<TR>
		<TD class="FormButton" noWrap height=10 colspan="3">
			Include subcategories
			<input type="checkbox" name="include_subcategories" checked="{include_subcategories}">
		</TD>
	</TR>
	<TR>
		<TD class="FormButton" noWrap height=10>For membership</TD>
        <TD width=10 height=10>&nbsp;</TD>
        <TD>
			<select name="membership">
				<option value="all" selected="{membership=#all#}">All</option>
				<option FOREACH="config.Memberships.memberships,_membership" selected="{membership=_membership}">{_membership}</option>
			</select>	
		</TD>
	</TR>
    <TR><TD colspan=3>&nbsp;</TD></TR>
	<TR>
		<TD colspan=3>
		<input type="submit" value=" Preview ">
        </TD>
	</TR>
</TABLE>
</FORM>
