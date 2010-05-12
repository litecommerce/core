<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_ImageEditor_ImageEditor extends XLite_Base
{
    public $images      = array();
    public $images_path;
    public $error       = false;
    public $uploadError = '';

    public function __construct($iniFile = null)
    {
        parent::__construct();
        if (!is_readable($iniFile)) {
            $this->error = true;
        } else {
            $this->images_path = $iniFile;
        }
    }

    function isError()
    {
        return $error;
    }

    function getImagesPath()
    {
        return $this->images_path;
    }

    function getImages()
    {
        if (!empty($this->images)) {
            return $this->images;
        }
        $this->images = parse_ini_file($this->images_path, true);
        return $this->images;
    }

    function uploadImage($image_field, $image_name)
    {
        // fetch images first
        $images = $this->get("images");
        // upload/update image
        $image_file_name = $this->images[$image_name]["filename"];
        $upload = new XLite_Model_Upload($_FILES[$image_field]);
        $move   = $upload->move($image_file_name);
        if ($move && !LC_OS_IS_WIN) {
            $real_img_name = realpath(".") . '/' . $image_file_name;
            @chmod($real_img_name, get_filesystem_permissions(0666));
        } elseif (!$move) {
            $this->uploadError = $upload->getErrorMessage();
        }

        return $upload->getCode();
    }
}
