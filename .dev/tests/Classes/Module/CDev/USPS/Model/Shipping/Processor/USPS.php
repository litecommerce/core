<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * \XLite\Model\Shipping\Processor\Offline class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Module_CDev_USPS_Model_Shipping_Processor_USPS
extends XLite_Tests_Model_OrderAbstract
{
    protected $orderProducts = array('00000', '00002', '00003', '00004', '00005', '00006');

    /**
     * setUp
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        if ($this->checkTestName()) {
            $this->doRestoreDb(__DIR__ . '/sql/shipping/setup.sql', false);
        }
    }

    /**
     * tearDown
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function tearDown()
    {
        parent::tearDown();

        if ($this->checkTestName()) {
            $this->doRestoreDb();
        }
    }


    /**
     * testGetProcessorName
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetProcessorName()
    {
        $processor = new XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

        $this->assertEquals('U.S.P.S.', $processor->getProcessorName());
    }

    /**
     * testGetProcessorId
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetProcessorId()
    {
        $processor = new XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

        $this->assertEquals('usps', $processor->getProcessorId());
    }

    /**
     * testGetApiUrl
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetApiUrl()
    {
        $processor = new XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

        $defaultHost = 'testing.shippingapis.com';

        $tmpServerName = \XLite\Core\Config::getInstance()->CDev->USPS->server_name;
        $tmpServerPath = \XLite\Core\Config::getInstance()->CDev->USPS->server_path;

        \XLite\Core\Config::getInstance()->CDev->USPS->server_path = 'path';

        \XLite\Core\Config::getInstance()->CDev->USPS->server_name = 'host';
        $this->assertEquals('http://host/path', $processor->getApiURL());

        \XLite\Core\Config::getInstance()->CDev->USPS->server_name = 'http://host';
        $this->assertEquals('http://' . $defaultHost . '/path', $processor->getApiURL());

        \XLite\Core\Config::getInstance()->CDev->USPS->server_name = $tmpServerName;
        \XLite\Core\Config::getInstance()->CDev->USPS->server_path = $tmpServerPath;
    }

    /**
     * testIsMethodNamesAdjustable
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsMethodNamesAdjustable()
    {
        $processor = new XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

        $this->assertFalse($processor->isMethodNamesAdjustable());
    }

    public function testGetPoundsOunces()
    {
        $processor = new XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

        \XLite\Core\Config::getInstance()->General->weight_unit = 'lbs';
        $this->assertEquals(array(10, 0), $processor->getPoundsOunces(10), 'Checking weight conversion failed (#1)');

        \XLite\Core\Config::getInstance()->General->weight_unit = 'lbs';
        $this->assertEquals(array(1, 4.6), $processor->getPoundsOunces(1.285), 'Checking weight conversion failed (#2)');

        \XLite\Core\Config::getInstance()->General->weight_unit = 'oz';
        $this->assertEquals(array(0, 1000), $processor->getPoundsOunces(1000), 'Checking weight conversion failed (#3)');

        \XLite\Core\Config::getInstance()->General->weight_unit = 'oz';
        $this->assertEquals(array(0, 1000.3), $processor->getPoundsOunces(1000.254), 'Checking weight conversion failed (#4)');

        \XLite\Core\Config::getInstance()->General->weight_unit = 'kg';
        $this->assertEquals(array(0, 352.7), $processor->getPoundsOunces(10), 'Checking weight conversion failed (#5)');

        \XLite\Core\Config::getInstance()->General->weight_unit = 'kg';
        $this->assertEquals(array(0, 361), $processor->getPoundsOunces(10.234), 'Checking weight conversion failed (#5)');

        \XLite\Core\Config::getInstance()->General->weight_unit = 'g';
        $this->assertEquals(array(0, 35.3), $processor->getPoundsOunces(1000), 'Checking weight conversion failed (#6)');

        \XLite\Core\Config::getInstance()->General->weight_unit = 'g';
        $this->assertEquals(array(0, 35.3), $processor->getPoundsOunces(1000.234), 'Checking weight conversion failed (#6)');
    }

    /**
     * testGetShippingMethods
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetShippingMethods()
    {
        $processor = new XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

        $methods = $processor->getShippingMethods();

        $this->assertTrue(is_array($methods), 'getShippingMethods() must return an array');

        foreach ($methods as $method) {
            $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'getShippingMethods() must return an array of \XLite\Model\Shipping\Method instances');
            $this->assertEquals('usps', $method->getProcessor(), 'getShippingMethods() returned methods of other processor');
        }
    }

    /**
     * testGetRates
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetRates()
    {
        $processor = new XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

        \XLite\Base::getInstance()->config->CDev->USPS->userid = $this->testConfig['usps']['userid'];

        // Test on anonymous order

        $tmpConfig = \XLite\Base::getInstance()->config->Company;

        \XLite\Base::getInstance()->config->Company->location_country = 'US';
        \XLite\Base::getInstance()->config->Company->location_zipcode = '10001';

        $m = $this->getTestOrder(false)->getModifier('shipping', 'SHIPPING')->getModifier();
        $rates = $processor->getRates($m, true);
        $ratesCached = $processor->getRates($m);

        \XLite\Base::getInstance()->config->Company = $tmpConfig;

        $this->assertTrue(is_array($rates), 'getRates() must return an array (#1)');

        // Actually USPS returns 22 methods in the response. We check just >15 to avoid failure if USPS will return other number of methods
        $this->assertGreaterThan(15, count($rates), 'Count of rates checking failed (#1) - ' . $processor->getErrorMsg());

        $this->assertEquals($rates, $ratesCached, 'Cached rates does not match an original rates (#1)');

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances (#1)');
            $this->assertTrue($rate->getMethod() instanceof \XLite\Model\Shipping\Method, 'Wrong method object returned (#1)');
            $this->assertEquals('usps', $rate->getMethod()->getProcessor(), 'Wrong method returned (processor does not match) (#1)');

            $this->assertNotEquals(0, $rate->getBaseRate(), 'Base rate is zero (#1)');
            $this->assertEquals(0, $rate->getMarkupRate(), 'Markup rate is not zero (#1)');
        }

        // Test on an array with input data

        $data = array(
            'srcAddress' => array(
                'zipcode' => 10001,
            ),
            'dstAddress' => array(
                'zipcode' => '22044',
                'country' => 'US'
            ),
            'packages' => array(
                0 => array(
                    'weight' => 10,
                    'subtotal' => 200,
                ),
            ),
        );

        $rates = $processor->getRates($data, true);

        $this->assertTrue(is_array($rates), 'getRates() must return an array (#2)');

        $this->assertGreaterThan(15, count($rates), 'Count of rates checking failed (#2) - ' . $processor->getErrorMsg());

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances (#2)');
            $this->assertTrue($rate->getMethod() instanceof \XLite\Model\Shipping\Method, 'Wrong method object returned (#2)');
            $this->assertEquals('usps', $rate->getMethod()->getProcessor(), 'Wrong method returned (processor does not match) (#2)');

            $this->assertNotEquals(0, $rate->getBaseRate(), 'Base rate is zero (#2)');
            $this->assertEquals(0, $rate->getMarkupRate(), 'Markup rate is not zero (#2)');
        }

        // Test error of Domestic API

        $tmpCont = \XLite\Base::getInstance()->config->CDev->USPS->container;
        \XLite\Base::getInstance()->config->CDev->USPS->container = 'WRONG';

        $rates = $processor->getRates($data, true);

        $this->assertTrue(is_array($rates), 'getRates() must return an array (111)');
        $this->assertEquals(empty($rates), 'getRates() must return empty array (111)');

        \XLite\Base::getInstance()->config->CDev->USPS->container = $tmpCont;


        // Test on anonymous order if config.Company.location_country != 'US'

        $tmpConfig = \XLite\Base::getInstance()->config->Company;

        \XLite\Base::getInstance()->config->Company->location_country = 'AU';

        $m = $this->getTestOrder(false)->getModifier('shipping', 'SHIPPING')->getModifier();
        $rates = $processor->getRates($m, true);

        \XLite\Base::getInstance()->config->Company = $tmpConfig;

        $this->assertTrue(is_array($rates), 'getRates() must return an array (#3)');

        $this->assertEquals(0, count($rates), 'Count of rates is not match with an expected value (#3) - ' . $processor->getErrorMsg());

        unset($rates);

        // Test International API

        \XLite\Base::getInstance()->config->Company->location_country = 'US';
        \XLite\Base::getInstance()->config->CDev->USPS->gxg = true;
        
        $methodToDelete = 'I-1-735bf98ee9fbdf9dbb374920def99049';

        $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findOneByCode($methodToDelete);

        $this->assertNotNull($method, 'Method with code ' . $methodToDelete . ' not found in the database');

        \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->delete($method);
        $method->detach();

        $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findOneByCode($methodToDelete);

        $this->assertNull($method, 'Method with code ' . $methodToDelete . ' found in the database');

        $data = array(
            'srcAddress' => array(
                'zipcode' => 50001,
            ),
            'dstAddress' => array(
                'zipcode' => '3456',
                'country' => 'GB'
            ),
            'packages' => array(
                0 => array(
                    'weight' => 3,
                    'subtotal' => 200,
                ),
            ),
        );

        $rates = $processor->getRates($data, true);

        $this->assertTrue(is_array($rates), 'getRates() must return an array (#4)');

        $this->assertGreaterThan(1, count($rates), 'Count of rates checking failed (#4) - ' . $processor->getErrorMsg());

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances (#4)');
            $this->assertTrue($rate->getMethod() instanceof \XLite\Model\Shipping\Method, 'Wrong method object returned (#4)');
            $this->assertEquals('usps', $rate->getMethod()->getProcessor(), 'Wrong method returned (processor does not match) (#4)');

            $this->assertNotEquals(0, $rate->getBaseRate(), 'Base rate is zero (#4)');
            $this->assertEquals(0, $rate->getMarkupRate(), 'Markup rate is not zero (#4)');
        }

        // Check that method I-1 returned to the database 
        $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findOneByCode($methodToDelete);

        $this->assertNotNull($method, 'Method with code ' . $methodToDelete . ' not restored in the database after request to USPS');
        $this->assertFalse($method->getEnabled(), 'Method with code ' . $methodToDelete . ' restored with enabled status');

        // Test International API error

        \XLite\Base::getInstance()->config->CDev->USPS->mail_type = 'WRONG';
        \XLite\Base::getInstance()->config->CDev->USPS->gxg = false;

        $rates = $processor->getRates($data, true);

        $this->assertTrue(is_array($rates), 'getRates() must return an array (222)');
        $this->assertEquals(empty($rates), 'getRates() must return empty array (222)');


        // Test for wrong input data
        $data = array(
            'something' => 'wrong',
        );

        $rates = $processor->getRates($data, true);

        $this->assertTrue(is_array($rates), 'getRates() must return an array (333)');
        $this->assertEquals(empty($rates), 'getRates() must return empty array (333)');
    }

    /**
     * getTestOrder
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTestOrder($new_order = false)
    {
        $order = parent::getTestOrder($new_order);

        $args = func_get_args();

        $profile = null;
        if (isset($args[0]) && !$args[0]) {
            $order->setProfile(null);
            $order->setOrigProfile(null);
        }

        foreach ($order->getItems() as $index => $item) {

            if ($index % 2) {
                $item->getProduct()->setFreeShipping(true);
            }

            $item->setAmount(4);
        }

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }

    /**
     * Return true for specific methods (methods which require database adjustments) 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkTestName()
    {
        return in_array(
            $this->getName(),
            array(
                'testGetRates',
            )
        );
    }
}
