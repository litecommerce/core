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

namespace XLite\Core;

/**
 * Events subsystem
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Event extends \XLite\Base\Singleton
{
    /**
     * Events list
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $events = array();

    /**
     * Trigger invalidElement event
     *
     * @param string $name    Element name
     * @param string $message Error message
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function invalidElement($name, $message)
    {
        self::__callStatic('invalidElement', array(array('name' => $name, 'message' => $message)));
    }

    /**
     * Short event creation
     *
     * @param string $name      Event name
     * @param array  $arguments Event arguments
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function __callStatic($name, array $arguments)
    {
        static::getInstance()->trigger(
            $name,
            0 < count($arguments) ? array_shift($arguments) : array()
        );
    }

    /**
     * Trigger event
     *
     * @param string $name      Event name
     * @param array  $arguments Event arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function trigger($name, array $arguments = array())
    {
        $this->events[] = array(
            'name'      => $name,
            'arguments' => $arguments,
        );
    }

    /**
     * Display events
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function display()
    {
        foreach ($this->events as $event) {
            header('event-' . $event['name'] . ': ' . json_encode($event['arguments']));
        }
    }

    /**
     * Clear list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clear()
    {
        $this->events = array();
    }
}
