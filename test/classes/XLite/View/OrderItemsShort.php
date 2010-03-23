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
 * Order items list (short version)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_OrderItemsShort extends XLite_View_Abstract
{
    /**
     *  Widget parameters names
     */
    const PARAM_ORDER    = 'order';
    const PARAM_ORDER_ID = 'order_id';
    const PARAM_FULL     = 'full';


    /**
     * Order items list maximunm length 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $orderItemsMax = 3;

    /**
     * Order (cache)
     * 
     * @var    XLite_Model_Order
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $order = null;

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
            self::PARAM_ORDER    => new XLite_Model_WidgetParam_Object(
                'Order', null, false, 'XLite_Model_Order'
            ),
            self::PARAM_ORDER_ID => new XLite_Model_WidgetParam_Int(
                'Order id', null, false
            ),
            self::PARAM_FULL => new XLite_Model_WidgetParam_Bool(
                'Display full list', false, false
            ),
        );

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('order/list/items.tpl');
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
            && $this->getOrder();
    }

    /**
     * Get order 
     * 
     * @return XLite_Model_Order
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrder()
    {
        if (is_null($this->order)) {

            $this->order = false;

            if ($this->getParam(self::PARAM_ORDER) instanceof XLite_Model_Order) {

                // order based
                $this->order = $this->getParam(self::PARAM_ORDER);

            } elseif (0 < $this->getRequestParamValue(self::PARAM_ORDER_ID)) {

                // order id based
                $order = new XLite_Model_Order($this->getRequestParamValue(self::PARAM_ORDER_ID));
                if ($order->isExists()) {
                    $this->order = $order;
                }
            }
        }

        return $this->order;
    }

    /**
     * Get order id 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrderId()
    {
        return $this->getOrder()->get('order_id');
    }

    /**
     * Get order items 
     * 
     * @return array of XLite_Model_OrderItem
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItems()
    {
        return $this->getRequestParamValue(self::PARAM_FULL)
            ? $this->getOrder()->getItems()
            : array_slice($this->getOrder()->getItems(), 0, $this->orderItemsMax);
    }

    /**
     * Check - link to full items list is visible or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isMoreLinkVisible()
    {
        return $this->orderItemsMax < $this->getOrder()->getItemsCount();
    }

    /**
     * Get list to full items list class name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMoreLinkClassName()
    {
        return $this->getRequestParamValue(self::PARAM_FULL) ? 'open' : 'close';
    }
}

