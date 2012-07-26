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

namespace XLite\View\PagerOrig;

/**
 * Common pager
 *
 */
class Common extends \XLite\View\PagerOrig
{
    /**
     * Page short names
     */

    const PAGE_FIRST    = 'first';
    const PAGE_PREVIOUS = 'previous';
    const PAGE_NEXT     = 'next';
    const PAGE_LAST     = 'last';


    /**
     * pagesPerFrame
     *
     * @var integer
     */
    protected $pagesPerFrame = 5;


    /**
     * Check if pages row is visible or not
     *
     * @return boolean
     */
    public function isPagerVisible()
    {
        return 1 < $this->getPagesCount();
    }


    /**
     * Build page URL by page ID
     *
     * @param integer $pageId Page ID
     *
     * @return string
     */
    protected function buildURLByPageId($pageId)
    {
        return parent::buildURLByPageId($this->getPageIdByNotation($pageId));
    }

    /**
     * getFrameStartPage
     *
     * @return integer
     */
    protected function getFrameStartPage()
    {
        return max(0, $this->getPageId() - ceil($this->pagesPerFrame / 2));
    }

    /**
     * Defaint pages URLs
     *
     * @return void
     */
    protected function definePageURLs()
    {
        parent::definePageURLs();

        $this->pageURLs = array_slice($this->pageURLs, $this->getFrameStartPage(), $this->pagesPerFrame, true);
    }

    /**
     * isFurthermostPage
     *
     * @param string $type Link type (first / previous / next / last)
     *
     * @return boolean
     */
    protected function isFurthermostPage($type)
    {
        $pageId = $this->getPageId();

        return (0 >= $pageId && in_array($type, array(self::PAGE_FIRST, self::PAGE_PREVIOUS)))
            || ($this->getPagesCount() - 1 <= $pageId && in_array($type, array(self::PAGE_LAST, self::PAGE_NEXT)));
    }

    /**
     * getPageIndexNotations
     *
     * @param mixed $index Page notation
     *
     * @return integer
     */
    protected function getPageIdByNotation($index)
    {
        $result = array(
            self::PAGE_FIRST    => 0,
            self::PAGE_PREVIOUS => max(0, $this->getPageId() - 1),
            self::PAGE_LAST     => $this->getPagesCount() - 1,
            self::PAGE_NEXT     => min($this->getPagesCount() - 1, $this->getPageId() + 1),
        );

        return isset($result[$index]) ? $result[$index] : $index;
    }

    /**
     * getLinkClassName
     *
     * @param mixed $index Page notation
     *
     * @return string
     */
    protected function getLinkClassName($index)
    {
        return 'page-' . $this->getPageIdByNotation($index);
    }

    /**
     * Get border link class name
     *
     * @param string $type Link type (first / previous / next / last)
     *
     * @return string
     */
    protected function getBorderLinkClassName($type)
    {
        return $type . ' ' . ($this->isFurthermostPage($type) ? $type . '-disabled disabled' : '');
    }

    /**
     * getPageClassName
     *
     * @param integer $pageId Current page ID
     *
     * @return string
     */
    protected function getPageClassName($pageId)
    {
        return 'page-item page-' . $pageId . ($this->isCurrentPage($pageId) ? ' selected' : '');
    }

    /**
     * Get page begin record number
     *
     * @return integer
     */
    protected function getBeginRecordNumber()
    {
        return $this->getPageId() * $this->getItemsPerPage() + 1;
    }

    /**
     * Get page end record number
     *
     * @return integer
     */
    protected function getEndRecordNumber()
    {
        return min($this->getBeginRecordNumber() + $this->getItemsPerPage() - 1, $this->getItemsTotal());
    }
}
