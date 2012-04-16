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
 * @since     1.0.19
 */

namespace XLite\Core;

/**
 * Event task 
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class EventTask extends \XLite\Base\Singleton
{
    /**
     * Driver 
     * 
     * @var   \XLite\Core\EventDriver\AEventDriver
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $driver;

    /**
     * Call events
     * 
     * @param string $name Event name
     * @param array  $args Event arguments OPTIONAL
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public static function __callStatic($name, array $args = array())
    {
        $result = false;

        if (in_array($name, \XLite\Core\EventListener::getInstance()->getEvents())) {
            $args = isset($args[0]) && is_array($args[0]) ? $args[0] : array();
            $driver = static::getInstance()->getDriver();
            $result = $driver ? $driver->fire($name, $args) : false;
        }

        return $result;
    }

    /**
     * Get driver 
     * 
     * @return \XLite\Core\EventDriver\AEventDriver
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getDriver()
    {
        if (!isset($this->driver)) {
            $driver = \XLite::GetInstance()->getOptions(array('other', 'event_driver')) ?: 'auto';
            $driver = strtolower($driver);
            $list = $this->getDrivers();

            if ('auto' != $driver) {
                foreach ($list as $class) {
                    if (strtolower($class::getCode()) == $driver) {
                        $this->driver = new $class;
                        break;
                    }
                }
            }

            if (!$this->driver) {
                $this->driver = $list ? new $list[0] : false;
            }
        }

        return $this->driver;
    }

    /**
     * Get valid drivers 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getDrivers()
    {
        $list = array();

        foreach ($this->getDriversClasses() as $class) {
            if ($class::isValid()) {
                $list[] = $class;
            }
        }

        return $list;
    }

    /**
     * Get drivers classes 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getDriversClasses()
    {
        return array(
            '\XLite\Core\EventDriver\Db',
            '\XLite\Core\EventDriver\AMQP',
        );
    }

}

