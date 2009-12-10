<?php

	$find_str = <<<EOT
</tr>
</tbody>

<tbody IF="showMembership">
EOT;
	$replace_str = <<<EOT
</tr>

<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/notice_register.tpl">

</tbody>

<tbody IF="showMembership">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
{*extraFields*}
<widget module="Newsletters" template="modules/Newsletters/subscription_form.tpl">

</table>
EOT;
	$replace_str = <<<EOT
{*extraFields*}
<widget module="Newsletters" template="modules/Newsletters/subscription_form.tpl">
<widget module="GiftCertificates" template="modules/GiftCertificates/active_certificates.tpl">

</table>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>