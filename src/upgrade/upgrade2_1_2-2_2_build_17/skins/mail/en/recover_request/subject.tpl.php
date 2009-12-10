<?php
    $find_str = <<<EOT
{config.Company.company_name:h}: Recover password confirmation
EOT;
    $replace_str = <<<EOT
{config.Company.company_name:h}: Confirm password recovery request
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
