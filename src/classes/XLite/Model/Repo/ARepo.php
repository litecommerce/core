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

use XLite\Core\Database as DB,
    XLite\Core\Converter;

/**
 * Abstract repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ARepo extends \Doctrine\ORM\EntityRepository
{
    /**
     * Cache TTL predefined values 
     */
    const INFINITY_TTL = 2592000;
    const LONG_TTL     = 86400;
    const SHORT_TTL    = 3600;


    const DEFAULT_TTL = self::SHORT_TTL;


    /**
     * Cache cell fields names
     */
    const TTL_CACHE_CELL      = 'ttl';
    const KEY_TYPE_CACHE_CELL = 'keyType';
    const ATTRS_CACHE_CELL    = 'attrs';


    /**
     *  Cache key types
     */
    const CACHE_ATTR_KEY       = 'attributesKey';
    const CACHE_HASH_KEY       = 'hashKey';
    const CACHE_CUSTOM_KEY     = 'customKey';
    const CONVERTER_CACHE_CELL = 'converter';
    const GENERATOR_CACHE_CELL = 'generator';


    const DEFAULT_KEY_TYPE = self::CACHE_ATTR_KEY;


    const EMPTY_CACHE_CELL = 'all';

    /**
     * Cache cells (local cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cacheCells = null;

    /**
     * Finder method name translation patterns
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $from = array(
        'Q', 'W', 'E', 'R', 'T',
        'Y', 'U', 'I', 'O', 'P',
        'A', 'S', 'D', 'F', 'G',
        'H', 'J', 'K', 'L', 'Z',
        'X', 'C', 'V', 'B', 'N',
        'M',
    );

    /**
     * Finder method name translation records
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $to = array(
        '_q', '_w', '_e', '_r', '_t',
        '_y', '_u', '_i', '_o', '_p',
        '_a', '_s', '_d', '_f', '_g',
        '_h', '_j', '_k', '_l', '_z',
        '_x', '_c', '_v', '_b', '_n',
        '_m',
    );

    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = null;

    /**
     * Default model alias 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultAlias = null;

    /**
     * Define cache cells 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCacheCells()
    {
        return array();
    }

    /**
     * Get cache cells 
     * 
     * @param string $key Cell name
     *  
     * @return array of cells / cell data
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCacheCells($key = null)
    {
        if (!isset($this->cacheCells)) {
            $this->cacheCells = $this->defineCacheCells();

            // Normalize cache cells
            foreach ($this->cacheCells as $name => $cell) {
                if (!isset($cell[self::TTL_CACHE_CELL])) {
                    $this->cacheCells[$name][self::TTL_CACHE_CELL] = self::DEFAULT_TTL;
                }

                if (!isset($cell[self::KEY_TYPE_CACHE_CELL])) {
                    $this->cacheCells[$name][self::KEY_TYPE_CACHE_CELL] = self::DEFAULT_KEY_TYPE;
                }

                if (!isset($cell[self::ATTRS_CACHE_CELL])) {
                    $this->cacheCells[$name][self::ATTRS_CACHE_CELL] = null;
                }

                $method = $this->getCacheParamsConverterName($name);
                $this->cacheCells[$name][self::CONVERTER_CACHE_CELL] = method_exists($this, $method)
                    ? $method
                    : false;

                if (self::CACHE_ATTR_KEY == $this->cacheCells[$name][self::KEY_TYPE_CACHE_CELL]) {
                    $this->cacheCells[$name][self::GENERATOR_CACHE_CELL] = $this->getCacheHashGeneratorName($name);
                }

            }
        }

        return $key
            ? (isset($this->cacheCells[$key]) ? $this->cacheCells[$key] : null)
            : $this->cacheCells;
    }

    /**
     * Assign cache options to query
     * 
     * @param \Doctrine\ORM\AbstractQuery $query  Query
     * @param string                     $name   Cell name
     * @param array                      $params Cell parameters
     *  
     * @return \Doctrine\ORM\AbstractQuery
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assignQueryCache(\Doctrine\ORM\AbstractQuery $query, $name, array $params = array())
    {
        if (DB::isCacheEnabled()) {
            $cell = $this->getCacheCells($name);
            if ($cell) {

                $query->useResultCache(
                    true,
                    $cell[self::TTL_CACHE_CELL],
                    $this->getCellHash($name, $cell, $params)
                );
            }
        }

        return $query;
    }

    /**
     * Get data from cache 
     * 
     * @param string $name   Cache cell name
     * @param array  $params Cache cell parameters
     *  
     * @return mixed or null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFromCache($name, array $params = array())
    {
        $result = null;

        if (DB::isCacheEnabled()) {
            $cell = $this->getCacheCells($name);
            if ($cell) {

                $result = DB::getCacheDriver()->fetch(
                    $this->getCellHash($name, $cell, $params)
                );
                if (false === $result) {
                    $result = null;
                }
            }
        }

        return $result;
    }

    /**
     * Savet data to cache 
     * 
     * @param mixed  $data   Data
     * @param string $name   Cache cell name
     * @param array  $params Cache cell parameters
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveToCache($data, $name, array $params = array())
    {
        if (DB::isCacheEnabled()) {
            $cell = $this->getCacheCells($name);
            if ($cell) {

                $hash = $this->getCellHash($name, $cell, $params);

                DB::getCacheDriver()->save(
                    $this->getCellHash($name, $cell, $params),
                    $data,
                    $cell[self::TTL_CACHE_CELL]
                );
            }
        }
    }


    /**
     * Get cell hash 
     * 
     * @param string $name   Cell name
     * @param array  $cell   Cell
     * @param array  $params Cache parameters
     *  
     * @return string or null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCellHash($name, array $cell, array $params)
    {
        $hash = null;

        if (self::CACHE_ATTR_KEY == $cell[self::KEY_TYPE_CACHE_CELL]) {

            $hash = implode('.', $params);

        } elseif (self::CACHE_HASH_KEY == $cell[self::KEY_TYPE_CACHE_CELL]) {

            $hash = md5(implode('.', $params));

        } elseif (self::CACHE_ATTR_KEY == $cell[self::KEY_TYPE_CACHE_CELL]) {

            $hash = $this->{$cell[self::GENERATOR_CACHE_CELL]}($params);
        }

        if (isset($hash) && empty($hash)) {
            $hash = self::EMPTY_CACHE_CELL;
        }

        return $this->_entityName . '.' . $name . '.' . $hash;
    }

    /**
     * Get full cache key 
     * 
     * @param string $cellName Cell name
     * @param string $key      Cell key
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTableHash($cellName = '', $key = '')
    {
        return $this->_entityName
            . ($cellName ? '.' . $cellName : '')
            . ($key ? '.' . $key : '');
    }

    /**
     * Get cell cache key generator method name 
     * 
     * @param string $name Cell name
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCacheHashGeneratorName($name)
    {
        return 'getCacheHash' . Converter::convertToCamelCase($name);
    }

    /**
     * Get cell cache parameters converter method name 
     * 
     * @param string $name Cell name
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCacheParamsConverterName($name)
    {
        return 'convertRecordToParams' . Converter::convertToCamelCase($name);
    }

    /**
     * Check - has repository any cache cells or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasCacheCells()
    {
        return DB::isCacheEnabled()
            && $this->getCacheCells();
    }

    /**
     * Delete cache by entity
     * 
     * @param \XLite\Model\AEntity $entity Record
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteCacheByEntity(\XLite\Model\AEntity $entity)
    {
        foreach ($this->getCacheCells() as $name => $cell) {
            if ($cell[self::CONVERTER_CACHE_CELL]) {
                   $params = $this->{$cell[self::CONVERTER_CACHE_CELL]}($entity);

            } elseif (
                is_array($cell[self::ATTRS_CACHE_CELL])
                && $cell[self::ATTRS_CACHE_CELL]
            ) {
                $params = array();
                foreach ($cell[self::ATTRS_CACHE_CELL] as $key) {
                    $params[$key] = $entity->$key;
                }

            } else {
                $params = array();
            }

            $hash = $this->getCellHash($name, $cell, $params);

            DB::getCacheDriver()->delete($hash);
        }
    }

    /**
     * Delete repository cache or cell cache
     * 
     * @param string $name Cell name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteCache($name = '')
    {
        if (DB::isCacheEnabled()) {
            DB::getCacheDriver()->deleteByPrefix($this->getTableHash($name) . '.');
        }
    }

    /**
     * Assign default orderBy 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param string                    $alias        Table short alias in query builder
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assignDefaultOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        if ($this->defaultOrderBy) {

            if (!isset($alias)) {
                $alias = $this->getMainAlias($qb);
            }

            if (is_string($this->defaultOrderBy)) {

                // One field
                $queryBuilder->addOrderBy($alias . '.' . $this->defaultOrderBy);

            } elseif (is_array($this->defaultOrderBy)) {

                // Many fields (field name => sort suffix)
                foreach ($this->defaultOrderBy as $field => $asc) {
                    $queryBuilder->addOrderBy($alias . '.' . $field, $asc ? 'ASC' : 'DESC');
                }

            }
        }

        return $queryBuilder;
    }

    /**
     * Get Query builder main alias 
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMainAlias(\Doctrine\ORM\QueryBuilder $qb)
    {
        $from = $qb->getDQLPart('from');
        $from = explode(' ', array_shift($from), 2);

        return isset($from[1]) ? $from[1] : $from[0];
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     * 
     * @param string $alias Table alias
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createQueryBuilder($alias = null)
    {
        $alias = $alias ?: $this->getDefaultAlias();

        return $this->assignDefaultOrderBy(parent::createQueryBuilder($alias), $alias);
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     * NOTE: without any relative subqueries!
     * 
     * @param string $alias Table alias
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createPureQueryBuilder($alias = null)
    {
        $alias = $alias ?: $this->getDefaultAlias();

        return $this->assignDefaultOrderBy(parent::createQueryBuilder($alias), $alias);
    }

    /**
     * getDefaultAlias 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultAlias()
    {
        if (!isset($this->defaultAlias)) {
            $list = explode('\\', $this->_entityName);
            $this->defaultAlias = strtolower(substr(array_pop($list), 0, 1));
        }

        return $this->defaultAlias;
    }

    /**
     * Count records
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function count()
    {
        try {
            $count = intval($this->defineCountQuery()->getQuery()->getSingleScalarResult());

        } catch (\Doctrine\ORM\NonUniqueResultException $exception) {
            $count = 0;
        }

        return $count;
    }

    /**
     * Define Query fo rcount() method
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCountQuery()
    {
        $qb = $this->createPureQueryBuilder();

        return $qb->select('COUNT(' . implode(', ', $this->getIdentifiersList($qb)) . ')')
            ->setMaxResults(1);
    }

    /**
     * Find entities by id's list
     * 
     * @param array $ids Id's list
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByIds(array $ids)
    {
        if (1 < count($this->_class->identifier)) {
            // TODO - add throw exception
        }

        $qb = $this->createQueryBuilder();
        $keys = DB::buildInCondition($qb, $ids);
        $alias = $this->getMainAlias($qb);
        $qb->andWhere($alias . '.' . $this->_class->identifier[0] . ' IN (' . implode(', ', $keys) . ')');

        return $qb->getQuery()->getResult();
    }

    /**
     * Find data frame 
     * 
     * @param int $start Start offset
     * @param int $limit Frame length
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findFrame($start = 0, $limit = 0)
    {
        return $this->defineFrameQuery($start, $limit)->getQuery()->getResult();
    }

    /**
     * Define query for 'findFrame()' finder
     * 
     * @param integer $start Start offset
     * @param integer $limit Frame length
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFrameQuery($start, $limit)
    {
        return $this->assignFrame($this->createPureQueryBuilder(), $start, $limit);
    }

    /**
     * Assign frame to query builder
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb    Query builder
     * @param int                       $start Start offset
     * @param int                       $limit Frame length
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assignFrame(\Doctrine\ORM\QueryBuilder $qb, $start = 0, $limit = 0)
    {
        $start = max(0, intval($start));
        $limit = max(0, intval($limit));

        if (0 < $start) {
            $qb->setFirstResult($start);
        }

        if (0 < $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

    /**
     * Get identifiers list for specified query builder object
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIdentifiersList(\Doctrine\ORM\QueryBuilder $qb)
    {
        $alias = $this->getMainAlias($qb);

        $list = array();

        foreach ($this->_class->identifier as $i) {
            $list[] = $alias . '.' . $i;
        }

        return $list;
    }

    /**
     * Adds support for magic finders
     * 
     * @param string $method    Method name
     * @param array  $arguments Arguments list
     *  
     * @return array|object The found entity/entities
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($method, $arguments)
    {
        if (0 === strncmp($method, 'findBy', 6)) {
            $by = substr($method, 6);
            $method = 'findBy';

        } elseif (0 === strncmp($method, 'findOneBy', 9)) {

            $by = substr($method, 9);
            $method = 'findOneBy';

        } else {
            throw new \BadMethodCallException(
                'Undefined method \'' . $method . '\'. The method name must start with '.
                'either findBy or findOneBy!'
            );
        }

        if (!isset($arguments[0])) {
            throw \Doctrine\ORM\ORMException::findByRequiresParameter($method . $by);
        }

        $fieldName = str_replace(self::$from, self::$to, lcfirst($by));

        if (!$this->_class->hasField($fieldName)) {
            throw \Doctrine\ORM\ORMException::invalidFindByCall(
                $this->_entityName,
                $fieldName, 
                $method . $by
            );
        }

        return $this->$method(array($fieldName => $arguments[0]));
    }

}
