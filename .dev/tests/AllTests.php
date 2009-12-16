<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * File to run all the tests
 *  
 * @category   X-Lite_Tests
 * @package    X-Lite_Tests
 * @subpackage Main
 * @author     Ruslan R. Fazliev <rrf@x-cart.com> 
 * @copyright  Copyright (c) 2009 Ruslan R. Fazliev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php LiteCommerce license
 * @version    SVN: $Id$
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

if (false === defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'XLite_Tests_AllTests::main');
}

// PHPUnit classes
set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/local/share/pear/');

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

// X-Lite classes
define('PATH_TESTS', realpath(dirname(__FILE__)));
define('PATH_ROOT', realpath(dirname(__FILE__) . '/../..'));

if (!defined('SELENIUM_SOURCE_URL')) {
    $arr = explode('/', realpath(__DIR__ . '/../..'));
    array_shift($arr);
    array_shift($arr);
    array_shift($arr);
    array_shift($arr);

    define('SELENIUM_SOURCE_URL', 'http://xcart2-530.crtdev.local/~' . posix_getlogin() . '/' . implode('/', $arr));

    unset($arr);
}

require_once PATH_ROOT . '/src/top.inc.php';

require_once PATH_TESTS . '/PHPUnit/TestSuite.php';
require_once PATH_TESTS . '/PHPUnit/TestCase.php';
require_once PATH_TESTS . '/PHPUnit/MetricWriter.php';
require_once PATH_TESTS . '/PHPUnit/SeleniumTestCase.php';

if (isset($_SERVER['argv']) && preg_match('/--log-xml\s+(\S+)\s/s', implode(' ', $_SERVER['argv']), $match)) {
    XLite_Tests_MetricWriter::init($match[1] . '.speed');
    unset($match);
}

PHPUnit_Util_Filter::addDirectoryToFilter(PATH_ROOT . '/.dev');
PHPUnit_Util_Filter::addDirectoryToFilter(PATH_ROOT . '/src/config');

// File to check coverage
/*
$excludeDirs = array();

foreach (new DirectoryIterator(PATH_ROOT . '/src/lib/XLite') as $handler) {

    if (!$handler->isDot()) {

		$fileName = $handler->getFilename();

		if (in_array($fileName, $excludeDirs)) {
			$filterMethod = 'addDirectoryToFilter';
		} elseif ($handler->isDir()) {
			$filterMethod = 'addDirectoryToWhitelist';
		} elseif (preg_match('/\.php$/Ss', $fileName)) {
			$filterMethod = 'addFileToWhitelist';
		} else {
            $filterMethod = 'addFileToFilter';
        }

  	    PHPUnit_Util_Filter::$filterMethod(PATH_ROOT . '/src/' . $fileName);
	}
}

unset($handler);
*/

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

        $dirIterator = new RecursiveDirectoryIterator($classesDir . XP_DS);
        $iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $filePath => $fileObject) {
            if (preg_match($pattern, $filePath, $matches)) {
                require_once $filePath;
            }
        }

        // Classes tests
		$classesDir  = dirname( __FILE__ ) . XP_DS . 'Classes' . XP_DS;
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
                $suite->addTestSuite(XLite_Tests_TestCase::CLASS_PREFIX . str_replace(XP_DS, '_', $matches[1]));
            }
        }	

        // Web tests
        $classesDir  = dirname( __FILE__ ) . XP_DS . 'Web' . XP_DS;
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
                $suite->addTestSuite(XLite_Tests_SeleniumTestCase::CLASS_PREFIX . str_replace(XP_DS, '_', $matches[1]));
            }
        } 


        error_reporting(E_ALL | E_STRICT);

        return $suite;
    }
}

// Execute
if (PHPUnit_MAIN_METHOD === 'XLite_Tests_AllTests::main') {
    XLite_Tests_AllTests::main();
}

