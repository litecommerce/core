<?php

$source = strReplace('Fields marked with <font class="Star">*</font> are mandatory.', 'Fields marked with <font class="Star">*</font> are mandatory.'."\n".'<p IF="target=#checkout#" align=justify>If you already have an account, please <a href="cart.php?target=profile&mode=login&returnUrl={dialog.url:u}"><u>login from here</u></a>.</p>', $source, __FILE__, __LINE__);
$source = strReplace('</tbody>', '</tbody>'."\n".'{*extraFields*}', $source, __FILE__, __LINE__);

?>
