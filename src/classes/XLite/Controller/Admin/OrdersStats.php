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
class OrdersStats extends Stats
{
    protected $stats = array();

    function getPageTemplate()
    {
        return "orders_stats.tpl";
    }

    function handleRequest()
    {
        // typedef
        $statRec = array("today" => 0, "week" => 0, "month" => 0);
        $this->stat = array(
                "processed" => $statRec,
                "queued" => $statRec,
                "failed" => $statRec,
                "not_finished" => $statRec,
                "total" => $statRec,
                "paid" => $statRec);

        $order = new \XLite\Model\Order();
        $date = $this->get('monthDate');
        // fetch orders for this month
        array_map(array($this, "summarize"), $order->findAll("date>=$date"));

        parent::handleRequest();
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
        $this->stat[$index][$period]++;
        $this->stat['total'][$period] += $amount;
        if ($paid) {
            $this->stat['paid'][$period] += $amount;
        }
    }
    
    function summarize($order)
    {
        switch ($order->get('status')) {
            case "P":
            case "C":
                $this->save('processed', $order, true);
                break;
            case "Q":
                $this->save('queued', $order);
                break;
            case "I":
                $this->save('not_finished', $order);
                break;
            case "F":
            case "D":
                $this->save('failed', $order);
                break;
        }
    }
}
