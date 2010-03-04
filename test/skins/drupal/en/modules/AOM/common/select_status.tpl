<select name="{field}" style="{style}">
<option value="" IF="allOption">All</option>
<option FOREACH="statuses,orderStatus" value="{orderStatus.status}" selected="{value=orderStatus.status}">{orderStatus.name}</option>
</select>
