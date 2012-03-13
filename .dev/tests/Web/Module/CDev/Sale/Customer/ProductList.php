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

class XLite_Web_Module_CDev_Sale_Customer_ProductList extends XLite_Web_Customer_ACustomer
{
    const PERCENT = \XLite\Model\Product::SALE_DISCOUNT_TYPE_PERCENT;
    const PRICE = \XLite\Model\Product::SALE_DISCOUNT_TYPE_PRICE;
    /**
     * @var XLite\Model\Product[]
     */
    protected static $products = array();

    function testSaleList(){
        #Sale 50%
        $products = $this->getSaleProducts(50, self::PERCENT);

        $this->open('store/sale_products/');
        //$modes = array('grid','table','list');

        foreach($products as $product){
            $listPrice = $this->formatPrice($product->getListPrice());
            $this->checkProductOnList($product, array('grid','table','list'), 50, self::PERCENT);
            $this->click('css=a.grid');
            //$this->waitForAjaxProgress();
            #quicklook
            $this->click("css=.content .items-list .product.productid-".$product->getProductId() . " .quicklook-view");
            $this->waitForLocalCondition("jQuery('.BlockMsg-product-quicklook .product-quicklook:visible').length > 0", 30000);
            $this->assertElementContainsText("css=.BlockMsg-product-quicklook .sale-banner-block .percent", "50% off", "Sale block shows wrong percent in quicklook, 50%");
            $this->assertElementContainsText('css=.BlockMsg-product-quicklook .product-price', $listPrice, "Wrong price in quicklook, 50%");
            $this->click('css=a.close-link');

        }
    }

    protected function checkProductOnList(\XLite\Model\Product $product, $modes, $sale, $type){
        $listPrice = $this->formatPrice($product->getListPrice());
        foreach($modes as $mode){
            $this->click('css=a.'.$mode);
            //$this->waitForAjaxProgress();
            sleep(1);
            $this->assertElementPresent("css=.content .items-list .product.productid-".$product->getProductId(), "Sale product missing, $mode mode, $sale $type");
            $this->assertElementPresent("css=.content .items-list .productid-" .$product->getProductId() . " .label-orange.sale-price", "Sale label missing, $mode mode, $sale $type");
            $this->assertNotEquals($product->getPrice(), $product->getListPrice(), "Price and list price equals, $mode mode, $sale $type");
            $this->assertElementContainsText("css=.content .items-list .product.productid-".$product->getProductId() . " .product-price", $listPrice, "Price shown without sale, $mode mode, $sale $type");
        }
    }


    function testProductList(){
        #Sale = 1$
        $products = $this->getSaleProducts(2, self::PRICE);
        foreach($products as $product){
            $categoryId = $product->getCategoryId();
            if ($categoryId){
                $this->open('store/category//category_id-'.$categoryId);
                $this->checkProductOnList($product, array('grid','table','list'), 2, self::PRICE);
            }
        }
    }
    function testSaleBlock(){
        $products = $this->getSaleProducts();
        $this->open('');
        $productCount = count($products);
        if ($productCount > 3){
            $productCount =  3;
        }

        $this->assertElementPresent('css=.items-list.sale-products', 'No sale block');
        for($i = 0; $i < $productCount; $i++ ){
            $product = $products[$i];
            $listPrice = $this->formatPrice($product->getListPrice());
            $this->assertElementPresent('css=.items-list.sale-products .product.productid-'. $product->getProductId(), 'Product is not in block: ' . $product->getName() . ' ' . $product->getProductId());
            $this->assertElementContainsText('css=.items-list.sale-products .product.productid-'. $product->getProductId().' .label-orange.sale-price', '50% off', 'Invalid percent value');
            $this->assertElementContainsText('css=.items-list.sale-products .product.productid-'. $product->getProductId().' .product-price', $listPrice, 'Invalid percent value');
        }
    }

    /**
     * @param int $sale
     * @param $type
     * @return XLite\Model\Product[]
     */
    protected function getSaleProducts($sale = 50, $type = self::PERCENT)
    {
        #First 5 products with price > 10 and < 500 sorted by name asc
        //if (empty(self::$products)){
            self::$products = \XLite\Core\Database::getRepo('XLite\Model\Product')->createQueryBuilder('p')->andWhere('p.price > 10')->andWhere('p.price < 500')->setMaxResults(3)->getResult();
        //}

        $this->assertNotEmpty(self::$products, 'No products > 10$ and < 500$ found');

        foreach(self::$products as $product){
            $this->saleProduct($product, $sale, $type);
        }
        XLite\Core\Database::getEM()->flush();
        return self::$products;
    }
    protected function saleProduct(XLite\Model\Product $product, $sale, $discountType){
        $product->setParticipateSale(true);
        $product->setSalePriceValue($sale);
        $product->setDiscountType($discountType);
        XLite\Core\Database::getEM()->persist($product);

    }
    public  function getListSelector(){
        return '.items-list';
    }
}