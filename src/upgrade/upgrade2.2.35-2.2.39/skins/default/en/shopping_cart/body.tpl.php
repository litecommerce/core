<?php

	$find_str = <<<EOT
<widget template="shopping_cart/buttons.tpl">
</form>
EOT;
	$replace_str = <<<EOT
<widget template="shopping_cart/buttons.tpl">
<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">
</form>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>