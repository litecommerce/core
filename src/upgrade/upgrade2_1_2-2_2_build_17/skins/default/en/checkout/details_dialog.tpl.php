<?php
    $find_str = <<<EOT
<p>
<widget template="checkout/credit_card.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/credit_card.tpl#}">
<widget template="checkout/echeck.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/echeck.tpl#}">
<widget template="checkout/offline.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/offline.tpl#}">
<widget module="PaySystems" template="modules/PaySystems/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PaySystems/checkout.tpl#}">
EOT;
    $replace_str = <<<EOT
<p>
<widget template="checkout/credit_card.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/credit_card.tpl#}">
<widget module="VerisignLink" template="modules/VerisignLink/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/VerisignLink/checkout.tpl#}">
<widget template="checkout/echeck.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/echeck.tpl#}">
<widget template="checkout/offline.tpl" visible="{cart.paymentMethod.formTemplate=#checkout/offline.tpl#}">
<widget module="PaySystems" template="modules/PaySystems/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PaySystems/checkout.tpl#}">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<widget module="Promotion" template="modules/Promotion/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/Promotion/checkout.tpl#}">
<widget module="2CheckoutCom" template="modules/2CheckoutCom/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/2CheckoutCom/checkout.tpl#}">
<widget module="PayPal" template="modules/PayPal/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PayPal/checkout.tpl#}">
<!-- PAYMENT METHOD FORM -->
EOT;
    $replace_str = <<<EOT
<widget module="Promotion" template="modules/Promotion/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/Promotion/checkout.tpl#}">
<widget module="2CheckoutCom" template="modules/2CheckoutCom/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/2CheckoutCom/checkout.tpl#}">
<widget module="PayPal" template="modules/PayPal/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/PayPal/checkout.tpl#}">
<widget module="Nochex" template="modules/Nochex/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/Nochex/checkout.tpl#}">
<widget module="PayPalPro" template="modules/PayPalPro/standard_checkout.tpl" visible="{cart.paymentMethod.params.solution=#standard#}">
<widget module="PayPalPro" template="modules/PayPalPro/express_checkout.tpl" visible="{cart.paymentMethod.payment_method=#paypalpro_express#}">
<widget module="SecureTrading" template="modules/SecureTrading/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/SecureTrading/checkout.tpl#}">
<widget module="ChronoPay" template="modules/ChronoPay/checkout.tpl" visible="{cart.paymentMethod.formTemplate=#modules/ChronoPay/checkout.tpl#}">
<!-- PAYMENT METHOD FORM -->
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
