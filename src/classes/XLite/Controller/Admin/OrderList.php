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

namespace XLite\Controller\Admin;

/**
 * Orders list controller
 *
 */
class OrderList extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage orders');
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Search for orders';
    }

    // {{{ Search

    /**
     * getDateValue
     * FIXME - to remove
     *
     * @param string  $fieldName Field name (prefix)
     * @param boolean $isEndDate End date flag OPTIONAL
     *
     * @return integer
     */
    public function getDateValue($fieldName, $isEndDate = false)
    {
        $dateValue = \XLite\Core\Request::getInstance()->$fieldName;

        if (!isset($dateValue)) {
            $nameDay   = $fieldName . 'Day';
            $nameMonth = $fieldName . 'Month';
            $nameYear  = $fieldName . 'Year';

            if (
                isset(\XLite\Core\Request::getInstance()->$nameMonth)
                && isset(\XLite\Core\Request::getInstance()->$nameDay)
                && isset(\XLite\Core\Request::getInstance()->$nameYear)
            ) {
                $dateValue = mktime(
                    $isEndDate ? 23 : 0,
                    $isEndDate ? 59 : 0,
                    $isEndDate ? 59 : 0,
                    \XLite\Core\Request::getInstance()->$nameMonth,
                    \XLite\Core\Request::getInstance()->$nameDay,
                    \XLite\Core\Request::getInstance()->$nameYear
                );
            }
        }

        return $dateValue;
    }

    /**
     * Get search condition parameter by name
     *
     * @param string $paramName Parameter name
     *
     * @return mixed
     */
    public function getCondition($paramName)
    {
        $searchParams = $this->getConditions();

        return isset($searchParams[$paramName])
            ? $searchParams[$paramName]
            : null;
    }

    /**
     * Get date condition parameter (start or end)
     *
     * @param boolean $start Start date flag, otherwise - end date  OPTIONAL
     *
     * @return mixed
     */
    public function getDateCondition($start = true)
    {
        $dates = $this->getCondition(\XLite\Model\Repo\Order::P_DATE);
        $n = (true === $start) ? 0 : 1;

        return isset($dates[$n]) ? $dates[$n] : null;
    }

    /**
     * Common prefix for editable elements in lists
     *
     * NOTE: this method is requered for the GetWidget and AAdmin classes
     * TODO: after the multiple inheritance should be moved to the AAdmin class
     *
     * @return string
     */
    public function getPrefixPostedData()
    {
        return 'data';
    }

    /**
     * Get search conditions
     *
     * @return array
     */
    protected function getConditions()
    {
        $searchParams = \XLite\Core\Session::getInstance()->{\XLite\View\ItemsList\Model\Order\Admin\Search::getSessionCellName()};

        if (!is_array($searchParams)) {

            $searchParams = array();
        }

        return $searchParams;
    }

    // }}}

    // {{{ Actions

    /**
     * doActionUpdate
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $changes = $this->getOrdersChanges();

        $list = new \XLite\View\ItemsList\Model\Order\Admin\Search();
        $list->processQuick();

        foreach ($changes as $orderId => $change) {

            \XLite\Core\OrderHistory::getInstance()->registerOrderChanges($orderId, $change);
        }
    }

    /**
     * doActionSearch
     *
     * @return void
     */
    protected function doActionSearch()
    {
        $ordersSearch = array();
        $searchParams   = \XLite\View\ItemsList\Model\Order\Admin\Search::getSearchParams();

        // Prepare dates

        $this->startDate = $this->getDateValue('startDate');
        $this->endDate   = $this->getDateValue('endDate', true);

        if (
            0 === $this->startDate
            || 0 === $this->endDate
            || $this->startDate > $this->endDate
        ) {
            $date = getdate(time());

            $this->startDate = mktime(0, 0, 0, $date['mon'], 1, $date['year']);
            $this->endDate   = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
        }

        foreach ($searchParams as $modelParam => $requestParam) {

            if (\XLite\Model\Repo\Order::P_DATE === $requestParam) {

                $ordersSearch[$requestParam] = array($this->startDate, $this->endDate);

            } elseif (isset(\XLite\Core\Request::getInstance()->$requestParam)) {

                $ordersSearch[$requestParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }
        }

        \XLite\Core\Session::getInstance()->{\XLite\View\ItemsList\Model\Order\Admin\Search::getSessionCellName()} = $ordersSearch;
    }

    /**
     * Get order changes from request
     *
     * @return array
     */
    protected function getOrdersChanges()
    {
        $changes = array();

        foreach ($this->getPostedData() as $orderId => $data) {

            $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($orderId);

            foreach ($data as $name => $value) {
                $dataFromOrder = $order->{'get' . ucfirst($name)}();

                if ($dataFromOrder !== $value) {

                    $changes[$orderId][$name] = array(
                        'old' => $dataFromOrder,
                        'new' => $value,
                    );
                }
            }
        }

        return $changes;
    }

    // }}}
}
