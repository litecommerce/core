<?php

	$find_str = <<<EOT
        Element.innerHTML = "<b>Please wait while your order is processing...</b>";
EOT;
	$replace_str = <<<EOT
        Element.innerHTML = "<b>Please wait while your order is being processed...</b>";
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
</table>

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&amp;mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&amp;mode=privacy_statement'><u>Privacy statement</u></a>".<br>
EOT;
	$replace_str = <<<EOT
</table>

<p>
<b>Notes</b><br>
<hr>
<table border="0" width="100%">
<tr>
    <td valign="top" align="right" width="40%">Customer notes:&nbsp;</td>
    <td align="left">&nbsp;<textarea cols="50" rows="7" name="notes"></textarea></td>
</tr>
</table>
</p>

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&amp;mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&amp;mode=privacy_statement'><u>Privacy statement</u></a>".<br>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>