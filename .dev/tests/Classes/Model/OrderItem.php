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

class XLite_Tests_Model_OrderItem extends XLite_Tests_Model_OrderAbstract
{
    public function testCreate()
    {
        $order = $this->getTestOrder();

        $this->assertEquals(1, $order->getItems()->count(), 'check items count');

        $item = $order->getItems()->get(0);

        $this->assertTrue(0 < $item->getItemId(), 'check items id');

        $this->assertEquals(
            $this->getProduct()->getProductId(),
            $item->getObject()->getId(),
            'check product id'
        );

        $this->assertEquals(
            $this->getProduct()->getName(),
            $item->getName(),
            'check name'
        );

        $this->assertEquals(
            $this->getProduct()->getSKU(),
            $item->getSKU(),
            'check sku'
        );

        $this->assertEquals(
            $this->getProduct()->getPrice(),
            $item->getPrice(),
            'check price'
        );

        $this->assertEquals(
            1,
            $item->getAmount(),
            'check amount'
        );

        // Saved modifiers
        $surcharge = new \XLite\Model\OrderItem\Surcharge;
        $surcharge->setType('shipping');
        $surcharge->setCode('ttt');
        $surcharge->setValue(10.00);
        $surcharge->setInclude(false);
        $surcharge->setAvailable(true);
        $surcharge->setClass(get_called_class());
        $surcharge->setName('test');

        $item->getSurcharges()->add($surcharge);
        $surcharge->setOwner($item);

        $count = count($item->getSurcharges());
        $this->assertEquals($surcharge, $item->getSurcharges()->get($count - 1), 'check surcharge');
    }

    public function testUpdate()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $id = $item->getItemId();

        $item->setAmount(2);

        \XLite\Core\Database::getEM()->persist($item);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        $this->assertEquals(2, $item->getAmount(), 'check amount');

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $p = $list[1];

        $item->setProduct($p);

        \XLite\Core\Database::getEM()->persist($item);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        $this->assertEquals($p->getProductId(), $item->getObject()->getId(), 'check object id');
    }

    public function testDelete()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $order->getItems()->removeElement($item);
        \XLite\Core\Database::getEM()->remove($item);

        $id = $item->getItemId();

        \XLite\Core\Database::getEM()->flush();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')
            ->find($id);

        $this->assertTrue(is_null($item), 'check removed item');
    }

    public function testGetProduct()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $id = $item->getItemId();

        $this->assertEquals($this->getProduct(), $item->getProduct(), 'check object');

        $item = new \XLite\Model\OrderItem();

        $this->assertTrue(is_null($item->getProduct()), 'check empty object #2');

        \XLite\Core\Database::getEM()->clear();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        $this->assertEquals($this->getProduct(), $item->getProduct(), 'check object #2');

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        \XLite\Core\Database::getEM()->remove($this->getProduct());
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        $this->assertFalse(is_null($item), 'check item from DB');
        $this->assertFalse(is_null($item->getProduct()), 'check dump object #2');
    }

    public function testSetObject()
    {
        $order = $this->getTestOrder();
        $item = $order->getItems()->get(0);

        $this->assertNotEquals(0, $item->getPrice(), 'check non-empty price');
        $this->assertNotEquals('', $item->getName(), 'check non-empty name');
        $this->assertNotEquals('', $item->getSku(), 'check non-empty sku');

        $item->setProduct(null);

        $this->assertEquals(0, $item->getPrice(), 'check empty price');
        $this->assertEquals('', $item->getName(), 'check empty name');
        $this->assertEquals('', $item->getSku(), 'check empty sku');
        $order->getItems()->removeElement($item);
        Xlite\Core\Database::getEM()->remove($item);
    }

    public function testSetAmount()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(1, $item->getAmount(), 'check amount');

        $id = $item->getItemId();

        $item->setAmount(2);

        \XLite\Core\Database::getEM()->persist($item);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        $this->assertEquals(2, $item->getAmount(), 'check amount #2');

        $item->setAmount(0);

        $this->assertEquals(1, $item->getAmount(), 'check amount #3');

        $item->setAmount(99999);

        $this->assertEquals(9999, $item->getAmount(), 'check amount #4');
    }

    public function testHasImage()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            (bool)$this->getProduct()->getImage(),
            $item->hasImage(),
            'check thumbnail'
        );
    }

    public function testGetImageURL()
    {
        $order = $this->getTestOrder();
        $item = $order->getItems()->get(0);
        print_r($item->getProduct()->getName());
        $this->assertEquals(
            $this->getProduct()->getImage()->getURL(),
            $item->getImageURL(),
            'check thumbnail url'
        );

    }

    public function testGetImage()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            $this->getProduct()->getImage(),
            $item->getImage(),
            'check thumbnail'
        );
    }

    public function testGetDescription()
    {

        $order = $this->getTestOrder();
        $item = $order->getItems()->get(0);

        $this->assertEquals(
            $this->getProduct()->getName() . ' (1)',
            $item->getDescription(),
            'check description'
        );
    }

    public function testGetURL()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            $this->getProduct()->getURL(),
            $item->getURL(),
            'check object page URL'
        );
    }

    public function testIsShippable()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            !$this->getProduct()->getFreeShipping(),
            $item->isShippable(),
            'check shipped status'
        );
    }

    public function testGetKey()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            'product.' . $this->getProduct()->getProductId(),
            $item->getKey(),
            'check key'
        );
    }

    public function testIsValid()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertTrue(
            $item->isValid(),
            'check validate status'
        );
    }

    public function testGetEventCell()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $etalon = array(
            'item_id'     => $item->getItemId(),
            'key'         => 'product.' . $this->getProduct()->getProductId(),
            'object_type' => 'product',
            'object_id'   => $this->getProduct()->getProductId(),
            'options'     => array(),
        );

        $this->assertEquals(
            $etalon,
            $item->getEventCell(),
            'check event item info'
        );

    }
}
