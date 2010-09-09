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

        $this->open('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent(
            "//h1[@id='page-title' and text()='" . $product->getName() . "']",
            'check name'
        );

        // Image block
        if ($product->hasZoomImage()) {
            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/div[@class='image-box']"
                . "/div[@class='image-center']"
                . "/div[@id='wrap']"
                . '/a[@id="pimage_' . $product->getProductId() . '" and @class="cloud-zoom" and @rel="adjustX: 97, showTitle: false, tintOpacity: 0.5, tint: \'#fff\', lensOpacity: 0"]'
                . "/img[@class='photo product-thumbnail']",
                'check image'
            );
            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/div[@class='image-box']"
                . "/a[@class='arrow left-arrow']"
                . "/img",
                'check left arrow'
            );
            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/div[@class='image-box']"
                . "/a[@class='arrow right-arrow']"
                . "/img",
                'check right arrow'
            );
        }

        // Gallery
        if ($product->getActiveDetailedImages()) {
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
                . "/div[@class='gallery-container']"
                . "/ul[@class='gallery']"
                . "/li"
                . "/a[@rel='gallery']"
                . "/img",
                'check gallery items'
            );
            $this->assertEquals(
                count($product->getActiveDetailedImages()),
                $this->getJSExpression("$('.product-details .image .gallery li a').length"),
                'check gallery length'
            );
            $this->assertEquals(
                'true',
                $this->getJSExpression("$('.product-details .image .gallery li').eq(0).hasClass('selected')"),
                'check default selected item'
            );
        }

        // Main block
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='price product-price' and text()='$ " . $product->getPrice() . "']",
            'check price'
        );
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='buttons-row']"
            . "/input[@type='text' and @class='quantity field-requred field-integer field-positive field-non-zero' and @value='1']",
            'check quantity input box'
        );
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='buttons-row']"
            . "/button[@type='submit' and @class='bright add2cart']"
            . "/span[text()='Add to Bag']",
            'check Add to bag button'
        );
        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='facebook']"
            . "/iframe[@src='http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fxcart2-530.crtdev.local%2F%7Emax%2Fxlite_cms%2Fsrc%2Fstore%2Fproduct%2F%2Fproduct_id-" . $product->getProductId() . "&layout=standard&show_faces=true&width=450&action=like&colorscheme=light&height=80']",
            'check Facebook Like button'
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
            4,
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
        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());

        // Base use case
        $this->mouseOver("//div[@class='mousetrap']");

        $this->assertJqueryPresent('#cloud-zoom-big:visible', 'check zoomer layout');
        $this->assertJqueryPresent('.cloud-zoom-lens:visible', 'check zoomer lens');

        $this->assertEquals(
            $this->getJSExpression("$('.product-details .body').offset().left") + 2,
            $this->getJSExpression("$('.cloud-zoom-big').offset().left"),
            'check zoomer layout offset'
        );

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
                . "/div[@class='image-box']"
                . "/div[@class='image-center' and @style='width: " . $w . "px;']",
                'check new image center block [' . $idx. ' image]'
            );

            $this->assertElementPresent(
                "//form[@class='product-details hproduct']"
                . "/div[@class='image']"
                . "/div[@class='image-box']"
                . "/div[@class='image-center']"
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
        $product = $this->getActiveProduct();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->getJSExpression('$("a.loupe").trigger("click")');

        $src = $this->getJSExpression("$('ul.gallery li:eq(0) a').attr('href')");

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$("#cboxPhoto").attr("src") == "' . $src . '"',
            2000,
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
        $length = intval($this->getJSExpression("$('ul.gallery li a').length"));

        for ($i = 1; $i < $length; $i++) {

            $this->click(
                "//body"
                . "/div[@id='colorbox']"
                . "/div[@id='cboxWrapper']"
                . "/div"
                . "/div[@id='cboxContent']"
                . "/div[@id='cboxNext' and text()='next']"
            );

            $src = $this->getJSExpression("$('ul.gallery li:eq($i) a').attr('href')");

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

        $this->click(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='buttons-row']"
            . "/button[@class='bright add2cart']"
        );

        $qty++;

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$(".product-details .added .buy-more").length > 0',
            10000,
            'check content reloading'
        );

        $this->assertElementPresent(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='added-block']"
            . "/button[@class='bright continue']",
            'check Continue shopping button'
        );

        $q = intval($this->getJSExpression("$('.minicart-items-number').html()"));

        $this->assertEquals($qty, $q, 'check quantity');

        $this->click(
            "//form[@class='product-details hproduct']"
            . "/div[@class='body']"
            . "/div[@class='buttons-row added']"
            . "/button[@class='action buy-more']"
        );

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
            ->innerJoin('p.detailed_images', 'd')
            ->andWhere('d.is_zoom = :true')
            ->setParameter('true', true)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
