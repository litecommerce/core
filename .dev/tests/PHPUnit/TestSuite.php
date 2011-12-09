<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce test suite
 *
 * @category  LiteCommerce_Tests
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
        if (ROOT_TEST_SUITE_NAME !== $this->name) {
            if (strpos($this->name, "Web"))
                $this->restoreDBState();
            $this->cleanUpCache();
        }
        else{
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
        xlite_restore_sql_from_backup();

        sleep(1);
    }

    /**
     * Clear data cache
     *
     * @since  1.0.13
     */
    protected function cleanUpCache()
    {
        xlite_clean_up_cache();
    }
}
