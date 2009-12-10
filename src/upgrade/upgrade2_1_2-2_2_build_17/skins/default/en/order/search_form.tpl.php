<?php
    $find_str = <<<EOT
<table>
<form action="cart.php" method="GET">
<input type="hidden" name="target" value="order_list">
<input type="hidden" name="mode" value="search">
<TBODY>
EOT;
    $replace_str = <<<EOT
<table>
<form action="cart.php" method="GET" name="order_search_form">
<input type="hidden" name="target" value="order_list">
<input type="hidden" name="mode" value="search">
<TBODY>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<TR>
<TD class=FormButton width=78>&nbsp;</TD>
<TD width=10>&nbsp;</TD>
<TD height=30><INPUT type="submit" value=" Search "> 
&nbsp; </TD></TR></FORM></TBODY></TABLE>&nbsp; 
EOT;
    $replace_str = <<<EOT
<TR>
<TD class=FormButton width=78>&nbsp;</TD>
<TD width=10>&nbsp;</TD>
<TD height=30><widget class="CButton" label="Search" href="javascript: document.order_search_form.submit();"> 
&nbsp; </TD></TR></FORM></TBODY></TABLE>&nbsp; 
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
