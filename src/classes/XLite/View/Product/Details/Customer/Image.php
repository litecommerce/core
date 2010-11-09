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
 * Image
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @ListChild (list="product.details.page.image.photo", weight="10")
 * @ListChild (list="product.details.quicklook.image", weight="10")
 */
class Image extends \XLite\View\Product\Details\Customer\ACustomer
{
    /**
     * Cloud zoom layer maximum width
     */
    const ZOOM_MAX_WIDTH = 460;

    /**
     * Zoom coefficient
     */
    const K_ZOOM = 1.3;

    /**
     * Image max width on product details page and Quick Look box
     */
    const IMG_MAX_WIDTH_PD = 330;
    const IMG_MAX_WIDTH_QL = 300;

    /**
     * Relative horizontal position of the zoom box
     */
    const ZOOM_ADJUST_X_PD = 97;
    const ZOOM_ADJUST_X_QL = 32;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/parts/image-regular.tpl';
    }

    /**
     * Return current template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        return ($this->isZoom())
            ? $this->getDir() . '/parts/image-zoom.tpl'
            : $this->getDefaultTemplate();
    }

    /**
     * Check if the product has any image to ZOOM
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function isZoom()
    {
        $isZoom = false;

        if ($this->getProduct()->hasImage()) {

            foreach($this->getProduct()->getImages() as $img) {
   
                if ($img->getWidth() > self::K_ZOOM * $this->getWidgetMaxWidth()) {
                    $isZoom = true;
                    break;
                } 
            }
        }

        return $isZoom;
    }

    /**
     * Get zoom image 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getZoomImageURL()
    {
        return $this->getProduct()->getImage()->getURL();
    }

    /**
     * Get zoom layer width
     *
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getZoomWidth()
    {
        return min($this->getProduct()->getImage()->getWidth(), self::ZOOM_MAX_WIDTH);
    }


    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'js/cloud-zoom.min.js';

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
        $list[] = 'css/cloud-zoom.css';

        return $list;
    }

    /**
     * Return the max image width depending on whether it is a quicklook popup, or not
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWidgetMaxWidth()
    {
        return strpos($this->viewListName, 'quicklook')
            ? self::IMG_MAX_WIDTH_QL
            : self::IMG_MAX_WIDTH_PD;
    }

    /**
     * Get image container max height
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function getWidgetMaxHeight()
    {
        $maxHeight = 0;

        if ($this->getProduct()->hasImage()) {
            foreach($this->getProduct()->getImages() as $img) {
                if ($img->getWidth() > $this->getWidgetMaxWidth()) {
                    $maxHeight = max($img->getHeight() * $this->getWidgetMaxWidth() / $img->getWidth(), $maxHeight);
                } else {
                    $maxHeight = max($img->getHeight(), $maxHeight);
                }
            }
        }

        return ceil($maxHeight);
    }

    /**
     * Return a relative horizontal position of the zoom box depending on whether it is a quicklook popup, or not
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoomAdjustX()
    {
        return strpos($this->viewListName, 'quicklook')
            ? self::ZOOM_ADJUST_X_QL
            : self::ZOOM_ADJUST_X_PD;
    }

}
