<?php
    $find_str = <<<EOT
<widget template="common/dialog.tpl" body="order/search_form.tpl" head="Search orders">
<span class="Text" IF="mode=#search#&count">
<widget template="common/dialog.tpl" mode="search" body="order/list.tpl" head="Search result">
</span>
<span class="Text" IF="mode=#search#&!count">No orders found</span>
EOT;
    $replace_str = <<<EOT
<widget template="common/dialog.tpl" body="order/search_form.tpl" head="Search orders">
<span class="Text" IF="mode=#search#&count">
<widget template="common/dialog.tpl" mode="search" body="order/list.tpl" head="Search results">
</span>
<span class="Text" IF="mode=#search#&!count">No orders found</span>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
