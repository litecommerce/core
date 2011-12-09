<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Category class tests
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
 *
 * @resource category
 * @resource product
 */

class XLite_Tests_Model_Category extends XLite_Tests_TestCase
{
    protected $categoryData = array(
        'name'        => 'test category',
        'description' => 'test description',
        'meta_tags'   => 'test meta tags',
        'meta_desc'   => 'test meta description',
        'meta_title'  => 'test meta title',
        'lpos'        => 100,
        'rpos'        => 200,
        'enabled'     => true,
        'cleanURL'   => 'testCategory',
        'show_title'  => true,
    );

    /**
     * testCreate
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCreate()
    {
        $c = new \XLite\Model\Category();

        foreach ($this->categoryData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $c->$setterMethod($testValue);
            $value = $c->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }

        $qf = new \XLite\Model\Category\QuickFlags;
        $qf->setCategory($c);
        $c->setQuickFlags($qf);
        $this->assertEquals($qf, $c->getQuickFlags(), 'test quick flags');

        $m = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
        $c->setMembership($m[0]);
        $this->assertEquals($m[0], $c->getMembership(), 'check membership');

        $i = new \XLite\Model\Image\Category\Image;
        $c->setImage($i);
        $this->assertEquals($i, $c->getImage(), 'check image');

        $cp = new \XLite\Model\CategoryProducts;
        $c->addCategoryProducts($cp);
        $this->assertEquals($cp, $c->getCategoryProducts()->get(0), 'check category products');
        $this->assertEquals(1, count($c->getCategoryProducts()), 'check category products length');

        $child = new \XLite\Model\Category;
        $c->addChildren($child);
        $child->setParent($c);
        $this->assertEquals($child, $c->getChildren()->get(0), 'check childs');
        $this->assertEquals(1, count($c->getChildren()), 'check childs length');
        $this->assertEquals($c, $child->getParent(), 'check parent');
    }

    public function testSetParent()
    {
        $c = new \XLite\Model\Category();
        $c->map($this->categoryData);

        $child = new \XLite\Model\Category;
        $c->addChildren($child);
        $child->setParent($c);

        $this->assertEquals($c, $child->getParent(), 'check parent');

        $child->setParent(null);

        $this->assertNull($child->getParent(), 'check empty parent');
    }

    public function testHasImage()
    {
        $c = new \XLite\Model\Category();
        $c->map($this->categoryData);

        $this->assertFalse($c->hasImage(), 'check image');


        $i = new \XLite\Model\Image\Category\Image;
        $c->setImage($i);
        $this->assertTrue($c->hasImage(), 'check image #2');

        $c->setImage(null);
        $this->assertFalse($c->hasImage(), 'check image #3');
    }

    public function testGetSubCategoriesCount()
    {
        $c = new \XLite\Model\Category();
        $c->map($this->categoryData);

        $qf = new \XLite\Model\Category\QuickFlags;
        $qf->setCategory($c);
        $c->setQuickFlags($qf);
        $this->assertEquals($qf, $c->getQuickFlags(), 'test quick flags');

        $qf->setSubcategoriesCountEnabled(1);
        $qf->setSubcategoriesCountAll(2);

        $this->assertEquals(2, $c->getSubCategoriesCount(), 'check count');
    }

    public function testHasSubcategories()
    {
        $c = new \XLite\Model\Category();
        $c->map($this->categoryData);

        $this->assertFalse($c->hasSubcategories(), 'check childs #1');

        $child = new \XLite\Model\Category;
        $c->addChildren($child);
        $child->setParent($c);

        $this->assertFalse($c->hasSubcategories(), 'check childs #2');

        $qf = new \XLite\Model\Category\QuickFlags;
        $qf->setCategory($c);
        $c->setQuickFlags($qf);
        $this->assertEquals($qf, $c->getQuickFlags(), 'test quick flags');

        $qf->setSubcategoriesCountEnabled(1);
        $qf->setSubcategoriesCountAll(2);

        $this->assertTrue($c->hasSubcategories(), 'check childs #3');
    }

    public function testGetSubcategories()
    {
        $c = new \XLite\Model\Category();
        $c->map($this->categoryData);

        $this->assertEquals(0, count($c->getSubcategories()), 'check empty childs');

        $child = new \XLite\Model\Category;
        $c->addChildren($child);
        $child->setParent($c);

        $this->assertEquals(1, count($c->getSubcategories()), 'check childs length');
    }

    public function testGetSiblings()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Category')->find(1);

        $c1 = new \XLite\Model\Category();
        $c1->map($this->categoryData);
        $p->addChildren($c1);

        $c2 = new \XLite\Model\Category();
        $c2->map($this->categoryData);
        $p->addChildren($c2);

        $em = \XLite\Core\Database::getEM();
        $em->flush();

        $found = false;
        foreach ($c1->getSiblings() as $s) {
            if ($s->getCategoryId() == $c2->getCategoryId()) {
                $found = true;
            }
        }

        $this->assertTrue($found, 'sibling category is not found');
        $em->remove($c1);
        $em->remove($c2);
        $em->flush();

    }

    public function testGetStringPath()
    {
        $this->doRestoreDb(__DIR__ . '/Repo/sql/category/setup.sql', false);

        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanURL' => 'fruit_1'));
        $this->assertEquals('Fruit/Fruit 2', $c->getStringPath());
    }

    public function testGetProductsCount()
    {
        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanURL' => 'fruit'));

        $this->assertEquals(6, $c->getProductsCount(), 'check products count (fruit)');
    }

    public function testGetProducts()
    {
        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanURL' => 'fruit'));

        $list = $c->getProducts();

        $this->assertEquals(15067, $list[0]->getProductId(), 'check product id (#15067)');
        $this->assertEquals(15068, $list[1]->getProductId(), 'check product id (#15068)');
        $this->assertEquals(15090, $list[2]->getProductId(), 'check product id (#15090)');
        $this->assertEquals(15091, $list[3]->getProductId(), 'check product id (#15091)');
        $this->assertEquals(15121, $list[4]->getProductId(), 'check product id (#15121)');
        $this->assertEquals(15123, $list[5]->getProductId(), 'check product id (#15123)');
    }
}
