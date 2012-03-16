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

namespace XLite\Module\CDev\Swarm\Core\Swarm\Handler\Base;

/**
 * Abstract AMQP handler
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
abstract class AMQP extends \Swarm\Worker\AMQP\Handler
{
    const QUEUE_SERVICE = 'service';

    /**
     * Exit flag
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $exit = false;

    /**
     * Process message
     *
     * @param string $name Queue name
     * @param array  $data Data
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function processMessage($name, array $data);

    /**
     * Process message
     *
     * @param \AMQPMessage $message Message
     * @param string       $queue   Queue name
     * @param string       $tag     Consumer tag
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function handle(\AMQPMessage $message, $queue, $tag)
    {
        $success = false;

        $data = @unserialize($message->body) ?: array();

        if (self::QUEUE_SERVICE == $queue) {
            $this->processServiceQueue($data);
            $success = true;

        } else {
            if ($this->processMessage($queue, $data)) {
                $success = true;
                emm()->flush();
                $this->sendAck($message);
            }

            emm()->em()->clear();
        }

        $this->postprocess();
    }

    /**
     * Process service queue
     *
     * @param array $data Message data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function processServiceQueue(array $data)
    {
        if (!empty($data['command']) && 'quit' == $data['command']) {
            $this->exit = true;
        }
    }

    /**
     * Postprocess handle
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocess()
    {
        if ($this->exit) {
            exit(0);
        }
    }

}

