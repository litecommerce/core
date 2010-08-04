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
     * Model\Category object's attributes  
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
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
     * setUpSql 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $setUpSql =<<<OUT
LOCK TABLES `xlite_categories` WRITE;
DELETE FROM `xlite_categories`;
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (1,1,18,1,0,0,0,1,1,'test-1');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (2,2,5,2,0,0,0,1,1,'test-2');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (3,3,4,3,0,0,0,1,1,'test-3');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (4,6,13,2,0,0,0,1,1,'test-4');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (5,7,8,3,0,0,0,1,1,'test-5');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (6,9,10,3,0,0,0,1,1,'test-6');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (7,11,12,3,0,0,0,1,1,'test-7');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (8,14,17,2,0,0,0,1,1,'test-8');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (9,15,16,3,0,0,0,1,1,'test-9');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (10,19,36,1,0,0,0,1,1,'test-10');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (11,20,23,2,0,0,0,1,1,'test-11');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (12,21,22,3,0,0,0,1,1,'test-12');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (13,24,29,2,0,0,0,1,1,'test-13');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (14,25,26,3,0,0,0,1,1,'test-14');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (15,27,28,3,0,0,0,1,1,'test-15');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (16,30,35,2,0,0,0,1,1,'test-16');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (17,31,32,3,0,0,0,1,1,'test-17');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (18,33,34,3,0,0,0,1,1,'test-18');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (19,37,38,1,0,0,0,1,1,'test-19');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (20,39,44,1,0,0,0,1,1,'test-20');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (21,40,41,2,0,0,0,1,1,'test-21');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (22,42,43,2,0,0,0,1,1,'test-22');
UNLOCK TABLES;

LOCK TABLES `xlite_category_images` WRITE;
DELETE FROM `xlite_category_images`;
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES ('',4,'demo_store_c3002.jpeg','image/jpeg',154,160,10267,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES ('',5,'demo_store_c1004.jpeg','image/jpeg',140,160,12860,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES ('',6,'demo_store_c4004.jpeg','image/jpeg',156,160,16022,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES ('',7,'demo_store_c1003.jpeg','image/jpeg',150,160,12662,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES ('',8,'demo_store_c4003.jpeg','image/jpeg',160,130,10698,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES ('',9,'demo_store_c4002.jpeg','image/jpeg',160,156,13711,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES ('',10,'demo_store_c1002.jpeg','image/jpeg',156,160,11592,1278412215,'');
UNLOCK TABLES;

LOCK TABLES `xlite_category_translations` WRITE;
DELETE FROM `xlite_category_translations`;
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',1,'Test #1','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',2,'Test #2','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',3,'Test #3','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',4,'Test #4','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',5,'Test #5','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',6,'Test #6','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',7,'Test #7','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',8,'Test #8','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',9,'Test #9','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',10,'Test #10','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',11,'Test #11','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',12,'Test #12','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',13,'Test #13','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',14,'Test #14','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',15,'Test #15','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',16,'Test #16','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',17,'Test #17','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',18,'Test #18','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',19,'Test #19','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',20,'Test #20','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',21,'Test #21','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES ('','en',22,'Test #22','','','','');
UNLOCK TABLES;

INSERT INTO `xlite_category_products` (`product_id`, `category_id`, `orderby`) VALUES (4002,4,10);
INSERT INTO `xlite_category_products` (`product_id`, `category_id`, `orderby`) VALUES (4004,4,20);
INSERT INTO `xlite_category_products` (`product_id`, `category_id`, `orderby`) VALUES (4009,4,30);
INSERT INTO `xlite_category_products` (`product_id`, `category_id`, `orderby`) VALUES (4030,8,10);
INSERT INTO `xlite_category_products` (`product_id`, `category_id`, `orderby`) VALUES (4016,4,40);
INSERT INTO `xlite_category_products` (`product_id`, `category_id`, `orderby`) VALUES (4006,8,20);
OUT;

    /**
     * restoreSql 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $restoreSql =<<<OUT
LOCK TABLES `xlite_categories` WRITE;
DELETE FROM `xlite_categories`;
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (3002,3,4,1,0,0,0,1,1,'downloadables');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (1004,5,14,1,0,0,0,1,1,'toys');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (4004,10,11,2,0,0,0,1,1,'puzzles');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (1003,6,7,2,0,0,0,1,1,'cube-goodies');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (4003,8,9,2,0,0,0,1,1,'science-toys');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (4002,12,13,2,0,0,0,1,1,'rc-toys');
INSERT INTO `xlite_categories` (`category_id`, `lpos`, `rpos`, `depth`, `views_stats`, `locked`, `membership_id`, `threshold_bestsellers`, `enabled`, `clean_url`) VALUES (1002,1,2,1,0,0,0,1,1,'apparel');
UNLOCK TABLES;

LOCK TABLES `xlite_category_images` WRITE;
DELETE FROM `xlite_category_images`;
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES (101,3002,'demo_store_c3002.jpeg','image/jpeg',154,160,10267,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES (102,1004,'demo_store_c1004.jpeg','image/jpeg',140,160,12860,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES (103,4004,'demo_store_c4004.jpeg','image/jpeg',156,160,16022,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES (104,1003,'demo_store_c1003.jpeg','image/jpeg',150,160,12662,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES (105,4003,'demo_store_c4003.jpeg','image/jpeg',160,130,10698,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES (106,4002,'demo_store_c4002.jpeg','image/jpeg',160,156,13711,1278412215,'');
INSERT INTO `xlite_category_images` (`image_id`, `id`, `path`, `mime`, `width`, `height`, `size`, `date`, `hash`) VALUES (107,1002,'demo_store_c1002.jpeg','image/jpeg',156,160,11592,1278412215,'');
UNLOCK TABLES;

LOCK TABLES `xlite_category_translations` WRITE;
DELETE FROM `xlite_category_translations`;
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES (101,'en',3002,'Downloadables','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES (102,'en',1004,'Toys','Category-2','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES (103,'en',4004,'Puzzles','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES (104,'en',1003,'Cube Goodies','Category-child','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES (105,'en',4003,'Science Toys','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES (106,'en',4002,'RC Toys','','','','');
INSERT INTO `xlite_category_translations` (`label_id`, `code`, `id`, `name`, `description`, `meta_tags`, `meta_desc`, `meta_title`) VALUES (107,'en',1002,'Apparel','Category-1','','','');
UNLOCK TABLES;

DELETE FROM `xlite_category_products` WHERE `category_id` IN (4,8);
OUT;

    /**
     * setUp 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->query($this->setUpSql);

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * tearDown 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->query($this->restoreSql);

        \XLite\Core\Database::getEM()->flush();
    }

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
    }

    /**
     * Test on getCategoryFromHash() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoryFromHash()
    {
        $categoryId = 4;

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
    public function testGetCategory()
    {
        $categoryId = 4;

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
        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories(1);

        $this->assertTrue(is_array($categories), 'getCategories(1) must return an array');

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
        $categoryId = 10;
        $etalonCategoriesCount = 3;
        $etalonChildren = array(11, 13, 16);

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoriesPlainList($categoryId);

        $this->assertTrue(is_array($categories) && !empty($categories), 'getCategoriesPlainList(' . $categoryId . ') must return a non-empty array');

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $categories[0], 'Attribute {' . $attr . '} not found');
        }

        $children = array();

        foreach ($categories as $category) {
            $catId = $category->getCategoryId();
            $parentId = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($catId);
            $this->assertEquals($categoryId, $parentId, 'Parent category of #' . $catId . ' does not match (#' . $parentId . ' instead of #' . $categoryId . ')');
            $children[] = $catId;
        }

        $this->assertEquals($etalonChildren, $children, 'Wrong subcategories list of #' . $categoryId);

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
        $categoryId = 18;

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
        $categoryId = 15;
        $etalonParentCategoryId = 13;
        $rootLevelCategoryId = 10;

        $parentCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategory($categoryId);

        $this->assertNotNull($parentCategory, 'Parent category of the category #16 must return object');

        foreach ($this->attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $parentCategory, 'Attribute {' . $attr . '} not found');
        }

        $parentCategoryId = $parentCategory->getCategoryId();
        $this->assertEquals($etalonParentCategoryId, $parentCategoryId, 'Wrong parent of #' . $categoryId . ' returned (#' . $parentCategoryId . ')');

        // Test #2: Get parent of the category from root level
        $parentCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategory($rootLevelCategoryId);

        $this->assertNull($parentCategory, 'Parent category of #' . $rootLevelCategoryId . ' must be null');

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
        $categoryId = 15;
        $etalonParentCategoryId = 13;
        $rootLevelCategoryId = 10;

        $parentCategoryId = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($categoryId);

        $this->assertEquals($etalonParentCategoryId, $parentCategoryId, 'Wrong parent of #' . $categoryId . ' returned (#' . $parentCategoryId . ')');

        // Test #2: Get parent Id for the category from root level
        $parentCategoryId = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($rootLevelCategoryId);

        $this->assertNull($parentCategoryId, 'Parent category id of #' . $rootLevelCategoryId . ' must be null');

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
        // Test #1: category #9 is a leaf node
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->isCategoryLeafNode(9);

        $this->assertTrue($result, 'Category #9 is a leaf node, true expected');

        // Test #2: category #4 is not a leaf node
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->isCategoryLeafNode(4);

        $this->assertFalse($result, 'Category #4 is not a leaf node, false expected');

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
        $cleanUrl = 'test-4';
        $categoryId = 4;

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
     * Test on addChild() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddChild()
    {
        // Test on add as a child of unexisting category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild(9999999);

        $this->assertNull($newCategory, 'Not null is returned on addAfter(9999999)');

        // Test on add as a child of category #19
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild(19);

        $this->assertNotNull($newCategory, 'Null is returned on addChild(19), object is expected');

        if (isset($newCategory)) {
            $this->assertObjectHasAttribute('category_id', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('lpos', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('rpos', $newCategory, 'Attribute not found');

            $this->assertGreaterThan(0, $newCategory->getCategoryId(), 'category_id value must be a positive number');

            $this->assertEquals(array(38, 39), array($newCategory->getLpos(), $newCategory->getRpos()), 'lpos and rpos indexes are wrong');
        }

        // Test on add as a child of root category
        $newCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild(0);

        $this->assertNotNull($newCategory, 'Null is returned on addChild(0), object is expected');

        if (isset($newCategory)) {
            $this->assertObjectHasAttribute('category_id', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('lpos', $newCategory, 'Attribute not found');
            $this->assertObjectHasAttribute('rpos', $newCategory, 'Attribute not found');

            $this->assertGreaterThan(0, $newCategory->getCategoryId(), 'category_id value must be a positive number');

            $this->assertEquals(array(1, 2), array($newCategory->getLpos(), $newCategory->getRpos()), 'lpos and rpos indexes are wrong');
        }
    }

    /**
     * Test on addSibling() method
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddSibling()
    {
        $categoryId = 4;

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
        $msg = 'Test on left-to-right moving failed: ';
        $categoryId = 4;
        $destCategoryId = 13;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, false);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));


        // Test on right-to-left moving: place Downloadables after Apparel
        $msg = 'Test on right-to-left moving failed: ';
        $categoryId = 16;
        $destCategoryId = 2;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, false);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));

        // Test on right-to-left moving and attach as a child: attach Cube Goodies to Downloadables
        $msg = 'Test on right-to-left moving and attach as a child failed: ';
        $categoryId = 4;
        $destCategoryId = 9;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, true);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));

        // Test on left-to-right moving and attach as a child: attach Cube Goodies to Toys
        $msg = 'Test on left-to-right moving and attach as a child failed: ';
        $categoryId = 16;
        $destCategoryId = 14;
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $destCategoryId, true);

        $this->assertTrue($result, $msg . 'method returned false');

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Category')->checkTreeIntegrity($err), $msg . 'integrity broken' . $this->array2string($err, 2));
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
        // TODO: Checking that all associated data are removed didn't work (translation, images, category_products relations)
       $this->markTestIncomplete();

        // Test #1: Delete unexisting category
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->deleteCategory(9999999);

        $this->assertFalse($result, 'Deletion of unexisting category must return false');


        // Test #2: Delete specified category and all its subcategories
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->deleteCategory(1);

        $this->assertTrue($result, 'Deletion of category #1 must return true');

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories(1);

        $this->assertEquals(array(), $categories, 'Deletion of category #1 must delete this category and all its subcategories');

        // Test if translation data were also removed (check at subcategory)
        $result = \XLite\Core\Database::getRepo('XLite\Model\CategoryTranslation')->findOneById(4);

        $this->assertNull($result, 'Text data were not removed after category #4 removing');

        // Test if image data were also removed (check at subcategory)
        $result = \XLite\Core\Database::getRepo('XLite\Model\Image\Category\Image')->findOneById(4);

        $this->assertNull($result, 'Category image was not removed after category #4 removing');

        // Test if related products were also removed (check at subcategory)
        $result = \XLite\Core\Database::getRepo('XLite\Model\CategoryProducts')->findOneByCategoryId(4);

        $this->assertNull($result, 'Products assigned to the category were not removed after category #4 removing');


        // Test #3: Delete only subcategories of the specified category
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->deleteCategory(10, true);

        $this->assertTrue($result, 'Deletion of category #10 must return true');

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories(10);

        $this->assertEquals(1, count($categories), 'Deletion of category #10 must delete all its subcategories');


        // Test #4: Delete all categories
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->deleteCategory(0);

        $this->assertTrue($result, 'Deletion of category #0 must return true');

        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories();

        $this->assertEquals(array(), $categories, 'Deletion of category #0 must delete all categories');
    }
}
