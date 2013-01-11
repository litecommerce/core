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

namespace XLite\View\ItemsList\Model\Product\Admin;

/**
 * Top selling products list (for dashboard page)
 * 
 */
class TopSellers extends \XLite\View\ItemsList\Model\Product\Admin\LowInventoryBlock
{
    /**
     * Widget parameter name
     */
    const PARAM_PERIOD = 'period';
    const PARAM_PRODUCTS_LIMIT = 'products_limit';

    /**
     * Allowed values for PARAM_PERIOD parameter
     */
    const P_PERIOD_DAY      = 'day';
    const P_PERIOD_WEEK     = 'week';
    const P_PERIOD_MONTH    = 'month';
    const P_PERIOD_LIFETIME = 'lifetime';


    /**
     * Get allowed periods
     *
     * @return array
     */
    public static function getAllowedPeriods()
    {
        return array(
            self::P_PERIOD_DAY      => 'Last 24 hours',
            self::P_PERIOD_WEEK     => 'Last 7 days',
            self::P_PERIOD_MONTH    => 'Last month',
            self::P_PERIOD_LIFETIME => 'Store lifetime',
        );
    }


    /**
     * Hide 'More...' link
     *
     * @return null
     */
    public function getMoreLink()
    {
        return null;
    }

    /**
     * Hide 'More...' link
     *
     * @return null
     */
    public function getMoreLinkTitle()
    {
        return null;
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
            static::PARAM_PERIOD         => new \XLite\Model\WidgetParam\String('Period', self::P_PERIOD_LIFETIME),
            static::PARAM_PRODUCTS_LIMIT => new \XLite\Model\WidgetParam\Int('Number of products', 5),
        );
    }

    /**
     * Define items list columns
     *
     * @return array
     */
    protected function defineColumns()
    {
        $allowedColumns = array(
            'sku',
            'name',
            'sold',
        );

        $columns = parent::defineColumns();

        $columns['sold'] = array(
            static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Sold'),
            static::COLUMN_CLASS => 'XLite\View\FormField\Inline\Input\Text\Integer',
        );

        // Remove redundant columns
        foreach ($columns as $k => $v) {
            if (!in_array($k, $allowedColumns)) {
                unset($columns[$k]);
            }
        }

        return $columns;
    }

    /*
     * getEmptyListTemplate
     *
     * @return string
     */
    protected function getEmptyListTemplate()
    {
        return $this->getDir() . '/' . $this->getPageBodyDir() . '/product/empty_top_sellers_list.tpl';
    }

    /**
     * Get search conditions
     *
     * @return \XLite\Core\CommonCell
     */
    protected function getSearchCondition()
    {
        $cnd = new \XLite\Core\CommonCell();

        $cnd->date = array($this->getStartDate(), 0);

        $cnd->currency = \XLite::getCurrency()->getCurrencyId();

        $cnd->limit = $this->getParam(self::PARAM_PRODUCTS_LIMIT);

        return $cnd;
    }

    /**
     * Get period start date timestamp
     *
     * @return integer
     */
    protected function getStartDate()
    {
        $now = time();

        switch ($this->getParam(self::PARAM_PERIOD)) {
            case self::P_PERIOD_DAY:
                $startDate = mktime(0, 0, 0, date('m', $now), date('d', $now), date('Y', $now));
                break;

            case self::P_PERIOD_WEEK:
                $startDate = $now - (date('w', $now) * 86400);
                break;

            case self::P_PERIOD_MONTH:
                $startDate = mktime(0, 0, 0, date('m', $now), 1, date('Y', $now));
                break;

            case self::P_PERIOD_LIFETIME:
            default:
                $startDate = 0;
        }

        return $startDate;
    }

    /**
     * Get data for items list
     *
     * @param \XLite\Core\CommonCell $cnd       Search conditions
     * @param boolean                $countOnly Count only flag
     *
     * @return array
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $data = \XLite\Core\Database::getRepo('\XLite\Model\OrderItem')->getTopSellers($this->getSearchCondition(), $countOnly);

        if (!$countOnly) {

            foreach ($data as $item) {
                $product = $item[0]->getObject();
                $product->setSold($item['cnt']);

                $result[] = $product;
            }

        } else {
            $result = $data;
        }

        return $result;
    }
}

