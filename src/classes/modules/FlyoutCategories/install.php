<?php

/*
* module installation code
* @version $Id: install.php,v 1.13 2008/05/22 08:33:03 vgv Exp $
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

$MODULE_NAME = "FlyoutCategories";
start_patching($MODULE_NAME);

if (is_object($this)) {
	$schema = (!empty($this->layout))?$this->layout:($this->xlite->get("config.Skin.skin"));
}

//////////////////////////////////////
//	COPY SKIN-DEPENDENT TEMPLATES
//////////////////////////////////////

copy_schema_template("schemes/001_Icons/categories.tpl", $schema, $MODULE_NAME);
copy_schema_template("schemes/001_Icons/cat_template.tpl", $schema, $MODULE_NAME);
copy_schema_template("schemes/002_Explorer/categories.tpl", $schema, $MODULE_NAME);
copy_schema_template("schemes/002_Explorer/cat_template.tpl", $schema, $MODULE_NAME);
copy_schema_template("schemes/003_Horizontal/categories.tpl", $schema, $MODULE_NAME);
copy_schema_template("schemes/004_Candy/categories.tpl", $schema, $MODULE_NAME);
copy_schema_template("schemes/004_Candy/cat_template.tpl", $schema, $MODULE_NAME);
copy_schema_template("main_side.tpl", $schema, $MODULE_NAME);
if ($schema == "GiftsShop") {
	copy_schema_template("common/sidebar_box_cat.tpl", $schema, $MODULE_NAME);
}

//////////////////////////////////////
//	ADMIN ZONE
//////////////////////////////////////

// patching "skins/admin/en/location.tpl"
$location = "skins/admin/en/location.tpl";
$check_str = "FlyoutCategories";
$find_str = <<<EOT
<widget module="Newsletters" template="modules/Newsletters/location.tpl">
EOT;
$replace_str = <<<EOT
<widget module="Newsletters" template="modules/Newsletters/location.tpl">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/location.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/look_feel/body.tpl"
$location = "skins/admin/en/look_feel/body.tpl";
$check_str = "FlyoutCategories";
$find_str = <<<EOT
<a href="admin.php?target=image_edit">Image Editor</a><br>
EOT;
$replace_str = <<<EOT
<a href="admin.php?target=image_edit">Image Editor</a><br>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/look_feel/menu.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/main.tpl"
$location = "skins/admin/en/main.tpl";
$check_str = "FlyoutCategories";
$find_str = <<<EOT
<!-- [/center] -->
EOT;
$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main.tpl">
<!-- [/center] -->
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/categories/body.tpl"
$location = "skins/admin/en/categories/body.tpl";
$check_str = "modules/FlyoutCategories/categories.tpl";
$find_str = <<<EOT
</form>
EOT;
$replace_str = <<<EOT
</form>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/categories.tpl">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/image_files.tpl"
$location = "skins/admin/en/image_files.tpl";
$check_str = "modules/FlyoutCategories/categories_auto.tpl";
$find_str = <<<EOT
<table cellpadding="0" cellspacing="0" border="0">
EOT;
if (is_template_patched($location, $find_str)) {
	$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/categories_auto.tpl">
<table cellpadding="0" cellspacing="0" border="0">
EOT;
	patch_template($location, $check_str, $find_str, $replace_str);
} else {
	$find_str = <<<EOT
<table border="1" cellspacing="0" cellpadding="5">
EOT;
	$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/categories_auto.tpl">
<table border="1" cellspacing="0" cellpadding="5">
EOT;
	patch_template($location, $check_str, $find_str, $replace_str);
}

// patching "skins/admin/en/categories/body.tpl"
$location = "skins/admin/en/categories/body.tpl";
$check_str = "modules/FlyoutCategories/categories_auto.tpl";
$find_str = <<<EOT
<form name="CategoryForm" method="POST" action="admin.php">
EOT;
$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/categories_auto.tpl">
<form name="CategoryForm" method="POST" action="admin.php">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/categories/add_modify_body.tpl"
$location = "skins/admin/en/categories/add_modify_body.tpl";
$check_str = "modules/FlyoutCategories/categories_auto.tpl";
$find_str = <<<EOT
<form name="add_modify_form" action="admin.php" method="POST" enctype="multipart/form-data">
EOT;
$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/categories_auto.tpl">
<form name="add_modify_form" action="admin.php" method="POST" enctype="multipart/form-data">
EOT;
patch_template($location, $check_str, $find_str, $replace_str);

// patching "skins/admin/en/categories/add_modify_body.tpl"
$location = "skins/admin/en/categories/add_modify_body.tpl";
$check_str = "modules/FlyoutCategories/add_modify_body.tpl";
// template for LC v2.2
$find_str = <<<EOT
    <tr>
        <td class="FormButton">Category page title </td>
        <td>&nbsp;</td>
EOT;
if (is_template_patched($location, $find_str)) {
	$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/add_modify_body.tpl">
    <tr>
        <td class="FormButton">Category page title </td>
        <td>&nbsp;</td>
EOT;
	patch_template($location, $check_str, $find_str, $replace_str);
} else {
	// template for LC v2.1.2
	$find_str = <<<EOT
            <widget class="CImageUpload" field="image" actionName="icon" formName="add_modify_form" object="{category}">
		</td>
	</tr>
EOT;
	if (is_template_patched($location, $find_str)) {
		$replace_str = <<<EOT
            <widget class="CImageUpload" field="image" actionName="icon" formName="add_modify_form" object="{category}">
		</td>
	</tr>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/add_modify_body.tpl">
EOT;
		patch_template($location, $check_str, $find_str, $replace_str);
	}
}


// patching "skins/admin/en/memberships.tpl"
$location = "skins/admin/en/memberships.tpl";
$check_str = "FlyoutCategories";
$find_str = "<p>Use this section to review the list of existing membership levels and add new ones.</p>";
$content = @file_get_contents($location);
if (strpos($content, $find_str) !== false) {
    // LC version 2.2
    $replace_str = <<<EOT
$find_str
<widget module="FlyoutCategories" template="modules/FlyoutCategories/memberships_auto.tpl">
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
    // LC version 2.1
    if (strpos($content, $check_str) === false) {
        global $patching_table_row;
        echo "<TR class=\"" . (($patching_table_row) ? "Middle" : "Center") . "\"><TD nowrap>&nbsp;$location&nbsp;</TD></TD><TD nowrap>&nbsp;";

        $content = '<widget module="FlyoutCategories" template="modules/FlyoutCategories/memberships_auto.tpl">'."\n".$content;
        @file_put_contents($location, $content);

        echo "<FONT COLOR=green><B>success</B></FONT>$replace_message";
        echo "&nbsp;</TD></TR>\n";
        $patching_table_row = ($patching_table_row) ? 0 : 1;
    } else {
        already_patched($location);
    }
}


//////////////////////////////////////
//	CUSTOMER ZONE
//////////////////////////////////////

// patching "skins/default/en/main.tpl"
$location = "skins/default/en/main.tpl";
$check_str = "FlyoutCategories/main_flat.tpl";
// template for LC v2.1.2
$find_str = <<<EOT
<BR>
<!-- [/top] -->
EOT;
if (is_template_patched($location, $find_str)) {
	$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_flat.tpl">
<BR>
<!-- [/top] -->
EOT;
	patch_template($location, $check_str, $find_str, $replace_str);
} else {
	// template for LC v2.2
	$find_str = <<<EOT
<!-- [/top] -->
EOT;
	if (is_template_patched($location, $find_str)) {
		$replace_str = <<<EOT
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_flat.tpl">
<!-- [/top] -->
EOT;
		patch_template($location, $check_str, $find_str, $replace_str);
	}
}

// patching "skins/default/en/main.tpl"
$location = "skins/default/en/main.tpl";
$check_str = "modules/FlyoutCategories/main_side.tpl";
$find_str = <<<EOT
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
EOT;
$replace_str = <<<EOT
<div IF="xlite.FlyoutCategoriesEnabled">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_side.tpl" visible="{!target=#main#}">
</div>
<div IF="!xlite.FlyoutCategoriesEnabled">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
</div>
EOT;
// template for LC v2.1.2
if (is_template_patched($location, $find_str)) {
    patch_template($location, $check_str, $find_str, $replace_str);
}

// find old version?
$find_str = <<<EOT
<span IF="xlite.FlyoutCategoriesEnabled">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_side.tpl" visible="{!target=#main#}">
</span>
<span IF="!xlite.FlyoutCategoriesEnabled">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories" visible="{!target=#main#}">
</span>
EOT;
    
if (is_template_patched($location, $find_str)) {
    patch_template($location, null, $find_str, $replace_str);
}

// template for LC v2.2
$find_str = <<<EOT
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories">
EOT;
$replace_str = <<<EOT
<div IF="xlite.FlyoutCategoriesEnabled">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_side.tpl">
</div>
<div IF="!xlite.FlyoutCategoriesEnabled">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories">
</div>
EOT;
if (is_template_patched($location, $find_str)) {
    patch_template($location, $check_str, $find_str, $replace_str);
}

// find old version?
$find_str = <<<EOT
<span IF="xlite.FlyoutCategoriesEnabled">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_side.tpl">
</span>
<span IF="!xlite.FlyoutCategoriesEnabled">
<widget class="CTopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories">
</span>
EOT;

if (is_template_patched($location, $find_str)) {
    patch_template($location, null, $find_str, $replace_str);
}

// patching "skins/default/en/main.tpl" - template for the footer of the body
$location = "skins/default/en/main.tpl";
$check_str = "modules/FlyoutCategories/main_footer.tpl";
if (!is_template_patched($location, $check_str)) {
	if (is_template_patched($location, "</BODY>")) {
		$find_str = "</BODY>";
	} else {
		$find_str = "</body>";
	}
	$replace_str = <<<EOT
<div IF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_footer.tpl">
</div>
</body>
EOT;
	patch_template($location, $check_str, $find_str, $replace_str);
} else {
    $find_str = <<<EOT
<span IF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_footer.tpl">
</span>
EOT;
    $replace_str = <<<EOT
<div IF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_footer.tpl">
</div>
EOT;

    if (is_template_patched($location, $find_str)) {
        patch_template($location, null, $find_str, $replace_str);
    }
}

// patching "skins/default/en/main.tpl" - template for the head 
$location = "skins/default/en/main.tpl";
$check_str = "{xlite.FlyoutCategoriesCssPath}";
if (!is_template_patched($location, $check_str)) {
    if (is_template_patched($location, "</HEAD>")) {
        $find_str = "</HEAD>";
    } else {
        $find_str = "</head>";
    }
    $replace_str = <<<EOT
<LINK IFF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}" rel="stylesheet" type="text/css">
$find_str
EOT;
    patch_template($location, $check_str, $find_str, $replace_str);
} else {
	$check_str = '<LINK IFF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme"';
	$find_str = '<LINK IFF="xlite.FlyoutCategoriesEnabled" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}"';
    $replace_str = '<LINK IFF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}"';
	patch_template($location, $check_str, $find_str, $replace_str);
}

end_patching();

?>
