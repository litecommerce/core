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
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_OrderModifier_Shipping extends XLite_Tests_Model_OrderAbstract
{
    protected $orderProducts = array('00057', '00002', '00003', '00004', '00005', '00006');

    /**
     * testCalculate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCalculate()
    {
        $order = $this->getTestOrder();

        \XLite\Core\Config::getInstance()->Shipping->shipping_enabled = 'N';

        $order->calculate();

        $modifiers = $order->getModifiers();

        $found = false;

        foreach ($modifiers as $code => $modifier) {
            if (\XLite\Model\OrderModifier\Shipping::MODIFIER_SHIPPING == $code) {
                $found = true;
                break;
            }
        }

        $this->assertFalse($found, 'Order modifier "Shipping" must be unsetted in the modifiers list');

        \XLite\Core\Config::getInstance()->Shipping->shipping_enabled = 'Y';

        $m = null; 
        foreach ($order->getShippingRates() as $r) { 
            if ($r->getMethod()->getName() == 'Courier') { 
                $m = $r; 
                break; 
            } 
        } 
        $this->assertNotNull($m, 'check selected rate');

        $order->setSelectedRate($m);

        $order->calculate();

        $this->assertEquals(
            6.9458,
            $order->getTotalByModifier(\XLite\Model\OrderModifier\Shipping::MODIFIER_SHIPPING),
            'Wrong shipping cost calculated'
        );
    }

    /**
     * testGetShippingRates 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetShippingRates()
    {
        $order = $this->getTestOrder();

        $rates = $order->getShippingRates();

        $this->assertTrue(is_array($rates), 'getShippingRates() must return an array');

        foreach ($rates as $rate) {
            $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getShippingRates() must return an array of \XLite\Model\Shipping\Rate instances');
        }
    }

    /**
     * testGetSelectedRate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetSelectedRate()
    {
        $order = $this->getTestOrder();

        $m = $this->getMethodByName('Local shipping');
        $order->setShippingId($m->getMethodId());
        $rate = $order->getSelectedRate();

        $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getSelectedRate() must return an instance of \XLite\Model\Shipping\Rate');

        $order->setSelectedRate(null);
        $rate = $order->getSelectedRate();

        $this->assertFalse($rate instanceof \XLite\Model\Shipping\Rate, 'getSelectedRate() must NOT return an instance of \XLite\Model\Shipping\Rate');
    }

    /**
     * testSetSelectedRate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSetSelectedRate()
    {
        $order = $this->getTestOrder();

        // Test setSelectedRate($rate)
        $rate = $this->getTestRate();

        $order->setSelectedRate($rate);

        $rate = $order->getSelectedRate();

        $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getSelectedRate() must return an instance of \XLite\Model\Shipping\Rate');

        $this->assertEquals(300, $rate->getTotalRate(), 'getSelectedRate() returned wrong rate (total)');
        $this->assertEquals($order->getShippingId(), $rate->getMethodId(), 'getSelectedRate() set up wrong shipping_id');

        // Test setSelectedRate(null) without any rates available
        $order->setSelectedRate(null);

        $rate = $order->getSelectedRate();

        if (isset($rate)) {
            $this->assertNull($rate->getMethodId(), 'rate->getMethodId() must return null');
            $this->assertEquals(0, $order->getShippingId(), 'order->getShippingId() must return 0');

        } else {
            $this->assertEquals(0, $order->getShippingId(), 'getSelectedRate() set up wrong shipping_id (3)');
        }
    }

    /**
     * testIsShippingVisible 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsShippingVisible()
    {
        $order = $this->getTestOrder();

        $this->assertEquals($order->isShippingEnabled(), $order->isShippingVisible(), 'isShippingVisible() must return value equal to isShippingEnabled()');
    }

    /**
     * testIsShippingAvailable 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsShippingAvailable()
    {
        $order = $this->getTestOrder();

        $m = $this->getMethodByName('Local shipping');
        $order->setShippingId($m->getMethodId());
        $this->assertEquals($order->isShippingSelected(), $order->isShippingAvailable(), 'isShippingAvailable() must return value equal to isShippingSelected() if shipping method is selected');

        $order->setSelectedRate(null);
        $this->assertNotEquals($order->isShippingSelected(), $order->isShippingAvailable(), 'isShippingAvailable() must NOT return value equal to isShippingSelected() if shipping method is NOT selected');

    }

    /**
     * testIsShippingEnabled 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsShippingEnabled()
    {
        $order = $this->getTestOrder();

        // Test on existing profile

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'Y';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'Y';

        $this->assertTrue($order->isShippingEnabled(), 'isShippingEnabled() must return true (1)');

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'N';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'Y';

        $this->assertFalse($order->isShippingEnabled(), 'isShippingEnabled() must return false (2)');

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'Y';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'N';

        $this->assertTrue($order->isShippingEnabled(), 'isShippingEnabled() must return true (3)');

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'N';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'N';

        $this->assertFalse($order->isShippingEnabled(), 'isShippingEnabled() must return false (4)');

        // Test on non-existing profile

        $order->setProfile(null);

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'N';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'Y';

        $this->assertFalse($order->isShippingEnabled(), 'isShippingEnabled() must return false (5)');

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'N';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'N';

        $this->assertFalse($order->isShippingEnabled(), 'isShippingEnabled() must return false (6)');

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'Y';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'N';

        $this->assertTrue($order->isShippingEnabled(), 'isShippingEnabled() must return true (8)');

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'Y';
        \XLite\Base::getInstance()->config->Shipping->def_calc_shippings_taxes = 'Y';

        $this->assertTrue($order->isShippingEnabled(), 'isShippingEnabled() must return true (7)');
    }

    /**
     * testIsShippingSelected 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsShippingSelected()
    {
        $order = $this->getTestOrder();

        $this->assertFalse($order->isShippingSelected(), 'isShippingSelected() must return false if shipping method not selected');

        $m = $this->getMethodByName('Local shipping');
        $order->setShippingId($m->getMethodId());
        $this->assertTrue($order->isShippingSelected(), 'isShippingSelected() must return true if shipping method selected');
    }

    /**
     * testIsDeliveryAvailable 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsDeliveryAvailable()
    {
        $order = $this->getTestOrder();

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'N';

        $this->assertFalse($order->isDeliveryAvailable(), 'isDeliveryAvailable() must return false');

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'Y';

        $this->assertTrue($order->isDeliveryAvailable(), 'isDeliveryAvailable() must return true');
    }

    /**
     * testGetShippedItems 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetShippedItems()
    {
        $order = $this->getTestOrder();

        $items = $order->getShippedItems();

        $this->assertTrue(is_array($items), 'getShippedItems() must return an array');

        foreach ($items as $item) {
            $this->assertTrue(
                $item instanceof \XLite\Model\OrderItem,
                'getShippedItems() must return an array of \XLite\Model\OrderItem instances'
            );
        }

        $this->assertEquals(3, count($items), 'Order must contain 3 shipped items');
    }

    /**
     * testGetWeight 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetWeight()
    {
        $order = $this->getTestOrder();

        $this->assertEquals(2.56, $order->getWeight(), 'check weight of shipped items');
    }

    /**
     * testCountShippedItems 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCountShippedItems()
    {
        $order = $this->getTestOrder();

        $items = $order->getShippedItems();

        $this->assertEquals(count($items) * 4, $order->countShippedItems(), 'countShippedItems() returned wrong value');
    }

    /**
     * testGetShippingMethod 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetShippingMethod()
    {
        $order = $this->getTestOrder();

        $rate = $this->getTestRate();

        $order->setSelectedRate($rate);

        $method = $order->getShippingMethod();

        $this->assertTrue($method instanceof \XLite\Model\Shipping\Method, 'getShippingMethod() returned not an object');
    }

    /**
     * testIsShipped 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsShipped()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isShipped(), 'isShipped() must return true');
    }

    /**
     * testGetShippedSubtotal 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetShippedSubtotal()
    {
        $order = $this->getTestOrder();

        $value = $order->getShippedSubtotal();

        $this->assertTrue(is_numeric($value), 'getShippedSubtotal() must return a numeric value');
        $this->assertEquals(299.72, round($value, 2), 'getShippedSubtotal() returned wrong value');
    }

    /**
     * getTestOrder 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTestOrder()
    {
        $order = parent::getTestOrder();

        $i = 0;
        foreach ($order->getItems() as $item) {

            $item->getProduct()->setFreeShipping(0 != $i % 2);

            $item->setAmount(4);
            $i++;
        }

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }

    /**
     * getTestRate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTestRate()
    {
        // Prepare data for rate
        $method = $this->getMethodByName('Local shipping');
        $methodName = $method->getName();

        $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')->findAll();
        $markup = array_shift($markups);
        $markupId = $markup->getMarkupId();

        $extraData = new \XLite\Core\CommonCell();
        $extraData->testparam1 = 'test value 1';
        $extraData->testparam2 = 'test value 2';

        // Create rate
        $newRate = new \XLite\Model\Shipping\Rate();

        $newRate->setMethod($method);
        $newRate->setBaseRate(100);
        $newRate->setMarkup($markup);
        $newRate->setMarkupRate(200);
        $newRate->setExtraData($extraData);

        return $newRate;
    }

    protected function getMethodByName($name)
    {
        $method = null;

        foreach (\XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findAll() as $m) {
            if ($m->getName() == $name) {
                $method = $m;
                break;
            }
        }

        return $method;
    }
}
