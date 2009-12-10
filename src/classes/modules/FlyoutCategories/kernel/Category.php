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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class Category provides access to shopping cart category.
*
* @package Kernel
* @version $Id: Category.php,v 1.4 2008/10/23 11:54:16 sheriff Exp $
*/
class FlyoutCategories_Category extends Category
{
	function constructor($id=null)
	{
		$this->fields["smallimage_auto"] = 1;
		parent::constructor($id);
	}

    function hasSmallImage() // {{{
    {
        if ($this->get("category_id")==0)
            return false;
        $image =& $this->get("smallImage");
        $data = $image->get("data");
        return !empty($data);
    } // }}}
    
    function &getSmallImage() // {{{
    {   
        if (is_null($this->smallimage)) {
            $this->smallimage = func_new("Image", "category_small", $this->get("category_id"));
        }
        return $this->smallimage;
    } // }}}

	function getSmallImageURL() // {{{
	{
        return $this->get("smallImage.url");
	} // }}}

	function resizeSmallImage($_width, $src_image=null, $filesystem=null)
	{
		include_once "modules/FlyoutCategories/encoded.php";
		if (!FlyoutCategories_gdLibEnabled()) {
			$this->_err_code = "wrong_gd_lib";
			return false;
		}

		if ($src_image == null) {
			$src_image =& $this->get("smallImage");
		}

		if ($src_image->get("source") == "D") {
			$source = $src_image->get("data");
		} else {
			$fpath = $src_image->getFilePath($src_image->get("data"));
			$source = @file_get_contents($fpath);
		}

		if (!$source) {
			$this->_err_code = "source_empty";
			return false;
		}

		$src = @imagecreatefromstring($source);

		if (!$src) {
			$this->_err_code = "wrong_file_format";
			return false;
		}

		$sw = imagesx($src);
		$sh = imagesy($src);

		$dw = $_width;
		$dh = ($sw > 0) ? round($sh * ($dw / $sw)) : 0;

		if ($sw == 0 || $sh == 0 || $dw == 0 || $dh == 0) {
			$this->_err_code = "wrong_size";
			return false;
		}

		$dst = ImageCreateTrueColor($dw, $dh);
		if (!ImageCopyResampled($dst, $src, 0, 0, 0, 0, $dw, $dh, $sw, $sh)) {
			$this->_err_code = "resize_error";
			return false;
		}

		ob_start();
		imagejpeg($dst, "", $this->xlite->get("config.FlyoutCategories.resize_quality"));
		$content = ob_get_contents();
		ob_end_clean();

		// Save
		$dst_image =& $this->get("smallImage");
		$dst_image->set("type", $src_image->get("type"));

		if (isset($filesystem)) {
			$filesystem = ((bool) $filesystem) ? "F" : "D";
		}

		if ($filesystem != "D" && $filesystem) {
			$fname = $dst_image->createFileName();
			$fpath = $dst_image->getFilePath($fname);

			$dst_image->set("data", $fname);
			$dst_image->set("source", "F");

			// Save image content fo FS
			$handle = @fopen($fpath, "w");
			if (!$handle) {
				$this->_err_code = "open_write";
				return false;
			}

			@fwrite($handle, $content);
			@fclose($handle);
		} else {
			$dst_image->set("data", $content);
			$dst_image->set("source", "D");
		}

		$dst_image->update();
		$this->_err_code = "";

		return true;
	}

    function delete()
    {
		$image =& $this->get("smallImage");
		$image->delete();

        parent::delete();
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
