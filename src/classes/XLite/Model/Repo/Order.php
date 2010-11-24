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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model\Repo;

/**
 * Order repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
    const P_EMAIL      = 'email';
    const P_STATUS     = 'status';
    const P_DATE       = 'date';
    const P_ORDER_BY   = 'orderBy';
    const P_LIMIT      = 'limit';


    /**
     * currentSearchCnd 
     * 
     * @var    \XLite\Core\CommonCell
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentSearchCnd = null;

    /**
     * Return list of handling search params 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::P_ORDER_ID,
            self::P_PROFILE_ID,
            self::P_EMAIL,
            self::P_STATUS,
            self::P_DATE,
            self::P_ORDER_BY,
            self::P_LIMIT,
        );
    }

    /**
     * Check if param can be used for search
     * 
     * @param string $param name of param to check
     *  
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param integer                    $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param integer                    $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndProfileId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $queryBuilder->andWhere('o.orig_profile_id = :profile_id')
                ->setParameter('profile_id', $value);
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param string                     $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndEmail(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            // TODO - uncomment after the "Profile" model will support ORM
            // $queryBuilder
            //    ->innerJoin('o.profile', 'p')
            //    ->andWhere('p.login = :email')
            //    ->setParameter('email', $value);
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param string                     $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndStatus(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!is_null(\XLite\Model\Order::getAllowedStatuses($value))) {
            $queryBuilder->andWhere('o.status = :status')
                ->setParameter('status', $value);

        } else {
            // TODO - add throw exception
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param array                      $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndDate(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value = null)
    {
        if (2 == count($value)) {
            list($start, $end) = $value;

            $queryBuilder->andWhere('o.date >= :start')
                ->andWhere('o.date <= :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end);
        }
    }

    /**
     * Return order TTL
     * 
     * @return integer 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getOrderTTL()
    {
        return self::ORDER_TTL;
    }

    /**     
     * Define query for findAllExipredTemporaryOrders() method
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllExpiredTemporaryOrdersQuery()
    {
        return $this->createQueryBuilder(null, false)
            ->andWhere('o.status = :tempStatus AND o.date < :time')
            ->setParameter('tempStatus', \XLite\Model\Order::STATUS_TEMPORARY)
            ->setParameter('time', time() - $this->getOrderTTL());
    }

    /**
     * Find all expired temporary orders 
     * 
     * @return \Doctrine\Common\Collection\ArrayCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllExipredTemporaryOrders()
    {
        return $this->defineAllExpiredTemporaryOrdersQuery()
            ->getQuery()
            ->getResult();
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string  $alias      Table alias
     * @param boolean $placedOnly Use only orders or orders + carts
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function collectGarbage()
    {
        $this->defineCollectGarbageQuery()->getQuery()->execute();
    }

    /**
     * Define query for collectGarbage() method
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCollectGarbageQuery()
    {
        // Use pure createQueryBuilder(), without local changes
        return $this->_em
            ->createQueryBuilder()
            ->delete($this->_entityName, 'o')
            ->andWhere('o.status = :tempStatus AND o.date < :time')
            ->setParameter('tempStatus', \XLite\Model\Order::STATUS_TEMPORARY)
            ->setParameter('time', time() - $this->getOrderTTL());
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param array                      $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        list($sort, $order) = $value;

        $queryBuilder
            ->addOrderBy($sort, $order);
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param array                      $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        array_unshift($value, $queryBuilder);
        call_user_func_array(array($this, 'assignFrame'), $value);
    }

    /**
     * Call corresponded method to handle a serch condition
     * 
     * @param mixed                      $value        condition data
     * @param string                     $key          condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * Common search
     * 
     * @param \XLite\Core\CommonCell $cnd       search condition
     * @param boolean                $countOnly return items list or only its size
     *  
     * @return \Doctrine\ORM\PersistentCollection or int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            if (self::P_LIMIT != $key || !$countOnly) {
                $this->callSearchConditionHandler($value, $key, $queryBuilder);
            }
        }

        if ($countOnly) {
            $queryBuilder->select('COUNT(o.order_id)');

            try {
                $result = intval($queryBuilder->getQuery()->getSingleScalarResult());

            } catch (\Doctrine\ORM\NoResultException $exception) {
                $result = 0;
            }

        } else {
            $result = $queryBuilder->getQuery()->getResult();
        }

        return $result;
    }
}
