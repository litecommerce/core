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

namespace Includes\Pattern;

/**
 * Singleton
 *
 * @package XLite
 */
abstract class Singleton extends \Includes\Pattern\APattern
{
    /**
     * Class instances
     *
     * @var array
     */
    protected static $instances = array();


    /**
     * Protected constructur
     *
     * @return void
     */
    protected function __construct()
    {
    }


    /**
     * Return object instance
     *
     * @return static
     */
    public static function getInstance()
    {
        if (!isset(static::$instances[$class = get_called_class()])) {
            static::$instances[$class] = new static();
        }

        return static::$instances[$class];
    }
}
