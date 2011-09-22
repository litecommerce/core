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

class XLite_Tests_Module_CDev_FileAttachments_Model_Product_Attachment extends XLite_Tests_TestCase
{
    public function testGetStorage()
    {
        $attach = new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment;

        $this->assertInstanceOf('XLite\Module\CDev\FileAttachments\Model\Product\Attachment\Storage', $attach->getStorage(), 'check class');
        $this->assertEquals($attach, $attach->getStorage()->getAttachment(), 'check attachment');
    }

    public function testgetPublicTitle()
    {
        $attach = $this->getTestAttachment();

        $this->assertEquals('max_ava.png', $attach->getPublicTitle(), 'check default title');
        $this->assertEquals('', $attach->getTitle(), 'check empty title');
        $attach->setTitle('test');
        $this->assertEquals('test', $attach->getPublicTitle(), 'check normal title');
    }

    protected function getTestAttachment()
    {
        $product = $this->getProduct();

        $attach = new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment;
        $product->addAttachments($attach);
        $attach->setProduct($product);

        $storage = $attach->getStorage();

        $path = LC_DIR_VAR . '/files/attachments/max_ava.png';
        if (file_exists($path)) {
            unlink($path);
        }

        $this->assertTrue($storage->loadFromLocalFile(__DIR__ . '/max_ava.png'), 'check loading');

        \XLite\Core\Database::getEM()->flush();

        return $attach;
    }
}
