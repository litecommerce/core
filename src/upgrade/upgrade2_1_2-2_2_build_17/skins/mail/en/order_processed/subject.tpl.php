<?php
    $find_str = <<<EOT
{config.Company.company_name:h}: Order reciept #{order.order_id}!
EOT;
    $replace_str = <<<EOT
{config.Company.company_name:h}: Order receipt #{order.order_id}!
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
