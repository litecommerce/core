<tr>
	<td align="right" valign="top">Membership expires</td>
    <td valign="top">&nbsp;</td>
    <td valign="middle">
		<script type="text/javascript">
function updateMembershipExpireDateState(type_el) {
	var _value, date_el;
	_value = 'never';
	if (type_el) {
		_value = type_el.value;
	}
	date_el = document.getElementById('membership_exp_date_id');
	if (date_el == null) return;
	date_el.style.display = (!(_value == 'custom'))?'none':'';
}
		</script>
		<select name="membership_exp_type" onChange="javascript: updateMembershipExpireDateState(this);">
			<option value="never" selected="{membership_exp_type=#never#}">Never</option>
			<option value="custom" selected="{membership_exp_type=#custom#}">Specific date</option>
		</select>
		<span id="membership_exp_date_id" {if:!membership_exp_type=#custom#}style="display:none;"{end:}>
			<widget class="CDate" field="membership_exp_date">
		</span>
	</td>
    <td>&nbsp;</td>
</tr>

