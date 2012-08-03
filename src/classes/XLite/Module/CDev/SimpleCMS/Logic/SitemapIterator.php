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

namespace XLite\Module\CDev\SimpleCMS\Logic;

/**
 * Sitemap links iterator
 *
 * @see   ____class_see____
 * @since 1.0.12
 *
 * @LC_Dependencies ("CDev\XMLSitemap")
 */
class SitemapIterator extends \XLite\Module\CDev\XMLSitemap\Logic\SitemapIterator implements \XLite\Base\IDecorator
{
    /**
     * Get current data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function current()
    {
        $data = parent::current();

        if (
            $this->position >= parent::count() 
            && $this->position < $this->count()
        ) {
            $data = \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleCMS\Model\Page')->findOneAsSitemapLink($this->position - parent::count(), 1);
            $data = $this->assemblePageData($data);
        }

        return $data;
    }

    /**
     * Get length
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function count()
    {
        return parent::count() + $this->getPagesLength();
    }

    /**
     * Get pages length
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function getPagesLength()
    {
        if (!isset($this->pagesLength)) {
            $this->pagesLength = \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleCMS\Model\Page')
                ->countPagesAsSitemapsLinks();
        }

        return $this->pagesLength;
    }

    /**
     * Assemble page data
     *
     * @param \XLite\Module\CDev\SimpleCMS\Model\Page $page Page
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function assemblePageData(\XLite\Module\CDev\SimpleCMS\Model\Page $page)
    {
        return array(
            'loc'        => array('target' => 'page', 'id' => $page->getId()),
            'lastmod'    => time(),
            'changefreq' => \XLite\Core\Config::getInstance()->CDev->XMLSitemap->page_changefreq,
            'priority'   => $this->processPriority(\XLite\Core\Config::getInstance()->CDev->XMLSitemap->page_priority),
        );
    }

}
