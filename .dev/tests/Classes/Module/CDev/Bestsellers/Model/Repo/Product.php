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

class XLite_Tests_Module_CDev_Bestsellers_Model_Repo_Product extends XLite_Tests_Model_OrderAbstract
{

    /**
     *  Product id constants
     */
    const PR1 = '00002';
    const PR2 = '00041';
    const PR3 = '00043';
    const PR4 = '00047';

    /**
     *  Category id for the PR1 product
     */
    const CATEGORY = 'apparel';


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
        $order = $this->getLocalTestOrder(
            \XLite\Model\Order::STATUS_PROCESSED,
            array(
                self::PR1 => 500,
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

            $this->assertEquals($best[$index]->getSku(), $id, 'Wrong #' . $index . ' product in bestsellers (1)' . $best[$index]->getSku() . ' ' . $id);

        }

        /**
         * Second order goes with completed status
         */
        $order = $this->getLocalTestOrder(
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

            $this->assertEquals($best[$index]->getSku(), $id, 'Wrong #' . $index . ' product in bestsellers (2)');

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
        $order = $this->getLocalTestOrder(
            \XLite\Model\Order::STATUS_COMPLETED,
            array(
                self::PR1 => 500,
                self::PR2 => 400,
                self::PR4 => 300,
            )
        );

        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanURL' => self::CATEGORY));
        $this->assertNotNull($c, 'check category');
        $best = $this->findBestsellers(1, $c->getCategoryId());

        $this->assertEquals(1, count($best), 'Wrong number of bestsellers was returned (1)');

        $one = $best[0];

        $this->assertEquals(self::PR1, $one->getSku(), 'Wrong root category bestsellers list');
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
    protected function getLocalTestOrder($status, array $items)
    {
        $this->orderProducts = array_keys($items);

        $order = $this->getTestOrder(true);

        if (!is_null($status)) {
            $order->setStatus($status);
        }

        $order->setPaymentMethod(\XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(3));

        foreach ($order->getItems() as $index => $item) {

            if (isset($items[$item->getSku()])) {
                $item->setAmount($items[$item->getSku()]);
            }
        }

        $order->calculate();

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
        return \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->findBestsellers(new \XLite\Core\CommonCell(array('orderBy' => array('translations.name', 'asc'))), $count, $cat);
    }


}
