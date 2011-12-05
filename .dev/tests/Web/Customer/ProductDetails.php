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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 *
 * @use product
 */

require_once __DIR__ . '/ACustomer.php';

/**
 * XLite_Web_Customer_ProductDetails
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class XLite_Web_Customer_ProductDetails extends XLite_Web_Customer_ACustomer
{
    /**
     * testStructure
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testStructure()
    {
        $product   = $this->getActiveProduct();
        $productId = $product->getProductId();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent(
            "//h1[@class='fn title' and text()='" . $product->getName() . "']",
            'check name'
        );

        // Image block
        if ($product->hasImage()) {

            $image    = $product->getImages()->get(0); // the default image
            $kZoom    = 1.3;
            $maxWidth = 330;

            $cloudZoom = $image->getWidth() > $kZoom * $maxWidth;

            if ($cloudZoom) {

                $listSelector = "css=div.product-details form.product-details .image .product-photo-box";

                $this->assertElementPresent(
                    $listSelector,
                    'check product-photo-box'
                );

                $this->assertElementPresent(
                    $listSelector . " .product-photo div#wrap a.cloud-zoom#pimage_" . $productId . " img.photo.product-thumbnail",
                    'check image'
                );

                $imageRel = $this->getJSExpression('jQuery("a.cloud-zoom#pimage_' . $productId . '").attr("rel")');
                $this->assertEquals(
                    $imageRel,
                    "adjustX: 97, showTitle: false, tintOpacity: 0.5, tint: '#fff', lensOpacity: 0",
                    "check image rel attribute"
                );

                if (1 < count($product->getImages())) {
                    $this->assertElementPresent(
                        $listSelector . " a.arrow.left-arrow img",
                        'check left arrow'
                    );
                    $this->assertElementPresent(
                        $listSelector . " a.arrow.right-arrow img",
                        'check right arrow'
                    );
                }

            }
        }

        // Gallery
        if ($product->countImages() > 1) {
            $this->assertElementPresent('css=form.product-details .image a.loupe img', 'check loupe');
            $this->assertElementPresent('css=form.product-details .image .product-image-gallery ul li a img', 'check gallery items');
            $this->assertEquals(
                count($product->getImages()),
                $this->getJSExpression('jQuery("div.product-details .image .product-image-gallery li a").length'),
                'check gallery length'
            );
            $this->assertEquals(
                'true',
                $this->getJSExpression('jQuery("div.product-details .image .product-image-gallery li").eq(0).hasClass("selected")'),
                'check default selected item'
            );
        }

        // Main block
        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-info']"
            . "/span[@class='price product-price' and text()='" . $this->formatPrice($product->getPrice()) . "']",
            'check price'
        );
        $this->assertElementPresent(
            "css=form.product-details .product-details-info .product-buttons input.quantity.wheel-ctrl[type=text][value=1]",
            'check quantity input box'
        );

        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-info']"
            . "/div[@class='product-buttons shade-base']"
            . "/div[@class='buttons-row']"
            . "/button[@type='submit' and @class='bright add2cart']"
            . "/span[text()='Add to Bag']",
            'check Add to bag button'
        );

        $facebookSelector = "css=form.product-details .product-details-info .facebook script";
        $this->assertElementPresent(
            $facebookSelector,
            "check Facebook widget"
        );

        // Tabs
        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-tabs']"
            . "/div[@class='tabs']"
            . "/ul[@class='tabs primary']"
            . "/li[@class='active']"
            . "/a[contains(text(), 'Description')]",
            'check for "Description" tab'
        );

        $this->assertEquals(
            1,
            $this->getJSExpression('jQuery(".product-details .tabs ul li a").length'),
            'check tabs length'
        );

        // Extra fields
        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-tabs']"
            . "/div[@class='tab-container']"
            . "/ul[@class='extra-fields']"
            . "/li"
            . "/strong[text()='Weight:']",
            'check weight (label)'
        );

        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-tabs']"
            . "/div[@class='tab-container']"
            . "/ul[@class='extra-fields']"
            . "/li"
            . "/span[text()='" . $product->getWeight(). " lbs']",
            'check weight'
        );

        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-tabs']"
            . "/div[@class='tab-container']"
            . "/ul[@class='extra-fields']"
            . "/li[@class='identifier product-sku']"
            . "/strong[@class='type' and text()='SKU:']",
            'check SKU (label)'
        );
        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-tabs']"
            . "/div[@class='tab-container']"
            . "/ul[@class='extra-fields']"
            . "/li"
            . "/span[@class='value' and text()='" . $product->getSKU(). "']",
            'check SKU'
        );

        $this->assertElementPresent(
            "//form[@class='product-details validationEngine']"
            . "/div[@class='product-details-tabs']"
            . "/div[@class='tab-container']"
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

        $this->waitForLocalCondition(
            'jQuery("#cloud-zoom-big:visible").length == 0',
            2000,
            'check zoomer gone'
        );

        $this->assertJqueryNotPresent('#cloud-zoom-big:visible', 'check zoomer layout #2');
        $this->assertJqueryNotPresent('#cloud-zoom-lens:visible', 'check zoomer lens #2');

        // Gallery based use cases
        $length = intval($this->getJSExpression('jQuery("ul.gallery li a").length'));

        for ($idx = 2; $idx < $length + 1; $idx++) {
            $i = $idx - 1;

            $this->click("//ul[@class='gallery']/li[position()=$idx]/a");

            $src = $this->getJSExpression('jQuery("ul.gallery li:eq(' . $i . ') img.middle").attr("src")');
            $w = $this->getJSExpression('jQuery("ul.gallery li:eq(' . $i . ') img.middle").attr("width")');
            $h = $this->getJSExpression('jQuery("ul.gallery li:eq(' . $i . ') img.middle").attr("height")');

            $this->waitForLocalCondition(
                'jQuery(".product-details .image .cloud-zoom img").attr("src") == "' . $src . '"',
                2000,
                'check zoomer image change [' . $idx. ' image]'
            );

            $this->assertElementPresent(
                "//form[@class='product-details']"
                . "/div[@class='image']"
                . "/div[@class='product-photo-box']"
                . "/div[@class='product-photo' and @style='width: " . $w . "px;']",
                'check new image center block [' . $idx. ' image]'
            );

            $this->assertElementPresent(
                "//form[@class='product-details']"
                . "/div[@class='image']"
                . "/div[@class='product-photo-box']"
                . "/div[@class='product-photo']"
                . "/div[@id='wrap']"
                . "/a[@class='cloud-zoom']"
                . "/img[@src='" . $src . "' and @width='" . $w . "' and @height='" . $h . "']",
                'check new image src [' . $idx. ' image]'
            );

            $rev = $this->getJSExpression('jQuery("ul.gallery li:eq(' . $i . ') a").attr("rev")');

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

        $this->getJSExpression('jQuery("a.loupe").trigger("click")');

        $src = $this->getJSExpression('jQuery(".product-image-gallery ul li:eq(0) a").attr("href")');

        $this->waitForLocalCondition(
            'jQuery("#cboxPhoto").attr("src") == "' . $src . '"',
            6000,
            'check colorbox start'
        );

        // Check first image and structure
        $this->assertJqueryPresent('#colorbox:visible', 'check visible colorbox');
        $this->waitForLocalCondition(
            'jQuery("#cboxPhoto:visible").length > 0',
            6000,
            'check visible photo'
        );

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
        $length = intval($this->getJSExpression('jQuery(".product-image-gallery ul li a").length'));

        $sleep = $this->setSleep(3);

        for ($i = 1; $i < $length; $i++) {

            $this->click(
                "//body"
                . "/div[@id='colorbox']"
                . "/div[@id='cboxWrapper']"
                . "/div"
                . "/div[@id='cboxContent']"
                . "/div[@id='cboxNext' and text()='next']"
            );

            $src = $this->getJSExpression('jQuery(".product-image-gallery ul li:eq(' . $i . ') a").attr("href")');

            $this->assertElementPresent(
                "//body"
                . "/div[@id='colorbox']"
                . "/div[@id='cboxWrapper']"
                . "/div"
                . "/div[@id='cboxContent']"
                . "/div[@id='cboxLoadedContent']"
                . "/img[@id='cboxPhoto' and @src='" . $src . "']",
                'check image src [' . $i . ' iteration] ' . $src
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

        $this->setSleep($sleep);
    }

    public function testAdd2Cart()
    {
        $product = $this->getActiveProduct();
        $product->getInventory()->setAmount(100);
        \XLite\Core\Database::getEM()->flush();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $qty = 0;

        $formSelector = "css=form.product-details.validationEngine";
        $cartButtonSelector = $formSelector . " .product-details-info .product-buttons button.bright.add2cart";
        $buyButtonSelector = $formSelector . " .product-details-info .product-buttons-added button.action.buy-more";
        $continueButtonSelector = $formSelector . " .product-details-info .product-buttons-added button.bright.continue";
        $quantitySelector = $formSelector . " .product-details-info .product-buttons input.quantity.wheel-ctrl[type=text]";

        $this->assertElementPresent(
            $cartButtonSelector,
            "check add2cart button"
        );

        $this->click($cartButtonSelector);

        $qty++;

        $this->waitForLocalCondition(
            'jQuery(".product-details .product-buttons-added .buy-more").length > 0',
            100000,
            'check content reloading'
        );
        $this->assertElementPresent(
            $continueButtonSelector,
            'check Continue shopping button'
        );

        // This assertion requires the minicart widget to be visible on the page
        $this->waitForLocalCondition(
            'jQuery(".minicart-items-number").html() == "1"',
            100000,
            'check quantity'
        );

        $this->assertElementPresent(
            $buyButtonSelector,
            'check Buy now button'
        );
        $this->click($buyButtonSelector);

        $qty++;

        $this->waitForLocalCondition(
            'jQuery(".minicart-items-number").html() == "2"',
            100000,
            'check content reloading #2'
        );

        // Check quantity and inventory
        $this->getJSExpression('jQuery(".product-details input.quantity").attr("value", 3)');

        $this->click($buyButtonSelector);

        $qty += 3;

        $this->waitForLocalCondition(
            'jQuery(".minicart-items-number").html() == "5"',
            300000,
            'check quantity'
        );

        $sleep = $this->setSleep(2);

        // Reload page (selenium does not process below steps properly w/o reload)
        // FIXME: try to avoid reloading page here
        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        // Check unallowed values (inventory tracking)
        $qtyBlurOperation = 'jQuery(".product-details input.quantity").blur()';
        $errorDivSelector = 'div.amount' . $product->getProductId() . 'formError:visible';
        $errorQtySelector = 'input.quantity.wrong-amount';

        $this->typeKeys($quantitySelector, '-3');
        $this->getJSExpression($qtyBlurOperation);
        $this->assertJqueryPresent($errorDivSelector, 'check minimal allowed quantity error');
        $this->assertJqueryPresent($errorQtySelector, 'check minimal allowed quantity');
        $this->assertJqueryPresent('button.action.buy-more.disabled.add2cart-disabled', 'check disabled buy now button (min qty)');

        $this->typeKeys($quantitySelector, '45');
        $this->getJSExpression($qtyBlurOperation);
        $this->assertJqueryNotPresent($errorDivSelector, 'check normalized quantity error');
        $this->assertJqueryNotPresent($errorQtySelector, 'check normalized quantity');
        $this->assertJqueryNotPresent('button.action.buy-more.disabled.add2cart-disabled', 'check enabled buy now button');

        $this->typeKeys($quantitySelector, '146');
        $this->getJSExpression($qtyBlurOperation);
        $this->assertJqueryPresent($errorDivSelector, 'check maximum allowed quantity error');
        $this->assertJqueryPresent($errorQtySelector, 'check maximum allowed quantity');
        $this->assertJqueryPresent('button.action.buy-more.disabled.add2cart-disabled', 'check disabled buy now button (max qty)');

        $this->setSleep($sleep);
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
            ->select(array('COUNT(i.id)', 'product.product_id'))
            ->innerJoin('i.product', 'product')
            ->groupBy('i.product')
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
