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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ShippingRates extends \XLite\Controller\Admin\AAdmin
{
    public $params = array('target', 'shipping_zone_range', 'shipping_id_range');
    
    public $shipping_id_range = "";
    public $shipping_zone_range = "";

    function getPageTemplate()
    {
        return "shipping/charges.tpl";
    }

    /**
     * getShippingZones 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingZones()
    {
        if (!isset($this->zones)) {
    
            $this->zones = \XLite\Core\Database::getRepo('XLite\Model\Zone')->getZones();

            $defaultZone = new \XLite\Model\Zone();
            $defaultZone->setZoneName('Default zone');

            array_unshift($this->zones, $defaultZone);
        }

        return $this->zones;
    }

    function getShippingRates()
    {
        // read select condition from the request
        $condition = array();
        if (isset(\XLite\Core\Request::getInstance()->shipping_zone_range) && strlen(\XLite\Core\Request::getInstance()->shipping_zone_range) > 0) {
            $this->shipping_zone_range = \XLite\Core\Request::getInstance()->shipping_zone_range;
            $condition[] = "shipping_zone='$this->shipping_zone_range'";
        }
        if (!empty(\XLite\Core\Request::getInstance()->shipping_id_range)) {
            $this->shipping_id_range = \XLite\Core\Request::getInstance()->shipping_id_range;
            $condition[] = "shipping_id='$this->shipping_id_range'";
        }
        $condition = implode(' AND ', $condition);
        $sr = new \XLite\Model\ShippingRate();
        $shipping_rates = $sr->findAll($condition);
        $shipping = new \XLite\Model\Shipping();
    	$modules = $shipping->getModules();
    	$modules = (is_array($modules)) ? array_keys($modules) : array();
        $shippings = $shipping->findAll();
        $validShippings = array("-1");
        foreach ($shippings as $shipping) {
            if (in_array($shipping->get('class'), $modules) && $shipping->get('enabled')) {
                $validShippings[] = $shipping->get('shipping_id');
            }
        }

        // assign numbers
        $i = 1;
        $excluded_shipping_rates = array();
        foreach ($shipping_rates as $key => $val) {
            $shipping_rates[$key]->pos = $i++;
            if (!in_array($val->get('shipping_id'), $validShippings)) {
            	$excluded_shipping_rates[$key] = true;
            }
        }
        foreach ($excluded_shipping_rates as $key => $val) {
        	unset($shipping_rates[$key]);
        }

        return $shipping_rates;
    }

    function action_add()
    {
        $this->params[] = "message";
        $rate = new \XLite\Model\ShippingRate();
        $rate->set('properties', \XLite\Core\Request::getInstance()->getData());
        if (!$rate->isExists()) {
        	$this->set('message', "added");
        	$rate->create();
        } else {
        	$this->set('message', "add_failed");
        }
    }

    function action_update()
    {
        $shippingRates = $this->get('shippingRates');
        foreach (\XLite\Core\Request::getInstance()->rate as $key => $rate_data) {
            if (array_key_exists($key, $shippingRates)) {
                $rate = new \XLite\Model\ShippingRate();
                $rate->set('properties', $rate_data);
                if ($rate->isExists()) {
                    $rate->update();
                } else {
                    $rate = $shippingRates[$key];
    	            $rate->delete();
        	        $rate->set('properties', $rate_data);
            	    $rate->create();
                }
            }
        }
    }

    function action_delete()
    {
        $shippingRates = $this->get('shippingRates');
        $rate = $shippingRates[\XLite\Core\Request::getInstance()->deleted_rate];
        $rate->delete();
    }
}
