<?php
    $find_str = <<<EOT
{config.Company.company_name:h}: Recover password
EOT;
    $replace_str = <<<EOT
{config.Company.company_name:h}: Your new password
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
