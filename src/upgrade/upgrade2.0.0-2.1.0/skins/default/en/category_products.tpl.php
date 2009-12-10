<?php

$source = strReplace('<a href="cart.php?target=product&product_id={product.product_id}&category_id={category_id}" IF="product.hasThumbnail()"><img src="{product.getThumbnailURL()}" border=0 width=70></a> <br><a href="cart.php?target=product&product_id={product.product_id}&category_id={category_id}" IF="product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details&nbsp;</font><img src="images/details.gif" width="13" height="13" border="0" align="absmiddle"></a>', '<a href="cart.php?target=product&product_id={product.product_id}&category_id={category_id}" IF="product.hasThumbnail()"><img src="{product.thumbnailURL:h}" border=0 width=70></a> <br><a href="cart.php?target=product&product_id={product.product_id}&category_id={category_id}" IF="product.hasThumbnail()"><font class="SeeDetails">See&nbsp;details&nbsp;</font><img src="images/details.gif" width="13" height="13" border="0" align="absmiddle"></a>', $source, __FILE__, __LINE__);

?>
