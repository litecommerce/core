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
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Pattern;

/**
 * Factory
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Factory extends \Includes\Pattern\APattern
{
    /**
     * Class handlers cache
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $classHandlers = array();


    /**
     * Return the Reflection handler for class
     * 
     * @param string $class class name
     *  
     * @return \ReflectionClass
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClassHandler($class)
    {
        if (!isset(static::$classHandlers[$class])) {
            static::$classHandlers[$class] = new \ReflectionClass($class);
        }

        return static::$classHandlers[$class];
    }


    /**
     * Create object instance and pass arguments to it contructor (if needed)
     *
     * @param string $class class name
     * @param array  $args  constructor arguments
     *
     * @return object
     * @access public
     * @since  3.0.0
     */
    public static function create($class, array $args = array())
    {
        $handler = $this->getClassHandler($class);

        return $handler->hasMethod('__construct') ? $handler->newInstanceArgs($args) : $handler->newInstance();
    }
}
