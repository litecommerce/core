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
 * Product option modifier widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class XLite_Module_ProductOptions_View_ProductOptionModifier extends XLite_View_AView
{
    /**
     * Widget parameter names
     */

    const PARAM_OPTION       = 'option';
    const PARAM_OPTION_GROUP = 'optionGroup';
    const PARAM_PRODUCT      = 'product';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/product_option_modifier.tpl';
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
            self::PARAM_PRODUCT      => new XLite_Model_WidgetParam_Object('Product', null, false, 'XLite_Model_Product'),
            self::PARAM_OPTION       => new XLite_Model_WidgetParam_Object('Option', null, false, 'stdClass'),
            self::PARAM_OPTION_GROUP => new XLite_Model_WidgetParam_Object('Option group', null, false, 'XLite_Module_ProductOptions_Model_ProductOption'),
        );
    }

    /**
     * Check widget visibility 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getParam(self::PARAM_OPTION)->modifyParams;
    }

    /**
     * Check - show price or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShowPrice()
    {
        return $this->getParam(self::PARAM_PRODUCT)->isDisplayPriceModifier() && !$this->getParam(self::PARAM_OPTION)->isZero;
    }
}

