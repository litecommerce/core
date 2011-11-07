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
 * @since     1.0.12
 */

class XLite_Tests_Module_CDev_XMLSitemap_Logic_SitemapGenerator extends XLite_Tests_TestCase
{
    public function testgetIndex()
    {
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*') as $path) {
            unlink($path);
        }

        $index = \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->getIndex();

        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8" ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
</sitemapindex>',
            $index,
            'empty index'
        );

        \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->generate();

        $index = \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->getIndex();
        $this->assertRegExp('/index=1/Ss', $index, 'check URL');
    }

    public function testgetSitemap()
    {
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*') as $path) {
            unlink($path);
        }

        $this->assertNull(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->getSitemap(1));

        \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->generate();

        $this->assertNotNull(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->getSitemap(1));

        $this->assertEquals(
            file_get_contents(LC_DIR_DATA . 'xmlsitemap.1.xml'),
            \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->getSitemap(1),
            'check sitemap #1'
        );
    }

    public function testisEmpty()
    {
        $this->assertFalse(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isEmpty());
    }

    public function testisGenerated()
    {
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*') as $path) {
            unlink($path);
        }

        $this->assertFalse(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());

        \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->generate();

        $this->assertTrue(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());

    }

    public function testclear()
    {
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*') as $path) {
            unlink($path);
        }

        $this->assertFalse(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());

        \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->generate();

        $this->assertTrue(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());

        \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->clear();

        $this->assertFalse(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());
    }

    public function testgenerate()
    {
        foreach (glob(LC_DIR_DATA . 'xmlsitemap.*') as $path) {
            unlink($path);
        }

        $this->assertFalse(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());

        \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->generate();

        $this->assertTrue(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findFrame(0, 1);
        $product = $list[0];
        $product->setPrice(10);
        \XLite\Core\Database::getEM()->flush();

        $this->assertFalse(\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated());
    }
}

