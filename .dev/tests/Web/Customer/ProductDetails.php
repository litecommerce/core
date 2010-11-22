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
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once __DIR__ . '/ACustomer.php';

class XLite_Web_Customer_ProductDetails extends XLite_Web_Customer_ACustomer
{
    public function testStructure()
    {
        $product = $this->getActiveProduct();

        $productId = $product->getProductId();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent(
            "//h1[@id='page-title' and text()='" . $product->getName() . "']",
            'check name'
        );

        // Image block
        if ($product->hasImage()) {

            $image    = $product->getImages()->get(0); // the default image
            $kZoom    = 1.3;
            $maxWidth = 330;

            $cloudZoom = $image->getWidth() > $kZoom * $maxWidth;

            if ($cloudZoom) {

                $listSelector = "css=div.product-details form.product-details.hproduct .image .product-photo-box"; 

                $this->assertElementPresent(
                    $listSelector,
                    'check product-photo-box'
                );

                $this->assertElementPresent(
                    "$listSelector .product-photo div#wrap a.cloud-zoom#pimage_".$productId." img.photo.product-thumbnail",
                    'check image'
                );

                $imageRel = $this->getJSExpression("$('a.cloud-zoom#pimage_$productId').attr('rel')");
                $this->assertEquals(
                    $imageRel,
                    "adjustX: 97, showTitle: false, tintOpacity: 0.5, tint: '#fff', lensOpacity: 0",
                    "check image rel attribute"
                );

                $this->assertElementPresent(
                    "$listSelector a.arrow.left-arrow img",
                    'check left arrow'
                );
                $this->assertElementPresent(
                    "$listSelector a.arrow.right-arrow img",
                    'check right arrow'
                );

            }
        }

        // Gallery
        if ($product->countImages() > 1) {
            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/a[@class='loupe']"
                . "/img",
                'check loupe'
            );
            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/div[@class='product-image-gallery']"
                . "/ul"
                . "/li"
                . "/a[@rel='gallery']"
                . "/img",
                'check gallery items'
            );
            $this->assertEquals(
                count($product->getImages()),
                $this->getJSExpression("$('div.product-details .image .product-image-gallery li a').length"),
                'check gallery length'
            );
            $this->assertEquals(
                'true',
                $this->getJSExpression("$('div.product-details .image .product-image-gallery li').eq(0).hasClass('selected')"),
                'check default selected item'
            );
        }

        // Main block
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='product-details-info']"
            . "/div[@class='price product-price' and text()='$ " . $product->getPrice() . "']",
            'check price'
        );
        $this->assertElementPresent(
            "css=form.product-details.hproduct .product-details-info .product-buttons input.quantity.field-requred.field-integer.field-positive.field-non-zero[type=text][value=1]",
            'check quantity input box'
        );

        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='product-details-info']"
            . "/div[@class='product-buttons']"
            . "/div[@class='buttons-row']"
            . "/button[@type='submit' and @class='bright add2cart']"
            . "/span[text()='Add to Bag']",
            'check Add to bag button'
        );

        $facebookSelector = "css=form.product-details.hproduct .product-details-info .facebook iframe";
        $this->assertElementPresent(
            $facebookSelector,
            "check Facebook widget"
        );

        $url = urlencode($this->getLocation());
        $facebookLink = "http://www.facebook.com/plugins/like.php?href=$url&layout=standard&show_faces=true&width=450&action=like&colorscheme=light&height=24";
        $iframeLink = $this->getJSExpression("$('.facebook iframe').attr('src')");
        $this->assertEquals(
            $iframeLink,
            $facebookLink,
            'check Facebook Like link'
        );


        // Tabs
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='tabs']"
            . "/ul[@class='tabs primary']"
            . "/li[@class='active']"
            . "/a[@class='active' and text()='Description']",
            'check first tabs'
        );
        $this->assertEquals(
            1,
            $this->getJSExpression("$('.product-details .tabs ul li a').length"),
            'check tabs length'
        );

        // Extra fields
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/ul[@class='extra-fields']"
            . "/li"
            . "/strong[text()='Weight:']",
            'check weight (label)'
        );
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/ul[@class='extra-fields']"
            . "/li"
            . "/span[text()='" . $product->getWeight(). " lbs']",
            'check weight'
        );

        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/ul[@class='extra-fields']"
            . "/li[@class='identifier product-sku']"
            . "/strong[@class='type' and text()='SKU:']",
            'check SKU (label)'
        );
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/ul[@class='extra-fields']"
            . "/li"
            . "/span[@class='value' and text()='" . $product->getSKU(). "']",
            'check SKU'
        );

        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='description product-description']",
            'check description'
        );
    }

    public function testZoomer()
    {
        $product = $this->getActiveProductZoomer();

        if (!$product) {
            $this->markTestSkipped();
        }

        $this->open('store/product//product_id-' . $product->getProductId());

        // Base use case
        $this->mouseOver("//div[@class='mousetrap']");

        $this->assertJqueryPresent('#cloud-zoom-big:visible', 'check zoomer layout');
        $this->assertJqueryPresent('.cloud-zoom-lens:visible', 'check zoomer lens');

        $this->mouseOut("//div[@class='mousetrap']");

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$("#cloud-zoom-big:visible").length == 0',
            2000,
            'check zoomer gone'
        );

        $this->assertJqueryNotPresent('#cloud-zoom-big:visible', 'check zoomer layout #2');
        $this->assertJqueryNotPresent('#cloud-zoom-lens:visible', 'check zoomer lens #2');

        // Gallery based use cases
        $length = intval($this->getJSExpression("$('ul.gallery li a').length"));

        for ($idx = 2; $idx < $length + 1; $idx++) {
            $i = $idx - 1;

            $this->click("//ul[@class='gallery']/li[position()=$idx]/a");

            $src = $this->getJSExpression("$('ul.gallery li:eq($i) img.middle').attr('src')");
            $w = $this->getJSExpression("$('ul.gallery li:eq($i) img.middle').attr('width')");
            $h = $this->getJSExpression("$('ul.gallery li:eq($i) img.middle').attr('height')");

            $this->waitForCondition(
                'selenium.browserbot.getCurrentWindow().$(".product-details .image .cloud-zoom img").attr("src") == "' . $src . '"',
                2000,
                'check zoomer image change [' . $idx. ' image]'
            );

            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/div[@class='product-photo-box']"
                . "/div[@class='product-photo' and @style='width: " . $w . "px;']",
                'check new image center block [' . $idx. ' image]'
            );

            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/div[@class='product-photo-box']"
                . "/div[@class='product-photo']"
                . "/div[@id='wrap']"
                . "/a[@class='cloud-zoom']"
                . "/img[@src='" . $src . "' and @width='" . $w . "' and @height='" . $h . "']",
                'check new image src [' . $idx. ' image]'
            );

            $rev = $this->getJSExpression("$('ul.gallery li:eq($i) a').attr('rev')");

            if (!preg_match('/width: (\d+), height: (\d+)/', $rev, $m)) {
                $this->fail('link rev attribute has wrong format [' . $idx. ' image]');
            }

            if ($m[1] > $w || $m[2] > $h) {

                $this->mouseOver("//div[@class='mousetrap']");

                $this->assertJqueryPresent('#cloud-zoom-big:visible', 'check zoomer layout #3 [' . $idx. ' image]');
                $this->assertJqueryPresent('.cloud-zoom-lens:visible', 'check zoomer lens #3 [' . $idx. ' image]');

            } else {

                $this->assertElementNotPresent("//div[@class='mousetrap']", 'check zommer disable [' . $idx. ' image]');

            }
        }
    }

    public function testColorBox()
    {
        $product = $this->getActiveProductGallery();

        if (!$product) {
            $this->markTestSkipped();
        }

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->getJSExpression('$("a.loupe").trigger("click")');

        $src = $this->getJSExpression("$('.product-image-gallery ul li:eq(0) a').attr('href')");

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$("#cboxPhoto").attr("src") == "' . $src . '"',
            6000,
            'check colorbox start'
        );

        // Check first image and structure

        $this->assertJqueryPresent('#colorbox:visible', 'check visible colorbox');
        $this->assertJqueryPresent('#cboxPhoto:visible', 'check visible photo');

        $this->assertElementPresent(
            "//body"
            . "/div[@id='colorbox']"
            . "/div[@id='cboxWrapper']"
            . "/div"
            . "/div[@id='cboxContent']"
            . "/div[@id='cboxLoadedContent']"
            . "/img[@id='cboxPhoto' and @src='" . $src . "']",
            'check first image'
        );

        $this->assertElementPresent(
            "//body"
            . "/div[@id='colorbox']"
            . "/div[@id='cboxWrapper']"
            . "/div"
            . "/div[@id='cboxContent']"
            . "/div[@id='cboxPrevious' and text()='previous']",
            'check left arrow'
        );

        $this->assertElementPresent(
            "//body"
            . "/div[@id='colorbox']"
            . "/div[@id='cboxWrapper']"
            . "/div"
            . "/div[@id='cboxContent']"
            . "/div[@id='cboxNext' and text()='next']",
            'check right arrow'
        );

        $this->assertElementPresent(
            "//body"
            . "/div[@id='colorbox']"
            . "/div[@id='cboxWrapper']"
            . "/div"
            . "/div[@id='cboxContent']"
            . "/div[@id='cboxClose' and text()='close']",
            'check close control'
        );

        // Check 'next' navigation
        $length = intval($this->getJSExpression("$('.product-image-gallery ul li a').length"));

        for ($i = 1; $i < $length; $i++) {

            $this->click(
                "//body"
                . "/div[@id='colorbox']"
                . "/div[@id='cboxWrapper']"
                . "/div"
                . "/div[@id='cboxContent']"
                . "/div[@id='cboxNext' and text()='next']"
            );

            $src = $this->getJSExpression("$('.product-image-gallery ul li:eq($i) a').attr('href')");

            $this->assertElementPresent(
                "//body"
                . "/div[@id='colorbox']"
                . "/div[@id='cboxWrapper']"
                . "/div"
                . "/div[@id='cboxContent']"
                . "/div[@id='cboxLoadedContent']"
                . "/img[@id='cboxPhoto' and @src='" . $src . "']",
                'check image src [' . $i . ' iteration]'
            );
        }

        // Check close
        $this->click(
            "//body"
            . "/div[@id='colorbox']"
            . "/div[@id='cboxWrapper']"
            . "/div"
            . "/div[@id='cboxContent']"
            . "/div[@id='cboxClose' and text()='close']"
        );

        $this->assertElementPresent(
            "//body"
            . "/div[@id='colorbox']",
            'check hidden colorbox'
        );
        $this->assertJqueryNotPresent('#colorbox:visible', 'check closed colorbox');
    }

    public function testAdd2Cart()
    {
        $product = $this->getActiveProduct();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $qty = 0;

        $formSelector = "css=form.product-details.hproduct";
        $cartButtonSelector = "$formSelector .product-details-info .product-buttons button.bright.add2cart";
        $buyButtonSelector = "$formSelector .product-details-info .product-buttons-added button.action.buy-more";
        $continueButtonSelector = "$formSelector .product-details-info .product-buttons-added button.bright.continue";
    
        $this->assertElementPresent(
            $cartButtonSelector,
            "check add2cart button"
        );
        $this->click($cartButtonSelector);
        
        $qty++;

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$(".product-details .product-buttons-added .buy-more").length > 0',
            10000,
            'check content reloading'
        );
       $this->assertElementPresent(
            $continueButtonSelector,
            'check Continue shopping button'
        );

        // This assertion requires the minicart widget to be visible on the page
        $q = intval($this->getJSExpression("$('.minicart-items-number').html()"));
        $this->assertEquals($qty, $q, 'check quantity');

        $this->assertElementPresent(
            $buyButtonSelector,
            'check Buy now button'
        );
        $this->click($buyButtonSelector);

        $qty++;

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$(".minicart-items-number").html() == ' . $qty,
            10000,
            'check content reloading #2'
        );
 
        /* TODO - rework after Inventory tracking module is changed

        $this->getJSExpression("$('.product-details input.quantity').attr('value', 3)");

        $this->click(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='buttons-row added']"
            . "/button[@class='action buy-more']"
        );

        $qty += 3;

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$(".product-details input.quantity").attr("value") == 1',
            10000,
            'check content reloading #3'
        );

        $q = intval($this->getJSExpression("$('.minicart-items-number').html()"));

        $this->assertEquals($qty, $q, 'check quantity #3');
        */
    }

    protected function getActiveProduct()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->createQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    protected function getActiveProductZoomer()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->createQueryBuilder()
            ->innerJoin('p.images', 'i')
            ->andWhere('i.width > :width')
            ->setParameter('width', 1.3 * 330)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    protected function getActiveProductGallery()
    {
        $res = \XLite\Core\Database::getRepo('XLite\Model\Image\Product\Image')
            ->createQueryBuilder()
            ->select(array('COUNT(i.image_id)', 'i.id'))
            ->groupBy('i.id')
            ->getQuery()
            ->getScalarResult();

        $product = null;

        foreach ($res as $v) {
            $count = array_shift($v);

            if ($count > 1) {
                $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(array_shift($v));
                break;
            }

        }

        return $product;
    }


}
