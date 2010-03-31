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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Admin_Dialog_image_files description.
*
* @package admin_dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Admin_ImageFiles extends XLite_Controller_Admin_Abstract
{
    function getImagesDir()
    {
		$images = $this->get("imageClasses");
        return ($this->getComplex('xlite.config.Images.images_directory') != "") ? $this->getComplex('xlite.config.Images.images_directory') : IMAGES_DIR;        
    }

	function action_move_to_filesystem($from = false)
	{
        $this->startDump();
		$images = $this->get("imageClasses");
		$imageClass = $images[XLite_Core_Request::getInstance()->index];
		$n = $imageClass->getImage()->moveToFilesystem($from);
		$m = $this->xlite->get("realyMovedImages");

		echo "<br><b>$m image" . (($m != 1) ? "s":"") . " from $n image" . (($n != 1) ? "s":"") . " " . (($m != 1) ? "are":"is") . " moved.</b><br>";
	}

	function action_move_to_database()
	{
		$this->action_move_to_filesystem(true);
	}

	function action_update_default_source()
	{
		$images = $this->get("imageClasses");
		$imageClass = $images[XLite_Core_Request::getInstance()->index];
		$imageClass->getImage()->setDefaultSource($this->get("default_source"));
	}

    /**
    * Returns image types formatted as array of class Image
    * with an additional field $image->imageClass - a string which displays
    * the image class.
    */
	function getImageClasses()
	{
        return XLite_Model_Image::getInstance()->get("imageClasses");
	}
	
	function getPageReturnUrl()
	{
		return array('<a href="'.$this->get("url").'"><u>Return to admin zone</u></a>');
	}

    function action_update_images_dir()
    {
        $images_directory = $this->get("images_dir");
		$images = $this->get("imageClasses");
        $images_directory = ($images_directory != "") ? $images_directory : IMAGES_DIR;

        $cfg = new XLite_Model_Config();
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
        $this->xlite->config = $cfg->readConfig();
        $this->config = $this->xlite->config;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
