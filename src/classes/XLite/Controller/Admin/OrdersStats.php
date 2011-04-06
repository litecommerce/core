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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Orders statistics page controller
 *
 * @see   ____class_see____
 * @since 3.0.0
 */
class OrdersStats extends \XLite\Controller\Admin\Stats
{
    /**
     * Columns
     */
    const P_PROCESSED  = 'processed';
    const P_QUEUED     = 'queued';
    const P_FAILED     = 'failed';
    const P_INCOMPLETE = 'incomplete';
    const P_TOTAL      = 'total';
    const P_PAID       = 'paid';


    /**
     * getPageTemplate
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTemplate()
    {
        return 'orders_stats.tpl';
    }

    /**
     * Get row headings
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRowTitles()
    {
        return array(
            self::P_PROCESSED  => 'Processed/Completed',
            self::P_QUEUED     => 'Queued',
            self::P_FAILED     => 'Failed/Declined',
            self::P_INCOMPLETE => 'Not finished',
            self::P_TOTAL      => 'Total',
            self::P_PAID       => 'Paid',
        );
    }

    /**
     * Status rows as row identificator => included statuses
     *
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    public function getStatusRows()
    {
        return array(
            self::P_PROCESSED => array(
                \XLite\Model\Order::STATUS_PROCESSED,
                \XLite\Model\Order::STATUS_COMPLETED,
            ),
            self::P_QUEUED => array(
                \XLite\Model\Order::STATUS_QUEUED,
            ),
            self::P_FAILED => array(
                \XLite\Model\Order::STATUS_FAILED,
                \XLite\Model\Order::STATUS_DECLINED,
            ),
            self::P_INCOMPLETE => array(
                \XLite\Model\Order::STATUS_INPROGRESS,
            ),
            self::P_TOTAL => array(
                \XLite\Model\Order::STATUS_INPROGRESS,
                \XLite\Model\Order::STATUS_FAILED,
                \XLite\Model\Order::STATUS_DECLINED,
                \XLite\Model\Order::STATUS_QUEUED,
                \XLite\Model\Order::STATUS_PROCESSED,
                \XLite\Model\Order::STATUS_COMPLETED,
            ),
            self::P_PAID => array(
                \XLite\Model\Order::STATUS_PROCESSED,
                \XLite\Model\Order::STATUS_COMPLETED,
            ),
        );
    }

    /**
     * Is totals row
     *
     * @param  string $row Row identificator
     * @return boolean
     * @see    ____var_see____
     * @since  3.0.0
     */
    public function isTotalsRow($row)
    {
        return in_array(
            $row,
            array(
                self::P_PAID,
                self::P_TOTAL,
            )
        );
    }

    /**
     * Get data
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStatsRows()
    {
        return array_keys($this->getStatusRows());
    }

    /**
     * Get data
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData()
    {
        $cnd = $this->getSearchCondition();

        return \XLite\Core\Database::getRepo('\XLite\Model\Order')->search($cnd);
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->t('Order statistics');
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode($this->t('Statistics'), $this->buildURL('orders_stats'));
    }

    /**
     * Collect statistics record
     *
     * @param string             $row   Row identificator
     * @param \Xlite\Model\Order $order Order
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectStatsRecord($row, $order)
    {
        foreach ($this->getStatsColumns() as $period) {

            if ($order->getDate() >= $this->getStartTime($period)) {

                if ($this->isTotalsRow($row)) {
                    $this->stats[$row][$period] += $order->getTotal();
                } else {
                    $this->stats[$row][$period] += 1;
                }

            }
        }
    }

    /**
     * Process statistics record
     *
     * @param \Xlite\Model\Order $order Order
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processStatsRecord($order)
    {
        foreach ($this->getStatusRows() as $row => $includedStatuses) {
            if (in_array($order->getStatus(), $includedStatuses)) {
                $this->collectStatsRecord($row, $order);
            }
        }
    }
}
