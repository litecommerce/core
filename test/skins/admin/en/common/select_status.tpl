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
<option value="Q" selected="{getParam(#value#)=#Q#}">Queued</option>
<option value="P" selected="{getParam(#value#)=#P#}">Processed</option>
<option value="I" selected="{getParam(#value#)=#I#}">Incomplete</option>
<option value="F" selected="{getParam(#value#)=#F#}">Failed</option>
<option value="D" selected="{getParam(#value#)=#D#}">Declined</option>
<option value="C" selected="{getParam(#value#)=#C#}">Complete</option>
</select>
<span IF="pm=#credit_card#" id="status_warning" class="DialogTitle" style="color: red; visibility: hidden">&nbsp;Credit card info will be removed on this status change.</span>
