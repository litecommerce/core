<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Orders list
 *  
 * @category  Litecommerce
 * @package   Controller Customer
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Orders list
 * 
 * @package Controller Customer
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_OrderList extends XLite_Controller_Customer_Abstract
{    
    /**
     * params 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'mode', 'order_id1', 'order_id2', 'status');    

    /**
     * order_id1 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $order_id1 = '';    

    /**
     * order_id2 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $order_id2 = '';    

    /**
     * status 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $status = '';    

    /**
     * orders 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $orders = null;



    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getLocation()
    {   
        return 'Search orders';
    }



    /**
     * Setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    function set($name, $value)
    {
        switch($name) {
            case 'startDate':
            case 'endDate':
                $value = intval($value);
                break;
        }

        parent::set($name, $value);
    }

    /**
     * Prefill form 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    function fillForm()
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set('startDate', mktime(0, 0, 0, $date['mon'], 1, $date['year']));
        }

        parent::fillForm();
    }
    
    /**
     * Get orders list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getOrders()
    {
        if (is_null($this->orders)) {
            if (!$this->auth->is('logged')) {
                die('Access denied');
            }

            $order = new XLite_Model_Order();
            $this->orders = $order->search(
                $this->auth->get('profile'), 
                $this->get('order_id1'), 
                $this->get('order_id2'), 
                $this->get('status'),
                $this->get('startDate'), 
                $this->get('endDate') + 24 * 3600
            );
        }

        return $this->orders;
    }

    /**
     * Get orders count 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getCount()
    {
        return count($this->get('orders'));
    }

    /**
     * Get secure mode
     * 
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getSecure()
    {
        return $this->config->Security->customer_security;
    }

    /**
     * Get page instance data (name and URL)
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceData()
    {
        $this->target = 'order_list';

        return parent::getPageInstanceData();
    }

    /**
     * Get page title
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Orders list';
    }

}

