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

namespace XLite\Module\CDev\Bestsellers\Model\Repo;

/**
 * The "OrderItem" model repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Product extends \XLite\Model\Repo\Product implements \XLite\Base\IDecorator
{
    /**
     * Defines bestsellers products collection
     *
     * @param \XLite\Core\CommonCell $cnd   Search condition
     * @param integer                $count Number of products to get OPTIONAL
     * @param integer                $cat   Category identificator OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findBestsellers(\XLite\Core\CommonCell $cnd, $count = 0, $cat = 0)
    {
        list($sort, $order) = $cnd->{self::P_ORDER_BY};

        return $this->defineBestsellersQuery($cnd, $count, $cat)->getOnlyEntities();
    }

    /**
     * Prepares query builder object to get bestsell products
     *
     * @param \XLite\Core\CommonCell $cnd   Search condition
     * @param integer                $count Number of products to get
     * @param integer                $cat   Category identificator
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder object
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineBestsellersQuery(\XLite\Core\CommonCell $cnd, $count, $cat)
    {
        list($sort, $order) = $cnd->{self::P_ORDER_BY};

        $qb = $this->createQueryBuilder()
            ->linkInner('p.order_items', 'o')
            ->linkInner('o.order', 'ord')
            ->addSelect('sum(o.amount) as product_amount')
            ->andWhere('ord.status IN (:complete_status, :authorized_status, :processed_status)')
            ->groupBy('o.object')
            ->addOrderBy($sort, $order)
            ->addOrderBy('product_amount', 'DESC')
            ->setParameter('complete_status', \XLite\Model\Order::STATUS_COMPLETED)
            ->setParameter('authorized_status', \XLite\Model\Order::STATUS_AUTHORIZED)
            ->setParameter('processed_status', \XLite\Model\Order::STATUS_PROCESSED);

        if (0 < $count) {
            $qb->setMaxResults($count);
        }

        if (0 < $cat) {
            $qb->linkLeft('p.categoryProducts', 'cp')
                ->linkLeft('cp.category', 'c');
            \XLite\Core\Database::getRepo('XLite\Model\Category')->addSubTreeCondition($qb, $cat);
        }

        return $qb;
    }

}
