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

namespace XLite\Module\CDev\ProductOptions\Model\Repo;

/**
 * Option exception repository
 *
 */
class OptionException extends \XLite\Model\Repo\ARepo
{
    // {{{ checkOptions

    /**
     * Check options ids list
     *
     * @param array $ids Option id list
     *
     * @return boolean
     */
    public function checkOptions(array $ids)
    {
        $count = 0;
        if (!empty($ids)) {
            try {
                $count = $this->defineCheckExceptionQuery($ids)
                    ->getSingleScalarResult();
                
                $count = intval($count);

            } catch (\Doctrine\ORM\NoResultException $exception) {
            } catch (\Doctrine\ORM\NonUniqueResultException $exception) {
                $count = 1;
            }
        }

        return 0 == $count;
    }

    /**
     * Define check exception query
     *
     * FIXME: decompose; move query definition to a separate method
     *
     * @param array $ids Option ids list
     *
     * @return \Doctrine\ORM\NativeQuery
     */
    protected function defineCheckExceptionQuery(array $ids)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('cnt', 'cnt');

        $keys = array();
        $parameters = array();
        foreach ($ids as $id) {
            $keys[] = ':id' . $id;
            $parameters['id' . $id] = $id;
        }

        $query = $this->_em->createNativeQuery(
            'SELECT COUNT(e1.option_id) as cnt '
            . 'FROM ' . $this->_class->getTableName() . ' as e1 '
            . 'WHERE e1.option_id IN (' . implode(', ', $keys) . ') '
            . 'GROUP BY e1.exception_id '
            . 'HAVING cnt = ('
            . 'SELECT COUNT(e2.option_id) '
            . 'FROM ' . $this->_class->getTableName() . ' as e2 '
            . 'WHERE e2.exception_id = e1.exception_id '
            . 'GROUP BY e2.exception_id'
            . ') '
            . 'LIMIT 1',
            $rsm
        );
        foreach ($parameters as $key => $value) {
            $query->setParameter($key, $value);
        }

        return $query;
    }

    // }}}

    // {{{ getNextExceptionId

    /**
     * Get next free exception id
     *
     * @return integer
     */
    public function getNextExceptionId()
    {
        return intval($this->defineNextExceptionIdQuery()->getSingleScalarResult()) + 1;
    }

    /**
     * Define query builder for getNextExceptionId() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineNextExceptionIdQuery()
    {
        return $this->createQueryBuilder()
            ->select('MAX(o.exception_id)');
    }

    // }}}

    // {{{ findByExceptionId

    /**
     * Find exceptions by exception id
     *
     * @param integer $exceptionId Exception id
     *
     * @return array
     */
    public function findByExceptionId($exceptionId)
    {
        return $this->defineByExceptionIdQuery($exceptionId)->getResult();
    }

    /**
     * Define query builder for findByExceptionId() method
     *
     * @param integer $exceptionId Exception id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineByExceptionIdQuery($exceptionId)
    {
        return $this->createQueryBuilder()
            ->andWhere('o.exception_id = :exceptionId')
            ->setParameter('exceptionId', $exceptionId);
    }

    // }}}

    // {{{ findByExceptionIds

    /**
     * Find exceptions by exception ids list
     *
     * @param array $exceptionIds Exception ids list
     *
     * @return array
     */
    public function findByExceptionIds(array $exceptionIds)
    {
        return $this->defineByExceptionIdsQuery($exceptionIds)->getResult();
    }

    /**
     * Define query builder for findByExceptionIds() method
     *
     * @param array $exceptionIds Exception ids list
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineByExceptionIdsQuery(array $exceptionIds)
    {
        $qb = $this->createQueryBuilder();

        $keys = \XLite\Core\Database::buildInCondition($qb, $exceptionIds);

        return $qb->andWhere('o.exception_id IN (' . implode(', ', $keys) . ')');
    }

    // }}}
}
