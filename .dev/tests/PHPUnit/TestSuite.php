<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce test suite
 *
 * @category  LiteCommerce_Tests
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

final class XLite_Tests_TestSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * Template Method that is called after the tests
     * of this test suite have finished running.
     *
     * @since 1.0.0
     */
    protected function tearDown()
    {
        if (ROOT_TEST_SUITE_NAME != $this->name) {
            $this->restoreDBState();
        }
    }

    /**
     * Restore database from the backup
     *
     * @since 1.0.0
     */
    protected function restoreDBState()
    {
        $path = realpath(dirname(__FILE__) . '/../dump.sql');
        if (!file_exists($path)) {
            return false;
        }

        echo (PHP_EOL . 'DB restore ... ');

        $config = \XLite::getInstance()->getOptions('database_details');
        
        $cmd = defined('TEST_MYSQL_BIN') ? TEST_MYSQL_BIN : 'mysql';
        $cmd .= ' -h' . $config['hostspec'];
        
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

        sleep(1);
    }

}
