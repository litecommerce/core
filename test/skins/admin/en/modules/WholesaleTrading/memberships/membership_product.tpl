<tr>
	<td class="FormButton">Select membership to sell through this product&nbsp;</td>
	<td class="ProductDeails">
		<widget class="XLite_View_MembershipSelect" field="selling_membership" value="{product.selling_membership}">
	</td>
</tr>
<tr>
	<td class="FormButton">Membership validity period:</td>
	<td>
		<input name="vperiod" size="7" value="{validatyPeriod}">&nbsp;
		<select name="vp_modifier">
			<option value="D" selected="{validatyModifier=#D#}">Days</option>
			<option value="W" selected="{validatyModifier=#W#}">Weeks</option>
			<option value="M" selected="{validatyModifier=#M#}">Months</option>
			<option value="Y" selected="{validatyModifier=#Y#}">Years</option>
		</select>
	</td>
</tr>
