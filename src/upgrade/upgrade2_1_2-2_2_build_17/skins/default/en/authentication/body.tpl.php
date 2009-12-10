<?php
    $find_str = <<<EOT
        {wrap(auth.profile,#login#,#20#)}<br>
        Logged in!<br>
        <br>
        <a href="cart.php?target=login&action=logoff" class="SidebarItems"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Logoff</a>
        <br>
    </td>
</tr>
EOT;
	$replace_str = <<<EOT
        {wrap(auth.profile,#login#,#20#)}<br>
        Logged in!<br>
        <br>
        <a href="cart.php?target=login&action=logoff" class="SidebarItems"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" alt=""> Logoff</a>
        <br>
    </td>
</tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
