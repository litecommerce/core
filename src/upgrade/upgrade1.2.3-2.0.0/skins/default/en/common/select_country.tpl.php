<?php

$source = strReplace('<select class="FixedSelect" name="{formField}" size="1">', '<select class="FixedSelect" name="{field}" size="1">', $source, __FILE__, __LINE__);
$source = strReplace('<option FOREACH="countries,k,v" value="{v.code:r}" selected="{v.isSelected(#code#,country)}">{v.country:h}</option>', '<option FOREACH="countries,k,v" value="{v.code:r}" selected="{v.code=value}">{v.country:h}</option>', $source, __FILE__, __LINE__);

?>
