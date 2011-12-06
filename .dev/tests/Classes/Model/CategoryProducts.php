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
 */

class XLite_Tests_Model_CategoryProducts extends XLite_Tests_TestCase
{
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
        $this->doRestoreDb(__DIR__ . '/Repo/sql/category/setup.sql', false);

        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanURL' => 'fruit'));
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00007'));

        $cp = new \XLite\Model\CategoryProducts;
        $c->addCategoryProducts($cp);
        $cp->setCategory($c);
        $cp->setProduct($p);
        $cp->setOrderby(100);

        $em = \XLite\Core\Database::getEM();
        $em->flush();

        $this->assertEquals(14015, $cp->getCategory()->getCategoryId(), 'check category id');
        $this->assertEquals(16281, $cp->getProduct()->getProductId(), 'check product id');
        $this->assertEquals(100, $cp->getOrderby(), 'check order');

        $this->assertTrue(0 < $cp->getId(), 'check id');
        $em->remove($cp);
        $em->flush();
    }
}
