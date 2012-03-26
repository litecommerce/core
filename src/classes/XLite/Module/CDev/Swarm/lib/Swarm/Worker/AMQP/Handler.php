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

namespace Swarm\Worker\AMQP;

/**
 * AMQP handler 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Handler extends \Swarm\ASwarm
{
    /**
     * Listeners list
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $listeners = array(
        array('messages'),
    );

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
    abstract public function handle(\AMQPMessage $message, $queue, $tag);

    /**
     * Get listeners 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getListeners()
    {
        $list = array();

        $object = $this;

        foreach ($this->listeners as $listener) {
            $listener[1] = (isset($listener[1]) && $listener[1])
                ? $listener[1]
                : null;

            $list[] = array(
                'queue'    => $listener[0],
                'tag'      => $listener[1],
                'listener' => function (\AMQPMessage $message) use ($object, $listener) {
                    return $object->handle($message, $listener[0], $listener[1]);
                },
            );
        }

        return $list;
    }

    /**
     * Send acknowledge
     * 
     * @param \AMQPMessage $msg Message
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function sendAck(\AMQPMessage $msg)
    {
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
}

