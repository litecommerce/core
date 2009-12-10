<?php
    $find_str = <<<EOT
<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.subcategories,4),row">
<tr>
   <td valign="center" FOREACH="row,subcategory" align="center" width="{percent(4)}%">
      <a href="cart.php?target=category&category_id={subcategory.category_id}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0"></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0"></span></a>&nbsp;
   </td>
</tr>
EOT;
	$replace_str = <<<EOT
<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.subcategories,4),row">
<tr>
   <td valign="middle" FOREACH="row,subcategory" align="center" width="{percent(4)}%">
      <a href="cart.php?target=category&category_id={subcategory.category_id}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0"></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0"></span></a>&nbsp;
   </td>
</tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
