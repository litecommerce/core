<?php

$source = strReplace('table FOREACH="category.subcategories,category">', '<table FOREACH="category.subcategories,subcategory">', $source, __FILE__, __LINE__);
$source = strReplace('<tr IF="category.enabled">', '<tr>', $source, __FILE__, __LINE__);
$source = strReplace('<a href="cart.php?target=category&category_id={category.category_id}"><FONT class="ItemsList">{category.name}</FONT></a>', '<a href="cart.php?target=category&category_id={subcategory.category_id}"><FONT class="ItemsList">{subcategory.name}</FONT></a>', $source, __FILE__, __LINE__);
?>
