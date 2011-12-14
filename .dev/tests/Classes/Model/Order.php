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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

/**
 * XLite_Tests_Model_Order
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class XLite_Tests_Model_Order extends XLite_Tests_Model_OrderAbstract
{
    /**
     * testCreate
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCreate()
    {
        $order = $this->getTestOrder();

        $this->assertTrue(0 < $order->getOrderId(), 'check order id');

        foreach ($this->testOrder as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $order->$m(), 'Check test order: ' . $k);
        }

        $this->assertEquals(
            $this->getProduct()->getProductId(),
            $order->getItems()->get(0)->getObject()->getProductId(),
            'check product id'
        );

        $this->assertEquals(
            'Purchase Order',
            $order->getPaymentMethod()->getName(),
            'check payment method'
        );

        $this->assertFalse(is_null($order->getProfile()), 'check profile');

        $this->assertFalse(is_null($order->getProfile()->getOrder()), 'check profile\'s order');

        $this->assertEquals(
            $order->getOrderId(),
            $order->getProfile()->getOrder()->getOrderId(),
            'check profile\'s order id'
        );

        $this->assertTrue(
            0 < $order->getDate(),
            'check date'
        );

        $shippingCost = $order->getSurchargeSumByType('shipping');

        $this->assertEquals(1, $order->getItems()->get(0)->getAmount(), 'check quantity');
        $this->assertEquals($this->getProduct()->getPrice(), $order->getItems()->get(0)->getPrice(), 'check price');
        $this->assertEquals($this->getProduct()->getPrice(), $order->getItems()->get(0)->getSubtotal(), 'check item subtotal');
        $this->assertEquals(
            $this->getProduct()->getPrice(),
            $order->getSubtotal(),
            'check order subtotal'
        );
        $this->assertEquals(
            round($shippingCost + $this->getProduct()->getPrice(), 2),
            $order->getTotal(),
            'check total'
        );

        // Payment method
        $pm = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(array('service_name' => 'PurchaseOrder'));
        $order->setPaymentMethod($pm);
        $this->assertEquals($pm, $order->getPaymentMethod(), 'check payment method');

        // Saved modifiers
        $surcharge = new \XLite\Model\Order\Surcharge;
        $surcharge->setType('shipping');
        $surcharge->setCode('ttt');
        $surcharge->setValue(10.00);
        $surcharge->setInclude(false);
        $surcharge->setAvailable(true);
        $surcharge->setClass(get_called_class());
        $surcharge->setName('test');

        $order->getSurcharges()->add($surcharge);
        $surcharge->setOwner($order);

        $count = count($order->getSurcharges());
        $this->assertEquals($surcharge, $order->getSurcharges()->get($count - 1), 'check surcharge');
    }

    public function testUpdate()
    {
        $order = $this->getTestOrder();

        $t = time() + 100;

        $order->setStatus($order::STATUS_PROCESSED);
        $order->setDate($t);

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

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

        \XLite\Core\Database::getEM()->clear();

        $this->order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($order->getOrderId());

        $order = $this->order;
        $shippingCost = $order->getSurchargeSumByType('shipping');

        $this->assertEquals(2, $order->getItems()->get(0)->getAmount(), 'check quantity');
        $this->assertEquals($this->getProduct()->getPrice(), $order->getItems()->get(0)->getPrice(), 'check price');
        $this->assertEquals(2 * $this->getProduct()->getPrice(), $order->getItems()->get(0)->getSubtotal(), 'check item subtotal');
        $this->assertEquals(2 * $this->getProduct()->getPrice(), $order->getSubtotal(), 'check order subtotal');
        $this->assertEquals(
            round($shippingCost + 2 * $this->getProduct()->getPrice(), 2),
            $order->getTotal(),
            'check total'
        );

        $order->setStatus($order::STATUS_INPROGRESS);
        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setStatus($order::STATUS_QUEUED);
        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setStatus($order::STATUS_PROCESSED);
        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setStatus($order::STATUS_DECLINED);
        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();
    }

    public function testDelete()
    {
        $order = $this->getTestOrder();

        $id = $order->getOrderId();

        \XLite\Core\Database::getEM()->remove($order);
        \XLite\Core\Database::getEM()->flush();
        $this->order = null;

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')
            ->find($id);

        $this->assertTrue(is_null($order), 'check removed order');
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

        $profiles = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findAll();
        $profile = array_shift($profiles);
        unset($profiles);

        $order->map($this->testOrder);
        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));
        $order->setDate(time());

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

        $item = new \XLite\Model\FakeOrderItem();

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

        $item->setInvalidFake();

        $this->assertFalse($order->addItem($item), 'check add item #3');
        $this->assertEquals($order::NOT_VALID_ERROR, $order->getAddItemError(), 'check error text');
        \XLite\Core\Database::getEM()->remove($order);
        \XLite\Core\Database::getEM()->flush();
	}

    public function testGetAddItemError()
    {
        $order = $this->getTestOrder();
        $this->assertTrue(is_null($order->getAddItemError()), 'empty add item error');
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

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

        $this->assertTrue(is_null($order->getItemByItem($item)), 'check not equals items');

		$order->addItem($item);

        $this->assertEquals($item->getKey(), $order->getItemByItem($item)->getKey(), 'check equals items #2');
	}

	public function testGetItemByItemId()
	{
		$order = $this->getTestOrder();

		$list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
		$p = $list[1];
        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

		$order->addItem($item);

       // \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(
			$item->getKey(),
			$order->getItemByItemId($item->getItemId())->getKey(),
			'check equals items'
		);

        $this->assertTrue(is_null($order->getItemByItemId(-1)), 'check not exists item');

		$o2 = $this->getTestOrder(true);

		$id = $o2->getItems()->get(0)->getItemId();

		$this->assertTrue(is_null($order->getItemByItemId($id)), 'check foreign item');
	}

    public function testNormalizeItems()
    {
        $order = $this->getTestOrder();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $p = $list[1];

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

        $order->addItem($item);

        $this->assertEquals(2, $order->getItems()->count(), 'check order items count');

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

        $this->assertEquals(2, $order->getItems()->count(), 'check order items count #2');

        $order->normalizeItems();

        $this->assertEquals(1, $order->getItems()->count(), 'check order items count #3');

        //\XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $p = $list[1];

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

        $order->addItem($item);

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

        $this->assertEquals(2, $order->getItems()->count(), 'check order items count #4');

        //\XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->normalizeItems();

        $this->assertEquals(1, $order->getItems()->count(), 'check order items count #5');

        $item = new \XLite\Model\FakeOrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

        $order->addItem($item);

        $order->normalizeItems();

        $this->assertEquals(2, $order->getItems()->count(), 'check order items count #6');

        $item->setInvalidFake();

        $order->normalizeItems();

        $this->assertEquals(1, $order->getItems()->count(), 'check order items count #7');
    }

    public function testCountItems()
    {
        $order = $this->getTestOrder();
        $this->assertEquals(1, $order->countItems(), 'check order items count');

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $p = $list[1];

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

        $order->addItem($item);

        $this->assertEquals(2, $order->countItems(), 'check order items count #2');

        $this->assertEquals($order->getItems()->count(), $order->countItems(), 'check order items count #3');
    }

    public function testCountQuantity()
    {
        $order = $this->getTestOrder();
        $this->assertEquals(1, $order->countQuantity(), 'check order quantity');

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $p = $list[1];

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($p);
        $item->setAmount(1);
        $item->setPrice($p->getPrice());

        $order->addItem($item);

        $this->assertEquals(2, $order->countQuantity(), 'check order quantity #2');

        $item->setAmount(2);

        $this->assertEquals(3, $order->countQuantity(), 'check order quantity #3');
    }

    public function testIsEmpty()
    {
        $order = $this->getTestOrder();

        $this->assertFalse($order->isEmpty(), 'check order empty');

        $order->getItems()->clear();

        $this->assertTrue($order->isEmpty(), 'check order empty #2');

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

        $order->addItem($item);

        $this->assertFalse($order->isEmpty(), 'check order empty #3');

        $item->setPrice(0);
        $this->assertFalse($order->isEmpty(), 'check order empty #4');
    }

    public function testIsMinOrderAmountError()
    {
        $order = $this->getTestOrder();

        $price = \XLite\Core\Config::getInstance()->General->minimal_order_amount - 1;
        $order->getItems()->get(0)->setPrice($price);
        $order->calculate();
        $this->assertTrue($order->isMinOrderAmountError(), 'is error');

        $price = \XLite\Core\Config::getInstance()->General->minimal_order_amount + 1;
        $order->getItems()->get(0)->setPrice($price);
        $order->calculate();
        $this->assertFalse($order->isMinOrderAmountError(), 'is not error');
    }

    public function testIsMaxOrderAmountError()
    {
        $order = $this->getTestOrder();

        $price = \XLite\Core\Config::getInstance()->General->maximal_order_amount + 1;
        $order->getItems()->get(0)->setPrice($price);
        $order->calculate();
        $this->assertTrue($order->isMaxOrderAmountError(), 'is error');

        $price = \XLite\Core\Config::getInstance()->General->maximal_order_amount - 1;
        $order->getItems()->get(0)->setPrice($price);
        $order->calculate();
        $this->assertFalse($order->isMaxOrderAmountError(), 'is not error');
    }

    public function testIsProcessed()
    {
        $order = $this->getTestOrder();

        $etalon = array(
            $order::STATUS_TEMPORARY  => false,
            $order::STATUS_INPROGRESS => false,
            $order::STATUS_QUEUED     => false,
            $order::STATUS_PROCESSED  => true,
            $order::STATUS_COMPLETED  => true,
            $order::STATUS_FAILED     => false,
            $order::STATUS_DECLINED   => false,
        );

        foreach ($etalon as $status => $res) {
            $order->setStatus($status);
            $this->assertEquals($res, $order->isProcessed(), 'check ' . $status . ' status');
        }
    }

    public function testIsQueued()
    {
        $order = $this->getTestOrder();

        $etalon = array(
            $order::STATUS_TEMPORARY  => false,
            $order::STATUS_INPROGRESS => false,
            $order::STATUS_QUEUED     => true,
            $order::STATUS_PROCESSED  => false,
            $order::STATUS_COMPLETED  => false,
            $order::STATUS_FAILED     => false,
            $order::STATUS_DECLINED   => false,
        );

        foreach ($etalon as $status => $res) {
            $order->setStatus($status);
            $this->assertEquals($res, $order->isQueued(), 'check ' . $status . ' status');
        }
    }

    public function testCalculate()
    {
        $order = $this->getTestOrder();

        $order->calculate();

        $shippingCost = $order->getSurchargeSumByType('shipping');

        $this->assertEquals(
            round($shippingCost + $this->getProduct()->getPrice(), 2),
            $order->getTotal(),
            'check total'
        );

        $order->getItems()->get(0)->setAmount(2);
        $order->calculate();

        $shippingCost = $order->getSurchargeSumByType('shipping');

        $this->assertEquals(
            round($shippingCost + 2 * $this->getProduct()->getPrice(), 2),
            $order->getTotal(),
            'check total #2'
        );

        $order->getItems()->clear();
        $order->calculate();

        $this->assertEquals(
            0,
            $order->getTotal(),
            'check total (empty)'
        );
    }

    public function testSetStatus()
    {
        $order = $this->getTestOrder();

        $order->setStatus($order::STATUS_PROCESSED);

        $this->assertEquals($order::STATUS_PROCESSED, $order->getStatus(), 'check status');

        // TODO - add email checking
    }

    public function testGetPaymentMethod()
    {
        $order = $this->getTestOrder();

        $this->assertEquals(
            'Purchase Order',
            $order->getPaymentMethod()->getName(),
            'check payment method'
        );

        $id = $order->getOrderId();

        \XLite\Core\Database::getEM()->clear();

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($id);

        $this->assertEquals(
            'Purchase Order',
            $order->getPaymentMethod()->getName(),
            'check payment method #2'
        );

    }

    public function testGetPaymentMethods()
    {
        $order = $this->getTestOrder();

        $etalon = array(
            'PurchaseOrder',
            'PhoneOrdering',
            'FaxOrdering',
            'MoneyOrdering',
            'Echeck',
            'COD',
        );

        $list = array();
        foreach ($order->getPaymentMethods() as $p) {
            $list[] = $p->getServiceName();
        }

        $this->assertEquals(array(), array_diff($etalon, $list), 'check extra methods');
        $this->assertEquals(array(), array_diff($list, $etalon), 'check missing methods');
    }

    public function testRenewPaymentMethod()
    {
        $order = $this->getTestOrder();
        $order->getProfile()->setLastPaymentId(0);

        $order->setPaymentMethod(null);
        $this->assertTrue(is_null($order->getPaymentMethod()), 'empty payment method');

        $order->renewPaymentMethod();
        $this->assertTrue(is_null($order->getPaymentMethod()), 'empty payment method #2');

        $order->getProfile()->setLastPaymentId(1);

        $order->renewPaymentMethod();
        $this->assertFalse(is_null($order->getPaymentMethod()), 'reassign payment method');
    }

    public function testgetActivePaymentTransactions()
    {
        $order = $this->getTestOrder();

        $list = $order->getActivePaymentTransactions();

        $this->assertEquals(1, count($list), 'check length');
        $this->assertEquals(
            'Purchase Order',
            $list[0]->getPaymentMethod()->getName(),
            'check payment method'
        );
        $this->assertEquals(
            $order->getTotal(),
            $list[0]->getValue(),
            'check total'
        );
    }

    public function testSetPaymentMethod()
    {
        $order = $this->getTestOrder();
        $order->getProfile()->setLastPaymentId(0);

        $order->setPaymentMethod(\XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(2));
        $this->assertEquals(
            'Phone Ordering',
            $order->getPaymentMethod()->getName(),
            'check payment method'
        );

        $order->setPaymentMethod(null);
        $this->assertTrue(
            is_null($order->getPaymentMethod()),
            'check payment method #2'
        );
    }

    public function testGetProfile()
    {
        $order = $this->getTestOrder();

        $id = $order->getOrderId();

        \XLite\Core\Database::getEM()->clear();

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($id);

        $this->assertNotEquals(
            $order->getOrigProfile()->getProfileId(),
            $order->getProfile()->getProfileId(),
            'check orig profile id'
        );
        $this->assertFalse(
            is_null($order->getProfile()->getOrder()),
            'check profile\'s order'
        );

        $this->assertEquals(
            $order->getOrderId(),
            $order->getProfile()->getOrder()->getOrderId(),
            'check profile\'s order id'
        );
    }

    public function testSetProfile()
    {
        $order = $this->getTestOrder();

        $p = $order->getProfile();

        $order->setProfile(null);

        $this->assertTrue(is_null($order->getProfile()), 'check profile');

        $order->setProfile($p);

        $this->assertEquals($p->getProfileId(), $order->getProfile()->getProfileId(), 'check profile id #2');
    }

    public function testGetOrigProfile()
    {
        $order = $this->getTestOrder();

        $id = $order->getOrderId();

        \XLite\Core\Database::getEM()->clear();

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($id);

        $this->assertNotEquals(
            $order->getProfile()->getProfileId(),
            $order->getOrigProfile()->getProfileId(),
            'check profile id'
        );

        $this->assertTrue(is_null($order->getOrigProfile()->getOrder()), 'check order id');

        $order->setOrigProfile(null);

        $this->assertEquals(
            $order->getProfile()->getProfileId(),
            $order->getOrigProfile()->getProfileId(),
            'check empty profile'
        );
    }

    public function testGetItemsFingerprint()
    {
        $order = $this->getTestOrder();

        $result = array();

        foreach ($order->getItems() as $item) {
            $result[] = array(
                $item->getItemId(),
                $item->getKey(),
                $item->getAmount()
            );
        }

        $result = md5(serialize($result));

        $this->assertEquals($result, $order->getItemsFingerprint(), 'check finger print');

        $order->getItems()->clear();

        $this->assertFalse($order->getItemsFingerprint(), 'check empty finger print');
    }

    public function testGetDescription()
    {
        $order = $this->getTestOrder();

        $result = array();

        foreach ($order->getItems() as $item) {
            $result[] = $item->getDescription();
        }

        $result = implode("\n", $result);

        $this->assertEquals($result, $order->getDescription(), 'check description');

        $order->getItems()->clear();

        $this->assertEquals('', $order->getDescription(), 'check empty description');
    }

    public function testSetOrigProfile()
    {
        $order = $this->getTestOrder();

        $p = $order->getOrigProfile();

        $order->setOrigProfile(null);

        $this->assertFalse(is_null($order->getOrigProfile()), 'check profile');
        $this->assertEquals($order->getProfile()->getProfileId(), $order->getOrigProfile()->getProfileId(), 'check profile id');

        $order->setOrigProfile($p);

        $this->assertEquals($p->getProfileId(), $order->getOrigProfile()->getProfileId(), 'check profile id #2');
    }

    public function testGetEventFingerprint()
    {
        $order = $this->getTestOrder();
        $product = $this->getProduct();
        $product->getInventory()->setEnabled(false);
        $etalon = array(
            'items' => array(
                array(
                    'item_id'     => $order->getItems()->get(0)->getItemId(),
                    'key'         => 'product.' . $product->getProductId(),
                    'object_type' => 'product',
                    'object_id'   => $product->getProductId(),
                    'options'     => array(),
                    'quantity'    => 1,
                ),
            ),
        );

        $this->assertEquals($etalon, $order->getEventFingerprint(), 'check fingerprint');

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($product);
        $item->setAmount(2);
        $item->setPrice($product->getPrice());

        $this->assertTrue($order->addItem($item), 'check add item');

        $etalon['items'][0]['quantity'] = 3;

        $this->assertEquals($etalon, $order->getEventFingerprint(), 'check fingerprint #2');

        $order->getItems()->clear();

        $etalon['items'] = array();

        $this->assertEquals($etalon, $order->getEventFingerprint(), 'check fingerprint (empty)');
    }

    public function testGetDetail()
    {
        $order = $this->getTestOrder();

        $this->assertTrue(is_null($order->getDetail('test')), 'check not-set detail');

        $order->setDetail('test', '123');

        $this->assertEquals('test', $order->getDetail('test')->getName(), 'check name');
        $this->assertEquals('123', $order->getDetail('test')->getValue(), 'check value');
        $this->assertTrue(is_null($order->getDetail('test')->getLabel()), 'check label');

        $order->getDetails()->removeElement($order->getDetail('test'));

        $this->assertTrue(is_null($order->getDetail('test')), 'check not-set detail #2');
    }

    public function testSetDetail()
    {
        $order = $this->getTestOrder();

        $this->assertTrue(is_null($order->getDetail('test')), 'check not-set detail');

        $order->setDetail('test', '123');

        $this->assertEquals('test', $order->getDetail('test')->getName(), 'check name');
        $this->assertEquals('123', $order->getDetail('test')->getValue(), 'check value');

        $order->setDetail('test', '456');
        $this->assertEquals('456', $order->getDetail('test')->getValue(), 'check value again');

        $order->getDetails()->removeElement($order->getDetail('test'));

        $this->assertTrue(is_null($order->getDetail('test')), 'check not-set detail #2');

        $order->setDetail('test', '123');

        $this->assertEquals('test', $order->getDetail('test')->getName(), 'check name #2');
        $this->assertEquals('123', $order->getDetail('test')->getValue(), 'check value #2');
        $this->assertTrue(is_null($order->getDetail('test')->getLabel()), 'check label');

        $order->setDetail('test', '999', 'lll');

        $this->assertEquals('999', $order->getDetail('test')->getValue(), 'check value #3');
        $this->assertEquals('lll', $order->getDetail('test')->getLabel(), 'check label #2');
    }

    public function testGetMeaningDetails()
    {
        $order = $this->getTestOrder();

        $this->assertEquals(array(), $order->getMeaningDetails(), 'check empty list');

        $order->setDetail('test', '123');
        $order->setDetail('test2', '456', 'lll');

        $d = $order->getDetails()->get(1);

        $this->assertEquals(array($d), $order->getMeaningDetails(), 'check list');

        $order->getDetails()->removeElement($d);

        $this->assertEquals(array(), $order->getMeaningDetails(), 'check empty list #2');

        $d = $order->getDetails()->get(0);
        $d->setLabel('ttt');

        $this->assertEquals(array($d), $order->getMeaningDetails(), 'check list #2');
    }

    public function testProcessSucceed()
    {
        $c = \XLite\Core\Database::getRepo('XLite\Model\Config')->findOneByName('enable_init_order_notif');
        $old = $c->getValue();

        $c = \XLite\Core\Database::getRepo('XLite\Model\Config')->findOneByName('enable_init_order_notif_customer');
        $old2 = $c->getValue();

        $order = $this->getTestOrder();
        $order->setStatus($order::STATUS_QUEUED);
        $order->processSucceed();

        // TODO - check email send

        $c = \XLite\Core\Database::getRepo('XLite\Model\Config')->findOneByName('enable_init_order_notif');
        $c->setValue($old);

        $c = \XLite\Core\Database::getRepo('XLite\Model\Config')->findOneByName('enable_init_order_notif_customer');
        $c->setValue($old2);
    }

    public function testRefreshItems()
    {
        $order = $this->getTestOrder();

        $order->refreshItems();

        // TODO - rework test after rework tax subsystem
    }

    protected function getTestOrder($new_order = false)
    {
        $order = parent::getTestOrder($new_order);

        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findOneBy(array('service_name' => 'PurchaseOrder'));
        $order->setPaymentMethod($method);

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }

}
