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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Model_Image_Product_Image extends XLite_Tests_TestCase
{
    protected $product;

    protected $images = array(
        'demo_store_p4026.jpeg',
        'demo_store_p4027.jpeg',
        'demo_store_p4028.jpeg',
    );

    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();
        \Includes\Utils\FileManager::unlinkRecursive(LC_DIR_IMAGES . 'product');
    }

    public function testCreate()
    {
        foreach ($this->getProduct()->getImages() as $n => $i) {
            $this->assertEquals($i->getPath(), $i->getAlt(), 'check path & alt equals (' . $n . ')');
            $this->assertEquals(1, $i->getOrderby(), 'check orderby (' . $n . ')');

            $this->assertEquals('image/jpeg', $i->getMime(), 'check mime type (' . $n . ')');
        }

        $i = $this->getProduct()->getImages()->get(0);

        $this->assertEquals(400, $i->getWidth(), 'check width');
        $this->assertEquals(357, $i->getHeight(), 'check height');
        $this->assertEquals(51846, $i->getSize(), 'check size');
        $this->assertTrue(is_numeric($i->getDate()), 'check date');
        $this->assertEquals('58bf160b2ac8b46fe6a1ba641ca22d7f', $i->getHash(), 'check hash');

        $this->assertEquals(
            $this->getProduct()->getProductId(),
            $i->getProduct()->getProductId(),
            'check product equals'
        );
    }

    public function testUpdate()
    {
        $i = $this->getProduct()->getImages()->get(0);

        $i->setAlt('test2');
        $i->setOrderby(99);

        \XLite\Core\Database::getEM()->persist($i);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('test2', $i->getAlt(), 'check alt');
        $this->assertEquals(99, $i->getOrderby(), 'check orderby');
    }

    public function testDelete()
    {
        $i = $this->getProduct()->getImages()->get(0);

        $id = $i->getId();

        $this->getProduct()->getImages()->removeElement($i);

        \XLite\Core\Database::getEM()->remove($i);
        \XLite\Core\Database::getEM()->flush();

        $i = \XLite\Core\Database::getRepo('XLite\Model\Image\Product\Image')
            ->find($id);

        $this->assertNull($i, 'Check removed image');
    }

    protected function getProduct()
    {
        if (!isset($this->product)) {
            $this->product = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByEnabled(true);

            if (!$this->product) {
                $this->fail('Enabled product not found');

            } elseif (!$this->product->getProductId()) {
                $this->fail('Product has not product_id');
            }

            // Remove old detailed images
            foreach ($this->product->getImages() as $i) {
                 \XLite\Core\Database::getEM()->remove($i);
            }
            $this->product->getImages()->clear();

            \XLite\Core\Database::getEM()->flush();

            foreach ($this->images as $path) {
                $i = new \XLite\Model\Image\Product\Image();

                $i->setProduct($this->product);
                $this->product->getImages()->add($i);

                $i->loadFromLocalFile(__DIR__ . LC_DS . $path);
                $i->setAlt($path);
                $i->setOrderby(1);
                \XLite\Core\Database::getEM()->persist($i);
            }

            \XLite\Core\Database::getEM()->flush();

        }

        return $this->product;
    }
    protected function tearDown(){
        $this->product = null;
        $this->doRestoreDb();
        parent::tearDown();
    }
}
