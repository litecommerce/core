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
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Pager;

/**
 * Abstract pager class
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class APager extends \XLite\View\RequestHandler\ARequestHandler
{
    /**
     * Widget parameter names
     */

    const PARAM_PAGE_ID                      = 'pageId';
    const PARAM_ITEMS_COUNT                  = 'itemsCount';
    const PARAM_ONLY_PAGES                   = 'onlyPages';
    const PARAM_ITEMS_PER_PAGE               = 'itemsPerPage';
    const PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR = 'showItemsPerPageSelector';
    const PARAM_LIST                         = 'list';

    /**
     * Page short names
     */

    const PAGE_FIRST    = 'first';
    const PAGE_PREVIOUS = 'previous';
    const PAGE_NEXT     = 'next';
    const PAGE_LAST     = 'last';


    /**
     * currentPageId
     * FIXME: due to old-style params mapping we cannot use the "pageId" name here:
     * it will be overriden by the "PARAM_PAGE_ID" request parameter
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $currentPageId;

    /**
     * pagesCount 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $pagesCount;

    /**
     * Number of items per page (cached value)
     *
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $itemsPerPage;

    /**
     * pageURLs 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $pageURLs;


    /**
     * Return number of items per page
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getItemsPerPageDefault();

    /**
     * Return number of pages to display
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getPagesPerFrame();


    /**
     * Return current list name
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListName()
    {
        return 'pager';
    }

    /**
     * getDir 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'pager';
    }

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * getList 
     * 
     * @return \XLite\View\ItemsList\AItemsList
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getList()
    {
        return $this->getParam(self::PARAM_LIST);
    }

    /**
     * getItemsTotal 
     * 
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getItemsTotal()
    {
        return $this->getParam(self::PARAM_ITEMS_COUNT);
    }

    /**     
     * Get pages count 
     * 
     * @return integer
     * @access protected
     * @since  3.0.0
     */
    protected function getPagesCount()
    {
        if (!isset($this->pagesCount)) {
            $this->pagesCount = ceil($this->getItemsTotal() / $this->getItemsPerPage());
        }

        return $this->pagesCount;
    }

    /**
     * Return minimal possible items number per page
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageMin()
    {
        return 1;
    }

    /**
     * Return maximal possible items number per page
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageMax()
    {
        return 100;
    }

    /**
     * getItemsPerPage
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getItemsPerPage()
    {
        if (!isset($this->itemsPerPage)) {
            $current = $this->getParam(self::PARAM_ITEMS_PER_PAGE);
            $this->itemsPerPage = max(
                min($this->getItemsPerPageMax(), $current),
                max($this->getItemsPerPageMin(), $current)
            );
        }

        return $this->itemsPerPage;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PAGE_ID => new \XLite\Model\WidgetParam\Int(
                'Page ID', 0
            ),
            self::PARAM_ITEMS_COUNT => new \XLite\Model\WidgetParam\Int(
                'Items number', 0
            ),
            self::PARAM_ONLY_PAGES => new \XLite\Model\WidgetParam\Bool(
                'Only display pages list', false
            ),
            self::PARAM_ITEMS_PER_PAGE => new \XLite\Model\WidgetParam\Int(
                'Items per page', $this->getItemsPerPageDefault(), true
            ),
            self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR => new \XLite\Model\WidgetParam\Checkbox(
                'Show "Items per page" selector', true, true
            ),
            self::PARAM_LIST => new \XLite\Model\WidgetParam\Object(
                'List object', null, false, '\XLite\View\ItemsList\AItemsList'
            ),
        );
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_PAGE_ID;
        $this->requestParams[] = self::PARAM_ITEMS_PER_PAGE;
    }

    /**
     * Returns a page Id by its notation
     * 
     * @param string $index Page notation (first, last, next, previous)
     *  
     * @return int
     * @access protected
     * @since  3.0.0
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
     * Checks whether a page is disabled by its notation
     * 
     * @param string $notation Page notation (first, last, next, previous)
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isDisabledNotation($notation)
    {
        return $this->isCurrentPage($this->getPageIdByNotation($notation));
    }




    /**
     * Build page URL by page ID
     *
     * @param int $pageId page ID
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function buildUrlByPageId($pageId)
    {
        return $this->getList()->getActionURL(array(self::PARAM_PAGE_ID => $this->getPageIdByNotation($pageId)));
    }

    /**
     * getFrameLength 
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFrameLength()
    {
        return min($this->getPagesPerFrame(), $this->getPagesCount());
    }

    /**
     * getFrameHalfLength 
     * 
     * @param bool $shortPart which part of frame to return
     *  
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFrameHalfLength($shortPart = true)
    {
        return call_user_func($shortPart ? 'floor' : 'ceil', $this->getFrameLength() / 2);
    }

    /**
     * getFrameStartPage
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getFrameStartPage()
    {
        $pageId = min(
            $this->getPageId() - $this->getFrameHalfLength(),
            $this->getPagesCount() - $this->getFrameLength()
        );

        return max(0, $pageId);
    }

    /**
     * isFurthermostPage 
     * 
     * @param string $type link type (first / previous / next / last)
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isFurthermostPage($type)
    {
        switch ($type) {

            case self::PAGE_FIRST:
                $result = (0 < $this->getFrameStartPage());
                break;

            case self::PAGE_LAST:
                $result = ($this->getFrameStartPage() + $this->getFrameLength()) < $this->getPagesCount();
                break;

            default:
                $result = false;
        }

        return $result;
    }

    /**
     * definePageUrls 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function definePageUrls()
    {
        for ($i = 0; $i < $this->getPagesCount(); $i++) {
            $this->pageURLs[$i] = $this->buildUrlByPageId($i);
        }

        $this->pageURLs = array_slice($this->pageURLs, $this->getFrameStartPage(), $this->getFrameLength(), true);
    }

    /**
     * Get pages URL list 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getPageUrls()
    {
        if (!isset($this->pageURLs)) {
            $this->pageURLs = array();
            $this->definePageUrls();
        }

        return $this->pageURLs;
    }

    /**
     * isCurrentPage 
     * 
     * @param int $pageId current page ID
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isCurrentPage($pageId)
    {
        return $this->getPageId() == $pageId;
    }

     /**
     * Return current page Id 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getPageId()
    {
        if (!isset($this->currentPageId)) {
            $this->currentPageId = min($this->getParam(self::PARAM_PAGE_ID), $this->getPagesCount() - 1);
        }

        return $this->currentPageId;
    }

    /**
     * Return index of the first item on the current page
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getStartItem()
    {
        return $this->getPageId() * $this->getItemsPerPage();
    }

    /**
     * Get page begin record number
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getBeginRecordNumber()
    {
        return $this->getStartItem() + 1;
    }

    /**
     * Get page end record number
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getEndRecordNumber()
    {
        return min($this->getBeginRecordNumber() + $this->getItemsPerPage() - 1, $this->getItemsTotal());
    }

    /**
     * Check if pages list is visible or not
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isPagesListVisible()
    {
        return 1 < $this->getPagesCount();
    }

    /**
     * isItemsPerPageVisible
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isItemsPerPageVisible()
    {
        return !((bool) $this->getParam(self::PARAM_ONLY_PAGES));
    }

    /**
     * isItemsPerPageSelectorVisible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isItemsPerPageSelectorVisible()
    {
        return $this->getParam(self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR);
    }

    /**
     * isVisible 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return $this->isPagesListVisible() || $this->isItemsPerPageVisible();
    }


    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/pager.css';
        $list[] = 'common/grid-list.css';

        return $list;
    }

    /**
     * Return SQL condition with limits
     * 
     * @param int                    $start index of the first item on the page
     * @param int                    $count number of items per page
     * @param \XLite\Core\CommonCell $cnd   search condition
     *  
     * @return array|\Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLimitCondition($start = null, $count = null, \XLite\Core\CommonCell $cnd = null)
    {
        if (!isset($start)) {
            $start = $this->getStartItem();
        }

        if (!isset($count)) {
            $count = $this->getItemsPerPage();
        }

        return \XLite\Model\Repo\Base\Searchable::addLimitCondition($start, $count, $cnd);
    }
}
