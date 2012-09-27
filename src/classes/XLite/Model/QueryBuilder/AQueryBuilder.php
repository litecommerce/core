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

namespace XLite\Model\QueryBuilder;

/**
 * Abstract query builder
 *
 */
abstract class AQueryBuilder extends \Doctrine\ORM\QueryBuilder
{

    /**
     * Service flags 
     * 
     * @var array
     */
    protected $flags = array();

    /**
     * Linked joins 
     * 
     * @var array
     */
    protected $joins = array();

    // {{{ Result helpers

    /**
     * Get result
     *
     * @return array
     */
    public function getResult()
    {
        return $this->getQuery()->getResult();
    }

    /**
     * Get result as object array.
     *
     * @return array
     */
    public function getObjectResult()
    {
        $result = array();

        foreach ($this->getResult() as $idx => $item) {
            $result[$idx] = is_object($item) ? $item : $item[0];
        }

        return $result;
    }

    /**
     * Get result as array
     *
     * @return array
     */
    public function getArrayResult()
    {
        return $this->getQuery()->getArrayResult();
    }

    /**
     * Get single result
     *
     * @return \XLite\Model\AEntity|void
     */
    public function getSingleResult()
    {
        try {
            $entity = $this->getQuery()->getSingleResult();

        } catch (\Doctrine\ORM\NonUniqueResultException $exception) {
            $entity = null;

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $entity = null;
        }

        return $entity;
    }

    /**
     * Get single scalar result
     *
     * @return mixed
     */
    public function getSingleScalarResult()
    {
        try {
            $scalar = $this->getQuery()->getSingleScalarResult();

        } catch (\Doctrine\ORM\NonUniqueResultException $exception) {
            $scalar = null;

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $scalar = null;
        }

        return $scalar;
    }

    /**
     * Get iterator
     *
     * @return \Iterator
     */
    public function iterate()
    {
        return $this->getQuery()->iterate();
    }

    /**
     * Execute query
     *
     * @return mixed
     */
    public function execute()
    {
        return $this->getQuery()->execute();
    }

    /**
     * Get only entities
     *
     * @return array
     */
    public function getOnlyEntities()
    {
        $result = array();

        foreach ($this->getResult() as $entity) {

            if (is_array($entity)) {
                $entity = $entity[0];
            }

            $result[] = $entity;
        }

        return $result;
    }


    // }}}

    // {{{ Service flags

    /**
     * Get service flag 
     * 
     * @param string $name Flag name
     *  
     * @return mixed
     */
    public function getFlag($name)
    {
        return isset($this->flags[$name]) ? $this->flags[$name] : null;
    }

    /**
     * Set service flag 
     * 
     * @param string $name  Flag name
     * @param mixed  $value Value OPTIONAL
     *  
     * @return void
     */
    public function setFlag($name, $value = true)
    {
        $this->flags[$name] = $value;
    }

    // }}}

    // {{{ Query builder helpers

    /**
     * Link association as inner join
     * 
     * @param string $join  The relationship to join
     * @param string $alias The alias of the join OPTIONAL
     *  
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    public function linkInner($join, $alias = null)
    {
        if (!$alias) {
            list($main, $alias) = explode('.', $join, 2);
        }

        if (!in_array($alias, $this->joins)) {
            $this->innerJoin($join, $alias);
            $this->joins[] = $alias;
        }

        return $this;
    }

    /**
     * Link association as left join
     *
     * @param string $join  The relationship to join
     * @param string $alias The alias of the join OPTIONAL
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    public function linkLeft($join, $alias = null)
    {
        if (!$alias) {
            list($main, $alias) = explode('.', $join, 2);
        }

        if (!in_array($alias, $this->joins)) {
            $this->leftJoin($join, $alias);
            $this->joins[] = $alias;
        }

        return $this;
    }

    /**
     * Get IN () condition
     * 
     * @param array  $data   Data
     * @param string $prefix Parameter prefix OPTIONAL
     *  
     * @return string
     */
    public function getInCondition(array $data, $prefix = 'id')
    {
        $keys = \XLite\Core\Database::buildInCondition($this, $data, $prefix);

        return implode(', ', $keys);
    }

    // }}}

}
