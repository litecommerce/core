<?php
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
 * @since     1.0.10
 *
 * @resource product
 */

class XLite_Web_Module_CDev_Sale_Admin_ProductDetails extends XLite_Web_Admin_AAdmin
{
    public function testNewProduct(){

        $productName = 'Sale_product';
        $productPrice = 100;
        $examples = array(
            array('sale_price' => '-10', 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('percent' => '10000', 'message' => 'Maximum limit is broken', 'expected_title' => ''),
            array('percent' => '-10', 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('percent' => '0', 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('sale_price' => '0', 'message' => 'New product has been added successfully', 'expected_title' => $productName)
        );

        $this->logIn();
        foreach($examples as $example){
            //Click new product
            $this->openAndWait("admin.php?target=add_product");

            //Product name/price
            $this->type("css=input[name='postedData[name]']", $productName);
            $this->type("css=input[name='postedData[price]']", $productPrice);
            //Check Sale
            $this->click("css=#participate-sale");
            //Sale fields
            if (isset($example['sale_price'])){
                $this->type("css=#sale-price-value-sale_price", $example['sale_price']);
            }
            else{
                $this->click("css=#sale-price-percent-off");
                $this->type("css=#sale-price-value-sale_percent", $example['percent']);
            }

            //Click save
            $this->clickAndWait("css=.main-button");
            $this->assertElementContainsText('css=#status-messages',$example['message']);

            //If product name in title
            if ($example['expected_title']){
                $this->assertElementContainsText("css=#page-title", $example['expected_title']);
                #Check customer page
                $this->checkCustomerPage($productName, $productPrice, $example);
            }
            else{
                $this->assertElementNotPresent("css=#page-title");
            }
        }

    }

    public function testEditProduct()
    {
        $productName = 'Sale_product';

        $productPrice = 100;

        $examples = array(
            array('sale_price' => '-10', 'message' => 'Minimum limit is broken'),
            array('percent' => '10000', 'message' => 'Maximum limit is broken'),
            array('percent' => '-10', 'message' => 'Minimum limit is broken'),
            array('percent' => '0', 'message' => 'Minimum limit is broken'),
            array('sale_price' => '0', 'message' => 'Product info has been updated successfully', 'check' => true)
        );

        $this->logIn();

        foreach($examples as $example) {

            //Click edit product
            $this->openAndWait("admin.php?target=product_list&itemsPerPage=100");
            $this->clickAndWait("//a[text()='$productName']");

            //Check Sale
            if (!$this->isChecked("css=#participate-sale")) {

                $this->click("css=#participate-sale");
            }
            //Sale fields
            if (isset($example['sale_price'])){

                $this->type("css=#sale-price-value-sale_price", $example['sale_price']);

            } else {

                $this->check("css=#sale-price-percent-off");
                $this->type("css=#sale-price-value-sale_percent", $example['percent']);
            }

            //Click save
            $this->clickAndWait("css=.main-button");
            $this->assertElementContainsText('css=#status-messages',$example['message']);

            //If product name in title
            if (isset($example['check'])) {

                $this->checkCustomerPage($productName, $productPrice, $example);
            }
        }
    }

    private function checkCustomerPage($name, $price, $example)
    {
        $this->openAndWait("admin.php?target=product_list&itemsPerPage=100");
        $this->clickAndWait("//a[text()='$name']");

        $url = $this->getLocation();

        $parts = explode("=", $url);

        $id = $parts[count($parts) - 1];

        $this->openShortCustomerAndWait("store/product/0/product_id-" . $id);

        if (isset($example['sale_price'])) {

            $salePrice = $example['sale_price'];
            $percent   = (int)((($price - $salePrice) * 100) / $price);
            $youSave   = $price - $salePrice;

        } else {

            $percent   = $example['percent'];
            $youSave   = ($price * $percent) / 100;
            $salePrice = $price - $youSave;
        }

        $price   = sprintf('%01.2f', $price);
        $youSave = sprintf('%01.2f', $youSave);

        //should see:
        if ($salePrice < $price){
            //Old price = $price
            //you save = price - sale price
            $this->assertElementContainsText("css=.sale-label-product-details", "Old price: $$price , you save $$youSave");

            //New price = sale price
            $this->assertElementContainsText("css=.product-price", $salePrice);

            //%off
            $this->assertElementPresent("css=.sale-banner");
            $this->assertElementContainsText("css=.percent", "$percent% off");

        } else {

            //Price = $price
            $this->assertElementContainsText("css=.product-price", $price);

            //no banner
            $this->assertElementNotPresent("css=.sale-banner");
        }
    }
}