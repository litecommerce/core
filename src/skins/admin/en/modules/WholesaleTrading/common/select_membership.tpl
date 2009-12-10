{if:!isEmpty(history)}
<select class="FixedSelect" id="{field}" name="{field}" size="1" OnChange="ChangeMembership(this);">
{else:}
<select class="FixedSelect" id="{field}" name="{field}" size="1">
{end:}
   <option value="all" IF="{allOption}" selected="{isSelected(#all#,value)}">All memberships</option>
   <option value="" selected="{isSelected(##,value)}">No membership</option>
   <option value="pending_membership" IF="pendingOption" selected="{isSelected(#pending_membership#,value)}">Pending membership</option>
   <option FOREACH="config.Memberships.memberships,membership" selected="{isSelected(membership,value)}" value="{membership:r}">{membership}</option>
</select>

{if:!isEmpty(history)}
<script language="javascript">
var membership_index = document.getElementById('{field}').selectedIndex;
function ChangeMembership(obj)
{
	if ( confirm("Are you sure you want to change mempership?") ) {
		membership_index = obj.selectedIndex;
	} else {
		obj.options[membership_index].selected = true;
	}
}
</script>
{end:}
