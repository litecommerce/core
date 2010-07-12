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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Orders search widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderSearch extends \XLite\View\Dialog
{
    /**
     * Orders list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $orders = null;
    /**
     * Orders total count 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $totalCount = null;

    /**
     * Conditions (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $conditions = null;


    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Search orders';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'order/search';
    }

    /**
     * Get conditions 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        if (is_null($this->conditions)) {
            $this->conditions = $this->session->get('orders_search');
            if (!is_array($this->conditions)) {
                $this->conditions = \XLite\Model\Order::getDefaultSearchConditions();
                $this->session->set('orders_search', $this->conditions);
            }
        }

        return $this->conditions;
    }

    /**
     * Get condition 
     * 
     * @param string $name Condition name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCondition($name)
    {
        $conditions = $this->getConditions();

        return isset($conditions[$name]) ? $conditions[$name] : null;
    }

    /**
     * Check - used conditions is default or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDefaultConditions()
    {
        $default = \XLite\Model\Order::getDefaultSearchConditions();

        unset($default['sortCriterion'], $default['sortOrder']);

        $intersect = count(
            array_intersect_assoc(
                $this->getConditions(),
                $default
            )
        );

        return count($default) == $intersect;
    }

    /**
     * Get orders list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrders()
    {
        if (is_null($this->orders)) {
            $conditions = $this->getConditions();

            $order = new \XLite\Model\Order();
            $this->orders = $order->search(
                $this->getProfile(),
                $conditions['order_id'],
                $conditions['status'],
                $conditions['startDate'],
                $conditions['endDate'],
                true,
                $conditions['sortCriterion'],
                $conditions['sortOrder'] == 'asc'
            );
        }

        return $this->orders;
    }

    /**
     * Get profile
     *
     * @return \XLite\Model\Profile
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProfile()
    {
        $result = null;

        if (\XLite::isAdminZone()) {
            if (\XLite\Core\Request::getInstance()->profile_id) {
                $result = new \XLite\Model\Profile(\XLite\Core\Request::getInstance()->profile_id);
                if (!$result->isExists()) {
                    $result = null;
                }
            }

        } else {
            $result = \XLite\Model\Auth::getInstance()->getProfile(\XLite\Core\Request::getInstance()->profile_id);
            if (!$result) {
                $result = \XLite\Model\Auth::getInstance()->getProfile();
            }
        }

        return $result;
    }

    /**
     * Get orders list count 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCount()
    {
        return count($this->getOrders());
    }

    /**
     * Get total count 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTotalCount()
    {
        if (is_null($this->totalCount)) {
            $order = new \XLite\Model\Order();
            $this->totalCount = $order->getCountByProfile($this->getProfile());
        }

        return $this->totalCount;
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'order/search/search.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'order/search/search.css';

        return $list;
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'order_list';
    
        return $result;
    }
}
