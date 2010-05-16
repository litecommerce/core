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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Factory extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Check if class is a singleton 
     * 
     * @param ReflectionClass $handler class descriptor
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected static function isSingleton(ReflectionClass $handler)
    {
        return $handler->implementsInterface('XLite_Base_ISingleton');
    }

    /**
     * Return a singleton refernce
     *
     * @param string $class class name
     *
     * @return XLite_Base
     * @access protected
     * @since  3.0.0
     */
    protected static function getSingleton($class)
    {
        return call_user_func(array($class, 'getInstance'));
    }

    /**
     * Create new object
     * 
     * @param ReflectionClass $handler class descriptor
     * @param array           $args    constructor params
     *  
     * @return XLite_Base
     * @access protected
     * @since  3.0.0
     */
    protected static function createObject(ReflectionClass $handler, array $args = array())
    {
        return $handler->hasMethod('__construct') ? $handler->newInstanceArgs($args) : $handler->newInstance();
    }


    /**
     * Return object instance
     *
     * @return XLite_Model_Session
     * @access public
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Create object instance 
     * 
     * @param string $name class name
     *  
     * @return XLite_Base
     * @access public
     * @since  3.0.0
     */
    public function __get($name)
    {
        return self::create($name);
    }

    /**
     * Create object instance and pass arguments to it contructor (if needed)
     * 
     * @param string $class class name
     * @param array  $args  constructor arguments
     *  
     * @return XLite_Base
     * @access public
     * @since  3.0.0
     */
    public static function create($class, array $args = array())
    {
        $handler = new ReflectionClass($class);

        return self::isSingleton($handler) ? self::getSingleton($class) : self::createObject($handler, $args);
    }
}
