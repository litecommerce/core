<widget target="category" template="category_description.tpl" visible="{category.description}">
<widget target="category" mode="" template="common/dialog.tpl" body="{config.General.subcategories_look}" head="Catalog" href="cart.php" visible="{category.subcategories&!page}" showLocationPath>
<widget target="category" template="common/dialog.tpl" body="category_products.tpl" head="Catalog"  href="cart.php" visible="{category.products}" showLocationPath>
<widget target="category" template="common/dialog.tpl" body="category_empty.tpl" head="Catalog"  href="cart.php" visible="{category.empty}" showLocationPath>
