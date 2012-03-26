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
 * Permanent worker
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Permanent extends \Swarm\Worker
{
    const SLEEP_TIME = 1;

    /**
     * Worker arguments 
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $arguments;

    /**
     * Periodic work 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function work();

    /**
     * Run worker
     *
     * @param mixed $arguments Arguments
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function run($arguments = null)
    {
        $this->arguments = $arguments;

        $this->prepare();

        while ($this->isAlive()) {
            $this->work();
            $this->wait();
            pcntl_signal_dispatch();
        }
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
        sleep(static::SLEEP_TIME);
    }
}

