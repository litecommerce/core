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

namespace XLite\Controller\Console;

/**
 * AMQP service 
 * 
 */
class AMQPService extends \XLite\Controller\Console\AConsole
{
    /**
     * Declare queues 
     * 
     * @return void
     */
    protected function doActionDeclareQueues()
    {
        if (\XLite\Core\EventDriver\AMQP::isValid()) {
            $driver = new \XLite\Core\EventDriver\AMQP;
            foreach (\XLite\Core\EventListener::getInstance()->getEvents() as $name) {
                $this->printContent($name . ' ... ');
                if ($driver->declareQueue($name)) {
                    $this->printContent('done');

                } else {
                    $this->printContent('failed');
                }

                $this->printContent(PHP_EOL);
            }
        }
    }

    /**
     * Remove all queues 
     * 
     * @return void
     */
    protected function doActionRemoveQueues()
    {
        if (\XLite\Core\EventDriver\AMQP::isValid()) {
            $driver = new \XLite\Core\EventDriver\AMQP;
            foreach (\XLite\Core\EventListener::getInstance()->getEvents() as $name) {
                $this->printContent($name . ' ... ');
                $result = false;
                try {
                    $driver->getChannel()->queue_delete($name);
                    $result = true;

                } catch (\Exception $e) {
                    $driver->getChannel(true);
                }

                if ($result) {
                    $this->printContent('done' . PHP_EOL);

                } else {
                    $this->printContent('failed' . PHP_EOL);
                }
            }
        }
    }
}

