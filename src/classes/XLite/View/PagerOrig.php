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

namespace XLite\View;

/**
 * Pager
 *
 */
class PagerOrig extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_PAGE_ID        = 'pageId';
    const PARAM_ITEMS_PER_PAGE = 'itemsPerPage';
    const PARAM_DATA           = 'data';

    const PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR = 'showItemsPerPageSelector';

    /**
     * Items-per-page range
     */

    const ITEMS_PER_PAGE_MIN = 1;
    const ITEMS_PER_PAGE_MAX = 100;

    /**
     * Items per page (default value)
     */

    const DEFAULT_ITEMS_PER_PAGE = 4;


    /**
     * pageId
     *
     * @var integer
     */
    protected $pageId = null;

    /**
     * Data
     *
     * @var integer
     */
    protected $itemsTotal = null;

    /**
     * itemsPerPage
     *
     * @var integer
     */
    protected $itemsPerPage = null;

    /**
     * pagesCount
     *
     * @var integer
     */
    protected $pagesCount = null;

    /**
     * pageURLs
     *
     * @var array
     */
    protected $pageURLs = null;

    /**
     * Get pages count
     *
     * @return integer
     */
    public function getPagesCount()
    {
        if (!isset($this->pagesCount)) {
            $this->pagesCount = ceil($this->getItemsTotal() / $this->getItemsPerPage());
        }

        return $this->pagesCount;
    }

    /**
     * Get currenct page data
     *
     * @return array
     */
    public function getPageData()
    {
        return array_slice(
            $this->getParam(self::PARAM_DATA),
            $this->getPageId() * $this->getItemsPerPage(),
            $this->getItemsPerPage()
        );
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        return array_merge(parent::getCSSFiles(), array('common/pager.css'));
    }


    /**
     * getItemsTotal
     *
     * @return integer
     */
    protected function getItemsTotal()
    {
        if (!isset($this->itemsTotal)) {
            $this->itemsTotal = count($this->getParam(self::PARAM_DATA));
        }

        return $this->itemsTotal;
    }

    /**
     * getItemsPerPage
     *
     * @return integer
     */
    protected function getItemsPerPage()
    {
        if (!isset($this->itemsPerPage)) {
            $current = intval($this->getParam(self::PARAM_ITEMS_PER_PAGE));
            $this->itemsPerPage = max(
                min(self::ITEMS_PER_PAGE_MAX, $current),
                max(self::ITEMS_PER_PAGE_MIN, $current)
            );
        }

        return $this->itemsPerPage;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'common/pager.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PAGE_ID => new \XLite\Model\WidgetParam\Int(
                'Page ID', 0
            ),
            self::PARAM_ITEMS_PER_PAGE => new \XLite\Model\WidgetParam\Int(
                'Items per page', intval(\XLite\Core\Config::getInstance()->General->products_per_page), true
            ),
            self::PARAM_DATA => new \XLite\Model\WidgetParam\Collection(
                'Data', array()
            ),
            self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR => new \XLite\Model\WidgetParam\Checkbox(
                'Show "Items per page" selector', true, true
            ),
        );
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_PAGE_ID;
    }

    /**
     * isItemsPerPageSelectorVisible
     *
     * @return boolean
     */
    protected function isItemsPerPageSelectorVisible()
    {
        return $this->getParam(self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR);
    }

    /**
     * Return list of page URL params
     *
     * @param integer $pageId Page ID
     *
     * @return void
     */
    protected function getPageURLParams($pageId)
    {
        return array(self::PARAM_PAGE_ID => $pageId) + $this->getRequestParams();
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
        return $this->getURL($this->getPageURLParams($pageId));
    }

    /**
     * definePageUrls
     *
     * @return void
     */
    protected function definePageURLs()
    {
        for ($i = 0; $i < $this->getPagesCount(); $i++) {
            $this->pageURLs[$i] = $this->buildURLByPageId($i);
        }
    }

    /**
     * Get pages URL list
     *
     * @return array
     */
    protected function getPageURLs()
    {
        if (!isset($this->pageURLs)) {
            $this->pageURLs = array();
            $this->definePageURLs();
        }

        return $this->pageURLs;
    }

    /**
     * isCurrentPage
     *
     * @param integer $pageId Current page ID
     *
     * @return boolean
     */
    protected function isCurrentPage($pageId)
    {
        return $this->getPageId() == $pageId;
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
        return 'page-item page-' . $pageId . ' ' . ($this->isCurrentPage($pageId) ? 'selected' : '');
    }

    /**
     * Return current page Id
     *
     * @return void
     */
    protected function getPageId()
    {
        if (!isset($this->pageId)) {
            $this->pageId = min($this->getParam(self::PARAM_PAGE_ID), $this->getPagesCount() - 1);
        }

        return $this->pageId;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getPageData();
    }
}
