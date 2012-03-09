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
    function testNewProduct(){

        $productName = 'Sale_product';
        $productPrice = 100;
        $examples = array(
            array('sale_price' => -10, 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('percent' => 10000, 'message' => 'Maximum limit is broken', 'expected_title' => ''),
            array('percent' => -10, 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('percent' => 0, 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('sale_price' => 0, 'message' => 'New product has been added successfully', 'expected_title' => $productName)
        );

        foreach($examples as $example){
            #Click new product
            $this->openAndWait("admin.php?target=add_product");
            #Product name/price
            $this->type("input[@name='postedData[name]']", $productName);
            $this->type("input[@name='postedData[price]']", $productPrice);
            #Check Sale
            $this->check("#participate-sale");
            #Sale fields
            if ($example['sale_price']){
                $this->typeKeys("#sale-price-value-sale_price", $example['sale_price']);
            }
            else{
                $this->check("#sale-price-percent-off");
                $this->typeKeys("#sale-price-value-sale_percent", $example['percent']);
            }
            #Click save
            $this->clickAndWait(".main-button");
            #If product name in title
            if ($example['expected_title']){
                $this->assertElementContainsText("#page-title", $example['expected_title']);
                #Check customer page
                $this->checkCustomerPage($productName, $productPrice, $example);
            }
            else{
                $this->assertElementNotPresent("#page-title");
            }
        }

    }

    function testEditProduct(){
        $productName = 'Sale_product';
        $productPrice = 100;
        $examples = array(
            array('sale_price' => -10, 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('percent' => 10000, 'message' => 'Maximum limit is broken', 'expected_title' => ''),
            array('percent' => -10, 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('percent' => 0, 'message' => 'Minimum limit is broken', 'expected_title' => ''),
            array('sale_price' => 0, 'message' => 'New product has been added successfully', 'expected_title' => $productName)
        );

        foreach($examples as $example){
            #Click edit product
            $this->openAndWait("admin.php?target=product_list");
            $this->clickAndWait("//a[text()='$productName'");

            #Check Sale
            $this->check("#participate-sale");
            #Sale fields
            if ($example['sale_price']){
                $this->typeKeys("#sale-price-value-sale_price", $example['sale_price']);
            }
            else{
                $this->check("#sale-price-percent-off");
                $this->typeKeys("#sale-price-value-sale_percent", $example['percent']);
            }
            #Click save
            $this->clickAndWait(".main-button");
            #If product name in title
            if ($example['expected_title']){
                $this->assertElementContainsText("#page-title", $example['expected_title']);
                #Check customer page
                $this->checkCustomerPage($productName, $productPrice, $example);
            }
            else{
                $this->assertElementNotPresent("#page-title");
            }
        }
    }
}