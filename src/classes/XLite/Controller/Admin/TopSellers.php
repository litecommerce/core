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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Top sellers statistics page controller
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class TopSellers extends \XLite\Controller\Admin\Stats
{
    /**
     * sort_by 
     * FIXME: to refactoring
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $sort_by = "amount";
    
    /**
     * counter 
     * FIXME: to refactoring
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $counter = array(0,1,2,3,4,5,6,7,8,9);

    /**
     * topProducts 
     * FIXME: to refactoring
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $topProducts = array();


    /**
     * getPageTemplate 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTemplate()
    {
        return 'top_sellers.tpl';
    }

    /**
     * getTopProduct 
     * 
     * @param mixed $period   ____param_comment____
     * @param mixed $pos      ____param_comment____
     * @param mixed $property ____param_comment____
     *  
     * @return void
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Statistics', $this->buildURL('orders_stats'));
    }

    /**
     * Initialize table matrix
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initStats()
    {
        return array_fill_keys($this->getStatsColumns(), array());
    }

    /**
     * Get data
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData()
    {
        $cnd = $this->getSearchCondition();

        return \XLite\Core\Database::getRepo('\XLite\Model\Order')->search($cnd);
/*
        $this->sort('todayItems');
        $this->sort('weekItems');
        $this->sort('monthItems');
*/
    }

    /**
     * Collect statistics record
     *
     * @param string             $row   Row identificator
     * @param \Xlite\Model\Order $order Order
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectStatsRecord($order)
    {
        foreach ($this->getStatsColumns() as $period) {
            if ($order->getDate() >= $this->getStartTime($period)) {
                $this->stats[$period] = array_merge($this->stats[$period], $order->getItems());
            }
        }
    }

    /**
     * Process statistics record
     *
     * @param \Xlite\Model\Order $order Order
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processStatsRecord($order)
    {
        $this->collectStatsRecord($order);
    }

    /**
     * sort 
     * 
     * @param mixed $name ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sort($name)
    {
        $this->topProducts[$name] = array();
        
        foreach ((array) $this->get($name) as $item) {
        
            $id = $item->get('product_id');
        
            if ($id) {

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
