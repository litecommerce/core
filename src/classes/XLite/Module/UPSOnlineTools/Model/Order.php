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

require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

/**
 * Order
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
    const PACKAGING_TYPE_NONE = 0;
    const PACKAGING_TYPE_PACKAGE = 2;

    // 0 - Fixed size
    const BINPACKING_SIMPLE_FIXED_SIZE = 0;

    // 1 - Max size
    const BINPACKING_SIMPLE_MAX_SIZE = 1;

    // 2 - Bin Packing
    const BINPACKING_NORMAL_ALGORITHM = 2;

    // 3 - Bin Packing oversize
    const BINPACKING_OVERSIZE_ALGORITHM = 3;

    public $_ups_containers = null;

    /**
     * Constructor
     * 
     * @param mixed $id Unique id
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->fields['ups_containers'] = base64_encode(serialize(array()));
    }

    /**
     * Assign first shipping rate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assignFirstShippingRate()
    {
        $rates = $this->getCarrierRates();

        $shipping = null;
        if (0 < count($rates)) {
            reset($rates);
            $rate = array_shift($rates);
            $shipping = $rate->get('shipping');
        }

        $this->setShippingMethod($shipping);
    }

    /**
     * Get current carrier 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCarrier()
    {
        if (is_null($this->_carrier)) {

            $carriers = $this->getCarriers();

            if ($this->get('shipping_id')) {
                $sm = new XLite_Model_Shipping();

                // return NULL if shipping method not available
                if (!$sm->find("shipping_id = '" . $this->get('shipping_id') . "' AND enabled = '1'")) {
                    $this->_carrier = null;
                    parent::assignFirstShippingRate();

                } else {

                    $sm = XLite_Model_Shipping::getInstanceByName($sm->get('class'), $this->get('shipping_id'));

                    $this->_carrier = $sm->get('class');
                }

            } else {

                $this->_carrier = 1 < count($carriers)
                    ? $this->getComplex('shippingMethod.class')
                    : '';
            }
        }

        return is_null($this->_carrier) ? '' : $this->_carrier;
    }

    /**
     * Get carriers 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCarriers()
    {
        if (!isset($this->_carriers)) {

            $return = array();
            $rates = $this->getShippingRates();
            foreach ($rates as $rate) {
                $class = $rate->getComplex('shipping.class');
                if (!isset($return[$class])) {
                    $return[$class] = $rate->getComplex('shipping.carrier');
                }
            }

            $this->_carriers = array();
            if (1 < count($return)) {
                $this->_carriers = $return;
            }

        }

        return $this->_carriers;
    }

    /**
     * Get shipping rates by carrier 
     * 
     * @param string $carrier Carrier
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCarrierRates($carrier = null)
    {
        $rates = $this->getShippingRates();

        if (is_null($carrier)) {
            $carrier = $this->getCarrier();
        }

        if ($carrier && is_array($rates)) {
            foreach ($rates as $k => $rate) {
                if ($carrier != $rate->getComplex('shipping.class')) {
                    unset($rates[$k]);
                }
            }
        }

        return $rates;
    }

    /**
     * Shipping rates sorting callback 
     * 
     * @param XLite_Model_ShippingRate $a First shipping rate
     * @param XLite_Model_ShippingRate $b Second shipping rate
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingRatesOrderCallback(XLite_Model_ShippingRate $a, XLite_Model_ShippingRate $b)
    {
        $class_a = $a->getComplex('shipping.class');
        $class_b = $b->getComplex('shipping.class');

        if ($class_a == 'ups' && $class_b != 'ups') {
            $result = -1;

        } elseif ($class_b == 'ups' && $class_a != 'ups') {
            $result = 1;

        } else {
            $result = parent::getShippingRatesOrderCallback($a, $b);
        }

        return $result;
    }

    /**
     * Setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($name, $value)
    {
        if ('ups_containers' == $name) {
            $value = base64_encode(serialize((array)$value));
        }

        parent::set($name, $value);
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        $value = parent::get($name);

        if ('ups_containers' == $name) {
            $value = unserialize(base64_decode($value));
            if (!is_array($value)) {
                $value = array();
            }
        }

        return $value;
    }

    /**
     * Get UPS containers data fingerprint 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getUPSContainersFingerprint()
    {
        return md5(parent::get('ups_containers'));
    }

    function ups_online_tools_getItemsFingerprint()
    {
        if ($this->isEmpty()) {
            return false;
        }

        $result = array();
        $items = $this->get('items');
        foreach ($items as $item_idx => $item) {
            $result[] = array
            (
                $item_idx,
                $item->get('key'),
                $item->get('amount')
            );
        }

        return md5(serialize($result));
    }

    function getPackItems()
    {
        $items = array();
        $global_id = 1;
        foreach ((array)$this->get('items') as $item) {
            $obj = $item->getPackItem();
            $obj->GlobalId = $global_id;
            $items = array_merge($items, array_fill(0, $item->get('amount'), $obj));
            $global_id++;
        }

        return $items;
    }

    function packOrderItems(&$failed_items)
    {
        $containers = array();

        // build list of all used packaging
        $packaging_ids = array($this->xlite->config->UPSOnlineTools->packaging_type);
        foreach ((array)$this->get('items') as $item) {
            $packaging_ids[] = $item->getComplex('product.ups_packaging');
        }
        $packaging_ids = array_unique($packaging_ids);

        // process order items
        $items = $this->getPackItems();

        $itemsProcess = array();
        $itemsSkip = array();
        $itemsFailed = array();

        $packing_algorithm = $this->xlite->config->UPSOnlineTools->packing_algorithm;

        // prevent execution timeout.
        if (count($items) > $this->xlite->config->UPSOnlineTools->packing_limit) {
            $packing_algorithm = self::BINPACKING_SIMPLE_MAX_SIZE;
        }

        $is_single_container = false;
        if (in_array($packing_algorithm, array(self::BINPACKING_SIMPLE_FIXED_SIZE, self::BINPACKING_SIMPLE_MAX_SIZE))) {
            $is_single_container = true;
        }

        // Step #1:
        // try to pack all item in product-defined containers
        foreach ($packaging_ids as $packaging_id) {
            $itemsProcess = array();
            foreach ($items as $item) {
                $packaging = $item->get('packaging');

                if ($packaging == self::PACKAGING_TYPE_NONE) {
                    $packaging = $this->xlite->config->UPSOnlineTools->packaging_type;
                }
                if ($packaging == $packaging_id || $is_single_container) {
                    $itemsProceed[] = $item;
                } else {
                    $itemsSkip[] = $item;
                }
            }

            $items = $itemsSkip;
            $itemsSkip = array();

            if (is_array($itemsProceed) && count($itemsProceed) > 0) {
                $result = $this->_packOrderItems($itemsProceed, $packing_algorithm, $packaging_id);
                $itemsFailed = array_merge($itemsFailed, $itemsProceed);
                if (is_array($result) && count($result) > 0) {
                    $containers = array_merge($containers, $result);
                }

                $itemsProceed = array();
            }
        }

        $items = $itemsFailed;
        $itemsFailed = array();

        // Step #2
        // We have unpacked items,
        // try to pack with UPS module params
        if (is_array($items) && count($items) > 0) {
            $result = $this->_packOrderItems($items, null, null);
            if (is_array($result) && count($result) > 0) {
                $containers = array_merge($containers, $result);
            }
        }

        // Step #3
        // We still have items.
        // Try to put them in container with max-size Packing algorithm.
        if (is_array($items) && count($items) > 0) {
            $result = $this->_packOrderItems($items, self::BINPACKING_SIMPLE_MAX_SIZE, self::PACKAGING_TYPE_PACKAGE);
            if (is_array($result) && count($result) > 0) {
                $containers = array_merge($containers, $result);
            }
        }

        $ups_containers = "";
        if (count($items) <= 0) {
            // All items packed in containers
            $ups_containers = (array) $this->prepareUpsContainers($containers);
        } else {
            // Failed to pack some items
            $ups_containers = base64_encode(serialize(array()));
            $failed_items = $items;
        }

        $this->set('ups_containers', $ups_containers);

        if (!$this->xlite->get('PromotionEnabled')) {
            $this->update();
        }

        return $containers;
    }

    function prepareUpsContainers($containers)
    {
        $export_data = array();
        foreach ((array)$containers as $container) {
            $export_data[] = $container->export();
        }

        $container_index = 1;
        foreach ((array)$export_data as $conId=>$con) {
            $export_data[$conId]['container_id'] = $container_index++;
        }
        return $export_data;
    }

    function _packOrderItems(&$items, $ptype=null, $packaging_type=null, $extra=array())
    {
        $ups_containers = array();

        if (is_null($ptype)) {
            $ptype = $this->xlite->config->UPSOnlineTools->packing_algorithm;
        }

        if (is_null($packaging_type)) {
            $packaging_type = $this->xlite->config->UPSOnlineTools->packaging_type;
        }

        $total_weight = 0;

        $is_additional_handling = false;
        $declared_value = 0;

        foreach ($items as $item) {
            $declared_value += $item->declaredValue;
            $total_weight += $item->weight;
        }

        // process with containers...
        switch ($ptype) {
            case self::BINPACKING_SIMPLE_FIXED_SIZE:
            case self::BINPACKING_SIMPLE_MAX_SIZE:
            default:

                if ($ptype == self::BINPACKING_SIMPLE_MAX_SIZE) {
                    // Max size
                    $_width = 0;
                    $_length = 0;
                    $_height = 0;

                    foreach ($items as $item) {
                        $_width = max($_width, $item->width);
                        $_length = max($_length, $item->length);
                        $_height = max($_height, $item->height);
                    }
                } else {
                    // fixed-size container or unknown
                    $_width = $this->xlite->config->UPSOnlineTools->width;
                    $_length = $this->xlite->config->UPSOnlineTools->length;
                    $_height = $this->xlite->config->UPSOnlineTools->height;
                }

                $weight_limit = 150; // lbs

                $container = new XLite_Module_UPSOnlineTools_Model_Container();
                $container->setDimensions($_width, $_length, $_height);
                $container->setWeightLimit($weight_limit);
                $container->setContainerType(self::PACKAGING_TYPE_PACKAGE); // Package type

                $ups_containers[] = $container;

                // pack items in containers
                $itemsCount = count($items);
                for ($iid = 0; $iid < $itemsCount;) {

                    $item = $items[$iid];
                    $item_weight = $item->weight;

                    if ($item_weight > $weight_limit)
                        return false;

                    $continue = false;
                    foreach ($ups_containers as $i=>$cont) {
                        $c_weight = $cont->getWeight();
                        $declared_value = $cont->getDeclaredValue();

                        if ($c_weight + $item_weight <= $weight_limit) {
                            $ups_containers[$i]->addExtraItemIds($item->OrderItemId);
                            $ups_containers[$i]->setWeight($c_weight + $item_weight);
                            $ups_containers[$i]->setDeclaredValue($declared_value + $item->declaredValue);

                            if ($item->additional_handling) {
                                $ups_containers[$i]->setAdditionalHandling(true);
                            }

                            $iid++;
                            $continue = true;

                            break;
                        }
                    }

                    // pack next item
                    if ($continue)
                        continue;

                    // add new container
                    $c = new XLite_Module_UPSOnlineTools_Model_Container();
                    $c->setDimensions($_width, $_length, $_height);
                    $c->setWeightLimit($weight_limit);
                    $c->setContainerType(self::PACKAGING_TYPE_PACKAGE); // Package type
                    $ups_containers[] = $c;
                }

                $items = array();
            break;
            ////////////////////////////////////////////////////////
            case self::BINPACKING_NORMAL_ALGORITHM:    // pack all items in one package
                $sm = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();
                $pack = $sm->getUPSContainerDims($packaging_type);

                $const_items = $items;
                $ups_containers = UPSOnlineTools_solve_binpack($pack['width'], $pack['length'], $pack['height'], $pack['weight_limit'], $items);

                // if can't place all items in defined container - fit container size
                if ($ups_containers === false || count($ups_containers) != 1 || count($items) > 0) {
                    $summ = 0;
                    foreach ($const_items as $item) {
                        $summ += $item->width;
                        $summ += $item->length;
                        $summ += $item->height;
                    }

                    // calc average container size
                    $medium_width = ceil($summ / (max(1, count($const_items)) * 3));
                    $inc_width = $medium_width * 0.1;

                    // iterate while all items will pack in single container
                    $fuse = 35;
                    do {
                        $items = $const_items;
                        $ups_containers = UPSOnlineTools_solve_binpack($medium_width, $medium_width, $medium_width, 0, $items);
                        $medium_width += $inc_width;

                        // return with error after N=35 tries.
                        if ($fuse-- <= 0) {
                            return false;
                        }

                        // increase incremental step on each iteration
                        $inc_width += $inc_width * 0.1;
                    } while ($ups_containers === false || count($ups_containers) > 1 || count($items) > 0);

                    $packaging_type = self::PACKAGING_TYPE_PACKAGE;    // Package type 
                }

                foreach ($ups_containers as $k=>$v) {
                    $ups_containers[$k]->setContainerType($packaging_type);
                }
            break;
            ////////////////////////////////////////////////////////
            case self::BINPACKING_OVERSIZE_ALGORITHM:    // pack items in similar containers
                $sm = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();
                $pack = $sm->getUPSContainerDims($packaging_type);

                $ups_containers = UPSOnlineTools_solve_binpack($pack['width'], $pack['length'], $pack['height'], $pack['weight_limit'], $items);

                if ($ups_containers === false/* || count($items) > 0*/) {
                    // return "oversized" items
                    return false;
                }

                foreach ($ups_containers as $k=>$v) {
                    $ups_containers[$k]->setContainerType($packaging_type);
                }
            break;
        }

        if (!is_array($ups_containers) || count($ups_containers) <= 0) {
            return false;
        }


// TODO: ..............
        // Analyze containers for AdditionalHandling condition(s)
        if ($ptype == self::BINPACKING_NORMAL_ALGORITHM || $ptype == self::BINPACKING_OVERSIZE_ALGORITHM) {
            foreach ($ups_containers as $container_id=>$container) {
                $found = false;

                foreach ((array)$container->getLevels() as $level) {
                    foreach ((array)$level->getItems() as $item) {
                        $item_id = $item->get('item_id');

                        $oi = new XLite_Model_OrderItem();
                        if ($oi->find("item_id='".addslashes($item_id)."'")) {
                            if ($oi->getComplex('product.ups_add_handling')) {
                                $ups_containers[$container_id]->setAdditionalHandling(true);
                                $found = true;
                                break;
                            }
                        }
                    }

                    if ($found) {
                        break;
                    }
                }
            }
        }

        return $ups_containers;
    }
}
