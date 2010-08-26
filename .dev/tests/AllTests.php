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
define('PATH_TESTS', realpath(dirname(__FILE__)));
define('PATH_ROOT', realpath(dirname(__FILE__) . '/../..'));

if (file_exists(PATH_ROOT . '/src')) {
    define('PATH_SRC', realpath(PATH_ROOT . '/src'));
} else {
    define('PATH_SRC', realpath(PATH_ROOT . '/src'));
}

set_include_path(
    get_include_path()
    . PATH_SEPARATOR . '/usr/local/share/pear/'
    . PATH_SEPARATOR . PATH_SRC . '/classes'
    . PATH_SEPARATOR . PATH_SRC . '/var/run/classes'
);

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once PATH_TESTS . '/PHPUnit/TestSuite.php';
require_once PATH_TESTS . '/PHPUnit/TestCase.php';
require_once PATH_TESTS . '/PHPUnit/MetricWriter.php';
require_once PATH_TESTS . '/PHPUnit/SeleniumTestCase.php';

// Start X-Lite core

require_once PATH_SRC . '/top.inc.php';

// Include local code
if (file_exists(__DIR__ . '/local.php')) {
    require_once __DIR__ . '/local.php';
}

if (!defined('SELENIUM_SOURCE_URL')) {
    $arr = explode('/', realpath(__DIR__ . '/../..'));
    array_shift($arr);
    array_shift($arr);
    array_shift($arr);
    array_shift($arr);

    define('SELENIUM_SOURCE_URL', 'http://xcart2-530.crtdev.local/~' . posix_getlogin() . '/' . implode('/', $arr));

    unset($arr);
}

if (isset($_SERVER['argv']) && preg_match('/--log-xml\s+(\S+)\s/s', implode(' ', $_SERVER['argv']), $match)) {
    XLite_Tests_MetricWriter::init($match[1] . '.speed');
    unset($match);
}

PHPUnit_Util_Filter::addDirectoryToFilter(PATH_ROOT . '/.dev');
PHPUnit_Util_Filter::addDirectoryToFilter(PATH_SRC . '/etc');
PHPUnit_Util_Filter::addDirectoryToWhitelist(PATH_SRC . '/var/run/classes');

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
        if (defined('INCLUDE_ONLY_TESTS')) {
            $includes = array_map('trim', explode(' ', INCLUDE_ONLY_TESTS));
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
                require_once $filePath;
                $suite->addTestSuite(XLite_Tests_TestCase::CLASS_PREFIX . str_replace(DIRECTORY_SEPARATOR, '_', $matches[1]));
            }
        }	

        // Web tests
        $classesDir  = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Web' . DIRECTORY_SEPARATOR;
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
                require_once $filePath;
                $suite->addTestSuite(XLite_Tests_SeleniumTestCase::CLASS_PREFIX . str_replace(DIRECTORY_SEPARATOR, '_', $matches[1]));
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

