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
    protected $attributes = array(
        'category_id',
        'lpos',
        'rpos',
        'enabled',
        'views_stats',
        'locked',
        'membership_id',
        'threshold_bestsellers',
        'clean_url',
        'products_count',
        'depth',
        'category_products'
    );

    /**
     * Convert array to string 
     * 
     * @param array $err    Array
     * @param int   $offset Offset (counter number for repeating indentation string for each level within array)
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function array2string($err, $offset)
    {
        $result = array();
        foreach ($err as $k => $v) {
            if (is_array($v)) {
                $v = $this->array2string($v, $offset + 2);
            }
            $result[] = str_repeat(' ', $offset) . "[$k] = '$v'";
        }
        return "\n" . implode("\n", $result);
    }

    /**
     * Test on cleanCache() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCleanCache()
    {
        if (\XLite\Core\Database::isCacheEnabled()) {
            
            $keys = array(
                '_Details',
                '_FullTree',
                '_FullTreeHash',
                '_NodePath',
                '_LeafNodes',
                '_MaxRightPos'
            );

            $cachePrefix = 'Category';

            $cellPrefix = 'XLite_Model_Category.Category';
            $cellSuffix = '.testcase';

            $cacheDriver = \XLite\Core\Database::getCacheDriver();

            // Save test data into the cache
            foreach ($keys as $key) {
                $cacheDriver->save($cellPrefix . $key . $cellSuffix, array('test'));
            }

            // Clean cache
            \XLite\Core\Database::getRepo('XLite\Model\Category')->cleanCache();

            // Check if data were removed from the cache
            $result = array();
            foreach ($keys as $key) {
                if ($cacheDriver->contains($cellPrefix . $key . $cellSuffix)) {
                    $result[] = $key;
                }
            }

            $this->assertTrue(empty($result), 'The following cache cells left: ' . implode(', ', $result));

        } else { // Cache is disabled
            $this->markTestSkipped();
        }
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

        // Test on add as a child of unexisting category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild(9999999);

        $this->assertNull($newCategory, 'Not null is returned on addAfter(9999999)');

        // Test on add as a child of root category
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
     * Test on addSibling() method
     * 
     * @depends testAddChild
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddSibling(integer $categoryId = null)
    {
        if (!isset($categoryId)) {
            $categoryId = 1002;
        }

        // Test of add after root category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addSibling(0, false);

        $this->assertNull($newCategory, 'Not null is returned on add after #0');

        // Test of add after unexisting category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addSibling(9999999, false);

        $this->assertNull($newCategory, 'Not null is returned on add after #9999999');

        // Test of add after existing category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addSibling($categoryId, false);

        $this->assertNotNull($newCategory, 'Null is returned on add after #' . $categoryId . ', object is expected');

        if (isset($newCategory)) {
            $this->assertObjectHasAttribute('category_id', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('lpos', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('rpos', $newCategory, 'Attribute not found');

            $this->assertEquals($newCategory->getLpos() + 1, $newCategory->getRpos(), 'rpos index must be greater than lpos index on 1 (' . $newCategory->getLpos() . ',' . $newCategory->getRpos() . ')');

            $this->assertGreaterThan(0, $newCategory->getCategoryId(), 'category_id value must be a positive number');

        } else {
            $this->assertTrue(false, 'Test failed as valid category #' . $categoryId . ' is not exists');
        }


        // Test of add before root category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addSibling(0);

        $this->assertNull($newCategory, 'Not null is returned on add before #0');

        // Test of add before unexisting category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addSibling(9999999);

        $this->assertNull($newCategory, 'Not null is returned on add before #9999999');

        // Test of add before existing category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addSibling($categoryId);

        $this->assertNotNull($newCategory, 'Null is returned on add before #' . $categoryId . ', object is expected');

        if (isset($newCategory)) {
            $this->assertObjectHasAttribute('category_id', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('lpos', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('rpos', $newCategory, 'Attribute not found');

            $this->assertEquals($newCategory->getLpos() + 1, $newCategory->getRpos(), 'rpos index must be greater than lpos index on 1 (' . $newCategory->getLpos() . ',' . $newCategory->getRpos() . ')');

            $this->assertGreaterThan(0, $newCategory->getCategoryId(), 'category_id value must be a positive number');

        } else {
            $this->assertTrue(false, 'Test failed as valid category #' . $categoryId . ' is not exists');
        }
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
        // Test on left-to-right moving: place Downloadables after Science Toys
        $msg = 'Test on left-to-right moving (place Downloadables after Science Toys) failed: ';
        $categoryId = 3002;
        $destCategoryId = 4003;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, false);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));


        // Test on right-to-left moving: place Downloadables after Apparel
        $msg = 'Test on right-to-left moving (place Downloadables after Apparel) failed: ';
        $categoryId = 3002;
        $destCategoryId = 1002;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, false);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));

        // Test on right-to-left moving and attach as a child: attach Cube Goodies to Downloadables
        $msg = 'Test on right-to-left moving and attach as a child (attach Cube Goodies to Downloadables) failed: ';
        $categoryId = 1003;
        $destCategoryId = 3002;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, true);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));

        // Test on left-to-right moving and attach as a child: attach Cube Goodies to Toys
        $msg = 'Test on left-to-right moving and attach as a child (attach Cube Goodies to Toys) failed: ';
        $categoryId = 1003;
        $destCategoryId = 1004;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, true);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));
    }

    /**
     * Test on getCategoryFromHash() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoryFromHash(integer $categoryId = null)
    {
        if (!isset($categoryId)) {
            $categoryId = 1002;
        }

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryFromHash($categoryId);

        $this->assertNotNull($category, 'Null is returned on getCategoryFromHash(' . $categoryId . '), object is expected');

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $category, 'Attribute {' . $attr . '} not found');
        }

        $this->assertEquals($categoryId, $category->getCategoryId(), 'Wrong category_id');

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryFromHash(99991002);

        $this->assertNull($category, 'Method did not return null on non-existing category_id');
    }

    /**
     * Test on getCategory() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategory(integer $categoryId = null)
    {
        if (!isset($categoryId)) {
            $categoryId = 1002;
        }

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory($categoryId);

        $this->assertNotNull($category, 'Null is returned on getCategoryFromHash(' . $categoryId . '), object is expected');

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $category, 'Attribute {' . $attr . '} not found');
        }

        $this->assertEquals($categoryId, $category->getCategoryId(), 'Wrong category_id');

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory(99991002);

        $this->assertNull($category, 'Method did not return null on non-existing category_id');

    }

    /**
     * Test on getCategories() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategories()
    {
        // Test on gathering of full categories tree
        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories();

        $this->assertTrue(is_array($categories) && !empty($categories), 'getCategories() must return a non-empty array');

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $categories[0], 'Attribute {' . $attr . '} not found');
        }

        // Test on gathering of subcategories
        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories(1004);

        $this->assertTrue(is_array($categories), 'getCategories(1004) must return an array');

        // Test on gathering of subcategories of unexisting category
        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories(99991002);

        $this->assertTrue(is_array($categories) && empty($categories), 'getCategories() must return an empty array if category with specified category_id does not exist');
    }

    /**
     * Test on getCategoriesPlainList() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoriesPlainList()
    {
        // Test #1: get plain list of subcategories of category #1004
        $categoryId = 1004;

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoriesPlainList($categoryId);

        $this->assertTrue(is_array($categories) && !empty($categories), 'getCategoriesPlainList(' . $categoryId . ') must return a non-empty array');

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $categories[0], 'Attribute {' . $attr . '} not found');
        }

        $parents = array();
        foreach ($categories as $category) {
            $parents[] = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($category->getCategoryId());
        }

        $parents = array_unique($parents);

        $this->assertEquals($categoryId, $parents[0], 'Parent category_id does not match');

        $this->assertEquals(1, count($parents), 'In the plain list of subcategories all categories must have a same parent' . $this->array2string($parents, 2));

        // Test #2: get plain list of subcategories of unexisting category
        $categoryId = 99991002;

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoriesPlainList($categoryId);

        $this->assertTrue(is_array($categories) && empty($categories), 'getCategoriesPlainList(' . $categoryId . ') must return an empty array for unexisting category');

    }

    /**
     * Test on getCategoryPath() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoryPath()
    {
        // Test #1: get category path for #4002
        $categoryId = 4002;

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryPath($categoryId);

        $this->assertTrue(is_array($categories) && !empty($categories), 'getCategoryPath(' . $categoryId . ') must return a non-empty array');

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $categories[0], 'Attribute {' . $attr . '} not found');
        }

        // Nodes must be sorted from root parent to the last child: ..., parent, child, ...
        for ($i = 0; $i < count($categories) - 1; $i++) {
            $parentId = $categories[$i]->getCategoryId();
            $childId = $categories[$i + 1]->getCategoryId();
            $parentIdOfChild = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($childId);
            $this->assertEquals($parentId, $parentIdOfChild, 'Category #' . $parentId . ' is expected to be a parent of #' . $childId);
        }

        // Test #2: get category path for unexisting category
        $categoryId = 99991002;

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryPath($categoryId);

        $this->assertTrue(is_array($categories) && empty($categories), 'getCategoryPath(' . $categoryId . ') must return an empty array for unexisting category');
    }

    /**
     * Test on getParentCategory() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetParentCategory()
    {
        // Test #1: Get parent of existing category
        $categoryId = 4002;
        $etalonParentCategoryId = 1004;

        $parentCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategory($categoryId);

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $parentCategory, 'Attribute {' . $attr . '} not found');
        }

        $this->assertEquals($etalonParentCategoryId, $parentCategory->getCategoryId(), 'Wrong parent category returned');

        // Test #2: Get parent of the category from root level
        $parentCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategory($etalonParentCategoryId);

        $this->assertNull($parentCategory, 'Parent category of the category from the root level must return null');

        // Test #3: Get parent of unexisting category
        $parentCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategory(99991002);

        $this->assertNull($parentCategory, 'Parent category of unexisting category must return null');
    }

    /**
     * Test on getParentCategoryId() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetParentCategoryId()
    {
        // Test #1: Get parent Id for existing category
        $categoryId = 4002;
        $etalonParentCategoryId = 1004;

        $parentCategoryId = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($categoryId);

        $this->assertEquals($etalonParentCategoryId, $parentCategoryId, 'Wrong parent category returned');

        // Test #2: Get parent Id for the category from root level
        $parentCategoryId = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($etalonParentCategoryId);

        $this->assertNull($parentCategoryId, 'Parent category id of #' . $etalonParentCategoryId . ' must be null');

        // Test #3: Get parent Id for unexisting category
        $parentCategoryId = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId(99991002);

        $this->assertNull($parentCategoryId, 'Parent category id did not return null for unexisting category');
    }

    /**
     * Test on isCategoryLeafNode() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsCategoryLeafNode()
    {
        // Test #1: category #4001 is a leaf node
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->isCategoryLeafNode(4002);

        $this->assertTrue($result, 'Category #4002 is a leaf node, true expected');

        // Test #2: category #1004 is not a leaf node
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->isCategoryLeafNode(1004);

        $this->assertFalse($result, 'Category #1004 is not a leaf node, false expected');

        // Test #3: category #99991002 is not exists
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->isCategoryLeafNode(99991002);

        $this->assertFalse($result, 'Category #99991002 is not exists, false expected');
    }

    /**
     * Test on getCategoryByCleanUrl() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoryByCleanUrl()
    {
        // Test #1: Get existing category by clean URL
        $cleanUrl = 'puzzles';
        $categoryId = 4004;

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryByCleanUrl($cleanUrl);

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $category, 'Attribute {' . $attr . '} not found');
        }

        $this->assertEquals($categoryId, $category->getCategoryId(), 'Category #' . $categoryId . ' expected on clean URL ' . $cleanUrl);

        // Test #2: Get category by unexisting clean URL
        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryByCleanUrl('some-unexisting-clean-url');

        $this->assertNull($category, 'Null is expected on unexisting clean URL');
    }

    /**
     * Test on deleteCategory() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testDeleteCategory()
    {
        $this->markTestIncomplete('Test is incomplete');

        // TODO: Check that all associated data are removed also: translation, images, category_products relations

        // Test #1: Delete specified category and all its subcategories

        // Test #2: Delete only subcategories of the specified category

        // Test #3: Delete all categories

    }
}
