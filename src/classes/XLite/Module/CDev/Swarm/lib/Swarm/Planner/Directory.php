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

namespace Swarm\Planner;

/**
 * Directory-based planner 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Directory extends \Swarm\Planner
{
    /**
     * Workers path
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $path;

    /**
     * Constructor
     *
     * @param string $path Workers path
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($path)
    {
        $this->path = $path;
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
        $dirIterator = new RecursiveDirectoryIterator($this->path . DIRECTORY_SEPARATOR);
        $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

        $this->workers = array();
        foreach ($iterator as $filePath => $fileObject) {
            if (preg_match('/.php$/Ss', $filePath)) {
                $classes = get_declared_classes();
                require_once $filePath;

                $classes = array_diff(get_declared_classes(), $classes);
                if ($classes) {
                    foreach ($classes as $class) {
                        $ref = new \ReflectionClass($class);
                        if ($ref->isInstantiable() && $ref->isSubclassOf('Swarm\Worker')) {
                            $this->registerWorker($class);
                        }
                    }
                }
            }
        }
    }

}


