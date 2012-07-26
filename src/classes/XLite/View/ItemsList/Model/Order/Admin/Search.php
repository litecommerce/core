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

namespace XLite\View\ItemsList\Model\Order\Admin;

/**
 * Search order
 *
 */
class Search extends \XLite\View\ItemsList\Model\Order\Admin\AAdmin
{
    /**
     * Widget param names
     */
    const PARAM_ORDER_ID = 'orderId';
    const PARAM_LOGIN    = 'login';
    const PARAM_STATUS   = 'status';
    const PARAM_DATE     = 'date';


    /**
     * Allowed sort criterions
     */
    const SORT_BY_MODE_ID       = 'o.order_id';
    const SORT_BY_MODE_DATE     = 'o.date';
    const SORT_BY_MODE_CUSTOMER = 'p.email';
    const SORT_BY_MODE_STATUS   = 'o.status';
    const SORT_BY_MODE_TOTAL    = 'o.total';


    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
        $this->sortByModes += array(
            static::SORT_BY_MODE_ID       => 'Order ID',
            static::SORT_BY_MODE_DATE     => 'Date',
            static::SORT_BY_MODE_CUSTOMER => 'Customer',
            static::SORT_BY_MODE_STATUS   => 'Status',
            static::SORT_BY_MODE_TOTAL    => 'Amount',
        );

        parent::__construct($params);
    }

    /**
     * Get a list of CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/' . $this->getPageBodyDir() . '/order/style.css';

        return $list;
    }

    /**
     * Set widget params
     *
     * @param array $params Handler params
     *
     * @return void
     */
    public function setWidgetParams(array $params)
    {
        if (!empty($params[static::PARAM_DATE]) && is_array($params[static::PARAM_DATE])) {
            foreach ($params[static::PARAM_DATE] as $i => $date) {
                if (is_string($date) && false !== strtotime($date)) {
                    $params[static::PARAM_DATE][$i] = strtotime($date);
                }
            }
        }

        parent::setWidgetParams($params);
    }

    /**
     * Define columns structure
     *
     * @return array
     */
    protected function defineColumns()
    {
        return array(
            'order_id' => array(
                static::COLUMN_NAME   => \XLite\Core\Translation::lbl('Order ID'),
                static::COLUMN_LINK   => 'order',
            ),
            'date' => array(
                static::COLUMN_NAME     => \XLite\Core\Translation::lbl('Date'),
                static::COLUMN_TEMPLATE => $this->getDir() . '/' . $this->getPageBodyDir() . '/order/cell.date.tpl',
            ),
            'profile' => array(
                static::COLUMN_NAME     => \XLite\Core\Translation::lbl('Customer'),
                static::COLUMN_TEMPLATE => $this->getDir() . '/' . $this->getPageBodyDir() . '/order/cell.profile.tpl',
            ),
            'status' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Status'),
                static::COLUMN_CLASS => 'XLite\View\FormField\Inline\Select\OrderStatus',
            ),
            'total' => array(
                static::COLUMN_NAME   => \XLite\Core\Translation::lbl('Amount'),
                static::COLUMN_TEMPLATE => $this->getDir() . '/' . $this->getPageBodyDir() . '/order/cell.total.tpl',
            ),
        );
    }

    /**
     * Get list name suffixes
     *
     * @return array
     */
    protected function getListNameSuffixes()
    {
        return array_merge(parent::getListNameSuffixes(), array('search'));
    }

    /**
     * Get panel class
     *
     * @return \XLite\View\Base\FormStickyPanel
     */
    protected function getPanelClass()
    {
        return 'XLite\View\StickyPanel\Order\Admin\Search';
    }

    /**
     * Preprocess profile
     *
     * @param \XLite\Model\Profile $profile Profile
     * @param array                $column  Column data
     * @param \XLite\Model\Order   $entity  Order
     *
     * @return string
     */
    protected function preprocessProfile(\XLite\Model\Profile $profile, array $column, \XLite\Model\Order $entity)
    {
        $address = $profile->getBillingAddress() ?: $profile->getShippingAddress();

        return $address ? $address->getName() : $profile->getLogin();
    }

    /**
     * Preprocess order id
     *
     * @param integer              $id      Order id
     * @param array                $column  Column data
     * @param \XLite\Model\Order   $entity  Order
     *
     * @return string
     */
    protected function preprocessOrderId($id, array $column, \XLite\Model\Order $entity)
    {
        return '#' . str_repeat('0', 5 - strlen($id)) . $id;
    }

    /**
     * Define line class as list of names
     *
     * @param integer              $index  Line index
     * @param \XLite\Model\AEntity $entity Line model
     *
     * @return array
     */
    protected function defineLineClass($index, \XLite\Model\AEntity $entity)
    {
        $classes = parent::defineLineClass($index, $entity);

        if (\XLite\Model\Order::STATUS_COMPLETED == $entity->getStatus()) {
            $classes[] = 'completed';
        }

        return $classes;
    }

    /**
     * Get right actions tempaltes
     *
     * @return array
     */
    protected function getRightActions()
    {
        $list = parent::getRightActions();

        $list[] = $this->getDir() . '/' . $this->getPageBodyDir() . '/order/action.link.tpl';

        return $list;
    }

    // {{{ Search

    /**
     * Return search parameters
     *
     * @return array
     */
    static public function getSearchParams()
    {
        return array(
            \XLite\Model\Repo\Order::P_ORDER_ID => static::PARAM_ORDER_ID,
            \XLite\Model\Repo\Order::P_EMAIL    => static::PARAM_LOGIN,
            \XLite\Model\Repo\Order::P_STATUS   => static::PARAM_STATUS,
            \XLite\Model\Repo\Order::P_DATE     => static::PARAM_DATE,
        );
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_ORDER_ID => new \XLite\Model\WidgetParam\Int('Order ID', null),
            static::PARAM_LOGIN    => new \XLite\Model\WidgetParam\String('Email', ''),
            static::PARAM_STATUS   => new \XLite\Model\WidgetParam\Set(
                'Status', null, array_keys(\XLite\Model\Order::getAllowedStatuses())
            ),
            static::PARAM_DATE     => new \XLite\Model\WidgetParam\Collection('Date', array(null, null)),
        );
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams = array_merge($this->requestParams, static::getSearchParams());
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        // We initialize structure to define order (field and sort direction) in search query.
        $result->{\XLite\Model\Repo\Order::P_ORDER_BY} = $this->getOrderBy();

        foreach (static::getSearchParams() as $modelParam => $requestParam) {
            $value = $this->getParam($requestParam);
            if (static::PARAM_DATE == $requestParam && is_array($value)) {
                foreach ($value as $i => $date) {
                    if (is_string($date) && false !== strtotime($date)) {
                        $value[$i] = strtotime($date);
                    }
                }
            }

            $result->$modelParam = $value;
        }

        return $result;
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Order')->search($cnd, $countOnly);
    }

    /**
     * Return 'Order by' array.
     * array(<Field to order>, <Sort direction>)
     *
     * @return array
     */
    protected function getOrderBy()
    {
        return array($this->getSortBy(), $this->getSortOrder());
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     */
    protected function getSortByModeDefault()
    {
        return static::SORT_BY_MODE_ID;
    }

    // }}}

    // {{{ Content helpers

    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return 'Search result';
    }

    /**
     * Get items sum quantity
     *
     * @param \XLite\Model\Order $order Order
     *
     * @return integer
     */
    protected function getItemsQuantity(\XLite\Model\Order $order)
    {
        return $order->countQuantity();
    }

    // }}}

    /**
     * Mark list as removable
     *
     * @return boolean
     */
    protected function isRemoved()
    {
        return true;
    }
}

