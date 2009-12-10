<?php

$source = strReplace('<tbody FOREACH="split3($t->dialog->subcategories),row">', '<tbody FOREACH="split3(category.subcategories),row">', $source, __FILE__, __LINE__);
$source = strReplace('<td valign="center" FOREACH="row,category" align="center" width="33%">', '<td valign="center" FOREACH="row,subcategory" align="center" width="33%">', $source, __FILE__, __LINE__);
$source = strReplace('<a href="cart.php?target=category&category_id={category.category_id}"><span IF="category.hasImage()"><img src="{category.getImageURL()}" border="0"></span><span IF="!category.hasImage()"><img src="images/no_image.gif" border="0"></span></a>&nbsp;', '<a href="cart.php?target=category&category_id={subcategory.category_id}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0"></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0"></span></a>&nbsp;', $source, __FILE__, __LINE__);
$source = strReplace('<td valign="top" FOREACH="row,category" align="center" width="33%">', '<td valign="top" FOREACH="row,subcategory" align="center" width="33%">', $source, __FILE__, __LINE__);
$source = strReplace('<a href="cart.php?target=category&category_id={category.category_id}"><FONT class="ItemsList">{category.name}</FONT></a>&nbsp;', '<a href="cart.php?target=category&category_id={subcategory.category_id}" IF="subcategory"><FONT class="ItemsList">{subcategory.name}</FONT></a>&nbsp;', $source, __FILE__, __LINE__);

?>
