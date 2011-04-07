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
 * Top sellers statistics page controller
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class TopSellers extends \XLite\Controller\Admin\Stats
{
    /**
     * Number of positions 
     */
    const TOP_SELLERS_NUMBER = 10;


    /**
     * getPageTemplate 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTemplate()
    {
        return 'top_sellers.tpl';
    }

    /**
     * Get rows count in statistics 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRowsCount()
    {
        return self::TOP_SELLERS_NUMBER;    
    }

    /**
     * Get columns for statistics table
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStatsRows()
    {
        return array_keys(array_fill(0, $this->getRowsCount(), ''));
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
        parent::getStats();

        $this->stats = $this->processData($this->getData());

        return $this->stats;
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
        return $this->t('Top sellers');
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

        $this->addLocationNode('Statistics', $this->buildURL('orders_stats'));
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
        $data = array();

        foreach ($this->getStatsColumns() as $interval) {

            $cnd = $this->getSearchCondition($interval);

            list($start, $end) = $cnd->date;

            $qb = \XLite\Core\Database::getRepo('\XLite\Model\OrderItem')->createQueryBuilder();

            $data[$interval] = $qb
                ->addSelect('SUM(o.amount) as cnt')
                ->innerJoin('o.order', 'o1')
                ->addSelect('o1.date')
                ->andWhere('o1.date >= :start')
                ->setParameter('start', $start)
                ->andWhere('o1.date <= :end')
                ->setParameter('end', $end)
                ->andWhere('o1.status IN (:statusProcessed, :statusCompleted)')
                ->setParameter('statusProcessed', \XLite\Model\Order::STATUS_PROCESSED)
                ->setParameter('statusCompleted', \XLite\Model\Order::STATUS_COMPLETED)
                ->setMaxResults(self::TOP_SELLERS_NUMBER)
                ->addGroupBy('o.sku')
                ->addOrderBy('cnt', 'desc')
                ->getQuery()
                ->getResult();
        }

        return $data;
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
    protected function processStatsRecord($data)
    {
        if (!empty($data)) {
            $this->collectStatsRecord($data);
        }
    }

    /**
     * processData 
     * 
     * @param array $data Collected data
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processData($data)
    {
        $stats = $this->stats;

        foreach ($this->stats as $rownum => $periods) {
            foreach ($periods as $period => $val) {
                $stats[$rownum][$period] = \Includes\Utils\ArrayManager::getIndex($data[$period], $rownum)
                    ? \Includes\Utils\ArrayManager::getIndex($data[$period][$rownum][0])
                    : null;
            }
        }

        return $stats;
    }
}
