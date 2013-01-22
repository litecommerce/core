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
 * Store statisics page controller
 *
 */
class Stats extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Time params
     */
    const P_TODAY  = 'today';
    const P_WEEK   = 'week';
    const P_MONTH  = 'month';
    const P_YEAR   = 'year';
    const P_ALL    = 'all';

    /**
     * Statistics data
     *
     * @return array
     */
    protected $stats = null;

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Statistics';
    }

    /**
     * Prepare statistics table
     *
     * @return array
     */
    public function getStats()
    {
        if (!isset($this->stats)) {
            $this->stats = $this->initStats();
        }

        return $this->stats;
    }

    /**
     * Get column headings
     *
     * @return array
     */
    public function getColumnTitles()
    {
        return array(
            self::P_TODAY => 'Today',
            self::P_WEEK  => 'This week',
            self::P_MONTH => 'This month',
            self::P_YEAR  => 'This year',
            self::P_ALL   => 'All time',
        );
    }

    /**
     * Get row headings
     *
     * @return array
     */
    public function getRowTitles()
    {
        return array();
    }

    /**
     * Get column heading
     *
     * @param string $column Column identificator
     *
     * @return array|string
     */
    public function getColumnTitle($column)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getColumnTitles(), $column);
    }

    /**
     * Get row heading
     *
     * @param string $row Row identificator
     *
     * @return array|string
     */
    public function getRowTitle($row)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getRowTitles(), $row);
    }

    // {{{ Common functions

    /**
     * Get rows for statistics table
     *
     * @return array
     */
    public function getStatsRows()
    {
        return array();
    }

    /**
     * Get columns for statistics table
     *
     * @return array
     */
    public function getStatsColumns()
    {
        return $this->getTimeIntervals();
    }

    /**
     * Get currencies
     *
     * @return array
     */
    public function getCurrencies()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Currency')->findUsed();
    }

    /**
     * Get currency from request
     *
     * @return \XLite\Model\Currency
     */
    public function getCurrency()
    {
        return \XLite\Core\Request::getInstance()->currency
            ? \XLite\Core\Database::getRepo('XLite\Model\Currency')
                ->findOneBy(array('currency_id' => \XLite\Core\Request::getInstance()->currency))
            : \XLite::getInstance()->getCurrency();
    }

    /**
     * Initialize table matrix
     *
     * @return array
     */
    protected function initStats()
    {
        return array_fill_keys(
            $this->getStatsRows(),
            array_fill_keys($this->getStatsColumns(), 0)
        );
    }

    /**
     * Get search condition
     *
     * @param string $interval Time interval OPTIONAL
     *
     * @return array
     */
    protected function getSearchCondition($interval = null)
    {
        $cnd = new \XLite\Core\CommonCell();

        $intervals = $this->getTimeIntervals();

        $cnd->date = array(
            $this->getStartTime($interval ? $interval : array_pop($intervals)),
            LC_START_TIME
        );

        $cnd->currency = $this->getCurrency();

        return $cnd;
    }

    /**
     * Get search data
     *
     * @return array
     */
    protected function getData()
    {
        return null;
    }

    // }}}

    // {{{ Time intervals operations

    /**
     * Time intervals
     *
     * @return array
     */
    protected function getTimeIntervals()
    {
        return array(
            self::P_TODAY,
            self::P_WEEK,
            self::P_MONTH,
            self::P_YEAR,
            self::P_ALL,
        );
    }

    /**
     * Get timestamp of current day start
     *
     * @return integer
     */
    protected function getStartTime($interval = self::P_ALL)
    {
        $methodName = 'get' . \XLite\Core\Converter::convertToCamelCase($interval) . 'StartTime';

        return method_exists($this, $methodName)
            ? call_user_func(array($this, $methodName))
            : $this->getDefaultStartTime();
    }

    /**
     * Get timestamp of current day start
     *
     * @return integer
     */
    protected function getTodayStartTime()
    {
        return mktime(0, 0, 0, date('m', LC_START_TIME), date('d', LC_START_TIME), date('Y', LC_START_TIME));
    }

    /**
     * Get timestamp of current week start
     *
     * @return integer
     */
    protected function getWeekStartTime()
    {
        return LC_START_TIME - (date('w', LC_START_TIME) * 86400);
    }

    /**
     * Get timestamp of current month start
     *
     * @return integer
     */
    protected function getMonthStartTime()
    {
        return mktime(0, 0, 0, date('m', LC_START_TIME), 1, date('Y', LC_START_TIME));
    }

    /**
     * Get timestamp of current year start
     *
     * @return integer
     */
    protected function getYearStartTime()
    {
        return mktime(0, 0, 0, 1, 1, date('Y', LC_START_TIME));
    }

    /**
     * Get start time for all dates condition
     *
     * @return integer
     */
    protected function getAllStartTime()
    {
        return 0;
    }

    /**
     * Get start time for all dates condition
     *
     * @return integer
     */
    protected function getDefaultStartTime()
    {
        return 0;
    }

    // }}}
}
