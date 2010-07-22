<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Repo\Category class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Repo_Category extends XLite_Tests_TestCase
{

    /**
     * Test on cleanCache() method (file cache driver only!)
     * TODO: add checking for memcache and other cache drivers
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCleanCache()
    {
        $keys = array(
            '_Details',
            '_FullTree',
            '_FullTreeHash',
            '_NodePath',
            '_LeafNodes',
            '_MaxRightPos'
        );

        $prefix = LC_VAR_DIR . 'datacache' . LC_DS . 'XLite_Model_Category.Category';
        $suffix = '.testcase.php';

        foreach ($keys as $key) {
            if (!file_exists($prefix . $key . $suffix)) {
                touch($prefix . $key . $suffix);
            }
        }

        \XLite\Core\Database::getRepo('XLite\Model\Category')->cleanCache();

        $result = array();
        foreach ($keys as $key) {
            if (file_exists($prefix . $key . $suffix)) {
                $result[] = $key;
            }
        }

        $this->assertTrue(empty($result), 'The following cache cells left: ' . implode(', ', $result));
    }

    /**
     * Test on getCategoryFromHash() method
     * TODO: add database setup for this testcase as category #1002 may not exist
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoryFromHash()
    {
        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryFromHash(1002);

        $this->assertNotNull($category, 'Method returns null');
        $this->assertObjectHasAttribute('category_id', $category, 'Attribute not found');
        $this->assertEquals('1002', $category->category_id, 'Wrong category_id');

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryFromHash(99991002);

        $this->assertNull($category, 'Method did not return null');
    }

    public function testAddBefore()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testAddAfter()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testAddChild()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testMoveNode()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testGetCategory()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testGetCategories()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testGetCategoriesPlainList()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testGetCategoryPath()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testGetParentCategory()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testGetParentCategoryId()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testIsCategoryLeafNode()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testGetCategoryByCleanUrl()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }

    public function testDeleteCategory()
    {
        $this->assertEquals(1, 1, 'what is wrong');
    }
}
