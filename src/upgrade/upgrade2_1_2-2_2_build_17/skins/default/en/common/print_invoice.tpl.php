<?php
    $find_str = <<<EOT
<tr>
	<td valign="top">Address</td>
	<td>{config.Company.location_address} <br>
	{config.Company.location_zipcode} {config.Company.location_city} {config.Company.location_country}
	</td>
</tr>
<tr>
EOT;
    $replace_str = <<<EOT
<tr>
	<td valign="top">Address</td>
	<td>{config.Company.location_address} <br>
	{config.Company.location_city} {config.Company.locationState.state} {config.Company.location_zipcode} <br>
	{config.Company.location_country}
	</td>
</tr>
<tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
