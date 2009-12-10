<?php

$source = strReplace('<select name="status">', '<select name="{field}">', $source, __FILE__, __LINE__);

$search =<<<EOT
<option value="Q" selected="{order.isSelected(#status#,#Q#)}">Queued</option>
<option value="P" selected="{order.isSelected(#status#,#P#)}">Processed</option>
<option value="I" selected="{order.isSelected(#status#,#I#)}">Incomplete</option>
<option value="F" selected="{order.isSelected(#status#,#F#)}">Failed</option>
<option value="D" selected="{order.isSelected(#status#,#D#)}">Declined</option>
<option value="C" selected="{order.isSelected(#status#,#C#)}">Complete</option>
EOT;

$replace =<<<EOT
<option value="Q" selected="{value=#Q#}">Queued</option>
<option value="P" selected="{value=#P#}">Processed</option>
<option value="I" selected="{value=#I#}">Incomplete</option>
<option value="F" selected="{value=#F#}">Failed</option>
<option value="D" selected="{value=#D#}">Declined</option>
<option value="C" selected="{value=#C#}">Complete</option>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

?>
