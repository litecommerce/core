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

class XLite_Web_Customer_QuickLook extends XLite_Web_Customer_ACustomer
{

    public function testStructure()
    {
        $list = $this->getListSelector();

        $this->open('toys');

        // Make sure there are no QuickLook buttons in the table mode
        $this->switchDisplayMode('table');
        $this->assertElementNotPresent(
            "$list a.quicklook-link",
            "Quicklook buttons are shown in the table display mode"
        );

        $l = array();

        // Test in different display modes
        foreach (array('grid', 'list') as $mode) {

            $this->switchDisplayMode($mode);

            $productsCount = $this->getJSExpression("$('$list .product').size()");
            $buttonsCount = $this->getJSExpression("$('$list .product a.quicklook-link').size()");
            $this->assertEquals($buttonsCount, $productsCount, "Wrong number of Quicklook buttons");
 
            // Test several products
            $products = array(
                4047, // Zoom, No gallery, no options
                4024, // Zoom, Gallery, no options
                4035, // Zoom, Galler, Options
            );

            foreach($products as $id) {
                $this->popupQuicklook($id);
                $link = $this->testProductStructure($id);
                $this->closeQuicklook();

                if (!isset($l[$id])) {
                    $l[$id] = $link;
                } else {
                    // Make sure a product link is the same for all modes
                    $this->assertEquals($link, $l[$id], "Product links depend on the display mode ($id)");
                }
            }
        }

              
    }


    /**
     * Test whether a Quicklook popup for a product has a correct structure. Returns link to a product page.
     * 
     * @param integer $id Product ID
     *  
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function testProductStructure($id)
    {

        $selector = ".BlockMsg-product-quicklook .product-quicklook";
        $this->assertElementPresent("css=$selector", "Quicklook popup for the $id product is missing on the page");

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($id);
        $this->assertNotNull($product, "Product $id is not found in the DB");

        // Name
        $name = $this->getJSExpression("$('$selector h1.fn.title').html()");
        $this->assertEquals($name, $product->getName(), "Wrong product name ($id)");
        
        // Price
        $price = $this->getJSExpression("$('$selector .product-price').html()");
        $parsedPrice = preg_replace("/^\D*(\d+\.\d+)\D*$/", "\\1", $price);
        $this->assertEquals($parsedPrice, $product->getPrice(), "Wrong price ($id)");

        // TODO: In-stock quantity
        // $qty = $this->getJSExpression("$('$selector .product-stock-level span').html()");
        // $parsedQty = preg_replace("/^\((\D+) .*/", "\\1", $qty);

        // Add-to-cart form
        $this->assertElementPresent("css=$selector .product-qty input.quantity", "Quantity field is missing (product $id)");
        $this->assertElementPresent("css=$selector button.add2cart", "Add to cart button is missing (product $id)");

        // Product page link (is returned by the method)
        $link = $this->getJSExpression("$('$selector a.product-more-link')");

        // Gallery
        if ($product->countImages() > 1) {
            $this->assertElementNotPresent("css=$selector .loupe", "Zoom icon is shown in the Quicklook popup (product $id)");
            $this->assertElementPresent("css=$selector .product-image-gallery ul li a[rel='gallery'] img", "Image links are missing in the image gallery (product $id)");
            $this->assertEquals(
                count($product->getImages()),
                $this->getJSExpression("$('$selector .product-image-gallery li a').length"),
                "Image gallery displays a wrong number of images (product $id)"
            );
            $this->assertEquals(
                'true',
                $this->getJSExpression("$('$selector .product-image-gallery li').eq(0).hasClass('selected')"),
                "The first image in the gallery is not selected (product $id)"
            );
        }

        // Image zoom

        if ($product->hasImage()) {

            $image = $product->getImages()->get(0); // the default image
            $kZoom = \XLite\View\Product\Details\Customer\Image::K_ZOOM;
            $maxWidth = \XLite\View\Product\Details\Customer\Image::IMG_MAX_WIDTH_QL;

            $cloudZoom = $image->getWidth() > $kZoom * $maxWidth;

            if ($cloudZoom) {

                $this->assertElementPresent(
                    "css=$selector .product-photo div#wrap a.cloud-zoom#pimage_".$id." img.photo.product-thumbnail",
                    "Cloud Zoom image is missing (product $id)"
                );

                $imageRel = $this->getJSExpression("$('$selector a.cloud-zoom#pimage_$id').attr('rel')");
                $this->assertEquals(
                    $imageRel,
                    "adjustX: 32, showTitle: false, tintOpacity: 0.5, tint: '#fff', lensOpacity: 0",
                    "Wrong 'rel' attribute (product $id)"
                );

                $this->assertElementNotPresent("css=$selector a.arrow", "Image switching arrows are shown in the Quicklook popup (product $id)");

            }

        }

        return $link;

    }

    /**
     * Closes a Quicklook popup
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function closeQuicklook()
    {
        $this->assertElementPresent(
            $selector = "css=.BlockMsg-product-quicklook a.close-link",
            "Close button is missing"
        );
        $this->click($selector);
        $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('.BlockMsg-product-quicklook:visible:visible').length <= 0", 300000);

        $this->assertJqueryNotPresent(
            ".BlockMsg-product-quicklook:visible",
            "Close button doesn't close the Quicklook popup"
        );
    }

    /**
     * Finds a product in the list of category products and displays a Quicklook popup for the product
     * 
     * @param integer $productId Product ID
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function popupQuicklook($productId)
    {
        $this->assertJqueryNotPresent(
            ".BlockMsg-product-quicklook:visible",
            "Quicklook popup is visible without clicking a Quicklook button ($productId)"
        );

        $selector = $this->getListSelector() . " .productid-$productId a.quicklook-link-$productId";
        
        $this->click("css=$selector");
        $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('.BlockMsg-product-quicklook:visible').length > 0", 300000);

    }

    /**
     * Switched the list of category products to a display mode
     * 
     * @param string $mode Mode (grid, list, table)
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function switchDisplayMode($mode)
    {
        $this->assertElementPresent(
            $selector = "css=".$this->getModeSelector($mode),
            "A selector for '$mode' display mode is missing!"
        );
        $this->click($selector);
        $this->waitForAjaxProgress();
    }

    /**
     * Waits until the AJAx progress bar disappears
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function waitForAjaxProgress()
    {
        $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('.blockUI.block-wait:visible').length <= 0", 300000);
    }
 
    /**
     * Returns a selector for a mode-switching link
     * 
     * @param string $mode Mode name (list, grid, table)
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModeSelector($mode)
    {
        return $this->getListSelector()." ul.display-modes li.list-type-$mode a";
    }

    protected function getListSelector()
    {
        return ".category-products";
    }    


    /**
     * Open a test category page
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function openTestCategory()
    {
        $category = $this->getTestCategory();
        $url = $this->getCategoryURL($category->getCategoryId());
        $this->open($url);

        $title = $this->getJSExpression("$('h1#page-title.title').attr('title')");
        $this->assertEquals(
            $category->getName(),
            $title,
            "Can't open a test category page"
        );

    }


    /**
     * Returns URL to a category page
     * 
     * @param integer $id Category ID
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategoryURL($id)
    {
        return "store/category//category_id-$id";
    }


    /**
     * Returns ID of a category having more products than other categories
     * 
     * @return \XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTestCategory()
    {
        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->findByEnabled(true);

        $category = null;
        $max = 0;

        foreach ($categories as $one) {
            $count = $one->getProductsNumber();
            if ($count && ($count > $max) && ($count < 10)) {
                $category = $one;
                $max = $count;
            }
        }

        $this->assertNotNull($category, 'getTestCategory() returned null');

        return $category;

    }




}
