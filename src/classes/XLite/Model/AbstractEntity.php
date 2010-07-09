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

/**
 * Abstract entity 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Model_AModelEntity
{
    /**
     * Field access codes
     */
    const FIELD_READ       = 'r';
    const FIELD_WRITE      = 'w';
    const FIELD_RW         = 'a';
    const FIELD_COLLECTION = 'c';


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
     * Accessors list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $accessors = array();

    /**
     * Mutators list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $mutators = array();

    /**
     * Dump constructor
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
        $class = get_called_class();

        if (!isset(self::$accessors[$class])) { 
            self::$accessors[$class] = array($name => $this->getAccessor($name));

        } elseif (!isset(self::$accessors[$class][$name])) {
            self::$accessors[$class][$name] = $this->getAccessor($name);
        }

        $accessor = self::$accessors[$class][$name];

        return $accessor
            ? $this->$accessor()
            : (isset($this->$name) ? $this->$name : null);
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
        $class = get_called_class();

        if (!isset(self::$mutators[$class])) {
            self::$mutators[$class] = array($name => $this->getMutator($name));

        } elseif (!isset(self::$mutators[$class][$name])) {
            self::$mutators[$class][$name] = $this->getMutator($name);
        }

        $mutator = self::$mutators[$class][$name];
        if ($mutator) {
            $this->$mutator($value);

        } else {
            $this->$name = $value;
        }
    }

    /**
     * Get accessor method name
     * 
     * @param string $name Property name
     *  
     * @return string or false
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAccessor($name)
    {
        $method = 'get' . XLite_Core_Converter::prepareMethodName($name);

        return method_exists($this, $method) ? $method : false;
    }

    /**
     * Get mutator method name
     * 
     * @param string $name Property name
     *  
     * @return string or false
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMutator($name)
    {
        $method = 'set' . XLite_Core_Converter::prepareMethodName($name);

        return method_exists($this, $method) ? $method : false;
    }

    /**
     * Get entity repository 
     * 
     * @return XLite_Model_Doctrine_Repo_AbstractRepo
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRepository()
    {
        return XLite_Core_Database::getEntityManager()
            ->getRepository(get_called_class());
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
            self::$cacheEnabled[$class] = ($repo && is_subclass_of($repo, 'XLite_Model_Repo_AbstractRepo'))
                ? $repo->hasCacheCells()
                : false;
        }

        if (self::$cacheEnabled[$class]) {
            $this->getRepository()->deleteCacheByEntity($this);
        }
    }
}
