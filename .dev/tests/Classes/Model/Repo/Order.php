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

class XLite_Tests_Model_Repo_Order extends XLite_Tests_Model_OrderAbstract
{

    public function testFindAllExpiredTemporaryOrders()
    {
        $order = $this->getTestOrder();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Order')->findAllExpiredTemporaryOrders();
        foreach ($list as $o) {
            \XLite\Core\Database::getEM()->remove($o);
        }
        \XLite\Core\Database::getEM()->flush();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Order')->findAllExpiredTemporaryOrders();

        $this->assertEquals(0, count($list), 'empty list');

        $order->setStatus(\XLite\Model\Order::STATUS_TEMPORARY);
        $order->setDate(time() - \XLite\Model\Repo\Order::ORDER_TTL - 1);

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Order')->findAllExpiredTemporaryOrders();

        $this->assertEquals(1, count($list), 'not empty list');
        $this->assertEquals($order->getOrderId(), $list[0]->getOrderId(), 'check order id');
    }

    public function testCreateQueryBuilder()
    {
        $repo = \XLite\Core\Database::getRepo('XLite\Model\Order');

        $qb = $repo->createQueryBuilder();

        $where = new \Doctrine\ORM\Query\Expr\Andx(array('o.status != :tempStatus'));
        $this->assertEquals(
            $where,
            $qb->getDQLPart('where'),
            'check where'
        );
        $this->assertEquals(
            array('tempStatus' => \XLite\Model\Order::STATUS_TEMPORARY),
            $qb->getParameters(),
            'check parameters'
        );

        $qb = $repo->createQueryBuilder('o', false);

        $this->assertNull(
            $qb->getDQLPart('where'),
            'check where #2'
        );
        $this->assertEquals(
            array(),
            $qb->getParameters(),
            'check parameters #2'
        );
    }

    public function testCollectGarbage()
    {
        $order = $this->getTestOrder();

        $order->setStatus(\XLite\Model\Order::STATUS_TEMPORARY);
        $order->setDate(time() - \XLite\Model\Repo\Order::ORDER_TTL - 1);

        \XLite\Core\Database::getEM()->flush();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Order')->findAllExpiredTemporaryOrders();

        $this->assertTrue(0 < count($list), 'not empty list');

        \XLite\Core\Database::getRepo('XLite\Model\Order')->collectGarbage();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Order')->findAllExpiredTemporaryOrders();

        $this->assertEquals(0, count($list), 'empty list');
    }

    public function testSearch()
    {
        $list = \XLite\Core\Database::getRepo('XLite\Model\Order')->findAll();
        foreach ($list as $o) {
            \XLite\Core\Database::getEM()->remove($o);
        }
        \XLite\Core\Database::getEM()->flush();
        \XLite\Core\Database::getEM()->clear();

        $o1 = $this->getTestOrder();
        $o1->setStatus($o1::STATUS_QUEUED);
        \XLite\Core\Database::getEM()->flush();

        $o2 = $this->getTestOrder(true);
        $o2->setStatus($o1::STATUS_PROCESSED);
        \XLite\Core\Database::getEM()->flush();

        $o3 = $this->getTestOrder(true);

        $repo = \XLite\Core\Database::getRepo('XLite\Model\Order');

        // By order id
        \XLite\Core\Database::getEM()->clear();
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{$repo::P_ORDER_ID} = $o1->getOrderId();

        $list = $repo->search($cnd);

        $this->assertEquals(1, count($list), 'check length');
        $this->assertEquals($o1->getOrderId(), $list[0]->getOrderId(), 'check order id');

        $cnd = new \XLite\Core\CommonCell();
        $cnd->{$repo::P_ORDER_ID} = 0;

        $list = $repo->search($cnd);

        $this->assertEquals(3, count($list), 'check length (empty)');

        // By profile id
        \XLite\Core\Database::getEM()->clear();
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{$repo::P_PROFILE_ID} = $o1->getOrigProfile()->getProfileId();

        $list = $repo->search($cnd);

        $this->assertEquals(3, count($list), 'check length #2');

        // By email
        \XLite\Core\Database::getEM()->clear();
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{$repo::P_EMAIL} = $o1->getOrigProfile()->getLogin();

        $list = $repo->search($cnd);

        $this->assertEquals(3, count($list), 'check length #3');

        // By status
        \XLite\Core\Database::getEM()->clear();
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{$repo::P_STATUS} = $o1::STATUS_QUEUED;

        $list = $repo->search($cnd);

        $this->assertEquals(1, count($list), 'check length #4');
        $this->assertEquals($o1->getOrderId(), $list[0]->getOrderId(), 'check order id #2');

        $cnd->{$repo::P_STATUS} = $o1::STATUS_PROCESSED;
        $list = $repo->search($cnd);

        $this->assertEquals(1, count($list), 'check length #5');
        $this->assertEquals($o2->getOrderId(), $list[0]->getOrderId(), 'check order id #3');

        // By date
        \XLite\Core\Database::getEM()->clear();
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{$repo::P_DATE} = array($o1->getDate() - 100, $o3->getDate() + 100);

        $list = $repo->search($cnd);

        $this->assertEquals(3, count($list), 'check length #6');

        // Search with order by
        \XLite\Core\Database::getEM()->clear();
        $cnd->{$repo::P_ORDER_BY} = array('o.order_id', 'asc');

        $list = $repo->search($cnd);

        $this->assertEquals($o1->getOrderId(), $list[0]->getOrderId(), 'check order id #4');
        $this->assertEquals($o3->getOrderId(), $list[2]->getOrderId(), 'check order id #5');

        $cnd->{$repo::P_ORDER_BY} = array('o.order_id', 'desc');

        $list = $repo->search($cnd);

        $this->assertEquals($o3->getOrderId(), $list[0]->getOrderId(), 'check order id #6');
        $this->assertEquals($o1->getOrderId(), $list[2]->getOrderId(), 'check order id #7');

        // With limit
        \XLite\Core\Database::getEM()->clear();
        $cnd->{$repo::P_LIMIT} = array(0, 2);

        $list = $repo->search($cnd);

        $this->assertEquals(2, count($list), 'check length #7');

        $cnd->{$repo::P_LIMIT} = array(2, 1);

        $list = $repo->search($cnd);

        $this->assertEquals(1, count($list), 'check length #8');
        $this->assertEquals($o1->getOrderId(), $list[0]->getOrderId(), 'check order id #8');

        $cnd->{$repo::P_ORDER_ID} = $o1->getOrderId();
        $cnt = $repo->search($cnd, true);
        $this->assertEquals(1, $cnt, 'check length #9');

        $cnd = new \XLite\Core\CommonCell();
        $cnt = $repo->search($cnd, true);
        $this->assertEquals(3, $cnt, 'check length #10');

        $cnd->{$repo::P_ORDER_ID} = -1;
        $cnt = $repo->search($cnd, true);
        $this->assertEquals(0, $cnt, 'check length #11');
    }
}
