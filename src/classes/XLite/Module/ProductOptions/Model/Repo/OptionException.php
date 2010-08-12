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

namespace XLite\Module\ProductOptions\Model\Repo;

/**
 * Option exception repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OptionException extends \XLite\Model\Repo\ARepo
{
    /**
     * Check options ids list
     * 
     * @param array $ids Option id list
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param array $ids Option ids list
     *  
     * @return \Doctrine\ORM\NativeQuery
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCheckExceptionQuery(array $ids)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping;
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

    /**
     * Get next free exception id 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNextExceptionId()
    {
        try {
            $max = $this->defineNextExceptionIdQuery()
                ->getQuery()
                ->getSingleScalarResult();
            $max = intval($max);

        } catch (\Doctrine\ORM\NonUniqueResultException $exception) {
            $max = 0;
        }

        return $max + 1;
    }

    /**
     * Define query builder for getNextExceptionId() method
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineNextExceptionIdQuery()
    {
        return $this->createQueryBuilder()
            ->select('MAX(o.exception_id)');
    }

    /**
     * Find exceptions by exception id 
     * 
     * @param integer $exceptionId Exception id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByExceptionId($exceptionId)
    {
        return $this->defineByExceptionIdQuery($exceptionId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Define query builder for findByExceptionId() method
     * 
     * @param integer $exceptionId Exception id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByExceptionIdQuery($exceptionId)
    {
        return $this->createQueryBuilder()
            ->andWhere('o.exception_id = :exceptionId')
            ->setParameter('exceptionId', $exceptionId);
    }

    /**
     * Find exceptions by exception ids list 
     * 
     * @param array $exceptionIds Exception ids list
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByExceptionIds(array $exceptionIds)
    {
        return $this->defineByExceptionIdsQuery($exceptionIds)
            ->getQuery()
            ->getResult();
    }

    /**
     * Define query builder for findByExceptionIds() method
     * 
     * @param array $exceptionIds Exception ids list
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByExceptionIdsQuery(array $exceptionIds)
    {
        $qb = $this->createQueryBuilder();

        $keys = \XLite\Core\Database::buildInCondition($qb, $exceptionIds);

        return $qb->andWhere('o.exception_id IN (' . implode(', ', $keys) . ')');
    }

}
