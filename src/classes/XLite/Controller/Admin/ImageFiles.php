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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ImageFiles extends AAdmin
{
    function getImagesDir()
    {
        $images = $this->get('imageClasses');
        return ($this->xlite->config->Images->images_directory != "") ? $this->xlite->config->Images->images_directory : \XLite\Model\Image::IMAGES_DIR;
    }

    function action_move_to_filesystem($from = false)
    {
        $this->startDump();
        $images = $this->get('imageClasses');
        $imageClass = $images[\XLite\Core\Request::getInstance()->index];
        $n = $imageClass->getImage()->moveToFilesystem($from);
        $m = $this->xlite->get('realyMovedImages');

        echo "<br><b>$m image" . (($m != 1) ? "s":"") . " from $n image" . (($n != 1) ? "s":"") . " " . (($m != 1) ? "are":"is") . " moved.</b><br>";
    }

    function action_move_to_database()
    {
        $this->action_move_to_filesystem(true);
    }

    function action_update_default_source()
    {
        $images = $this->get('imageClasses');
        $imageClass = $images[\XLite\Core\Request::getInstance()->index];
        $imageClass->getImage()->setDefaultSource($this->get('default_source'));
    }

    /**
    * Returns image types formatted as array of class Image
    * with an additional field $image->imageClass - a string which displays
    * the image class.
    */
    function getImageClasses()
    {
        return \XLite\Model\Image::getImageClasses();
    }
    
    function getPageReturnUrl()
    {
        return array('<a href="'.$this->get('url').'"><u>Return to admin zone</u></a>');
    }

    function action_update_images_dir()
    {
        $images_directory = $this->get('images_dir');
        $images = $this->get('imageClasses');
        $images_directory = ($images_directory != "") ? $images_directory : \XLite\Model\Image::IMAGES_DIR;

        \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
            array(
                'category' => 'Images',
                'name'     => 'images_directory',
                'value'    => $images_directory
            )
        );

        // re-read config data
        $this->xlite->config = \XLite\Core\Config::readConfig(true);
        $this->config = $this->xlite->config;
    }
}
