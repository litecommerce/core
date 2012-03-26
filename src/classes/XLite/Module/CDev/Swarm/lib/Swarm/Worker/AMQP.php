<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * PHP version 5.3.0
 * 
 * @author    ____author____ 
 * @copyright ____copyright____
 * @license   ____license____
 * @link      https://github.com/max-shamaev/swarm
 * @since     1.0.0
 */

namespace Swarm\Worker;

/**
 * AMQP client 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AMQP extends \Swarm\Worker\Permanent
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
     * Blocking worker or not
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $blocking = true;

    /**
     * Ready state
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $ready = false;

    /**
     * Get AMQP server settings 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function getAMQPServerSettings();

    /**
     * Get handlers 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function getHandlers();

    /**
     * Check alive state
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAlive()
    {
        return parent::isAlive() && (!$this->ready || 0 < count($this->channel->callbacks));
    }

    /**
     * Periodic work
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function work()
    {
        $this->ready = true;
    }

    /**
     * Prepare worker
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepare()
    {
        parent::prepare();

        $this->initializeConnection();

        $this->setupChannel();
        $this->assignHandlers();
    }

    /**
     * Wait
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function wait()
    {
        $this->channel->wait();
    }

    /**
     * Destructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __destruct()
    {
        $this->finalizeConnection();
    }

    /**
     * Finalize connection
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function finalizeConnection()
    {
        if (isset($this->channel)) {
            $this->channel->close();
        }

        if (isset($this->connection)) {
            $this->connection->close();
        }
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
        $config = $this->getAMQPServerSettings();

        $this->connection = @new \AMQPConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config['vhost']
        );
        $this->channel = $this->connection->channel();
    }

    /**
     * Setup channel 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setupChannel()
    {
    }

    /**
     * Assign channel handlers
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assignHandlers()
    {
        foreach ($this->getHandlers() as $handler) {
            foreach ($handler->getListeners() as $listener) {
                $this->assignHandler($listener);
            }
        }
    }

    /**
     * Assign channel handler 
     * 
     * @param array $listener Listener
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assignHandler(array $listener)
    {
        $this->channel->basic_consume(
            $listener['queue'],
            $listener['tag'],
            false,
            false,
            false,
            false,
            $listener['listener']
        );
    }
}

