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

class XLite_Web_Customer_CategoryPage extends XLite_Web_Customer_ACustomer
{

    /**
     * Test all category pages
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function testCategoryPages()
    {
        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->findByEnabled(true);

        foreach($categories as $category) {
            if ($category->getCategoryId() == 1) {
                continue;
            }
            $url = $this->getCategoryURL($category->getCategoryId());
            $this->openAndWait($url);
            $this->testCategoryPage($category);
        }

    }

    /**
     * Match the opened page to a category
     * 
     * @param \XLite\Model\Category $category Category
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function testCategoryPage($category)
    {

        // Title
        $titleSelector = "h1#page-title.title";
        $this->assertElementPresent(
            "css=$titleSelector",
            "A category title is missing (" . $category->getCategoryId() . ")"
        );
        $title = $this->getJSExpression("$('$titleSelector').html()");
        $this->assertEquals(
            $category->getName(),
            $title,
            "A category displays a wrong title (" . $category->getCategoryId() . ")"
        );

        // Description
        $method = $category->getDescription() ? "assertElementPresent" : "assertElementNotPresent";
        $this->$method(
            "css=.category-description",
            "Wrong category description ($method, $title) (" . $category->getCategoryId() . ")"
        );

    }

    /**
     * Returns URL of a category page
     * 
     * @param int $id ID of the category
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryURL($id)
    {
        return "store/category//category_id-$id";
    }

}
