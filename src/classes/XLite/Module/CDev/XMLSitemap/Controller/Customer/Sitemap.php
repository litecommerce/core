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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\XMLSitemap\Controller\Customer;

/**
 * Sitemap controller
 * 
 */
class Sitemap extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Run controller
     *
     * @return void
     */
    protected function run()
    {
        $this->doNoAction();
    }

    /**
     * Preprocessor for no-action ren
     *
     * @return void
     */
    protected function doNoAction()
    {
        if (!\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isGenerated()) {
            \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->generate();
        }

        $index = intval(\XLite\Core\Request::getInstance()->index);

        if ($index) {
            $content = \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->getSitemap($index);

        } else {
            $content = \XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->getIndex();
        }

        $this->displayContent($content);
    }

    /**
     * Display content 
     * 
     * @param string $content Content
     *  
     * @return void
     */
    protected function displayContent($content)
    {
        header('Content-Type: application/xml; charset=UTF-8');
        header('Content-Length: ' . strlen($content));
        header('ETag: ' . md5($content));

        print ($content);

        $this->silent = true;

        die (0);
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean
     */
    protected function checkAccess()
    {
        return parent::checkAccess()
            && !\XLite\Module\CDev\XMLSitemap\Logic\SitemapGenerator::getInstance()->isEmpty();
    }
}

