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
 * Top sellers statistics page controller
 *
 */
class TopSellers extends \XLite\Controller\Admin\Stats
{
    /**
     * Number of positions
     */
    const TOP_SELLERS_NUMBER = 10;

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
     * getPageTemplate
     *
     * @return void
     */
    public function getPageTemplate()
    {
        return 'top_sellers.tpl';
    }

    /**
     * Get rows count in statistics
     *
     * @return integer
     */
    public function getRowsCount()
    {
        return self::TOP_SELLERS_NUMBER;
    }

    /**
     * Get columns for statistics table
     *
     * @return array
     */
    public function getStatsRows()
    {
        return array_keys(array_fill(0, $this->getRowsCount(), ''));
    }

    /**
     * Prepare statistics table
     *
     * @return array
     */
    public function getStats()
    {
        parent::getStats();

        $this->stats = $this->processData($this->getData());

        return $this->stats;
    }

    /**
     * Get data
     *
     * @return array
     */
    protected function getData()
    {
        $data = array();

        foreach ($this->getStatsColumns() as $interval) {
            $cnd = $this->getSearchCondition($interval);
            $cnd->limit = self::TOP_SELLERS_NUMBER;

            $currency = null;

            if (\XLite\Core\Request::getInstance()->currency) {
                $currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find(\XLite\Core\Request::getInstance()->currency);
            }

            if (!$currency) {
                $currency = \XLite::getInstance()->getCurrency();
            }

            $cnd->currency = $currency->getCurrencyId();
            $data[$interval] = \XLite\Core\Database::getRepo('\XLite\Model\OrderItem')->getTopSellers($cnd);
        }

        return $data;
    }

    /**
     * processData
     *
     * @param array $data Collected data
     *
     * @return void
     */
    protected function processData($data)
    {
        $stats = $this->stats;

        foreach ($this->stats as $rownum => $periods) {

            foreach ($periods as $period => $val) {

                $stats[$rownum][$period] = (
                    is_array($data[$period])
                    && \Includes\Utils\ArrayManager::getIndex($data[$period], $rownum)
                )
                    ? $data[$period][$rownum][0]
                    : null;
            }
        }

        return $stats;
    }
}
