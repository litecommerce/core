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

class XLite_Tests_Model_Repo_Image_Product_Detailed extends XLite_Tests_TestCase
{
    protected $product;

    protected $images = array(
        'demo_store_p4004.jpeg',
        'demo_store_d4004_1.jpeg',
        'demo_store_d4012_1.jpeg',
    );

    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();
    }

    public function testFindActiveByProductId()
    {
        $list = \XLite\Core\Database::getRepo('XLite\Model\Image\Product\Detailed')
            ->findActiveByProductId($this->getProduct()->getProductId());

        $this->assertEquals(3, count($list), 'check list count');

        $i = \XLite\Core\Database::getRepo('XLite\Model\Image\Product\Detailed')
            ->find($list[0]->getImageId());

        $i->setEnabled(false);
        \XLite\Core\Database::getEM()->persist($i);
        \XLite\Core\Database::getEM()->flush();

        $list = \XLite\Core\Database::getRepo('XLite\Model\Image\Product\Detailed')
            ->findActiveByProductId($this->getProduct()->getProductId());

        $this->assertEquals(2, count($list), 'check list count #2');
    }

    protected function getProduct()
    {
        if (!isset($this->product)) {
            $this->product = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByEnabled(true);

            // Remove old detailed images
            foreach ($this->product->getDetailedImages() as $i) {
                 \XLite\Core\Database::getEM()->remove($i);
            }
            $this->product->getDetailedImages()->clear();

            foreach ($this->images as $path) {
                $i = new \XLite\Model\Image\Product\Detailed();

                $i->setProduct($this->product);
                $this->product->addDetailedImages($i);

                $p = LC_ROOT_DIR . 'images' . LC_DS . 'product_detailed_images' . LC_DS . $path;
                $this->assertTrue(
                    $i->loadFromLocalFile($p),
                    'load image from ' . $p
                );
                $i->setEnabled(true);
                $i->setAlt($path);
                $i->setOrderby(1);
            }

            \XLite\Core\Database::getEM()->persist($this->product);
            \XLite\Core\Database::getEM()->flush();

        }

        return $this->product;
    }
}
