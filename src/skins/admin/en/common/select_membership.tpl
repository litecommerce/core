<select {if:!nonFixed}class="FixedSelect"{end:} name="{field}" size="1">
   <option value="%" IF="{allOption}" selected="{isSelected(#%#,value)}">All memberships</option>
   <option value="" selected="{isSelected(##,value)}">No membership</option>
   <option value="pending_membership" IF="pendingOption" selected="{isSelected(#pending_membership#,value)}">Pending membership</option>
   <option FOREACH="config.Memberships.memberships,membership" selected="{isSelected(membership,value)}" value="{membership:r}">{membership}</option>
</select>
