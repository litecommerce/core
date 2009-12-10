<?php

	$find_str = <<<EOT
<FONT class="SidebarItems"><a href="cart.php?target=help&amp;mode=contactus" class="SidebarItems">Contact us</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&amp;mode=privacy_statement" class="SidebarItems">Privacy statement</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&amp;mode=terms_conditions" class="SidebarItems">Terms &amp; Conditions</a></FONT><br>
EOT;

	$replace_str = <<<EOT
<FONT class="SidebarItems"><a href="cart.php?target=help&amp;mode=contactus" class="SidebarItems">Contact us</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&amp;mode=privacy_statement" class="SidebarItems">Privacy statement</a></FONT><br>
<FONT class="SidebarItems"><a href="cart.php?target=help&amp;mode=terms_conditions" class="SidebarItems">Terms &amp; Conditions</a></FONT><br>
<div IF="!isEmpty(xlite.factory.ExtraPage.pages)">
<widget template="help/pages_links.tpl">
</div>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
