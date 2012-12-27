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

namespace XLite\View\Order\Statistics;

/**
 * Orders summary mini informer (used on Dashboard page)
 *
 */
class MiniInformer extends \XLite\View\Dialog
{
    /**
     * Values of period parameter
     */
    const T_DAY      = 'day';
    const T_WEEK     = 'week';
    const T_MONTH    = 'month';
    const T_LIFETIME = 'lifetime';

    /**
     * Names of tab sections
     */
    const P_ORDERS  = 'orders';
    const P_REVENUE = 'revenue';

    /**
     * No statistics flag
     *
     * @var boolean
     */
    protected $emptyStats = false;


    /**
     * Add widget specific CSS file
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

    /**
     * Add widget specific JS-file
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }

 
    /**
     * Return widget templates directory
     *
     * @return string
     */
    protected function getDir()
    {
        return 'order/statistics/informer';
    }

    /**
     * Define tabs
     *
     * @return array
     */
    protected function defineTabs()
    {
        return array(
            self::T_DAY => array(
                'name' => 'Day',
                'template' => 'order/statistics/informer/content.tpl',
            ),
            self::T_WEEK => array(
                'name' => 'Week',
                'template' => 'order/statistics/informer/content.tpl',
            ),
            self::T_MONTH => array(
                'name' => 'Month',
                'template' => 'order/statistics/informer/content.tpl',
            ),
            self::T_LIFETIME => array(
                'name' => 'Lifetime',
                'template' => 'order/statistics/informer/content.tpl',
            ),
        );
    }

    /**
     * Process tabs
     *
     * @param array $tabs Tabs
     *
     * @return array
     */
    protected function postprocessTabs($tabs)
    {
        foreach ($tabs as $k => $tab) {
            $tabs[$k] = array_merge($tab, $this->getOrdersSummary($k));
        }

        return $tabs;
    }

    /**
     * Get orders summary statistics
     *
     * @param string $key Period name
     *
     * @return array
     */
    protected function getOrdersSummary($key)
    {
        $result = array(
            'orders' => array(
                'value' => 0,
                'prev'  => 0,
            ),
            'revenue' => array(
                'value' => 0,
                'prev'  => 0,
            ),
        );

        $now = time();

        switch ($key) {
            case self::T_DAY:
                $startDate = mktime(0, 0, 0, date('m', $now), date('d', $now), date('Y', $now));
                $prevStartDate = $startDate - 86400;
                break;

            case self::T_WEEK:
                $startDate = $now - (date('w', $now) * 86400);
                $prevStartDate = $startDate - 7 * 86400;
                break;

            case self::T_MONTH:
                $startDate = mktime(0, 0, 0, date('m', $now), 1, date('Y', $now));
                $prevStartDate = mktime(0, 0, 0, date('m', $now) - 1, 1, date('Y', $now));
                break;

            case self::T_LIFETIME:
            default:
                $startDate = 0;
                $prevStartDate = 0;
        }

        $thisPeriod = \XLite\Core\Database::getRepo('XLite\Model\Order')->getOrderStats($startDate);

        $result['orders']['value'] = $thisPeriod['orders_count'];
        $result['revenue']['value'] = $thisPeriod['orders_total'];

        if (self::T_LIFETIME != $key) {

            $prevPeriod = \XLite\Core\Database::getRepo('XLite\Model\Order')->getOrderStats($prevStartDate, $startDate - 1);

            $result['orders']['prev'] = $prevPeriod['orders_count'];
            $result['revenue']['prev'] = $prevPeriod['orders_total'];

        } elseif (0 == $result['orders']['value']) {
            $this->emptyStats = true;
        }

        return $result;
    }

    /**
     * Prepare tabs
     *
     * @return array
     */
    protected function getTabs()
    {
        $tabs = $this->defineTabs();

        $i = 0;
        foreach ($tabs as $k => $tab) {
            $tabs[$k]['index'] = $i;
            $tabs[$k]['id']    = sprintf('order-stat-informer-tab-%d', $i);
            $tabs[$k]['class'] = $k;
            $i++;
        }

        return $this->postprocessTabs($tabs);
    }

    /**
     * Get tab style (inline)
     *
     * @param array $tab Tab data cell
     *
     * @return string
     */
    protected function getTabStyle(array $tab)
    {
        return $this->isTabActive($tab) ? '' : 'display: none;';
    }

    /**
     * Return true if tab is active
     *
     * @param array $tab Tab data cell
     *
     * @return boolean
     */
    protected function isTabActive(array $tab)
    {
        return 0 === $tab['index'];
    }

    /**
     * Get tab style (CSS classes)
     *
     * @param array $tab Tab data cell
     *
     * @return string
     */
    protected function getTabClass(array $tab)
    {
        $style = !empty($tab['style']) ? $tab['style'] : '';

        return $style . ($this->isTabActive($tab) ? ' active' : '');
    }

    /**
     * Get class name as a mark of delta between current value and value for previous period
     *
     * @param array  $tab       Tab data cell
     * @param string $valueType Value type (order or revenue)
     *
     * @return string
     */
    protected function getDeltaType(array $tab, $valueType)
    {
        if ($tab[$valueType]['prev'] == $tab[$valueType]['value'] || self::T_LIFETIME == $tab['class']) {
            $deltaType = 'zero';

        } elseif ($tab[$valueType]['prev'] > $tab[$valueType]['value']) {
            $deltaType = 'negative';

        } else {
            $deltaType = 'positive';
        }

        return $deltaType;
    }

    /**
     * Return true if no statistics
     *
     * @return boolean
     */
    protected function isEmptyStats()
    {
        return $this->emptyStats;
    }

    /**
     * Get string for previous value
     *
     * @param array  $tab       Tab data cell
     * @param string $valueType Value type (order or revenue)
     *
     * @return string
     */
    protected function getPrevValue(array $tab, $valueType)
    {
        if (0 == $tab[$valueType]['prev'] && 'order' == $valueType) {
            $result = $this->t(sprintf('No %s on last %s', $valueType, $tab['class']));

        } else {
            $result = $this->t(
                sprintf('X %s on last %s', $valueType, $tab['class']),
                array(
                    'value' => ('revenue' == $valueType)
                        ? $this->formatValue($tab[$valueType]['prev'])
                        : $tab[$valueType]['prev']
                )
            );
        }

        return $result;
    }

    /**
     * Get formatted currency value (e.g. $10K instead of $10,000.00)
     *
     * @param float $value Value
     *
     * @return string
     */
    protected function formatValue($value)
    {
        $suffixes = array('', 'K', 'M', 'B', 'T');

        $i = 0;
        while ($value > 1000) {
            $value = $value / 1000;
            $i++;
        }

        if (0 < $i) {
            $currency = \XLite::getInstance()->getCurrency();
            $parts = $currency->formatParts($value);
            unset($parts['decimalDelimiter']);
            unset($parts['decimal']);

            $parts['suffix'] = !empty($parts['suffix']) ? $suffixes[$i] . $parts['suffix'] : $suffixes[$i];

            $result = implode('', $parts);

        } else {
            $result = $this->formatPrice($value);
        }

        return $result;
    }

    /**
     * Return true if specified tab is for lifetime period
     *
     * @param array $tab Tab data cell
     *
     * @return boolean
     */
    protected function isLifetimeTab(array $tab)
    {
        return !$this->isEmptyStats() && self::T_LIFETIME == $tab['class'];
    }

    /**
     * Return true if value for prev period should be displayed
     *
     * @param array $tab Tab data cell
     *
     * @return boolean
     */
    protected function isDisplayPrevValue(array $tab)
    {
        return !$this->isEmptyStats() && self::T_LIFETIME != $tab['class'];
    }

    /**
     * Get block style
     *
     * @return string
     */
    protected function getBlockStyle()
    {
        return '';
    }
}
