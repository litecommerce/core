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
 * Recent orders list block (for dashboard page)
 * 
 */
class RecentBlock extends \XLite\View\ItemsList\Model\Order\Admin\Recent
{
    /**
     * Get URL of 'More...' link
     *
     * @return string
     */
    public function getMoreLink()
    {
        return \XLite\Core\Converter::buildURL('recent_orders');
    }

    /**
     * Get title of 'More...' link
     *
     * @return string
     */
    public function getMoreLinkTitle()
    {
        return 'View all open orders';
    }


    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return $this->hasResults() ? 'Recent orders' : 'No orders has been placed yet';
    }

    /**
     * Get template displayed when the list is empty
     *
     * @return string
     */
    protected function getEmptyListTemplate()
    {
        return $this->getDir() . '/empty_blank.tpl';
    }

    /**
     * Define list columns
     *
     * @return array
     */
    protected function defineColumns()
    {
        $result = array();
        
        $columns = parent::defineColumns();

        // Re-sort columns (move 'status' column before 'date')
        foreach ($columns as $k => $v) {
            if ('date' == $k) {
                $result['status'] = $columns['status'];
            } elseif ('status' == $k) {
                continue;
            }

            $result[$k] = $v;
        }

        $result['status'] = array(
            static::COLUMN_NAME  => null,
            static::COLUMN_LINK   => 'order',
            static::COLUMN_CLASS => null,
            static::COLUMN_TEMPLATE => $this->getDir() . '/' . $this->getPageBodyDir() . '/order/cell.status.tpl'
        );

        return $result;
    }

    /**
     * Preprocess order status value
     *
     * @param integer            $id     ID
     * @param array              $column Column
     * @param \XLite\Model\Order $entity Order entity
     *
     * @return string
     */
    protected function preprocessStatus($id, array $column, \XLite\Model\Order $entity)
    {
        return \XLite\Model\Order::getAllowedStatuses($entity->getStatus());
    }

    /**
     * Hide panel
     *
     * @return null
     */
    protected function getPanelClass()
    {
        return null;
    }

    /**
     * Items are non-removable
     *
     * @return boolean
     */
    protected function isRemoved()
    {
        return false;
    }

    /**
     * Get pager class
     *
     * @return string
     */
    protected function getPagerClass()
    {
        return 'XLite\View\Pager\Admin\Model\Block';
    }
}
