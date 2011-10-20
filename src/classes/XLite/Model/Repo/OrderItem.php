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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Repo;

/**
 * The "order_item" model repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class OrderItem extends \XLite\Model\Repo\ARepo
{
    /**
     * Get detailed foreign keys
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDetailedForeignKeys()
    {
        $list = parent::getDetailedForeignKeys();

        $list[] = array(
            'fields'          => array('object_id'),
            'referenceRepo'   => 'XLite\Model\Product',
            'referenceFields' => array('product_id'),
            'delete'          => 'SET NULL',
        );

        return $list;
    }

    // {{{ Functions to grab top selling products data

    /**
     * Get top sellers depending on certain condition
     *
     * @param \XLite\Core\CommonCell $cnd Conditions
     *
     * @return \Doctrine\ORM\PersistentCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTopSellers(\XLite\Core\CommonCell $cnd)
    {
        $queryBuilder = $this->createQueryBuilder();

        $this->prepareTopSellersCondition($queryBuilder, $cnd);

        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * Prepare top sellers search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param \XLite\Core\CommonCell     $cnd          Conditions
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareTopSellersCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, \XLite\Core\CommonCell $cnd)
    {
        list($start, $end) = $cnd->date;

        $queryBuilder->addSelect('SUM(o.amount) as cnt')
            ->innerJoin('o.order', 'o1')
            ->innerJoin('o1.currency', 'currency', 'WITH', 'currency.currency_id = :currency_id')
            ->addSelect('o1.date')
            ->andWhere('o1.date >= :start')
            ->setParameter('start', $start)
            ->andWhere('o1.date <= :end')
            ->setParameter('end', $end)
            ->andWhere('o1.status IN (:statusProcessed, :statusCompleted)')
            ->setParameter('statusProcessed', \XLite\Model\Order::STATUS_PROCESSED)
            ->setParameter('statusCompleted', \XLite\Model\Order::STATUS_COMPLETED)
            ->setParameter('currency_id', $cnd->currency)
            ->setMaxResults($cnd->limit)
            ->addGroupBy('o.sku')
            ->addOrderBy('cnt', 'desc')
            ->addOrderBy('o.name', 'asc');
    }

    // }}}
}
