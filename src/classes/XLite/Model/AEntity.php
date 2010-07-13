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

use XLite\Core\Database as DB,
    XLite\Core\Converter;

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
     * Constructor (dump)
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
    }

    /**
     * Map data to entity columns
     * 
     * @param array $data Data
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function map(array $data)
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
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
        $accessor = 'get' . $this->getMethodName($name);

        // Accessor name assembled into getAccessor() method
        return $this->$accessor();
    }

    /**
     * Common setter
     * 
     * @param string $name  Property name
     * @param mixed  $value Property value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __set($name, $value)
    {
        $mutator = 'set' . $this->getMethodName($name);

        // Mutator name assembled into getMutator() method
        $this->$mutator($value);
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
        $mutator = 'set' . $this->getMethodName($name);

        // Mutator name assembled into getMutator() method
        $this->$mutator(null);
    }

    /**
     * Get method name
     * 
     * @param string $name Property name
     *  
     * @return string or false
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMethodName($name)
    {
        $class = get_called_class();

        if (!isset(self::$methodNames[$class])) {
            self::$methodNames[$class] = array($name => Converter::convertToCamelCase($name));

        } elseif (!isset(self::$methodNames[$class][$name])) {
            self::$methodNames[$class][$name] = Converter::convertToCamelCase($name);
        }

        return self::$methodNames[$class][$name];
    }

    /**
     * Common caller
     * NOTE: Used in decoration procedure, before models updating
     * 
     * @param string $methodName Method name
     * @param array  $args       Method arguments
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($methodName, array $args)
    {
        $result = null;

        if (defined('LC_DECORATION') && preg_match('/^(get|set)([A-Z]\w+)$/Ss', $methodName, $matches)) {

            $fieldName = Converter::convertFromCamelCase(lcfirst($matches[2]));

            if ('get' == $matches[1]) {
                $result = isset($this->$fieldName) ? $this->$fieldName : null;

            } elseif (property_exists($this, $fieldName) && 0 < count($args)) {
                $this->$fieldName = $args[0];
            }

        } else {

            throw new \BadMethodCallException($methodName . '() method not found');

        }

        return $result;
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
        return DB::getEntityManager()->getRepository(get_called_class());
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
}
