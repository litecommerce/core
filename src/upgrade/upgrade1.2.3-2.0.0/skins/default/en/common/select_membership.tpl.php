<?php

$search =<<<EOT
<select class="FixedSelect" name="{formField}" size="1">
   <option value="%" IF="allOption" selected="{isSelected(#%#,membership)}">All memberships</option>
   <option value="_%" IF="allOption" selected="{isSelected(#_%#,membership)}">All but empty</option>
   <option value="" selected="{isSelected(##,membership)}">No membership</options>
   <option FOREACH="config.Memberships.memberships,membership" selected="{isSelected(membership,selectedMembership)}">{membership}</option>
EOT;

$replace =<<<EOT
<select class="FixedSelect" name="{field}" size="1" id="{field}">
   <option value="%" IF="{allOption}" selected="{isSelected(#%#,value)}">All memberships</option>
   <option value="" selected="{isSelected(##,value)}">No membership</option>
   <option value="pending_membership" IF="pendingOption" selected="{isSelected(#pending_membership#,value)}">Pending membership</option>
   <option FOREACH="config.Memberships.memberships,membership" selected="{isSelected(membership,value)}">{membership}</option>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

?>
