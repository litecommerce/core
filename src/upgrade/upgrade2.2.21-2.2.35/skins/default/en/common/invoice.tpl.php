<?php

	$find_str = <<<EOT
<FONT color=#B2B2B3 style="font-size: 30px;"><b>INVOICE</b></FONT><BR>
<br>
<FONT style="font-size: 14px;"><b>{config.Company.company_name}</b></FONT>
<br>
EOT;

	$replace_str = <<<EOT
<FONT color="#B2B2B3" style="font-size: 30px;"><b>INVOICE</b></FONT><BR>
<br>
<FONT style="font-size: 14px;"><b>{config.Company.company_name}</b></FONT>
<br>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Products Ordered</b></td>
	</tr>
EOT;

	$replace_str = <<<EOT
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
		<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile={order.profile}>
	<tr>
		<td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Products Ordered</b></td>
	</tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
            <td>{price_format(item,#total#):h}</td>
      </tr>
      <tr>
	    <td colspan="2" height="1"><table bgcolor="#DDDDDD" width="100%" height="1" border="0" cellspacing="0" cellpadding="0"><td></td></table></td>
      </tr>
	</tbody>
	<tr>
EOT;

	$replace_str = <<<EOT
            <td>{price_format(item,#total#):h}</td>
      </tr>
      <tr>
	    <td colspan="2" height="1"><table bgcolor="#DDDDDD" width="100%" height="1" border="0" cellspacing="0" cellpadding="0"><tr><td></td></tr></table></td>
      </tr>
	</tbody>
	<tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
