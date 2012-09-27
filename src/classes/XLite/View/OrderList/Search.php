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

namespace XLite\View\OrderList;

/**
 * Orders search widget
 * TODO: reimplement with items lists usage
 *
 *
 * @ListChild (list="orders.search.base", weight="30")
 */
class Search extends \XLite\View\OrderList\AOrderList
{
    /**
     * Widget class name
     *
     * @var string
     */
    protected $widgetClass = '\XLite\View\OrderList\Search';

    /**
     * Search conditions (cache)
     *
     * @var array
     */
    protected $conditions = null;


    /**
     * Get orders
     *
     * @return array(\XLite\Model\Order)
     */
    public function getOrders(\XLite\Core\CommonCell $cnd = null)
    {
        if (!isset($this->orders)) {

            $this->orders = \XLite\Core\Database::getRepo('\XLite\Model\Order')
                ->search($this->getConditions($cnd));
        }

        return $this->orders;
    }

    /**
     * Get page data
     *
     * @return array
     */
    public function getPageData()
    {
        if (!isset($this->namedWidgets['pager'])) {

            $this->getWidget(
                array('pageId' => $this->getPageId()),
                '\XLite\View\Pager\Customer\Order\Search',
                'pager'
            );
        }

        return $this->getOrders($this->namedWidgets['pager']->getLimitCondition());
    }

    /**
     * Get page id
     *
     * @return integer
     */
    public function getPageId()
    {
        return abs(intval($this->getConditions()->pageId));
    }


    /**
     * Get profile
     *
     * @return \XLite\Model\Profile
     */
    protected function getProfile()
    {
        return \XLite\Core\Auth::getInstance()->getProfile(\XLite\Core\Request::getInstance()->profile_id);
    }

    /**
     * Get widget keys
     *
     * @return array
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
     */
    protected function getConditions(\XLite\Core\CommonCell $cnd = null)
    {
        if (!isset($this->conditions)) {

            $this->conditions = \XLite\Core\Session::getInstance()->orders_search;

            if (!is_array($this->conditions)) {

                $this->conditions = array();

                \XLite\Core\Session::getInstance()->orders_search = $this->conditions;
            }

            foreach ($this->conditions as $key => $value) {

            }
        }

        $cnd = $cnd ?: new \XLite\Core\CommonCell();

        if ($this->getProfile()->isAdmin() && \XLite::isAdminZone()) {

            if (!empty(\XLite\Core\Request::getInstance()->profile_id)) {
                $cnd->profileId = \XLite\Core\Request::getInstance()->profile_id;
            }

        } else {
            $cnd->profileId = $this->getProfile()->getProfileId();
        }

        // Sort orders list in reverse chronological order by default
        if (!isset($this->conditions['sortCriterion']) || !$this->conditions['sortCriterion']) {
            $this->conditions['sortCriterion'] = 'order_id';
        }

        if (!isset($this->conditions['sortOrder']) || !$this->conditions['sortOrder']) {
            $this->conditions['sortOrder'] = 'DESC';
        }

        $cnd->orderBy = array('o.' . $this->conditions['sortCriterion'], $this->conditions['sortOrder']);

        if (isset($this->conditions['order_id'])) {
            $cnd->orderId = $this->conditions['order_id'];
        }

        if (isset($this->conditions['status'])) {
            $cnd->status = $this->conditions['status'];
        }

        $start = isset($this->conditions['startDate']) ? $this->conditions['startDate'] : 0;

        $end   = isset($this->conditions['endDate']) ? $this->conditions['endDate'] : 0;

        if ($start < $end) {
            $cnd->date = array($start, $end);
        }

        return $cnd;
    }
}
