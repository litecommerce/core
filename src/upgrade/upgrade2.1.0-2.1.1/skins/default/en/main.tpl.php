<?php
    $source = strReplace('<widget target="product" template="common/dialog.tpl" body="product_details.tpl" head="Catalog"  href="cart.php" showLocationPath>',
'<widget target="product" template="common/dialog.tpl" body="product_details.tpl" head="Catalog"  href="cart.php" visible="{product.available}" showLocationPath>', $source, __FILE__, __LINE__);
    $source = strReplace('<widget target="order" template="common/dialog.tpl" body="common/invoice.tpl" head="Order # {order.order_id}">',
'<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">', $source, __FILE__, __LINE__);
    $source = strReplace('<widget module="Bestsellers" target="main,category" class="CBestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}" name="bestsellerswidget">',
'<widget module="Bestsellers" target="main,category" mode="" class="CBestsellers" template="common/dialog.tpl" body="modules/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}" name="bestsellerswidget">', $source, __FILE__, __LINE__);
    $source = strReplace('<widget module="FeaturedProducts" target="main,category" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">',
'<widget module="FeaturedProducts" target="main,category" mode="" template="common/dialog.tpl" body="{config.FeaturedProducts.featured_products_look}" head="Featured products" visible="{category.featuredProducts&!page}">', $source, __FILE__, __LINE__);
    $source = strReplace('<!-- [/modules] }}} -->', 
"<widget module=\"Newsletters\" template=\"modules/Newsletters/newsletters.tpl\">\n\n<!-- [/modules] }}} -->", $source, __FILE__, __LINE__);
?> 
