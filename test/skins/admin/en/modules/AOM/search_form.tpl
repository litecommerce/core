<script>
	function setPeriod(period)
	{
		var custom_dates = document.getElementById('custom_dates');
		if (period != 6) 
			custom_dates.style.display = "none";
		else 
			custom_dates.style.display = "";
	}
</script>
<form action="admin.php" method="GET" name="order_search_form">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="mode" value="search">
<input type="hidden" name="action" value="">
<table border="0" cellpadding="3" cellspacing="0">
	<tr>		
		<td class="FormButton">Order ID:</td>
		<td align="right"><input size=8 name=start_order_id value="{start_order_id}" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;"> - <input size=8 name=end_order_id value="{end_order_id}" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;"> </td>
	    <td>&nbsp;</td>
		<td class="FormButton">E-mail:</td>
		<td width="220"><input type="text" name="login" value="{login}" class="Input"></td>	   
	</tr>
	<tr>
        <td class="FormButton">Order total:</td>
        <td align="right"><input size=8 name=start_total value="{start_total}" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;"> - <input size=8 name=end_total value="{end_total}" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;"> </td>
        <td>&nbsp;</td>
        <td class="FormButton">Customer info:</td>
        <td><input type="text" name="person_info" value="{person_info}" class="Input"></td>
	</tr>	
    <tr>
        <td class="FormButton">Order status:</td>
        <td><widget class="XLite_View_StatusSelect" field="status" allOption style="width: 100%"></td>
        <td>&nbsp;</td>
        <td class="FormButton">Product name:</td>
        <td><input type="text" name="product_name" value="{product_name}" class="Input"></td>
    </tr>
	<tr>
        <td class="FormButton">Payment method:</td>
        <td><select class="Input" name="payment_method">
				<option value="">All</option>
	            <option FOREACH="paymentMethods,method" value="{method.payment_method}" selected="{method.payment_method=payment_method}">{method.name:h}</option>
			</select>
		</td>
        <td>&nbsp;</td>
        <td class="FormButton">Delivery method:</td>
        <td><select class="Input" name="shipping_id">
                <option value="-1">All</option>
            	<option FOREACH="shippingRates,rate" value="{rate.shipping.shipping_id}" selected="{rate.shipping.shipping_id=shipping_id}">{rate.shipping.name:h}</option>
            </select>
		</td>
    </tr>
    <tr>
        <td class="FormButton">Search period:</td>
		<td><select class="FixedSelect" name="period" id="search_period" onChange="setPeriod(this.value)" style="width: 100%"> 
			<option value="-1" selected="period=#-1#">Whole period</option>
			<option value="0" selected="period=#0#">Today</option>
			<option value="1" selected="period=#1#">Yesterday</option>
			<option value="2" selected="period=#2#">Current week</option>
			<option value="3" selected="period=#3#">Previous week</option>
			<option value="4" selected="period=#4#">Current month</option>
			<option value="5" selected="period=#5#">Previous month</option>
			<option value="6" selected="period=#6#">Custom period</option>
			</select>
		</td>
		<td colspan="3"></td>
    </tr>
	<tbody id="custom_dates" style="display: none;">
	<tr>
		<td class="FormButton">Date from:</td>
		<td colspan="3"><widget class="XLite_View_Date" field="startDate"></td>
        <td></td>		
	</tr>
    <tr>
        <td class="FormButton">Date through:</td>
        <td colspan="3"><widget class="XLite_View_Date" field="endDate"></td>
        <td></td>
    </tr>
	</tbody>
	<tr>
		<td height=30 colspan="2" align="right"><input type="button" value=" Search " onclick="document.order_search_form.mode.value='search'; document.order_search_form.action.value=''; document.order_search_form.submit()"></td>
        <td class=FormButton >&nbsp;</td>
        <td colspan="2" align="right"><input type="button" value=" Export to.. " onclick="document.order_search_form.action.value=document.order_search_form.export_format.value;document.order_search_form.submit()">&nbsp;
<select name="export_format"><option value="default" selected>- select export format -</option><option FOREACH="exportFormats,format,description" value="{format}">{description}</option>
</select>
        </td>
	</tr>
</table>
<script>
	setPeriod({period});
</script>	
</form>
<br>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td><b>Note:</b> You can also <a href="admin.php?target=create_order&action=create_order"><u>create a new order</u></a>.</td>
	</tr>
</table>
