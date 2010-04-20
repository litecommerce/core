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
class XLite_View_Image extends XLite_View_Abstract
{
    /**
     * Widget arguments names 
     */
    const PARAM_IMAGE        = 'image';
    const PARAM_ALT          = 'alt';
    const PARAM_MAX_WIDTH    = 'maxWidth';
    const PARAM_MAX_HEIGHT   = 'maxHeight';
    const PARAM_CENTER_IMAGE = 'centerImage';


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
            self::PARAM_IMAGE        => new XLite_Model_WidgetParam_Object('Image', null, false, 'XLite_Model_Image'),
            self::PARAM_ALT          => new XLite_Model_WidgetParam_String('Alt. text', '', false),
            self::PARAM_MAX_WIDTH    => new XLite_Model_WidgetParam_Int('Max. width', 0),
            self::PARAM_MAX_HEIGHT   => new XLite_Model_WidgetParam_Int('Max. height', 0),
            self::PARAM_CENTER_IMAGE => new XLite_Model_WidgetParam_Checkbox('Center the image after resizing', true),
        );
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

        // Calculate new image dimensions
        if (isset($params[self::PARAM_IMAGE])) {
            $this->calculateDimensions();

            // Center the image vertically and horizontally
            if ($this->getParam(self::PARAM_CENTER_IMAGE)) {
                $this->centerImage();
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
        return parent::isVisible()
            && $this->getParam(self::PARAM_IMAGE);
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
        return $this->getParam(self::PARAM_IMAGE)->getURL();
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
     * Calculate image dimensions 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateDimensions()
    {
        $w = $this->getParam(self::PARAM_IMAGE)->get('width');
        $h = $this->getParam(self::PARAM_IMAGE)->get('height');

        $maxw = max(0, $this->getParam(self::PARAM_MAX_WIDTH));
        $maxh = max(0, $this->getParam(self::PARAM_MAX_HEIGHT));

        $this->properties['width'] = 0 < $w ? $w : $maxw;
        $this->properties['height'] = 0 < $h ? $h : $maxh;

        if (0 < $w && 0 < $h && (0 < $maxw || 0 < $maxh)) {

            if (0 < $maxw && 0 < $maxh) {
                $kw = $w > $maxw ? $maxw / $w : 1;
                $kh = $h > $maxh ? $maxh / $h : 1;
                $k = $kw < $kh ? $kw : $kh;

            } elseif (0 < $maxw) {
                $k = $w > $maxw ? $maxw / $w : 1;

            } elseif (0 < $maxh) {
                $k = $h > $maxh ? $maxh / $h : 1;

            }

            $this->properties['width'] = max(1, round($k * $w, 0));
            $this->properties['height'] = max(1, round($k * $h, 0));
        }

        if (0 == $this->properties['width']) {
            unset($this->properties['width']);
        }

        if (0 == $this->properties['height']) {
            unset($this->properties['height']);
        }
    }

    /**
     * Return a CSS style centering the image vertically and horizontally
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function centerImage()
    {
        $vertical = ($this->getParam(self::PARAM_MAX_HEIGHT) - $this->properties['height']) / 2;
        $horizontal = ($this->getParam(self::PARAM_MAX_WIDTH) - $this->properties['width']) / 2;

        $top = ceil($vertical);
        $bottom = floor($vertical);
        $left = ceil($horizontal);
        $right = floor($horizontal);

        $this->addInlineStyle('padding: '.$top.'px '.$right.'px '.$bottom.'px '.$left.'px;');
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
        if (!isset($this->properties['style']))
            $this->properties['style'] = '';

        $this->properties['style'] .= $style;
    }

}
