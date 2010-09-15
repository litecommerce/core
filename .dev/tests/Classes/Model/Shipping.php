<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Shipping class tests
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

require_once PATH_TESTS . '/FakeClass/Model/Shipping.php';

class XLite_Tests_Model_Shipping extends XLite_Tests_TestCase
{
    /**
     * testRegisterProcessor 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testRegisterProcessor()
    {
        \XLite\Model\Shipping::getInstance()->registerProcessor('\XLite\Model\FakeShippingProcessor');

        $processors = \XLite\Model\Shipping::getInstance()->getProcessors();
        $this->assertTrue(is_array($processors), 'getProcessors() must return an array');

        // offline processor is always last
        $lastProcessor = array_pop($processors);
        $this->assertEquals('offline', $lastProcessor->getProcessorId());

        $found = false;
        foreach ($processors as $processor) {
            if ('test' == $processor->getProcessorId()) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, 'Test processor was not registered');

        if ($found) {

            \XLite\Model\Shipping::getInstance()->unregisterProcessor('\XLite\Model\FakeShippingProcessor');

            $processors = \XLite\Model\Shipping::getInstance()->getProcessors();

            $found = false;
            foreach ($processors as $processor) {
                if ('test' == $processor->getProcessorId()) {
                    $found = true;
                    break;
                }
            }

            $this->assertFalse($found, 'Test processor was not unregistered');
        }
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
        $methods = \XLite\Model\Shipping::getInstance()->getShippingMethods();

        $this->assertTrue(is_array($methods), 'getShippingMethods() must return an array');

        foreach ($methods as $method) {
            $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'getShippingMethods() must return an array of \XLite\Model\Shipping\Method instances');
        }

        $methods = \XLite\Model\Shipping::getInstance()->getShippingMethods('\XLite\Model\Shipping\Processor\Offline');

        $this->assertTrue(is_array($methods), 'getShippingMethods(offline) must return an array');

        foreach ($methods as $method) {
            $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'getShippingMethods(offline) must return an array of \XLite\Model\Shipping\Method instances');
            $this->assertEquals('offline', $method->getProcessor(), 'getShippingMethods(offline) returned methods of other processor');
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
        $rates = \XLite\Model\Shipping::getInstance()->getRates($this->getTestOrder());

        $this->assertTrue(is_array($rates), 'getRates() must return an array');

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getRates() must return an array of \XLite\Model\Shipping\Rate instances');
            $this->assertTrue(0 < is_numeric($rate->getTotalRate()), 'getRates() returned rate with zero total cost');
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
