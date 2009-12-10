<?php

$source = strReplace('<p IF="!cart.empty">', '', $source, __FILE__, __LINE__);
$source = strReplace('<form name="cart_form" action="cart.php" method="POST">', '<form IF="!cart.empty" name="cart_form" action="cart.php" method="POST">', $source, __FILE__, __LINE__);

$source = strReplace('The items in your shopping cart are listed below. To remove any item click "Delete Item". To place your order, please click "CHECKOUT".<hr>', '<p align=justify>The items in your shopping cart are listed below. To remove any item click "Delete Item". To place your order, please click "CHECKOUT".</p>', $source, __FILE__, __LINE__);
$source = strReplace('<span FOREACH="cart.items,cart_id,item">', '<p FOREACH="cart.items,cart_id,item">', $source, __FILE__, __LINE__);
$source = strReplace('</span>'."\n".'<hr>', '</p>'."\n".'<IMG SRC="images/spacer.gif" class=DialogBorder WIDTH="100%" HEIGHT=1 BORDER="0">', $source, __FILE__, __LINE__);
$source = strReplace('<hr>', '<IMG SRC="images/spacer.gif" class=DialogBorder WIDTH="100%" HEIGHT=1 BORDER="0">', $source, __FILE__, __LINE__);
$source = strReplace('</form>'."\n".'</p>', '</form>', $source, __FILE__, __LINE__);

?>
