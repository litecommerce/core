<?php

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

$MODULE_NAME = "WholesaleTrading";
start_patching($MODULE_NAME);

if (is_object($this)) {
    $schema = (!empty($this->layout))?$this->layout:($this->xlite->getComplex('config.Skin.skin'));
}

//////////////////////////////////////
//	COPY SKIN-DEPENDENT TEMPLATES
//////////////////////////////////////

copy_schema_template('membership/payed_membership_added.tpl', $schema, $MODULE_NAME);
copy_schema_template('membership/register.tpl', $schema, $MODULE_NAME);
copy_schema_template('add_error.tpl', $schema, $MODULE_NAME);

//////////////////////////////////////
//	CUSTOMER ZONE
//////////////////////////////////////

$location = "skins/default/en/product_details.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
<widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
EOT;
    $replace_str = <<<EOT
<widget module="ProductOptions" template="modules/ProductOptions/product_options.tpl" IF="product.hasOptions()&!product.showExpandedOptions"/>
<widget module="WholesaleTrading" template="modules/WholesaleTrading/expanded_options.tpl" IF="product.hasOptions()&product.showExpandedOptions"/>
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/default/en/main.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
<!-- [/center] -->
EOT;
    $replace_str = <<<EOT
<widget module="WholesaleTrading" mode="update_error" template="common/dialog.tpl" body="modules/WholesaleTrading/update_error.tpl" head="Product quantities not changed">    
<widget module="WholesaleTrading" target="price_list" template="modules/WholesaleTrading/pl_main.tpl"> 
<widget module="WholesaleTrading" target="wholesale" template="modules/WholesaleTrading/wholesale.tpl">
<widget module="WholesaleTrading" target="global_discount" template="common/dialog.tpl" body="modules/WholesaleTrading/global_discount.tpl" head="Global discounts">
<!-- [/center] -->
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/default/en/profile.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
EOT;
    $replace_str = <<<EOT
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
EOT;
    $replace_str = <<<EOT
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/default/en/register_form.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
{*extraFields*}
EOT;
    $replace_str = <<<EOT
{*extraFields*}
<widget module="WholesaleTrading" template="modules/WholesaleTrading/profile_form.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/default/en/common/invoice.tpl";

// remove existing global discount:
$find_str = <<<EOT
    <widget module="WholesaleTrading" template="modules/WholesaleTrading/invoice.tpl">
    <widget module="Promotion" template="modules/Promotion/invoice_discount.tpl">
EOT;
if (is_template_patched($location, $find_str)) {
    $replace_str = <<<EOT
    <widget module="Promotion" template="modules/Promotion/invoice_discount.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}

// add correct global discount:
if (!is_template_patched($location, "WholesaleTrading/invoice.tpl"))
{
    $find_str = <<<EOT
    <tr>
        <td nowrap>Subtotal</td>
EOT;
    $replace_str = <<<EOT
    <widget module="WholesaleTrading" template="modules/WholesaleTrading/invoice.tpl">
    <tr>
        <td nowrap>Subtotal</td>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

if (!is_template_patched($location, "WholesaleTrading/wholesaler_details.tpl"))
{
    $find_str = <<<EOT
    </tr>
    <tr>
        <td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Products Ordered</b></td>
    </tr>
EOT;
    $replace_str = <<<EOT
    </tr>
        <widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile={order.profile}>
    <tr>
        <td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Products Ordered</b></td>
    </tr>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}


$location = "skins/default/en/common/print_invoice.tpl";

if (!is_template_patched($location, "WholesaleTrading/print_invoice_discount_label.tpl"))
{
    $find_str = <<<EOT
            <b>Subtotal</b><br>
EOT;
    $replace_str = <<<EOT
            <widget module="WholesaleTrading" template="modules/WholesaleTrading/print_invoice_discount_label.tpl" ignoreErrors>
            <b>Subtotal</b><br>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

if (!is_template_patched($location, "WholesaleTrading/print_invoice_discount.tpl"))
{
    $find_str = <<<EOT
            {price_format(order,#subtotal#):h}<br>
EOT;
    $replace_str = <<<EOT
            <widget module="WholesaleTrading" template="modules/WholesaleTrading/print_invoice_discount.tpl" ignoreErrors>
            {price_format(order,#subtotal#):h}<br>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}


$location = "skins/default/en/shopping_cart/totals.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
<table align="right" border=0>
EOT;
    $replace_str = <<<EOT
<table align="right" border=0>
<widget module="WholesaleTrading" template="modules/WholesaleTrading/totals.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}


$location = "skins/admin/en/common/invoice.tpl";

// remove existing global discount:
$find_str = <<<EOT
    <widget module="WholesaleTrading" template="modules/WholesaleTrading/invoice.tpl">
    <widget module="Promotion" template="modules/Promotion/invoice.tpl">
EOT;
if (is_template_patched($location, $find_str)) {
    $replace_str = <<<EOT
    <widget module="Promotion" template="modules/Promotion/invoice.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}

// add correct global discount:
if (!is_template_patched($location, "WholesaleTrading/invoice.tpl"))
{
    $find_str = <<<EOT
    <tr>
        <td nowrap>Subtotal</td>
EOT;
    $replace_str = <<<EOT
    <widget module="WholesaleTrading" template="modules/WholesaleTrading/invoice.tpl">
    <tr>
        <td nowrap>Subtotal</td>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

if (!is_template_patched($location, "WholesaleTrading/wholesaler_details.tpl"))
{
    $find_str = <<<EOT
    </tr>
    <tr>
        <td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Products Ordered</b></td>
    </tr>
EOT;
    $replace_str = <<<EOT
    </tr>
        <widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile={order.profile}>
    <tr>
        <td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Products Ordered</b></td>
    </tr>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

if (is_template_patched($location, '<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl">'))
{
    $find_str = <<<EOT
<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl">
EOT;
    $replace_str = <<<EOT
<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile={order.profile}>
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}


$location = "skins/admin/en/main.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
<!-- [/center] -->
EOT;
    $replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/WholesaleTrading/main.tpl"> 
<!-- [/center] -->
EOT;
    patch_template($location, null, $find_str, $replace_str);
} elseif (is_template_patched($location, "WholesaleTrading")&&!is_template_patched($location, "WholesaleTrading/main.tpl")) {
    if (is_template_patched($location, "WholesaleTrading/global_discount.tpl")) {
        $find_str = <<<EOT
<widget module="WholesaleTrading" target="price_list" template="modules/WholesaleTrading/pl_main.tpl"> 
<widget module="WholesaleTrading" target="wholesale" template="modules/WholesaleTrading/wholesale.tpl">
<widget module="WholesaleTrading" target="global_discount" template="common/dialog.tpl" body="modules/WholesaleTrading/global_discount.tpl" head="Global discounts">
EOT;
        $replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/WholesaleTrading/main.tpl">
EOT;
        patch_template($location, null, $find_str, $replace_str);
    }

    if (is_template_patched($location, "WholesaleTrading")&&!is_template_patched($location, "WholesaleTrading/main.tpl")) {
    	$find_str = <<<EOT
<widget module="WholesaleTrading" target="price_list" template="modules/WholesaleTrading/pl_main.tpl"> 
<widget module="WholesaleTrading" target="wholesale" template="modules/WholesaleTrading/wholesale.tpl">
EOT;
    	$replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/WholesaleTrading/main.tpl">
EOT;
        patch_template($location, null, $find_str, $replace_str);
    }
} else {
    already_patched($location);
}

$location = "skins/admin/en/product/add.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
{*extraFields*}
EOT;
    $replace_str = <<<EOT
{*extraFields*}
<widget module="WholesaleTrading" template="modules/WholesaleTrading/memberships/membership_product.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/product/info.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
{*extraFields*}
EOT;
    $replace_str = <<<EOT
{*extraFields*}
<widget module="WholesaleTrading" template="modules/WholesaleTrading/memberships/membership_product.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/catalog/body.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
<hr>
EOT;
    $replace_str = <<<EOT
<hr>
<widget module="WholesaleTrading" template="modules/WholesaleTrading/catalog.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} elseif (is_template_patched($location, "WholesaleTrading")&&!is_template_patched($location, "global_discount") && !is_template_patched($location, "WholesaleTrading/catalog.tpl")) {
    $find_str = <<<EOT
<a IF="xlite.mm.activeModules.WholesaleTrading" href="admin.php?target=price_list">Print price list<br></a>
EOT;
    $replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/WholesaleTrading/catalog.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} elseif (is_template_patched($location, "global_discount")) {
    $find_str = <<<EOT
<a IF="xlite.mm.activeModules.WholesaleTrading" href="admin.php?target=price_list">Print price list<br></a>
<a IF="xlite.mm.activeModules.WholesaleTrading" href="admin.php?target=global_discount">Global discounts</a><br>
EOT;
    $replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/WholesaleTrading/catalog.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}  else {
    already_patched($location);
}

$location = "skins/admin/en/profile/body.tpl";
    
if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
{*extraFields*}
EOT;
    $replace_str = <<<EOT
{*extraFields*}
<widget module="WholesaleTrading" template="modules/WholesaleTrading/profile_form.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/location.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
<widget module="GiftCertificates" template="modules/GiftCertificates/location.tpl">
EOT;
    $replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/WholesaleTrading/location.tpl">
<widget module="GiftCertificates" template="modules/GiftCertificates/location.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/product/product_list.tpl";

if (!is_template_patched($location, "WholesaleTrading"))
{
    $find_str = <<<EOT
    <td>Product</td>
EOT;
    $replace_str = <<<EOT
    <td>Product</td>
    <td align=center IF="xlite.mm.activeModules.WholesaleTrading">Prod.#</td>
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
    <TD nowrap align="right"><INPUT type="text" size=4 maxlength=4 value="{product.order_by}" name="product_orderby[{product.product_id}]"></TD>
EOT;
    $replace_str = <<<EOT
    <td width=1% align=right IF="xlite.mm.activeModules.WholesaleTrading"><a href="admin.php?target=product&product_id={product.product_id}&backUrl={url:u}"><u>#{product.product_id:h}</u></a></td>
    <TD nowrap align="right"><INPUT type="text" size=4 maxlength=4 value="{product.order_by}" name="product_orderby[{product.product_id}]"></TD>
EOT;
    patch_template($location, null, $find_str, $replace_str);

} else {
    already_patched($location);
}

$location = "skins/admin/en/profile/body.tpl";
if (!is_template_patched($location, "WholesaleTrading/profile_grant_membership.tpl"))
{
    $find_str = <<<EOT
    <td><widget class="CMembershipSelect" field="pending_membership">&nbsp;&nbsp;<a IF="mode=#modify#" href="javascript: grantMembership()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <font class="FormButton">Grant membership</font></a></td>
EOT;
    $replace_str = <<<EOT
    <td><widget class="CMembershipSelect" field="pending_membership">&nbsp;&nbsp;{if:xlite.WholesaleTradingEnabled}<widget module="WholesaleTrading" template="modules/WholesaleTrading/profile_grant_membership.tpl">{else:}<a IF="mode=#modify#" href="javascript: grantMembership()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <font class="FormButton">Grant membership</font></a>{end:}</td>
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
    <td align="right">Granted membership</td>
    <td><font class="Star">*</font></td>
EOT;
    $replace_str = <<<EOT
    <td align="right" valign=top>Granted membership</td>
    <td valign=top><font class="Star">*</font></td>
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
    <td><widget class="CMembershipSelect" field="membership"></td>
EOT;
    $replace_str = <<<EOT
    <td><widget class="CMembershipSelect" field="membership" history="{membership_history}"><widget module="WholesaleTrading" template="modules/WholesaleTrading/membership_history/caption.tpl" membership_history="{membership_history}"></td>
EOT;
    patch_template($location, null, $find_str, $replace_str);
}

$location = "skins/admin/en/profile/body.tpl";
if (!is_template_patched($location, "WholesaleTrading/memberships/profile_expiration.tpl"))
{
    $find_str = <<<EOT
<tr valign="middle">
    <td align="right">Referred by</td>
EOT;
    $replace_str = <<<EOT
<widget module="WholesaleTrading" template="modules/WholesaleTrading/memberships/profile_expiration.tpl">

<tr valign="middle">
    <td align="right">Referred by</td>
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/tax/options.tpl";
if (!is_template_patched($location, '<tr IF="discountUsedForTaxes">'))
{
    $find_str = <<<EOT
<tr id="tax_message">
    <td>Message next to the product price when tax is included:</td>
    <td><input type="text" size="40" name="include_tax_message" value="{config.Taxes.include_tax_message:r}"></td>
</tr>
EOT;
    $replace_str = <<<EOT
<tbody id="tax_message">
<tr IF="discountUsedForTaxes">
    <td>Discounts charged after taxes application:</td>
    <td>
        <select name="discounts_after_taxes">
            <option value="N" selected="{config.Taxes.discounts_after_taxes=0}">No</option>    
            <option value="Y" selected="{config.Taxes.discounts_after_taxes=1}">Yes</option>   
        </select>
    </td>
</tr>
<tr>
    <td>Message next to the product price when tax is included:</td>
    <td><input type="text" size="40" name="include_tax_message" value="{config.Taxes.include_tax_message:r}"></td>
</tr>
</tbody>
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

// Mail templates
    $find_str = <<<EOT
</table>

<p>{signature:h}
</body>
</html>
EOT;
    $replace_str = <<<EOT
<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile={profile}>
</table>

<p>{signature:h}
</body>
</html>
EOT;

$skins = array('profile_admin_deleted', "profile_admin_modified", "profile_modified", "signin_admin_notification", "signin_notification");
foreach ($skins as $skin_name) {
    $location = "skins/mail/en/$skin_name/body.tpl";
    if (!is_template_patched($location, "WholesaleTrading"))
    {
        patch_template($location, null, $find_str, $replace_str);
    } else {
        already_patched($location);
    }
}

end_patching();

?>
