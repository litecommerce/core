<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce test suite
 *
 * @category   LiteCommerce_Tests
 * @package    LiteCommerce_Tests
 * @subpackage Main
 * @author     Ruslan R. Fazliev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2009 Ruslan R. Fazliev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php LiteCommerce license
 * @version    SVN: $Id$
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

final class XLite_Tests_TestSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * Current test class
     *
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  1.0.0
     */
    public static $currentClass = '';

    /**
     * Runs the tests and collects their result in a TestResult.
     *
     * @param  PHPUnit_Framework_TestResult $result
     * @param  mixed                        $filter
     * @param  array                        $groups
     * @param  array                        $excludeGroups
     * @return PHPUnit_Framework_TestResult
     * @throws InvalidArgumentException
     */
    public function run(PHPUnit_Framework_TestResult $result = NULL, $filter = FALSE, array $groups = array(), array $excludeGroups = array())
    {
        $isTopSuite = true;
        foreach ($this->tests as $t) {
            if ($t instanceof PHPUnit_Framework_TestCase) {
                $isTopSuite = false;
                break;
            }
        }

        $r = parent::run($result, $filter, $groups, $excludeGroups);

        if (!$isTopSuite) {
            $this->restoreDBState();
        }

        return $r;
    }

    protected function restoreDBState()
    {
        $path = realpath(dirname(__FILE__) . '/../dump.sql');
        if (!file_exists($path)) {
            return false;
        }

        echo (PHP_EOL . 'DB restore ...');

        $config = \XLite::getInstance()->getOptions('database_details');
        $cmd = 'mysql -h' . $config['hostspec'];
        if ($config['port']) {
            $cmd .= ':' . $config['port'];
        }

        $cmd .= ' -u' . $config['username'] . ' -p' . $config['password'];
        if ($config['socket']) {
            $cmd .= ' -S' . $config['socket'];
        }

        exec($cmd . ' -e"drop database ' . $config['database'] . '"');
        exec($cmd . ' -e"create database ' . $config['database'] . '"');
        exec($cmd . ' ' . $config['database'] . ' < ' . $path);

        echo ('done' . PHP_EOL);
    }

}
