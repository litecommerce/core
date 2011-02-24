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

namespace XLite\Model;

/**
 * Abstract entity 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class AEntity
{
    /**
     * Cache enabled flag (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected static $cacheEnabled = array();

    /**
     * Method names (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected static $methodNames = array();


    /**                                                                           
     * Constructor                                                                
     *                                                                            
     * @param array $data Entity properties OPTIONAL
     *                                                                            
     * @return void                                                               
     * @see    ____func_see____                                                   
     * @since  3.0.0                                                              
     */                                                                           
    public function __construct(array $data = array())                            
    {
        empty($data) ?: $this->map($data);
    }

    /**
     * Map data to entity columns
     * 
     * @param array $data Data
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function map(array $data)
    {
        foreach ($data as $key => $value) {
            // Map only existing properties with setter methods or direct
            $method = 'set' . $this->getMethodName($key);
            if (method_exists($this, $method)) {
                // $method is assembled from 'set' + getMethodName()
                $this->$method($value);

            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Common getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        // Accessor method name
        return $this->{'get' . $this->getMethodName($name)}();
    }

    /**
     * Common setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __set($name, $value)
    {
        // Mutator method name
        return $this->{'set' . $this->getMethodName($name)}($value);
    }

    /**
     * Common unset
     *
     * @param string $name Property name
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __unset($name)
    {
        $this->__set($name, null);
    }

    /**
     * Get method name
     * FIXME - to remove
     * 
     * @param string $name Property name
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMethodName($name)
    {
        $class = get_called_class();

        if (!isset(self::$methodNames[$class])) {
            self::$methodNames[$class] = array();
        }

        if (!isset(self::$methodNames[$class][$name])) {
            self::$methodNames[$class][$name] = \XLite\Core\Converter::convertToCamelCase($name);
        }

        return self::$methodNames[$class][$name];
    }

    /**
     * Get entity repository 
     * 
     * @return \XLite\Model\Doctrine\Repo\AbstractRepo
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRepository()
    {
        return \XLite\Core\Database::getRepo(get_class($this));
    }

    /**
     * Check cache after enity persis or remove
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkCache()
    {
        $class = get_called_class();

        if (!isset(self::$cacheEnabled[$class])) {
            $repo = $this->getRepository();
            self::$cacheEnabled[$class] = ($repo && is_subclass_of($repo, '\XLite\Model\Repo\ARepo'))
                ? $repo->hasCacheCells()
                : false;
        }

        if (self::$cacheEnabled[$class]) {
            $this->getRepository()->deleteCacheByEntity($this);
        }
    }

    /**
     * Detach self 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function detach()
    {
        \XLite\Core\Database::getEM()->detach($this);
    }

    /**
     * Emulate the Doctrine autogenerated methods.
     * TODO - DEVCODE - to remove!
     * 
     * @param string $method Method name
     * @param array  $args   Call arguments
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        $result = preg_match('/^(get|set)(\w+)$/Si', $method, $matches) && !empty($matches[2]);

        if ($result) {
            $property = \XLite\Core\Converter::convertFromCamelCase($matches[2]);
            $result = property_exists($this, $property);
        }

        $return = null;

        if ($result) {
            if ('set' === $matches[1]) {
                $this->$property = array_shift($args);

            } else {
                $return = $this->$property;
            }

        } else {
            throw new \BadMethodCallException(
                get_class($this) . '::' . $method . '() - method not exists or invalid getter/setter'
            );
        }

        return $return;
    }

    /**
     * Check if entity is persistent
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPersistent()
    {
        return (bool) $this->{'get' . $this->getMethodName($this->getRepository()->getPrimaryKeyField())}();
    }

    /**
     * Update entity
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function update()
    {
        \XLite\Core\Database::getEM()->persist($this);
        \XLite\Core\Database::getEM()->flush();

        return true;
    }

    /**
     * Create entity
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function create()
    {
        return $this->update();
    }

    /**
     * Delete entity
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        \XLite\Core\Database::getEM()->remove($this);
        \XLite\Core\Database::getEM()->flush();
        \XLite\Core\Database::getEM()->clear();

        return true;
    }

    /**
     * Clone 
     * 
     * @return \XLite\Model\AEntity
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function cloneEntity()
    {
        $class  = $this instanceof \Doctrine\ORM\Proxy\Proxy ? $this->_entityClass : get_called_class();
        $entity = new $class();
        $fields = array_keys(\XLite\Core\Database::getEM()->getClassMetadata($class)->fieldMappings);

        $map = array();

        foreach ($fields as $field) {
            $map[$field] = $this->$field;
        }

        $entity->map($map);

        return $entity;
    }

    /**
     * Since Doctrine lifecycle callbacks do not allow to modify associations, we've added this method
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function beforeCommit()
    {
    }
}
