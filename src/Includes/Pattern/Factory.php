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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace Includes\Pattern;

/**
 * Factory
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Factory extends \Includes\Pattern\APattern
{
    /**
     * Class handlers cache
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $classHandlers = array();

    /**
     * Create object instance and pass arguments to it contructor (if needed)
     *
     * @param string $class Class name
     * @param array  $args  Constructor arguments OPTIONAL
     *
     * @return object
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function create($class, array $args = array())
    {
        $handler = static::getClassHandler($class);

        return $handler->hasMethod('__construct') ? $handler->newInstanceArgs($args) : $handler->newInstance();
    }

    /**
     * Return the Reflection handler for class
     *
     * @param string $class Class name
     *
     * @return \ReflectionClass
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getClassHandler($class)
    {
        if (!isset(static::$classHandlers[$class])) {
            static::$classHandlers[$class] = new \ReflectionClass($class);
        }

        return static::$classHandlers[$class];
    }
}
