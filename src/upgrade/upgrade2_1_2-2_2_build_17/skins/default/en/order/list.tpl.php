<?php
    $find_str = <<<EOT
<tr>
    <td colspan="4">
        <input type="hidden" name="target" value="order">
        <input type="submit" value=" Details ">
    </td>
</tr>
</table>
EOT;
    $replace_str = <<<EOT
<tr>
    <td colspan="4">
        <input type="hidden" name="target" value="order">
        <widget class="CButton" label="Details" href="javascript: document.order_form.submit();">
    </td>
</tr>
</table>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
