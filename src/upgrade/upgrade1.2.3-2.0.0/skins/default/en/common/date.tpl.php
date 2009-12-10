<?php

$search =<<<EOT
<select name="{formMonth}">
	<option value="1" selected="{isSelected(#month#,#1#)}">January</option>
	<option value="2" selected="{isSelected(#month#,#2#)}">February</option>
	<option value="3" selected="{isSelected(#month#,#3#)}">March</option>
	<option value="4" selected="{isSelected(#month#,#4#)}">April</option>
	<option value="5" selected="{isSelected(#month#,#5#)}">May</option>
	<option value="6" selected="{isSelected(#month#,#6#)}">June</option>
	<option value="7" selected="{isSelected(#month#,#7#)}">July</option>
	<option value="8" selected="{isSelected(#month#,#8#)}">August</option>
	<option value="9" selected="{isSelected(#month#,#9#)}">September</option>
	<option value="10" selected="{isSelected(#month#,#10#)}">October</option>
	<option value="11" selected="{isSelected(#month#,#11#)}">November</option>
	<option value="12" selected="{isSelected(#month#,#12#)}">December</option>
EOT;

$replace =<<<EOT
<select name="{field}Month">
	<option value="1" selected="{month=1}">January</option>
	<option value="2" selected="{month=2}">February</option>
	<option value="3" selected="{month=3}">March</option>
	<option value="4" selected="{month=4}">April</option>
	<option value="5" selected="{month=5}">May</option>
	<option value="6" selected="{month=6}">June</option>
	<option value="7" selected="{month=7}">July</option>
	<option value="8" selected="{month=8}">August</option>
	<option value="9" selected="{month=9}">September</option>
	<option value="10" selected="{month=10}">October</option>
	<option value="11" selected="{month=11}">November</option>
	<option value="12" selected="{month=12}">December</option>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

$search =<<<EOT
<select name="{formDay}">
	<option FOREACH="day,v" value="{v}" selected="{isSelected(#day#,v)}">{v}</option>
EOT;

$replace =<<<EOT
<select name="{field}Day">
	<option FOREACH="days,v" value="{v}" selected="{day=v}">{v}</option>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

$search =<<<EOT
<select name="{formYear}">
	<option FOREACH="year,v" value="{v}" selected="{isSelected(#year#,v)}">{v}</option>
EOT;

$replace =<<<EOT
<select name="{field}Year">
	<option FOREACH="years,v" value="{v}" selected="{year=v}">{v}</option>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

?>
