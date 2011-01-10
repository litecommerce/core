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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Product\Details\Customer;

/**
 * Gallery
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @ListChild (list="product.details.page.image", weight="20")
 * @ListChild (list="product.details.quicklook.image", weight="20")
 */
class Gallery extends \XLite\View\Product\Details\Customer\ACustomer
{
    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/parts/gallery.tpl';
    }

    /**
     * Get LightBox library images directory 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLightBoxImagesDir()
    {
        return \XLite::getInstance()->getShopUrl(
            \XLite\Model\Layout::getInstance()->getPath() . 'images/lightbox'
        );
    }

    /**
     * Check visibility
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getProduct()->countImages() > 1;
    }

    /**
     * Get list item class attribute
     * 
     * @param integer $i Detailed image index
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListItemClassAttribute($i)
    {
        $class = $this->getListItemClass($i);

        return $class ? 'class="' . $class . '"' : '';
    }

    /**
     * Get list item class name
     * 
     * @param integer $i Detailed image index
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListItemClass($i)
    {
        return 0 == $i ? 'selected' : '';
    }

    /**
     * Get image URL (middle-size)
     * 
     * @param \XLite\Model\Base\Image $image  Image
     * @param integer                 $width  Width limit OPTIONAL
     * @param integer                 $height Height limit OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMiddleImageURL(\XLite\Model\Base\Image $image, $width = null, $height = null)
    {
        $result = $image->getResizedURL($width, $height);

        return $result[2];
    }

    /**
     * Register files from common repository
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();

        $list['js'][] = 'js/jquery.colorbox-min.js';
        $list['css'][] = 'css/colorbox.css';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/parts/gallery.css';

        return $list;
    }

    /**
     * Return the max image width depending on whether it is a quicklook popup, or not
     * 
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWidgetMaxWidth()
    {
        return strpos($this->viewListName, 'quicklook') ? 300 : 330;
    }

    /**
     * Get image alternative text 
     * 
     * @param \XLite\Model\Base\Image $image Image
     * @param integer                 $i     Image index
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAlt(\XLite\Model\Base\Image $image, $i)
    {
        return $image->getAlt() ?: \XLite\Core\Translation::lbl('Image X', array('index' => $i));
    }
}

