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

namespace XLite\View\Pager;

/**
 * Abstract pager class
 *
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
    const PARAM_MAX_ITEMS_COUNT              = 'maxItemsCount';

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
     * @var integer
     */
    protected $currentPageId;

    /**
     * pagesCount
     *
     * @var integer
     */
    protected $pagesCount;

    /**
     * Cached pages
     *
     * @var array
     */
    protected $pages = null;


    /**
     * Return number of items per page
     *
     * @return integer
     */
    abstract protected function getItemsPerPageDefault();

    /**
     * Return number of pages to display
     *
     * @return integer
     */
    abstract protected function getPagesPerFrame();


    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/pager.css';

        if (!\XLite::isAdminZone()) {
            $list[] = 'common/grid-list.css';
        }

        return $list;
    }

    /**
     * Return CSS classes for parent block of pager (list-pager by default)
     *
     * @return string
     */
    public function getCSSClasses()
    {
        return 'list-pager';
    }

    /**
     * Return SQL condition with limits
     *
     * @param integer                $start Index of the first item on the page OPTIONAL
     * @param integer                $count Number of items per page OPTIONAL
     * @param \XLite\Core\CommonCell $cnd   Search condition OPTIONAL
     *
     * @return array|\Doctrine\ORM\PersistentCollection
     */
    public function getLimitCondition($start = null, $count = null, \XLite\Core\CommonCell $cnd = null)
    {
        if (!isset($start)) {
            $start = $this->getStartItem();
        }

        if (!isset($count)) {
            $count = $this->getItemsPerPage();
        }

        if ($this->getMaxItemsCount()) {
            $count = max(
                0,
                min(
                    $count,
                    $this->getMaxItemsCount() - $start
                )
            );
        }

        return \XLite\Model\Repo\Base\Searchable::addLimitCondition($start, $count, $cnd);
    }


    /**
     * Return current list name
     *
     * @return string
     */
    protected function getListName()
    {
        return 'pager';
    }

    /**
     * getDir
     *
     * @return string
     */
    protected function getDir()
    {
        return 'pager';
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * getList
     *
     * @return \XLite\View\ItemsList\AItemsList
     */
    protected function getList()
    {
        return $this->getParam(static::PARAM_LIST);
    }

    /**
     * getItemsTotal
     *
     * @return integer
     */
    protected function getItemsTotal()
    {
        return min(
            $this->getParam(static::PARAM_ITEMS_COUNT),
            $this->getMaxItemsCount() ?: $this->getParam(static::PARAM_ITEMS_COUNT) + 1
        );
    }

    /**
     * Get maximum number of items to display in the list
     * 
     * @return integer
     */
    protected function getMaxItemsCount()
    {
        return intval($this->getParam(static::PARAM_MAX_ITEMS_COUNT));
    }

    /**
     * Get pages count
     *
     * @return integer
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
     * @return integer
     */
    protected function getItemsPerPageMin()
    {
        return 1;
    }

    /**
     * Return maximal possible items number per page
     *
     * @return integer
     */
    protected function getItemsPerPageMax()
    {
        return 100;
    }

    /**
     * getItemsPerPage
     *
     * @return integer
     */
    protected function getItemsPerPage()
    {
        $current = $this->getParam(static::PARAM_ITEMS_PER_PAGE);

        return max(
            min($this->getItemsPerPageMax(), $current),
            max($this->getItemsPerPageMin(), $current)
        );
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
            static::PARAM_PAGE_ID => new \XLite\Model\WidgetParam\Int(
                'Page ID', 0
            ),
            static::PARAM_ITEMS_COUNT => new \XLite\Model\WidgetParam\Int(
                'Items number', 0
            ),
            static::PARAM_ONLY_PAGES => new \XLite\Model\WidgetParam\Bool(
                'Only display pages list', false
            ),
            static::PARAM_ITEMS_PER_PAGE => new \XLite\Model\WidgetParam\Int(
                'Items per page', $this->getItemsPerPageDefault(), true
            ),
            static::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR => new \XLite\Model\WidgetParam\Checkbox(
                'Show pagination', true, true
            ),
            static::PARAM_LIST => new \XLite\Model\WidgetParam\Object(
                'List object', null, false, '\XLite\View\ItemsList\AItemsList'
            ),
            static::PARAM_MAX_ITEMS_COUNT => new \XLite\Model\WidgetParam\Int(
                'Maximum number of items to display in the list', 0 
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

        $this->requestParams[] = static::PARAM_PAGE_ID;
        $this->requestParams[] = static::PARAM_ITEMS_PER_PAGE;
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
        return $this->getList()->getActionURL(array(static::PARAM_PAGE_ID => $pageId));
    }

    /**
     * getFrameLength
     *
     * @return integer
     */
    protected function getFrameLength()
    {
        return min($this->getPagesPerFrame(), $this->getPagesCount());
    }

    /**
     * getFrameHalfLength
     *
     * @param boolean $shortPart Which part of frame to return OPTIONAL
     *
     * @return integer
     */
    protected function getFrameHalfLength($shortPart = true)
    {
        return call_user_func($shortPart ? 'floor' : 'ceil', $this->getFrameLength() / 2);
    }

    /**
     * getFrameStartPage
     *
     * @return integer
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
     * Return ID of the first page
     *
     * @return integer
     */
    protected function getFirstPageId()
    {
        return 0;
    }

    /**
     * Return ID of the previous page
     *
     * @return integer
     */
    protected function getPreviousPageId()
    {
        return max(0, $this->getPageId() - 1);
    }

    /**
     * Return ID of the last page
     *
     * @return integer
     */
    protected function getLastPageId()
    {
        return (int)$this->getPagesCount() - 1;
    }

    /**
     * Return ID of the next page
     *
     * @return void
     */
    protected function getNextPageId()
    {
        return min((int)$this->getPagesCount() - 1, $this->getPageId() + 1);
    }

    /**
     * Return an array with information on the pages to be displayed
     *
     * @return array
     */
    protected function getPages()
    {
        if (!isset($this->pages)) {
            $this->pages = array();

            // Define the list of pages
            $id = $this->getPreviousPageId();

            $this->pages[] = array(
                'type'  => 'previous-page',
                'num'   => $id,
                'title' => 'Previous page',
            );

            $firstId = $this->getFirstPageId();

            if ($this->getFrameStartPage() > 0) {

                $this->pages[] = array(
                    'type'  => 'first-page',
                    'num'   => $firstId,
                    'title' => 'First page',
                );

                $this->pages[] = array(
                    'type'  => 'more-pages',
                    'num'   => null,
                    'title' => '',
                );
            }

            $from = $this->getFrameStartPage();

            $till = min($this->getPagesCount()+1, $this->getFrameStartPage()+$this->getFrameLength());

            for ($i = $from; $i < $till; $i++) {
                $this->pages[] = array(
                    'type'  => 'item',
                    'num'   => $i,
                    'title' => '',
                );
            }

            $lastId = $this->getLastPageId();

            if ($this->getFrameStartPage() < $this->getPagesCount() - $this->getFrameLength()) {

                $this->pages[] = array(
                    'type'  => 'more-pages',
                    'num'   => null,
                    'title' => '',
                );

                $this->pages[] = array(
                    'type'  => 'last-page',
                    'num'   => $lastId,
                    'title' => 'Last page',
                );
            }

            $id = $this->getNextPageId();
            $this->pages[] = array(
                'type'  => 'next-page',
                'num'   => $id,
                'title' => 'Next page',
            );

            // Now prepare data for the view
            foreach ($this->pages as $k => $page) {

                $num = isset($page['num']) ? $page['num'] : null;
                $type = $page['type'];

                $isItem        = isset($num) && ('item' === $type);
                $isOmitedItems = 'more-pages' === $type;
                $isSpecialItem = !$isItem && !$isOmitedItems;

                $isCurrent  = isset($num) && $this->isCurrentPage($num);
                $isSelected = $isItem && $isCurrent;
                $isDisabled = $isSpecialItem && $isCurrent;
                $isActive   = !$isSelected && !$isOmitedItems && !$isDisabled;

                if ($isItem || ('first-page' === $type) || ('last-page' === $type)) {
                    $this->pages[$k]['text'] = $num + 1;

                } elseif ($isOmitedItems) {
                    $this->pages[$k]['text'] = '...';

                } else {
                    $this->pages[$k]['text'] = '&nbsp;';
                }

                $this->pages[$k]['page'] = !isset($num) ? null : 'page-' . $num;
                $this->pages[$k]['href'] = (!isset($num) || $isSelected || $isDisabled)
                    ? null
                    : $this->buildURLByPageId($num);

                $classes = array(
                    'item'                      => $isSpecialItem,
                    'selected'                  => $isSelected,
                    'disabled'                  => $isDisabled,
                    'active'                    => $isActive,
                    $this->pages[$k]['page']    => $isItem,
                    $type                       => true,
                );

                $css = array();

                foreach ($classes as $class => $enabled) {
                    if ($enabled) {
                        $css[] = $class;
                    }
                }

                $this->pages[$k]['classes'] = join(' ', $css);

            }

        }

        return $this->pages;

    }


    /**
     * Check whether the page is currently selected
     *
     * @param integer $pageId ID of the page to check
     *
     * @return boolean
     */
    protected function isCurrentPage($pageId)
    {
        return $this->getPageId() == $pageId;
    }

    /**
     * Return ID of the current page
     *
     * @return integer
     */
    protected function getPageId()
    {
        if (!isset($this->currentPageId)) {
            $this->currentPageId = min($this->getParam(static::PARAM_PAGE_ID), $this->getPagesCount() - 1);
        }

        return $this->currentPageId;
    }

    /**
     * Return index of the first item on the current page
     *
     * @return integer
     */
    protected function getStartItem()
    {
        return $this->getPageId() * $this->getItemsPerPage();
    }

    /**
     * Get page begin record number
     *
     * @return integer
     */
    protected function getBeginRecordNumber()
    {
        return $this->getStartItem() + 1;
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

    /**
     * Check if pages list is visible or not
     *
     * @return boolean
     */
    protected function isPagesListVisible()
    {
        return 1 < $this->getPagesCount();
    }

    /**
     * isItemsPerPageVisible
     *
     * @return boolean
     */
    protected function isItemsPerPageVisible()
    {
        return !$this->getParam(static::PARAM_ONLY_PAGES);
    }

    /**
     * isItemsPerPageSelectorVisible
     *
     * @return boolean
     */
    protected function isItemsPerPageSelectorVisible()
    {
        return $this->getParam(static::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR);
    }

    /**
     * isVisible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && ($this->isPagesListVisible() || $this->isItemsPerPageVisible());
    }

    /**
     * isVisible bottom
     *
     * @return boolean
     */
    protected function isVisibleBottom()
    {
        return $this->isVisible();
    }

    /**
     * Get specific for items list 'More' link URL
     *
     * @return string
     */
    protected function getMoreLink()
    {
        return $this->getList()->getMoreLink();
    }

    /**
     * Get specific for items list 'More' link title
     *
     * @return string
     */
    protected function getMoreLinkTitle()
    {
        return $this->getList()->getMoreLinkTitle();
    }
}
