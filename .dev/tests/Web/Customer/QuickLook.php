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

    /**
     * Test the basic structure
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testStructure()
    {
        $list = $this->getListSelector();

        $c2 = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanUrl' => 'toys'));
        $this->open('store/category/0/category_id-' . $c2->getCategoryId());

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

            $productsCount = $this->getJSExpression("jQuery('$list .product').size()");
            $buttonsCount = $this->getJSExpression("jQuery('$list .product a.quicklook-link').size()");
            $this->assertEquals($buttonsCount, $productsCount, "Wrong number of Quicklook buttons");
 
            // Test several products
            $products = array(
                $this->getProductBySku('00045')->getProductId(), // Zoom, No gallery, no options
                $this->getProductBySku('00022')->getProductId(), // Zoom, Gallery, no options
                $this->getProductBySku('00033')->getProductId(), // Zoom, Galler, Options
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
     * Test how the Quicklook popup displays product options
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testProductOptions()
    {
        $cat = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanUrl' => 'apparel'));
        list($product, $selector) = $this->popupTestProduct('store/category/0/category_id-' . $cat->getCategoryId(), '00000');
        $id = $product->getProductId();

        $this->assertTrue(method_exists($product, 'hasOptions'), "ProductOptions module is not enabled");
        $this->assertTrue($product->hasOptions(), "Product $id doesn't have options");

        // Collect data on displayed options

        $fieldInput = array();
        $field = $selector . ' ul.product-options li.product-option input';
        $count = (int)$this->getJSExpression("jQuery('$field').length");
        for ($i = 0; $i < $count; $i++) {
            $name = $this->getJSExpression("jQuery('$field').eq($i).attr('name')");
            $type = $this->getJSExpression("jQuery('$field').eq($i).attr('type')");
            $value = $this->getJSExpression("jQuery('$field').eq($i).attr('value')");
            $fieldInput[$type][$name][$value] = $value;
        }

        $fieldArea = array();
        $field = "$selector ul.product-options li.product-option textarea";
        $count = $this->getJSExpression("jQuery('$field').size()");
        for($i=0; $i<$count; $i++) {
            $name = $this->getJSExpression("jQuery('$field').eq($i).attr('name')");
            $fieldArea[$name] = $name;
        }

        $fieldSelect = array();
        $field = "$selector ul.product-options li.product-option select";
        $count = $this->getJSExpression("jQuery('$field').size()");
        for($i=0; $i<$count; $i++) {
            $name = $this->getJSExpression("jQuery('$field').eq($i).attr('name')");
            $fieldSelect[$name] = null;
            $values = array();
            $field2 = "$field.eq($i) option";
            $count2 = $this->getJSExpression("jQuery('$field2').size()");
            for($j=0; $j++; $j<$count2) {
                $value = $this->getJSExpression("jQuery('$field2').eq($i).attr('value')");
                $fieldSelect[$name][$value] = $value;
            }
        }

        foreach($product->getActiveOptions() as $optionGroup) {

            $name = $optionGroup->getDisplayName();
            $groupId = $optionGroup->getGroupId();
            $type = $optionGroup->getType() . $optionGroup->getViewType();

            $this->assertJqueryPresent(
                "$selector ul.product-options li.product-option strong.subtitle:contains($name)",
                "Option name is missing ($name)"
            );


            switch ($optionGroup->getType() . $optionGroup->getViewType()) {

                case $optionGroup::TEXT_TYPE . $optionGroup::INPUT_VISIBLE:
                    $this->assertTrue(
                        isset($fieldInput['text']["product_options[$groupId]"]),
                        "A text field is missing: $groupId"
                    );
                    break;

                case $optionGroup::TEXT_TYPE . $optionGroup::TEXTAREA_VISIBLE:
                    $this->assertTrue(
                        isset($fieldArea["product_options[$groupId]"]),
                        "A text-area field is missing: $groupId"
                    );
                    break;

                case $optionGroup::GROUP_TYPE . $optionGroup::SELECT_VISIBLE:
                    foreach ($optionGroup->getOptions() as $option) {
                        $id = $option->getOptionId();
                        $name = $option->getName();
                        $this->assertTrue(
                            isset($fieldSelect["product_options[$groupId]"][$id]),
                            "A select option is missing: $groupId, $id"
                        );
                    }
                    break;

                case $optionGroup::GROUP_TYPE . $optionGroup::RADIO_VISIBLE:
                    foreach ($optionGroup->getOptions() as $option) {
                        $id = $option->getOptionId();
                        $name = $option->getName();
                        $this->assertTrue(
                            isset($fieldInput['radio']["product_options[$groupId]"][$id]),
                            "A radio option is missing: $groupId, $id"
                        );
                    }
                    break;

                default:
                    $this->assertTrue(false, "Wrong option type: ".$optionGroup->getType() . $optionGroup->getViewType());


            }

        }

    }

    /**
     * Test how the Quicklook popup displays an image gallery and the image zoomer
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGalleryAndZoomer()
    {
        $c1 = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanUrl' => 'apparel'));
        $c2 = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanUrl' => 'toys'));

        $products = array(
            array('url' => 'store/category/0/category_id-' . $c1->getCategoryId(), 'id' => '00002'),
            array('url' => 'store/category/0/category_id-' . $c2->getCategoryId(), 'id' => '00022'),
        );

        foreach ($products as $p) {

            list($product, $selector) = $this->popupTestProduct($p['url'], $_id = $p['id']);

            // Base use case
            $this->mouseOver("css=div.mousetrap");

            $this->assertJqueryPresent('#cloud-zoom-big:visible', "Zoomer layout #1 ($_id)");
            $this->assertJqueryPresent('.cloud-zoom-lens:visible', "Zoomer lens #1 ($_id");

            $this->mouseOut("css=div.mousetrap");

            $this->waitForLocalCondition(
                'jQuery("#cloud-zoom-big:visible").length == 0',
                2000,
                "Zoomer is shown when the mouse cursors is out of the bounds ($_id)"
            );

            $this->assertJqueryNotPresent('#cloud-zoom-big:visible', "Zoomer layout #2 ($_id)");
            $this->assertJqueryNotPresent('#cloud-zoom-lens:visible', "Zoomer lens #2 ($_id)");

            $this->assertElementPresent("css=$selector .product-image-gallery ul li a", "Gallery links are missing ($_id)");

            // Gallery based use cases
            $length = intval($this->getJSExpression("jQuery('$selector .product-image-gallery ul li a').length"));

            for ($idx = 2; $idx < $length + 1; $idx++) {
                $i = $idx - 1;

                $this->click("//div[@class='product-image-gallery']/ul/li[position()=$idx]/a");

                $src = $this->getJSExpression("jQuery('$selector .product-image-gallery ul li:eq($i) img.middle').attr('src')");
                $w = $this->getJSExpression("jQuery('$selector .product-image-gallery ul li:eq($i) img.middle').attr('width')");
                $h = $this->getJSExpression("jQuery('$selector .product-image-gallery ul li:eq($i) img.middle').attr('height')");

                $this->waitForLocalCondition(
                    'jQuery("'.$selector.' .cloud-zoom img").attr("src") == "' . $src . '"',
                    2000,
                    "Image change is failed [$idx image] ($_id)"
                );


                $rev = $this->getJSExpression("jQuery('$selector .product-image-gallery ul li:eq($i) a').attr('rev')");
                if (!preg_match('/width: (\d+), height: (\d+)/', $rev, $m)) {
                    $this->fail("Rev attribute has a wrong format [$idx image] ($_id)");
                }

                if ($this->isZoomEnabled($m[1])) {

                    $style = $this->getJSExpression("jQuery('$selector .product-photo').attr('style')");
                    $this->assertTrue(
                        strpos(" $style", 'width: '.$w.'px;') > 0,
                        "Style check is failed [$idx image] ($_id)"
                    );

                    $imgSrc = $this->getJSExpression("jQuery('$selector .product-photo #wrap a.cloud-zoom img').attr('src')");
                    $this->assertEquals($imgSrc, $src, "URL check failed [$idx image] ($_id)");


                    $this->mouseOver("css=div.mousetrap");

                    $this->assertJqueryPresent('#cloud-zoom-big:visible', "Zoomer layout #3 [$idx image]  ($_id)");
                    $this->assertJqueryPresent('.cloud-zoom-lens:visible', "Zoomer lens #3 [$idx image] ($_id)");

                } else {

                    $this->assertElementNotPresent("css=div.mousetrap", "Zoomer elements are shown for small images [$idx image] ($_id)");

                }
            }
        }
    }


    /**
     * Test how the Quicklook popup displays and acts on cart buttons
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAdd2Cart()
    {
        $c2 = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanUrl' => 'toys'));
        list($product, $selector) = $this->popupTestProduct('store/category/0/category_id-' . $c2->getCategoryId(), '00022');
        $id = $product->getProductId();

        // This assertion requires the minicart widget to be visible on the page
        $qty = intval($this->getJSExpression("jQuery('.minicart-items-number').html()"));

        $formSelector = "css=$selector form.product-details.hproduct";
        $cartButtonSelector = "$formSelector .product-details-info .product-buttons button.bright.add2cart";
        $buyButtonSelector = "$formSelector .product-details-info .product-buttons-added button.action.buy-more";
        $continueButtonSelector = "$formSelector .product-details-info .product-buttons-added button.bright.continue";

        $this->assertElementPresent($cartButtonSelector, "Add-to-cart button is missing (#1)");
        $this->assertElementNotPresent($buyButtonSelector, "Buy-now button is visible (#1)");
        $this->assertElementNotPresent($continueButtonSelector, "Continue-button is visible (#1)");

        $this->click($cartButtonSelector);

        $qty++;

        $this->waitForLocalCondition(
            "jQuery('.BlockMsg-product-quicklook:visible').length <= 0",
            10000,
            "Add-to-cart button doesn't close Quicklook popups"
        );

        $this->waitForLocalCondition(
            'jQuery(".minicart-items-number").html() == ' . $qty,
            10000,
            "Minicart widget displays a wrong qty (#1)"
        );

        $this->popupQuicklook($id);

        $this->assertElementNotPresent($cartButtonSelector, "Add-to-cart button is visible (#1)");
        $this->assertElementPresent($buyButtonSelector, "Buy-now button is missing (#2)");
        $this->assertElementPresent($continueButtonSelector, "Continue-button is missing (#2)");

        $this->click($buyButtonSelector);

        $qty++;

        $this->waitForLocalCondition(
            "jQuery('.BlockMsg-product-quicklook:visible').length <= 0",
            10000,
            "Buy-now button doesn't close Quicklook popups"
        );

        $this->waitForLocalCondition(
            'jQuery(".minicart-items-number").html() == ' . $qty,
            10000,
            "Minicart widget displays a wrong qty (#1)"
        );


        // TODO - Continue button

        // TODO - rework after Inventory tracking module is changed

    }

    /**
     * Open a category page and popup a Quicklook for a product.
     * Returns a list of a product object and a CSS selector to the Quicklook popup
     * 
     * @param string  $categoryUrl URL of a category page
     * @param integer $productId   Product ID
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function popupTestProduct($categoryUrl, $productId)
    {
        $list = $this->getListSelector();
        $this->open($categoryUrl);
        $this->switchDisplayMode('grid');

        $id = $productId;

        $product = $this->getProductBySku($productId);
        $this->assertNotNull($product, "Product $id is not found in the DB");

        $this->popupQuicklook($product->getProductId());

        $selector = ".BlockMsg-product-quicklook .product-quicklook";
        $this->assertElementPresent("css=$selector", "Quicklook popup for the $id product is missing on the page");

        return array($product, $selector);
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
        $name = $this->getJSExpression("jQuery('$selector h1.fn.title').html()");
        $this->assertEquals($name, $product->getName(), "Wrong product name ($id)");
        
        // Price
        $price = $this->getJSExpression("jQuery('$selector .product-price').html()");
        $parsedPrice = preg_replace("/^\D*(\d+\.\d+)\D*$/", "\\1", $price);
        $this->assertEquals($parsedPrice, $product->getPrice(), "Wrong price ($id)");

        // TODO: In-stock quantity
        // $qty = $this->getJSExpression("jQuery('$selector .product-stock-level span').html()");
        // $parsedQty = preg_replace("/^\((\D+) .*/", "\\1", $qty);

        // Add-to-cart form
        $this->assertElementPresent("css=$selector .product-qty input.quantity", "Quantity field is missing (product $id)");
        $this->assertElementPresent("css=$selector button.add2cart", "Add to cart button is missing (product $id)");

        // Product page link (is returned by the method)
        $link = $this->getJSExpression("jQuery('$selector a.product-more-link')");

       // Gallery
        if ($product->countImages() > 1) {
            $this->assertElementNotPresent("css=$selector .loupe", "Zoom icon is shown in the Quicklook popup (product $id)");
            $this->assertElementPresent("css=$selector .product-image-gallery ul li a[rel='gallery'] img", "Image links are missing in the image gallery (product $id)");
            $this->assertEquals(
                count($product->getImages()),
                $this->getJSExpression("jQuery('$selector .product-image-gallery li a').length"),
                "Image gallery displays a wrong number of images (product $id)"
            );
            $this->assertEquals(
                'true',
                $this->getJSExpression("jQuery('$selector .product-image-gallery li').eq(0).hasClass('selected')"),
                "The first image in the gallery is not selected (product $id)"
            );
        }

        // Image zoom

        if ($product->hasImage()) {

            $image = $product->getImages()->get(0); // the default image

            if ($this->isZoomEnabled($image->getWidth())) {

                $this->assertElementPresent(
                    "css=$selector .product-photo div#wrap a.cloud-zoom#pimage_".$id." img.photo.product-thumbnail",
                    "Cloud Zoom image is missing (product $id)"
                );

                $imageRel = $this->getJSExpression("jQuery('$selector a.cloud-zoom#pimage_$id').attr('rel')");
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
     * Checks whether the zoomer function is to be enabled for an image width
     * 
     * @param integer $width Image width
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isZoomEnabled($width)
    {
        $kZoom = 1.3; // see skins/drupal/en/product/details/controller.js
        $maxWidth = 300; // see \XLite\View\Product\Details\Customer\Image
        return $width > $kZoom * $maxWidth;
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
        $this->waitForLocalCondition("jQuery('.BlockMsg-product-quicklook:visible:visible').length <= 0", 300000);

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
        $this->waitForLocalCondition("jQuery('.BlockMsg-product-quicklook:visible').length > 0", 300000);

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
        $selector = 'css=' . $this->getModeSelector($mode);
        $this->assertElementPresent(
            $selector,
            'A selector for \'' . $mode . '\' display mode is missing (' . $selector . ') on ' . $this->getLocation() . '!'
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
        $this->waitForLocalCondition("jQuery('.blockUI.block-wait:visible').length <= 0", 300000);
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

        $title = $this->getJSExpression("jQuery('h1#page-title.title').attr('title')");
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
