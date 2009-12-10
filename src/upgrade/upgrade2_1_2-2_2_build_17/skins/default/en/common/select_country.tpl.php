<?php
    $find_str = <<<EOT
<select class="FixedSelect" name="{field}" size="1">
   <option value="">Select one..</option>
   <option FOREACH="countries,k,v" value="{v.code:r}" selected="{v.code=value}">{v.country:h}</option>
</select>
EOT;
    $replace_str = <<<EOT
<select class="FixedSelect" name="{field}" size="1" onChange="{onChange}" id="{fieldId}">
   <option value="">Select one..</option>
   <option FOREACH="countries,k,v" value="{v.code:r}" selected="{v.code=value}">{v.country:h}</option>
</select>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
