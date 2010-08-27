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

class XLite_Tests_Model_Order extends XLite_Tests_TestCase
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

    public function testCreate()
    {
        $order = $this->getTestOrder();

        $this->assertTrue(0 < $order->getOrderId(), 'check order id');

        foreach ($this->testOrder as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $order->$m(), 'Check ' . $k);
        }

        $this->assertEquals(
            $this->getProduct()->getProductId(),
            $order->getItems()->get(0)->getObject()->getProductId(),
            'check product id'
        );

        $this->assertEquals(
            'Purchase Order',
            $order->getPaymentMethod()->get('name'),
            'check product id'
        );

        $this->assertEquals(
            $order->getProfileId(),
            $order->getProfile()->get('profile_id'),
            'check profile id'
        );
        $this->assertNotEquals(
            $order->getOrigProfileId(),
            $order->getProfile()->get('profile_id'),
            'check orig profile id'
        );
        $this->assertEquals(
            $order->getOrderId(),
            $order->getProfile()->get('order_id'),
            'check profile\'s order id'
        );

        $this->assertTrue(
            0 < $order->getDate(),
            'check date'
        );

        $shippingCost = $order->getTotalByModifier('shipping');

        $this->assertEquals(1, $order->getItems()->get(0)->getAmount(), 'check quantity');
        $this->assertEquals($this->getProduct()->getPrice(), $order->getItems()->get(0)->getPrice(), 'check price');
        $this->assertEquals($this->getProduct()->getPrice(), $order->getItems()->get(0)->getSubtotal(), 'check item subtotal');
        $this->assertEquals(
            $this->getProduct()->getPrice(),
            $order->getSubtotal(),
            'check order subtotal'
        );
        $this->assertEquals(
            $shippingCost + $this->getProduct()->getPrice(),
            $order->getTotal(),
            'check total'
        );
    }

    public function testUpdate()
    {
        $order = $this->getTestOrder();

        $t = time() + 100;

        $order->setStatus($order::STATUS_PROCESSED);
        $order->setDate($t);

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($order->getOrderId());

        $this->assertEquals($order::STATUS_PROCESSED, $order->getStatus(), 'check new status');
        $this->assertEquals(
            $t,
            $order->getDate(),
            'check date'
        );

        $order->getItems()->get(0)->setAmount(2);
        $order->calculate();
        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($order->getOrderId());

        $shippingCost = $order->getTotalByModifier('shipping');

        $this->assertEquals(2, $order->getItems()->get(0)->getAmount(), 'check quantity');
        $this->assertEquals($this->getProduct()->getPrice(), $order->getItems()->get(0)->getPrice(), 'check price');
        $this->assertEquals(2 * $this->getProduct()->getPrice(), $order->getItems()->get(0)->getSubtotal(), 'check item subtotal');
        $this->assertEquals(2 * $this->getProduct()->getPrice(), $order->getSubtotal(), 'check order subtotal');
        $this->assertEquals($shippingCost + 2 * $this->getProduct()->getPrice(), $order->getTotal(), 'check total');
    }

    public function testDelete()
    {
        $order = $this->getTestOrder();

        $id = $order->getOrderId();

        \XLite\Core\Database::getEM()->remove($order);
        \XLite\Core\Database::getEM()->flush();

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')
            ->find($id);

        $this->assertNull($order, 'check removed order');
    }

    public function testGetAllowedStatuses()
    {
        $order = new \XLite\Model\Order();

        $etalon = array(
            $order::STATUS_TEMPORARY  => 'Cart',
            $order::STATUS_INPROGRESS => 'Incompleted',
            $order::STATUS_QUEUED     => 'Queued',
            $order::STATUS_PROCESSED  => 'Processed',
            $order::STATUS_COMPLETED  => 'Completed',
            $order::STATUS_FAILED     => 'Failed',
            $order::STATUS_DECLINED   => 'Declined',
        );

        $this->assertEquals(
            $etalon,
            $order::getAllowedStatuses(),
            'check allowed statuses list'
        );

        $this->assertEquals(
            'Processed',
            $order::getAllowedStatuses($order::STATUS_PROCESSED),
            'check allowed status'
        );

    }

	public function testAddItem()
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

        $this->assertTrue($order->addItem($item), 'check add item');
		$this->assertEquals(1, $order->getItems()->count(), 'check items length');
        $this->assertEquals(1, $order->getItems()->get(0)->getAmount(), 'check item amount');

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

        $this->assertTrue($order->addItem($item), 'check add item #2');
        $this->assertEquals(1, $order->getItems()->count(), 'check items length #2');
        $this->assertEquals(2, $order->getItems()->get(0)->getAmount(), 'check item amount #2');

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();
	}

    public function testGetAddItemError()
    {
        $order = $this->getTestOrder();
        $this->assertNull($order->getAddItemError(), 'empty add item error');
    }

	public function testGetItemByItem()
	{
		$order = $this->getTestOrder();

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

		$this->assertEquals($item->getKey(), $order->getItemByItem($item)->getKey(), 'check equals items');

		$list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
		$p = $list[1];
		unset($list[1]);
		foreach ($list as $i) {
			\XLite\Core\Database::getEM()->detach($i);
		}
		unset($list);

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

        $this->assertNull($order->getItemByItem($item), 'check not equals items');

		$order->addItem($item);

        $this->assertEquals($item->getKey(), $order->getItemByItem($item)->getKey(), 'check equals items #2');
	}

	public function testGetItemByItemId()
	{
		$order = $this->getTestOrder();

		$list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
		$p = $list[1];
		unset($list[1]);
		foreach ($list as  $i) {
			\XLite\Core\Database::getEM()->detach($i);
		}
		unset($lisst);

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

		$order->addItem($item);

        $this->assertEquals(
			$item->getKey(),
			$order->getItemByItemId($item->getItemId())->getKey(),
			'check equals items'
		);

        $this->assertNull($order->getItemByItemId(0), 'check not exists item');

		$o2 = $this->gettestOrder();

		$id = $o2->getItems()->get(0)->getItemId();

		$this->assertNull($order->getItemByItemId($id), 'check foreign item');
	}

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
