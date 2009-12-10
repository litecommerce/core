<?php

	$find_str = <<<EOT
<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0">
<tr>
    <td valign="top" IF="category.hasImage()">
            <img src="{category.getImageURL():h}" border="0" alt="">
    </td>
    <td valign="top">
        <table FOREACH="category.subcategories,subcategory">
EOT;

	$replace_str = <<<EOT
<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0">
<tr>
    <td valign="top" IF="category.hasImage()">
            <img src="{category.getImageURL()}" border="0" alt="">
    </td>
    <td valign="top">
        <table FOREACH="category.subcategories,subcategory">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
