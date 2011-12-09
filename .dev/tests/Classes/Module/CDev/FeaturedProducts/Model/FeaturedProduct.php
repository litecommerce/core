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
 * @package    XLite
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Module_CDev_FeaturedProducts_Model_FeaturedProduct extends XLite_Tests_TestCase
{
    protected $product;

    protected $category;

    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();
    }

    public function testCreate()
    {
        $fp = $this->getTestFeaturedProduct();

        $this->assertTrue(0 < $fp->getId(), 'Check featured product link id');
        $this->assertEquals($this->getProduct()->getProductId(), $fp->getProduct()->getProductId(), 'Check product id');
        $this->assertEquals($this->getCategory()->getCategoryId(), $fp->getCategory()->getCategoryId(), 'Check category id');

        $allFeatured = \XLite\Core\Database::getRepo('XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
            ->getFeaturedProducts($this->category->getCategoryId());

        $this->assertEquals(1, count($allFeatured), 'Check featured products count');
    }

    public function testUpdate()
    {
        $fp = $this->getTestFeaturedProduct();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findFrame(2, 1);
        $someOtherProduct = array_shift($list);
        foreach ($list as $p) {
            $p->detach();
        }
        $fp->setProduct($someOtherProduct);

        $list = \XLite\Core\Database::getRepo('XLite\Model\Category')->findFrame(2, 1);

        $someOtherCategory = array_shift($list);
        foreach ($list as $c) {
            $c->detach();
        }

        // Clean existing featured links
        $links = \XLite\Core\Database::getRepo('XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
           ->getFeaturedProducts($someOtherCategory->getCategoryId());

        if ($links) {
           foreach ($links as $fl) {
               \XLite\Core\Database::getEM()->remove($fl);
           }
        }
        \XLite\Core\Database::getEM()->flush();

        $fp->setCategory($someOtherCategory);

        $fp->setOrderBy(10);

        \XLite\Core\Database::getEM()->persist($fp);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals($fp->getCategory(), $someOtherCategory, 'Check new category');
        $this->assertEquals($fp->getProduct(), $someOtherProduct, 'Check new category');
        $this->assertEquals($fp->getOrderBy(), 10, 'Check new position');
    }

    public function testDelete()
    {
        $fp = $this->getTestFeaturedProduct();

        $id = $fp->getId();

        $fp->delete();

        $fp2 = \XLite\Core\Database::getRepo('XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
            ->find($id);

        $this->assertNull($fp2, 'Check removed featured link');
    }

    protected function getProduct()
    {
        if (!isset($this->product)) {

            $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findFrame(1, 1);

            $this->product = array_shift($list);
            foreach ($list as $p) {
                $p->detach();
            }
        }

        $this->assertNotNull($this->product, 'getProduct() returned null');

        return $this->product;
    }

    protected function getCategory()
    {
        if (!isset($this->category)) {
            $list = \XLite\Core\Database::getRepo('XLite\Model\Category')->findFrame(1, 1);

            $this->category = array_shift($list);
            foreach ($list as $c) {
                $c->detach();
            }

            $this->assertNotNull($this->category, 'getCategory() returned null');

            // Clean existing featured links
            $links = \XLite\Core\Database::getRepo('XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
                ->getFeaturedProducts($this->category->getCategoryId());

            if ($links) {
                foreach ($links as $fp) {
                    \XLite\Core\Database::getEM()->remove($fp);
                }
                \XLite\Core\Database::getEM()->flush();
            }
        }

        return $this->category;
    }

    /**
     * @var XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct
     */
    protected $fp;

    protected function getTestFeaturedProduct()
    {
        $fProduct = new XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct();

        $fProduct->setProduct($this->getProduct());
        $fProduct->setCategory($this->getCategory());
        $fProduct->setOrderBy(5);

        \XLite\Core\Database::getEM()->persist($fProduct);
        \XLite\Core\Database::getEM()->flush();
        $this->fp = $fProduct;
        return $fProduct;
    }
    protected function tearDown(){
        if($this->fp){
            $em = \XLite\Core\Database::getEM();
            if(!$em->contains($this->fp))
                $em->merge($this->fp);
            $em->remove($this->fp);
            $em->flush();
        }
    }
}
