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
