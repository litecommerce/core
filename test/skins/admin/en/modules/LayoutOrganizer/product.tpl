<p align=justify>Use this section to specify the template to be used for product presentation in the store catalog.</p>

<table border=0 cellpadding="0" cellspacing="0">
<form action="admin.php" method=POST name="modify_form">
<input type="hidden" FOREACH="allparams,_name,_val" name="{_name}" value="{_val}" />
<input type=hidden name=action value=modify_templates>
<input type="hidden" name="product_id" value="{product_id}">
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
                            	<td nowrap>&nbsp;&nbsp;Product page&nbsp;&nbsp;</td>
                            	<td nowrap>
                        		<widget class="XLite_View_FormField" template="modules/LayoutOrganizer/select_scheme.tpl" field="custom_template" inherit scheme_id="{productCustomTemplate}" schemes="{schemes}">
                            	</td>
                        	</tr>
                        </table>
                	</td>
            	</tr>
            </table>
    	</td>
    	<td width=50>&nbsp;</td>
    	<td valign=middle>
			<b>(*)</b> Inherit the product template the product's <a href="admin.php?target=category&category_id={product.parent.category_id}&mode=modify&page=category_templates"><u>category</u></a>.
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
