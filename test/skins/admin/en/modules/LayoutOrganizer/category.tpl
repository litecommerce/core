<script language="Javascript">
<!--

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

-->
</script>

<p align=justify>Use this section to specify templates to be used for the presentation of the category and it's contents.
<span IF="simpleScheme">Switch to <a href="{url}&view=advanced" onClick="this.blur()"><u>advanced</u></a> view.</span>
<span IF="!simpleScheme"><span IF="category.simpleScheme">Switch to <a href="{url}&view=" onClick="this.blur()"><u>simple</u></a> view.</span></span>
</p>

<span IF="simpleScheme">
<table border=0 cellpadding="0" cellspacing="0">
<form action="admin.php" method=POST name="modify_form">
<input type="hidden" FOREACH="allparams,_name,_val" name="{_name}" value="{_val}" />
<input type=hidden name=action value=modify_templates>
<input type="hidden" name="mode" value="{mode}">
<input type="hidden" name="category_id" value="{category_id}">
<input type="hidden" name="view" value="">
	<tr>
    	<td>
            <table border=0 cellpadding="0" cellspacing="0">
            	<tr class="TableHead">
                	<td>
                        <table border=0 cellpadding="1" cellspacing="2" class="TableHead">
                        	<tr class="TableRow" height=20>
                            	<td nowrap>&nbsp;&nbsp;Template type&nbsp;&nbsp;</td>
                            	<td nowrap>&nbsp;&nbsp;Template scheme&nbsp;&nbsp;</td>
                        	</tr>
                        	<tr class="Center" height=20>
                            	<td nowrap>&nbsp;&nbsp;All (subcategory/product list, product page)&nbsp;&nbsp;</td>
                            	<td nowrap IF="parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="custom_template" scheme_id="{getCategoryScheme(#custom_template#)}" schemes="{schemes}">
                            	</td>
                            	<td nowrap IF="!parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="custom_template" inherit scheme_id="{getCategoryScheme(#custom_template#)}" schemes="{schemes}">
                            	</td>
                        	</tr>
                        </table>
                	</td>
            	</tr>
            </table>
    	</td>
    	<td width=50>&nbsp;</td>
    	<td valign=middle>
			<span IF="!parent=#0#">
			<b>(*)</b> Inherit the corresponding template from the <a href="admin.php?target=category&category_id={parent}&mode=modify&page=category_templates"><u>parent</u></a> category.
			</span>
    	</td>
	</tr>
	<tr>
    	<td align=center>
    		<br><input type="submit" value="Update templates">
    	</td>
    	<td colspan=2>
    	</td>
	</tr>
</form>
</table>
</span>

<span IF="!simpleScheme">
<table border=0 cellpadding="0" cellspacing="0">
<form action="admin.php" method=POST name="modify_form">
<input type="hidden" FOREACH="allparams,_name,_val" name="{_name}" value="{_val}" />
<input type=hidden name=action value=modify_templates>
<input type="hidden" name="mode" value="{mode}">
<input type="hidden" name="category_id" value="{category_id}">
<input type="hidden" name="view" value="advanced">
	<tr>
    	<td>
            <table border=0 cellpadding="0" cellspacing="0">
            	<tr class="TableHead">
                	<td>
                        <table border=0 cellpadding="1" cellspacing="2" class="TableHead">
                        	<tr class="TableRow" height=20>
                            	<td nowrap>&nbsp;&nbsp;Template type&nbsp;&nbsp;</td>
                            	<td nowrap>&nbsp;&nbsp;Template scheme&nbsp;&nbsp;</td>
                        	</tr>
                        	<tr class="Center" height=20>
                            	<td nowrap>&nbsp;&nbsp;Subcategory list&nbsp;&nbsp;</td>
                            	<td nowrap IF="parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="sc_custom_template" scheme_id="{getCategoryScheme(#sc_custom_template#)}" schemes="{schemes}">
                            	</td>
                            	<td nowrap IF="!parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="sc_custom_template" inherit scheme_id="{getCategoryScheme(#sc_custom_template#)}" schemes="{schemes}">
                            	</td>
                        	</tr>
                        	<tr class="Center" height=20>
                            	<td nowrap>&nbsp;&nbsp;Product list&nbsp;&nbsp;</td>
                            	<td nowrap IF="parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="custom_template" scheme_id="{getCategoryScheme(#custom_template#)}" schemes="{schemes}">
                            	</td>
                            	<td nowrap IF="!parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="custom_template" inherit scheme_id="{getCategoryScheme(#custom_template#)}" schemes="{schemes}">
                            	</td>
                        	</tr>
                        	<tr class="Center" height=20>
                            	<td nowrap>&nbsp;&nbsp;Product page&nbsp;&nbsp;</td>
                            	<td nowrap IF="parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="p_custom_template" scheme_id="{getCategoryScheme(#p_custom_template#)}" schemes="{schemes}">
                            	</td>
                            	<td nowrap IF="!parent=#0#">
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="p_custom_template" inherit scheme_id="{getCategoryScheme(#p_custom_template#)}" schemes="{schemes}">
                            	</td>
                        	</tr>
                        </table>
                	</td>
            	</tr>
            </table>
    	</td>
    	<td width=50>&nbsp;</td>
    	<td valign=middle>
			<span IF="!parent=#0#">
			<b>(*)</b> Inherit the corresponding template from the <a href="admin.php?target=category&category_id={parent}&mode=modify&page=category_templates"><u>parent</u></a> category.
			</span>
    	</td>
	</tr>
	<tr>
    	<td align=center>
    		<br><input type="submit" value="Update templates">
    	</td>
    	<td colspan=2>
    	</td>
	</tr>
</form>
</table>
</span>
