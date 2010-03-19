<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Orders list widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Orders list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_OrderList extends XLite_View_Dialog
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('order_list');

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
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Search result';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDir()
    {
        return $this->getCount() ? 'order/list' : 'order/list_empty';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible()
            && 'search' == XLite_Core_Request::getInstance()->mode;
    }

    /**
     * Get search conditions 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        $ordersSearch = $this->session->get('orders_search');
        if (!is_array($ordersSearch)) {
            $ordersSearch = XLite_Model_Order::getDefaultSearchConditions();
            $this->session->set('orders_search', $ordersSearch);
        }

        return $ordersSearch;
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

            $order = new XLite_Model_Order();
            $this->orders = $order->search(
                $this->auth->getProfile(),
                $conditions['order_id1'],
                $conditions['order_id2'],
                $conditions['status'],
                $conditions['startDate'],
                $conditions['endDate']
            );
        }

        return $this->orders;
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
}

