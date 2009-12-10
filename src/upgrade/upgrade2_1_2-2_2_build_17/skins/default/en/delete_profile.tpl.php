<?php
    $find_str = <<<EOT
<td><widget class="CButton" label=" No " href="cart.php?target=profile&mode=delete&submode=cancelled"></td>
</tr>
</table>
</p>
</td>
</tr>
EOT;
    $replace_str = <<<EOT
<td><widget class="CButton" label=" No " href="cart.php?target=profile&mode=delete&submode=cancelled"></td>
</tr>
</table>
</td>
</tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
