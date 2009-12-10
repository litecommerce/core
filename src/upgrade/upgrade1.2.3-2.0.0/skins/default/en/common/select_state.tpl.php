<?php

$source = strReplace('<select class="FixedSelect" name="{formField}" size="1">', '<select class="FixedSelect" name="{field}" size="1">', $source, __FILE__, __LINE__);
$source = strReplace('<option value="-1" selected="{isSelected(state,#-1#)}">Other</option>', '<option value="-1" selected="{widget.value=-1}">Other</option>', $source, __FILE__, __LINE__);
$source = strReplace('<option FOREACH="states,k,v" value="{v.state_id:r}" selected="{v.isSelected(#state_id#,state)}">{v.state:h}</option>', '<option FOREACH="states,k,v" value="{v.state_id:r}" selected="{v.state_id=value}">{v.state:h}</option>', $source, __FILE__, __LINE__);

?>
