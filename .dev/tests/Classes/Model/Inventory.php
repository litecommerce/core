<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Inventory class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Tests_Model_Inventory 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Tests_Model_Inventory extends XLite_Tests_TestCase
{
    /**
     * Default values for testing 
     */

    const DEFAULT_INVENTORY_AMOUNT = 997;
    const DEFAULT_LOW_LIMIT_AMOUNT = 4;


    /**
     * getProductWithInventory 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductWithInventory()
    {
        $product = $this->getProduct();
        $product->getInventory()->setEnabled(true);
        $product->getInventory()->setAmount(self::DEFAULT_INVENTORY_AMOUNT);

        return $product;
    }

    /**
     * getProductWithInventoryAndLowLimit 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductWithInventoryAndLowLimit()
    {
        $product = $this->getProductWithInventory();
        $product->getInventory()->setLowLimitEnabled(true);
        $product->getInventory()->setLowLimitAmount(self::DEFAULT_LOW_LIMIT_AMOUNT);

        return $product;
    }

    /**
     * getProductWithoutInventory 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductWithoutInventory()
    {
        $product = $this->getProduct();
        $product->getInventory()->setEnabled(false);
        $product->getInventory()->setAmount(self::DEFAULT_INVENTORY_AMOUNT);

        return $product;
    }

    /**
     * getProductWithoutInventoryAndLowLimit 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductWithoutInventoryAndLowLimit()
    {
        $product = $this->getProductWithoutInventory();
        $product->getInventory()->setLowLimitEnabled(true);
        $product->getInventory()->setLowLimitAmount(self::DEFAULT_LOW_LIMIT_AMOUNT);

        return $product;
    }

    /**
     * getInventory 
     * 
     * @param mixed $type ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInventory($type)
    {
        return $this->{'getProduct' . $type}()->getInventory();
    }


    /**
     * testChangeAmount 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testChangeAmount()
    {
        // Increase amount
        $inventory = $this->getInventory('WithInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(30);
        $this->assertEquals($amount += 30, $inventory->getAmount(), '#1: expected amount: ' . $amount);

        // Decrease amount
        $inventory = $this->getInventory('WithInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(-30);
        $this->assertEquals($amount += -30, $inventory->getAmount(), '#2: expected amount: ' . $amount);

        // Negative amount handling (commented for a while)
        /*$inventory = $this->getInventory('WithInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(-self::DEFAULT_INVENTORY_AMOUNT - 10);
        $this->assertEquals(0, $inventory->getAmount(), '#3: expected amount: ' . $amount);*/
    }

    /**
     * testGetAvailableAmount 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetAvailableAmount()
    {

    }

    public function testIsOutOfStock()
    {
    }
}
