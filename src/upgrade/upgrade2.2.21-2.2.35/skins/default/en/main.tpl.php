<?php
	$find_str = <<<EOT
<META IFF="metaDescription" name="description" content="{metaDescription:r}">
<META IFF="keywords" name="keywords" content="{keywords:r}">
<LINK href="skins/default/en/style.css"  rel="stylesheet" type="text/css">
</HEAD>
<!-- [/head] -->

EOT;
	$replace_str = <<<EOT
<META IFF="metaDescription" name="description" content="{metaDescription:r}">
<META IFF="keywords" name="keywords" content="{keywords:r}">
<LINK href="skins/default/en/style.css"  rel="stylesheet" type="text/css">
<LINK IFF="xlite.FlyoutCategoriesEnabled" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}" rel="stylesheet" type="text/css">
</HEAD>
<!-- [/head] -->

EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
</TR>
</TABLE>

<BR>
<!-- [/top] -->

EOT;
	$replace_str = <<<EOT
</TR>
</TABLE>

<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_flat.tpl">
<BR>
<!-- [/top] -->

EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
<!-- [right] -->
<widget module="SnsIntegration" template="modules/SnsIntegration/tracker.tpl">
<widget target="main" template="common/sidebar_box.tpl" head="News" dir="menu_news">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
<widget module="ProductAdviser" target="main,category,product,cart,RecentlyViewed" class="CNewArrivalsProducts" template="common/sidebar_box.tpl" head="New Arrivals" dir="modules/ProductAdviser/NewArrivals">
<widget module="ProductAdviser" target="main,category,product,cart" class="CRecentliesProducts" template="common/sidebar_box.tpl" head="Recently viewed" dir="modules/ProductAdviser/RecentlyViewed">
<widget module="Bestsellers" target="main,category" class="CMenuBestsellers" template="common/sidebar_box.tpl" head="Bestsellers" dir="modules/Bestsellers/menu" visible="{config.Bestsellers.bestsellers_menu}">
EOT;
	$replace_str = <<<EOT
<!-- [right] -->
<widget module="SnsIntegration" template="modules/SnsIntegration/tracker.tpl">
<widget target="main" template="common/sidebar_box.tpl" head="News" dir="menu_news">
<div IF="xlite.FlyoutCategoriesEnabled">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_side.tpl" visible="{!target=#main#}">
</div>
<div IF="!xlite.FlyoutCategoriesEnabled">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
</div>
<widget module="ProductAdviser" target="main,category,product,cart,RecentlyViewed" class="CNewArrivalsProducts" template="common/sidebar_box.tpl" head="New Arrivals" dir="modules/ProductAdviser/NewArrivals">
<widget module="ProductAdviser" target="main,category,product,cart" class="CRecentliesProducts" template="common/sidebar_box.tpl" head="Recently viewed" dir="modules/ProductAdviser/RecentlyViewed">
<widget module="Bestsellers" target="main,category" class="CMenuBestsellers" template="common/sidebar_box.tpl" head="Bestsellers" dir="modules/Bestsellers/menu" visible="{config.Bestsellers.bestsellers_menu}">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
</TABLE>

<!-- [end] -->
</BODY>
</HTML>
EOT;
	$replace_str = <<<EOT
</TABLE>

<!-- [end] -->
<div IF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_footer.tpl">
</div>
</body>
</HTML>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
