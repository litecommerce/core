<?php

	$find_str = <<<EOT
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
</tbody>

</table>
EOT;

	$replace_str = <<<EOT
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile={profile}>
</tbody>

</table>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
