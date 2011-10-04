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
 */

class XLite_Tests_Module_CDev_FileAttachments_Model_Product_Attachment_Storage extends XLite_Tests_TestCase
{
    public function testLoading()
    {
        $product = $this->getProduct();

        $attach = new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment;
        $product->addAttachments($attach);
        $attach->setProduct($product);

        $storage = $attach->getStorage();

        $path = LC_DIR_FILES . 'attachments/max_ava.png';
        if (file_exists($path)) {
            unlink($path);
        }

        // Success    
        $this->assertTrue($storage->loadFromLocalFile(__DIR__ . '/../max_ava.png'), 'check loading');
        $this->assertEquals('application/octet-stream', $storage->getMime(), 'check mime');
        $this->assertRegExp('/^max_ava(_\d+)?\.png$/Ss', $storage->getFileName(), 'check file name');
        $this->assertEquals(12673, $storage->getSize(), 'check size');
        $this->assertEquals(file_get_contents(__DIR__ . '/../max_ava.png'), $storage->getBody(), 'check body');
        $this->assertRegExp('/^http:\/\//Ss', $storage->getURL(), 'check URL');
        $this->assertEquals($storage->getURL(), $storage->getFrontURL(), 'check front url');
        $this->assertEquals('png', $storage->getExtension(), 'check extension');
        $this->assertEquals('mime-icon-png', $storage->getMimeClass(), 'check MIME class');
        $this->assertEquals('png file type', $storage->getMimeName(), 'check MIME name');


        // Fail
        $this->assertFalse($storage->loadFromLocalFile(__DIR__ . '/../wrong.png'), 'check loading (fail)');

        // Duplicate
        $this->assertTrue($storage->loadFromLocalFile(__DIR__ . '/../max_ava.png'), 'check loading (dup)');
        $this->assertRegExp('/^max_ava_\d+\.png$/Ss', $storage->getFileName(), 'check file name (rename)');

        // Forbid extension
        $this->assertFalse($storage->loadFromLocalFile(__FILE__), 'check loading (forbid ext)');
        $this->assertEquals('extension', $storage->getLoadError(), 'check load error code');
    }

    public function testRemove()
    {
        $storage = $this->getTestStorage();

        $path = LC_DIR_FILES . 'attachments/' . $storage->getPath();
        $this->assertTrue(file_exists($path), 'check exist');

        $storage->getAttachment()->getProduct()->getAttachments()->removeElement($storage->getAttachment());
        \XLite\Core\Database::getEM()->remove($storage->getAttachment());

        \XLite\Core\Database::getEM()->flush();

        $this->assertFalse(file_exists($path), 'check remove');

        // Duplicate
        $s1 = $this->getTestStorage();
        $s2 = $this->getTestStorage();
        $this->assertTrue($s2->loadFromLocalFile($path), 'check duplicate loading');
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->remove($s1->getAttachment());
        \XLite\Core\Database::getEM()->flush();
        $this->assertTrue(file_exists($path), 'check remove (duplicate)');

        \XLite\Core\Database::getEM()->remove($s2->getAttachment());
        \XLite\Core\Database::getEM()->flush();
        $this->assertFalse(file_exists($path), 'check remove (duplicate) #2');
    }

    public function testRenewStorage()
    {
        $storage = $this->getTestStorage();
        $size = $storage->getSize();

        $path = LC_DIR_FILES . 'attachments/' . $storage->getPath();
        $this->assertTrue(file_exists($path), 'check exist');

        $s2 = $this->getTestStorage();
        $this->assertTrue($s2->loadFromLocalFile($path), 'check duplicate loading');
        \XLite\Core\Database::getEM()->flush();

        unlink($path);
        copy(__DIR__ . '/../vertical_dots.png', $path);
        $this->assertTrue($storage->renewStorage(), 'check renew storage status');

        $this->assertNotEquals($size, $storage->getSize(), 'check old file size');
        $this->assertEquals(filesize($path), $storage->getSize(), 'check file size');
        $this->assertEquals(filesize($path), $s2->getSize(), 'check file size #2');
    }

    protected function getTestStorage()
    {
        $product = $this->getProduct();

        $attach = new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment;
        $product->addAttachments($attach);
        $attach->setProduct($product);

        $storage = $attach->getStorage();

        $path = LC_DIR_FILES . 'attachments/max_ava.png';
        if (file_exists($path)) {
            unlink($path);
        }

        $this->assertTrue($storage->loadFromLocalFile(__DIR__ . '/../max_ava.png'), 'check loading');

        \XLite\Core\Database::getEM()->flush();

        return $storage;
    }
}
