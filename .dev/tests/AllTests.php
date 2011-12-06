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
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */
if (0 > version_compare(phpversion(), '5.3.0')) {
    echo ('PHP version must be 5.3.0 or later' . PHP_EOL);
    die(1);
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

        exec($cmd .= ' ' . $config['database'] . ' > ' . $path);

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

            exec($cmd . ' -e"drop database ' . $config['database'] . '"' , $message);

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

function xlite_clean_up_cache()
{
    echo (PHP_EOL.'Clean up the cache ... ');

    \Includes\Utils\FileManager::unlinkRecursive(LC_DIR_DATACACHE);

    echo ('done' . PHP_EOL);
}


if (false === defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'XLite_Tests_AllTests::main');
}

if (!defined('ROOT_TEST_SUITE_NAME')) {
    define('ROOT_TEST_SUITE_NAME', 'LiteCommerce - AllTests');
}

ini_set('memory_limit', '900M');

// PHPUnit classes
define('PATH_TESTS', realpath(__DIR__));
define('PATH_ROOT', realpath(__DIR__ . '/../..'));

// Include local code
if (file_exists(PATH_TESTS . '/local.php')) {
    require_once PATH_TESTS . '/local.php';
}

if (defined('DRUPAL_SITE_PATH') && !defined('LOCAL_TESTS')) {
    define('PATH_SRC', realpath(DRUPAL_SITE_PATH . '/modules/lc_connector/litecommerce'));

} else {
    define('PATH_SRC', realpath(PATH_ROOT . '/src'));
}

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . PATH_SRC . '/classes'
    . PATH_SEPARATOR . PATH_SRC . '/var/run/classes'
);

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once PATH_TESTS . '/PHPUnit/TestSuite.php';
require_once PATH_TESTS . '/PHPUnit/TestCase.php';
require_once PATH_TESTS . '/PHPUnit/MetricWriter.php';
require_once PATH_TESTS . '/PHPUnit/SeleniumTestCase.php';
require_once PATH_TESTS . '/PHPUnit/WebDriverTestCase.php';

if (!defined('MENU_LOCAL_TASK')) {
    define('MENU_LOCAL_TASK', 0x0080 | 0x0004);
}

// Start X-Lite core

define('LC_DO_NOT_REBUILD_CACHE', true);

if (
    defined('INCLUDE_ONLY_TESTS')
    && preg_match('/DEPLOY_/', constant('INCLUDE_ONLY_TESTS'))
    && !defined('XLITE_INSTALL_MODE')
) {
    define('XLITE_INSTALL_MODE', true);
}

require_once PATH_SRC . '/top.inc.php';

if (!defined('SELENIUM_SOURCE_URL')) {
    $arr = explode(LC_DS, realpath(__DIR__ . LC_DS . '..' . LC_DS . '..'));
    array_shift($arr);
    array_shift($arr);
    array_shift($arr);
    array_shift($arr);

    define('SELENIUM_SOURCE_URL', 'http://xcart2-530.crtdev.local/~' . posix_getlogin() . '/' . implode('/', $arr));

    unset($arr);
}

if (!defined('SELENIUM_SERVER')) {
    define('SELENIUM_SERVER', 'cormorant.crtdev.local');
}

if (!defined('TESTS_LOG_DIR')) {
    define('TESTS_LOG_DIR', LC_DIR_VAR . 'log' . LC_DS);
}

if (isset($_SERVER['argv']) && preg_match('/--log-xml\s+(\S+)\s/s', implode(' ', $_SERVER['argv']), $match)) {
    XLite_Tests_MetricWriter::init($match[1] . '.speed');
    unset($match);
}

if (!defined('INCLUDE_ONLY_TESTS') || !preg_match('/DEPLOY_/', constant('INCLUDE_ONLY_TESTS'))) {
    PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist(PATH_ROOT . LC_DS . '.dev');
    PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist(PATH_SRC . LC_DS . 'etc');
    PHP_CodeCoverage_Filter::getInstance()->addDirectoryToWhitelist(PATH_SRC . LC_DS . 'var' . LC_DS . 'run' . LC_DS . 'classes');
    PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist(PATH_SRC . LC_DS . 'var' . LC_DS . 'run' . LC_DS . 'classes' . LC_DS . 'XLite' . LC_DS . 'Model' . LC_DS . 'Proxy');
}

foreach (glob(LC_DIR_ROOT . 'var/log/selenium.*.html') as $f) {
    @unlink($f);
}


/**
 * Class to sort out files gathered by RecursiveIteratorIterator
 *
 * @package    X-Lite_Tests
 * @subpackage Main
 * @see        ____class_see____
 * @since      1.0.10
 */
class XLite_Tests_SortedIterator extends SplHeap
{
    public function __construct(Iterator $iterator)
    {
        foreach ($iterator as $item) {
            $this->insert($item);
        }
    }

    public function compare($a, $b)
    {
        return strcmp($b->getRealpath(), $a->getRealpath());
    }
};

/**
 * Class to run all the tests
 *
 * @package    X-Lite_Tests
 * @subpackage Main
 * @see        ____class_see____
 * @since      1.0.0
 */
class XLite_Tests_AllTests
{
    /**
     * Test suite main method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Creates the phpunit test suite
     *
     * @return XLite_Tests_TestSuite
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function suite()
    {
        $suite = new XLite_Tests_TestSuite(ROOT_TEST_SUITE_NAME);

        $deploy = null;
        $includes = false;
        $includeTests = array();
        $excludes = array();
        $ds = preg_quote(LC_DS, '/');


        if (defined('INCLUDE_ONLY_TESTS')) {

            $includes = array_map('trim', explode(',', INCLUDE_ONLY_TESTS));

            if (in_array('LOCAL_TESTS', $includes)) {
                $k = array_search('LOCAL_TESTS', $includes);
                unset($includes[$k]);
            }

            if (in_array('NOWEB', $includes)) {
                if (!defined('SELENIUM_DISABLED')) {
                    define('SELENIUM_DISABLED', true);
                }
                $k = array_search('NOWEB', $includes);
                unset($includes[$k]);
            }

            if (in_array('ONLYWEB', $includes)) {
                if (!defined('UNITS_DISABLED')) {
                    define('UNITS_DISABLED', true);
                }
                $k = array_search('ONLYWEB', $includes);
                unset($includes[$k]);
            }

            if (in_array('DEPLOY_DRUPAL', $includes)) {
                $deploy = 'Drupal';

            } elseif (in_array('DEPLOY_STANDALONE', $includes)) {
                $deploy = 'Standalone';
            }

            if (!is_null($deploy)) {
                if (!defined('UNITS_DISABLED')) {
                    define('UNITS_DISABLED', true);
                }
                $k = array_search('DEPLOY_' . strtoupper($deploy), $includes);
                if (!defined('DIR_TESTS')) {
                    define('DIR_TESTS', 'Deploy' . LC_DS . $deploy);
                }
                unset($includes[$k]);
            }

            if (in_array('W3C', $includes)) {
                if (!defined('W3C_VALIDATION')) {
                    define('W3C_VALIDATION', true);
                }
                $k = array_search('W3C', $includes);
                unset($includes[$k]);
            }

            foreach ($includes as $k => $v) {
                if ('-' == substr($v, 0, 1)) {
                    $excludes[] = substr($v, 1);
                    unset($includes[$k]);
                }
            }

            foreach ($includes as $k => $v) {
                $tmp = explode(':', $v, 2);
                $includes[$k] = $tmp[0];
                if (isset($tmp[1])) {
                    $includeTests[$tmp[0]] = $tmp[1];
                }
            }
        }


        if (isset($deploy) && !defined('DEPLOYMENT_TEST')) {
            define('DEPLOYMENT_TEST', true);
        }

        // Include abstract classes
        $classesDir  = dirname( __FILE__ );
        $pattern     = '/^' . preg_quote($classesDir, '/') . '.*' . $ds . '(?:\w*Abstract|A[A-Z][a-z]\w*)\.php$/Ss';

        $dirIterator = new RecursiveDirectoryIterator($classesDir . LC_DS);
        $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $filePath => $fileObject) {
            if (preg_match($pattern, $filePath, $matches)) {
                require_once $filePath;
            }
        }

        // Include fake classes
        if (!defined('DEPLOYMENT_TEST')) {
            $classesDir  = dirname( __FILE__ ) . LC_DS . 'FakeClass' . LC_DS;
            $pattern     = '/^' . preg_quote($classesDir, '/') . '.+\.php$/Ss';

            $dirIterator = new RecursiveDirectoryIterator($classesDir . LC_DS);
            $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($iterator as $filePath => $fileObject) {
                if (preg_match($pattern, $filePath, $matches)) {
                    require_once $filePath;
                }
            }
        }

        if (!isset($deploy) || !$deploy) {
            \Includes\Utils\FileManager::unlinkRecursive(__DIR__ . '/images');
            \Includes\Utils\FileManager::mkdirRecursive(__DIR__ . '/images');
            \Includes\Utils\FileManager::copyRecursive(LC_DIR_IMAGES, __DIR__ . '/images/');
            xlite_make_sql_backup();
        }

        // Classes tests
        if (!defined('UNITS_DISABLED')) {

            $classesDir  = dirname( __FILE__ ) . LC_DS . 'Classes' . LC_DS;
            $pattern     = '/^' . preg_quote($classesDir, '/') . '(.*)\.php$/';

            $dirIterator = new RecursiveDirectoryIterator($classesDir);
            $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
            $siterator   = new XLite_Tests_SortedIterator($iterator);

            foreach ($siterator as $filePath) {

                if (
                    preg_match($pattern, $filePath, $matches)
                    && !empty($matches[1])
                    && !preg_match('/' . $ds . '(\w+Abstract|A[A-Z]\w+)\.php$/Ss', $filePath)
                    && !preg_match('/' . $ds . '(?:scripts|skins)' . $ds . '/Ss', $filePath)
                ) {

                    $matched = str_replace(LC_DS, '/', $matches[1]);

                    if (
                        (!$includes || static::isPatternInArray($matched, $includes))
                        && (!$excludes || !static::isPatternInArray($matched, $excludes))
                    ) {

                        $class = XLite_Tests_TestCase::CLASS_PREFIX
                            . str_replace('/', '_', $matched);

                        require_once $filePath;

                        $suite->addTest(new XLite_Tests_TestSuite(new ReflectionClass($class)));

                        // Limit test range by a specific test if it was specified in call. Example: ./phpunit-report.sh Model/Zone:create
                        if (isset($includeTests[$matched])) {
                            eval($class . '::$testsRange = array($includeTests[$matched]);');
                        }

                    }
                }
            }
        }

        // Web tests
        if (!defined('SELENIUM_DISABLED')) {

            if (!defined('DIR_TESTS')) {
                define('DIR_TESTS', 'Web');
            }

            $classesDir = dirname( __FILE__ ) . LC_DS . constant('DIR_TESTS') . LC_DS;
            $pattern    = '/^' . preg_quote($classesDir, '/') . '(.*)\.php$/';

            $dirIterator = new RecursiveDirectoryIterator($classesDir);
            $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
            $siterator   = new XLite_Tests_SortedIterator($iterator);

            foreach ($siterator as $filePath) {

                if (
                    preg_match($pattern, $filePath, $matches)
                    && !empty($matches[1])
                    && !preg_match('/' . $ds . '(\w+Abstract|A[A-Z]\w+)\.php/Ss', $filePath)
                    && !preg_match('/' . $ds . '(?:scripts|skins)' . $ds . '/Ss', $filePath)
                ) {

                    $matched = str_replace(LC_DS, '/', $matches[1]);

                    if (
                        (!$includes || static::isPatternInArray($matched, $includes))
                        && (!$excludes || !static::isPatternInArray($matched, $excludes))
                    ) {

                        $classPrefix = !isset($deploy)
                            ? XLite_Tests_SeleniumTestCase::CLASS_PREFIX
                            : 'XLite_Deploy_' . $deploy . '_';

                        $class = $classPrefix . str_replace('/', '_', $matched);

                        require_once $filePath;

                        $seleniumSuite = new PHPUnit_Framework_TestSuite();
                        $seleniumSuite->addTestSuite($class);

                        $suite->addTest($seleniumSuite);

                        // Limit test range by a specific test if it was specified in call. Example: ./phpunit-report.sh Model/Zone:create
                        if (isset($includeTests[$matched])) {
                            eval($class . '::$testsRange = array($includeTests[$matched]);');
                        }
                    }
                }
            }
        }

        error_reporting(E_ALL);

        return $suite;
    }


    /**
     * Return regexp-pattern from array values
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getIncludesPattern(array $array)
    {
        array_walk(
            $array,
            create_function('&$v', '$v = preg_quote($v, "/");')
        );

        return '/(' . implode(')|(', $array) . ')/';
    }

    /**
     * Returns true if $search matches to the patterns in the $subject array
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function isPatternInArray($search, $subject)
    {
        $result = preg_match(static::getIncludesPattern($subject), $search);

        return $result;
    }
}

// Execute
if (PHPUnit_MAIN_METHOD === 'XLite_Tests_AllTests::main') {
    XLite_Tests_AllTests::main();
}
