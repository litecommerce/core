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

namespace XLite;

/**
 * Singletons
 *
 */
class Singletons
{
    /**
     * handler
     *
     * @var \Includes\Singletons
     */
    public static $handler;

    /**
     * classNames
     *
     * @var array
     */
    protected static $classNames = array(
        'xlite'   => '\XLite',
        'request' => '\XLite\Core\Request',
        'layout'  => '\XLite\Core\Layout',
        'session' => '\XLite\Core\Session',
        'config'  => '\XLite\Core\Config',
        'flexy'   => '\XLite\Core\FlexyCompiler',
        'auth'    => '\XLite\Core\Auth',
    );

    /**
     * __constructStatic
     *
     * @return void
     */
    public static function __constructStatic()
    {
        static::$handler = new static();
    }

    /**
     * Magic getter
     *
     * @param string $name Variable name
     *
     * @return \XLite\Base\Singleton
     */
    public function __get($name)
    {
        $this->$name = call_user_func(array(static::$classNames[$name], 'getInstance'));

        return $this->$name;
    }
}
