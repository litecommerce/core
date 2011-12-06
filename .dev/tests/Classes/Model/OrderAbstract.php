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

abstract class XLite_Tests_Model_OrderAbstract extends XLite_Tests_TestCase
{
    protected $testOrder = array(
        'tracking' => 'test t',
        'notes'    => 'Test note',
    );

    protected $orderProducts = array();
    /**
     * @var XLite\Model\Order
     */
    protected $order;
    protected $orders;

    protected function getTestOrder($new_order = false)
    {
        if ($this->order && !$new_order)
            return $this->order;
        \XLite\Core\Auth::getInstance()->logoff();

        $this->testOrder['date'] = time();

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->insert($this->testOrder);
        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));

        if ($this->orderProducts) {
            foreach ($this->orderProducts as $sku) {
                $product = $this->getProductBySku($sku);

                $this->assertNotNull($product, 'Product with SKU ' . $sku . ' not found!');

                $item = new \XLite\Model\OrderItem();
                $item->setProduct($product);
                $item->setAmount(1);

                $order->addItem($item);
            }

        } else {
            $item = new \XLite\Model\OrderItem();
            $item->setProduct($this->getProduct());
            $item->setAmount(1);

            $order->addItem($item);
        }

        $order->calculate();

        \XLite\Core\Database::getRepo('XLite\Model\Order')->update($order);

        $list = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findAll();
        $found = false;
        foreach ($list as $p) {
            if (!$p->getOrder() && $p->getLogin() == 'rnd_tester@cdev.ru') {
                $order->setProfileCopy($p);
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, 'test order\'s profile is not found');

        $order->calculate();

        \XLite\Core\Database::getRepo('XLite\Model\Order')->update($order);
        if (!$this->order || $new_order)
            $this->orders[] = $order;
        $this->order = $order;
        return $order;
    }

    protected function tearDown(){
        if (!empty($this->orders))
            foreach($this->orders as $order)
                $this->clearEntity($order);
        if ($this->order)
            $this->clearEntity($this->order);
        $this->order = null;
        parent::tearDown();
    }

    static function tearDownAfterClass(){
        \XLite\Core\Database::getEM()->flush();
    }

}
