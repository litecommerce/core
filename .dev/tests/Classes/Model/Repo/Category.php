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

        $this->query(file_get_contents(__DIR__ . '/sql/category/setup.sql'));
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

        // Not needed right now
        // $this->query(file_get_contents(__DIR__ . '/sql/category/restore.sql'));
        // \XLite\Core\Database::getEM()->flush();
    }

    public function testDump()
    {
        // TODO - remove after tests reworked
    }

    /**
     * getRepo 
     * 
     * @return \XLite\Model\Repo\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRepo()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category');
    }


    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetRootCategoryId()
    {
        $this->assertSame(
            \XLite\Model\Repo\Category::CATEGORY_ID_ROOT,
            $this->getRepo()->getRootCategoryId(),
            'Invalid root category ID returned'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetEnabledCondition()
    {
        $this->assertSame(
            false,
            $this->getRepo()->getEnabledCondition(),
            'For UNIT tests the "category enabled" condition must be false (emulate admin area)'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCreateQueryBuilder()
    {
        $qb = $this->getRepo()->createQueryBuilder();

        $this->assertType(
            '\Doctrine\ORM\QueryBuilder',
            $qb,
            'Wrong type of returned object'
        );

        $this->assertEquals(
            'SELECT c, translations FROM XLite\Model\Category c LEFT JOIN c.translations' 
            . ' translations WHERE c.category_id <> :rootId ORDER BY c.lpos ASC',
            $qb->getDQL(),
            'Generated a wrong DQL'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategory()
    {
        $categoryId = 0;

        $this->assertNull(
            $this->getRepo()->getCategory($categoryId),
            'Category with ID ' . $categoryId . ' must not exist'
        );

        $categoryId = 14015;

        $this->assertEquals(
            $categoryId,
            $this->getRepo()->getCategory($categoryId)->getCategoryId(),
            'The "Fruit" category (ID ' . $categoryId . ') was not found'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategories()
    {
        foreach (array(1, null) as $rootId) {

            $tree = $this->getRepo()->getCategories($rootId);

            $this->assertType(
                'array',
                $tree,
                'List of categories must be of the "array" type'
            );

            $this->assertEquals(
                6,
                count($tree),
                'Number of fetched categories must be "6"'
            );
        }
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetSiblings()
    {
        $list = $this->getRepo()->getSiblings(14017);

        $this->assertType(
            'array',
            $list,
            'List of categories must be of the "array" type'
        );

        $this->assertEquals(
            2,
            count($list),
            'Number of fetched categories must be "2"'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetSubcategories()
    {
        foreach (array(array(1, 2), array(14015, 3)) as $index => $data) {

            list($rootId, $count) = $data;
            $tree = $this->getRepo()->getSubcategories($rootId);

            $this->assertType(
                'array',
                $tree,
                'List of subcategories must be of the "array" type (case ' . ($index + 1) . ')'
            );

            $this->assertEquals(
                $count,
                count($tree),
                'Number of fetched subcategories must be "' . $count . '" (case ' . ($index + 1) . ')'
            );
        }
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoryPath()
    {
        $categoryId = 14018;
        $path = $this->getRepo()->getCategoryPath($categoryId);

        $this->assertType(
           'array',
            $path,
           'Category path must be of the "array" type'
        );

        $this->assertEquals(
            2,
            count($path),
            'Number of nodes in the path of "Fruit" category (ID ' . $categoryId . ')  must be "2"'
        );

        foreach (array(14015, 14018) as $index => $categoryId) {

            $this->assertEquals(
                $categoryId,
                $path[$index]->getCategoryId(),
                'Category ID of the #' . $index . ' node in the path of "Fruit" category (ID ' 
                . $categoryId . ')  must be "' . $categoryId . '"'
            );
        }
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCategoryDepth()
    {
        $categoryId = 14018;

        $this->assertEquals(
            1,
            $this->getRepo()->getCategoryDepth($categoryId),
            'Depth of the "Fruit" category subtree (ID ' . $categoryId . ')  must be "1"'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindAllByProductId()
    {
        $productId = 15090;
        $categories = $this->getRepo()->findAllByProductId($productId);

        $this->assertType(
           'array',
            $categories,
           'List of product categories must be of the "array" type'
        );

        $this->assertEquals(
            3,
            count($categories),
            'Number of product categories for the "Apple" product (ID ' . $productId . ')  must be "3"'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testInsert()
    {
        $data = array(
            'parent_id' => 14016,
            'enabled'   => false,
            'name'      => 'Test category',
            'cleanUrl'  => 'test_cat',
        );
        $entity = $this->getRepo()->insert($data);

        $this->assertType(
            '\XLite\Model\Category',
            $entity,
            'Created entity must be of the "\XLite\Model\Category" type'
        );

        foreach ($data as $name => $value) {

            $this->assertEquals(
                $value,
                $entity->{'get' . \XLite\Core\Converter::convertToCamelCase($name)}(),
                'The "' . $name . '" property of the created entity must be equal to "' . $value . '"'
            );
        }
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testDeleteSubcategories()
    {
        $categoryId = 14015;
        $entity = $this->getRepo()->getCategory($categoryId);

        $this->assertEquals(
            3,
            $entity->getSubcategoriesCount(),
            'Subcategories number in the "Fruit" category subtree (ID ' . $categoryId . ') must be "3"'
        );

        $this->getRepo()->deleteCategory($categoryId, true);

        $this->assertEquals(
            0,
            $entity->getSubcategoriesCount(),
            'Some subcategories of the "Fruit" category subtree (ID ' . $categoryId . ') were not removed'
        );
    }

    /**
     * test 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddSubTreeCondition()
    {
        $qb = $this->getRepo()->createQueryBuilder();

        $this->getRepo()->addSubTreeCondition($result = clone $qb, 14015);
        $expr = new Doctrine\ORM\Query\Expr\Andx(array('c.category_id <> :rootId', 'c.lpos BETWEEN 2 AND 9'));

        $this->assertEquals(
            $expr,
            $result->getDQLPart('where'),
            'Added subtree condition has a wrong set of tokens (case 1)'
        );

        $this->getRepo()->addSubTreeCondition($result = clone $qb, 14015, 'rpos', 10, 20);
        $expr->add('c.rpos BETWEEN 10 AND 20');

        $this->assertEquals(
            $expr,
            $result->getDQLPart('where'),
            'Added subtree condition has a wrong set of tokens (case 2)'
        );
    }
}
