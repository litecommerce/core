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

namespace Swarm;

/**
 * Common workers planner 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Planner extends \Swarm\ASwarm
{
    /**
     * Workers info list
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $workers;

    /**
     * Get instance 
     * 
     * @return \Swarm\Planner
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getInstance()
    {
        $class = get_called_class();

        return new $class;
    }

    /**
     * Define workers
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWorkers()
    {
        return array();
    }

    /**
     * Get workers
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getWorkers()
    {
        if (!isset($this->workers)) {
            $this->defineWorkers();
        }

        return $this->workers;
    }

    /**
     * Verifies that all meet the following objectives and the need for workers is no longer
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isClosed()
    {
        return 0 == count($this->getWorkers());
    }

    // {{{ Workers list operations

    /**
     * Register worker
     *
     * @param string $class     Worker's class name
     * @param mixed  $arguments Worker arguments OPTIONAL
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerWorker($class, $arguments = null)
    {
        $this->workers[] = array(
            'class'     => $class,
            'arguments' => $arguments,
        );

        end($this->workers);

        return key($this->workers);
    }

    /**
     * Register worker
     *
     * @param string  $class     Worker's class name
     * @param mixed   $arguments Worker arguments OPTIONAL
     * @param integer $value     Worker's value OPTIONAL
     *
     * @return \Swarm\Planner
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addWorker($class, $arguments = null, $value = 1)
    {
        $value = intval($value);
        while (0 < $value) {
            $this->registerWorker($class, $arguments);
            $value--;
        }

        return $this;
    }

    /**
     * Unregister worker 
     * 
     * @param integer $index Worker index
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function unregisterWorker($index)
    {
        if (isset($this->workers[$index])) {
            unset($this->workers[$index]);
        }
    }

    /**
     * Clear all workers records
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clearWorkers()
    {
        $this->workers = array();
    }

    /**
     * Check - worker with specified index is registered or not
     * 
     * @param integer $index Worker index into workers list
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isRegisteredWorker($index)
    {
        return isset($this->workers[$index]);
    }

    // }}}

}

