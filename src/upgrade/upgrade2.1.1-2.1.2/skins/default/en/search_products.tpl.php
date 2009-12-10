<?php
	$find_str = <<<EOT
        <input type="text" name="substring" style="width:75pt" value="{substring:r}">
EOT;
    $replace_str = <<<EOT
    <span IF="!substring:r"><input type="text" name="substring" style="width:75pt;color:#888888" value="Find product" onFocus="this.value=''; this.style.color='#000000';"></span>
    <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
