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

class XLite_Tests_Model_OrderModifier_Shipping extends XLite_Tests_TestCase
{
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
        $this->markTestSkipped('temporary skipped');

        $order = $this->getTestOrder();

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'N';

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

        \XLite\Base::getInstance()->config->Shipping->shipping_enabled = 'Y';

        $order->calculate();

        $this->assertEquals(100, $order->getShippingId(), 'Wrong shipping_id');
        $this->assertEquals(5.6782, $order->getTotalByModifier(\XLite\Model\OrderModifier\Shipping::MODIFIER_SHIPPING), 'Wrong shipping cost calculated');
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
        $this->markTestSkipped('temporary skipped');

        $order = $this->getTestOrder();

        $order->setShippingId(101);
        $rate = $order->getSelectedRate();

        $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getSelectedRate() must return an instance of \XLite\Model\Shipping\Rate');
        $this->assertEquals(101, $rate->getMethodId(), 'getSelectedRate() returned wrong rate');

        $order->setShippingId(0);
        $rate = $order->getSelectedRate();

        $this->assertTrue($rate instanceof \XLite\Model\Shipping\Rate, 'getSelectedRate() must return an instance of \XLite\Model\Shipping\Rate');
        $this->assertEquals(100, $rate->getMethodId(), 'getSelectedRate() returned wrong rate');
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

        $order->setSelectedRate('ascd');

        $order->setSelectedRate(123456);

        $rate = $order->getSelectedRate();

        if (isset($rate)) {
            $this->assertEquals($rate->getMethodId(), $order->getShippingId(), 'getSelectedRate() set up wrong shipping_id (2)');

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

        $this->assertEquals($order->isShippingSelected(), $order->isShippingAvailable(), 'isShippingAvailable() must return value equal to isShippingSelected()');
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
        $this->markTestSkipped('temporary skipped');

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

        $this->assertTrue($order->isShippingSelected(), 'isShippingSelected() must return true');
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
            $this->assertTrue($item instanceof \XLite\Model\OrderItem, 'getShippedItems() must return an array of \XLite\Model\OrderItem instances');
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
        $this->assertEquals(101, $method->getMethodId(), 'getShippingMethod() returned wrong method');
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
     * getProducts 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProduct($productId)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->find($productId);
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
        $order = new \XLite\Model\Order();

        $profile = new \XLite\Model\Profile();
        $list = $profile->findAll();
        $profile = array_shift($list);
        unset($list);

        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));
        $order->setProfileId($profile->get('profile_id'));

        $productIds = array(4059, 4004, 4005, 4006, 4007, 4008);

        foreach ($productIds as $index => $productId) {

            $product = $this->getProduct($productId);

            if ($index % 2) {
                $product->setFreeShipping(true);
            }

            $item = new \XLite\Model\OrderItem();

            $item->setProduct($product);
            $item->setAmount(4);
            $item->setPrice($product->getPrice());

            $order->addItem($item);
        }

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setProfileCopy($profile);

        \XLite\Core\Database::getEM()->persist($order);
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
        $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->find(101);
        $methodName = $method->getName();

        $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')->findAll();
        $markup = array_shift($markups);
        $markupId = $markup->getMarkupId();
        unset($markups);

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

}
