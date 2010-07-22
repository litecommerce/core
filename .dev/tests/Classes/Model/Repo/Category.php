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
     * categoryId 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $categoryId = 'XLite_Tests_Model_Repo_Category_categoryId';

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
     * Test on addChild() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddChild()
    {
        $result = 0;

        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild(9999999);

        $this->assertNull($newCategory, 'Not null is returned on addAfter(9999999)');

        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild(0);

        $this->assertNotNull($newCategory, 'Null is returned on addChild(0), object is expected');

        if (isset($newCategory)) {
            $this->assertObjectHasAttribute('category_id', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('lpos', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('rpos', $newCategory, 'Attribute not found');

            $this->assertEquals(array(1, 2), array($newCategory->getLpos(), $newCategory->getRpos()), 'lpos and rpos indexes are wrong');

            $this->assertGreaterThan(0, $newCategory->getCategoryId(), 'category_id value must be a positive number');

            $result = $newCategory->getCategoryId();
        }

        return $result;
    }

    /**
     * Test on addAfter() method
     * 
     * @depends testAddChild
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddAfter(integer $categoryId = null)
    {
        if (!isset($categoryId)) {
            $categoryId = 14015;
        }

        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addAfter(0);

        $this->assertNull($newCategory, 'Not null is returned on addAfter(0)');

        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild(9999999);

        $this->assertNull($newCategory, 'Not null is returned on addAfter(9999999)');

        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addAfter($categoryId);

        $this->assertNotNull($newCategory, 'Null is returned on addChild(' . $categoryId . '), object is expected');

        if (isset($newCategory)) {
            $this->assertObjectHasAttribute('category_id', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('lpos', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('rpos', $newCategory, 'Attribute not found');

            $this->assertEquals($newCategory->getLpos() + 1, $newCategory->getRpos(), 'rpos index must be greater than lpos index on 1 (' . $newCategory->getLpos() . ',' . $newCategory->getRpos() . ')');

            $this->assertGreaterThan(0, $newCategory->getCategoryId(), 'category_id value must be a positive number');

        } else {
            $this->markTestSkipped('Test skipped as valid category_id is not exists');
        }
    }

    /**
     * Test on addBefore() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddBefore()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    /**
     * Test on moveNode() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testMoveNode()
    {
        $this->markTestIncomplete('Test is incomplete');
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
    public function testGetCategoryFromHash(integer $categoryId = null)
    {
        if (!isset($categoryId)) {
            $categoryId = 14015;
        }

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryFromHash($categoryId);

        $this->assertNotNull($category, 'Null is returned on getCategoryFromHash(0), object is expected');

        $this->assertObjectHasAttribute('category_id', $category, 'Attribute not found');
        $this->assertEquals($categoryId, $category->getCategoryId(), 'Wrong category_id');

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryFromHash(99991002);

        $this->assertNull($category, 'Method did not return null on non-existing category_id');
    }

    public function testGetCategory()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testGetCategories()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testGetCategoriesPlainList()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testGetCategoryPath()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testGetParentCategory()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testGetParentCategoryId()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testIsCategoryLeafNode()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testGetCategoryByCleanUrl()
    {
        $this->markTestIncomplete('Test is incomplete');
    }

    public function testDeleteCategory()
    {
        $this->markTestIncomplete('Test is incomplete');
    }
}
