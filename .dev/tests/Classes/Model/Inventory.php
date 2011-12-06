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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

/**
 * XLite_Tests_Model_Inventory
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 * @resource product
 */
class XLite_Tests_Model_Inventory extends XLite_Tests_TestCase
{
    /**
     * Default values for testing
     */

    const DEFAULT_INVENTORY_AMOUNT = 997;
    const DEFAULT_LOW_LIMIT_AMOUNT = 4;

    /**
     * Product amounts in cart
     */

    const CART_AMOUNT_WITH_INVENTORY    = 11;
    const CART_AMOUNT_WITHOUT_INVENTORY = 24;

    /**
     * @var XLite\Model\Product
     */
    protected  $productWithInventory;
    /**
     * @var XLite\Model\Product
     */
    protected  $productWithoutInventory;

    function setUp(){
        $products = \XLite\Core\Database::getRepo('XLite\Model\Product')->findByEnabled(true);
        $i = 0;
        $em = \XLite\Core\Database::getEM();
        while (count($products) < 2){
            $product = new \XLite\Model\Product(array(
			    'price'         => 1,
			    'enabled'       => true,
                'name'          => 'test name'.$i,
                'description'   => 'test description'
		    ));
            $em->persist($product);
            $products[] = $product;
            $i++;
        }
        $em->flush();
        $this->productWithInventory = $products[0];
        $this->productWithoutInventory = $products[1];
    }

    /**
     * @return XLite\Model\Product
     */
    protected function getProductWithInventory(){
        $this->productWithInventory->getInventory()->setEnabled(true);
        $this->productWithInventory->getInventory()->setAmount(self::DEFAULT_INVENTORY_AMOUNT);
        return $this->productWithInventory;
    }
    /**
     * @return XLite\Model\Product
     */
    protected function getProductWithoutInventory(){
        $this->productWithoutInventory->getInventory()->setEnabled(false);
        $this->productWithoutInventory->getInventory()->setAmount(self::DEFAULT_INVENTORY_AMOUNT);
        return $this->productWithoutInventory;
    }
    /**
     * getProductWithInventoryAndLowLimit
     *
     * @return XLite\Model\Product
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProductWithInventoryAndLowLimit()
    {
        $product = $this->getProductWithInventory();
        $product->getInventory()->setLowLimitEnabled(true);
        $product->getInventory()->setLowLimitAmount(self::DEFAULT_LOW_LIMIT_AMOUNT);
        $product->getInventory()->setAmount(self::DEFAULT_LOW_LIMIT_AMOUNT - 1);

        return $product;
    }

    /**
     * getProductWithoutInventoryAndLowLimit
     *
     * @return XLite\Model\Product
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProductWithoutInventoryAndLowLimit()
    {
        $product = $this->getProductWithoutInventory();
        $product->getInventory()->setLowLimitEnabled(true);
        $product->getInventory()->setLowLimitAmount(self::DEFAULT_LOW_LIMIT_AMOUNT);
        $product->getInventory()->setAmount(self::DEFAULT_LOW_LIMIT_AMOUNT - 1);

        return $product;
    }

    /**
     * getInventory
     *
     * @param mixed $type ____param_comment____
     *
     * @return XLite\Model\Inventory
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInventory($type)
    {
        return $this->{'getProduct' . $type}()->getInventory();
    }

    /**
     * prepareCart
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCart()
    {
        $cart = \XLite\Model\Cart::getInstance();
        $cart->setItems(new \Doctrine\Common\Collections\ArrayCollection());

        $item = new \XLite\Model\OrderItem();
        $item->setProduct($this->getProductWithInventory());
        $item->setAmount(self::CART_AMOUNT_WITH_INVENTORY);
        $cart->addItem($item);

        $item = new \XLite\Model\OrderItem();
        $item->setProduct($this->getProductWithoutInventory());
        $item->setAmount(self::CART_AMOUNT_WITHOUT_INVENTORY);
        $cart->addItem($item);
    }


    /**
     * testChangeAmount
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
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

        // Negative amount handling
        $inventory = $this->getInventory('WithInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(-self::DEFAULT_INVENTORY_AMOUNT - 10);
        $this->assertEquals($amount = 0, $inventory->getAmount(), '#3: expected amount: ' . $amount);

        // Negative amount handling (inventory is disabled, but the resalt as the same as previous)
        $inventory = $this->getInventory('WithoutInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(-self::DEFAULT_INVENTORY_AMOUNT - 10);
        $this->assertEquals($amount, $inventory->getAmount(), '#4: expected amount: ' . $amount);
    }

    /**
     * testGetAvailableAmount
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetAvailableAmount()
    {
        $this->prepareCart();

        // Inventory enabled
        $this->assertEquals(
            $amount = self::DEFAULT_INVENTORY_AMOUNT - self::CART_AMOUNT_WITH_INVENTORY,
            $this->getInventory('WithInventory')->getAvailableAmount(),
            '#1: expected amount: ' . $amount
        );

        // Inventory disabled
        $this->assertEquals(
            $amount = \XLite\Model\Inventory::AMOUNT_DEFAULT_INV_TRACK,
            $this->getInventory('WithoutInventory')->getAvailableAmount(),
            '#2: expected amount: ' . $amount
        );
    }

    /**
     * testIsOutOfStock
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsOutOfStock()
    {
        $this->prepareCart();

        // In stock (with inventory)
        $inventory = $this->getInventory('WithInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(-self::DEFAULT_INVENTORY_AMOUNT + self::CART_AMOUNT_WITH_INVENTORY + 1);
        $this->assertFalse($inventory->isOutOfStock(), '#1: expected "In stock" flag: "false"');

        // Out of stock (with inventory)
        $inventory = $this->getInventory('WithInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(-self::DEFAULT_INVENTORY_AMOUNT + self::CART_AMOUNT_WITH_INVENTORY - 1);
        $this->assertTrue($inventory->isOutOfStock(), '#2: expected "In stock" flag: "true"');

        // In stock (without inventory)
        $inventory = $this->getInventory('WithoutInventory');
        $amount = $inventory->getAmount();
        $inventory->changeAmount(-self::DEFAULT_INVENTORY_AMOUNT + self::CART_AMOUNT_WITH_INVENTORY - 1);
        $this->assertFalse($inventory->isOutOfStock(), '#3: expected "In stock" flag: "false"');
    }

    /**
     * testIsLowLimitReached
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsLowLimitReached()
    {
        // With inventory (reached)
        $inventory = $this->getInventory('WithInventoryAndLowLimit');
        $amount = $inventory->getAmount();
        $this->assertTrue($inventory->isLowLimitReached(), '#1: expected "Low limit reached" flag: "true"');

        // With inventory (not reached)
        $inventory = $this->getInventory('WithInventory');
        $amount = $inventory->getAmount();
        $this->assertFalse($inventory->isLowLimitReached(), '#2: expected "Low limit reached" flag: "false"');

        // Without inventory (not reached)
        $inventory = $this->getInventory('WithoutInventoryAndLowLimit');
        $amount = $inventory->getAmount();
        $this->assertFalse($inventory->isLowLimitReached(), '#3: expected "Low limit reached" flag: "false"');
    }

    /**
     * testProccessPreUpdate
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testProccessPreUpdate()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Inventory')->update($this->getInventory('WithInventoryAndLowLimit'));
    }
}
