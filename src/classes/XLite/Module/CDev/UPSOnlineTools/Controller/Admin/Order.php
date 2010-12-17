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

namespace XLite\Module\CDev\UPSOnlineTools\Controller\Admin;

define('DISABLE_UPS_EDIT_ORDER', true);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Controller\Admin\Order implements \XLite\Base\IDecorator
{
    // visual coords for default box image "ups_box.gif"	
    public $_img_left = 57;
    public $_img_top = 17;
    public $_img_width = 82;
    public $_img_height = 86;

    function init()
    {
        parent::init();

        // Disable edit order if shipping method UPSOnlineTools
        if (DISABLE_UPS_EDIT_ORDER === true && $this->getComplex('order.shippingMethod.class') == "ups" && $this->xlite->get('AOMEnabled')) {
            unset($this->pages['order_edit']);
            unset($this->pageTemplates['order_edit']);
            if ($this->get('page') == "order_edit") {
                $this->redirect("admin.php?target=order&order_id=".$this->get('order_id')."&page=order_info");
                return;
            }
        }
    }

    function getTemplate()
    {
        if ($this->get('mode') == "container_details") {
            // container details
            return "modules/CDev/UPSOnlineTools/container_details.tpl";
        }

        return parent::getTemplate();
    }

    function getUPSContainers()
    {
        return $this->getComplex('order.ups_containers');
    }

    function countUPSContainers()
    {
        return count((array)$this->get('upscontainers'));
    }


    function hasUPSValidContainers()
    {
        $order = $this->get('order');
        if ($order->getComplex('shippingMethod.class') != "ups") {
            return false;
        }

        $containers = $this->get('upscontainers');
        return (is_array($containers) && count($containers) > 0) ? true : false;
    }


    function getUPSContainerName($container_type)
    {
        $sm = new \XLite\Module\CDev\UPSOnlineTools\Model\Shipping\Ups();
        $desc = $sm->getUPSContainerDims($container_type);
        return $desc['name'];
    }


    function getUPSOrderItems()
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

        $order_id = $this->get('order_id');
        $containers = $this->get('uPSContainers');

        $items_list = array();
        $extra_items = array();

        foreach ($containers as $container) {
            foreach ((array)$container['levels'] as $level) {
                foreach ((array)$level['items'] as $item) {
                    $global_id = $item['global_id'];
                    if (isset($items_list[$global_id]))
                        continue;

                    $items_list[$global_id] = $item;
                }
            }

            // extra items
            if (is_array($container['extra_item_ids'])) {
                foreach ($container['extra_item_ids'] as $item_id) {
                    $temp = array();
                    $temp['item_id'] = $item_id;
                    $temp['global_id'] = "-";

                    $extra_items[] = $temp;
                }
            }


        }

        // merge usual & extra items
        $items_list = array_merge($items_list, $extra_items);

        ksort($items_list);

        $export_fields = array('name', "weight", "ups_width", "ups_length", "ups_height", "ups_handle_care");
        foreach ($items_list as $k=>$item) {
            $oi = new \XLite\Model\OrderItem();

            if (!$oi->find("item_id='".$item['item_id']."' AND order_id='$order_id'"))
                continue;

            $items_list[$k]['amount'] = $oi->get('amount');
            $product = $oi->get('product');

            foreach ($export_fields as $field_name) {
                $items_list[$k][$field_name] = $product->get($field_name);
            }

            $items_list[$k]['weight_lbs'] = UPSOnlineTools_convertWeight($product->get('weight'), $this->config->General->weight_unit, "lbs", 2);
        }

        return array_values($items_list);
    }

    function countUPSOrderItems()
    {
        return count((array)$this->get('UPSOrderItems'));
    }

    function displayLevel($container_id, $level)
    {
        $level_id = $level['level_id'];

        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

        $order = $this->get('order');

        if (
            \XLite\Module\CDev\UPSOnlineTools\Main::isGDlibEnabled()
            && $this->config->CDev->UPSOnlineTools->display_gdlib
        ) {
            // GDlib
            return '<img src="cart.php?target=image&mode=ups_container_level_details&order_id='.$order->get('order_id').'&container='.$container_id.'&level='.$level_id.'&id='.$this->xlite->session->getID().'" border=2 alt="Container #'.($container_id + 1).', Layer #'.($level_id + 1).'">';
        }

        $containers = $order->get('ups_containers');

        if (!isset($containers[$container_id])) {
            return "<b>Resource not found!</b>";
        }

        $container = $containers[$container_id];

        if (!isset($container['levels'][$level_id])) {
            return "<b>Resource not found!</b>";
        }

        $level = $container['levels'][$level_id];


        // collect product ids
        $pids = array();
        foreach ($level['items'] as $item) {
            $pids[] = $item['item_id'];
        }
        $pids = array_unique($pids);

        // get products names by ids
        $names = array();
        foreach ($pids as $id) {
            $po = new \XLite\Model\Product($id);
            $names[$id] = $po->get('name');
        }

        // assign product name to title
        foreach ($level['items'] as $k=>$v) {
            $id = $v['item_id'];
            $level['items'][$k]['title'] = $names[$id];
        }

        // display container details based on <div>...</div>
        $result = UPSOnlineTools_displayLevel_div($container['width'], $container['length'], $level['items'], $level['dirt_spaces'], $this->config->CDev->UPSOnlineTools->visual_container_width, $level_id);

        return $result;
    }

    function displayContainer($container_id)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

        $order = $this->get('order');
        $containers = $order->get('ups_containers');

        if (!isset($containers[$container_id])) {
            return "<b>Resource not found!</b>";
        }

        $container = $containers[$container_id];

        return UPSOnlineTools_displayContainer_div($this, $container, $this->_img_left, $this->_img_top, $this->_img_width, $this->_img_height);
    }

    function UPSInc1($value)
    {
        return (intval($value) + 1);
    }
}
