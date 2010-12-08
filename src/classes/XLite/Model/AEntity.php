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

namespace XLite\Model;

/**
 * Abstract entity 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AEntity
{
    /**
     * Cache enabled flag (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cacheEnabled = array();

    /**
     * Method names (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $methodNames = array();


    /**                                                                           
     * Constructor                                                                
     *                                                                            
     * @param array $data Entity properties                                       
     *                                                                            
     * @return void                                                               
     * @access public                                                             
     * @see    ____func_see____                                                   
     * @since  3.0.0                                                              
     */                                                                           
    public function __construct(array $data = array())                            
    {
        if (!empty($data)) {
            $this->map($data);
        }
    }

    /**
     * Map data to entity columns
     * 
     * @param array $data Data
     *  
     * @return boolean
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
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
     * @access protected
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
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function cloneEntity()
    {
        $class = $this instanceof \Doctrine\ORM\Proxy\Proxy ? $this->_entityClass : get_called_class();

        $entity = new $class();

        $cmd = \XLite\Core\Database::getEM()->getClassMetadata($class);
        $map = array();
        foreach (array_keys($cmd->fieldMappings) as $f) {
            $map[$f] = $this->$f;
        }

        $entity->map($map);

        return $entity;
    }
}
