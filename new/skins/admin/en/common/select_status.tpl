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
<select name="{field}" {if:xlite.GoogleCheckoutEnabled&googleCheckoutOrder}disabled{end:} {if:pm=#credit_card#}onChange="javascript: status_change(this)"{end:}>
<option value="" IF="allOption">All</option>
<option value="Q" selected="{value=#Q#}">Queued</option>
<option value="P" selected="{value=#P#}">Processed</option>
<option value="I" selected="{value=#I#}">Incomplete</option>
<option value="F" selected="{value=#F#}">Failed</option>
<option value="D" selected="{value=#D#}">Declined</option>
<option value="C" selected="{value=#C#}">Complete</option>
</select>
<span IF="pm=#credit_card#" id="status_warning" class="DialogTitle" style="color: red; visibility: hidden">&nbsp;Credit card info will be removed on this status change.</span>
