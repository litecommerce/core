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

namespace XLite\Model\Repo;

/**
 * Order repository
 *
 */
class Order extends \XLite\Model\Repo\ARepo
{
    /**
     * Cart TTL (in seconds)
     */
    const ORDER_TTL = 86400;

    /**
     * Allowable search params
     */

    const P_ORDER_ID   = 'orderId';
    const P_PROFILE_ID = 'profileId';
    const P_PROFILE    = 'profile';
    const P_EMAIL      = 'email';
    const P_STATUS     = 'status';
    const P_DATE       = 'date';
    const P_CURRENCY   = 'currency';
    const P_ORDER_BY   = 'orderBy';
    const P_LIMIT      = 'limit';


    /**
     * currentSearchCnd
     *
     * @var \XLite\Core\CommonCell
     */
    protected $currentSearchCnd = null;


    /**
     * Find all expired temporary orders
     *
     * @return \Doctrine\Common\Collection\ArrayCollection
     */
    public function findAllExpiredTemporaryOrders()
    {
        return $this->defineAllExpiredTemporaryOrdersQuery()->getResult();
    }

    /**
     * Get orders statistics data: count and sum of orders
     *
     * @param integer $startDate Start date timestamp
     * @param integer $endDate   End date timestamp OPTIONAL
     *
     * @return array
     */
    public function getOrderStats($startDate, $endDate = 0)
    {
        $result = $this->defineGetOrderStatsQuery($startDate, $endDate)->getSingleResult();

        return $result;
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string  $alias      Table alias OPTIONAL
     * @param boolean $placedOnly Use only orders or orders + carts OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder($alias = null, $placedOnly = true)
    {
        $result = parent::createQueryBuilder($alias);

        if ($placedOnly) {
            $result->andWhere('o.status != :tempStatus')
                ->setParameter('tempStatus', \XLite\Model\Order::STATUS_TEMPORARY);
        }

        return $result;
    }

    /**
     * Orders collect garbage
     *
     * @return void
     */
    public function collectGarbage()
    {
        $list = $this->findAllExpiredTemporaryOrders();
        if (count($list)) {
            foreach ($list as $order) {
                \XLite\Core\Database::getEM()->remove($order);
            }

            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder()
            ->innerJoin('o.profile', 'p')
            ->leftJoin('o.orig_profile', 'op');
        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            if (self::P_LIMIT != $key || !$countOnly) {
                $this->callSearchConditionHandler($value, $key, $queryBuilder);
            }
        }

        if ($countOnly) {
            $queryBuilder->select('COUNT(o.order_id)');
            $result = intval($queryBuilder->getSingleScalarResult());

        } else {
            $result = $queryBuilder->getResult();
        }

        return $result;
    }


    /**
     * Create a QueryBuilder instance for getOrderStats()
     *
     * @param integer $startDate Start date timestamp
     * @param integer $endDate   End date timestamp
     *
     * @return \Doctrine\ORM\QueryBuilder
     */

    protected function defineGetOrderStatsQuery($startDate, $endDate)
    {
        $qb = $this->createQueryBuilder()
            ->select('COUNT(o.order_id) as orders_count')
            ->addSelect('SUM(o.total) as orders_total');

        $this->prepareCndDate($qb, array($startDate, $endDate));
        $this->prepareCndStatus($qb, $this->getStatusesForStats());

        return $qb;
    }

    /**
     * Get allowed order statuses list for getOrderStats()
     *
     * @return array
     */
    protected function getStatusesForStats()
    {
        $statuses = array_keys(\XLite\Model\Order::getAllowedStatuses());

        $exclude = array(
            \XLite\Model\Order::STATUS_TEMPORARY,
            \XLite\Model\Order::STATUS_INPROGRESS,
            \XLite\Model\Order::STATUS_FAILED,
            \XLite\Model\Order::STATUS_DECLINED,
        );

        foreach ($statuses as $k => $v) {
            if (in_array($v, $exclude)) {
                unset($statuses[$k]);
            }
        }

        return $statuses;
    }

    /**
     * Return list of handling search params
     *
     * @return array
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::P_ORDER_ID,
            self::P_PROFILE_ID,
            self::P_PROFILE,
            self::P_EMAIL,
            self::P_STATUS,
            self::P_DATE,
            self::P_CURRENCY,
            self::P_ORDER_BY,
            self::P_LIMIT,
        );
    }

    /**
     * Check if param can be used for search
     *
     * @param string $param Name of param to check
     *
     * @return boolean
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param integer                    $value        Condition data
     *
     * @return void
     */
    protected function prepareCndOrderId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $queryBuilder->andWhere('o.order_id = :order_id')
                ->setParameter('order_id', $value);
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param \XLite\Model\Profile       $value        Profile
     *
     * @return void
     */
    protected function prepareCndProfile(\Doctrine\ORM\QueryBuilder $queryBuilder, \XLite\Model\Profile $value)
    {
        if (!empty($value)) {
            $queryBuilder->andWhere('op.profile_id = :opid')
                ->setParameter('opid', $value->getProfileId());
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param integer                    $value        Condition data
     *
     * @return void
     */
    protected function prepareCndProfileId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $value = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($value);
            $queryBuilder->andWhere('o.orig_profile = :orig_profile')
                ->setParameter('orig_profile', $value);
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     *
     * @return void
     */
    protected function prepareCndEmail(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $queryBuilder->andWhere('p.login LIKE :email')
                ->setParameter('email', '%' . $value . '%');
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     *
     * @return void
     */
    protected function prepareCndStatus(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {

            if (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in('o.status', $value));

            } else {
                $queryBuilder->andWhere('o.status = :status')
                    ->setParameter('status', $value);
            }
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data OPTIONAL
     *
     * @return void
     */
    protected function prepareCndDate(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value = null)
    {
        if (is_array($value)) {
            $value = array_values($value);
            $start = empty($value[0]) ? null : intval($value[0]);
            $end = empty($value[1]) ? null : intval($value[1]);

            if ($start) {
                $queryBuilder->andWhere('o.date >= :start')
                    ->setParameter('start', $start);
            }

            if ($end) {
                $queryBuilder->andWhere('o.date <= :end')
                    ->setParameter('end', $end);
            }
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param integer                    $value        Condition data OPTIONAL
     *
     * @return void
     */
    protected function prepareCndCurrency(\Doctrine\ORM\QueryBuilder $queryBuilder, $value = null)
    {
        if ($value) {
            $queryBuilder->innerJoin('o.currency', 'currency', 'WITH', 'currency.currency_id = :currency_id')
                ->setParameter('currency_id', $value);
        }
    }

    /**
     * Return order TTL
     *
     * @return integer
     */
    protected function getOrderTTL()
    {
        return self::ORDER_TTL;
    }

    /**
     * Define query for findAllExpiredTemporaryOrders() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllExpiredTemporaryOrdersQuery()
    {
        return $this->createQueryBuilder(null, false)
            ->andWhere('o.status = :tempStatus AND o.date < :time')
            ->setParameter('tempStatus', \XLite\Model\Order::STATUS_TEMPORARY)
            ->setParameter('time', time() - $this->getOrderTTL());
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        list($sort, $order) = $value;

        $queryBuilder->addOrderBy($sort, $order);
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     */
    protected function prepareCndLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        array_unshift($value, $queryBuilder);
        call_user_func_array(array($this, 'assignFrame'), $value);
    }

    /**
     * Call corresponded method to handle a serch condition
     *
     * @param mixed                      $value        Condition data
     * @param string                     $key          Condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     *
     * @return void
     */
    protected function callSearchConditionHandler($value, $key, \Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        if ($this->isSearchParamHasHandler($key)) {
            $methodName = 'prepareCnd' . ucfirst($key);
            // $methodName is assembled from 'prepareCnd' + key
            $this->$methodName($queryBuilder, $value);

        } else {
            // TODO - add logging here
        }
    }

    /**
     * Get detailed foreign keys
     *
     * @return array
     */
    protected function getDetailedForeignKeys()
    {
        return array(
            array(
                'fields'          => array('orig_profile_id'),
                'referenceRepo'   => 'XLite\Model\Profile',
                'referenceFields' => array('profile_id'),
                'delete'          => 'SET NULL',
            ),
        );
    }

    /**
     * Delete single entity
     *
     * @param \XLite\Model\AEntity $entity Entity to detach
     *
     * @return void
     */
    protected function performDelete(\XLite\Model\AEntity $entity)
    {
        $entity->setOldStatus(null);

        parent::performDelete($entity);
    }
}
