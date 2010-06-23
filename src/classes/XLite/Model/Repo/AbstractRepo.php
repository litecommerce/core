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

use Doctrine\ORM\EntityRepository;

/**
 * Abstract repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Model_Repo_AbstractRepo extends EntityRepository
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
     * @param Doctrine\ORM\AbstractQuery $query  Query
     * @param string                     $name   Cell name
     * @param array                      $params Cell parameters
     *  
     * @return Doctrine\ORM\AbstractQuery
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assignQueryCache(Doctrine\ORM\AbstractQuery $query, $name, array $params = array())
    {
        if (XLite_Core_Database::isCacheEnabled()) {
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

        if (XLite_Core_Database::isCacheEnabled()) {
            $cell = $this->getCacheCells($name);
            if ($cell) {

                $result = XLite_Core_Database::getCacheDriver()->fetch(
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
        if (XLite_Core_Database::isCacheEnabled()) {
            $cell = $this->getCacheCells($name);
            if ($cell) {

                $hash = $this->getCellHash($name, $cell, $params);

                XLite_Core_Database::getCacheDriver()->save(
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
        return 'getCacheHash' . ucfirst($name);
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
        return 'convertRecordToParams' . ucfirst($name);
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
        return XLite_Core_Database::isCacheEnabled()
            && $this->getCacheCells();
    }

    /**
     * Delete cache by entity
     * 
     * @param XLite_Model_AbstractEntity $entity Record
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteCacheByEntity(XLite_Model_AbstractEntity $entity)
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

            XLite_Core_Database::getCacheDriver()
                ->delete($hash);
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
        if (XLite_Core_Database::isCacheEnabled()) {
            XLite_Core_Database::getCacheDriver()->deleteByPrefix($this->getTableHash($name) . '.');
        }
    }

    /**
     * Assign default orderBy 
     * 
     * @param Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param string                    $alias        Table short alias in query builder
     *  
     * @return Doctrine\ORM\QueryBuilder
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assignDefaultOrderBy(Doctrine\ORM\QueryBuilder $queryBuilder, $alias)
    {
        if ($this->defaultOrderBy) {
            if (is_string($this->defaultOrderBy)) {

                // One field
                $queryBuilder->orderBy($alias . '.' . $this->defaultOrderBy);

            } elseif (is_array($this->defaultOrderBy)) {

                // Many fields (field name => sort suffix)
                foreach ($this->defaultOrderBy as $field => $asc) {
                    if (!isset($exp)) {
                        $exp = new Doctrine\ORM\Query\Expr\OrderBy($alias . '.' . $field, $asc ? 'ASC' : 'DESC');

                    } else {
                        $exp->add($alias . '.' . $field, $asc ? 'ASC' : 'DESC');
                    }
                }

                if (isset($exp)) {
                    $queryBuilder->orderBy($exp);
                }
            }
        }

        return $queryBuilder;
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
            throw Doctrine\ORM\ORMException::findByRequiresParameter($method . $by);
        }

        $fieldName = str_replace(self::$from, self::$to, lcfirst($by));

        if (!$this->_class->hasField($fieldName)) {
            throw Doctrine\ORM\ORMException::invalidFindByCall(
                $this->_entityName,
                $fieldName, 
                $method . $by
            );
        }

        return $this->$method(array($fieldName => $arguments[0]));
    }

}
