<?php

	$find_str = <<<EOT
    <th nowrap width="100%">Products purchased</th>
EOT;
	$replace_str = <<<EOT
    <th nowrap>Products purchased</th>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
    <td nowrap align=right valign=top>{price_format(order,#total#):h}</td>
EOT;
	$replace_str = <<<EOT
    <td align=right valign=top >{price_format(order,#total#):h}</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>