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
<script>
function RestoreDefaults()
{
	var fields = new Array("order_total_multiplier","processed_orders_multiplier","declined_orders_multiplier","risk_country_multiplier","duplicate_ip_multiplier");
	var Element;
    for (var i=0; i<fields.length; i++) {
	 Element = document.getElementById(fields[i]);
        if (Element) {
			switch (fields[i]) {
				case "order_total_multiplier" : 
	            case "processed_orders_multiplier" : 
    	        case "duplicate_ip_multiplier" : 
					Element.value = "2";
				break;	
    	        case "declined_orders_multiplier" : 
					Element.value = "1.5";
				break;	
        	    case "risk_country_multiplier" : 
					Element.value = "7";
				break;	
			}
		}
	}
	document.options_form.submit();
}
</script>
<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">
<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<TR FOREACH="options,option">
    {if:option.isCheckbox()}
	<TD align=right width="50%">{option.comment:h} </TD>
    <TD width="50%">
    <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.isChecked()}">
    </TD>
    {end:}
    {if:option.isText()}
    <TD align=right width="50%">{option.comment:h} </TD>
    <TD width="50%">
    <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    </TD>
    {end:}
    {if:option.isName(#antifraud_force_queued#)}
    <TD align=right width="50%">{option.comment:h} </TD>
    <TD width="50%">
    <select name="{option.name}">
        <option value="Y" selected="{option.isSelected(#Y#)}">Manual processing</option>
        <option value="N" selected="{option.isSelected(#N#)}">Automatic processing</option>
    </select>
    </TD>
    {end:}
</TR>
<TR>
<TD>&nbsp;</TD>
<TD><input type="submit" value="Submit"></TD>
</TR>
<TR>
<TD colspan=2><HR></TD>
</TR>
<TR><TD class="DialogTitle" align="right">Advanced options:</TD>
	<TD>&nbsp;</TD>
</TR>
<TR FOREACH="options,option">
	{if:option.isName(#order_total_multiplier#)}
    <TD align=right width="50%">{option.comment:h}</TD>
    <TD width="50%">
		 <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    </TD>
    {end:}
    {if:option.isName(#processed_orders_multiplier#)}
    <TD align=right width="50%">{option.comment:h} </TD>
    <TD width="50%">
         <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    </TD>
    {end:} 
    {if:option.isName(#declined_orders_multiplier#)}
    <TD align=right width="50%">{option.comment:h} </TD>
    <TD width="50%">
         <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    </TD>
    {end:} 
    {if:option.isName(#risk_country_multiplier#)}
    <TD align=right width="50%">{option.comment:h} </TD>
    <TD width="50%">
         <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
	</TD>
    {end:} 
    {if:option.isName(#duplicate_ip_multiplier#)}
    <TD align=right width="50%">{option.comment:h} </TD>
    <TD width="50%">
         <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    </TD>
    {end:}
</TR>
<TR><TD>&nbsp;</TD>
<TD><input type="submit" value="Submit"> &nbsp;&nbsp;&nbsp;<input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <a href="javascript: RestoreDefaults();" onClick="this.blur()">Restore defaults</a></TD>
</TR></TD>
</TR>
</table>
</form>
