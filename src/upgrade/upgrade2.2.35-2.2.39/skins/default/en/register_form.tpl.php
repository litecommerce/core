<?php

	$find_str = <<<EOT
</tr>

<tbody IF="showMembership">
<tr>
    <td colspan="4">&nbsp;</td>
EOT;
	$replace_str = <<<EOT
</tr>

<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/notice_register.tpl">
<tbody IF="showMembership">
<tr>
    <td colspan="4">&nbsp;</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
</table>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
EOT;
	$replace_str = <<<EOT
</table>

<br>
<div IF="GDLibLoaded">
<widget template="common/spambot_arrest.tpl" id="on_register"></div>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>