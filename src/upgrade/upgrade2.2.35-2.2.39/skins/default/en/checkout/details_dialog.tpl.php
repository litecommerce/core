<?php

	$find_str = "<tr><td colspan=\"2\"><b>Customer information:<b><hr></td></tr>";
	$replace_str = "<tr><td colspan=\"2\"><b>Customer information:</b><hr></td></tr>";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
<widget module="PayFlowLink" template="modules/PayFlowLink/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PayFlowLink/checkout.tpl#}">
<!-- PAYMENT METHOD FORM -->
EOT;
	$replace_str = <<<EOT
<widget module="PayFlowLink" template="modules/PayFlowLink/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PayFlowLink/checkout.tpl#}">
<widget module="GoogleCheckout" template="modules/GoogleCheckout/google_checkout.tpl" visible="{cart.paymentMethod.payment_method=#google_checkout#}">
<!-- PAYMENT METHOD FORM -->
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>