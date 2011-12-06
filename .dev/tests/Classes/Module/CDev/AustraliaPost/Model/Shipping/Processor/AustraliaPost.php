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

class XLite_Tests_Module_CDev_AustraliaPost_Model_Shipping_Processor_AustraliaPost
extends XLite_Tests_Model_OrderAbstract
{
    protected $orderProducts = array('00000', '00002', '00003', '00004', '00005', '00006');

    /**
     * setUp
     *
     * @return void
     * @access protected
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
     * @access protected
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
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetProcessorName()
    {
        $processor = new XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor\AustraliaPost();

        $this->assertEquals('Australia Post', $processor->getProcessorName());
    }

    /**
     * testGetProcessorId
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetProcessorId()
    {
        $processor = new XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor\AustraliaPost();

        $this->assertEquals('aupost', $processor->getProcessorId());
    }

    /**
     * testGetApiUrl
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetApiUrl()
    {
        $processor = new XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor\AustraliaPost();

        $this->assertEquals('http://drc.edeliver.com.au/ratecalc.asp', $processor->getApiURL());
    }

    /**
     * testIsMethodNamesAdjustable
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsMethodNamesAdjustable()
    {
        $processor = new XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor\AustraliaPost();

        $this->assertFalse($processor->isMethodNamesAdjustable());
    }

    /**
     * testGetShippingMethods
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetShippingMethods()
    {
        $processor = new XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor\AustraliaPost();

        $methods = $processor->getShippingMethods();

        $this->assertTrue(is_array($methods), 'getShippingMethods() must return an array');

        foreach ($methods as $method) {
            $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'getShippingMethods() must return an array of \XLite\Model\Shipping\Method instances');
            $this->assertEquals('aupost', $method->getProcessor(), 'getShippingMethods() returned methods of other processor');
        }
    }

    /**
     * testGetRates
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetRates()
    {
        $processor = new XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor\AustraliaPost();

        // Test on anonymous order

        $tmpConfig = \XLite\Base::getInstance()->config->Company;

        \XLite\Base::getInstance()->config->Company->location_country = 'AU';
        \XLite\Base::getInstance()->config->Company->location_zipcode = '3146';

        $m = $this->getTestOrder(false)->getModifier('shipping', 'SHIPPING')->getModifier();
        $rates = $processor->getRates($m);
        $ratesCached = $processor->getRates($m);

        \XLite\Base::getInstance()->config->Company = $tmpConfig;

        $this->assertTrue(is_array($rates), 'getRates() must return an array (#1)');

        $this->assertEquals(5, count($rates), 'Count of rates is not match with an expected value (#1)');

        $this->assertEquals($rates, $ratesCached, 'Cached rates does not match an original rates (#1)');

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances (#1)');
            $this->assertTrue($rate->getMethod() instanceof \XLite\Model\Shipping\Method, 'Wrong method object returned (#1)');
            $this->assertEquals('aupost', $rate->getMethod()->getProcessor(), 'Wrong method returned (processor does not match) (#1)');

            $this->assertNotEquals(0, $rate->getBaseRate(), 'Base rate is zero (#1)');
            $this->assertEquals(0, $rate->getMarkupRate(), 'Markup rate is not zero (#1)');
        }

        // Test on an array with input data

        $data = array(
            'weight' => 250,
            'srcAddress' => array(
                'zipcode' => 3146,
            ),
            'dstAddress' => array(
                'zipcode' => '2154',
                'country' => 'AU'
            )
        );

        $rates = $processor->getRates($data, true);

        $this->assertTrue(is_array($rates), 'getRates() must return an array (#2)');

        $this->assertEquals(2, count($rates), 'Count of rates is not match with an expected value (#2)');

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances (#2)');
            $this->assertTrue($rate->getMethod() instanceof \XLite\Model\Shipping\Method, 'Wrong method object returned (#2)');
            $this->assertEquals('aupost', $rate->getMethod()->getProcessor(), 'Wrong method returned (processor does not match) (#2)');

            $this->assertNotEquals(0, $rate->getBaseRate(), 'Base rate is zero (#2)');
            $this->assertEquals(0, $rate->getMarkupRate(), 'Markup rate is not zero (#2)');
        }

        // Test on anonymous order if config.Company.location_country != 'AU'

        $tmpConfig = \XLite\Base::getInstance()->config->Company;

        \XLite\Base::getInstance()->config->Company->location_country = 'US';

        $m = $this->getTestOrder(false)->getModifier('shipping', 'SHIPPING')->getModifier();
        $rates = $processor->getRates($m);

        \XLite\Base::getInstance()->config->Company = $tmpConfig;

        $this->assertTrue(is_array($rates), 'getRates() must return an array (#3)');

        $this->assertEquals(0, count($rates), 'Count of rates is not match with an expected value (#3)');
    }

    /**
     * getTestOrder
     *
     * @return void
     * @access protected
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
