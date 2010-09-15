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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Shipping_Processor_Offline extends XLite_Tests_TestCase
{
    /**
     * testGetProcessorName 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetProcessorName()
    {
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $this->assertEquals('Manually defined shipping methods', $processor->getProcessorName());
    }

    /**
     * testGetProcessorId 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetProcessorId()
    {
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $this->assertEquals('offline', $processor->getProcessorId());
    }

    /**
     * testIsMethodNamesAdjustable 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsMethodNamesAdjustable()
    {
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $this->assertTrue($processor->isMethodNamesAdjustable());
    }

    /**
     * testGetShippingMethods 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetShippingMethods()
    {
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $methods = $processor->getShippingMethods();

        $this->assertTrue(is_array($methods), 'getShippingMethods() must return an array');

        foreach ($methods as $method) {
            $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'getShippingMethods() must return an array of \XLite\Model\Shipping\Method instances');
            $this->assertEquals('offline', $method->getProcessor(), 'getShippingMethods() returned methods of other processor');
        }
    }

    /**
     * testGetRates 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetRates()
    {
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $rates = $processor->getRates($this->getTestOrder(false));

        $this->assertTrue(is_array($rates), 'getRates() must return an array');

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances');
            $this->assertEquals(0, $rate->getBaseRate(), 'Base rate is not zero');
            $this->assertNotEquals(0, $rate->getMarkupRate(), 'Markup rate is zero');
        }
    }

    /**
     * getTestOrder 
     * 
     * @param bool $profile Flag: if true, then get real profile from database
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTestOrder($profile = true)
    {
        $order = new \XLite\Model\Order();

        if ($profile) {
            $profile = new \XLite\Model\Profile();
            $list = $profile->findAll();
            $profile = array_shift($list);
            $profileId = $profile->get('profile_id');
            unset($list);

        } else {
            $profileId = 0;
        }

        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));
        $order->setProfileId($profileId);
        $order->setSubTotal(17.99);

        return $order;
    }

}
