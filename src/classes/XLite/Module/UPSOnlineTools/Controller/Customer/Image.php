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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Controller_Customer_Image extends XLite_Controller_Customer_Image implements XLite_Base_IDecorator
{
    function handleRequest()
    {
        if ($this->get('mode') == "ups_container_level_details") {
            if ($this->session->getID() != $this->get('id')) {
                // access denied
                exit();
            }

            $order = new XLite_Model_Order($this->get('order_id'));
            $containers = $order->get('ups_containers');

            $container_id = $this->get('container');
            $level_id = $this->get('level');

            if (!isset($containers[$container_id])) {
                // container not exists
                exit();
            }

            $container = $containers[$container_id];

            if (!isset($container['levels'][$level_id])) {
                // level not set 
                exit();
            }

            $level = $container['levels'][$level_id];

            require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
            $cont = UPSOnlineTools_displayLevel_gdlib($container['width'], $container['length'], $level['items'], $level['dirt_spaces'], $this->config->UPSOnlineTools->visual_container_width);

            header("Content-type: image/jpeg");
            echo $cont;

            exit();
        }

        return parent::handleRequest();
    }

}
