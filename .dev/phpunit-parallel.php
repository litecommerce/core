#!/usr/bin/env php
<?php

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
 * @category   LiteCommerce
 * @package    Tests
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 *
 */
define('PATH_TESTS', realpath(__DIR__ . '/tests'));
define('PATH_ROOT', realpath(__DIR__ . '/..'));

define('PATH_SRC', realpath(PATH_ROOT . '/src'));

set_include_path(
    get_include_path()
        . PATH_SEPARATOR . PATH_SRC . '/classes'
        . PATH_SEPARATOR . PATH_SRC . '/var/run/classes'
        . PATH_SEPARATOR . PATH_SRC
);

require_once PATH_SRC . '/top.inc.php';

parse_options();

function parse_options()
{
    $options = getopt("h", array("clients-count:", "log-junit", "verbose", "build"));

    if (isset($options['h'])) {
        print_info();
        die;
    }

    if (isset($options['build'])) {
        $options['verbose'] = true;
        $options['log-junit'] = true;
    }
    if (isset($options['clients-count']))
        define('SELENIUM_CLIENTS_COUNT', $options['clients-count']);

    if (isset($options['log-junit'])) {
        TestRunner::$log_xml = true;
        shell_exec("rm /tmp/phpunit*.xml");
    }
    if (isset($options['verbose']))
        TestRunner::$verbose = true;

    if (!defined('SELENIUM_CLIENTS_COUNT'))
        define('SELENIUM_CLIENTS_COUNT', 5);
    $runner = new testRunner();
    $runner->start(SELENIUM_CLIENTS_COUNT);
}

function print_info()
{
    print PHP_EOL .
        " Start parallel webtests.
    -h - print this info
    --clients-count - set selenium RC clients count, 5 by default
    --build - start with standard build configuration (--verbose --log-junit phpunit.xml)
    --log-junit, --verbose - options for phpunit". PHP_EOL;
}

class TestRunner
{

    /**
     * @var TestTask[]
     */
    protected $tests;
    /**
     * @var ResourcePool
     */
    protected $resources;
    /**
     * @var int
     */
    protected $clientsCount;

    public static $log_xml = false;
    public static $verbose = false;

    function __construct()
    {
        $this->tests = self::getTests();
        $this->tests = array_merge($this->tests, self::getFeatures());
        $this->resources = new ResourcePool();
        array_map(function (TestTask $test)
        {
            print $test->toString();
        }, $this->tests);
    }
    static private function getTests()
    {
        $testDir = 'tests' . DIRECTORY_SEPARATOR . 'Web';
        $classesDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $testDir . DIRECTORY_SEPARATOR;
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
                && !preg_match('/' . $ds . '(\w+WebDriver\w+)\.php/Ss', $filePath)
                && !preg_match('/' . $ds . '(?:scripts|skins)' . $ds . '/Ss', $filePath)
            ) {

                $tests[] = new TestTask($filePath, $classesDir);
            }
        }
        return $tests;

    }
    private static function getFeatures()
    {
        $testDir = 'tests' . DIRECTORY_SEPARATOR . 'Behat'.DIRECTORY_SEPARATOR.'features';
        $classesDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $testDir . DIRECTORY_SEPARATOR;
        $pattern = '/^' . preg_quote($classesDir, '/') . '(.*)\.feature$/';
        $dirIterator = new RecursiveDirectoryIterator($classesDir);
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
        $ds = preg_quote(DIRECTORY_SEPARATOR, '/');

        $tests = array();
        foreach ($iterator as $filePath => $fileObject) {

            if (
                preg_match($pattern, $filePath, $matches)
                && !empty($matches[1])
            ) {
                $tests[] = new TestTask($filePath, $classesDir, TestTask::BEHAT_FEATURE);
            }
        }
        return $tests;
    }

    function start($clientsCount)
    {
        $time = microtime(true);
        xlite_make_sql_backup();
        exec("rm /tmp/output*");
        $this->clientsCount = $clientsCount;
        while (true)
        {
            $this->run();

            while($this->isRunning()){
                sleep(5);
                $this->clean();
            }

            if ($this->isComplete())
                break;
        }
        $this->resources->reset();
        $time = round(microtime(true) - $time, 2);
        print PHP_EOL . " Total time: " . $time . "sec";
        exec('cat /tmp/output-* > /tmp/phpunit.txt');
    }

    private function isComplete()
    {
        return array_all(
            $this->tests,
            function($test)
            {
                return $test->status == 'complete' || $test->status == 'error' || $test->status == 'abstract';
            }
        );
    }

    private function isRunning()
    {
        return array_any(
            $this->tests,
            function($test)
            {
                return $test->status == 'running';
            }
        );
    }


    private function clean()
    {
        foreach ($this->tests as $test)
        {
            if ($test->isForClean()) {
                $test->stop($this->resources);
                $this->clientsCount++;
            }
        }
        if (!$this->isRunning()){
            $this->resources->reset();
        }
    }

    private function run()
    {
        foreach ($this->tests as $test)
        {
            if ($this->clientsCount == 0)
                return;

            if ($test->isForRun($this->resources)) {
                $test->run($this->resources);
                $this->clientsCount--;
            }
        }
    }
}


class TestTask
{
    const BEHAT_FEATURE = 1;
    const PHPUNIT_TEST = 0;
    public $name;
    public $resources = array();
    public $uses = array();
    public $block_all = false;
    public $status = 'init';
    public $type = self::PHPUNIT_TEST;
    /**
     * @var Resource
     */
    public $process = null;

    private $exitCode = 0;

    function __construct($filePath, $classesDir, $type = self::PHPUNIT_TEST)
    {
        $fileName = str_replace($classesDir, '', $filePath);
        $this->name = substr($fileName, 0, strpos($fileName, '.'));
        $this->type = $type;

        $source = file_get_contents($filePath);

        if($type == self::PHPUNIT_TEST){
            $comments = self::getPHPComments($source);
        } elseif($type == self::BEHAT_FEATURE){
            $comments = self::getGherkinComments($source);
        } else{
            $comments = array();
        }

        $resources = array();
        $uses = array();
        $block_all = false;
        foreach($comments as $comment){
            $resources = array_merge($resources, self::getResources($comment, 'resource'));
            $uses = array_merge($uses, self::getResources($comment, 'use'));
            $block_all = $block_all || preg_match('/^.*\@block_all\s*$/Sm', $comment) > 0;
        }

        if ($block_all) {
            $this->block_all = true;
        }
        else {
            $this->resources = $resources;
            $this->uses = $uses;
        }
    }

    private static function getGherkinComments($source){
        $result = array();
        if (preg_match_all('/#.*@(use|resource|block_all).+/', $source, $matches)){
            foreach($matches[0] as $comment){
                $result[] = $comment;
            }
        }
        return $result;
    }

    private static function getPHPComments($source){
        $result = array();
        $comments = token_get_all($source);
        foreach ($comments as $key => $comment) {
            if ($comment[0] == T_DOC_COMMENT) {
                $result[] = $comment[1];
            }
        }
        return $result;
    }

    private static function getResources($comment, $res_string)
    {
        preg_match_all('/^.*\@' . $res_string . '\s+([a-zA-Z_-]+)\s*$/Sm', $comment, $result);
        $resources = $result[1];
        foreach ($resources as $resource) {
            if (strpos($resource, '_')) {
                $parts = explode('_', $resource);
                $resource = $parts[0];
            }
        }
        return $resources;
    }

    function run(ResourcePool $resources)
    {
        $pipes = null;
        $testName = str_replace('/', '_', $this->name);
        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array("file", "/tmp/output-" . $testName . ".txt", "a"),
            2 => array("file", "/tmp/errors-" . $testName . ".txt", "a")
        );
        //Fake run
        //$this->process = proc_open("sleep " . rand(2, 4), $descriptorspec, $pipes);
        //Real run
        if ($this->type == self::PHPUNIT_TEST){
            $options = TestRunner::$log_xml ? ' --log-junit /tmp/phpunit.' . $testName . ".xml " : "";
            $options .= TestRunner::$verbose ? ' --verbose ' : "";
            $this->process = proc_open('./phpunit_no_restore.sh ' . $this->name . " " . $options, $descriptorspec, $pipes);

            //$this->process = proc_open("sleep " . rand(1, 2), $descriptorspec, $pipes);
        } elseif($this->type == self::BEHAT_FEATURE){
            $options = TestRunner::$log_xml ? '-f progress,junit --out ,/tmp/phpunit.'.$testName."xml " : "-f progress";
            $options .= TestRunner::$verbose ? ' --expand ' : "";
            //echo 'cd ./tests/Behat; bin/behat features/' . $this->name . ".feature " . $options;
            $this->process = proc_open('cd ./tests/Behat; bin/behat features/' . $this->name . ".feature " . $options, $descriptorspec, $pipes);
            //$this->process = proc_open("sleep " . rand(2, 4), $descriptorspec, $pipes);
        } else{
            $this->process = null;
        }


        if ($this->process) {
            print PHP_EOL . "Running test: " . $this->name;
            $this->status = 'running';
            $resources->allocate($this->resources, $this->uses, $this->block_all);
        }
        else {
            $this->status = 'error';
        }

    }

    function stop(ResourcePool $resources)
    {
        print PHP_EOL . "Stopping test: " . $this->name;
        if ($this->exitCode)
            print " - fail";
        $this->status = 'complete';
        proc_close($this->process);
        $resources->delocate($this->resources, $this->uses, $this->block_all);
    }

    private function isRunning()
    {
        if ($this->status != 'running' || $this->process == null)
            return false;
        $status = proc_get_status($this->process);
        if (!$status['running'])
            $this->exitCode = $status['exitcode'];
        return $status['running'];
    }

    function isForClean()
    {
        return $this->status == 'running' && !$this->isRunning();
    }

    function isForRun(ResourcePool $resources)
    {
        if ($this->status != 'init')
            return false;
        return $resources->checkAccess($this->resources, $this->uses, $this->block_all);
    }

    function toString()
    {
        return PHP_EOL . "Name: " . $this->name .
            PHP_EOL . " Resources: {" . implode(';', $this->resources) . '} ' .
            PHP_EOL . " Uses: {" . implode(';', $this->uses) . '}' .
            PHP_EOL . " Blocks all: <" . ($this->block_all ? 'true' : 'false') . "> " .
            PHP_EOL . " Status: <" . $this->status . ">" . PHP_EOL;
    }
}

class ResourcePool
{
    const RESOURCE_RESERVED = 'reserved';
    const RESOURCE_CLEARED = 'cleared';
    private $resources = array();
    private $used = array();
    private $block_all = false;

    public function isEmpty()
    {
        return empty($this->resources);
    }

    public function checkAccess($resources, $uses, $block)
    {
        //        print PHP_EOL . "Reserved resources: ";
        //        print_r($this->resources);
        //        print PHP_EOL . "Resources: ";
        //        print_r($resources);
        //        print PHP_EOL . "Uses: ";
        //        print_r($uses);

        if ($block && !empty($this->resources)) {
            return false;
        }
        if ($this->block_all !== false) {
            return false;
        }
        if (array_intersect(array_keys($this->resources), $resources)) {
            return false;
        }
        if (array_intersect(array_keys($this->used), $resources)) {
            return false;
        }
        foreach($uses as $use){
            if (array_key_exists($use, $this->resources) && $this->resources[$use] == ResourcePool::RESOURCE_RESERVED){
                return false;
            }
        }
        return true;
    }
    public function allocate($resources, $uses, $block = false){
        foreach($resources as $resource){
            $this->addResource($resource);
        }
        foreach($uses as $use){
            $this->addUse($use);
        }
        if ($block){
            $this->setBlock();
        }
    }

    public function delocate($resources, $uses, $block = false){
        foreach($uses as $use){
            $this->clearUse($use);
        }
        foreach($resources as $resource){
            $this->clearResource($resource);
        }
        if($block){
            $this->clearBlock();
        }
    }

    public function reset()
    {
        if ($this->block_all == ResourcePool::RESOURCE_RESERVED)
            throw new Exception("There is block resource");
        if (array_search(ResourcePool::RESOURCE_RESERVED, $this->resources) !== false) {
            print_r($this->resources);
            throw new Exception("There is some reserved or used resources");
        }
        $this->resources = array();
        $this->used = array();
        $this->block_all = false;
        xlite_restore_sql_from_backup();
        sleep(2);
    }

    public function isCleared()
    {
        if($this->block_all === ResourcePool::RESOURCE_CLEARED){
            return true;
        }
        if (empty($this->used) && !empty($this->resources)
            && array_all($this->resources,
                function ($res)
                {
                    return $res == ResourcePool::RESOURCE_CLEARED;
                }))
        {
            return true;
        }
        return false;
    }

    private function addUse($use){
        if (array_key_exists($use, $this->resources) && $this->resources[$use] == ResourcePool::RESOURCE_RESERVED)  {
            print_r($this->resources);
            throw new Exception("Resource " . $use . " is reserved!");
        }
        if (isset($this->used[$use]))
            $this->used[$use]++;
        else
            $this->used[$use] = 1;
    }

    private function clearUse($use){
        if (!array_key_exists($use, $this->used)){
            print_r($this->used);
            throw new Exception("Resource " . $use . " not used!");
        }
        if (array_key_exists($use, $this->resources) && $this->resources[$use] == ResourcePool::RESOURCE_RESERVED){
            print_r($this->resources);
            throw new Exception("Resource " . $use . " is reserved!");

        }
        $this->used[$use]--;
        if ($this->used[$use] == 0)
            unset($this->used[$use]);
    }

    private function addResource($resource)
    {
        if (array_key_exists($resource, $this->resources) || array_key_exists($resource, $this->used)){
            print_r($this->resources);
            throw new Exception("Resource " . $resource . " is reserved or used!");

        }
        $this->resources[$resource] = ResourcePool::RESOURCE_RESERVED;
    }

    private function clearResource($resource)
    {
        if (!array_key_exists($resource, $this->resources))
            return;
        //throw new Exception("No such resource: " .$resource);
        $this->resources[$resource] = ResourcePool::RESOURCE_CLEARED;
    }

    private function setBlock(){
        $this->block_all = ResourcePool::RESOURCE_RESERVED;
    }
    private function clearBlock(){
        $this->block_all = ResourcePool::RESOURCE_CLEARED;
    }

}

/**
 * @param array $array
 * @param closure $callback
 * @return bool
 */
function array_any(array $array, $callback)
{
    foreach ($array as $element) {
        if (call_user_func($callback, $element))
            return true;
    }
    return false;
}

/**
 * @param array $array
 * @param closure $callback
 * @return bool
 */
function array_all(array $array, $callback)
{
    return !array_any(
        $array,
        function ($el) use ($callback)
        {
            return !call_user_func($callback, $el);
        });
}

function xlite_restore_sql_from_backup($path = null, $verbose = true, $drop = true, &$message = null)
{
    !$verbose && ob_start();

    echo (PHP_EOL . 'DB restore ... ');

    \Includes\Utils\FileManager::copyRecursive(__DIR__ . '/images', LC_DIR_IMAGES);

    $result = true;

    if (!isset($path)) {
        $path = dirname(__FILE__) . LC_DS . 'dump.sql';
    }

    if (file_exists($path)) {

        $config = \XLite::getInstance()->getOptions('database_details');

        $cmd = defined('TEST_MYSQL_BIN') ? TEST_MYSQL_BIN : 'mysql';
        $cmd .= ' -h' . $config['hostspec'];

        if ($config['port']) {
            $cmd .= ' -P' . $config['port'];
        }

        $cmd .= ' -u' . $config['username'] . ('' == $config['password'] ? '' : (' -p' . $config['password']));

        if ($config['socket']) {
            $cmd .= ' -S' . $config['socket'];
        }

        $message = '';

        if ($drop) {

            // Drop&Create database

            exec($cmd . ' -e"drop database ' . $config['database'] . '"', $message);

            if (empty($message)) {
                exec($cmd . ' -e"create database ' . $config['database'] . '"', $message);
            }
        }

        if (empty($message)) {
            exec($cmd . ' ' . $config['database'] . ' < ' . $path, $message);
        }

        if (empty($message)) {
            echo ('done' . PHP_EOL);

        } else {
            $result = false;
            echo ('failed: ' . $message . PHP_EOL);
        }

    } else {
        echo ('ignored (sql-dump file not found)' . PHP_EOL);
        $result = false;
    }

    !$verbose && ob_end_clean();

    return $result;
}

function xlite_make_sql_backup($path = null)
{
    // DB backup
    echo (PHP_EOL . 'DB backup ... ');

    \Includes\Utils\FileManager::unlinkRecursive(__DIR__ . '/images');
    \Includes\Utils\FileManager::mkdirRecursive(__DIR__ . '/images');
    \Includes\Utils\FileManager::mkdirRecursive(__DIR__ . '/images/product');
    \Includes\Utils\FileManager::mkdirRecursive(__DIR__ . '/images/category');
    \Includes\Utils\FileManager::copyRecursive(LC_DIR_IMAGES, __DIR__ . '/images');

    $result = true;

    if (!isset($path)) {
        $path = dirname(__FILE__) . LC_DS . 'dump.sql';
    }

    if (file_exists(dirname($path))) {

        if (file_exists($path)) {
            unlink($path);
        }

        $config = \XLite::getInstance()->getOptions('database_details');

        $cmd = defined('TEST_MYSQLDUMP_BIN') ? TEST_MYSQLDUMP_BIN : 'mysqldump';
        $cmd .= ' --opt -h' . $config['hostspec'];

        if ($config['port']) {
            $cmd .= ' -P' . $config['port'];
        }

        $cmd .= ' -u' . $config['username'] . ('' == $config['password'] ? '' : (' -p' . $config['password']));

        if ($config['socket']) {
            $cmd .= ' -S' . $config['socket'];
        }

        $cmd .= ' ' . $config['database'];

        exec('echo "SET autocommit=0;
        SET unique_checks=0;
        SET foreign_key_checks=0;" > ' . $path . '
        ' . $cmd . ' >> ' . $path . '
        echo "COMMIT;" >> ' . $path);

        echo ('done' . PHP_EOL);

        sleep(1);

    } else {
        $result = false;
    }

    if (!$result) {
        echo ('ignored' . PHP_EOL);
    }

    return $result;
}
