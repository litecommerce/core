<?php

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
        echo "<FONT COLOR=blue><B>already patched</B></FONT>";
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
       		echo "<FONT COLOR=blue><B>already patched</B></FONT>";
    	}
       	echo "&nbsp;</TD></TR>\n";
        $patching_table_row = ($patching_table_row) ? 0 : 1;
    }
}

start_patching("AdvancedSecurity");

$location = "skins/admin/en/main.tpl";
    
if (!is_template_patched($location, "AdvancedSecurity"))
{
    $find_str = <<<EOT
<widget target="order_list" template="order/search.tpl">
EOT;
    $replace_str = <<<EOT
<widget target="order_list,order,advanced_security" module="AdvancedSecurity" template="modules/AdvancedSecurity/advanced_security.tpl">
<widget target="order_list" template="order/search.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/location.tpl";
     
if (!is_template_patched($location, "AdvancedSecurity"))
{
    $find_str = <<<EOT

EOT;
    $replace_str = <<<EOT

<widget module="AdvancedSecurity" template="modules/AdvancedSecurity/location.tpl">
EOT;
    patch_template($location, null, $find_str, $replace_str);
} else {
    already_patched($location);
}

$location = "skins/admin/en/payment_methods/body.tpl";
     
if (!is_template_patched($location, "AdvancedSecurity"))
{
    $find_str = <<<EOT
instructions on using corresponding payment methods.<br>
</span>
<hr><br>

<table cellpadding="0" cellspacing="0" border="0">
EOT;
    $replace_str = <<<EOT
instructions on using corresponding payment methods.<br>
</span>
<hr><br>
<widget module="AdvancedSecurity" template="modules/AdvancedSecurity/payment_methods_note.tpl">

<table cellpadding="0" cellspacing="0" border="0">
EOT;

    $src = @file_get_contents($location);
    $src = preg_replace("/\r\n/m","\n", $src);
    if (strpos($src, $find_str) === false) {
        // LC version lower than 2.2
        $find_str = <<<EOT
ke all checks payable to...".<br>
<hr><br>

<form action="admin.php" method="POST" name="payment_methods">
EOT;
        $replace_str = <<<EOT
ke all checks payable to...".<br>
<hr><br>
<widget module="AdvancedSecurity" template="modules/AdvancedSecurity/payment_methods_note.tpl">

<form action="admin.php" method="POST" name="payment_methods">
EOT;
    }

    patch_template($location, null, $find_str, $replace_str);

} else {
    already_patched($location);
}

end_patching();
?>
