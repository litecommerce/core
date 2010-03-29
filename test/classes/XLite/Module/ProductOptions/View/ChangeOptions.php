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
 * Change options widget
 *
 * @package    XLite
 * @subpackage View
 * @since      3.0
 */
class XLite_Module_ProductOptions_View_ChangeOptions extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_ITEM = 'item';

    /**
     * Product (cache)
     * 
     * @var    XLite_Model_Product
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $product = null;

    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('change_options');

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
            self::PARAM_ITEM    => new XLite_Model_WidgetParam_Object('Item', null, false, 'XLite_Model_OrderItem'),
         );

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('modules/ProductOptions/change_options.tpl');
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
        $result = parent::isVisible() && XLite::getController()->getItem();

        if ($result) {
            $this->widgetParams[self::PARAM_ITEM]->setValue(XLite::getController()->getItem());

            $result = $this->getParam(self::PARAM_ITEM)->hasOptions();
        }

        return $result;
    }

    /**
     * Get product 
     * 
     * @return XLite_Model_Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = $this->getParam(self::PARAM_ITEM)->getProduct();

            foreach ($this->product->getProductOptions() as $option) {
                foreach ($this->getParam(self::PARAM_ITEM)->getProductOptions() as $selected) {
                    if ($option->get('optclass') == $selected->class) {
                        if (
                            $option->get('opttype') == 'Radio button'
                            || $option->get('opttype') == 'SelectBox'
                        ) {
                            $option->set('selectedValue', $selected->option_id);

                        } else {
                            $option->set('selectedValue', $selected->option);
                        }
                    }
                }
            }
        }

        return $this->product;
    }
}

