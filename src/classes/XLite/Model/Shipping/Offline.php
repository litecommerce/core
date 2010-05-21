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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Offline shipping method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Shipping_Offline extends XLite_Model_Shipping
{
    /**
     * Get module name 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleName()
    {
        return "Manually defined shipping methods";
    }

    /**
     * Build find rates SQL query 
     * 
     * @param string            $sql   Initial query
     * @param XLite_Model_Order $order Order
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function _buildRatesSql($sql, XLite_Model_Order $order)
    {
        return $sql;
    }

    /**
     * Get rates 
     * 
     * @param XLite_Model_Order $order Order
     *  
     * @return array of XLite_Model_ShippingRate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRates(XLite_Model_Order $order)
    {
        $shop_country = $this->config->Company->location_country;

        $rates = array();

        if ((!is_null($order->getProfile()) || $this->config->General->def_calc_shippings_taxes)) {

            $dest_country = is_null($order->getProfile())
                ? $this->config->General->default_country
                : $order->getProfile()->get('shipping_country');

            // select all national/international shipping methods
            $dest = $dest_country == $shop_country ? 'L' : 'I';

            $sql = 'destination = \'' . $dest . '\' AND enabled = 1 AND class = \'Offline\'';

            // join with rates table
            foreach ($this->findAll($this->_buildRatesSql($sql, $order)) as $method) {
                
                $rate = $this->getRate(
                    $order,
                    self::getInstanceByName($method->get('class'), $method->get('shipping_id'))
                );

                if (isset($rate)) {
                    $rates[$method->get('shipping_id')] = $rate;
                }
            }

            // TODO: sort by rate
        }

        return $rates;
    }

    /**
     * Build get rate SQL query
     * 
     * @param string               $sql    Initiual SQL query
     * @param XLite_Model_Order    $order  Order
     * @param XLite_Model_Shipping $method Shipping method
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function _buildRateSql($sql, XLite_Model_Order $order, XLite_Model_Shipping $method)
    {
        return $sql;
    }

    /**
     * Get rate 
     * 
     * @param XLite_Model_Order    $order  Order
     * @param XLite_Model_Shipping $method Shipping method
     *  
     * @return XLite_Model_ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRate(XLite_Model_Order $order, XLite_Model_Shipping $method)
    {
        $shipping_id = $method->get('shipping_id');
        $weight = doubleval($order->get('weight'));
        $total = doubleval($order->calcSubTotal(true)); // SubTotal for "shipped only" items

        $r = new XLite_Model_ShippingRate();
        $zone = $this->getZone($order);
        $items = $order->get('shippedItemsCount');
        $sql = "(shipping_id=-1 OR shipping_id='$shipping_id') AND (shipping_zone=-1 OR shipping_zone='$zone') AND min_weight<=$weight AND max_weight>=$weight AND min_total<=$total AND min_items<=$items AND max_items>=$items AND max_total>$total";

        if (!$r->find($this->_buildRateSql($sql, $order, $method), "shipping_id DESC, shipping_zone DESC")) {

            $r = null;

        } else {

            $r->rate = doubleval($r->get('flat'))
                + doubleval($r->get('per_item')) * $items
                + doubleval($r->get('percent')) * $total / 100
                + doubleval($r->get('per_lbs')) * $weight;
            $r->shipping = $method;

        }

        return $r;
    }
}
