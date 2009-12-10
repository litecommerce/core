<?php
    $find_str = <<<EOT
{* Product extra fields template *}
<tbody FOREACH="extraFields,ef">
<tr IF="ef.value" valign=top>
    <td width="30%" class="ProductDetails">{ef.name:h}:</td>
    <td class="ProductDetails">{ef.value:h}</td>
</tr>
EOT;
    $replace_str = <<<EOT
{* Product extra fields template *}
<tbody FOREACH="extraFields,ef">
<tr IF="!ef.value=##" valign=top>
    <td width="30%" class="ProductDetails">{ef.name:h}:</td>
    <td class="ProductDetails">{ef.value:h}</td>
</tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
