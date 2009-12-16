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

define('PATH_TESTS', realpath(dirname(__FILE__)));
define('PATH_ROOT', realpath(dirname(__FILE__) . '/../..'));

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once PATH_TESTS . '/PHPUnit/TestSuite.php';
require_once PATH_TESTS . '/PHPUnit/TestCase.php';
require_once PATH_TESTS . '/PHPUnit/MetricWriter.php';
require_once PATH_TESTS . '/PHPUnit/SeleniumTestCase.php';

// X-Lite classes

chdir(PATH_ROOT . '/src');

require_once PATH_ROOT . '/src/includes/prepend.php';

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
PHPUnit_Util_Filter::addDirectoryToFilter(PATH_ROOT . '/src/etc');

// File to check coverage

$classes = array();
$files = array();

$dirIterator = new RecursiveDirectoryIterator(PATH_ROOT . '/src/lib5');
$iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
foreach ($iterator as $filePath => $fileObject) {
    if ($fileObject->isFile() && preg_match('/\.php$/Ss', $fileObject->getFilename()) && $fileObject->getFilename() != 'Var_Dump.php') {
        $data = file_get_contents($filePath);
        if (preg_match_all('/^\s*class\s+(\S+)(?:\s+extends\s+(\S+))?\s*(?:$|\{)/USm', $data, $match)) {
            foreach ($match[1] as $k => $v) {
                $classes[strtolower($v)] = array(
                    'path' => $filePath,
                    'parent' => isset($match[2][$k]) ? strtolower($match[2][$k]) : false,
                    'childs' => array(),
                    'added' => false,
                );

            }
        }
        $files[$filePath] = array_map('strtolower', $match[1]);
    }
}

$dirIterator = new RecursiveDirectoryIterator(PATH_ROOT . '/src/classes');
$iterator    = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

$excludeModules = array('Promotion', 'DetailedImages', 'InventoryTracking', 'ProductOptions', 'WholesaleTrading', 'Egoods', 'AdvancedSecurity', 'ProductAdviser', 'AOM');
foreach ($iterator as $filePath => $fileObject) {
    if (
        $fileObject->isFile()
        && preg_match('/\.php$/Ss', $fileObject->getFilename())
        && !preg_match('/modules\/(' . implode('|', $excludeModules) . ')\//Ss', $filePath)
    ) {
        $data = file_get_contents($filePath);
        if (preg_match_all('/^\s*class\s+(\S+)(?:\s+extends\s+(\S+))?\s*$/USm', $data, $match)) {
            foreach ($match[1] as $k => $v) {
                $classes[strtolower($v)] = array(
                    'path' => $filePath,
                    'parent' => isset($match[2][$k]) ? strtolower($match[2][$k]) : false,
                    'childs' => array(),
                    'added' => false,
                );
                
            }
        }
        $files[$filePath] = array_map('strtolower', $match[1]);
    }
}

foreach ($classes as $k => $v) {
    foreach ($files[$v['path']] as $sub) {
        if ($classes[$sub]['parent'] && isset($classes[$classes[$sub]['parent']])) {
        
            $parent = $classes[$sub]['parent'];
            $parents = array();
            while ($parent) {
                $parents[] = $parent;
                $parent = $classes[$parent]['parent'];
            }

            foreach (array_reverse($parents) as $p) {
                if (!$classes[$p]['added']) {
                    PHPUnit_Util_Filter::addFileToWhitelist($classes[$p]['path']);
                    $classes[$p]['added'] = true;
                }
            }
        }
    }
    PHPUnit_Util_Filter::addFileToWhitelist($v['path']);
    $classes[$k]['added'] = true;
}

unset($classes, $data, $k, $v, $p, $filePath, $fileObject, $match, $parent, $parents, $files, $sub);


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

//        error_reporting(E_ERROR | E_NOTICE | E_PARSE);
        error_reporting(E_ALL);

        return $suite;
    }
}

// Execute
if (PHPUnit_MAIN_METHOD === 'XLite_Tests_AllTests::main') {
    XLite_Tests_AllTests::main();
}

