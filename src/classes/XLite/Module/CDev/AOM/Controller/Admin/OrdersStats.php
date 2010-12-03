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

namespace XLite\Module\CDev\AOM\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrdersStats extends \XLite\Controller\Admin\OrdersStats implements \XLite\Base\IDecorator
{
    function getPageTemplate()
    {
        return "modules/CDev/AOM/orders_stats.tpl";
    }
    
    function getStats()
    {
        // typedef
        $statRec = array("today" => 0, "week" => 0, "month" => 0);
        $orderStatus = new \XLite\Module\CDev\AOM\Model\OrderStatus();
        $orderStatuses = $orderStatus->findAll();
        $orderStatusesHash = array();
        foreach ($orderStatuses as $orderStatus_) {
            $orderStatusesHash[] = $orderStatus_->get('status');	// order by Pos.
            $this->stats['orders'][$orderStatus_->get('status')]['statistics'] = $statRec;
            $this->stats['orders'][$orderStatus_->get('status')]['name'] = $orderStatus_->get('name');
        }
        $this->stats['total'] = $statRec;
        $this->stats['paid'] = $statRec;
        
        $order = new \XLite\Model\Order();
        $date = $this->get('monthDate');
        // fetch orders for this month
        array_map(array($this, "summarize"), $order->findAll("date>=$date"));

        // sort summarized results by order status Pos.
        $orders = $this->stats['orders'];
        $this->stats['orders'] = array();
        foreach ($orderStatusesHash as $orderStatus_) {
            $this->stats['orders'][$orderStatus_] = $orders[$orderStatus_];
        }

        return $this->stats;
    }

    function save($index, $order, $paid = false)
    {
        if ($order->get('date') >= $this->get('todayDate')) {
            $this->sum($index, "today", $order->get('total'), $paid);
        }
        if ($order->get('date') >= $this->get('weekDate')) {
            $this->sum($index, "week", $order->get('total'), $paid);
        }
        if ($order->get('date') >= $this->get('monthDate')) {
            $this->sum($index, "month", $order->get('total'), $paid);
        }
    }

    function sum($index, $period, $amount, $paid)
    {
        $this->stats['orders'][$index]['statistics'][$period]++;
        $this->stats['total'][$period] += $amount;
        if ($paid) {
            $this->stats['paid'][$period] += $amount;
        }
    }
    
    function summarize($order)
    {
        $orderStatus = $order->get('orderStatus');
        $paid = ($orderStatus->get('status') == "P" || $orderStatus->get('status') == "C" || $orderStatus->get('parent') == "P" || $orderStatus->get('parent') == "C" ? true : false);
        $this->save($orderStatus->get('status'), $order, $paid);
    }
}
