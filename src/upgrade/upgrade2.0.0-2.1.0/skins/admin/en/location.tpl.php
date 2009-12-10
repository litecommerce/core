<?php

$addToLocation =<<<EOT
<span IF="target=#extra_fields#">&nbsp;::&nbsp;Product extra fields</span>
<span IF="target=#orders_stats#">&nbsp;::&nbsp;<a href="admin.php?target=orders_stats" class="NavigationPath">Orders statistics</a></span>
<span IF="target=#top_sellers#">&nbsp;::&nbsp;<a href="admin.php?target=orders_stats" class="NavigationPath">Top sellers</a></span>
EOT;

$source .= $addToLocation;
?>
