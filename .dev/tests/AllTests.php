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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

if (false === defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'XLite_Tests_AllTests::main');
}

// PHPUnit classes
define('PATH_TESTS', realpath(__DIR__));
define('PATH_ROOT', realpath(__DIR__ . '/../..'));
define('PATH_SRC', realpath(PATH_ROOT . '/src'));

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . '/usr/local/share/pear/'
    . PATH_SEPARATOR . PATH_SRC . '/classes'
    . PATH_SEPARATOR . PATH_SRC . '/var/run/classes'
);

// Include local code
if (file_exists(__DIR__ . '/local.php')) {
    require_once __DIR__ . '/local.php';
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once PATH_TESTS . '/PHPUnit/TestSuite.php';
require_once PATH_TESTS . '/PHPUnit/TestCase.php';
require_once PATH_TESTS . '/PHPUnit/MetricWriter.php';
require_once PATH_TESTS . '/PHPUnit/SeleniumTestCase.php';

// Start X-Lite core

require_once PATH_SRC . '/top.inc.php';

if (!defined('SELENIUM_SOURCE_URL')) {
    $arr = explode('/', realpath(__DIR__ . '/../..'));
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

if (isset($_SERVER['argv']) && preg_match('/--log-xml\s+(\S+)\s/s', implode(' ', $_SERVER['argv']), $match)) {
    XLite_Tests_MetricWriter::init($match[1] . '.speed');
    unset($match);
}

PHPUnit_Util_Filter::addDirectoryToFilter(PATH_ROOT . '/.dev');
PHPUnit_Util_Filter::addDirectoryToFilter(PATH_SRC . '/etc');
PHPUnit_Util_Filter::addDirectoryToWhitelist(PATH_SRC . '/var/run/classes');
PHPUnit_Util_Filter::addDirectoryToFilter(PATH_SRC . '/var/run/classes/XLite/Model/Proxy');

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
     * @access public
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
        $suite = new XLite_Tests_TestSuite('LiteCommerce - AllTests');

        $includes = false;
        $includeTests = array();
        if (defined('INCLUDE_ONLY_TESTS')) {
            $includes = array_map('trim', explode(',', INCLUDE_ONLY_TESTS));

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

            $deploy = (in_array('DEPLOY_DRUPAL', $includes) ? 'Drupal' :( in_array('DEPLOY_STANDALONE', $includes) ? 'Standalone' : null));

            if (!is_null($deploy)) {
                if (!defined('UNITS_DISABLED')) {
                    define('UNITS_DISABLED', true);
                }
                $k = array_search('DEPLOY_' . strtoupper($deploy), $includes);
                if (!defined('DIR_TESTS')) {
                    define('DIR_TESTS', 'Deploy' . DIRECTORY_SEPARATOR . $deploy);
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
                $tmp = explode(':', $v, 2);
                $includes[$k] = $tmp[0];
                if (isset($tmp[1])) {
                    $includeTests[$tmp[0]] = $tmp[1];
                }
            }
        }

        // Include abstract classes
        $classesDir  = dirname( __FILE__ );
        $pattern     = '/^' . preg_quote($classesDir, '/') . '.*\/Abstract\.php$/';

        $dirIterator = new RecursiveDirectoryIterator($classesDir . DIRECTORY_SEPARATOR);
        $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $filePath => $fileObject) {
            if (preg_match($pattern, $filePath, $matches)) {
                require_once $filePath;
            }
        }

        // Classes tests
        if (!defined('UNITS_DISABLED')) {
            $classesDir  = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR;
            $pattern     = '/^' . str_replace('/', '\/', preg_quote($classesDir)) . '(.*)\.php$/';

            $dirIterator = new RecursiveDirectoryIterator($classesDir);
            $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($iterator as $filePath => $fileObject) {
                if (
                    preg_match($pattern, $filePath, $matches)
                    && !empty($matches[1])
                    && !preg_match('/\/Abstract.php/Ss', $filePath)
                    && (!$includes || in_array($matches[1], $includes))
                ) {
                    $class = XLite_Tests_TestCase::CLASS_PREFIX
                        . str_replace(DIRECTORY_SEPARATOR, '_', $matches[1]);

                    require_once $filePath;
                    $suite->addTestSuite($class);

                    if (isset($includeTests[$matches[1]])) {
                        eval($class . '::$testsRange = array($includeTests[$matches[1]]);');
                    }

                }
            }
        }

        // Web tests
        if (!defined('SELENIUM_DISABLED')) {

            if (!defined('DIR_TESTS')) {
                define('DIR_TESTS', 'Web');
            }

            $classesDir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . constant('DIR_TESTS') . DIRECTORY_SEPARATOR;
            $pattern    = '/^' . str_replace('/', '\/', preg_quote($classesDir)) . '(.*)\.php$/';

            $dirIterator = new RecursiveDirectoryIterator($classesDir);
            $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($iterator as $filePath => $fileObject) {
                if (
                    preg_match($pattern, $filePath, $matches)
                    && !empty($matches[1])
                    && !preg_match('/\/Abstract.php/Ss', $filePath)
                    && !preg_match('/\/A[A-Z]/Ss', $filePath)
                    && (!$includes || in_array($matches[1], $includes))
                ) {

                    $classPrefix = !isset($deploy) ? XLite_Tests_SeleniumTestCase::CLASS_PREFIX : 'XLite_Deploy_' . $deploy . '_';
                    $class = $classPrefix
                        . str_replace(DIRECTORY_SEPARATOR, '_', $matches[1]);

                    require_once $filePath;
                    $suite->addTestSuite($class);

                    if (isset($includeTests[$matches[1]])) {
                        eval($class . '::$testsRange = array($includeTests[$matches[1]]);');
                    }
                }
            } 
        }

        error_reporting(E_ALL);

        return $suite;
    }
}

// Execute
if (PHPUnit_MAIN_METHOD === 'XLite_Tests_AllTests::main') {
    XLite_Tests_AllTests::main();
}
