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

namespace XLite\View\Product;

/**
 * Top sellers block widget 
 * 
 */
class TopSellersBlock extends \XLite\View\Dialog
{
    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'product/top_sellers';
    }

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
     * Add widget specific JS files
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
     * Get options for selector (allowed periods)
     *
     * @return array
     */
    protected function getOptions()
    {
        return \XLite\View\ItemsList\Model\Product\Admin\TopSellers::getAllowedPeriods();
    }

    /**
     * Return true if current period is a default
     *
     * @param string $period Period name
     *
     * @return string
     */
    protected function isDefaultPeriod($period)
    {
        return \XLite\View\ItemsList\Model\Product\Admin\TopSellers::P_PERIOD_WEEK == $period;
    }

    /**
     * Return true if there are no statistics for lifetime period
     *
     * @return boolean
     */
    protected function isEmptyStats()
    {
        $cnd = new \XLite\Core\CommonCell();
        $cnd->currency = \XLite::getCurrency()->getCurrencyId();

        $count = \XLite\Core\Database::getRepo('\XLite\Model\OrderItem')->getTopSellers($cnd, true);

        return 0 == $count;
    }
}
