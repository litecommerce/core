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

namespace XLite\View\OrderList;

/**
 * Orders search widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @ListChild (list="orders.search.base", weight="30")
 */
class Search extends \XLite\View\OrderList\AOrderList
{
    /**
     * Widget class name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $widgetClass = '\XLite\View\OrderList\Search';

    /**
     * Search conditions (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $conditions = null;

    /**
     * Get orders 
     * 
     * @return array of \XLite\Model\Order
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
     * Get page data 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageData()
    {
        if (!isset($this->namedWidgets['pager'])) {
            $this->getWidget(
                array(
                    'data'   => $this->getOrders(),
                    'pageId' => $this->getPageId(),
                ),
                '\XLite\View\PagerOrig\OrdersList',
                'pager'
            );
        }

        return $this->namedWidgets['pager']->getData();
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
        return \XLite\Model\Auth::getInstance()->getProfile(\XLite\Core\Request::getInstance()->profile_id);
    }

    /**
     * Get widget keys 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getWidgetKeys()
    {
        return array(
            'mode' => 'search'
        );
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
     * Get page id 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageId()
    {
        $conditions = $this->getConditions();

        return isset($conditions['pageId']) ? $conditions['pageId'] : 0;
    }
}

