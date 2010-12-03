<?php

// module installation code

if (!function_exists('file_put_contents')) 
{
    function file_put_contents($file, $content) 
    {
        if (file_exists($file)) 
        {
            unlink($file);
        }
        $fp = fopen($file, "wb") or die("write failed for $file");
        fwrite($fp, $content);
        fclose($fp);
        @chmod($file, 0666);
    }
}

if (!function_exists('file_get_contents')) 
{
    function file_get_contents($f) 
    {
        ob_start();
        $retval = @readfile($f);
        if (false !== $retval) 
        {
        	// no readfile error
            $retval = ob_get_contents();
        }
        ob_end_clean();
        return $retval;
    }
}

if (!function_exists('start_patching'))
{
    function start_patching($title)
    {
    ?>
</PRE>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<TITLE><?php echo $title; ?> installation steps</TITLE>
<STYLE type="text/css">
BODY,P,DIV {FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; COLOR: #000000; FONT-SIZE: 12px;}
TH,TD {FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif; COLOR: #000000; FONT-SIZE: 10px;}
PRE {FONT-FAMILY: Courier, "Courier New"; COLOR: #000000; FONT-SIZE: 12px;}
.Head {BACKGROUND-COLOR: #CDD9E1;}
.Center {BACKGROUND-COLOR: #FFFFFF;}
.Middle {BACKGROUND-COLOR: #EFEFEF;}
</STYLE>
</HEAD>
<BODY bgcolor=#FFFFFF link=#0000FF alink=#4040FF vlink=#800080>
<TABLE border=0 cellpadding=3 cellspacing=2>
<TR class="Head">
<TD nowrap><B>&nbsp;&nbsp;Modifying templates ...&nbsp;</TD>
<TD nowrap><B>&nbsp;&nbsp;Status&nbsp;</TD>
</TR>
    <?php
        global $patching_table_row;
        $patching_table_row = 0;
    }
}

if (!function_exists('end_patching'))
{
    function end_patching()
    {
    ?>
</TABLE>
<P>
</BODY>
</HTML>
<PRE>
<?php
    }
}

if (!function_exists('is_template_patched'))
{
    function is_template_patched($location, $check_str)
    {
        $src = @file_get_contents($location);
        return (strpos($src, $check_str) === false) ? false : true;
    }
}

if (!function_exists('already_patched'))
{
    function already_patched($location)
    {
        global $patching_table_row;
        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;$location&nbsp;</TD><TD nowrap>&nbsp;";
        echo "<FONT COLOR=\"blue\"><B>already patched</B></FONT>";
        echo "&nbsp;</TD></TR>\n";
        $patching_table_row = ($patching_table_row) ? 0 : 1;
    }
}

if (!function_exists('patch_template'))
{
    function patch_template($location, $check_str=null, $find_str=null, $replace_str=null, $add_str=null)
    {
        global $patching_table_row;
        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;$location&nbsp;</TD></TD><TD nowrap>&nbsp;";

        $src = @file_get_contents($location);
        $src = preg_replace("/\r\n/m","\n", $src);
        if (!isset($check_str) || strpos($src, $check_str) === false) 
        {
        	$replace_message = "";
        	if (isset($find_str) && isset($replace_str))
    		{
    			$old_src = $src;
                $src = str_replace($find_str, $replace_str, $src);
                if (strcmp($old_src, $src) == 0)
                {
                    $replace_message = "<FONT COLOR=red><B>&nbsp;(replace failed)&nbsp;</B></FONT>";
                }
    		}
        
       	 	if (isset($add_str))
       	 	{
       	 		$src .= $add_str;
       	 	}
    	
       	 	file_put_contents($location, $src);
       	 	echo "<FONT COLOR=green><B>success</B></FONT>$replace_message";
       	}
       	else 
       	{
       		echo "<FONT COLOR=\"blue\"><B>already patched</B></FONT>";
    	}
       	echo "&nbsp;</TD></TR>\n";
        $patching_table_row = ($patching_table_row) ? 0 : 1;
    }
}

if (!function_exists('copy_schema_template'))
{
    function copy_schema_template($template, $schema, $module, $zone = "default", $locale = "en")
    {
        global $patching_table_row;
        if (empty($schema) || in_array($schema, array("3-columns_classic", "3-columns_modern", "2-columns_classic", "2-columns_modern"))) $schema = "standard";

        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;Replacing&nbsp;$template&nbsp;for&nbsp;<b>$schema</b>&nbsp;skin</TD><TD nowrap>&nbsp;";
        $patching_table_row = ($patching_table_row) ? 0 : 1;

        $from = "skins/$zone/$locale/modules/$module/schemas/templates/$schema/$zone/$locale/modules/$module/$template";
        $to = "skins/$zone/$locale/modules/$module/$template";

        if (file_exists($from)) {
            if (@copy($from, $to)) {
                echo "<FONT COLOR=\"green\"><B>success</B></FONT>";
            } else {
                echo "<FONT COLOR=\"red\"><B>failed</B></FONT>";
            }
        } else {
            echo "<FONT COLOR=\"blue\"><B>skipped</B></FONT>";
        }
        echo "&nbsp;</TD></TR>\n";
    }
}

$MODULE_NAME = "ProductAdviser";
start_patching($MODULE_NAME);

if (is_object($this)) {
    $schema = (!empty($this->layout))?$this->layout:($this->xlite->config->Skin->skin);
}

//////////////////////////////////////
//	COPY SKIN-DEPENDENT TEMPLATES
//////////////////////////////////////

copy_schema_template('RelatedProducts/icons.tpl', $schema, $MODULE_NAME);
copy_schema_template('RelatedProducts/list.tpl', $schema, $MODULE_NAME);
copy_schema_template('RelatedProducts/table.tpl', $schema, $MODULE_NAME);
copy_schema_template('ProductsAlsoBuy/icons.tpl', $schema, $MODULE_NAME);
copy_schema_template('ProductsAlsoBuy/list.tpl', $schema, $MODULE_NAME);
copy_schema_template('ProductsAlsoBuy/table.tpl', $schema, $MODULE_NAME);
copy_schema_template('notify_me.tpl', $schema, $MODULE_NAME);
copy_schema_template('new_arrivals.tpl', $schema, $MODULE_NAME);

//////////////////////////////////////
//	ADMIN ZONE
//////////////////////////////////////

// patching "skins/admin/en/location.tpl"
$location = "skins/admin/en/location.tpl";
$check_str = "modules/CDev/ProductAdviser/location.tpl";
$find_str = <<<EOT
<widget module="EcommerceReports" template="modules/CDev/EcommerceReports/location.tpl">
EOT;
$replace_str = <<<EOT
<widget module="EcommerceReports" template="modules/CDev/EcommerceReports/location.tpl">
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/location.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/main.tpl"
$location = "skins/admin/en/main.tpl";
$check_str = "modules/CDev/ProductAdviser/main.tpl";
$find_str = <<<EOT
<widget module="Affiliate" template="modules/CDev/Affiliate/main.tpl">
EOT;
$replace_str = <<<EOT
<widget module="Affiliate" template="modules/CDev/Affiliate/main.tpl">
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/main.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/management/body.tpl"
$location = "skins/admin/en/management/body.tpl";
$check_str = "modules/CDev/ProductAdviser/menu.tpl";
$find_str = <<<EOT
<widget module="Promotion" template="modules/CDev/Promotion/menu.tpl">
EOT;
$replace_str = <<<EOT
<widget module="Promotion" template="modules/CDev/Promotion/menu.tpl">
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/menu.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// checking for InventoryTracking module
$location = "skins/admin/en/modules/CDev/InventoryTracking/inventory_tracking.tpl";
if (is_template_patched($location, "inventory_tracking")) {
    // patching "skins/admin/en/modules/CDev/InventoryTracking/inventory_tracking.tpl"
    $check_str = "modules/CDev/ProductAdviser/inventory_changed.tpl";
    $find_str = <<<EOT
        <input type="text" name="inventory_data[amount]" size="18" value="{inventory.amount}">
    </td>
</tr>
<tr>
EOT;
    $replace_str = <<<EOT
        <input type="text" name="inventory_data[amount]" size="18" value="{inventory.amount}">
    </td>
</tr>
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/inventory_changed.tpl" visible="{isNotifyPresent(inventory.inventory_id)}" dialog="{dialog}" inventory="{inventory}">
<tr>
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
}

// checking for ProductOptions module
$location = "skins/admin/en/modules/CDev/ProductOptions/inventory_tracking.tpl";
if (is_template_patched($location, "inventory_form")) {
    // patching "skins/admin/en/modules/CDev/ProductOptions/inventory_tracking.tpl"
    $check_str = "modules/CDev/ProductAdviser/inventory_changed.tpl";
    $find_str = <<<EOT
    <td valign=top> <input type="text" name="optdata[amount]" value="{ivt.amount}"></td>
</tr>
<tr>
EOT;
    $replace_str = <<<EOT
    <td valign=top> <input type="text" name="optdata[amount]" value="{ivt.amount}"></td>
</tr>
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/inventory_changed.tpl" visible="{isNotifyPresent(ivt.inventory_id)}" dialog="{dialog}" inventory="{ivt}">
<tr>
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
}

// patching "skins/admin/en/product/info.tpl"
$location = "skins/admin/en/product/info.tpl";
if (!is_template_patched($location, "modules/CDev/ProductAdviser/price_changed.tpl")) {
    $find_str = <<<EOT
</tr>

<tr>
  <td valign=middle><font class="FormButton">Tax class</font><br>
EOT;
    $replace_str = <<<EOT
</tr>
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/price_changed.tpl" visible="{priceNotifyPresent}" dialog="{dialog}">

<tr>
  <td valign=middle><font class="FormButton">Tax class</font><br>
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
<widget module="WholesaleTrading" template="modules/CDev/WholesaleTrading/memberships/membership_product.tpl">
EOT;
    $replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/CDev/WholesaleTrading/memberships/membership_product.tpl">
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/product.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

// patching "skins/admin/en/product/product_list.tpl"
$location = "skins/admin/en/product/product_list.tpl";
$check_str = "modules/CDev/ProductAdviser/price_list_changed.tpl";
if (!is_template_patched($location, $check_str)) {
    $find_str = <<<EOT
<tr FOREACH="pager.pageData,product">
EOT;
    $replace_str = <<<EOT
<tbody FOREACH="pager.pageData,product">
<tr>
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
</tr>
<tr><td colspan=5>&nbsp;</td></tr>
EOT;
    $replace_str = <<<EOT
</tr>
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/price_list_changed.tpl" visible="{isNotifyPresent(product.product_id)}" dialog="{dialog}" product="{product}">
</tbody>
<tr><td colspan=5>&nbsp;</td></tr>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}
$find_str = <<<EOT
        <a href="admin.php?target=product&product_id={product.product_id}&backUrl={url:u}"><font class="ItemsList"><u>{product.name:h}</u></font></a>
EOT;
$replace_str = <<<EOT
        <a href="admin.php?target=product&product_id={product.product_id}&backUrl={url:u}"><font class="ItemsList"><u>{product.name:h}</u></font></a><widget module="ProductAdviser" template="modules/CDev/ProductAdviser/product_list.tpl" product="{product}">
EOT;
patch_template($location, "modules/CDev/ProductAdviser/product_list.tpl", $find_str, $replace_str);

// patching "skins/admin/en/product/search.tpl"
$location = "skins/admin/en/product/search.tpl";
$check_str = "modules/CDev/ProductAdviser/product_search.tpl";
$find_str = <<<EOT
            <input type="checkbox" name="subcategory_search" checked="{subcategory_search}">
        </TD>
    </TR>
EOT;
$replace_str = <<<EOT
            <input type="checkbox" name="subcategory_search" checked="{subcategory_search}">
        </TD>
    </TR>
    <widget module="ProductAdviser" template="modules/CDev/ProductAdviser/product_search.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

//////////////////////////////////////
//	CUSTOMER ZONE
//////////////////////////////////////

// patching "skins/default/en/category_products.tpl"
$location = "skins/default/en/category_products.tpl";
if (!is_template_patched($location, "modules/CDev/ProductAdviser/PriceNotification/category_button.tpl")) {
    $find_str = <<<EOT
                <!--AFTER PRICE-->
EOT;
    $replace_str = <<<EOT
                <widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/category_button.tpl" visible="{!priceNotificationSaved}" product="{product}" visible="{!getPriceNotificationSaved(product.product_id)}">
                <!--AFTER PRICE-->
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
<widget name="pager">
EOT;
    $replace_str = <<<EOT
<widget name="pager">

<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/notify_form.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$skin2_find_str = <<<EOT
<widget module="Bestsellers" target="main,category" mode="" class="CBestsellers" template="common/dialog.tpl" body="modules/CDev/Bestsellers/bestsellers.tpl" head="Bestsellers" visible="{!config.Bestsellers.bestsellers_menu}" name="bestsellerswidget">
EOT;
// is that 2 columns skin ?
if (is_template_patched('skins/default/en/main.tpl', $skin2_find_str)) {
//////////////////////////////////////
//	2 columns skin
//////////////////////////////////////

    // patching "skins/default/en/checkout/checkout.tpl"
    $location = "skins/default/en/checkout/checkout.tpl";
    if (!is_template_patched($location, "modules/CDev/ProductAdviser/OutOfStock/checkout_item.tpl")) {
    	$find_str = <<<EOT
<tr FOREACH="cart.items,key,item" valign=top>
EOT;
    	$replace_str = <<<EOT
<tbody FOREACH="cart.items,key,item">
<tr valign=top>
EOT;
    	patch_template($location, null, $find_str, $replace_str);

    	$find_str = <<<EOT
</table>
<hr>
EOT;
    	$replace_str = <<<EOT
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/checkout_item.tpl" visible="{xlite.PA_InventorySupport}">
</tbody>
</table>
<hr>
EOT;
    	patch_template($location, null, $find_str, $replace_str);

    	$find_str = <<<EOT
</form>
EOT;
    	$replace_str = <<<EOT
</form>
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    } else {
        already_patched($location);
    }

    // patching "skins/default/en/main.tpl"
    $location = "skins/default/en/main.tpl";
    if (!is_template_patched($location, "modules/CDev/ProductAdviser/main.tpl")) {
    	$find_str = <<<EOT
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" IF="{auth.isLogged()}" />
EOT;
    	$replace_str = <<<EOT
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" IF="{auth.isLogged()}" />
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/main.tpl">
EOT;
    	patch_template($location, null, $find_str, $replace_str);

    	$find_str = <<<EOT
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
EOT;
    	$replace_str = <<<EOT
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
<widget module="ProductAdviser" class="\XLite\Module\CDev\ProductAdviser\View\NewArrivals" template="common/sidebar_box.tpl" display_in="menu">
<widget module="ProductAdviser" target="main,category,product,cart" class="CRecentliesProducts" template="common/sidebar_box.tpl" head="Recently viewed" dir="modules/ProductAdviser/RecentlyViewed">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    } else {
        already_patched($location);
    }

} else {
//////////////////////////////////////
//	3 columns skin
//////////////////////////////////////

    // patching "skins/default/en/checkout/checkout.tpl"
    $location = "skins/default/en/checkout/checkout.tpl";
    if (!is_template_patched($location, "modules/CDev/ProductAdviser/OutOfStock/checkout_item.tpl")) {
    	$find_str = <<<EOT
<tr FOREACH="cart.items,key,item" valign=top>
EOT;
    	$replace_str = <<<EOT
<tbody FOREACH="cart.items,key,item">
<tr valign=top>
EOT;
    	patch_template($location, null, $find_str, $replace_str);

    	$find_str = <<<EOT
</table>
<hr>
EOT;
    	$replace_str = <<<EOT
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/checkout_item.tpl" visible="{xlite.PA_InventorySupport}">
</tbody>
</table>
<hr>
EOT;
    	patch_template($location, null, $find_str, $replace_str);

    	$find_str = <<<EOT
</form>
EOT;
    	$replace_str = <<<EOT
</form>
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    } else {
        already_patched($location);
    }

    // patching "skins/default/en/main.tpl"
    $location = "skins/default/en/main.tpl";
    if (!is_template_patched($location, "modules/CDev/ProductAdviser/main.tpl")) {
    	$find_str = <<<EOT
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" IF="{auth.isLogged()}" />
EOT;
    	$replace_str = <<<EOT
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" IF="{auth.isLogged()}" />
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/main.tpl">
EOT;
    	patch_template($location, null, $find_str, $replace_str);

    	$find_str = <<<EOT
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" IF="{auth.isLogged()}" />
EOT;
    	$replace_str = <<<EOT
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" IF="{auth.isLogged()}" />
<widget module="ProductAdviser" class="\XLite\Module\CDev\ProductAdviser\View\NewArrivals" template="common/sidebar_box.tpl" display_in="menu">
<widget module="ProductAdviser" target="main,category,product,cart" class="CRecentliesProducts" template="common/sidebar_box.tpl" head="Recently viewed" dir="modules/ProductAdviser/RecentlyViewed">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    } else {
        already_patched($location);
    }
}

// patching "skins/default/en/location.tpl"
$location = "skins/default/en/location.tpl";
$check_str = "modules/CDev/ProductAdviser/location.tpl";
$find_str = <<<EOT
<widget module="Newsletters" template="modules/CDev/Newsletters/location.tpl">

EOT;
$replace_str = <<<EOT
<widget module="Newsletters" template="modules/CDev/Newsletters/location.tpl">
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/location.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// checking for InventoryTracking module
$location = "skins/default/en/modules/CDev/InventoryTracking/out_of_stock.tpl";
if (is_template_patched($location, "OutOfStock")) {
    // patching "skins/default/en/modules/CDev/InventoryTracking/out_of_stock.tpl"
    $check_str = "modules/CDev/ProductAdviser/OutOfStock/add_to_cart.tpl";
    $find_str = <<<EOT
<table border=0 align=center><tr align=center><td class="OutOfStock"> &gt;&gt; Product is out of stock! &lt;&lt; <p class="ErrorMessage">Please try to select another product options</td></tr><tr><td>&nbsp;</td></tr></table>
EOT;
    $replace_str = <<<EOT
<table border=0 align=center><tr align=center><td class="OutOfStock"> &gt;&gt; Product is out of stock! &lt;&lt; <P class="ErrorMessage">Please try to select another product options</td></tr><widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/add_to_cart.tpl" visible="{xlite.PA_InventorySupport}"><tr><td>&nbsp;</td></tr></table>
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
}

// checking for InventoryTracking module
$location = "skins/default/en/modules/CDev/InventoryTracking/product_quantity.tpl";
if (is_template_patched($location, "product.inventory.amount")) {
    // patching "skins/default/en/modules/CDev/InventoryTracking/product_quantity.tpl"
    $check_str = "modules/CDev/ProductAdviser/OutOfStock/product_quantity.tpl";
    $find_str = <<<EOT
<tr><td width="30%" class="ProductDetails">Quantity:</td><td IF="{product.inventory.amount}" class="ProductDetails" nowrap>{product.inventory.amount} item(s) available</td><td IF="{!product.inventory.amount}" class="ErrorMessage" nowrap>- out of stock -</td></tr>
EOT;
    $replace_str = <<<EOT
<tr><td width="30%" class="ProductDetails">Quantity:</td><td IF="{product.inventory.amount}" class="ProductDetails" nowrap>{product.inventory.amount} item(s) available</td><td IF="{!product.inventory.amount}" class="ErrorMessage" nowrap>- out of stock -</td></tr>
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/product_quantity.tpl" visible="{xlite.PA_InventorySupport}">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
}

// checking for FeaturedProducts module
$location = "skins/default/en/modules/CDev/FeaturedProducts/featuredProducts_icons.tpl";
if (is_template_patched($location, "featuredProduct")) {
    // patching "skins/default/en/modules/CDev/FeaturedProducts/featuredProducts_icons.tpl"
    $check_str = "modules/CDev/ProductAdviser/PriceNotification/category_button.tpl";
    $find_str = <<<EOT
        <span IF="featuredProduct"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(featuredProduct.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {featuredProduct.product.priceMessage:h}</FONT></span>
EOT;
    $replace_str = <<<EOT
        <span IF="featuredProduct"><FONT class="ProductPriceTitle">Price: </FONT><FONT class="ProductPrice">{price_format(featuredProduct.product,#listPrice#):h}</FONT><FONT class="ProductPriceTitle"> {featuredProduct.product.priceMessage:h}</FONT><br><widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/category_button.tpl" visible="{!priceNotificationSaved}" product="{featuredProduct.product}" visible="{!getPriceNotificationSaved(featuredProduct.product.product_id)}"></span>
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);

    $check_str = "modules/CDev/ProductAdviser/PriceNotification/notify_form.tpl";
    $find_str = <<<EOT
</tbody>
</table>
EOT;
    $replace_str = <<<EOT
</tbody>
</table>

<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/notify_form.tpl">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
}

// checking for FeaturedProducts module
$location = "skins/default/en/modules/CDev/FeaturedProducts/featuredProducts.tpl";
if (is_template_patched($location, "featuredProduct")) {
    // patching "skins/default/en/modules/CDev/FeaturedProducts/featuredProducts.tpl"
    $check_str = "modules/CDev/ProductAdviser/PriceNotification/category_button.tpl";
    $find_str = <<<EOT
<!--AFTER PRICE-->
EOT;
    $replace_str = <<<EOT
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/category_button.tpl" visible="{!priceNotificationSaved}" product="{featuredProduct.product}" visible="{!getPriceNotificationSaved(featuredProduct.product.product_id)}">
<!--AFTER PRICE-->
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);

    $check_str = "modules/CDev/ProductAdviser/PriceNotification/notify_form.tpl";
    $find_str = '<p FOREACH="category.featuredProducts,featuredProduct">';
    if (is_template_patched($location, $find_str)) {
        $replace_str = '<div FOREACH="category.featuredProducts,featuredProduct">';
        
        patch_template($location, null, $find_str, $replace_str);
        
        $find_str = <<<EOT
</table>
</p>
EOT;
        
        $replace_str = <<<EOT
</table>
</div>
EOT;
        patch_template($location, null, $find_str, $replace_str);
    }

    $find_str = <<<EOT
</table>
</div>
EOT;

    $replace_str = <<<EOT
</table>
</div>

<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/notify_form.tpl">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
}

// patching "skins/default/en/product_details.tpl"
$location = "skins/default/en/product_details.tpl";
if (!is_template_patched($location, "modules/CDev/ProductAdviser/PriceNotification/product_button.tpl")) {
    $find_str = <<<EOT
        <widget module="ProductOptions" template="modules/CDev/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
EOT;
    $replace_str = <<<EOT
        <widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/product_button.tpl" visible="{!priceNotificationSaved}">
        <widget module="ProductOptions" template="modules/CDev/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
</form>
EOT;
    $replace_str = <<<EOT
</form>

<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/PriceNotification/notify_form.tpl" visible="{!priceNotificationSaved}">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

// patching "skins/default/en/shopping_cart/body.tpl"
$location = "skins/default/en/shopping_cart/body.tpl";
$check_str = "modules/CDev/ProductAdviser/OutOfStock/notify_form.tpl";
$find_str = <<<EOT
</form>
EOT;
$replace_str = <<<EOT
</form>

<widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/default/en/shopping_cart/item.tpl"
$location = "skins/default/en/shopping_cart/item.tpl";
$check_str = "modules/CDev/ProductAdviser/OutOfStock/cart_item.tpl";
$find_str = <<<EOT
        <FONT class="ProductPrice">{price_format(item,#total#):h}</FONT>
EOT;
$replace_str = <<<EOT
        <FONT class="ProductPrice">{price_format(item,#total#):h}</FONT>
        <widget module="ProductAdviser" template="modules/CDev/ProductAdviser/OutOfStock/cart_item.tpl" visible="{xlite.PA_InventorySupport}">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

end_patching();

?>
