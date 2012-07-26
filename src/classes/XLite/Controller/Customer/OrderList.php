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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Customer;

/**
 * Orders list
 *
 */
class OrderList extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters
     *
     * @var array
     */
    protected $params = array('target', 'page');

    /**
     * Handles the request
     *
     * @return void
     */
    public function handleRequest()
    {
        parent::handleRequest();

        if (isset(\XLite\Core\Request::getInstance()->pageId)) {
            $ordersSearch = \XLite\Core\Session::getInstance()->orders_search;

            if (!is_array($ordersSearch)) {
                $ordersSearch = \XLite\Model\Order::getDefaultSearchConditions();
            }

            $ordersSearch['pageId'] = intval(\XLite\Core\Request::getInstance()->pageId);

            \XLite\Core\Session::getInstance()->orders_search = $ordersSearch;
        }
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean
     */
    public function checkAccess()
    {
        return parent::checkAccess() && \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Setter
     *
     * @param string $name  Property name
     * @param mixed  $value Property value
     *
     * @return void
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
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return 'Orders';
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('My account');
    }

    /**
     * Save search conditions
     * TODO: to revise
     *
     * @return void
     */
    protected function doActionSearch()
    {
        $ordersSearch = \XLite\Core\Session::getInstance()->orders_search;

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

            if (
                false !== $time
                && -1 !== $time
            ) {
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

        \XLite\Core\Session::getInstance()->orders_search = $ordersSearch;

        $this->setReturnURL($this->buildURL('order_list'));
    }

    /**
     * Reset search conditions
     *
     * @return void
     */
    protected function doActionReset()
    {
        \XLite\Core\Session::getInstance()->orders_search = \XLite\Model\Order::getDefaultSearchConditions();

        $this->setReturnURL($this->buildURL('order_list'));
    }

    // {{{ Pages

    /**
     * Get pages sections
     *
     * @return array
     */
    public function getPages()
    {
        $list = parent::getPages();
        $list['default'] = 'Orders';

        return $list;
    }

    /**
     * Get pages templates
     *
     * @return array
     */
    protected function getPageTemplates()
    {
        $list = parent::getPages();
        $list['default'] = 'order/list.tpl';

        return $list;
    }

    // }}}
}
