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
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_Stats extends XLite_Web_Admin_AAdmin
{
    /**
     * Test order statistics
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testOrderStats()
    {
        $this->skipCoverage();

        $this->logIn();

        // Test statistics basing on demo dump
        $this->openAndWait('admin.php?target=orders_stats');

        $this->assertElementPresent(
            "//h1[@id='page-title' and text()='Statistics']",
            "Check header"
        );

        // Check tabs
        $this->assertElementPresent(
            "//div[@class='page-tabs']" .
            "/ul" .
            "/li[@class='tab-current']" .
            "/a[text()='Order statistics' and @href='admin.php?target=orders_stats']",
            "Check active tab"
        );

        $this->assertElementPresent(
            "//div[@class='page-tabs']" .
            "/ul" .
            "/li[@class='tab']" .
            "/a[text()='Top sellers' and @href='admin.php?target=top_sellers']",
            "Check inactive tab"
        );

        // Check column headers

        $headers = \XLite\Controller\Admin\OrdersStats::getInstance()->getColumnTitles();

        foreach ($headers as $k => $title) {

            $this->assertElementPresent(
                "//table[@class='data-table order-statistics']" .
                "/tbody" .
                "/tr[@class='TableHead']" .
                "/th[text()='" . $title . "']",
                "Check '" . $title . "' header"
            );

        }

        $rows = \XLite\Controller\Admin\OrdersStats::getInstance()->getRowTitles();

        // Check row titles headers
        foreach ($rows as $k => $title) {
            $totalsClass = \XLite\Controller\Admin\OrdersStats::getInstance()->isTotalsRow($k) ? ' totals' : '';
            $this->assertElementPresent(
                "//table[@class='data-table order-statistics']" .
                "/tbody" .
                "/tr[@class='dialog-box" . $totalsClass. "']" .
                "/td[text()='" . addslashes($title) . "']",
                "Check '" . $title . "' row"
            );
        }

        // Check key values of the demo data
        $this->assertEquals(
            "2",
            $this->getJSExpression('jQuery("table.data-table tr:eq(1) td:eq(5)").text().trim()'),
            "Check All time, processed/completed orders: 2 orders"
        );

        $this->assertEquals(
            '$ 141.87',
            $this->getJSExpression('jQuery("table.data-table tr:eq(5) td:eq(5)").text().trim()'),
            "Check All time, total"
        );

        $this->assertEquals(
            '$ 141.87',
            $this->getJSExpression('jQuery("table.data-table tr:eq(6) td:eq(5)").text().trim()'),
            "Check All time, paid"
        );

    }

    /**
     * Test top sellers
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testTopSellers()
    {
        $this->skipCoverage();

        $this->logIn();

        // Test statistics basing on demo dump
        $this->openAndWait('admin.php?target=top_sellers');

        $this->assertElementPresent(
            "//h1[@id='page-title' and text()='Statistics']",
            "Check header"
        );

        // Check tabs
        $this->assertElementPresent(
            "//div[@class='page-tabs']" .
            "/ul" .
            "/li[@class='tab']" .
            "/a[text()='Order statistics' and @href='admin.php?target=orders_stats']",
            "Check inactive tab"
        );

        $this->assertElementPresent(
            "//div[@class='page-tabs']" .
            "/ul" .
            "/li[@class='tab-current']" .
            "/a[text()='Top sellers' and @href='admin.php?target=top_sellers']",
            "Check active tab"
        );

        // Check column headers

        $headers = \XLite\Controller\Admin\TopSellers::getInstance()->getColumnTitles();

        foreach ($headers as $k => $title) {

            $this->assertElementPresent(
                "//table[@class='data-table top-sellers']" .
                "/tbody" .
                "/tr[@class='TableHead']" .
                "/th[text()='" . $title . "']",
                "Check '" . $title . "' header"
            );

        }

        $rows = \XLite\Controller\Admin\TopSellers::getInstance()->getStatsRows();

        // Check row titles headers
        foreach ($rows as $k => $title) {

            $this->assertElementPresent(
                "//table[@class='data-table top-sellers']" .
                "/tbody" .
                "/tr[@class='dialog-box']" .
                "/td[text()='" . addslashes($k+1 . '.') . "']",
                "Check '" . $k . "' row"
            );
        }

        // Check key values of the demo data
        $this->assertEquals(
            "Binary Mom",
            $this->getJSExpression('jQuery("table.data-table tr:eq(1) td:eq(5) a").text().trim()'),
            "Check top product"
        );

        // Check key values of the demo data
        $this->assertEquals(
            "Wi-Fi Detector Shirt",
            $this->getJSExpression('jQuery("table.data-table tr:eq(5) td:eq(5) a").text().trim()'),
            "Check 5th product"
        );
    }
}
