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

class XLite_Tests_Model_Shipping_Processor_Offline extends XLite_Tests_Model_OrderAbstract
{
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
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $this->assertEquals('Manually defined shipping methods', $processor->getProcessorName());
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
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $this->assertEquals('offline', $processor->getProcessorId());
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
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $this->assertTrue($processor->isMethodNamesAdjustable());
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
     * @since  1.0.0
     */
    public function testGetRates()
    {
        $processor = new \XLite\Model\Shipping\Processor\Offline();

        $m = $this->getTestOrder(false)->getModifier('shipping', 'SHIPPING')->getModifier();
        $rates = $processor->getRates($m);

        $this->assertTrue(is_array($rates), 'getRates() must return an array');

        foreach ($rates as $i => $rate) {
            $this->assertInstanceOf('XLite\Model\Shipping\Rate', $rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances (#' . $i . ')');
            $this->assertEquals(0, $rate->getBaseRate(), 'Base rate is not zero (#' . $i . ')');
            if ($rate->getMarkup()->getMarkupId() == 1) {
                $this->assertEquals(0, $rate->getMarkupRate(), 'Markup rate is not zero (#' . $i . ')');

            } else {
                $this->assertNotEquals(0, $rate->getMarkupRate(), 'Markup rate is zero (#' . $i . ')');
            }
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
     * @since  1.0.0
     */
    protected function getTestOrder($new_order = false)
    {
        $order = parent::getTestOrder($new_order);

        $args = func_get_args();

        if (isset($args[0]) && !$args[0]) {
            $order->setProfile(null);
            $order->setOrigProfile(null);
        }

        $order->setSubTotal(17.99);

        return $order;
    }

}
