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

abstract class XLite_Tests_Model_OrderAbstract extends XLite_Tests_TestCase
{
    protected $testOrder = array(
        'tracking'       => 'test t',
        'notes'          => 'Test note',
    );

    protected $orderProducts = array();

    protected function getTestOrder()
    {
        $order = new \XLite\Model\Order();

        $order->map($this->testOrder);
        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));

        if ($this->orderProducts) {
            foreach ($this->orderProducts as $sku) {
                $product = $this->getProductBySku($sku);

                if (!$product) {
                    $this->fail('Product woth SKU ' . $sku . ' not found!');
                }

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

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findAll();
        foreach ($list as $p) {
            if (!$p->getOrder()) {
                $order->setProfileCopy($p);
                break;
            }
        }

        $order->calculate();

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }
}
