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

class XLite_Web_Customer_Subcategories extends XLite_Web_Customer_ACustomer
{
    protected $widgetClass = '\\XLite\\View\\Subcategories';

    public function testSubcategoriesList()
    {
        $this->setDisplayMode('list');
        $this->testSubcategories('list');
    }

    public function testSubcategoriesIcons()
    {
        $this->setDisplayMode('icons');
        $this->testSubcategories('icons');
    }

    protected function testSubcategories($mode = 'icons')
    {
        $category = $this->getTestCategory();
        $url = $this->getCategoryURL($category->getCategoryId());

        $this->resetBrowser();
        $this->open($url);

        $subcategoriesSelector = "ul.subcategory-$mode";

        $this->assertElementPresent(
            "css=$subcategoriesSelector",
            "Subcategories are missings ($mode mode)"
        );

        $subcategories = $category->getSubcategories();
        $listedCategories = $this->getListedSubcategories($subcategoriesSelector);
        
        $this->assertEquals(
            count($subcategories),
            count($listedCategories),
            "A category page doesn't list all subcategories ($mode mode)"
        );

        foreach($subcategories as $category) {

            $name = $category->getName();

            $this->assertTrue(
                isset($listedCategories[$name]),
                "A subcategory is missing: $name ($mode mode)"
            );            

            $listed =& $listedCategories[$name];

            $this->assertEquals(
                $name,
                $listed['name'],
                "A subcategory has a wrong name ($mode mode)"
            );

            if ($mode=='icons') {
                $this->assertEquals(
                    $name,
                    $listed['imgAlt'],
                    "A subcategory image has a wrong alt ($mode mode)"
                );
            }

            $this->open($listed['link']);
            $titleSelector = "h1.title#page-title";
            $this->assertElementPresent(
                "css=$titleSelector",
                "A subcategory doesn't link to a category page ($mode mode)"
            );
            $title = $this->getJSExpression("$('$titleSelector').attr('title')");
            $this->assertEquals(
                $name,
                $title,
                "A subcategory links to a wrong category page"
            );
        }

    }

    protected function getListedSubcategories($listSelector)
    {
        $selector = "$listSelector li";
        $items = array();

        $count = $this->getJSExpression("$('$selector').size()");

        for($i=0; $i<$count; $i++) {

            $item['link'] = $this->getJSExpression("$('$selector a').eq($i)");
            $item['imgAlt'] = $this->getJSExpression("$('$selector .subcategory-icon img').eq($i).attr('alt')");
            $name = $item['name'] = $this->getJSExpression("$('$selector .subcategory-name').eq($i).html()");

            $items[$name] = $item;
        }

        return $items;

    }


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
            $count = count($one->getSubcategories());
            if ($count && ($count > $max) && ($count < 50)) {
                $category = $one;
                $max = $count;
            }
        }

        $this->assertNotNull($category, 'getTestCategory() returned null');

        return $category;

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
    protected function setDisplayMode($mode = 'list')
    {
        $this->setWidgetParam($this->getWidgetId(), 'displayMode', $mode);
    }


}
