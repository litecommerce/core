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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Module_CDev_FeaturedProducts_Model_Repo_FeaturedProduct extends XLite_Tests_TestCase
{
    protected $product;

    protected $category;

    protected $featuredProduct;

    public function testGetFeaturedProducts($categoryId = 0)
    {
        $p = $this->getProduct();
        $c = $this->getCategory();

        $testFp = $this->getTestFeaturedProduct();

        $fps = \XLite\Core\Database::getRepo('XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
            ->getFeaturedProducts($c->getCategoryId());

        $this->assertEquals(1, count($fps), 'check featured product links count');
        $this->assertEquals($p->getProductId(), $fps[0]->getProduct()->getProductId(), 'check product id');
        $this->assertEquals($c->getCategoryId(), $fps[0]->getCategory()->getCategoryId(), 'check category id');
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

        $this->assertNotNull($this->product, 'getProduct() returned null, object expected');

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

            // Clean existing featured links
            $links = \XLite\Core\Database::getRepo('XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
                ->getFeaturedProducts($this->category->getCategoryId());

            if ($links) {
                foreach ($links as $fp) {
                    \XLite\Core\Database::getEM()->remove($fp);
                }
            }

            \XLite\Core\Database::getEM()->flush();
        }

        $this->assertNotNull($this->category, 'getCategory() returned null, object expected');

        return $this->category;
    }

    protected function getTestFeaturedProduct()
    {
        $fProduct = new XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct();

        $fProduct->setProduct($this->getProduct());
        $fProduct->setCategory($this->getCategory());
        $fProduct->setOrderBy(5);

        \XLite\Core\Database::getEM()->persist($fProduct);
        \XLite\Core\Database::getEM()->flush();

        $this->assertNotNull($fProduct, 'getTestFeaturedProduct() returned null, object expected');
        $this->featuredProduct = $fProduct;
        return $fProduct;
    }

    protected function tearDown()
    {
        if ($this->featuredProduct) {
            $em = \XLite\Core\Database::getEM();
            if (!$em->contains($this->featuredProduct))
                $em->merge($this->featuredProduct);
            $em->remove($this->featuredProduct);
            $em->flush();
        }
    }

}
