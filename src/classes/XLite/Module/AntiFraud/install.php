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
    
start_patching('AntiFraud');

$location = "skins/admin/en/countries.tpl";
if (!is_template_patched($location, "AntiFraud"))
{
    $find_str = <<<EOT
    <td>Active</td>
EOT;
    $replace_str = <<<EOT
    <td>Active</td>
    <widget module="AntiFraud" template="modules/AntiFraud/risk_country/label.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
    <td>
        <input type="checkbox" name="countries[{country.code}][enabled]" value="Y" checked="{country.enabled}">
    </td>
EOT;
    $replace_str = <<<EOT
    <td align="center">
        <input type="checkbox" name="countries[{country.code}][enabled]" value="Y" checked="{country.enabled}">
    </td>
    <widget module="AntiFraud" template="modules/AntiFraud/risk_country/checkbox.tpl">
</tr>
EOT;
    patch_template($location, "checkbox.tpl", $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/main.tpl";
if (!is_template_patched($location, "AntiFraud"))
{
    $find_str = <<<EOT
<widget target="order_list" template="order/search.tpl">
EOT;
    $replace_str = <<<EOT
<widget module="AntiFraud" target='order' mode="{mode}" template="common/dialog.tpl" body="modules/AntiFraud/tracking/message.tpl" head="Antifraud Service Notification" visible="{mode}">
<widget module="AntiFraud" target='order' template="common/dialog.tpl" body="modules/AntiFraud/order.tpl" head="Antifraud Service" visible="{order.details.af_result}">
<widget target="order_list" template="order/search.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/order/list.tpl";
if (!is_template_patched($location, "AntiFraud"))
{
    $find_str = <<<EOT
    <td align=left>Status</td>
EOT;
    $replace_str = <<<EOT
    <td align=left>Status</td>
    <widget module="AntiFraud" template="modules/AntiFraud/orders/label.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
    <td><widget template="common/order_status.tpl"></td>
EOT;
    $replace_str = <<<EOT
    <td><widget template="common/order_status.tpl"></td>
    <widget module="AntiFraud" template="modules/AntiFraud/orders/factor.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/order/search_form.tpl";
if (!is_template_patched($location, "AntiFraud"))
{
    $find_str = <<<EOT
<TR>
<TD class=FormButton noWrap height=10>Order status:</TD>
<TD height=10>
<widget class="CStatusSelect" field="status" allOption>
</TD></TR>
EOT;
    $replace_str = <<<EOT
<TR>
<TD class=FormButton noWrap height=10>Order status:</TD>
<TD height=10>
<widget class="CStatusSelect" field="status" allOption>
</TD></TR>
<widget module="AntiFraud" template="modules/AntiFraud/orders/search_form.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

$location = "skins/admin/en/order/recent_orders.tpl";
if (!is_template_patched($location, "AntiFraud"))
{
    $find_str = <<<EOT
    <td align=left>Status</td>
EOT;
    $replace_str = <<<EOT
    <td align=left>Status</td>
    <widget module="AntiFraud" template="modules/AntiFraud/orders/label.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);

    $find_str = <<<EOT
    <td><widget template="common/order_status.tpl"></td>
EOT;
    $replace_str = <<<EOT
    <td><widget template="common/order_status.tpl"></td>
    <widget module="AntiFraud" template="modules/AntiFraud/orders/factor.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
}
else
{
    already_patched($location);
}

end_patching();

?>
