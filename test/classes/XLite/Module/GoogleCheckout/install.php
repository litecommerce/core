<?php

/*
* module installation code
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
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
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
        echo "<FONT COLOR=blue><B>already patched</B></FONT>";
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
       		echo "<FONT COLOR=blue><B>already patched</B></FONT>";
    	}
       	echo "&nbsp;</TD></TR>\n";
        $patching_table_row = ($patching_table_row) ? 0 : 1;
    }
}

start_patching('GoogleCheckout');

////////////////// Admin area ////////////////////////////////
$location = "skins/admin/en/product/info.tpl";
$check_str = "GoogleCheckout";
if (!is_template_patched($location, $check_str)) {
    $find_str = <<<EOT
{*extraFields*}
EOT;
    $replace_str = <<<EOT
{*extraFields*}
<widget module="GoogleCheckout" template="modules/GoogleCheckout/product/info.tpl">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}


$location = "skins/admin/en/product/add.tpl";
$check_str = "GoogleCheckout";
if (!is_template_patched($location, $check_str)) {
    $find_str = <<<EOT
{*extraFields*}
EOT;
    $replace_str = <<<EOT
{*extraFields*}
<widget module="GoogleCheckout" template="modules/GoogleCheckout/product/add.tpl">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}


$location = "skins/admin/en/main.tpl";
$check_str = "GoogleCheckoutEnabled";
if (!is_template_patched($location, $check_str)) {
    $find_str = <<<EOT
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
EOT;
    $replace_str = <<<EOT
{if:!xlite.GoogleCheckoutEnabled}
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
{else:}
<widget module="AOM" template="modules/AOM/main.tpl">
{end:}
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/main.tpl";
$check_str = "modules/GoogleCheckout/main.tpl";
if (!is_template_patched($location, $check_str)) {
    $find_str = <<<EOT
<widget target="shipping_zones" class="CTabber" body="{pageTemplate}" switch="target">
EOT;
    $replace_str = <<<EOT
<widget target="shipping_zones" class="CTabber" body="{pageTemplate}" switch="target">
<widget module="GoogleCheckout" template="modules/GoogleCheckout/main.tpl">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/common/select_status.tpl";
$check_str = 'GoogleCheckout';
if (!is_template_patched($location, $check_str))
{
    $find_str = <<<EOT
<select name="{field}">
EOT;
    $replace_str = <<<EOT
<select name="{field}" {if:xlite.GoogleCheckoutEnabled&googleCheckoutOrder}disabled{end:}>
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/payment_methods/body.tpl";
$content = file_get_contents($location);
if (strpos($content, "{if:payment_method.enabled}") !== false) {
    $check_str = 'google_checkout';
    if (!is_template_patched($location, $check_str))
    {
    	$find_str = <<<EOT
{if:payment_method.enabled}
EOT;
        $replace_str = <<<EOT
{if:payment_method.enabled&!payment_method.payment_method=#google_checkout#}
EOT;
        patch_template($location, $check_str, $find_str, $replace_str);
    } else {
        already_patched($location);
    }
}

$location = "skins/admin/en/modules/AOM/common/select_status.tpl";
if (file_exists($location)) {
    $check_str = 'GoogleCheckout';
    if (!is_template_patched($location, $check_str))
    {
    	$find_str = <<<EOT
<select name="{field}" style="{widget.style}">
EOT;
        $replace_str = <<<EOT
<select name="{field}" style="{widget.style}" {if:xlite.GoogleCheckoutEnabled&googleCheckoutOrder}disabled{end:}>
EOT;
        patch_template($location, $check_str, $find_str, $replace_str);
    } else {
        already_patched($location);
    }
}


////////////////// Customer area /////////////////////////////

$location = "skins/default/en/checkout/details_dialog.tpl";
if (!is_template_patched($location, "GoogleCheckout"))
{
    $find_str = <<<EOT
<!-- PAYMENT METHOD FORM -->
EOT;
    $replace_str = <<<EOT
<widget module="GoogleCheckout" template="modules/GoogleCheckout/google_checkout.tpl" visible="{cart.paymentMethod.payment_method=#google_checkout#}">
<!-- PAYMENT METHOD FORM -->
EOT;
    patch_template($location, "GoogleCheckout", $find_str, $replace_str);
} else {
    already_patched($location);
}


$location = "skins/default/en/shopping_cart/item.tpl";
if (!is_template_patched($location, "common/button_shopping_cart.tpl")) {
    // default skin patch
    if (!is_template_patched($location, "GoogleCheckout")) {
        $find_str = <<<EOT
<widget class="CButton" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
EOT;
        $replace_str = <<<EOT
<widget class="CButton" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
        <widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/item.tpl">
EOT;
        patch_template($location, "GoogleCheckout", $find_str, $replace_str);
    } else {
        already_patched($location);
    }
} else {
    // commercial skin patch
    if (!is_template_patched($location, "GoogleCheckout")) {
        $find_str = <<<EOT
        <widget class="CButton" template="common/button_shopping_cart.tpl" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
EOT;
        $replace_str = <<<EOT
        <widget class="CButton" template="common/button_shopping_cart.tpl" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
        <widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/item.tpl">
EOT;
        patch_template($location, "GoogleCheckout", $find_str, $replace_str);
    } else {
        already_patched($location);
    }
}


$location = "skins/default/en/modules/Promotion/item.tpl";
if (file_exists($location)) {
    if (file_exists($location) && !is_template_patched($location, "GoogleCheckout")) {
        $find_str = <<<EOT
<widget class="CButton" href="cart.php?target=cart&action=delete&cart_id={cart_id}" label="Delete item" font="FormButton">
EOT;
        $replace_str = <<<EOT
<widget class="CButton" href="cart.php?target=cart&action=delete&cart_id={cart_id}" label="Delete item" font="FormButton">
        <widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/item.tpl">
EOT;
        patch_template($location, "GoogleCheckout", $find_str, $replace_str);
    } else {
    	already_patched($location);
    }
}


$location = "skins/default/en/main.tpl";
$check_str = 'class="CGoogleAltCheckout"';
if (!is_template_patched($location, $check_str))
{
    $find_str = <<<EOT
<!-- [center] -->
EOT;
    $replace_str = <<<EOT
<!-- [center] -->
<widget module="GoogleCheckout" class="CGoogleAltCheckout">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "cart.html";
$check_str = 'class="CGoogleAltCheckout"';
if (!is_template_patched($location, $check_str))
{
    $find_str = <<<EOT
<!-- DO NOT DELETE LINES BELOW! -->
EOT;
    $replace_str = <<<EOT
<!-- DO NOT DELETE LINES BELOW! -->
<widget module="GoogleCheckout" class="CGoogleAltCheckout">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/default/en/main.tpl";
$check_str = 'modules/GoogleCheckout/google_checkout_dialog.tpl';
if (!is_template_patched($location, $check_str))
{
    $find_str = <<<EOT
<!-- [/checkout] }}} -->
EOT;
    if (is_template_patched($location, $find_str)) {
    	$replace_str = <<<EOT
<widget module="GoogleCheckout" template="common/dialog.tpl" body="modules/GoogleCheckout/google_checkout_dialog.tpl" head="Google Checkout payment module" visible="{target=#googlecheckout#&!valid}" >
<!-- [/checkout] }}} -->
EOT;
    	patch_template($location, $check_str, $find_str, $replace_str);
    } else {
    	$find_str = <<<EOT
<!-- [/center] -->
EOT;
    	$replace_str = <<<EOT
<widget module="GoogleCheckout" template="common/dialog.tpl" body="modules/GoogleCheckout/google_checkout_dialog.tpl" head="Google Checkout payment module" visible="{target=#googlecheckout#&!valid}" >
<!-- [/center] -->
EOT;
    	patch_template($location, $check_str, $find_str, $replace_str);
    }
} else {
    already_patched($location);
}

$location = "cart.html";
$check_str = 'modules/GoogleCheckout/google_checkout_dialog.tpl';
if (!is_template_patched($location, $check_str))
{
    $find_str = <<<EOT
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
EOT;
   	$replace_str = <<<EOT
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<widget module="GoogleCheckout" template="common/dialog.tpl" body="modules/GoogleCheckout/google_checkout_dialog.tpl" head="Google Checkout payment module" visible="{target=#googlecheckout#&!valid}" >
EOT;
   	patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/default/en/shopping_cart/buttons.tpl";
$check_str = '<td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>';
if (!is_template_patched($location, $check_str))
{
    $find_str = <<<EOT
<td align="left" nowrap>
EOT;
    $replace_str = <<<EOT
<td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/default/en/shopping_cart/buttons.tpl";
$check_str = '<td align="right" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>';
if (!is_template_patched($location, $check_str))
{
    $find_str = <<<EOT
<td align="right" nowrap>
EOT;
    $replace_str = <<<EOT
<td align="right" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    already_patched($location);
}

// generate 'CHECKOUT' button
$button = @file_get_contents('skins/default/en/common/button.tpl');
$mod = @file_get_contents('skins/default/en/modules/GoogleCheckout/button_alt_checkout.tpl');
if ($button && $mod && preg_match("/\{\* ORIGINAL BUTTON TEMPLATE \*\}.*\{\* \/ORIGINAL BUTTON TEMPLATE \*\}/s", $mod)) {
    $out = preg_replace("/(\{\* ORIGINAL BUTTON TEMPLATE \*\}).*(\{\* \/ORIGINAL BUTTON TEMPLATE \*\})/s", '\1'.$button.'\2', $mod);

    if ($handle = fopen("skins/default/en/modules/GoogleCheckout/button_alt_checkout.tpl", "w")) {
        fwrite($handle, $out);
        fclose($handle);
    }
}

$location = "skins/default/en/shopping_cart/body.tpl";
$check_str = 'GoogleCheckout';
if (is_template_patched($location, '<widget template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">')) {
    patch_template($location, null, '<widget template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">', '<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">');
} else {
    if (!is_template_patched($location, $check_str)) {
        $find_str = <<<EOT
<widget template="shopping_cart/buttons.tpl">
EOT;
        $replace_str = <<<EOT
<widget template="shopping_cart/buttons.tpl">
<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">
EOT;
        patch_template($location, $check_str, $find_str, $replace_str);
    } else {
        already_patched($location);
    }
}

end_patching();

?>
