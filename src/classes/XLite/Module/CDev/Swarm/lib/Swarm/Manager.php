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
 * Swarm manager 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Manager extends \Swarm\Base\Singleton
{
    /**
     * Workers planner 
     * 
     * @var   \Swarm\Planner
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $planner;

    /**
     * Alive state
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $alive = true;

    /**
     * Reload state
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $reload = false;

    /**
     * Workers process info
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $workers = array();

    /**
     * Managed worker 
     * 
     * @var   \Swarm\Worker
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $managedWorker;

    /**
     * Manager pid 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $pid;

    /**
     * Get manager process id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Check state 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     * @throws \Swarm\BlockException
     */
    public function checkState()
    {
        if ($this->managedWorker && $this->managedWorker->getBlocking()) {
            pcntl_signal_dispatch();
            if (!$this->isAlive()) {
                throw new \Swarm\BlockException;
            }
        }
    }

    /**
     * Constructor
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
        declare(ticks = 1);
        register_tick_function(array($this, 'checkState'));

        $this->pid = posix_getpid();

        $this->assignSignals();
    }

    /**
     * Assign signals 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assignSignals()
    {
        pcntl_signal(SIGTERM, array($this, 'processSigTerm'));
        pcntl_signal(SIGQUIT, array($this, 'processSigTerm'));
        pcntl_signal(SIGTSTP, array($this, 'processSigTerm'));
        pcntl_signal(SIGINT, array($this, 'processSigTerm'));

        pcntl_signal(SIGHUP, array($this, 'processSigHup'));
    }

    // {{{ Process name operations

    /**
     * Renew current process name 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function renewProcessName()
    {
        if (function_exists('setproctitle')) {
            setproctitle($this->getProcessName());
        }
    }

    /**
     * Get process name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProcessName()
    {
        return $this->managedWorker
            ? $this->managedWorker->getProcessName()
            : $this->getManagerProcessName();
    }

    /**
     * Get manager process name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getManagerProcessName()
    {
        return 'swarm manager';
    }

    // }}}

    // {{{ Signals processing

    /**
     * Process termination signal
     *
     * @param integre $signal Signal
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processSigTerm($signal)
    {
        $this->alive = false;
    }

    /**
     * Process hand up signal
     *
     * @param integre $signal Signal
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processSigHup($signal)
    {
        $this->reload = true;
    }

    // }}}

    // {{{ Manage workes

    /**
     * Run manager
     * 
     * @return boolean Manager or not
     * @see    ____func_see____
     * @since  1.0.0
     * @throws \Swarm\Exception
     */
    public function run()
    {
        if (!$this->isReady()) {
            throw \Swarm\Exception('Can not run manager - it is not ready to run');
        }

        $this->renewProcessName();

        $exitWorker = false;

        while ($this->isAlive()) {
            if (!$this->checkWorkers()) {
                $exitWorker = true;
                break;
            }
            $this->wait();
            pcntl_signal_dispatch();
        }

        if (!$exitWorker) {
            $this->killWorkers();

            $status = 0;
            pcntl_wait($status);
        }

        return !$exitWorker;
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
        return $this->alive;
    }

    /**
     * Check manager state
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isReady()
    {
        return isset($this->planner);
    }

    /**
     * Check and (re)start workers 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkWorkers()
    {
        $result = true;

        if ($this->reload) {
            $this->reload = false;
            $this->killWorkers();
        }

        $checked = array();

        $workers = $this->planner->getWorkers();
        foreach ($workers as $info) {
            $found = false;
            foreach ($this->workers as $index => $worker) {
                if (!in_array($index, $checked) && $worker['class'] == $info['class']) {
                    if ($this->checkWorker($worker)) {
                        $found = $index;
                        break;

                    } else {
                        unset($this->workers[$index]);
                    }
                }
            }

            if (false !== $found) {
                $checked[] = $found;

            } else {
                $result = $this->startWorker($info);
                if (is_array($result)) {

                    // Create worker
                    $this->workers[] = $result;
                    end($this->workers);
                    $checked[] = key($this->workers);

                } elseif ($result) {
                    // Exit worker
                    $result = false;
                    break;
                }
            }
        }

        if ($result) {

            // Remove obsolete workers
            foreach ($this->workers as $index => $worker) {
                if (!in_array($index, $checked)) {
                    $this->killWorker($index);
                }
            }
        }


        return $result;
    }

    /**
     * Start worker 
     * 
     * @param array $info Worker info
     *  
     * @return aray|boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function startWorker(array $info)
    {
        $pid = pcntl_fork();
        if ($pid) {
            $result = array('pid' => $pid) + $info;

        } elseif (posix_setsid() == -1) {
            $result = false;

        } else {

            try {
                $this->runWorker($info);

            } catch (\Swarm\BlockException $e) {
            }
            $result = true;

        }

        return $result;
    }

    /**
     * Start worker
     *
     * @param array $info Worker info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function runWorker(array $info)
    {
        $this->managedWorker = new $info['class']($this);
        $this->renewProcessName();
        $this->managedWorker->run(isset($info['arguments']) ? $info['arguments'] : null);

        die(0);
    }

    /**
     * Check worker 
     * 
     * @param array $worker Worker info
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkWorker(array $worker)
    {
        $status = 0;
        pcntl_waitpid($worker['pid'], $status, WNOHANG);

        return posix_getsid($worker['pid']);
    }

    /**
     * Kill all workers 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function killWorkers()
    {
        foreach ($this->workers as $index => $pid) {
            $this->killWorker($index);
        }
    }

    /**
     * Kill worker by workers list index
     * 
     * @param integer $index Index
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function killWorker($index)
    {
        if (isset($this->workers[$index])) {
            posix_kill($this->workers[$index]['pid'], SIGTERM);
            $status = 0;
            pcntl_waitpid($this->workers[$index]['pid'], $status);
            unset($this->workers[$index]);
        }
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
        sleep(1);
    }

    // }}}

    // {{{ Settings

    /**
     * Set planner 
     * 
     * @param \Swarm\Planner $planner Planner
     *  
     * @return \Swarm\Manager
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setPlanner(\Swarm\Planner $planner)
    {
        $this->planner = $planner;

        return $this;
    }

    // }}}

}
