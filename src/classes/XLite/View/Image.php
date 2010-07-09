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

/**
 * Image
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Image extends XLite_View_AView
{
    /**
     * Widget arguments names 
     */
    const PARAM_IMAGE             = 'image';
    const PARAM_ALT               = 'alt';
    const PARAM_MAX_WIDTH         = 'maxWidth';
    const PARAM_MAX_HEIGHT        = 'maxHeight';
    const PARAM_CENTER_IMAGE      = 'centerImage';
    const PARAM_USE_CACHE         = 'useCache';
    const PARAM_USE_DEFAULT_IMAGE = 'useDefaultImage';


    /**
     * Allowed properties names
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $allowedProperties = array(
        'className'   => 'class',
        'id'          => 'id',
        'onclick'     => 'onclick',
        'style'       => 'style',
        'onmousemove' => 'onmousemove',
        'onmouseup'   => 'onmouseup',
        'onmousedown' => 'onmousedown',
        'onmouseover' => 'onmouseover',
        'onmouseout'  => 'onmouseout',
    );

    /**
     * Additioanl properties 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $properties = array();

    /**
     * Resized thumbnail URL 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $resizedURL = null;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/image.tpl';
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
            self::PARAM_IMAGE             => new XLite_Model_WidgetParam_Object('Image', null, false, 'XLite_Model_Image'),
            self::PARAM_ALT               => new XLite_Model_WidgetParam_String('Alt. text', '', false),
            self::PARAM_MAX_WIDTH         => new XLite_Model_WidgetParam_Int('Max. width', 0),
            self::PARAM_MAX_HEIGHT        => new XLite_Model_WidgetParam_Int('Max. height', 0),
            self::PARAM_CENTER_IMAGE      => new XLite_Model_WidgetParam_Checkbox('Center the image after resizing', true),
            self::PARAM_USE_CACHE         => new XLite_Model_WidgetParam_Bool('Use cache', 1),
            self::PARAM_USE_DEFAULT_IMAGE => new XLite_Model_WidgetParam_Bool('Use default image', 1),
        );
    }

    /**
     * checkImage 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkImage()
    {
        return $this->getParam(self::PARAM_IMAGE) 
            && $this->getParam(self::PARAM_IMAGE)->isExists();
    }

    /**
     * checkDefaultImage 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkDefaultImage()
    {
        return $this->getParam(self::PARAM_USE_DEFAULT_IMAGE) 
            && XLite::getInstance()->getOptions(array('images', 'default_image'));
    }

    /**
     * Set widget parameters
     *
     * @param array $params Widget parameters
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        // Save additional parameters
        foreach ($params as $name => $value) {
            if (isset($this->allowedProperties[$name])) {
                $this->properties[$this->allowedProperties[$name]] = $value;
            }
        }
    }

    /**
     * Check widget visibility
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        $result = parent::isVisible();

        if ($result) {

            if ($this->checkImage()) {
                $this->processImage();

            } elseif ($this->checkDefaultImage()) {
                $this->processDefaultImage();

            } else {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Get image URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURL()
    {
        $url = false;

        if ($this->getParam(self::PARAM_IMAGE) && $this->getParam(self::PARAM_IMAGE)->isExists()) {

            // Specified image

            $url = $this->getParam(self::PARAM_USE_CACHE)
                ? $this->resizedURL
                : $this->getParam(self::PARAM_IMAGE)->getURL();

        } elseif ($this->getParam(self::PARAM_USE_DEFAULT_IMAGE)) {

            // Defualt image

            $url = XLite::getInstance()->getOptions(array('images', 'default_image'));

            if (!XLite_Core_Converter::isURL($url)) {
                $url = XLite::getInstance()->getShopUrl(
                    XLite_Model_Layout::getInstance()->getSkinURL($url)
                );
            }
        }

        return $url;
    }

    /**
     * Get image alternative text
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAlt()
    {
        return $this->getParam(self::PARAM_ALT);
    }

    /**
     * Get properties 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Return a CSS style centering the image vertically and horizontally
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setImagePaddings()
    {
        $vertical = ($this->getParam(self::PARAM_MAX_HEIGHT) - $this->properties['height']) / 2;
        $horizontal = ($this->getParam(self::PARAM_MAX_WIDTH) - $this->properties['width']) / 2;

        $top    = max(0, ceil($vertical));
        $bottom = max(0, floor($vertical));
        $left   = max(0, ceil($horizontal));
        $right  = max(0, floor($horizontal));

        if (0 < $top || 0 < $bottom || 0 < $left || 0 < $right) {
            $this->addInlineStyle(
                'padding: ' . $top . 'px ' . $right . 'px ' . $bottom . 'px ' . $left . 'px;'
            );
        }
    }

    /**
     * Add CSS styles to the value of "style" attribute of the image tag
     * 
     * @param string $style CSS styles to be added to the end of "style" attribute
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addInlineStyle($style)
    {
        if (!isset($this->properties['style'])) {
            $this->properties['style'] = $style;

        } else {
            $this->properties['style'] .= ' ' . $style;
        }
    }

    /**
     * Preprocess image 
     * TODO: replace getResizedThumbnailURL to getResizedURL
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processImage()
    {
        $maxw = max(0, $this->getParam(self::PARAM_MAX_WIDTH));
        $maxh = max(0, $this->getParam(self::PARAM_MAX_HEIGHT));

        $funcName = method_exists($this->getParam(self::PARAM_IMAGE), 'getResizedURL') ? 'getResizedURL' : 'getResizedThumbnailURL';

        list(
            $this->properties['width'],
            $this->properties['height'],
            $this->resizedURL
        ) = $this->getParam(self::PARAM_IMAGE)->$funcName($maxw, $maxh);

        // Center the image vertically and horizontally
        if ($this->getParam(self::PARAM_CENTER_IMAGE)) {
            $this->setImagePaddings();
        }
    }

    /**
     * Preprocess default image 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processDefaultImage()
    {
        list($this->properties['width'], $this->properties['height']) = XLite_Core_Converter::getCroppedDimensions(
            XLite::getInstance()->getOptions(array('images', 'default_image_width')),
            XLite::getInstance()->getOptions(array('images', 'default_image_height')),
            max(0, $this->getParam(self::PARAM_MAX_WIDTH)),
            max(0, $this->getParam(self::PARAM_MAX_HEIGHT))
        );

        // Center the image vertically and horizontally
        if ($this->getParam(self::PARAM_CENTER_IMAGE)) {
            $this->setImagePaddings();
        }
    }

}
