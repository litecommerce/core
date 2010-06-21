<?php

/**
* @package Module_WishList
* @access private
* @version $Id$
*/

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
        fwrite($fp, "\n");
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

$MODULE_NAME = "WishList";
start_patching($MODULE_NAME);

if (is_object($this)) {
    $schema = (!empty($this->layout))?$this->layout:($this->xlite->config->Skin->skin);
}

//////////////////////////////////////
//	COPY SKIN-DEPENDENT TEMPLATES
//////////////////////////////////////

copy_schema_template('mini_cart/body.tpl', $schema, $MODULE_NAME);
copy_schema_template('common/button.tpl', $schema, $MODULE_NAME);
copy_schema_template('wishlist.tpl', $schema, $MODULE_NAME);
copy_schema_template('send_to_friend.tpl', $schema, $MODULE_NAME);
copy_schema_template('message.tpl', $schema, $MODULE_NAME);
copy_schema_template('item.tpl', $schema, $MODULE_NAME);

//////////////////////////////////////
//	CUSTOMER ZONE
//////////////////////////////////////

if (is_template_patched('skins/default/en/main.tpl',"<!-- [tabs] {{{ -->")) {
    // 2-columns
    $location = "skins/default/en/main.tpl";

    if (!is_template_patched($location, "wish_list_icon.gif"))
    {
        $find_str = <<<EOT
<TD>
<widget if="cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" img="sideicon_cart.gif"/>
<widget if="!cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" img="sideicon_cart_full.gif"/>
</TD>
EOT;
    	$replace_str = <<<EOT
<TD>
<widget if="cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" img="sideicon_cart.gif"/>
<widget if="!cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" img="sideicon_cart_full.gif"/>
</TD>
<TD>
<widget module="WishList" visible="{auth.logged&wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon.gif">
<widget module="WishList" visible="{auth.logged&!wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon_empty.gif">
</TD>
EOT;
    	patch_template($location, "wish_list_icon.gif", $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }
}

$location = "skins/default/en/product_details.tpl";

$find_str = <<<EOT
            <td colspan=2>
                <widget class="CButton" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
            </td>
EOT;
$replace_str = <<<EOT
            <td>
                <widget class="CButton" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
            </td>
            <td>
                <widget module="WishList" template="modules/WishList/add.tpl" href="javascript: if (isValid()) document.add_to_cart.target.value = 'wishlist'; document.add_to_cart.action.value = 'add'; document.add_to_cart.submit();">
            </td>
EOT;
patch_template($location, "WishList", $find_str, $replace_str);

$find_str = <<<EOT
                <widget module="WishList" template="modules/WishList/add.tpl" href="javascript: if (isValid()) document.add_to_cart.target.value = 'wishlist'; document.add_to_cart.action.value = 'add'; document.add_to_cart.submit();">
EOT;
$replace_str = <<<EOT
                <widget module="WishList" template="modules/WishList/add.tpl" href="javascript: WishList_Add2Cart();">
EOT;
patch_template($location, "WishList_Add2Cart", $find_str, $replace_str);

$location = "skins/default/en/category_products.tpl";

if (!is_template_patched($location, "WishList"))
{
    $find_str = <<<EOT
                <br><br>
                <widget template="buy_now.tpl" product="{product}">
EOT;
    $replace_str = <<<EOT
                <br><br>
                <table cellpadding="0" cellspacing="0" border="0">  
                <tr>    
                    <td><widget template="buy_now.tpl" product="{product}"></td>
                    <td width="40">&nbsp;</td> 
                    <td><widget module="WishList" template="modules/WishList/add.tpl" href="cart.php?target=wishlist&action=add&product_id={product.product_id}"></td>
                </tr>       
                </table> 
EOT;
    patch_template($location, "WishList", $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/default/en/search_result.tpl";

if (!is_template_patched($location, "WishList"))
{
    $find_str = <<<EOT
        <br><br>
        <widget template="buy_now.tpl" product="{product}">
EOT;
    $replace_str = <<<EOT
        <br><br>
        <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><widget template="buy_now.tpl" product="{product}"></td>
            <td width="40">&nbsp;</td>
            <td><widget module="WishList" template="modules/WishList/add.tpl" href="cart.php?target=wishlist&action=add&product_id={product.product_id}"></td>
        </tr>
        </table>
EOT;
    patch_template($location, "WishList", $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/default/en/location.tpl";

if (!is_template_patched($location, "WishList"))
{
    $find_str = <<<EOT
</font>
EOT;
    $replace_str = <<<EOT
<widget module="WishList" template="modules/WishList/location.tpl">
</font>
EOT;
    patch_template($location, "WishList", $find_str, $replace_str);
}
else
{
    already_patched($location);
}

if (!is_template_patched('skins/default/en/main.tpl',"<!-- [tabs] {{{ -->")) {
    // 3-columns
    $location = "skins/default/en/main.tpl";

    if (!is_template_patched($location, "send_to_friend.tpl"))
    {
        $find_str = <<<EOT
<!-- [/center] -->
EOT;
    	$replace_str = <<<EOT
<widget module="WishList" target="wishlist,product" mode="MessageSent" template="common/dialog.tpl" body="modules/WishList/message.tpl" head="Message has been sent">
<widget module="WishList" target="wishlist" head="Wish List" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl">
<widget module="WishList" target="product" head="Send to a friend" template="common/dialog.tpl" body="modules/WishList/send_to_friend.tpl">
<!-- [/center] -->
EOT;
    	patch_template($location, "send_to_friend.tpl", $find_str, $replace_str);
    }
    else
    {
        already_patched($location);
    }
} else {
    // 2-columns
    $location = "skins/default/en/main.tpl";

    if (!is_template_patched($location, "send_to_friend.tpl"))
    {
        $find_str = <<<EOT
<!-- [/modules] }}} -->
EOT;
    	$replace_str = <<<EOT
<widget module="WishList" target="wishlist,product" mode="MessageSent" template="common/dialog.tpl" body="modules/WishList/message.tpl" head="Message has been sent">
<widget module="WishList" target="wishlist" head="Wish List" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl">
<widget module="WishList" target="product" head="Send to a friend" template="common/dialog.tpl" body="modules/WishList/send_to_friend.tpl">
<!-- [/modules] }}} -->
EOT;
    	patch_template($location, "send_to_friend.tpl", $find_str, $replace_str);
    }
    else
    {
        already_patched($location);
    }
}

$location = "skins/admin/en/main.tpl";

if (!is_template_patched($location, "WishList"))
{
    $find_str = <<<EOT
<!-- [/center] -->
EOT;
    $replace_str = <<<EOT
<widget module="WishList" target="wishlists" template="modules/WishList/wishlists.tpl" head="Wish Lists">
<widget module="WishList" target="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist.tpl" head="Wish List">
<!-- [/center] -->
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/location.tpl";
    
if (!is_template_patched($location, "WishList"))
{
    $find_str = <<<EOT
<widget module="Promotion" template="modules/Promotion/location.tpl">
EOT;
    $replace_str = <<<EOT
<widget module="Promotion" template="modules/Promotion/location.tpl">
<widget module="WishList" template="modules/WishList/location.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/management/body.tpl";
    
if (!is_template_patched($location, "WishList"))
{
    $find_str = <<<EOT
<a href="admin.php?target=order_list">Orders</a><br>
EOT;
    $replace_str = <<<EOT
<a href="admin.php?target=order_list">Orders</a><br>
<widget module="WishList" template="modules/WishList/menu.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

if (is_template_patched('skins/default/en/main.tpl',"<!-- [tabs] {{{ -->")) {
    // 2-columns
    $location = "skins/default/en/main.tpl";

    if (!is_template_patched($location, "wishlist_note"))
    {
    	$find_str = <<<EOT
<!-- [main] {{{ -->
EOT;
    	$replace_str = <<<EOT
<!-- [main] {{{ -->
<widget module="WishList" mode="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist_note.tpl" head="Wishlist Notification">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }
} else {
    // 3-columns
    $location = "skins/default/en/main.tpl";

    if (!is_template_patched($location, "wishlist_note"))
    {
    	$find_str = <<<EOT
<widget template="location.tpl" name="locationWidget">
EOT;
    	$replace_str = <<<EOT
<widget template="location.tpl" name="locationWidget">
<widget module="WishList" mode="wishlist" template="common/dialog.tpl" body="modules/WishList/wishlist_note.tpl" head="Wishlist Notification">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }

    $location = "skins/default/en/mini_cart/body.tpl";

    if (!is_template_patched($location, "WishList/mini_cart"))
    {
    	$find_str = <<<EOT
<table width="100%">
<tr IF="cart.empty">
EOT;
    	$replace_str = <<<EOT
<span IF="!xlite.WishListEnabled">
<table width="100%">
<tr IF="cart.empty">
EOT;
    	patch_template($location, null, $find_str, $replace_str);

    	$add_str = <<<EOT
</span>
<span IF="xlite.WishListEnabled">
<widget module="WishList" template="modules/WishList/mini_cart/body.tpl">
</span>
EOT;
        patch_template($location, null, null, null, $add_str);
    }
    else
    {
    	already_patched($location);
    }

    $location = "skins/default/en/product_details.tpl";

    if (!is_template_patched($location, "modules/WishList/add.tpl"))
    {
    	$find_str = <<<EOT
            <td colspan=2>
                <widget class="CButton" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
            </td>
EOT;
    	$replace_str = <<<EOT
            <td>
                <widget class="CButton" label="Add to Cart" href="javascript: if (isValid()) document.add_to_cart.submit()" img="cart4button.gif" font="FormButton">
            </td>
            <td IF="!config.General.add_on_mode">
                <widget module="WishList" template="modules/WishList/add.tpl" href="javascript: WishList_Add2Cart();">
            </td>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }

}
 	
end_patching();

?>
