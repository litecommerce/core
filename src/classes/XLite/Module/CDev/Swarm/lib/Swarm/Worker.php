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
 * Abstratc worker 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Worker extends \Swarm\ASwarm
{
    /**
     * Manager 
     * 
     * @var   \Swarm\Manager
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $manager;

    /**
     * Silent worker mode flag
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $silent = false;

    /**
     * Blocking worker or not
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $blocking = false;

    /**
     * Run worker
     *
     * @param mixed $arguments Arguments
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function run($arguments = null);

    /**
     * Constructor
     * 
     * @param \Swarm\Manager $manager Manager
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(\Swarm\Manager $manager)
    {
        $this->manager = $manager;

        if ($this->silent) {
            fclose(STDIN);
            fclose(STDOUT);
            fclose(STDERR);
        }
    }

    /**
     * Get blocking 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBlocking()
    {
        return $this->blocking;
    }

    /**
     * Get process name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProcessName()
    {
        return 'swarm worker ' . get_called_class() . ' ' . date('Y-m-d H-i-s') . ' ' . substr(round(microtime(true) * 10000), -4);
    }

    /**
     * Check alive state
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAlive()
    {
        return $this->manager->isAlive()
            && posix_kill($this->manager->getPid(), 0);
    }
}

