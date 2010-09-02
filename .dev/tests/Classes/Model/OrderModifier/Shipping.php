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
    protected $testOrder = array(
        'tracking'       => 'test t',
        'notes'          => 'Test note',
    );

    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();
    }

    public function testIsShippingAvailable()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isShippingAvailable(), 'check shipping avalable');

        $order->setShippingRate(null);
        $order->getItems()->get(0)->getObject()->setFreeShipping(true);

        $this->assertFalse($order->isShippingAvailable(), 'check shipping not avalable');
    }

    /*
    public function testGetShippingRates()
    {
        $order = $this->getTestOrder();

        $rates = $order->getShippingRates();

        $this->assertEquals(1, count($rates), 'check rates count');

        $rate = array_shift($rates);
        $this->assertTrue($rate instanceof \XLite\Model\ShippingRate, 'check class');

        // TODO - add some tests after shipping system rework
    }

    public function testGetShippedItems()
    {
        $order = $this->getTestOrder();

        $this->assertEquals(1, count($order->getShippedItems()), 'check list');

        $order->getItems()->get(0)->getObject()->setFreeShipping(true);

        $this->assertEquals(0, count($order->getShippedItems()), 'check empty list');
    }

    public function testCountShippedItems()
    {
        $order = $this->getTestOrder();

        $this->assertEquals(1, $order->countShippedItems(), 'check count');

        $order->getItems()->get(0)->setAmount(2);

        $this->assertEquals(2, $order->countShippedItems(), 'check count #2');

        $order->getItems()->get(0)->getObject()->setFreeShipping(true);

        $this->assertEquals(0, $order->countShippedItems(), 'check count #3');
    }

    public function testIsShippingDefined()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isShippingDefined(), 'check shipping defined');

        $order->setShippingMethod(null);

        $this->assertFalse($order->isShippingDefined(), 'check shipping not defined');
    }

    public function testGetShippingMethod()
    {
        $order = $this->getTestOrder();

        $m = $order->getShippingMethod();

        $this->assertTrue($m instanceof \XLite\Model\Shipping, 'check class');

        // TODO - rework test after shipping subsystem reworking

        $order->setShippingMethod(null);

        $this->assertFalse($order->getShippingMethod(), 'check empty method');
    }

    public function testSetShippingMethod()
    {
        $order = $this->getTestOrder();

        $m = $order->getShippingMethod();

        $this->assertTrue($m instanceof \XLite\Model\Shipping, 'check class');
        $this->assertEquals(2, $order->getShippingId(), 'check shipping id');

        // TODO - rework test after shipping subsystem reworking

        $order->setShippingMethod(null);

        $this->assertFalse($order->getShippingMethod(), 'check empty method');
        $this->assertEquals(0, $order->getShippingId(), 'check shipping id again');
    }

    public function testIsDeliveryAvailable()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isDeliveryAvailable(), 'check delivery avalability');

        // TODO - add some tests after shipping system rework
    }

    public function testAssignFirstShippingRate()
    {
        $order = $this->getTestOrder();

        $order->setShippingMethod(null);

        $this->assertFalse($order->getShippingMethod(), 'check empty shipping method');

        $order->assignFirstShippingRate();

        $this->assertEquals(2, $order->getShippingId(), 'check shipping id');
    }

    public function testIsShipped()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isShipped(), 'check shipped status');

        $order->getItems()->get(0)->getObject()->setFreeShipping(true);

        $this->assertFalse($order->isShipped(), 'check shipped status (fail)');
    }

    public function testGetShippedSubtotal()
    {
        $order = $this->getTestOrder();

        $this->assertEquals($order->getSubtotal(), $order->getShippedSubtotal(), 'check shipped subtotal');

        $order->getItems()->get(0)->getObject()->setFreeShipping(true);

        $this->assertEquals(0, $order->getShippedSubtotal(), 'check shipped subtotal (empty)');
    }
    */

    protected function getProduct()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByEnabled(true);
    }

    protected function getTestOrder()
    {
        $order = new \XLite\Model\Order();

        $profile = new \XLite\Model\Profile();
        $list = $profile->findAll();
        $profile = array_shift($list);
        unset($list);

        $order->map($this->testOrder);
        $order->setPaymentMethod(\XLite\Model\PaymentMethod::factory('PurchaseOrder'));
        $order->setProfileId(0);

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

        $order->addItem($item);

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setProfileCopy($profile);
        $order->calculate();

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        return $order;
    }
}
