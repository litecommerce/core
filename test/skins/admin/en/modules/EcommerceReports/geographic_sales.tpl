<p align=justify>This section displays statistics on product sales for the specified period and group it by geographic location.</p>

<br>

<a name=report_form></a>

<form name=ecommerce_report_form action="admin.php" method=POST>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>
<input type=hidden name=action value=get_data>

<table border="0" cellpadding="1" cellspacing="3">

<widget template="modules/EcommerceReports/period_form.tpl">

<widget template="modules/EcommerceReports/categories_form.tpl">

<tr>
    <td>Use address:</td>
    <td>
        <select name=group_by>
            <option value=billing selected="group_by=#billing#">Billing address</option>
            <option value=shipping selected="group_by=#shipping#">Shipping address</option>
        </select>
    </td>
</tr>

<tr>
    <td valign=top>Country:</td>
    <td>
        <select id="country_selector" name="country_codes[]" multiple size="10">
            <option FOREACH="countries,k,v" value="{v.code:r}" selected="{isSelectedItem(#country_codes#,v.code)}">{v.country:h}</option>
        </select>

        <widget template="modules/EcommerceReports/checker.tpl" selector="country_selector">

    </td>
</tr>

<tr>
    <td valign=top>State:</td>
    <td>
        <select id="state_selector" name="state_ids[]" multiple size="10">
            <option value="-1" selected="{isSelectedItem(#state_ids#,-1)}">Other</option>
            <option FOREACH="states,k,v" value="{v.state_id:r}" selected="{isSelectedItem(#state_ids#,v.state_id)}">{v.state:h}</option>
        </select>

        <widget template="modules/EcommerceReports/checker.tpl" selector="state_selector">
    </td>
</tr>

<widget module="ProductOptions" template="modules/EcommerceReports/options_form.tpl">

<tr>
	<td>Sort by:</td>
	<td>
		<select name="sort_by">
			<option value="amount" selected="{sort_by=#amount#}">Quantity</option>
			<option value="total" selected="{sort_by=#total#}">Total</option>
		</select>
	</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value=Search></td>
</tr>
</table>
</form>

<br>

<a name="sales_result"></a>

<p IF="search">{productsFound} product(s) found</p>

<p IF="search&geoSales">
<table FOREACH="geoSales,gidx,productSales" border=0 cellpadding=5 cellspacing=1>
<tr class=TableHead>
    <td colspan=4>Location: <b>{gidx:h}</b></td>
</tr>
<tr class="Head">
	<td>Product name</td>
	<td align=center>Qty</td>
	<td align=center>Average price</td>
	<td align=center>Total</td>
</tr>	
<tr FOREACH="productSales,pidx,ps" valign=top class="{getRowClass(pidx,#TableRow#,##)}">
	<td>
        <a href="admin.php?target=product&product_id={ps.product_id}"><u>{ps.order_item.product.name:h}</u></a>
        <widget module="ProductOptions" template="modules/EcommerceReports/selected_options.tpl" item="{ps.order_item}" visible="{split_options}">
    </td>
	<td align="center">{ps.amount}</td>
	<td align="right">{price_format(ps.avg_price):h}</td>
	<td align="right">{price_format(ps.total):h}</td>
</tr>
<tr valign=top class="TableRow">
    <td align=right><b>Total:</b></td>
    <td align="center"><b>{sumTotal(#amount#)}</b></td>
    <td align="right"><b>{price_format(sumTotal(#avg_price#)):h}</b></td>
    <td align="right"><b>{price_format(sumTotal(#total#)):h}</b></td>
</tr>
<tr>
    <td colspan=4><br></td>
</tr>
</table>
</p>
