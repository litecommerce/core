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

abstract class XLite_Web_Customer_AProductList extends XLite_Web_Customer_ACustomer
{

    protected $widgetContainerClass = '';
    
    protected $widgetClass = '';

    protected $currentTestUrl = null;

    protected $currentMode = '';

    /*
     *
     * TESTS
     *
     */

    // Table mode
/*
    public function testBasicStructureTableMode()
    {
        $this->setDisplayMode('table');
        $this->testBasicStructure();
    }

    public function testProductsDataTableMode()
    {
        $this->setDisplayMode('table');
        $this->testProductsData();
    }
*/
    public function testPagerTableMode()
    {
        $this->setDisplayMode('table');
        $this->testPager();
    }
/*
    public function testDisplayModeSwitchTableMode()
    {
        $this->setDisplayMode('table');
        $this->testDisplayModeSwitch();
    }

    public function testSortingTableMode()
    {
        $this->setDisplayMode('table');
        $this->testSorting();
    }

    // List mode

    public function testBasicStructureListMode()
    {
        $this->setDisplayMode('list');
        $this->testBasicStructure();
    }

    public function testProductsDataListMode()
    {
        $this->setDisplayMode('list');
        $this->testProductsData();
    }

    public function testPagerListMode()
    {
        $this->setDisplayMode('list');
        $this->testPager();
    }

    public function testDisplayModeSwitchListMode()
    {
        $this->setDisplayMode('list');
        $this->testDisplayModeSwitch();
    }

    public function testSortingListMode()
    {
        $this->setDisplayMode('list');
        $this->testSorting();
    }

    // Grid mode

    public function testBasicStructureGridMode()
    {
        $this->setDisplayMode('grid', 3);
        $this->testBasicStructure();
    }

    public function testProductsDataGridMode()
    {
        $this->setDisplayMode('grid', 3);
        $this->testProductsData();
    }

    public function testPagerGridMode()
    {
        $this->setDisplayMode('grid', 3);
        $this->testPager();
    }

    public function testDisplayModeSwitchGridMode()
    {
        $this->setDisplayMode('grid', 3);
        $this->testDisplayModeSwitch();
    }

    public function testSortingGridMode()
    {
        $this->setDisplayMode('grid', 3);
        $this->testSorting();
    }

*/


    /*
     * HELPER FUNCTIONS
     */

    /**
     * Resets the browser and instantiates a new browser session
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function resetBrowser()
    {
        $this->stop();
        $this->start();
    }

    /**
     * Opens the current test page
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    abstract protected function openTestPage();

    /**
     * Returns the number of all test products to be listed in a pager on the current test page
     * 
     * @return int
     * @since  3.0.0
     */
    abstract protected function countAllTestProducts();

    /**
     * Returns all test products to be listed in a pager on the current test page
     * 
     * @return mixed
     * @since  3.0.0
     */
    abstract protected function getAllTestProducts();


    /**
     * Returns jQuery selector to the top widget container element
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getListSelector()
    {
        return '.items-list';
    }

    /**
     * Test whether all the top UI elements are displayed and have a correct structure
     * 
     * @param string $mode Display mode
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testBasicStructure()
    {
        $mode = $this->getDisplayMode();

        // Set the pager to display more than one page
        $count = $this->countAllTestProducts();
        $this->configurePager(ceil($count/2), true);

        /*
         * Test all top UI elements
         */

        $this->setWidgetParam($this->getWidgetId(), 'showSortBySelector', true);
        $this->setWidgetParam($this->getWidgetId(), 'showDisplayModeSelector', true);
        $this->resetBrowser();
        $this->openTestPage();

        $selector = $this->getListSelector();

        $elements = array(
            "$selector" => "Widget is missing ($mode mode)",
            "$selector .products-$mode" => "Mode contianer element is missing ($mode mode)",
            "$selector .list-header" => "List header is missing",
            "$selector .list-header .display-modes" => "Display Mode box is missing",
            "$selector .list-header .sort-box" => "Sort Box is missing",
        );
        if ($mode != 'table') {
            $elements["$selector .cart-tray"] = "Cart tray is missing";
        }

        foreach ($elements as $s=>$message) {
            $this->assertJqueryPresent($s, "$message ($mode mode)");
        }

        /*
         * Now test how UI elements are enabled/disabled
         */

        $options = array(false, true);

        foreach ($options as $sortBox) {

            foreach ($options as $displayMode) {

                $this->setWidgetParam($this->getWidgetId(), 'showSortBySelector', $sortBox);
                $this->setWidgetParam($this->getWidgetId(), 'showDisplayModeSelector', $displayMode);
                $this->resetBrowser(); 
                $this->openTestPage();

                $sortBoxMethod = $sortBox ? "assertElementPresent" : "assertElementNotPresent";
                $displayModeMethod = $displayMode ? "assertElementPresent" : "assertElementNotPresent";

                $this->$sortBoxMethod(
                    "css=$selector .list-header .sort-box",
                    "Failed assertion ($mode mode): sort box = ".(string)$sortBox."; display mode selector = ".(string)$displayMode
                );
                $this->$displayModeMethod(
                    "css=$selector .list-header .display-modes",
                    "Failed assertion ($mode mode): display mode selector = ".(string)$displayMode."; sort box = ".(string)$sortBox
                );

            }
        }

    }


    /**
     * Test whether the widget displays all products and with correct product data
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testProductsData()
    {
        $mode = $this->getDisplayMode();

        // Configure the pager to display all test products on one page
        $productsCount = $this->countAllTestProducts();
        $this->configurePager($productsCount, true);
        $this->resetBrowser();
        $this->openTestPage();

        // Get products from the DB
        $products = $this->getAllTestProducts();
        // Get products from the page
        $listedProducts = $this->getListedProducts($mode);

        // Make sure the page displays enough products
        $this->assertEquals(
            count($listedProducts),
            $productsCount,
            "The widget displays a wrong number of products ($mode mode)"
        );

        // Make sure that the page displays all products and the products have correct data
        foreach($products as $product) {

            // find the product
            $id = $product->getProductId();
            $this->assertTrue(
                isset($listedProducts[$id]),
                "A test $id product is missing in the widget ($mode mode)"
            );
            $listedProduct =& $listedProducts[$id];

            // test a product name
            $this->assertEquals(
                $name = $product->getName(),
                $listedProduct['name'],
                "A test $id product is displayed with a wrong name ($mode mode)"
            );

            // test a product sku
            $this->assertEquals(
                $name = $product->getSku(),
                $listedProduct['sku'],
                "A test $id product is displayed with a wrong sku ($mode mode)"
            );

/*
            // TODO: this test fails due to a timeout

            // test a link to the product page
            $this->open($listedProduct['nameUrl']);
            $this->assertJqueryPresent(
                "h1.fn.title:contains($name)",
                "Product $id doesn't link to the product page ($mode mode)"
            );
*/

            // TODO: find a way to check a formatted price against the product price property

        }

        // Make sure the products are displayed with a correct structure
        $this->testPagerProducts(array_values($listedProducts), $productsCount, 1);

    }

    /**
     * Test whether the pager splits and browses products correctly
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testPager()
    {
        $mode = $this->getDisplayMode();
        $listSelector = $this->getListSelector();

        // Display all products and store displayed product data for further reference
        $productsCount = $this->countAllTestProducts();
        $this->configurePager($productsCount, true);
        $this->resetBrowser();
        $this->openTestPage();
        $allProducts = array_values($this->getListedProducts($mode));

        // Test the pager when there is one page only
        $this->testPagerStructure($productsCount, $productsCount, 1);

        // Now test how the pager displays products split into several pages
        $pagesCount = ($productsCount > 4) ? 4 : 1;
        $perPage = ($productsCount > $pagesCount) ? ceil($productsCount/$pagesCount) : $productCount;
        $this->configurePager($perPage, true);

        // Open the first page
        $this->resetBrowser();
        $this->openTestPage();

        /*
         * Browse all pager pages by clicking "Next page" button
         */

        for($page=1; $page<=$pagesCount; $page++) {

            // Test the structure of the pager widget and listed products
            $this->testPagerStructure($productsCount, $perPage, $page);
            $this->testPagerProducts($allProducts, $perPage, $page);

            // Click "Next page" button on all pager pages except the last one
            if ($page < $pagesCount) {

                $this->assertElementPresent(
                    $link = "css=$listSelector ul.pager li.next-page a",
                    "'Next page' link is missing on the $page page ($mode mode)"
                );

                $this->click($link);
                sleep(15);
            }

        }

        // Make sure the last page doesn't display "Next page" link
        $this->assertElementNotPresent(
            "css=$listSelector ul.pager li.next-page a",
            "'Next page' link is missing on the $page page ($mode mode)"
        );

        /*
         * Browse all pager pages by clicking "Previous page" button
         */

        for($page=$pagesCount; $page>=1; $page--) {

            // Test the structure of the pager widget and listed products
            $this->testPagerStructure($productsCount, $perPage, $page);
            $this->testPagerProducts($allProducts, $perPage, $page);

            // Click "Previous page" button on all pager pages except the first one
            if ($page > 1) {

                $this->assertElementPresent(
                    $link = "css=$listSelector ul.pager li.previous-page a",
                    "'Previous page' link is missing on the $page page ($mode mode)"
                );

                $this->click($link);
                sleep(15);
            }

        }

        // Make sure the first page doesn't display "Previous page" link
        $this->assertElementNotPresent(
            "css=$listSelector ul.pager li.previous-page a",
            "'Previous page' link is missing on the $page page ($mode mode)"
        );


        /*
         * Now check how changing the number of products per page affects the pager
         */

        $max = ($productsCount>9) ? 9 : $productsCount;

        $productsSelector = "$listSelector .products .product";
/*
        for ($perPage=1; $perPage < $max; $perPage++) {

            $inputSelector = "$listSelector .list-pager .pager-items-total input.page-length";

            $this->assertElementPresent(
                "css=$inputSelector",
                "Input element is missing ($perPage products per page, $mode mode)"
            );
            $this->type($inputSelector, 1);

            // TODO: find a way to submit the form

            $this->assertEquals(
                $perPage,
                $this->getJSExpression("$('$productsSelector').size()"),
                "Number of products doesn't match the number to be displayed per page ($perPage per page, $mode mode)"
            );

        }
*/
    }

    protected function testDisplayModeSwitch()
    {
        $mode = $this->getDisplayMode();
        $listSelector = $this->getListSelector();

        $this->configurePager(9, true);
        $this->setWidgetParam($this->getWidgetId(), 'showDisplayModeSelector', true);
        $this->resetBrowser();
        $this->openTestPage();

        $modes = array(
            'grid',
            'list',
            'table',
        );

        foreach ($modes as $m){
            $linkSelector = "$listSelector .list-header ul.display-modes li.list-type-$m a";
            $productsSelector = "$listSelector .products .products-$m";
            $this->assertElementPresent(
                "css=$linkSelector",
                "Link for $m mode is missing ($mode mode)"
            );
            $this->click("css=$linkSelector");
            $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('$productsSelector-c:visible').length > 0");
            /*
             * Test becomes skipped (not failed) if a mode has not been switched
             */
        }

    }


    protected function testSorting()
    {
        $mode = $this->getDisplayMode();
        $listSelector = $this->getListSelector();

        // Display all products and store displayed product data for further reference
        $productsCount = $this->countAllTestProducts();

        $this->configurePager($productsCount, true);
        $this->setWidgetParam($this->getWidgetId(), 'showSortBySelector', true);
        $this->resetBrowser();
        $this->openTestPage();

        $allProducts = array_values($this->getListedProducts());

        $optionLabels = array('Default', 'Price', 'Name', 'SKU');
        $selector = "$listSelector .list-header .sort-box select";

        foreach ($optionLabels as $label) {

            $this->select($selector, "label=$label");

        }
    }


    /**
     * Test products displayed on a pager page
     * 
     * @param array $allProducts List of all products that are split by the pager into pages
     * @param int   $perPage     Number of products per page
     * @param int   $page        The pager page to be tested
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testPagerProducts($allProducts, $perPage, $page)
    {
        $mode = $this->getDisplayMode();

        $pageProducts = array_values($this->getListedProducts($mode));

        $pagesCount = ceil(count($allProducts)/$perPage);
        $max = ($page == $pagesCount) ? (count($allProducts)-$perPage*($page-1)) : $perPage;

        $this->assertEquals(
            $max,
            count($pageProducts),
            "The $page pager page displays more than the configured number of products per page ($mode mode)"
        );

        foreach($pageProducts as $n=>$product) {

            $m = ($page-1)*$perPage + $n;

            // Check whether the listed product is on its correct place among all products
            $this->assertTrue(
                isset($allProducts[$m]) && ($allProducts[$m]===$product),
                "The first pager page displays products in a wrong order ($mode mode)"
            );

            // Check the product structure
            $method = 'test'.ucwords($mode).'ProductStructure';
            $this->$method($product);

        }       
       
    }

    /**
     * Test a structure of a product shown in a product list
     * 
     * @param array $product Product data as if it was returned by getListedProducts()
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testListProductStructure($product)
    {
        $selector = $this->getListSelector() . " .products-list .product.productid-" . $product['id'];

        $this->assertElementPresent(
            "css=$selector h3.product-name a",
            "$selector product misses a linked product name (list mode)"
        );

        $this->assertElementPresent(
            "css=$selector a.product-thumbnail img",
            "$selector product misses a linked product thumbnail (list mode)"
        );

        $this->assertElementPresent(
            "css=$selector .product-description",
            "$selector product misses a product description (list mode)"
        );

        $this->assertElementPresent(
            "css=$selector .product-price",
            "$selector product misses a product price (list mode)"
        );

        $this->assertEquals(
            $product['nameUrl'],
            $product['imgUrl'],
            "Product image and product name link to different pages (list mode)"
        );

        $this->assertEquals(
            $product['name'],
            $product['imgAlt'],
            "Product name differs from the thumbnail alt value (list mode)"
        );

    }

    /**
     * Test a structure of a product shown in a product grid
     * 
     * @param array $product Product data as if it was returned by getListedProducts()
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testGridProductStructure($product)
    {
        $selector = $this->getListSelector() . " .products-grid .product.productid-" . $product['id'];

        $this->assertElementPresent(
            "css=$selector h3.product-name a",
            "$selector product misses a linked product name (grid mode)"
        );

        $this->assertElementPresent(
            "css=$selector a.product-thumbnail img",
            "$selector product misses a linked product thumbnail (grid mode)"
        );

        $this->assertElementPresent(
            "css=$selector .product-price",
            "$selector product misses a product price (grid mode)"
        );

        $this->assertEquals(
            $product['nameUrl'],
            $product['imgUrl'],
            "Product image and product name link to different pages (grid mode)"
        );

        $this->assertEquals(
            $product['name'],
            $product['imgAlt'],
            "Product name differs from the thumbnail alt value (grid mode)"
        );

    }

    /**
     * Test a structure of a product shown in a product table
     * 
     * @param array $product Product data as if it was returned by getListedProducts()
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testTableProductStructure($product)
    {
        $id =& $product['id'];

        $selector = $this->getListSelector() . " .products-table .product.productid-$id";

        $this->assertElementPresent(
            "css=$selector a.product-link",
            "$selector product misses a linked product name (table mode)"
        );

        $this->assertElementPresent(
            "css=$selector .product-sku",
            "$selector product misses a product sku (table mode)"
        );

        $this->assertElementPresent(
            "css=$selector .product-price",
            "$selector product misses a product price (table mode)"
        );

        $inputSelector = "$selector input.product-qty";
        $this->assertElementPresent(
            "css=$inputSelector",
            "$selector product misses a quantity field (table mode)"
        );
        $qtyFieldName = $this->getJSExpression("$('$inputSelector').attr('name')");
        $this->assertEquals(
            "qty[$id]",
            $qtyFieldName,
            "$selector product has a wrong name of the quantity field (table mode)"
        );

    }

    /**
     * Test whether all pager UI elements are displayed and have a correct structure
     * 
     * @param int $total        The total number of products split by the pager
     * @param int $perPage      Number of products per page
     * @param int $selectedPage The page being tested
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testPagerStructure($total, $perPage, $selectedPage = 1)
    {
        $mode = $this->getDisplayMode();

        $pager1 = $this->getListSelector() . " .list-pager";
        $pager2 = $this->getListSelector() . " .list-pager-bottom";
        $pages = "ul.pager";
        $info = ".pager-items-total";

        $this->assertElementPresent(
            "css=$pager1",
            "Pager container is missing ($mode mode)"
        );

        $from = ($perPage * ($selectedPage-1)) + 1;
        $till = ($perPage*$selectedPage < $total) ? $perPage*$selectedPage : $total;

        $infoElements = array(
            "$info" => "info block is missing",
            "$info .begin-record-number" => "'from' number is missing",
            "$info .end-record-number" => "'till' number is missing",
            "$info .records-count" => "'count' number is missing",
            "$info input.page-length" => "'per page' field is missing",
            "$info input.page-length[value=$perPage]" => "'per page' field contains wrong value, not '$perPage'",
        );
        foreach ($infoElements as $selector=>$message) {
            $this->assertJqueryPresent("$pager1 $selector", "Pager 1: $message ($mode mode)");
        }

        $infoFields = array(
            "$info .begin-record-number" => $from,
            "$info .end-record-number" => $till,
            "$info .records-count" => $total,
        );
        foreach ($infoFields as $selector=>$value) {
            $html = $this->getJSExpression("$('$pager1 $selector').html()");
            $this->assertEquals(
                $value,
                $html,
                "Pager 1: '$selector' element has a wrong value that does not match '$value' ($mode mode)"
            );
        }

        if ($total > $perPage) {

            // Multiple pages
            $this->assertElementPresent(
                "css=$pager1 $pages",
                "The first pager container doesn't display pages ($mode mode)"
            );
            $this->assertElementPresent(
                "css=$pager2",
                "The second pager container is missing ($mode mode)"
            );
            $this->assertElementPresent(
                "css=$pager2 $pages",
                "The second pager container doesn't display pages ($mode mode)"
            );

            foreach ($infoElements as $selector=>$message) {
                $this->assertJqueryPresent("$pager2 $selector", "Pager 2: $message ($mode mode)");
            }

            foreach ($infoFields as $selector=>$value) {
                $html = $this->getJSExpression("$('$pager2 $selector').html()");
                $this->assertEquals(
                    $html,
                    $value,
                    "Pager 2: '$selector' element has a wrong value that does not match '$value' ($mode mode)"
                );
            }

            $lastPage = ceil($total/$perPage);
            $pageElements = array(
                "li.item:contains($selectedPage)" => "selected page is missing",
                "li.item.selected" => "selected page is not marked",
            );
            if ($selectedPage > 1) {
                $pageElements["li.item.previous-page"] = "link to the previous page is missing";
            }
            if ($selectedPage < $lastPage) {
                $pageElements["li.item.next-page"] = "link to the next page is missing";
            }
            foreach ($pageElements as $selector=>$message) {
                $this->assertJqueryPresent("$pager1 $selector", "Pager 1: $message ($mode mode)");
                $this->assertJqueryPresent("$pager2 $selector", "Pager 2: $message ($mode mode)");
            }

        } else {

            // One page only
            $this->assertElementNotPresent(
                "css=$pager1 $pages",
                "One-page pager displays pages ($mode mode)"
            );
            $this->assertElementNotPresent(
                "css=$pager2",
                "One-page pager displays two pager containers ($mode mode)"
            );

        }

    }

    /**
     * Returns IDs of the products listed inside a container DOM element
     * 
     * @param string $listSelector jQuery selector for the root widget element (the top container element)
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getListedProducts($mode)
    {
        $this->assertElementPresent(
            "css=" . $this->getListSelector() . " .products-$mode",
            "Display mode selector is missing ($mode mode)"
        );

        $selector = $this->getListSelector() . " .products-$mode .product";
        $cellSelector = "$selector-cell";

        $products = array();

        $count = $this->getJSExpression("$('$selector').size()");
        $cells = $this->getJSExpression("$('$cellSelector').size()");

        $this->assertEquals(
            $count,
            $cells,
            "Number of product cells differs from the number of products"
        );

        for ($i=0; $i<$count; $i++) {
            $class = $this->getJSExpression("$('$selector').eq($i).attr('class')");
            $id = preg_replace('/^.*productid-([0-9]+).*$/', '\\1', $class);

            $productSelector = "$selector.productid-$id";
            $product = array('id' => $id);    

            $nameSelector = ($mode=='table') ? "$productSelector a.product-link" : "$productSelector h3.product-name a";
            $product['name'] = $this->getJSExpression("$('$nameSelector').html()");
            $product['nameUrl'] = $this->getJSExpression("$('$nameSelector')");
            $product['sku'] = $this->getJSExpression("$('$productSelector .product-sku').html()");
            $product['price'] = $this->getJSExpression("$('$productSelector .product-price').html()");
            $product['imgUrl'] = $this->getJSExpression("$('$productSelector a.product-thumbnail')");
            $product['imgSrc'] = $this->getJSExpression("$('$productSelector a.product-thumbnail img').attr('src')");
            $product['imgAlt'] = $this->getJSExpression("$('$productSelector a.product-thumbnail img').attr('alt')");
            $product['description'] = $this->getJSExpression("$('$productSelector .product-description').html()");
            
            foreach ($product as $k=>$v) {
                $product[$k] = ($v==='null') ? null : $v;
            }

           $products[$id] = $product;

        }

        return $products;
        
    }

    /**
     * Returns ID of the widget implementing a product list
     * 
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getWidgetId()
    {
        $id = $this->findWidgetID($this->widgetClass);
        $this->assertFalse(is_null($id), "Can't find the widget in the database");
        return $id;
    }

    /**
     * Configure the pager
     * 
     * @param int $itemsPerPage Number of products per page
     * @param int $showSelector Whether users can change the number of products per page, or not
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function configurePager($itemsPerPage, $showSelector = true)
    {
        $this->setWidgetParam($this->getWidgetId(), 'showItemsPerPageSelector', $showSelector);

        $allItems = ($itemsPerPage == 'all');
        $this->setWidgetParam($this->getWidgetId(), 'showAllItemsPerPage', $allItems);

        if (!$allItems && (int)$itemsPerPage) {
            $this->setWidgetParam($this->getWidgetId(), 'itemsPerPage', (int)$itemsPerPage);
        }

    }

    /**
     * Switches the first Drupal block displaying the widget to the specifed display mode
     * 
     * @param string $mode    Display mode ('list', 'grid', 'table', 'rotator')
     * @param mixed  $columns The number of columns to be displayed in Grid mode ("css-defined" for a CSS layout)
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function setDisplayMode($mode = 'list', $columns = null)
    {
        $this->currentMode = $mode;

        $this->setWidgetParam($this->getWidgetId(), 'displayMode', $mode);

        if (!is_null($columns)) {
            $this->setWidgetParam($this->getWidgetId(), 'gridColumns', $columns);
        }

    }

    /**
     * Returns the currently selected display modege
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDisplayMode()
    {
        return $this->currentMode;
    }

    /**
     * Waits until the progress bar appears and is hidden then
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function waitForAjaxProgress()
    {
        $listSelector = $this->getListSelector();

        // wait until the progress bar appears
        $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('$listSelector .blockUI.wait-block:visible').length > 0");

        // wait until the progress bar is hidden
        $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('$listSelector .blockUI.wait-block:visible').length = 0");
 
    }


}
