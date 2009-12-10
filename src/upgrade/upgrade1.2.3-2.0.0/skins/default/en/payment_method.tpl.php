<?php

$source = strReplace('<tr FOREACH="payment_methods,payment_method">', '<tr FOREACH="paymentMethods,payment_method">', $source, __FILE__, __LINE__);

?>
