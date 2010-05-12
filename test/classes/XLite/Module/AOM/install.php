<?php

/**
* 
*
* @package AOM
* @access public
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
        echo "<FONT COLOR=\"blue\"><B>already patched</B></FONT>";
        echo "&nbsp;</TD></TR>\n";
        $patching_table_row = ($patching_table_row) ? 0 : 1;
    }
}

if (!function_exists("patch_template"))
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

if (!function_exists("copy_schema_template"))
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

$MODULE_NAME = "AOM";
start_patching($MODULE_NAME);

if (is_object($this)) {
    $schema = (!empty($this->layout))?$this->layout:($this->xlite->getComplex('config.Skin.skin'));
}

//////////////////////////////////////
//	COPY SKIN-DEPENDENT TEMPLATES
//////////////////////////////////////

copy_schema_template("common/statuses.tpl", $schema, $MODULE_NAME);
copy_schema_template("order_info.tpl", $schema, $MODULE_NAME);
copy_schema_template("invoice_wsale.tpl", $schema, $MODULE_NAME);
copy_schema_template("invoice_so.tpl", $schema, $MODULE_NAME);
copy_schema_template("invoice_promotion.tpl", $schema, $MODULE_NAME);
copy_schema_template("invoice_gc.tpl", $schema, $MODULE_NAME);
copy_schema_template("invoice_bonus_points.tpl", $schema, $MODULE_NAME);

//////////////////////////////////////
//	ADMIN ZONE
//////////////////////////////////////

$location = "skins/admin/en/main.tpl";

if (!is_template_patched($location, "AOM"))
{
    $find_str = <<<EOT
<widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
EOT;
    $replace_str = <<<EOT
<span IF="!xlite.AOMEnabled">
    <widget target="order" template="common/dialog.tpl" body="order/order.tpl" head="Order # {order.order_id}">
</span>
<span IF="xlite.AOMEnabled">
    <widget module="AOM" template="modules/AOM/main.tpl">
</span>
EOT;
    patch_template($location, "AOM", $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/settings/body.tpl";

if (!is_template_patched($location, "AOM"))
{
    $find_str = <<<EOT
<a href="admin.php?target=states">States</a><br>
EOT;
    $replace_str = <<<EOT
<a href="admin.php?target=states">States</a><br>
<widget module="AOM" template="modules/AOM/menu.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/location.tpl";

if (!is_template_patched($location, "AOM"))
{
    $find_str = <<<EOT

EOT;
    $replace_str = <<<EOT

<widget module="AOM" template="modules/AOM/location.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/management/body.tpl";

if (!is_template_patched($location, "AOM"))
{
    $find_str = <<<EOT
<a href="admin.php?target=order_list">Orders</a><br>
EOT;
    $replace_str = <<<EOT
<a href="admin.php?target=order_list">Orders</a><br>
<widget module="AOM" template="modules/AOM/management.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/style.css";

if (!is_template_patched($location, "Aom"))
{
    $find_str = <<<EOT
.TableHead {
    BACKGROUND-COLOR: #E5EBEF
}
EOT;
    $replace_str = <<<EOT
.TableHead {
    BACKGROUND-COLOR: #E5EBEF
}
.AomTableHead {
    BACKGROUND-COLOR: #E5EBEF;
    FONT-SIZE: 12px;
    FONT-WEIGHT: bold;
}
.Input {
    BORDER : solid;
    BORDER-WIDTH : 1px;
    BORDER-COLOR : #B2B2B3;
    WIDTH : 100%;
}
.OrderTitle {
    COLOR : #516176;
    FONT-WEIGHT: bold;
}
.VerBorder {
    border-top: none;
    border-left: none;
    border-right : solid;
    border-bottom: none;
    height: 4px;
    border-width : 2px;
    border-right-color: #9b9b9b;
}
.VerBorderHighlighted {
    border-top: none;
    border-left: none;
    border-right : solid;
    border-bottom: none;
    height: 4px;
    border-width : 2px;
    border-right-color: #000000;
}
.LeftVerBorder {
    border-top: none;
    border-left: solid;
    border-right : none;
    border-bottom: none;
    height: 4px;
    border-width : 2px;
    border-left-color: #9b9b9b;
}
.LeftVerBorderHighlighted {
    border-top: none;
    border-left: solid;
    border-right : none;
    border-bottom: none;
    height: 4px;
    border-width : 2px;
    border-left-color: #000000;
}
.HorBorder {
    border-top: solid;
    border-left: none;
    border-right : none;
    border-bottom: none;
    height: 2px;
    border-width : 2px;
    border-top-color: #9b9b9b;
}
.HorBorderHighlighted {
    border-top: solid;
    border-left: none;
    border-right : none;
    border-bottom: none;
    height: 2px;
    border-width : 2px;
    border-top-color: #000000;
}

A.AomMenu:link {
        color: #466479; TEXT-DECORATION: none;
        font-size : 11px
}
A.AomMenu:visited {
        color: #466479; TEXT-DECORATION: none;
        font-size : 11px
}
A.AomMenu:hover {
        color: #466479; TEXT-DECORATION: underline;
        font-size : 11px
}
A.AomMenu:active  {
        color: #466479; TEXT-DECORATION: none;
        font-size : 11px;
}
.UpdateButton {
        background-color: #CDD9E1;
}
EOT;
    patch_template($location, "Aom", $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/default/en/style.css";

if (!is_template_patched($location, "Aom"))
{
    $find_str = <<<EOT
.TableHead {
    BACKGROUND-COLOR: #E5EBEF
}
EOT;
    $replace_str = <<<EOT
.TableHead {
    BACKGROUND-COLOR: #E5EBEF
}
.AomTableHead {
    BACKGROUND-COLOR: #E5EBEF;
    FONT-SIZE: 12px;
    FONT-WEIGHT: bold;
}
.Input {
    BORDER : solid;
    BORDER-WIDTH : 1px;
    BORDER-COLOR : #B2B2B3;
    WIDTH : 100%;
}
.OrderTitle {
    COLOR : #516176;
    FONT-WEIGHT: bold;
}

A.AomMenu:link {
        color: #466479; TEXT-DECORATION: none;
        font-size : 11px
}
A.AomMenu:visited {
        color: #466479; TEXT-DECORATION: none;
        font-size : 11px
}
A.AomMenu:hover {
        color: #466479; TEXT-DECORATION: underline;
        font-size : 11px
}
A.AomMenu:active  {
        color: #466479; TEXT-DECORATION: none;
        font-size : 11px;
}
.AomProductDetailsTitle {
        COLOR: #000000; FONT-WEIGHT: bold; FONT-SIZE: 10px;
}
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

end_patching();

?>
