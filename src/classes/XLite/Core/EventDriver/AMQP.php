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

namespace XLite\Core\EventDriver;

/**
 * AMQP-based event driver 
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class AMQP extends \XLite\Core\EventDriver\AEventDriver
{
    /**
     * Connection
     *
     * @var   \AMQPConnection
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $connection;

    /**
     * Channel
     *
     * @var   \AMQPChannel
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $channel;

    /**
     * Check driver
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public static function isValid()
    {
        return (bool)static::getInstance()->getChannel();
    }

    /**
     * Get driver code
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    public static function getCode()
    {
        return 'amqp';
    }

    /**
     * Fire event
     *
     * @param string $name      Event name
     * @param array  $arguments Arguments OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function fire($name, array $arguments = array())
    {
        $result = true;

        $channel = $this->getChannel();
        try {
            $this->redeclareQueue($name);
            $channel->basic_publish(
                new \AMQPMessage(serialize($arguments), array('content_type' => 'text/plain')),
                $this->getExchange(),
                $name
            );

        } catch (\Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Get channel
     *
     * @return \AMQPChannel
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getChannel()
    {
        if (!$this->channel) {
            require_once LC_DIR_LIB . 'AMQP' . LC_DS . 'amqp.inc';

            try {
                $this->initializeConnection();
            } catch (\Exception $e) {
            }
        }

        return $this->channel;
    }

    /**
     * Redeclare queue 
     * 
     * @param string $name Queue name
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function redeclareQueue($name)
    {
        $key = 'amqp.queue.' . $name;

        $entity = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->findOneBy(array('name' => $key));

        if (!$entity) {
            $entity = new \XLite\Model\TmpVar;
            $entity->setName($key);
            $entity->setValue(time());
            \XLite\Core\Database::getEM()->persist($entity);

            $this->declareQueue($name);
        }
    }

    /**
     * Declare exchange and queue
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function declareQueue($name)
    {
        $result = true;
        $channel = $this->getChannel();
        $exchange = $this->getExchange();

        try {
            $channel->exchange_declare($exchange, 'direct', false, true, false);
            $channel->queue_declare($name, false, true, false, false);
            $channel->queue_bind($name, $exchange, $name);

        } catch (\Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Consume queue
     * 
     * @param string   $queue    Queue name
     * @param callable $listener Callable listener
     * @param string   $tag      Consumer tag OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function consume($queue, $listener, $tag = null)
    {
        $channel = $this->getChannel();

        if ($channel) {
            $channel->basic_consume(
                $queue,
                $tag,
                false,
                false,
                false,
                false,
                $listener
            );
        }

    }

    /**
     * Send acknowledge
     * 
     * @param \AMQPMessage $message Mesasge
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function sendAck(\AMQPMessage $message)
    {
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }

    /**
     * Wait messages
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function wait()
    {
        $channel = $this->getChannel();

        if ($channel && 0 < count($channel->callbacks)) {
            $channel->wait();
        }
    }

    /**
     * Get exchange name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getExchange()
    {
        return \XLite::getInstance()->getOptions(array('amqp', 'excange')) ?: 'xlite';
    }

    /**
     * Initialize connection
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function initializeConnection()
    {
        $this->channel = null;
        $this->connection = null;

        if (function_exists('bcmod')) {
            $config = \XLite::getInstance()->getOptions(array('amqp'));

            $this->connection = new \AMQPConnection(
                $config['host'],
                $config['port'],
                $config['user'],
                $config['password'],
                $config['vhost']
            );
            $this->channel = $this->connection->channel();
        }
    }

}

