<?php

$search =<<<EOT
<FONT class="SidebarItems"><a href="cart.php?target=help&action=recover_password" class="SidebarItems">Recover password</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&action=contactus" class="SidebarItems">Contact us</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&action=privacy_statement" class="SidebarItems">Privacy statement</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&action=conditions" class="SidebarItems">Terms &amp; Conditions</a></FONT><br>
EOT;

$replace =<<<EOT
<FONT class="SidebarItems"><a href="cart.php?target=recover_password" class="SidebarItems">Recover password</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&mode=contactus" class="SidebarItems">Contact us</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&mode=privacy_statement" class="SidebarItems">Privacy statement</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&mode=terms_conditions" class="SidebarItems">Terms &amp; Conditions</a></FONT><br>
EOT;
 
$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

?>
