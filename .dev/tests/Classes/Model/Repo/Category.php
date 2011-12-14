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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Model_Repo_Category extends XLite_Tests_TestCase
{
    /**
     * setUp
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->doRestoreDb(__DIR__ . '/sql/category/setup.sql', false);
    }

    /**
     * tearDown
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function tearDown()
    {
        parent::tearDown();

        // Not needed right now
        $this->doRestoreDb();
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function testCreateQueryBuilder()
    {
        $qb = $this->getRepo()->createQueryBuilder();

        $this->assertInstanceOf(
            '\Doctrine\ORM\QueryBuilder',
            $qb,
            'Wrong type of returned object'
        );

        $this->assertEquals(
            'SELECT c, translations FROM XLite\Model\Category c LEFT JOIN c.translations'
            . ' translations WHERE c.category_id <> :rootId ORDER BY c.pos ASC, c.lpos ASC',
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
     * @since  1.0.0
     */
    public function testGetCategory()
    {
        $categoryId = 0;

        $this->assertNull(
            $this->getRepo()->getCategory($categoryId),
            'Category with ID ' . $categoryId . ' must not exist'
        );

        $categoryId = -10;

        $this->assertNull(
            $this->getRepo()->getCategory($categoryId),
            'Category with ID ' . $categoryId . ' must not exist'
        );

        $categoryId = 14015;

        $this->assertNotNull(
            $this->getRepo()->getCategory($categoryId),
            'The "Fruit" category (ID ' . $categoryId . ') was not found'
        );

        $this->assertEquals(
            $categoryId,
            $this->getRepo()->getCategory($categoryId)->getCategoryId(),
            'The "Fruit" category (ID ' . $categoryId . ') was not found (id)'
        );
    }

    /**
     * test
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetCategories()
    {
        foreach (array(1, null) as $rootId) {

            $tree = $this->getRepo()->getCategories($rootId);

            $this->assertInternalType(
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
     * @since  1.0.0
     */
    public function testGetSiblings()
    {
        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->find(14017);
        $list = $this->getRepo()->getSiblings($c);

        $this->assertInternalType(
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
     * @since  1.0.0
     */
    public function testGetSubcategories()
    {
        foreach (array(array(1, 2), array(14015, 3)) as $index => $data) {

            list($rootId, $count) = $data;
            $tree = $this->getRepo()->getSubcategories($rootId);

            $this->assertInternalType(
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
     * @since  1.0.0
     */
    public function testGetCategoryPath()
    {
        $categoryId = 14018;
        $path = $this->getRepo()->getCategoryPath($categoryId);

        $this->assertInternalType(
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

        $path = $this->getRepo()->getCategoryPath(9999999999); // Non-existing category

        $this->assertInternalType(
           'array',
            $path,
           'Category path must be of the "array" type'
        );

        $this->assertEquals(
            0,
            count($path),
            'Number of nodes in the path of non-existing category must be "0" (9999999999)'
        );
    }

    /**
     * test
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetCategoryDepth()
    {
        $categoryId = 14018;

        $this->assertEquals(
            1,
            $this->getRepo()->getCategoryDepth($categoryId),
            'Depth of the "Fruit" category subtree (ID ' . $categoryId . ')  must be "1"'
        );

        $categoryId = 9999999; // Non-existing category

        $this->assertEquals(
            0,
            $this->getRepo()->getCategoryDepth($categoryId),
            'Depth of non-existing category subtree (ID ' . $categoryId . ')  must be "0"'
        );
    }

    /**
     * test
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testFindAllByProductId()
    {
        $productId = 15090;
        $categories = $this->getRepo()->findAllByProductId($productId);

        $this->assertInternalType(
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
     * @since  1.0.0
     */
    public function testInsert()
    {
        $data = array(
            'enabled'   => false,
            'name'      => 'Test category',
            'cleanURL'  => 'test_cat',
        );
        $entity = $this->getRepo()->insert($data);
        $entity->setParent($this->getRepo()->find(14016));

        $this->assertInstanceOf(
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function testAddSubTreeCondition()
    {
        $qb = $this->getRepo()->createQueryBuilder();

        $result = clone $qb;
        $this->getRepo()->addSubTreeCondition($result, 14015);
        $expr = new Doctrine\ORM\Query\Expr\Andx(array('c.category_id <> :rootId', 'c.lpos BETWEEN 2 AND 9'));

        $this->assertEquals(
            $expr,
            $result->getDQLPart('where'),
            'Added subtree condition has a wrong set of tokens (case 1)'
        );

        $this->getRepo()->addSubTreeCondition($result, 14015, 'rpos', 10, 20);
        $expr->add('c.rpos BETWEEN 10 AND 20');

        $this->assertEquals(
            $expr,
            $result->getDQLPart('where'),
            'Added subtree condition has a wrong set of tokens (case 2)'
        );
    }
}
