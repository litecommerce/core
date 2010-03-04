{if:pm=#credit_card#}
<script type="text/javascript">
function status_change(el)
    {
if (el.value == '{config.General.clear_cc_info}')
    document.getElementById('status_warning').style.visibility = 'visible';
else
    document.getElementById('status_warning').style.visibility = 'hidden';
}
</script>
{end:}
<select name="{field}" style="{style}" {if:xlite.GoogleCheckoutEnabled&googleCheckoutOrder}disabled{end:} {if:pm=#credit_card#}onChange="javascript: status_change(this)"{end:}>
<option value="" IF="allOption">All</option>
<option FOREACH="statuses,orderStatus" value="{orderStatus.status}" selected="{value=orderStatus.status}">{orderStatus.name}</option>
</select>
<span IF="pm=#credit_card#" id="status_warning" class="DialogTitle" style="color: red; visibility: hidden; white-space: nowrap;">&nbsp;Credit card info will be removed on this status change.</span>
