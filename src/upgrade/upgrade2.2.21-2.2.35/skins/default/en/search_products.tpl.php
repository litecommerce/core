<?php

	$find_str = <<<EOT
    <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td valign="center">
<!-- [button] -->
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD class="SearchButtonBG"><a href="javascript: document.search_form.submit()" title="Search"><FONT class="FormButton"><img src="images/search.gif" width=11 height=11 align=middle border="0" alt=""></FONT></a></TD>
</TR>
</TABLE>
<!-- [/button] -->
    </td>
    <td>
        &nbsp;&nbsp;<img src="images/modules/AdvancedSearch/plus_advanced.gif" alt="">&nbsp;<a href="cart.php?target=advanced_search" style="TEXT-DECORATION: underline;" title="Advanced search">Advanced</a>
EOT;
	$replace_str = <<<EOT
    <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td valign="middle">
    <!-- [button] -->
    <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
    <TR>
    <TD class="SearchButtonBG"><a href="javascript: document.search_form.submit()" title="Search"><FONT class="FormButton"><img src="images/search.gif" width=11 height=11 align=middle border="0" alt=""></FONT></a></TD>
    </TR>
    </TABLE>
    <!-- [/button] -->
    </td>
    <td>
        &nbsp;&nbsp;<img src="images/modules/AdvancedSearch/plus_advanced.gif" alt="">&nbsp;<a href="cart.php?target=advanced_search" style="TEXT-DECORATION: underline;" title="Advanced search">Advanced</a>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
