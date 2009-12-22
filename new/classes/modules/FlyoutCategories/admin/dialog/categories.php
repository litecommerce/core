<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL:                                                        |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*
* @package Module_FlyoutCategories
* @access public
* @version $Id$
*/
class Admin_Dialog_categories_FlyoutCategories extends Admin_Dialog_categories
{
	var $categoriesMenu = array();
	var $categoriesMenuMode = 0;
	var $customerLayout = null;
	var $redirect_back = true;
	var $_categories_processed = 0;
	var $_html2js_cache_size = 1024;
	var $catalog_key = "";

    function getZIndex()
    {
        return 1000;
    }

	function getActiveScheme()
	{
		if ( is_null($this->_scheme) ) {
			$scheme_id = $this->get("config.FlyoutCategories.scheme");
			$this->_scheme = func_new("FCategoriesScheme", $scheme_id);
		}

		return $this->_scheme;
	}

	function _prepareJScodeStr($str)
	{
		$str = ltrim($str);
		$str = str_replace("\\", "\\\\", $str);
		$str = str_replace("\"", "\\\"", $str);
		$str = str_replace("<script", "\\x3cscript", $str);
		$str = str_replace("</script>", "\\x3c/script\\x3e", $str);
		$str = str_replace("script>", "script\\x3e", $str);

		return $str;
	}

	function _prepareJScode($data)
	{
		if (!(isset($data) && is_array($data))) {
			return array ("");
		}

		$newData = array();
		$newDataPtr = 0;
		$processedDataPtr = -1;
		foreach ($data as $str) {
			if (!isset($newData[$newDataPtr])) {
				$newData[$newDataPtr] = "";
			}

			$str = $this->_prepareJScodeStr($str);
			$newData[$newDataPtr] .= $str;
			if (strlen($newData[$newDataPtr]) > $this->_html2js_cache_size) {
				$newData[$newDataPtr] = "document.write(\"" . $newData[$newDataPtr] . "\");";
				$processedDataPtr = $newDataPtr;
				$newDataPtr ++;
			}
		}
		if ($processedDataPtr != $newDataPtr) {
			$newData[$newDataPtr] = "document.write(\"" . $newData[$newDataPtr] . "\");";
		}

		return $newData;
	}

	function splitHtmlJs($origHtml, $destJS)
	{
		$catalog_path = $this->get_fc_catalog_path();

		$fd1Name .= $catalog_path."/".$origHtml;
		$fd2Name .= $catalog_path."/".$destJS;

        $fd2 = @fopen($fd2Name, "wb");
    	if ( $fd1 && $fd2 ) {
            $contents = '';
            while (!feof($fd1)) {
              $contents .= fread($fd1, 8192);
            }
            fclose($fd1);
            $contents = explode("\n", $contents);
            foreach($contents as $strPtr => $str) {
            	if (strpos($str, $fd2Name) !== false) {
            	    if ($this->get("config.FlyoutCategories.force_js_in_layout")) {
                	    $htmlPart = array_slice($contents, 0, $strPtr+1);
                	    $jsPart = array_slice($contents, $strPtr+1);
                	    $jsPart = $this->_prepareJScode($jsPart);
                	} else {
                	    $htmlPart = array_merge(array_slice($contents, 0, $strPtr), array_slice($contents, $strPtr+1));
                	    $jsPart = array("");
                	}

        			$fd1 = @fopen($fd1Name, "wb");
        			@fwrite($fd1, implode("\n", $htmlPart));
        			@fwrite($fd1, "\n");
        			fclose($fd1);

        			@fwrite($fd2, implode("\n", $jsPart));
        			@fwrite($fd2, "\n");
        			fclose($fd2);
            		break;
            	}
            }
        }
	}

	function get_fc_catalog_path()
	{
		$layout_path = $this->customerLayout->getPath();
		if (substr($layout_path, strlen($layout_path)-1, 1) == "/") {
			$layout_path = substr($layout_path, 0, strlen($layout_path)-1);
		}
		$layout_path .= "/modules/FlyoutCategories/catalog";
		$layout_path .= (($this->catalog_key) ? "/".$this->catalog_key : "");

		return $layout_path;
	}

	function action_fc_categories_actions()
	{
		// generate small icons for all categories
		if ($this->get("auto_generate")) {
			$width = $this->xlite->get("config.FlyoutCategories.smallimage_width");

			$co = func_new("Category");
			$categories = $co->findAll();

			foreach ($categories as $category) {
				if (!$category->get("smallimage_auto"))
					continue;

				$image = $category->get("image");
				$category->resizeSmallImage($width, $image, (($category->get("smallimage_source") == "F") ? true : false));
			}
		}

		// rebuild categories
		if ($this->get("rebuild_categories")) {
			$this->action_build_categories();
		}


	}


	function getFlyoutCategoriesCacheConditions()
	{
		$conditions = array();
		$conditions[] = array(
			"key"			=> "",
			"conditions"	=> array(
                "membership" => ""
            ),
		);

		$memberships = $this->xlite->config->get("Memberships.membershipsCollection");
		if (!is_array($memberships) || count($memberships) <= 0) {
			$values = $this->xlite->config->get("Memberships.memberships");
			foreach ($values as $value) {
				$memberships[] = array("membership" => $value);
			}
		}

		if (!is_array($memberships) || count($memberships) <= 0) {
			return $conditions;
		}

		foreach ((array)$memberships as $membership) {
			$conditions[] = array(
				"key"			=> md5($membership["membership"]),
				"conditions"	=> array(
					"membership"	=> $membership["membership"],
				),
			);
		}

		return $conditions;
	}

	function setFlyoutCategoriesCacheCondition($conditions)
	{
		$profile = $this->xlite->auth->get("profile");
		if (is_object($profile)) {
			$profile->_FlyoutCategories_membership = $conditions["membership"];
		}

		// set customer zone
		$this->fc_adminZone = $this->xlite->is("adminZone");
		$this->xlite->set("adminZone", false);
	}

	function cleanFlyoutCategoriesCacheCondition()
	{
		$profile = $this->xlite->auth->get("profile");
		if (is_object($profile)) {
			$profile->_FlyoutCategories_membership = null;
		}

		// return admin zone
		$this->xlite->set("adminZone", $this->fc_adminZone);
	}


	//*
	// Action to generate whole category static structure
	//*/
	function action_build_categories($return_url=null)
	{
		$this->customerLayout = null;

		$config = func_new("Config");
		$config->createOption("FlyoutCategories", "last_categories_processed", 0);

		if ($this->get("config.FlyoutCategories.scheme") <= 0)
			return;


		if (!$this->get("silent")) {
			if (method_exists($this, "displayPageHeader")) {
				// Display std. header in version 2.2
				$this->displayPageHeader();
			} else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<TITLE>Categories Tree</TITLE>
</HEAD>
<BODY bgcolor=#FFFFFF link=#0000FF alink=#4040FF vlink=#800080>
<?php
			}

			func_refresh_start();
		}

		// get customer layout path
		$this->customerLayout = $this->getCustomerLayout();

		// Drop "Flyout Categories build" flag
		$config = func_new("Config");
		$config->createOption("FlyoutCategories", "flyout_categories_built", 0);

		$success = true;


		// get all conditions
		$conditions = $this->getFlyoutCategoriesCacheConditions();

		// Build static 'catalog' with each key
		foreach ($conditions as $condition) {
			$this->catalog_key = $condition["key"];
			$key = implode("-", $condition["conditions"]);

			if ( !$this->get("silent") ) {
?>
<p><font style="FONT-WEIGHT: bold; FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif">Compiling categories tree.</font><br>Key: <?php echo (($key) ? $key : "[N/A]"); ?></p>

<?php
			}

			$this->setFlyoutCategoriesCacheCondition($condition["conditions"]);
			$result = $this->fc_build_categories($return_url);
			$this->cleanFlyoutCategoriesCacheCondition();
			if (!$result) {
				$success = false;
				break;
			}

			if ( !$this->get("silent") ) {
?><hr><?php
			}

		}

		// Set "Flyout Categories built" flag
		if ( $success ) {
			$config = func_new("Config");
			$config->createOption("FlyoutCategories", "flyout_categories_built", 1);
			$config->createOption("FlyoutCategories", "category_changed", 0);
			$config->createOption("FlyoutCategories", "last_categories_processed", $this->_categories_processed);
		} elseif ( !$this->get("silent") ) {
			print("<BR><font color='red'>WARNING: Can't compile Flyout Categories.</font><BR>");
		}


		// Get return URL
		if ($return_url == null || strlen(trim($return_url)) <= 0) {
			$return_url = $_SERVER["HTTP_REFERER"];
		}

		$return_url = preg_replace("/[\&\?]status=\w+/", "", $return_url);
		$return_url .= "&".(($success) ? "status=rebuilt" : "status=error");

		if (!$this->get("silent")) {
?>
<hr>
If you are not redirected automatically,<br>
click on <a href="<?php echo $return_url; ?>"><b>this link</b></a> to return to admin zone<br>
<?php

			if ($this->get("redirect_back") && $success) {
?>
<SCRIPT language="JavaScript">
loaded = true;
if (window.opera) {
	window.onload = opera_click;
} else {
	window.document.location = "<?php echo $return_url; ?>";
}
function opera_click() {
	window.document.location = "<?php echo $return_url; ?>";
}
</SCRIPT>
<?php
			}

			if (method_exists($this, "displayPageHeader")) {
				// Display std. footer in version 2.2
				$this->displayPageFooter();
			} else {
?>
</BODY>
</HTML>
<?php
			}
		}

		$this->set("silent", true);

		return true;
	}


	//*
	// Generate root category and subcategories body
	// 
	//*/
	function fc_build_categories($return_url=null)
	{
		$this->categoriesMenu = array();
		$this->categoriesMenuMode = 0;
		$this->_categories_processed = 0;

		$scheme = $this->get("activeScheme");

		$root = null;
        $root = $this->buildTree($root);


		if (!$scheme->get("explorer")) {
			$this->categoriesMenuMode ++;
		}

		$this->processTree($root);

		$catalog_path = $this->get_fc_catalog_path();

		// drop and create catalog
		$fNode = func_new("FileNode");
		$fNode->path = $catalog_path; //customerLayout->getPath() . "/modules/FlyoutCategories/$catalog";
		$fNode->remove();
		$fNode->createDir();
		$fNode = null;

		chmod($catalog_path, 0755);


		if ($scheme->get("explorer")) {
			// build explorer tree
			$success = $this->fc_explorer_scheme();
		} else {
    		// Create main template
			$fd1 = @fopen("$catalog_path/body.tpl", "wb");
			$fd2 = @fopen("$catalog_path/body_footer.tpl", "wb");
            if ( $fd1 && $fd2 ) {
    			foreach((array) $this->categoriesMenu[$this->categoriesMenuMode] as $items) {
        			foreach($items as $item_key => $item) {
        				if (!$this->get("silent")) {
        	                echo "Processing ";
						}

        				if ($item->is_first) {
                            $template = func_new("Object");

        					// Set Advanced options
        					$options = $scheme->get("options");
        					foreach ($options as $k=>$v) {
        						$scheme->$k = $v["value"];
        					}

        					$template->set("scheme", $scheme);
    						$file = $scheme->get("templates");
        					if ($item->parent != 0) {
        						$file .= "/scat_template.tpl";
        						$fd = $fd2;
        						$scatCounter = $this->get("scatCounter");
        						if (!isset($scatCounter)) {
        							$scatCounter = 1;
        							$this->set("scatCounter", $scatCounter);
        						}
        						$template->set("scatCounter", $scatCounter);
								$scatCounter ++;
								$this->set("scatCounter", $scatCounter);
        					} else {
        						$file .= "/cat_template.tpl";
        						$fd = $fd1;
        					}

        					if ( !is_readable($file) ) {
        						return false;
        					}

        					$file = $this->getRelativeTemplatePath($file);
        					$template->set("template", $file);

                			$template->set("parent", $item->parent);
                			$skinPath = $this->customerLayout->getPath();
                			if (substr($skinPath, strlen($skinPath)-1, 1) == "/") {
                				$skinPath = substr($skinPath, 0, strlen($skinPath)-1);
                			}
                			$template->set("skinPath", $skinPath);
							$template->set("catalogPath", "$catalog_path");
							$template->set("imagesPath", "$catalog_path/images/");
							$template->set("zIndex", $this->getZIndex());
        				}
                        $item->category = func_new("Category", $item->category_id);

        				if ( !$this->get("silent") )
        	                echo "[<b>" . $item->category->get("stringPath") . "</b>] <font color=green>OK</font><br>";
        				$value = (array) $item;

        				// get imageUrl
        				$adminZone = $this->xlite->is("adminZone");
        				$this->xlite->set("adminZone", false);
        				if ($item->category->hasSmallImage()) {
        					$value["image_url"] = $item->category->getSmallImageUrl();
        				} elseif ($item->category->hasImage()) {
        					$value["image_url"] = $item->category->getImageUrl();
        				} else {
        					$value["image_url"] = "";
        				}
        				$this->xlite->set("adminZone", $adminZone);

        				
        				$value["path_ids"] = array();
        				$category_path = $item->category->get("path");
        				foreach ((array)$category_path as $v) {
        					$value["path_ids"][] = $v->get("category_id");
        				}

    					$items[$item_key] = $value;
        				if ($item->is_last) {
                			$template->set("items", $items);
                			@fwrite($fd, $this->compile($template) . "\n");
        				}
        			}
    			}
    			@fwrite($fd1, "\n");
    			fclose($fd1);
    			@fwrite($fd2, "\n");
    			fclose($fd2);

    			$this->splitHtmlJs("body.tpl", "body_header.js");
    			$this->splitHtmlJs("body_footer.tpl", "body_footer.js");

    			$success = true;
    		}
		} // $scheme->get("explorer")

		// copy additional template/image files
		$fNode = func_new("FileNode");
		$files = array("categories.tpl", "style.css", "script.js", "image.tpl", "images", "styles", "script");
		foreach($files as $file) {
			$fNode->path = $scheme->get("templates") . "/" . $file;
			$fNode->newPath = "$catalog_path/" . $file;
			$fNode->copy();
			if (file_exists($fNode->newPath)) {
				chmod($fNode->newPath, 0755);
			}
		}

		return $success;
	}

	function getRelativeTemplatePath($file)
	{
		$skin_details = $this->xlite->get("options.skin_details");
		return str_replace("skins/" . $skin_details->get("skin") . "/" . $skin_details->get("locale") . "/", "", $file);
	}

	function fc_explorer_scheme()
	{
		$items = $this->categoriesMenu[$this->categoriesMenuMode];
		foreach ($items as $k=>$v) {
			unset($v->subcategories);
			$items[$k] = $v;
		}

		$nodes = array();
		$old_depth = 0;
		foreach ($items as $k=>$v) {
			$depth = $v->depth;

			$nodes[$k] = (array)$v;
			$category = func_new("Category", $v->category_id);
			$nodes[$k]["name"] = $category->get("name");
			$category = null;

			for ($i = 0; $i < $depth; $i++) {
				if ( $depth == 1 && $k == count($items)-1 ) {
					$nodes[$k]["chain"][] = "E";
					continue;
				}

				if ( $i < $depth-1 ) {
					$nodes[$k]["chain"][] = "M";
					continue;
				} else {
					$nodes[$k]["chain"][] = "B";
					continue;
				}

				$nodes[$k]["chain"][] = "";
			}

			if ( $depth > $old_depth ) {
				$nodes[$k]["tag_type"] = "open";
			} elseif ($depth < $old_depth) {
				$nodes[$k]["tag_type"] = "close";
			} else {
				$nides[$k]["tag_type"] = "none";
			}

			for ($i = 0; $i < abs($old_depth - $depth); $i++) {
				$nodes[$k]["tags"][] = "</div>";
			}
			$old_depth = $depth;
		}

		for ($i = count($nodes)-1; $i > 0; $i--) {
			$pre = $nodes[$i+1]["chain"];
			$node = $nodes[$i]["chain"];

			for ($j = 0; $j < count($node); $j++) {
				if ( $j == count($node)-1 && $pre[$j] == "" ) {
					$node[$j] = "E";
					continue;
				}

				if ( !isset($pre[$j]) || $pre[$j] == "" )
					$node[$j] = "";

			}

			$nodes[$i]["chain"] = $node;
		}

		for ($i = 0; $i < count($nodes); $i++) {
			if ( count($nodes[$i]["chain"]) < count($nodes[$i+1]["chain"]) ) {
				$nodes[$i]["chain"][] = "2";
				$nodes[$i]["is_leaf"] = 0;
			} else {
				$nodes[$i]["chain"][] = "1";
				$nodes[$i]["is_leaf"] = 1;
			}
		}

		$catalog_path = $this->get_fc_catalog_path();

		$fd1 = @fopen("$catalog_path/body.tpl", "wb");
		$fd2 = @fopen("$catalog_path/body_footer.tpl", "wb");

		if ( $fd1 && $fd2 ) {
			$fd = $fd1;
			// perform compile template
			$scheme = $this->get("activeScheme");

			$options = $scheme->get("options");
			foreach ($options as $k=>$v) {
				$scheme->$k = $v["value"];
			}

			$template = func_new("Object");
			$template->set("scheme", $scheme);
			$template->set("nodes", $nodes);

			$file = $scheme->get("templates") . "/cat_template.tpl";
			$file = $this->getRelativeTemplatePath($file);
			$template->set("template", $file);

			$skinPath = $this->customerLayout->getPath();
			if (substr($skinPath, strlen($skinPath)-1, 1) == "/") {
				$skinPath = substr($skinPath, 0, strlen($skinPath)-1);
			}
			$template->set("skinPath", $skinPath);
			$template->set("catalogPath", "$catalog_path");
			$template->set("imagesPath", "$catalog_path/images/");
			$template->set("zIndex", $this->getZIndex());

			$compiled = $this->compile($template) . "\n";
			fwrite($fd, $compiled);

			@fwrite($fd1, "\n");
			fclose($fd1);
			@fwrite($fd2, "\n");
			fclose($fd2);

			$this->splitHtmlJs("body.tpl", "body_header.js");

			return true;
		}

		return false;
	}


    function compile($template)
    {
        // replace layout with mailer skinned
     	$layout = func_get_instance("Layout");
        $skin = $layout->get("skin");
        $layout->set("skin", $this->customerLayout->get("skin"));

        $component = func_new("Component");
        
        $component->template = $template->get("template");
        $component->init();
        $component->set("data", $template);
        ob_start();
        $component->display();
        $text = trim(ob_get_contents());
        ob_end_clean();

        // restore old skin
        $layout->set("skin", $skin);
            
        return $text;
    }

	function getCustomerLayout()
	{
		if (!is_null($this->customerLayout)) {
			return $this->customerLayout;
		}

        $this->customerLayout = func_new("Layout");
		$this->xlite->set("adminZone", false);
        $this->customerLayout->initFromGlobals();
		$this->xlite->set("adminZone", true);

		return $this->customerLayout;
    }

    function processTree(&$tree)
    {
    	if (isset($tree->subcategories)) {
    		for($i=0; $i<$tree->subcategories_chunks; $i++) {
    			$this->processTreeChunk($tree->subcategories[$i]);
    		}
    	}
    }

    function processTreeChunk(&$chunk)
    {
    	foreach($chunk as $chunk_item) {
    		$this->processTreeItem($chunk_item);
    		if (isset($chunk_item->subcategories)) {
				$this->processTree($chunk_item);
   			}
    	}
    }

    function processTreeItem(&$item)
    {
		include_once "modules/FlyoutCategories/encoded.php";
		FlyoutCategories_processTreeItem($this, $item);
    }

	function buildTree(&$parent)
	{
		include_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_buildTree($this, $parent);
	}

	function isFCatGDlibEnabled()
	{
		include_once "modules/FlyoutCategories/encoded.php";
		return FlyoutCategories_gdLibEnabled();
	}

	function action_delete()
	{
		parent::action_delete();

		// rebuild cache if new category added
		if ($this->get("config.FlyoutCategories.category_autoupdate")) {
			$delete_return_url = $this->shopURL("admin.php?target=categories&category_id=0");
			$this->action_build_categories($delete_return_url);
		}

	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
