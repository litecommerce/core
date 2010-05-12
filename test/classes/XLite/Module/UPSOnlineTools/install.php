<?php

/**
*
* @package Module_UPSOnlineTools
* @version $Id$
*/

if (!function_exists("file_put_contents")) 
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

if (!function_exists("file_get_contents")) 
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
if (!function_exists("start_patching"))
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
        $patching_table_row = 1;
    }
}

if (!function_exists("end_patching"))
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

if (!function_exists("is_template_patched"))
{
    function is_template_patched($location, $check_str)
    {
        global $patching_table_row;
        $patching_table_row = ($patching_table_row) ? 0 : 1;

        $src = @file_get_contents($location);
        return (strpos($src, $check_str) === false) ? false : true;
    }
}

if (!function_exists("already_patched"))
{
    function already_patched($location)
    {
        global $patching_table_row;
        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;$location&nbsp;</TD><TD nowrap>&nbsp;";
        echo "<FONT COLOR=red><B>already patched</B></FONT>";
        echo "&nbsp;</TD></TR>\n";
//	    $patching_table_row = ($patching_table_row) ? 0 : 1;
    }
}

if (!function_exists("file_not_exists"))
{
    function file_not_exists($location)
    {
        global $patching_table_row;
        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;$location&nbsp;</TD></TD><TD nowrap>&nbsp;";

        if (!file_exists($location)) {
            echo "<FONT COLOR=red><B>not exists</B></FONT>";
            echo "&nbsp;</TD></TR>\n";
            return;
        }
    }
}

if (!function_exists("pattern_not_found"))
{
    function pattern_not_found()
    {
        global $patching_table_row;
        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;$location&nbsp;</TD></TD><TD nowrap>&nbsp;";

        $replace_message = "<FONT COLOR=red><B>&nbsp;(replace failed)&nbsp;</B></FONT>";
        echo "<FONT COLOR=green><B>success</B></FONT>$replace_message";
        echo "&nbsp;</TD></TR>\n";
    }
}

if (!function_exists("patch_template"))
{
    function patch_template($location, $check_str=null, $find_str=null, $replace_str=null, $add_str=null)
    {
        global $patching_table_row;
        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;$location&nbsp;</TD></TD><TD nowrap>&nbsp;";

        if (!file_exists($location)) {
            echo "<FONT COLOR=red><B>not exists</B></FONT>";
            echo "&nbsp;</TD></TR>\n";
            return;
        }

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
       		echo "<FONT COLOR=red><B>already patched</B></FONT>";
    	}
       	echo "&nbsp;</TD></TR>\n";
//		$patching_table_row = ($patching_table_row) ? 0 : 1;
    }
}

start_patching("UPSOnlineTools");


// Admin area
//------------------------------------------------
$location = "skins/admin/en/location.tpl";
if (!is_template_patched($location, "UPSOnlineTools"))
{
    $find_str = <<<EOT
<widget module="EcommerceReports" template="modules/EcommerceReports/location.tpl">
EOT;
    $replace_str = <<<EOT
<widget module="EcommerceReports" template="modules/EcommerceReports/location.tpl">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/location.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/admin/en/main.tpl";
if (!is_template_patched($location, "UPSOnlineTools"))
{
    $find_str = <<<EOT
<widget module="Affiliate" template="modules/Affiliate/main.tpl">
EOT;
    $replace_str = <<<EOT
<widget module="Affiliate" template="modules/Affiliate/main.tpl">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/admin/en/shipping/charges.tpl";
$find_str = "{shipping.name}</option>";
$replace_str = "{shipping.name:h}</option>";
if (!is_template_patched($location, $replace_str))
{
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/admin/en/shipping/charges_form.tpl";
$find_str = "{shipping.name}</option>";
$replace_str = "{shipping.name:h}</option>";
if (!is_template_patched($location, $replace_str))
{
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/admin/en/shipping/methods.tpl";
if (!is_template_patched($location, "<tr><td class=\"AdminTitle\" colspan=5>{module.getModuleName()}</td></tr>")) {
    if (!is_template_patched($location, "UPSOnlineTools/settings_link.tpl"))
    {
    	$find_str = <<<EOT
        <td class="AdminHead" colspan=5>{module.getModuleName()}</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
EOT;
    	$replace_str = <<<EOT
        <td class="AdminHead" colspan=5>{module.getModuleName()}</td>
    </tr>
    <tr><td align="right">&nbsp;<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/settings_link.tpl" IF="module.class=#ups#"/></td></tr>
EOT;
        patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
        already_patched($location);
    }
} else {
    if (!is_template_patched($location, "UPSOnlineTools/settings_link.tpl"))
    {
    	$find_str = "<tr><td class=\"AdminTitle\" colspan=5>{module.getModuleName()}</td></tr>";
    	$replace_str = "<tr><td class=\"AdminTitle\" colspan=5>{module.getModuleName()}</td></tr>\n<tr><td align=\"right\">&nbsp;<widget module=\"UPSOnlineTools\" template=\"modules/UPSOnlineTools/settings_link.tpl\" IF=\"module.class=#ups#\"/></td></tr>";
        patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
        already_patched($location);
    }
}

//------------------------------------------------
$location = "skins/admin/en/shipping/methods.tpl";
if (!is_template_patched($location, "{shipping.name:h}"))
{
    $find_str = <<<EOT
<td>{shipping.name}</td>
EOT;
    $replace_str = <<<EOT
<td>{shipping.name:h}</td>
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/admin/en/shipping/methods.tpl";
if (!file_exists($location)) {
    file_not_exists($location);
} else {
    $content = file_get_contents($location);
    if (!preg_match('/document\.shipping_method_\{module.class\}.submit\(\)\;\"\>(?(?!\<\/tr\>).)*?\<\/tr\>/si', $content, $out)) {
        pattern_not_found();
    } else {
        if (!is_template_patched($location, "UPSOnlineTools/settings_disclaimer.tpl"))
        {
            $find_str = array_shift($out);
            $replace_str = <<<EOT
$find_str
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/settings_disclaimer.tpl" IF="module.class=#ups#"/>
EOT;
            patch_template($location, null, $find_str, $replace_str);
        } else {
            already_patched($location);
        }
    }
}

//------------------------------------------------
$location = "skins/admin/en/order/order.tpl";
if (!is_template_patched($location, "UPSOnlineTools"))
{
    $find_str = <<<EOT
<b><a href="admin.php?target=order&mode=invoice&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Print invoice</a></b>
EOT;
    $replace_str = <<<EOT
<b><a href="admin.php?target=order&mode=invoice&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Print invoice</a></b>
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/show_container_details.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/admin/en/modules/AOM/order.tpl";
if (file_exists($location)) {
    if (!is_template_patched($location, "UPSOnlineTools"))
    {
    	$find_str = <<<EOT
<input class="ProductDetailsTitle" type="button" value=" Print invoice " onClick="window.open('admin.php?target=order&mode=invoice&order_id={order.order_id}')">
EOT;
        $replace_str = <<<EOT
<input class="ProductDetailsTitle" type="button" value=" Print invoice " onClick="window.open('admin.php?target=order&mode=invoice&order_id={order.order_id}')">
            <widget module="UPSOnlineTools" template="modules/UPSOnlineTools/show_container_details.tpl" style="button">
EOT;
        patch_template($location, null, $find_str, $replace_str);
    } else {
        already_patched($location);
    }
}


// Customer area
//------------------------------------------------
$location = "skins/default/en/main.tpl";

if (is_template_patched($location, 'head="New customer" body="register_form.tpl"')) {
    if (!is_template_patched($location, 'name="registerForm" IF="!showAV"'))
    {
    	$find_str = <<<EOT
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New customer" body="register_form.tpl" name="registerForm">
EOT;
    	$replace_str = <<<EOT
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New customer" body="register_form.tpl" name="registerForm" IF="!showAV"/>
EOT;
        patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
        already_patched($location);
    }
} else {
    if (!is_template_patched($location, 'head="New member" IF="!showAV"'))
    {
    	$find_str = 'head="New member"';
    	$replace_str = 'head="New member" IF="!showAV"';
        patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
        already_patched($location);
    }
}

//------------------------------------------------
$location = "skins/default/en/main.tpl";
if (!is_template_patched($location, 'name="profileForm" IF="!showAV"'))
{
    $find_str = <<<EOT
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm">
EOT;
    $replace_str = <<<EOT
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm" IF="!showAV"/>
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/default/en/main.tpl";
if (!is_template_patched($location, '<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">'))
{
    if (is_template_patched($location, '<!-- [/profile] }}} -->'))
    {
    	$find_str = <<<EOT
<!-- [/profile] }}} -->
EOT;
    	$replace_str = <<<EOT
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<!-- [/profile] }}} -->
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	$find_str = <<<EOT
<!-- [/center] -->
EOT;
    	$replace_str = <<<EOT
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<!-- [/center] -->
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/default/en/main.tpl";
if (!is_template_patched($location, 'head="Shopping cart" IF="!showAV"'))
{
    $find_str = <<<EOT
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart">
EOT;
    $replace_str = <<<EOT
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart" IF="!showAV"/>
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/default/en/main.tpl";
if (is_template_patched($location, 'head="New customer" body="register_form.tpl"')) {
    if (!is_template_patched($location, 'allowAnonymous="{config.General.enable_anon_checkout}" IF="!showAV"'))
    {
    	$find_str = <<<EOT
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="Customer Information" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}">
EOT;
    	$replace_str = <<<EOT
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="Customer Information" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}" IF="!showAV"/>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }
}

//------------------------------------------------
$location = "skins/default/en/profile.tpl";
if (!is_template_patched($location, "UPSOnlineTools"))
{
    $find_str = <<<EOT
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>&nbsp;</td>
</tr>
EOT;
    $replace_str = <<<EOT
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>&nbsp;</td>
</tr>

<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/notice_register.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/default/en/register_form.tpl";
if (!is_template_patched($location, "UPSOnlineTools"))
{
    $find_str = <<<EOT
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>
    </td>
</tr>
EOT;
    $replace_str = <<<EOT
        <input type="text" name="shipping_zipcode" value="{shipping_zipcode:r}" size="32" maxlength="32">
    </td>
    <td>
    </td>
</tr>

<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/notice_register.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

//------------------------------------------------
$location = "skins/default/en/shopping_cart/delivery.tpl";
if (is_template_patched($location, "<p IF=\"cart.shippingAvailable&cart.shipped&cart.getShippingRates()\" align=\"right\">"))
{
    if (!is_template_patched($location, "UPSOnlineTools"))
    {
    	$find_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped&cart.getShippingRates()" align="right">
EOT;
    	$replace_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped&cart.getShippingRates()" align="right">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">
<span IF="!xlite.UPSOnlineToolsEnabled">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }

    if (!is_template_patched($location, '</span> {* /UPSOnlineTools *}'))
    {
    	$find_str = <<<EOT
</select>
</p>
EOT;
    	$replace_str = <<<EOT
</select>
</span> {* /UPSOnlineTools *}
</p>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }
} elseif (is_template_patched($location, "<p IF=\"cart.shippingAvailable&cart.shipped\" align=\"right\">")) {
    if (!is_template_patched($location, "UPSOnlineTools"))
    {
    	$find_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped" align="right">
EOT;
    	$replace_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped" align="right">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">
<span IF="!xlite.UPSOnlineToolsEnabled">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }

    if (!is_template_patched($location, '</span> {* /UPSOnlineTools *}'))
    {
    	$find_str = <<<EOT
</select>
</p>
EOT;
    	$replace_str = <<<EOT
</select>
</span> {* /UPSOnlineTools *}
</p>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }
} else {
    if (!is_template_patched($location, "UPSOnlineTools"))
    {
    	$find_str = <<<EOT
<p IF="cart.shippingAvailable" align="right">
EOT;
    	$replace_str = <<<EOT
<p IF="cart.shippingAvailable" align="right">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">
<span IF="!xlite.UPSOnlineToolsEnabled">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }

    if (!is_template_patched($location, '</span> {* /UPSOnlineTools *}'))
    {
    	$find_str = <<<EOT
</select>
</p>
EOT;
    	$replace_str = <<<EOT
</select>
</span> {* /UPSOnlineTools *}
</p>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
    else
    {
    	already_patched($location);
    }
}

//------------------------------------------------
$location = "skins/default/en/modules/Promotion/delivery.tpl";
if (file_exists($location)) {
    if (!is_template_patched($location, "UPSOnlineTools"))
    {
    	$find_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped" align="right">
EOT;
        $replace_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped" align="right">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">
<div IF="!xlite.UPSOnlineToolsEnabled">
EOT;
        patch_template($location, null, $find_str, $replace_str);
    } else {
    	already_patched($location);
    }
}

//------------------------------------------------
$location = "skins/default/en/modules/Promotion/delivery.tpl";
if (file_exists($location)) {
    if (!is_template_patched($location, '</div> {* /UPSOnlineTools *}'))
    {
    	$find_str = <<<EOT
</select>
</p>
EOT;
        $replace_str = <<<EOT
</select>
</div> {* /UPSOnlineTools *}
</p>
EOT;
        patch_template($location, null, $find_str, $replace_str);
    } else {
    	already_patched($location);
    }
}




end_patching();

?>
