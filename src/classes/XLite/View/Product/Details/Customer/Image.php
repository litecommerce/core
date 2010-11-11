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
    const PARAM_ZOOM_MAX_WIDTH = 'zoomMaxWidth';

    /**
     * Zoom coefficient
     */
    const PARAM_K_ZOOM = 'kZoom';

    /**
     * Image max width on product details page and Quick Look box
     */
    const PARAM_IMG_MAX_WIDTH_PD = 'imgMaxWidthPD';
    const PARAM_IMG_MAX_WIDTH_QL = 'imgMaxWidthQL';

    /**
     * Relative horizontal position of the zoom box
     */
    const PARAM_ZOOM_ADJUST_X_PD = 'zoomAdjustXPD';
    const PARAM_ZOOM_ADJUST_X_QL = 'zoomAdjustXQL';

    /**
     * Product has any image to ZOOM
     *
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $isZoom = null;

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
        return ($this->hasZoomImage())
            ? $this->getDir() . '/parts/image-zoom.tpl'
            : $this->getDefaultTemplate();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ZOOM_MAX_WIDTH   => new \XLite\Model\WidgetParam\Int('Cloud zoom layer maximum width, px', 460),
            self::PARAM_K_ZOOM           => new \XLite\Model\WidgetParam\Float('Minimal zoom coefficient', 1.3),
            self::PARAM_IMG_MAX_WIDTH_PD => new \XLite\Model\WidgetParam\Int('Image max width on the Product details page', 330),
            self::PARAM_IMG_MAX_WIDTH_QL => new \XLite\Model\WidgetParam\Int('Image max width in the Quick Look box', 300),
            self::PARAM_ZOOM_ADJUST_X_PD => new \XLite\Model\WidgetParam\Int('Relative horizontal position of the zoom box on the Product details page', 97),
            self::PARAM_ZOOM_ADJUST_X_QL => new \XLite\Model\WidgetParam\Int('Relative horizontal position of the zoom box in the Quick look box', 32),
        );
    }

    /**
     * Check if the product has any image to ZOOM
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function hasZoomImage()
    {
        if (is_null($this->isZoom)) {
            
            $this->isZoom = false;

            if ($this->getProduct()->hasImage()) {

                foreach($this->getProduct()->getImages() as $img) {
   
                    if ($img->getWidth() > $this->getParam('kZoom') * $this->getWidgetMaxWidth()) {
                        $this->isZoom= true;
                        break;
                    } 
                }
            }
        }
        
        return $this->isZoom;
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
        return min($this->getProduct()->getImage()->getWidth(), $this->getParam('zoomMaxWidth'));
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
            ? $this->getParam('imgMaxWidthQL')
            : $this->getParam('imgMaxWidthPD');
    }

    /**
     * Get product image container max height
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
     * Return a relative horizontal position of the zoom box 
     * depending on whether it is a quicklook popup, or not
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoomAdjustX()
    {
        return strpos($this->viewListName, 'quicklook')
            ? $this->getParam('zoomAdjustXQL')
            : $this->getParam('zoomAdjustXPD');
    }

}
