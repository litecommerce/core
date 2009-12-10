<?php

	$find_str = <<<EOT
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
    <td valign="top" width="70">
        <a href="{item.url}" IF="item.hasThumbnail()"><img src="{item.thumbnailURL:h}" border="0" width="70" alt=""></a>
    </td>
    <td>
        <a href="{item.url}"><FONT class="ProductTitle">{item.name}</FONT></a><br><br>
EOT;

	$replace_str = <<<EOT
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
    <td valign="top" width="70">
        <a href="{item.url}" IF="item.hasThumbnail()"><img src="{item.thumbnailURL}" border="0" width="70" alt=""></a>
    </td>
    <td>
        <a href="{item.url}"><FONT class="ProductTitle">{item.name}</FONT></a><br><br>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
