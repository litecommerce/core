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

namespace XLite\Controller\Console;

/**
 * AMQP listener controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class AMQPListener extends \XLite\Controller\Console\AConsole
{
    /**
     * Driver 
     * 
     * @var   \XLite\Core\EventDriver\AMQP
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $driver;

    /**
     * Handle message 
     * 
     * @param \AMQPMessage $message Mesasge
     * @param string       $name    Event (queue) name
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function handleMessage(\AMQPMessage $message, $name)
    {
        $result = false;
        $data = @unserialize($message->body) ?: array();

        if (\XLite\Core\EventListener::getInstance()->handle($name, $data)) {
            $result = true;
            $this->getDriver()->sendAck($message); 
        }

        return $result;
    }

    /**
     * Preprocessor for no-action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doNoAction()
    {
        $driver = $this->getDriver();
        if ($driver) {
            foreach (\XLite\Core\EventListener::getInstance()->getEvents() as $name) {
                $object = $this;
                $listener = function (\AMQPMessage $message) use ($object, $name) {
                    return $object->handleMessage($message, $name);
                };
                $driver->consume($name, $listener);
            }

            do {
                $this->wait();
            } while($this->checkCycle());
        }
    }

    /**
     * Check wait cycle 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function checkCycle()
    {
        return (bool)\XLite\Core\Request::getInstance()->permanent;
    }

    /**
     * Wait
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function wait()
    {
        $this->getDriver()->wait();
        if (function_exists('pcntl_signal_dispatch')) {
            pcntl_signal_dispatch();
        }
    }

    /**
     * Get driver 
     * 
     * @return \XLite\Core\EventDriver\AMQP
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getDriver()
    {
        if (!isset($this->driver)) {
            $this->driver = \XLite\Core\EventDriver\AMQP::isValid() ? new \XLite\Core\EventDriver\AMQP : false;
        }

        return $this->driver;
    } 
}
