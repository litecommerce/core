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
abstract class XLite_Model_Doctrine_AbstractEntity
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
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cacheEnabled = null;

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
     * Collections list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $collections = null;

    /**
     * Fields list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fields = array();

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        $class = get_called_class();

        // Cache class collections
        if (is_null($class::$collections)) {
            $class::$collections = array();
            foreach ($this->fields as $name => $type) {
                if (self::FIELD_COLLECTION == $type) {
                    $class::$collections[] = $name;
                }
            }
        }

        // Assign collections
        foreach ($class::$collections as $name) {
            $this->$name = new Doctrine\Common\Collections\ArrayCollection;
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
        if (!isset($this->fields[$name]) || self::FIELD_WRITE == $this->fields[$name]) {
            // TODO - add throw exception
            return null;
        }

        $class = get_called_class();
        if (!isset($class::$accessors[$name])) {
            $class::$accessors[$name] = $this->getAccessor($name);
        }

        $accessor = $class::$accessors[$name];

        return $accessor
            ? $this->$accessor()
            : $this->$name;
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
        if (!isset($this->fields[$name]) || self::FIELD_READ == $this->fields[$name]) {
            // TODO - add throw exception
            return null;
        }

        $class = get_called_class();
        if (!isset($class::$mutators[$name])) {
            $class::$mutators[$name] = $this->getMutator($name);
        }

        $mutator = $class::$mutators[$name];
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
        $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));

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
        $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));

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
        return XLite_Model_Database::getEntityManager()
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

        if (is_null($class::$cacheEnabled)) {
            $repo = $this->getRepository();
            $class::$cacheEnabled = ($repo && $repo instanceof XLite_Model_Doctrine_Repo_AbstractRepo)
                ? $repo->hasCacheCells()
                : false;
        }

        if ($class::$cacheEnabled) {
            $this->getRepository()->checkCache($this);
        }
    }
}
