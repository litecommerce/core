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

namespace XLite\Controller\Customer;

/**
 * Orders list 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderList extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target');

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

        if (isset(\XLite\Core\Request::getInstance()->pageId)) {

            $ordersSearch = $this->session->get('orders_search');

            if (!is_array($ordersSearch)) {
                $ordersSearch = \XLite\Model\Order::getDefaultSearchConditions();
            }

            $ordersSearch['pageId'] = intval(\XLite\Core\Request::getInstance()->pageId);

            $this->session->set('orders_search', $ordersSearch);
        }
    }

    /**
     * Check if current page is accessible
     * 
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    public function checkAccess()
    {
        $auth = \XLite\Core\Auth::getInstance();

        return parent::checkAccess() 
            && $auth->isLogged() 
            && (
                $auth->getProfile()->isAdmin() 
                || $auth->getProfile()->getProfileId() == \XLite\Core\Request::getInstance()->userId
            );
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
            $ordersSearch = \XLite\Model\Order::getDefaultSearchConditions();
        }

        if (isset(\XLite\Core\Request::getInstance()->order_id)) {

            $ordersSearch['order_id'] = intval(\XLite\Core\Request::getInstance()->order_id);

            if (0 == $ordersSearch['order_id']) {
                $ordersSearch['order_id'] = '';
            }

        }

        if (isset(\XLite\Core\Request::getInstance()->status)) {
            $ordersSearch['status'] = \XLite\Core\Request::getInstance()->status;
        }

        if (
            isset(\XLite\Core\Request::getInstance()->startDateMonth)
            && isset(\XLite\Core\Request::getInstance()->startDateDay)
            && isset(\XLite\Core\Request::getInstance()->startDateYear)
        ) {
            $ordersSearch['startDate'] = mktime(
                0, 0, 0,
                intval(\XLite\Core\Request::getInstance()->startDateMonth),
                intval(\XLite\Core\Request::getInstance()->startDateDay),
                intval(\XLite\Core\Request::getInstance()->startDateYear)
            );

        } elseif (isset(\XLite\Core\Request::getInstance()->startDate)) {

            $time = strtotime(\XLite\Core\Request::getInstance()->startDate);

            if (
                false !== $time 
                && -1 !== $time
            ) {

                $ordersSearch['startDate'] = mktime(
                    0, 0, 0,
                    date('m', $time),
                    date('d', $time),
                    date('Y', $time)
                );

            } elseif (0 == strlen(\XLite\Core\Request::getInstance()->startDate)) {

                $ordersSearch['startDate'] = '';

            }

        }

        if (
            isset(\XLite\Core\Request::getInstance()->endDateMonth)
            && isset(\XLite\Core\Request::getInstance()->endDateDay)
            && isset(\XLite\Core\Request::getInstance()->endDateYear)
        ) {
            $ordersSearch['endDate'] = mktime(
                23, 59, 59,
                intval(\XLite\Core\Request::getInstance()->endDateMonth),
                intval(\XLite\Core\Request::getInstance()->endDateDay),
                intval(\XLite\Core\Request::getInstance()->endDateYear)
            );

        } elseif (isset(\XLite\Core\Request::getInstance()->endDate)) {
            $time = strtotime(\XLite\Core\Request::getInstance()->endDate);

            if (false !== $time && -1 !== $time) {
                $ordersSearch['endDate'] = mktime(
                    23, 59, 59,
                    date('m', $time),
                    date('d', $time),
                    date('Y', $time)
                );
                
            } elseif (0 == strlen(\XLite\Core\Request::getInstance()->endDate)) {
                $ordersSearch['endDate'] = '';
            }

        }

        if (\XLite\Core\Request::getInstance()->sortCriterion) {
            $ordersSearch['sortCriterion'] = \XLite\Core\Request::getInstance()->sortCriterion;
        }

        if (\XLite\Core\Request::getInstance()->sortOrder) {
            $ordersSearch['sortOrder'] = \XLite\Core\Request::getInstance()->sortOrder;
        }

        if (isset(\XLite\Core\Request::getInstance()->pageId)) {
            $ordersSearch['pageId'] = intval(\XLite\Core\Request::getInstance()->pageId);
        }

        $this->session->set('orders_search', $ordersSearch);

        $this->set('returnUrl', $this->buildUrl('order_list'));
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
        $this->session->set('orders_search', \XLite\Model\Order::getDefaultSearchConditions());

        $this->set('returnUrl', $this->buildUrl('order_list'));
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

}
