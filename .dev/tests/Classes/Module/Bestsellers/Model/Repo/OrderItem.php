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

class XLite_Tests_Module_Bestsellers_Model_Repo_OrderItem extends XLite_Tests_TestCase
{

    /**
     *  Product id constants
     */
    const PR1 = 4004;
    const PR2 = 4043;
    const PR3 = 4045;
    const PR4 = 4049;

    /**
     *  Category id for the PR1 product
     */
    const CATEGORY = 1002;


    /**
     * Some information for the test order
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $testOrder = array(
        'tracking'       => 'test t',
        'notes'          => 'Test note',
    );

    /**
     * First test sequence of the bestsellers
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $test1 = array(
        0 => self::PR1,
        1 => self::PR2,
        2 => self::PR4,
    );

    /**
     * Second test sequence of the bestsellers
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $test2 = array(
        0 => self::PR1,
        1 => self::PR3,
        2 => self::PR2,
    );

    /** 
     * setUp
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function setUp()
    {   
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();

        $this->query(file_get_contents(__DIR__ . '/sql/product/setup.sql'));

        \XLite\Core\Database::getEM()->flush();
    }   
 */
    /** 
     * tearDown
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function tearDown()
    {
        parent::tearDown();

        \XLite\Core\Database::getEM()->clear();

        $this->query(file_get_contents(__DIR__ . '/sql/product/restore.sql'));

        \XLite\Core\Database::getEM()->flush();
    }
 */
    /**
     *  Test of bestseller for the root category
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testFindBestsellersRoot()
    {
        /**
         * First order goes with processed status 
         */
        $order = $this->getTestOrder(
            \XLite\Model\Order::STATUS_PROCESSED,
            array(
                self::PR1 => 50,
                self::PR2 => 40,
                self::PR4 => 30,
            )
        );

        $best = $this->findBestsellers(3);

        $this->assertEquals(count($this->test1), count($best), 'Wrong number of bestsellers was returned. (1)');

        /**
         * First sequence 
         */
        foreach ($this->test1 as $index => $id) {

            $this->assertTrue(isset($best[$index]), 'Not set #' . $index . ' product in bestsellers (1)');

            $this->assertEquals($best[$index]->getProductId(), $id, 'Wrong #' . $index . ' product in bestsellers (1)');

        }

        /**
         * Second order goes with completed status 
         */
        $order = $this->getTestOrder(
            \XLite\Model\Order::STATUS_COMPLETED,
            array(
                self::PR3 => 45,
            )
        );

        $best = $this->findBestsellers(3);

        $this->assertEquals(count($this->test2), count($best), 'Wrong number of bestsellers was returned. (2)');

        /**
         * Second sequence  
         */
        foreach ($this->test2 as $index => $id) {

            $this->assertTrue(isset($best[$index]), 'Not set #' . $index . ' product in bestsellers (2)');

            $this->assertEquals($best[$index]->getProductId(), $id, 'Wrong #' . $index . ' product in bestsellers (2)');

        }

    }

    /**
     * Test of bestseller in some non-root category
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testFindBestsellersCategory()
    {
        $order = $this->getTestOrder(
            \XLite\Model\Order::STATUS_COMPLETED,
            array(
                self::PR1 => 50,
                self::PR2 => 40,
                self::PR4 => 30,
            )   
        );  

        $best = $this->findBestsellers(1, self::CATEGORY);

        $this->assertEquals(1, count($best), 'Wrong number of bestsellers was returned (1)');

        $one = $best[0];

        $this->assertEquals(self::PR1, $one->getProductId(), 'Wrong root category bestsellers list');
    }


/**
 *  FOR INNER USE ONLY
 */

    /**
     * Prepare order
     * 
     * @param mixed $status ____param_comment____
     * @param array $items  ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTestOrder($status, array $items)
    {
        $order = new \XLite\Model\Order();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findAll();
        $profile = array_shift($list);
        unset($list);

        $order->map($this->testOrder);
        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));
        $order->setProfileId(0);

        if (!is_null($status)) {

            $order->setStatus($status);

        }   

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setPaymentMethod(\XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(3));

        foreach ($items as $index => $amount) {

            $item = new \XLite\Model\OrderItem();

            $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($index);

            if (!isset($p)) {
                $this->assertFalse(true, 'Product #' . $index . ' not found in DB!');
            }
            $item->setProduct($p);
            $item->setAmount($amount);
            $item->setPrice($p->getPrice());

            $order->addItem($item);
        }

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setProfileCopy($profile);
        $order->calculate();

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        return $order;
    }

    /**
     *  Wrapper for the REPO findBestsellers method
     * 
     * @param int $count ____param_comment____
     * @param int $cat   ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function findBestsellers($count = 0, $cat = 0)
    {   
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->findBestsellers($count, $cat);
    }   


}
