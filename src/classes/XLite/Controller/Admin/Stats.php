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
 * Store statisics page controller
 * 
 * @see   ____class_see____
 * @since 3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected $stats = null;

    
    /**
     * Return the current page title (for the content area)
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return $this->t('Statistics');
    }

    /**
     * Prepare statistics table
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStats()
    {
        if (is_null($this->stats)) {
            $this->stats = $this->initStats();
            array_map(array($this, 'processStatsRecord'), $this->getData());
        }

        return $this->stats;
    }

    /**
     * Get column headings
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getColumnTitle($column)
    {
        $titles = $this->getColumnTitles();

        return !isset($titles[$column]) ?: $titles[$column];
    }

    /**
     * Get row heading
     *
     * @param string $row Row identificator
     *
     * @return array|string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRowTitle($row)
    {
        $titles = $this->getRowTitles();

        return !isset($titles[$row]) ?: $titles[$row];
    }


    // {{{ Common functions
    
    /**
     * Get rows for statistics table
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStatsRows()
    {
        return array();
    }

    /**
     * Get columns for statistics table
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStatsColumns()
    {
        return $this->getTimeIntervals();
    }

    /**
     * Initialize table matrix
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
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
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSearchCondition()
    {
        $cnd = new \XLite\Core\CommonCell();
        
        $cnd->date = array(
            $this->getStartTime(array_pop($this->getTimeIntervals())),
            LC_START_TIME
        );

        return $cnd;
    }

    /**
     * Get search data
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTodayStartTime()
    {
        return mktime(0, 0, 0, date('m', LC_START_TIME), date('d', LC_START_TIME), date('Y', LC_START_TIME));
    }

    /**
     * Get timestamp of current week start
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getWeekStartTime()
    {
        return LC_START_TIME - (date('w', LC_START_TIME) * 86400);
    }

    /**
     * Get timestamp of current month start
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMonthStartTime()
    {
        return mktime(0, 0, 0, date('m', LC_START_TIME), 1, date('Y', LC_START_TIME));
    }

    /**
     * Get timestamp of current year start
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getYearStartTime()
    {
        return mktime(0, 0, 0, 1, 1, date('Y', LC_START_TIME));
    }

    /**
     * Get start time for all dates condition
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAllStartTime()
    {
        return 0;
    }

    /**
     * Get start time for all dates condition
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultStartTime()
    {
        return 0;
    }

    // }}}
}
