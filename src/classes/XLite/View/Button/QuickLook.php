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

namespace XLite\View\Button;

/**
 * QuickLook 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class QuickLook extends \XLite\View\Button\Regular
{
    /**
     * Widget param names
     */
    const PARAM_PRODUCT = 'product';


    /**
     * getProduct 
     * 
     * @return \XLite\Model\Product
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProduct()
    {
        return $this->getParam(self::PARAM_PRODUCT);
    }

    /**
     * getDefaultLabel
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultLabel()
    {
        return 'Quick look';
    }

    /**
     * getLink 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLink()
    {
        return $this->buildURL('quick_look', '', array('product_id' => $this->getProduct()->getProductId()));
    }

    /**
     * JavaScript: default JS code to execute
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultJSCode()
    {
        return 'return !openBlockUIPopupURL(\'' . $this->getLink() . '\')';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();
        
        $this->widgetParams += array(
            self::PARAM_PRODUCT     => new \XLite\Model\WidgetParam\Object(
                'Product', null, false, '\XLite\Model\Product'
            ),
        );  
    }
}
