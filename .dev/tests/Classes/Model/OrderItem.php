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

require_once PATH_TESTS . '/FakeClass/Model/OrderItem.php';

class XLite_Tests_Model_OrderItem extends XLite_Tests_TestCase
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

        $this->assertEquals(1, $order->getItems()->count(), 'check items count');

        $item = $order->getItems()->get(0);

        $this->assertTrue(0 < $item->getItemId(), 'check items id');

        $this->assertEquals('product', $item->getObjectType(), 'check object type');

        $this->assertEquals(
            $this->getProduct()->getProductId(),
            $item->getObjectId(),
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

        $this->assertEquals($p->getProductId(), $item->getObjectId(), 'check object id');
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

        $this->assertNull($item, 'check removed item');
    }

    public function testGetObject()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals($this->getProduct(), $item->getObject(), 'check object');

        $item->setObjectType('xxx');

        $this->assertNull($item->getObject(), 'check empty object');

        $item = new \XLite\Model\OrderItem();

        $this->assertNull($item->getObject(), 'check empty object');
    }

    public function testGetProduct()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $id = $item->getItemId();

        $this->assertEquals($this->getProduct(), $item->getProduct(), 'check object');

        $item->setObjectType('xxx');

        $this->assertNull($item->getProduct(), 'check empty object');

        $item = new \XLite\Model\OrderItem();

        $this->assertNull($item->getProduct(), 'check empty object #2');

        $item->setObjectType('product');

        $p = new \XLite\Model\Product(
            array(
                'name' => null,
                'sku'  => '',
            )
        );

        $this->assertEquals($p, $item->getProduct(), 'check dump object');

        \XLite\Core\Database::getEM()->clear();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        $this->assertEquals($this->getProduct(), $item->getProduct(), 'check object #2');

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);

        \XLite\Core\Database::getEM()->remove($this->getProduct());
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $item = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->find($id);
        $p = new \XLite\Model\Product(
            array(
                'name' => $item->getName(),
                'sku'  => $item->getSKU(),
            )
        );
        $this->assertEquals($p, $item->getProduct(), 'check dump object #2');
    }

    public function testSetProduct()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $p = $list[1];

        $item->setObjectType('xxx');

        $item->setProduct($p);

        $this->assertEquals($p, $item->getProduct(), 'check object');
        $this->assertEquals($p, $item->getObject(), 'check object #2');

        $this->assertEquals('product', $item->getObjectType(), 'check object type');
    }

    public function testSetObject()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $p = $list[1];

        $item->setObjectType('xxx');

        $item->setObject($p);

        $this->assertNull($item->getProduct(), 'check object');
        $this->assertNull($item->getObject(), 'check object #2');

        $item->setObjectType('product');

        $this->assertEquals($p->getProductId(), $item->getProduct()->getProductId(), 'check object #3');
        $this->assertEquals($p->getProductId(), $item->getObject()->getProductId(), 'check object #4');
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

    public function testGetWeight()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals($this->getProduct()->getWeight(), $item->getWeight(), 'check weight');

        $item->setAmount(2);

        $this->assertEquals(2 * $this->getProduct()->getWeight(), $item->getWeight(), 'check weight #2');

        $item = new \XLite\Model\OrderItem();

        $this->assertEquals(0, $item->getWeight(), 'check weight #3');
    }

    public function testHasThumbnail()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            (bool)$this->getProduct()->getThumbnail()->getImageId(),
            $item->hasThumbnail(),
            'check thumbnail'
        );
    }

    public function testGetThumbnailURL()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            $this->getProduct()->getThumbnail()->getURL(),
            $item->getThumbnailURL(),
            'check thumbnail url'
        );

    }

    public function testGetThumbnail()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            $this->getProduct()->getThumbnail(),
            $item->getThumbnail(),
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

    public function testIsShipped()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            !$this->getProduct()->getFreeShipping(),
            $item->isShipped(),
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

    public function testGetDiscountablePrice()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            $item->getDiscountablePrice(),
            $item->getPrice(),
            'check discountable price'
        );
    }

    public function testGettaxableTotal()
    {
        $order = $this->getTestOrder();

        $item = $order->getItems()->get(0);

        $this->assertEquals(
            $item->getTaxableTotal(),
            $item->getTotal(),
            'check taxable total'
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
        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));
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
