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
class TopSellers extends \XLite\Controller\Admin\Stats
{
    /**
     * todayItems 
     * FIXME: to refactoring
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $todayItems = array();

    /**
     * weekItems
     * FIXME: to refactoring
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $weekItems = array();

    /**
     * monthItems 
     * FIXME: to refactoring
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $monthItems = array();

    /**
     * sort_by 
     * FIXME: to refactoring
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $sort_by = "amount";
    
    /**
     * counter 
     * FIXME: to refactoring
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $counter = array(0,1,2,3,4,5,6,7,8,9);

    /**
     * topProducts 
     * FIXME: to refactoring
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $topProducts = array();


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
        return 'top_sellers.tpl';
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
            'processed' => $statRec,
            'queued' => $statRec,
            'failed' => $statRec,
            'not_finished' => $statRec,
            'total' => $statRec,
            'paid' => $statRec
        );

        $order = new \XLite\Model\Order();
        $date = $this->getMonthDate();

        // FIXME - old code
        array_map(array($this, 'collect'), /*$order->findAll("(status='P' OR status='C') AND date>=$date")*/ array());

        $this->sort('todayItems');
        $this->sort('weekItems');
        $this->sort('monthItems');

        parent::handleRequest();
    }

    /**
     * getTopProduct 
     * 
     * @param mixed $period   ____param_comment____
     * @param mixed $pos      ____param_comment____
     * @param mixed $property ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTopProduct($period, $pos, $property)
    {
        $val = $this->getComplex('topProducts.' . $period . 'Items.' . $pos . '.' . $property);
    
        return is_null($val) ? '' : $val;
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
        return $this->t('Top sellers');
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

        $this->addLocationNode('Statistics', $this->buildURL('orders_stats'));
    }

    /**
     * collect 
     * 
     * @param mixed $order ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collect($order)
    {
        $items = $order->get('items');
        if ($order->get('date') >= $this->get('todayDate')) {
            $this->todayItems = array_merge($this->todayItems, $items);
        }
        if ($order->get('date') >= $this->get('weekDate')) {
            $this->weekItems = array_merge($this->weekItems, $items);
        }
        if ($order->get('date') >= $this->get('monthDate')) {
            $this->monthItems = array_merge($this->monthItems, $items);
        }
    }

    /**
     * sort 
     * 
     * @param mixed $name ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sort($name)
    {
        $this->topProducts[$name] = array();
        
        foreach ((array) $this->get($name) as $item) {
        
            $id = $item->get('product_id');
        
            if (!$id) {
                continue;
            }

            if (!isset($this->topProducts[$name][$id])) {
                $this->topProducts[$name][$id] = array(
                    'id'     => $id,
                    'name'   => $item->get('name'),
                    'amount' => $item->get('amount')
                );

            } else {
                $this->topProducts[$name][$id]['amount'] += $item->get('amount');
            }
        }

        usort($this->topProducts[$name], array($this, 'compareProducts'));

        $topProducts = array_chunk(array_reverse($this->topProducts[$name]), 10);
        
        $this->topProducts[$name] = isset($topProducts[0]) ? $topProducts[0] : null;
    }

    /**
     * compareProducts 
     * 
     * @param mixed $p1 ____param_comment____
     * @param mixed $p2 ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function compareProducts($p1, $p2)
    {
        $result = 0;
        
        $key = $this->sort_by;
        
        if ($p1[$key] != $p2[$key]) {
            $result = ($p1[$key] < $p2[$key]) ? -1 : 1;
        }

        return $result;
    }
}
