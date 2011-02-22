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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
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
class OrdersStats extends \XLite\Controller\Admin\Stats
{
    /**
     * getPageTemplate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTemplate()
    {
        return 'orders_stats.tpl';
    }

    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        // typedef
        $statRec = array('today' => 0, 'week' => 0, 'month' => 0);
        $this->stat = array(
            'processed'    => $statRec,
            'queued'       => $statRec,
            'failed'       => $statRec,
            'not_finished' => $statRec,
            'total'        => $statRec,
            'paid'         => $statRec
        );

        $order = new \XLite\Model\Order();
        $date = $this->getMonthDate();
        // fetch orders for this month

        // FIXME - old code
        array_map(array($this, 'summarize'), /*$order->findAll("date>=$date")*/array());

        parent::handleRequest();
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->t('Order statistics');
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode($this->t('Statistics'), $this->buildURL('orders_stats'));
    }

    /**
     * save 
     * 
     * @param mixed $index ____param_comment____
     * @param mixed $order ____param_comment____
     * @param mixed $paid  ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function save($index, $order, $paid = false)
    {
        if ($order->getDate() >= $this->getTodayDate()) {
            $this->sum($index, 'today', $order->getTotal(), $paid);
        }
        if ($order->getDate() >= $this->getWeekDate()) {
            $this->sum($index, 'week', $order->getTotal(), $paid);
        }
        if ($order->getDate() >= $this->getMonthDate()) {
            $this->sum($index, 'month', $order->getTotal(), $paid);
        }
    }

    /**
     * sum 
     * 
     * @param mixed $index  ____param_comment____
     * @param mixed $period ____param_comment____
     * @param mixed $amount ____param_comment____
     * @param mixed $paid   ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sum($index, $period, $amount, $paid)
    {
        $this->stat[$index][$period] += 1;
        
        $this->stat['total'][$period] += $amount;
        
        if ($paid) {
            $this->stat['paid'][$period] += $amount;
        }
    }
    
    /**
     * summarize 
     * 
     * @param mixed $order ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function summarize($order)
    {
        switch ($order->getStatus()) {
            case 'P':
            case 'C':
                $this->save('processed', $order, true);
                break;

            case 'Q':
                $this->save('queued', $order);
                break;

            case 'I':
                $this->save('not_finished', $order);
                break;

            case 'F':
            case 'D':
                $this->save('failed', $order);
                break;

            default:
        }
    }
}
