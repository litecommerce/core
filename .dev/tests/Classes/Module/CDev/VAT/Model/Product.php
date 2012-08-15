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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

require_once __DIR__ . '/ATax.php';

class XLite_Tests_Module_CDev_VAT_Model_Product extends XLite_Tests_Module_CDev_VAT_Model_ATax
{
    /**
     * testGetIncludedTaxList
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function testGetIncludedTaxList()
    {
        $product = $this->getProduct();

        \XLite\Core\Config::getInstance()->Shipping->anonymous_country = 'US';
        \XLite\Core\Config::updateInstance();

        $productTaxes = $product->getIncludedTaxList();

        $this->assertTrue(is_array($productTaxes), 'Returned not an array');

        foreach ($productTaxes as $k => $v) {
            $productTaxes[$k] = number_format(round($v, 2), 2);
        }

        $this->assertEquals(
            array('VAT' => 4.15),
            $productTaxes,
            'Wrong taxes returned'
        );
   }


    /**
     * getProduct 
     * 
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getProduct()
    {
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(22); // Find Binary Mom

        $this->assertNotNull($product, 'Product #22 (Binary Mom) not found');

        return $product;
    }
}
