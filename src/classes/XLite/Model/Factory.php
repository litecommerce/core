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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model;

/**
 * ____description____
 * TODO[SINGLETON] - must extends the Base\Singleton
 * NOTE - check the "factory.<name>" tags in templates
 *
 */
class Factory extends \XLite\Base
{
    /**
     * Create object instance and pass arguments to it contructor (if needed)
     *
     * @param string $class Class name
     * @param array  $args  Constructor arguments OPTIONAL
     *
     * @return \XLite\Base
     */
    public static function create($class, array $args = array())
    {
        $handler = new \ReflectionClass($class);

        return self::isSingleton($handler) ? self::getSingleton($class) : self::createObject($handler, $args);
    }


    /**
     * Check if class is a singleton
     * FIXME - must be revised or removed
     *
     * @param \ReflectionClass $handler Class descriptor
     *
     * @return void
     */
    protected static function isSingleton(\ReflectionClass $handler)
    {
        return $handler->getConstructor()->isProtected();
    }

    /**
     * Return a singleton refernce
     *
     * @param string $class Class name
     *
     * @return \XLite\Base
     */
    protected static function getSingleton($class)
    {
        return call_user_func(array($class, 'getInstance'));
    }

    /**
     * Create new object
     *
     * @param \ReflectionClass $handler Class descriptor
     * @param array            $args    Constructor params OPTIONAL
     *
     * @return \XLite\Base
     */
    protected static function createObject(\ReflectionClass $handler, array $args = array())
    {
        return $handler->hasMethod('__construct') ? $handler->newInstanceArgs($args) : $handler->newInstance();
    }


    /**
     * Create object instance
     *
     * @param string $name Class name
     *
     * @return \XLite\Base
     */
    public function __get($name)
    {
        return self::create($name);
    }
}
