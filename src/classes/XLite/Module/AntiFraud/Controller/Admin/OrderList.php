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

namespace XLite\Module\AntiFraud\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderList extends \XLite\Controller\Admin\OrderList implements \XLite\Base\IDecorator
{
        function init()
        {
            $this->params[] = "show_factor";
            $this->params[] = "risk_factor";
            if (!isset($this->risk_factor)) $this->risk_factor = $this->config->AntiFraud->antifraud_risk_factor;

            parent::init();
        }
        
        function getOrders() 
        {
            $orders = parent::getOrders();
            
            if (!is_null($orders)&&$this->show_factor) {
                foreach ($orders as $key => $order) {
                    if (!is_object($order)) {
                        $order = new \XLite\Model\Order($order['data']['order_id']);
                    }
                    if ($order->getComplex('details.af_result.total_trust_score') < $this->risk_factor)
                        unset($orders[$key]);
                }
            }
            return $orders;
        }
}
