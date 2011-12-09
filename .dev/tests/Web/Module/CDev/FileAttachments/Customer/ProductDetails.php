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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.10
 *
 * @resource product
 * @resource product_attachment
 */

class XLite_Web_Module_CDev_FileAttachments_Customer_ProductDetails extends XLite_Web_Customer_ACustomer
{
    public function testAttachments()
    {
        $product = $this->getActiveProduct();
        $productId = $product->getProductId();

        $attachment = new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment;
        $product->addAttachments($attachment);
        $attachment->setProduct($product);
        $this->assertTrue($attachment->getStorage()->loadFromLocalFile(__DIR__ . LC_DS . 'spacer.gif'), 'check loading');
        $attachment->setTitle('test1');
        $attachment->setDescription('test2');

        foreach ($product->getAttachments() as $a) {
            \XLite\Core\Database::getEM()->remove($a);
        }
        $product->getAttachments()->clear();
        $product->addAttachments($attachment);
        $attachment->setProduct($product);

        \XLite\Core\Database::getEM()->persist($attachment);

        \XLite\Core\Database::getEM()->flush();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent(
            'css=.product-attachments',
            'check attachments box'
        );

        $this->assertEquals(
            'mime-icon mime-icon-gif',
            $this->getJSExpression('jQuery(".product-attachments li img").attr("class")'),
            'check img class'
        );
        $this->assertEquals(
            'gif file type',
            $this->getJSExpression('jQuery(".product-attachments li img").attr("alt")'),
            'check img alt'
        );
        $this->assertEquals(
            'test1',
            $this->getJSExpression('jQuery(".product-attachments li a").html()'),
            'check title'
        );
        $this->assertEquals(
            'test2',
            $this->getJSExpression('jQuery(".product-attachments li .description").html()'),
            'check description'
        );
        $this->assertEquals(
            1,
            intval($this->getJSExpression('jQuery(".product-attachments li").length')),
            'check attachments list length'
        );

        // Empty description
        $attachment->setDescription('');
        \XLite\Core\Database::getEM()->flush();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertElementNotPresent(
            'css=.product-attachments li .descriptionf',
            'check NO description'
        );

        // Empty title
        $attachment->setTitle('');
        \XLite\Core\Database::getEM()->flush();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertRegExp(
            '/^spacer(?:_\d+)?.gif$/Ss',
            $this->getJSExpression('jQuery(".product-attachments li a").html()'),
            'check empty title (filename based)'
        );

        // Remove attachment
        $product->detach();

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($productId);

        $attachments = $product->getAttachments();

        foreach($attachments as $att){
            \XLite\Core\Database::getEM()->remove($att);
        }

        $product->getAttachments()->clear();
 
        \XLite\Core\Database::getEM()->persist($product);

        \XLite\Core\Database::getEM()->flush();

        $product->detach();

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($productId);

        $this->openAndWait('store/product//product_id-' . $product->getProductId());
        $this->refresh();
        $this->assertElementNotPresent(
            'css=.product-attachments',
            'check empty attachments box'
        );

        // Check position
        $a1 = new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment;
        $product->addAttachments($a1);
        $a1->setProduct($product);
        $this->assertTrue($a1->getStorage()->loadFromLocalFile(__DIR__ . LC_DS . 'spacer.gif'), 'check loading');
        $a1->setOrderby(10);
        $a1->setTitle('a1');
        $product->addAttachments($a1);
        $a1->setProduct($product);
        \XLite\Core\Database::getEM()->persist($a1);

        $a2 = new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment;
        $product->addAttachments($a2);
        $a2->setProduct($product);
        $this->assertTrue($a2->getStorage()->loadFromLocalFile(__DIR__ . LC_DS . 'spacer.gif'), 'check loading');
        $a2->setOrderby(20);
        $a2->setTitle('a2');
        $product->addAttachments($a2);
        $a2->setProduct($product);
        \XLite\Core\Database::getEM()->persist($a2);

        \XLite\Core\Database::getEM()->flush();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertEquals(
            'a1',
            $this->getJSExpression('jQuery(".product-attachments li:eq(0) a").html()'),
            'check pos 1'
        );
        $this->assertEquals(
            'a2',
            $this->getJSExpression('jQuery(".product-attachments li:eq(1) a").html()'),
            'check pos 2'
        );

        $a2->setOrderby(5);
        \XLite\Core\Database::getEM()->flush();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertEquals(
            'a1',
            $this->getJSExpression('jQuery(".product-attachments li:eq(1) a").html()'),
            'check pos 3'
        );
        $this->assertEquals(
            'a2',
            $this->getJSExpression('jQuery(".product-attachments li:eq(0) a").html()'),
            'check pos 4'
        );


    }
}

