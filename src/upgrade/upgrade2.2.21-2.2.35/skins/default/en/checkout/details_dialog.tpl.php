<?php

	$find_str = <<<EOT
<widget module="PayPalPro" template="modules/PayPalPro/express_checkout.tpl" visible="{cart.paymentMethod.payment_method=#paypalpro_express#}">
<widget module="SecureTrading" template="modules/SecureTrading/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/SecureTrading/checkout.tpl#}">
<widget module="ChronoPay" template="modules/ChronoPay/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/ChronoPay/checkout.tpl#}">
<!-- PAYMENT METHOD FORM -->
EOT;

	$replace_str = <<<EOT
<widget module="PayPalPro" template="modules/PayPalPro/express_checkout.tpl" visible="{cart.paymentMethod.payment_method=#paypalpro_express#}">
<widget module="SecureTrading" template="modules/SecureTrading/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/SecureTrading/checkout.tpl#}">
<widget module="ChronoPay" template="modules/ChronoPay/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/ChronoPay/checkout.tpl#}">
<widget module="PayFlowLink" template="modules/PayFlowLink/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PayFlowLink/checkout.tpl#}">
<!-- PAYMENT METHOD FORM -->
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
