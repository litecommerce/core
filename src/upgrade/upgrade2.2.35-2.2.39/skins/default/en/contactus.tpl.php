<?php

	$find_str = <<<EOT
<textarea cols=48 rows=12 name=body>{body}</textarea>
<widget class="CRequiredValidator" field="body">
</td>
</tr>

<tr valign=middle>
EOT;
	$replace_str = <<<EOT
<textarea cols=48 rows=12 name=body>{body}</textarea>
<widget class="CRequiredValidator" field="body">
</td>
</tr>

<tr IF="GDLibLoaded" valign="middle">
<td colspan="3">
<widget template="common/spambot_arrest.tpl" id="on_contactus">
</td>    
</tr>

<tr valign=middle>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>