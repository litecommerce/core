<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Detailed images module base class.
*
* @package Module_DetailedImages
* @access public
* @version $Id: DetailedImage.php,v 1.22 2008/10/23 11:53:15 sheriff Exp $
*/
class DetailedImage extends Base
{
    var $fields = array(
            "image_id"     => 0,
            "product_id"   => 0,
            "image_source" => "D",
            "image_type"   => "image/jpeg",
            "alt"          => "",
            "enabled"      => 1,
            "order_by"     => 0
            );

    var $alias = "images";
    var $autoIncrement = "image_id";
    var $defaultOrder = "order_by";
    var $image = null;

    function &getImage() // {{{
    {
        if (is_null($this->image)) {
            $this->image = func_new("Image","detailed_image", $this->get("image_id"));
        }
        return $this->image;
    } // }}}

	function getImageURL() // {{{
	{
		return $this->get("image.url");
	} // }}}

    function &findImages($product_id = 0) // {{{
    {
        return $this->findAll("product_id='$product_id'");
    } // }}}

    function getImportFields() // {{{
    {
        $layout = array();
        if (isset($this->config->ImportExport->detailed_images_layout)) {
            $layout = explode(',', $this->config->ImportExport->detailed_images_layout);
        }
        // detailed image import fields
        $fields = array(
            "NULL"      => true,
            "sku"       => false,
            "name"      => false,
            "image"     => false,
            "alt"       => false,
            "enabled"   => false,
            "order_by"  => false
            );
        $result = array();
        // build multiarray
        foreach ($fields as $name) {
            $result[] = $fields;
        }
        // fill fields array with the default layout
        foreach ($result as $id => $fields) {
            if (isset($layout[$id])) {
                $selected = $layout[$id];
                $result[$id][$selected] = true;
            }
        }
        return $result;
    } // }}}

    function deepCopyTo($id)
    {
        $_image = $this->get("image");

        $newImg = func_new("Image", $_image->imageClass, $_image->get($_image->autoIncrement));
        if (!$_image->isRead) {
            $_image->read();
        }
        $newImg->properties = $_image->properties;
        if ($newImg->get("source") == "F") {
            $fnPrevious = $newImg->get("data");
        }
        $newImg->set($_image->autoIncrement, $id);
        if ($newImg->get("source") == "F") {
			// createFileName
	        if (is_null($id)) {
       		    $id = $newImg->get($newImg->autoIncrement);
	        }
    	    $ext = $newImg->get("type");
        	$ext = (empty($ext)) ? ".gif" : ("." . substr($newImg->get("type"), 6));
	        $fnNew = $newImg->alias{0} . $newImg->fieldPrefix{0} . "_$id$ext";

			// copyImageFile
	        $src = $newImg->getFilePath($fnPrevious);
	        $dest = $newImg->getFilePath($fnNew);
    	    copy($src, $dest);
        	@chmod($dest, 0644);

            $newImg->set("data", $fnNew);
        }
        $newImg->update();
        return $newImg;
    }

    function _import(&$options) // {{{
    {
        static $line;
        if (!isset($line)) $line = 1; else $line++;

        $properties = $options["properties"];
        $save_images = $options["save_images"];
        $images_directory = $options["images_directory"];
        if (!empty($images_directory)) {
            // update images base directory
            $cfg = func_new("Config");
            if ($cfg->find("name='images_directory'")) {
                $cfg->set("value", $images_directory);
                $cfg->update();
            } else {
                $cfg->set("name", "images_directory");
                $cfg->set("category", "Images");
                $cfg->set("value", $images_directory);
                $cfg->create();
            }
            // re-read config data
            $cfg->readConfig();
        }
        $image = $properties["image"];

        $images_directory = isset($this->config->Images->images_directory) ?
            $this->config->Images->images_directory : "";
        $image_path = empty($images_directory) ? $image : "$images_directory/$image";

        $product = func_new("Product");
        $found = false;
        // try to find product by SKU
        if (!empty($properties["sku"]) && $product->find("sku='".addslashes($properties["sku"])."'")) {
            $found = true;
        }
        // .. or by NAME
        elseif (empty($properties["sku"]) &&  !empty($properties["name"]) && $product->find("name='".addslashes($properties["name"])."'"))
        {
            $found = true;    
        }

        if(!$found){
            
            echo "<b>line# $line:</b> <font color=red>No product found for detailed image $image</font>";
            echo '<br /><br><a href="admin.php?target=import_catalog&page=detailed_images"><u>Click here to return to admin interface</u></a>';
            die;
        }

        $detailed_image = func_new("DetailedImage");
        echo "<b>line# $line:</b> Importing detailed image $image for product ".$product->get("name")."<br>\n";
        // create detailed image
        $detailed_image->set("product_id", $product->get("product_id"));
        $detailed_image->set("properties", $properties);
        $detailed_image->create();
        // fill image content
        $img =& $detailed_image->get("image");
        if ($save_images) {
            // save image content to database
            $img->import($image_path);
        } else {
            // update image info
            $img->set("data", $image);
            $img->set("source", "F");
            $img->set("type", $img->getImageType($image_path));
            $img->update();
        }
    } // }}}
	
	function delete() // {{{
	{
		$image =& $this->get("image");
		$image->delete();
		parent::delete();
	} // }}}
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
