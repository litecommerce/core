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

$MODULE_NAME = "AdvancedSearch";
start_patching($MODULE_NAME);

if (is_object($this)) {
    $schema = (!empty($this->layout))?$this->layout:($this->xlite->config->Skin->skin);
}

//////////////////////////////////////
//	COPY SKIN-DEPENDENT TEMPLATES
//////////////////////////////////////

copy_schema_template('select_category.tpl', $schema, $MODULE_NAME);
copy_schema_template('advanced_search.tpl', $schema, $MODULE_NAME);


//////////////////////////////////////
//	CUSTOMER ZONE
//////////////////////////////////////

$location = "skins/default/en/main.tpl";

if (!is_template_patched($location, "AdvancedSearch"))
{
    if (is_template_patched($location,"<!-- [tabs] }}} -->")) {
        $find_str = <<<EOT
<!-- [/modules] }}} -->
EOT;
        $replace_str = <<<EOT
<widget module="AdvancedSearch" mode="" target="advanced_search" head="Search for products" template="common/dialog.tpl" body="modules/AdvancedSearch/advanced_search.tpl">
<widget module="AdvancedSearch" target="advanced_search" mode="found" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
<!-- [/modules] }}} -->
EOT;
        patch_template($location, null, $find_str, $replace_str);
    } else {
        $find_str = <<<EOT
<widget template="location.tpl" name="locationWidget">
EOT;
    	$replace_str = <<<EOT
<widget template="location.tpl" name="locationWidget">
<widget module="AdvancedSearch" mode="" target="advanced_search" head="Search for products" template="common/dialog.tpl" body="modules/AdvancedSearch/advanced_search.tpl">
<widget module="AdvancedSearch" target="advanced_search" mode="found" template="common/dialog.tpl" body="search_result.tpl" head="Search Result">
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
}
else
{
    already_patched($location);
}

$location = "skins/default/en/search_products.tpl";

if (!is_template_patched($location, "AdvancedSearchEnabled"))
{
    if (is_template_patched('skins/default/en/main.tpl', "<!-- [tabs] }}} -->")) {
    	$find_str = <<<EOT
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
EOT;
    	$replace_str = <<<EOT
<TABLE IF="!xlite.AdvancedSearchEnabled" BORDER=0 CELLPADDING=0 CELLSPACING=0>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    } else {
        $find_str = <<<EOT
<table border=0 cellpadding=0 cellspacing=0>
EOT;
        $replace_str = <<<EOT
<table if="!xlite.AdvancedSearchEnabled" border=0 cellpadding=0 cellspacing=0>
EOT;
        patch_template($location, null, $find_str, $replace_str);
    }

    if (is_template_patched('skins/default/en/main.tpl', "<!-- [tabs] }}} -->")) {
    	$find_str = <<<EOT
</form>
</TABLE>
EOT;
    	$replace_str = <<<EOT
</form>
</TABLE>
<TABLE IF="xlite.AdvancedSearchEnabled" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<form action="{getShopUrl(#cart.php#)}" method="GET" name="search_form">
<input type="hidden" name="target" value="search">

<tr valign=middle>
    <td WIDTH=28>&nbsp;&nbsp;</td>
    <td>&nbsp;<img src="images/search.gif" width=19 height=19 align=absmiddle>&nbsp;</td>
    <td><span IF="!substring:r"><input type="text" name="substring" style="width:75pt;color:#888888" value="Find product" onFocus="this.value=''; this.style.color='#000000';"></span>
        <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td>
    <!-- [button] -->
    <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
    <TR>
    <TD><IMG SRC="images/rect_button_1.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
    <TD background="images/rect_button_bg.gif"><A href="javascript:void(0);" onclick="javascript: document.search_form.submit();" title="Search"><FONT class="FormButton">Go</FONT></A></TD>
    <TD><IMG SRC="images/rect_button_2.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
    </TR>
    </TABLE>
    <!-- [/button] -->
    </td>
    <td>
        &nbsp;&nbsp;<img src="images/modules/AdvancedSearch/plus_advanced.gif">&nbsp;<a href="cart.php?target=advanced_search" title="Advanced Search" style="TEXT-DECORATION: underline; font-size : 9px">Advanced</a></td>
    </td>
    <td>&nbsp;&nbsp;</td>
</tr>
</form>
</TABLE>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    } else {
    	$find_str = <<<EOT
</form>
</table>
EOT;
    	$replace_str = <<<EOT
</form>
</table>
<TABLE IF="xlite.AdvancedSearchEnabled" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<form action="{getShopUrl(#cart.php#)}" method="GET" name="search_form">
<input type="hidden" name="target" value="search">

<tr valign=middle>
    <td>&nbsp;<img src="images/search.gif" width=19 height=19 align=absmiddle>&nbsp;</td>
    <td><span IF="!substring:r"><input type="text" name="substring" style="width:75pt;color:#888888" value="Find product" onFocus="this.value=''; this.style.color='#000000';"></span>
        <span IF="substring:r"><input type="text" name="substring" style="width:75pt" value="{substring:r}"></span>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td>
    <!-- [button] -->
    <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
    <TR>
    <TD><IMG SRC="images/rect_button_1.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
    <TD background="images/rect_button_bg.gif"><a href="javascript: document.search_form.submit()" title="Search"><FONT class="FormButton">Go</FONT></a></TD>
    <TD><IMG SRC="images/rect_button_2.gif" WIDTH=11 HEIGHT=18 BORDER="0"></TD>
    </TR>
    </TABLE>
    <!-- [/button] -->
    </td>

    </td>
    </td>
    <td>
        &nbsp;&nbsp;<img src="images/modules/AdvancedSearch/plus_advanced.gif">&nbsp;<a href="cart.php?target=advanced_search" title="Advanced Search" style="TEXT-DECORATION: underline; font-size : 9px">Advanced</a></td>
    </td>
    <td>&nbsp;&nbsp;</td>
</tr>
</form>
</TABLE>
EOT;
    	patch_template($location, null, $find_str, $replace_str);
    }
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/location.tpl";

if (!is_template_patched($location, "AdvancedSearch"))
{
    $find_str = <<<EOT
<widget module="Promotion" template="modules/Promotion/location.tpl">
EOT;
    $replace_str = <<<EOT
<widget module="Promotion" template="modules/Promotion/location.tpl">
<widget module="AdvancedSearch" template="modules/AdvancedSearch/location.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/default/en/location.tpl";

if (!is_template_patched($location, "AdvancedSearch"))
{
    $find_str = <<<EOT
<span IF="target=#search#">&nbsp;::&nbsp;Search Result</span>
EOT;
    $replace_str = <<<EOT
<span IF="target=#search#">&nbsp;::&nbsp;Search Result</span>
<widget module="AdvancedSearch" template="modules/AdvancedSearch/location.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/main.tpl";

if (!is_template_patched($location, "AdvancedSearch"))
{
    $find_str = <<<EOT
<widget target="order_list" template="order/search.tpl">
EOT;
    $replace_str = <<<EOT
<widget target="advanced_search" module="AdvancedSearch" template="common/dialog.tpl" body="modules/AdvancedSearch/config.tpl">
<widget target="order_list" template="order/search.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/default/en/search_result.tpl";

if (!is_template_patched($location, "AdvancedSearchEnabled"))
{
    $find_str = <<<EOT
<span IF="!products">
No products found on your query. Please try to re-formulate the query.
</span>
EOT;
    $replace_str = <<<EOT
<span IF="!products">
No products found on your query. Please try to {if:xlite.AdvancedSearchEnabled}<a href ="cart.php?target=advanced_search" class="FormButton"><u>re-formulate</u></a>{else:}re-formulate{end:} the query.
</span>
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
<span IF="products">
EOT;
    $replace_str = <<<EOT
<span IF="products">
{if:xlite.AdvancedSearchEnabled&count}{dialog.count} {if:count=#1#}product{else:} products {end:} found. <a class="FormButton" href="cart.php?target=advanced_search"><u>Refine your search</u></a>{end:}
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

end_patching();

?>

