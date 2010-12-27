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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_CategoryProducts extends XLite_Tests_TestCase
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
        'cleanUrl'   => 'testCategory',
        'show_title'  => true,
    );

    /**
     * testCreate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCreate()
    {
        $this->query(file_get_contents(__DIR__ . '/Repo/sql/category/setup.sql'));

        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->find(14015);
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(16281);

        $cp = new \XLite\Model\CategoryProducts;
        $c->addCategoryProducts($cp);
        $cp->setCategory($c);
        $cp->setProduct($p);
        $cp->setOrderby(100);

        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(14015, $cp->getCategory()->getCategoryId(), 'check category id');
        $this->assertEquals(16281, $cp->getProduct()->getProductId(), 'check product id');
        $this->assertEquals(100, $cp->getOrderby(), 'check order');
    }
}
