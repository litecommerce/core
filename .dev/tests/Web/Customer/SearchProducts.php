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

require_once __DIR__ . '/AProductList.php';

class XLite_Web_Customer_SearchProducts extends XLite_Web_Customer_AProductList
{
    const SUBMIT_BUTTON = "//table[@class='search-form-main-part']/tbody/tr/td/button[@class='search-form-submit' and @type='submit']";

    const SUBSTRING = "//table[@class='search-form-main-part']/tbody/tr/td/input[@type='text' and @name='substring']";

    const LESS_SEARCH_OPTIONS = "//td[@class='less-search-options-cell']/a[text()='Less search options']";

    const ADV_BOX = "//table[@id='advanced_search_options' and @class='advanced-search-options']";

    const SELECT_CATEGORY = "//table[@id='advanced_search_options' and @class='advanced-search-options']/tbody/tr/td/select[@name='categoryId']";

    const OPTION_CATEGORY_TOYS = "Toys";

    const OPTION_CATEGORY_DOWNLOADABLES = "Downloadables";


    protected $widgetContainerClass = '.items-list.products-search-result';
    
    protected $widgetClass = '\\XLite\\View\\Search';

    protected $isOpen = false;

    protected $doNotOpenNew = false;


    protected function openTestPage()
    {
        if ($this->doNotOpenNew && $this->isOpen) {

            return;
        }

        $url = $this->getSearchURL();

        $this->open($url);

        $this->isOpen = true;

        $this->doNotOpenNew = false;
    }

    protected function getSearchURL()
    {
        return "store/search//mode-search";
    }

    public function testSimpleSearchForm()
    {
        $this->open('store/main');

        $this->assertElementPresent(
            "//div[@class='simple-search-product-form']",
            "No simple search form!"
        );

        $this->assertElementPresent(
            "//div[@class='simple-search-product-form']/form/input[@type='text' and @name='substring' and @class='form-text']",
            "No appropriate input box in simple search form"
        );
    }

    public function testSearchSubstring()
    {
        $this->configurePager($this->getAllTestProducts());

        $this->openTestPage();

        $this->assertElementPresent(self::SUBSTRING, 'No substring field');
        $this->type(self::SUBSTRING,'mom');

        $this->assertElementPresent(self::SUBMIT_BUTTON, 'No search products button');
        $this->click(self::SUBMIT_BUTTON);

        $this->waitForAjaxProgress();

        $this->checkCounter(2);

        // Check Advanced box hide/show
        $this->click(self::LESS_SEARCH_OPTIONS);
        $this->assertVisible(self::ADV_BOX, 'Hidden advanced box');

        $this->click(self::LESS_SEARCH_OPTIONS);
        $this->assertNotVisible(self::ADV_BOX, 'Visible advanced box');

        $this->click(self::LESS_SEARCH_OPTIONS);

        // Check including options
        $this->type(self::SUBSTRING, 'search mom');

        $this->checkIncluding('any', 6);
        $this->checkIncluding('all', 1);
        $this->checkIncluding('phrase', 0);

        // Check category selector
        $this->type(self::SUBSTRING, 'search');

        // Check 'Toys' category search
        $this->assertElementPresent(self::SELECT_CATEGORY, 'No category selector!');

        $this->select(self::SELECT_CATEGORY, self::OPTION_CATEGORY_TOYS);

        $this->click(self::SUBMIT_BUTTON);

        $this->waitForAjaxProgress();

        $this->checkCounter(2);

        // Check 'Downloadables' category search
        $this->select(self::SELECT_CATEGORY, self::OPTION_CATEGORY_DOWNLOADABLES);

        $this->click(self::SUBMIT_BUTTON);

        $this->waitForAjaxProgress();

        $this->checkCounter(3);
    }

    protected function checkIncluding($type, $count)
    {
        $this->click("//label[@for='including-$type']/input[@id='including-$type' and @type='radio' and @name='including' and @value='$type']");

        $this->click(self::SUBMIT_BUTTON);

        $this->waitForAjaxProgress();

        $this->checkCounter($count);
    }

    protected function checkCounter($count)
    {
        $this->assertElementPresent(
            "//div[@class='items-list products-search-result']/h2[@class='items-list-title' and text()='$count products found']",
            '"Search found" string is wrong. must be ' . $count
        );

        $this->assertElementPresent(
            "//div[@class='items-list products-search-result']/div[@class='list-pager']/div[@class='pager-items-total']/span[@class='records-count' and text()='$count']",
            'Records counter is wrong - must be ' . $count
        );
    }


    protected function countAllTestProducts()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->search(null, true);
    }

    protected function getAllTestProducts()
    {
        return array();
    }

    protected function setDisplayMode($mode = 'list', $columns = null)
    {
        $this->doNotOpenNew = true;

        $this->openTestPage();

        $this->doNotOpenNew = true;

        $this->currentMode = $mode;

        $this->click('//a[@class="' . $mode . '"]');

        $this->waitForAjaxProgress();
    }

    protected function setVisible($part)
    {
        return true;
    }

    protected function setHidden($part)
    {
        return true;
    }

    protected function resetBrowser()
    {
//        $this->doNotOpenNew = false;
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
        $this->doNotOpenNew = true;

        $this->openTestPage();

        $this->doNotOpenNew = true;

        $patternItemsPerPage = "//div[@class='pager-items-total']/span/input[@class='page-length']";

        $this->type($patternItemsPerPage, (int)$itemsPerPage);

        $this->keyPress($patternItemsPerPage, '\\13');

        $this->waitForAjaxProgress();
    }


}
