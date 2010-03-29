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

/**
 * Orders list 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_OrderList extends XLite_Controller_Customer_Abstract
{
    /**
     * Controller parameters 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'mode');

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLocation()
    {   
        return 'Search orders';
    }

    /**
     * Handles the request
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        parent::handleRequest();

        if (isset(XLite_Core_Request::getInstance()->pageId)) {
            $ordersSearch = $this->session->get('orders_search');
            if (!is_array($ordersSearch)) {
                $ordersSearch = XLite_Model_Order::getDefaultSearchConditions();
            }

            $ordersSearch['pageId'] = intval(XLite_Core_Request::getInstance()->pageId);

            $this->session->set('orders_search', $ordersSearch);
        }
    }

    /**
     * Check if current page is accessible
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkAccess()
    {
        return parent::checkAccess()
            && $this->auth->isLogged();
    }

    /**
     * Save search conditions
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearch()
    {
        $ordersSearch = $this->session->get('orders_search');
        if (!is_array($ordersSearch)) {
            $ordersSearch = XLite_Model_Order::getDefaultSearchConditions();
        }

        if (isset(XLite_Core_Request::getInstance()->order_id)) {
            $ordersSearch['order_id'] = intval(XLite_Core_Request::getInstance()->order_id);
            if (0 == $ordersSearch['order_id']) {
                $ordersSearch['order_id'] = '';
            }
        }

        if (isset(XLite_Core_Request::getInstance()->status)) {
            $ordersSearch['status'] = XLite_Core_Request::getInstance()->status;
        }

        if (
            isset(XLite_Core_Request::getInstance()->startDateMonth)
            && isset(XLite_Core_Request::getInstance()->startDateDay)
            && isset(XLite_Core_Request::getInstance()->startDateYear)
        ) {
            $ordersSearch['startDate'] = mktime(
                0, 0, 0,
                intval(XLite_Core_Request::getInstance()->startDateMonth),
                intval(XLite_Core_Request::getInstance()->startDateDay),
                intval(XLite_Core_Request::getInstance()->startDateYear)
            );

        } elseif (isset(XLite_Core_Request::getInstance()->startDate)) {
            $time = strtotime(XLite_Core_Request::getInstance()->startDate);
            if (false !== $time && -1 !== $time) {
                $ordersSearch['startDate'] = mktime(
                    0, 0, 0,
                    date('m', $time),
                    date('d', $time),
                    date('Y', $time)
                );

            } elseif (0 == strlen(XLite_Core_Request::getInstance()->startDate)) {
                $ordersSearch['startDate'] = '';
            }
        }

        if (
            isset(XLite_Core_Request::getInstance()->endDateMonth)
            && isset(XLite_Core_Request::getInstance()->endDateDay)
            && isset(XLite_Core_Request::getInstance()->endDateYear)
        ) {
            $ordersSearch['endDate'] = mktime(
                23, 59, 59,
                intval(XLite_Core_Request::getInstance()->endDateMonth),
                intval(XLite_Core_Request::getInstance()->endDateDay),
                intval(XLite_Core_Request::getInstance()->endDateYear)
            );

        } elseif (isset(XLite_Core_Request::getInstance()->endDate)) {
            $time = strtotime(XLite_Core_Request::getInstance()->endDate);
            if (false !== $time && -1 !== $time) {
                $ordersSearch['endDate'] = mktime(
                    23, 59, 59,
                    date('m', $time),
                    date('d', $time),
                    date('Y', $time)
                );
                
            } elseif (0 == strlen(XLite_Core_Request::getInstance()->endDate)) {
                $ordersSearch['endDate'] = '';
            }
        }

        if (XLite_Core_Request::getInstance()->sortCriterion) {
            $ordersSearch['sortCriterion'] = XLite_Core_Request::getInstance()->sortCriterion;
        }

        if (XLite_Core_Request::getInstance()->sortOrder) {
            $ordersSearch['sortOrder'] = XLite_Core_Request::getInstance()->sortOrder;
        }

        if (isset(XLite_Core_Request::getInstance()->pageId)) {
            $ordersSearch['pageId'] = intval(XLite_Core_Request::getInstance()->pageId);
        }

        $this->session->set('orders_search', $ordersSearch);

        $this->set('returnUrl', $this->buildUrl('order_list', '', array('mode' => 'search')));
    }

    /**
     * Reset search conditions
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionReset()
    {
        $this->session->set('orders_search', XLite_Model_Order::getDefaultSearchConditions());
        $this->set('returnUrl', $this->buildUrl('order_list', '', array('mode' => 'search')));
    }

    /**
     * Setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($name, $value)
    {
        switch ($name) {
            case 'startDate':
            case 'endDate':
                $value = intval($value);
                break;

            default:
        }

        parent::set($name, $value);
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

