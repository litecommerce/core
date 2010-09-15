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

class XLite_Tests_Module_Bestsellers_Model_Repo_OrderItem extends XLite_Tests_TestCase
{
    protected $testOrder = array(
        'tracking'       => 'test t',
        'notes'          => 'Test note',
    );

    protected $bestsUrls1 = array(
        0 => 4012,
        1 => 4003,
        2 => 4030,
    );

    protected $bestsUrls2 = array(
        0 => 4012,
        1 => 4009,
        2 => 4003,
    );  

    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();
    }

    public function testGetBestsellers()
    {
        $best = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->getBestsellers(1);

        $this->assertEquals(1, count($best), 'Wrong number of bestsellers was returned (1)');

        $one = $best[0];

        $this->assertEquals(3002, $one->getProduct()->getProductId(), 'Wrong root category bestsellers list');
    }

    public function testCollection()
    {
        $order = $this->getTestOrder(
            \XLite\Model\Order::STATUS_PROCESSED,
            array(
                4012 => 50,
                4003 => 40,
                4030 => 30,
            )
        );

        $best = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->getBestsellers(3);

        foreach ($this->bestsUrls1 as $index => $id) {

            $this->assertEquals($best[$index]->getObjectId(), $id, 'Wrong #' . $index . ' product in bestsellers (1)');

        }

        $order = $this->getTestOrder(
            \XLite\Model\Order::STATUS_COMPLETED,
            array(
                4009 => 45,
            )
        );

        $best = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->getBestsellers(3);

        $this->assertNotEquals(count($best), 1, 'Wrong number of bestsellers was returned. (1).0');

        foreach ($this->bestsUrls2 as $index => $id) {

            $this->assertEquals($best[$index]->getObjectId(), $id, 'Wrong #' . $index . ' product in bestsellers (2)');

        }

    }

    public function testGetBestsellersCategory()
    {   
        $best = \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->getBestsellers(1, 1002);

        $this->assertEquals(1, count($best), 'Wrong number of bestsellers was returned (1)');

        $one = $best[0];

        $this->assertEquals(4009, $one->getObjectId(), 'Wrong root category bestsellers list');

    }   

    protected function getTestOrder($status, array $items)
    {
        $order = new \XLite\Model\Order();

        $profile = new \XLite\Model\Profile();
        $list = $profile->findAll();
        $profile = array_shift($list);
        unset($list);

        $order->map($this->testOrder);
        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));
        $order->setProfileId(0);

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setPaymentMethod(\XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(3));

        foreach ($items as $index => $amount) {

            $item = new \XLite\Model\OrderItem();

            $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($index);

            if (!isset($p)) {
                $this->markTestSkipped('Product #' . $index . ' not found in DB!');
            }
            $item->setProduct($p);
            $item->setAmount($amount);
            $item->setPrice($p->getPrice());

            $order->addItem($item);
        }

        if (!is_null($status)) {

            $order->setStatus($status);

        }

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setProfileCopy($profile);
        $order->calculate();

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        return $order;
    }   




}
