<?php
class testRunner
{

    /**
     * @var TestTask[]
     */
    protected $tests;
    /**
     * @var string[]
     */
    protected $resources;
    /**
     * @var int
     */
    protected $clientsCount;

    static function getTests()
    {

        if (!defined('DIR_TESTS')) {
            define('DIR_TESTS', 'tests' . DIRECTORY_SEPARATOR . 'Web');
        }
        $classesDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . constant('DIR_TESTS') . DIRECTORY_SEPARATOR;

        $pattern = '/^' . preg_quote($classesDir, '/') . '(.*)\.php$/';

        $dirIterator = new RecursiveDirectoryIterator($classesDir);
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
        $ds = preg_quote(DIRECTORY_SEPARATOR, '/');

        $tests = array();
        foreach ($iterator as $filePath => $fileObject) {

            if (
                preg_match($pattern, $filePath, $matches)
                && !empty($matches[1])
                && !preg_match('/' . $ds . '(\w+Abstract|A[A-Z]\w+)\.php/Ss', $filePath)
                && !preg_match('/' . $ds . '(?:scripts|skins)' . $ds . '/Ss', $filePath)
            ) {

                $tests[] = new TestTask(self::get_run_path($filePath, $classesDir), self::get_tags($filePath));
            }
        }
        return $tests;

    }

    static function get_run_path($filePath, $classesDir)
    {
        return substr(str_replace($classesDir, '', $filePath), 0, -4);
    }

    static function get_tags($filePath)
    {
        $source = file_get_contents($filePath);

        $comments = token_get_all($source);

        $resources = array();
        foreach ($comments as $comment) {
            if ($comment[0] != T_DOC_COMMENT)
                continue;
            $resources = array_merge($resources, self::get_resources($comment[1]));
        }

        return $resources;
    }

    static function get_resources($comment)
    {

        preg_match_all('/^.*\@resource\s+([a-zA-Z]+)\s*$/Sm', $comment, $result);
        return $result[1];
    }

    function main()
    {
        $this->tests = self::getTests();
        $this->clientsCount = 2;
        $this->resources = array();
        $this->run();
        while (true)
        {
            $queue = false;
            $complete = true;
            foreach ($this->tests as $test) {
                if ($test->status == 'init')
                {
                    $queue = true;
                    break;
                }
            }

            sleep(5);
            $this->clean();

            foreach($this->tests as $test)
            {
                if($test->status != 'complete')
                {
                    $complete = false;
                    break;
                }
            }

            if ($complete)
                break;

            if ($queue)
                $this->run();

        }


    }

    function clean()
    {
        foreach($this->tests as $test)
        {
            if ($test->isForClean()){
                $this->resources = $test->stop($this->resources);
                $this->clientsCount++;
            }
        }
    }

    function run()
    {

        if ($this->clientsCount == 0)
            return;

        foreach ($this->tests as $test)
        {
            if ($test->status != 'init')
                continue;
            if (count(array_intersect($test->resources, $this->resources)) > 0)
                continue;
            $this->resources = $test->run($this->resources);
            $this->clientsCount--;
        }
    }
}


class TestTask
{
    public $name;
    public $resources;
    public $status = 'init';
    public $process = null;

    function __construct($name, $resources)
    {
        $this->name = $name;
        $this->resources = $resources;
    }

    function run($resources)
    {
        $pipes = null;
        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array("file", "/tmp/output-" . $this->name . ".txt", "a"),
            2 => array("file", "/tmp/errors-" . $this->name . ".txt", "a")
        );
        $proc = proc_open('./phpunit.sh ' . $this->name, $descriptorspec, $pipes);
        $this->process = $proc;
        $this->status = 'running';
        return array_merge($resources, $this->resources);
    }

    function stop($resources)
    {
        $this->status = 'complete';
        proc_close($this->process);
        return array_diff($resources, $this->resources);
    }

    function isRunning()
    {
        if ($this->status != 'running' || $this->process == null)
            return false;
        $status = proc_get_status($this->process);
        return $status['running'];
    }

    function isForClean()
    {
        return $this->status == 'running' && !$this->isRunning();
    }
}