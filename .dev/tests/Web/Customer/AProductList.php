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

// Disable the time limit, otherwise some tests may fail due to PHP breaking the script
set_time_limit(1200); // 20 minutes

require_once __DIR__ . '/ACustomer.php';

abstract class XLite_Web_Customer_AProductList extends XLite_Web_Customer_ACustomer
{
    protected $widgetContainerClass = '.items-list';
    
    protected $widgetClass = '';

    protected $currentTestUrl = null;

    protected $currentMode = '';

    protected $testProductLinks = false;

    /*
     *
     * TESTS
     *
     */

    // Table mode

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

    public function testPagerTableMode()
    {
        $this->setDisplayMode('table');
        $this->testPager();
    }


    public function testDisplayModeSwitchTableMode()
    {
        $this->setDisplayMode('table');
        $this->testDisplayModeSwitch();
    }

    public function testSortingTableMode()
    {
// TODO need to re-work....
return;
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
//        $this->setDisplayMode('list');
//        $this->testProductsData();
    }


    public function testPagerListMode()
    {
//        $this->setDisplayMode('list');
//        $this->testPager();
    }

    public function testDisplayModeSwitchListMode()
    {
        $this->setDisplayMode('list');
        $this->testDisplayModeSwitch();
    }

    public function testSortingListMode()
    {
//        $this->setDisplayMode('list');
//        $this->testSorting();
    }

    // Grid mode

    public function testBasicStructureGridMode()
    {
        $this->setDisplayMode('grid', 3);
        $this->testBasicStructure();
    }

    public function testProductsDataGridMode()
    {
//        $this->setDisplayMode('grid', 3);
//        $this->testProductsData();
    }

    public function testPagerGridMode()
    {
//        $this->setDisplayMode('grid', 3);
//        $this->testPager();
    }

    public function testDisplayModeSwitchGridMode()
    {
        $this->setDisplayMode('grid', 3);
        $this->testDisplayModeSwitch();
    }

    public function testSortingGridMode()
    {
//        $this->setDisplayMode('grid', 3);
//        $this->testSorting();
    }

    /*
     * HELPER FUNCTIONS
     */

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
        return $this->widgetContainerClass;
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
        $this->setVisible('SortBySelector');
        $this->setVisible('DisplayModeSelector');

        $this->resetBrowser();

        $this->openTestPage();

        $selector = $this->getListSelector();

        $elements = array(
            "$selector" => "Widget is missing ($mode mode)",
            "$selector .products-$mode" => "Mode container element is missing ($mode mode)",
//            "$selector .list-header" => "List header is missing",
//            "$selector .list-header .display-modes" => "Display Mode box is missing",
//            "$selector .list-header .sort-box" => "Sort Box is missing",
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
        foreach (array(false, true) as $sortBox) {

            foreach (array(false, true) as $displayMode) {

                $sortBox = $sortBox ? $this->setVisible('SortBySelector') : $this->setHidden('SortBySelector');

                $displayMode = $displayMode ? $this->setVisible('DisplayModeSelector') : $this->setHidden('DisplayModeSelector');

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

        // Make sure the products are displayed with a correct structure
//        $this->testPagerProducts(array_values($listedProducts), $productsCount, 1);

        $products = array(current($products), $products[count($products) - 1]);

        // Make sure that the page displays all products and the products have correct data
        foreach($products as $product) {

            // find the product
            $id = $product->getProductId();

            $this->assertTrue(
                isset($listedProducts[$id]),
                "A test $id product is missing in the widget ($mode mode)"
            );
/*
            $listedProduct =& $listedProducts[$id];

            // test a product name
            $this->assertEquals(
                $name = $product->getName(),
                $listedProduct['name'],
                "A test $id product is displayed with a wrong name ($mode mode)"
            );

            // test a product sku
            if (isset($listedProduct['sku'])) {
                $this->assertEquals(
                    $product->getSku(),
                    $listedProduct['sku'],
                    "A test $id product is displayed with a wrong sku ($mode mode)"
                );
            }

            // test a product price
            $this->assertEquals(
                (float)$product->getPrice(),
                (float)$listedProduct['parsedPrice'],
                "A test $id product is displayed with a wrong price ($mode mode)"
            );

            if ($this->testProductLinks) {

                // test a link to the product page
                $this->getJSExpression('window.location = "'.$listedProduct['nameUrl'].'";');
                $this->waitForPageToLoad(180000);
                $this->assertElementPresent(
                    "css=h1.fn.title",
                    "Product $id doesn't link to a product page ($mode mode)"
                );
                $title = $this->getJSExpression("$('h1.fn.title').html()");
                $this->assertEquals(
                    $name,
                    $title,
                    "Product $id links to a wrong product page ($mode mode)"
                );

           }
*/

        }

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
// TODO make this test lighter
return;

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
        $pagesCount = ($productsCount > 3) ? 3 : 1;
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
                $this->waitForAjaxProgress();
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
                $this->waitForAjaxProgress();
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

        $productsSelector = "$listSelector .products .product";

        for ($perPage=1; $perPage <= $productsCount; $perPage++) {

            $inputSelector = "$listSelector .list-pager .pager-items-total input.page-length";

            $this->assertElementPresent(
                "css=$inputSelector",
                "Input element is missing ($perPage products per page, $mode mode)"
            );
            $this->type("css=$inputSelector", $perPage);
            $this->keyPress("css=$inputSelector", '\\13');
            $this->waitForAjaxProgress();

            $this->assertEquals(
                $perPage,
                $this->getJSExpression("$('$productsSelector').size()"),
                "Number of products doesn't match the number to be displayed per page ($perPage per page, $mode mode)"
            );

        }

    }

    /**
     * Test how the mode switching links work
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testDisplayModeSwitch()
    {
        $mode = $this->getDisplayMode();
        $listSelector = $this->getListSelector();

        $this->configurePager(9, true);

        $this->setVisible('DisplayModeSelector');
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
                "Link for $m mode is missing ($mode initial mode)"
            );

            $this->click("css=$linkSelector");
            $this->waitForAjaxProgress();

            $this->assertElementPresent(
                "css=$productsSelector",
                "$productsSelector is missing after clicking '$m' mode ($mode initial mode)"
            );

        }

    }


    /**
     * Test the sorting options
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testSorting()
    {
        $mode = $this->getDisplayMode();
        $listSelector = $this->getListSelector();
        $selector = "$listSelector .list-header .sort-box select.sort-crit";
        $orderSelector = "$listSelector .list-header .sort-box a.sort-order";

        // Display all products and store displayed product data for further reference
        $productsCount = $this->countAllTestProducts();

        $this->configurePager($productsCount, true);

        $this->setVisible('SortBySelector');

        $this->resetBrowser();
        $this->openTestPage();

        $optionLabels = array(
            'Name'=>'name',
            'Price'=>'price',
            'Default'=>'name',   // for some reasons the default sort method is neither by name nor by id; can't find a way to test it
        );

        if ('table' === $mode) {
            $optionLabels['SKU'] = 'sku';
        }

        $sortOrder = 'asc';

        foreach ($optionLabels as $label=>$field) {

            $this->assertElementPresent(
                "css=$selector",
                "Mode selector is missing ($label label, $field field, $mode mode)"
            );

            $this->select("css=$selector", "label=$label");

            $this->waitForAjaxProgress();

            $this->testSortedProducts($field, $label, $mode, ($sortOrder=='asc'));

            $this->assertElementPresent(
                "css=$orderSelector",
                "Asc/Desc link is missing ($label label, $field field, $mode mode)"
            );

            $this->click("css=$orderSelector");

            $this->waitForAjaxProgress();

            $sortOrder = ($sortOrder == 'asc') ? 'desc' : 'asc';

            $this->testSortedProducts($field, $label, $mode, ($sortOrder=='asc'));

        }
    }

    /**
     * Test whether sorted products are displayed in the correct order
     * 
     * @param string  $field    Name of the field to compare
     * @param string  $label    Name of the selected sort option
     * @param string  $mode     Current display mode
     * @param boolean $ascOrder Whether it is an ascending order, or not
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testSortedProducts($field, $label, $mode, $ascOrder)
    {
        $products = $this->getListedProducts($mode);

        $last = null;

        foreach($products as $product) {

            if (is_null($last)) {

                $last = $product;

            } else {

                if ($ascOrder) {

                    $this->assertTrue(
                        in_array($field, array('price')) ? (strnatcasecmp($product['parsed' . ucfirst($field)], $last['parsed' . ucfirst($field)]) >= 0) : (strcasecmp($product[$field] ,$last[$field]) >= 0),
                        "Wrong order ($label label, $field field, '$last[$field]', '$product[$field]', ASC, $mode mode)"
                    );

                } else {

                    $this->assertTrue(
                        in_array($field, array('price')) ? (strnatcasecmp($product['parsed' . ucfirst($field)], $last['parsed' . ucfirst($field)]) <= 0) : (strcasecmp($product[$field], $last[$field]) <= 0),
                        "Wrong order ($label label, $field field, '$last[$field]', '$product[$field]', DESC, $mode mode)"
                    );
                }

                $last = $product;

            }
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
/*
        There is no qty field in table mode!

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
*/
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
//            "$info .end-record-number" => "'till' number is missing",
//            "$info .records-count" => "'count' number is missing",
//            "$info input.page-length" => "'per page' field is missing",
//            "$info input.page-length[value=$perPage]" => "'per page' field contains wrong value, not '$perPage'",
        );
        foreach ($infoElements as $selector=>$message) {
            $this->assertJqueryPresent("$pager1 $selector", "Pager 1: $message ($mode mode)");
        }

        $infoFields = array(
            "$info .begin-record-number" => $from,
 //           "$info .end-record-number" => $till,
//            "$info .records-count" => $total,
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

/*
            foreach ($infoFields as $selector=>$value) {
                $html = $this->getJSExpression("$('$pager2 $selector').html()");
                $this->assertEquals(
                    $html,
                    $value,
                    "Pager 2: '$selector' element has a wrong value that does not match '$value' ($mode mode)"
                );
            }
*/
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

        for ($i = 0; $i < $count; $i++) {

            $class = $this->getJSExpression("$('$selector').eq($i).attr('class')");

            $id = preg_replace('/^.*productid-([0-9]+).*$/', '\\1', $class);

            $productSelector = "$selector.productid-$id";

            $product = array('id' => $id);    

            $nameSelector               = ($mode=='table') ? "$productSelector a.product-link" : "$productSelector h3.product-name a";

            $product['name']            = $this->getJSExpression("$('$nameSelector').html()");
//            $product['nameUrl']         = $this->getJSExpression("$('$nameSelector')");
            $product['sku']             = $this->getJSExpression("$('$productSelector .product-sku').html()");
            $product['price']           = $this->getJSExpression("$('$productSelector .product-price').html()");
            $product['parsedPrice']     = preg_replace("/^\D*(\d+\.\d+)\D*$/", "\\1", $product['price']);
//            $product['imgUrl']          = $this->getJSExpression("$('$productSelector a.product-thumbnail')");
//            $product['imgSrc']          = $this->getJSExpression("$('$productSelector a.product-thumbnail img').attr('src')");
//            $product['imgAlt']          = $this->getJSExpression("$('$productSelector a.product-thumbnail img').attr('alt')");
//            $product['description']     = $this->getJSExpression("$('$productSelector .product-description').html()");
            
            foreach ($product as $k => $v) {
                $product[$k] = ($v === 'null') ? null : $v;
            }

           $products[$id] = $product;

        }

        return $products;
        
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
        if ($showSelector) {

            $this->setVisible('ItemsPerPageSelector');

        } else {

            $this->setHidden('ItemsPerPageSelector');

        }

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
        // it is commented due to the fact that sometimes the progress bar disappers faster than selenium checks whether it is visible
        // $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('$listSelector .blockUI.wait-block:visible').length > 0");

        // wait until the progress bar is hidden
        $this->waitForCondition("selenium.browserbot.getCurrentWindow().$('$listSelector .blockUI.block-wait:visible').length <= 0", 300000);
 
    }

    /**
     * Set as visible and return visibility 
     * 
     * @param string $part part of widget
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setVisible($part)
    {
        $this->setWidgetParam($this->getWidgetId(), 'show' . $part, true);

        return true;
    }


    /**  
     * Set as hidden and return visibility 
     * 
     * @param string $part part of widget
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setHidden($part)
    {
        $this->setWidgetParam($this->getWidgetId(), 'show' . $part, false);

        return false;
    }

}
