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
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * Cached pages 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $pages = null;


    /**
     * Return number of items per page
     * 
     * @return integer 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getItemsPerPageDefault();

    /**
     * Return number of pages to display
     * 
     * @return integer 
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
     * @return integer 
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
     * @return integer 
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
     * @return integer 
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
     * @return integer 
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
     * Build page URL by page ID
     *
     * @param integer $pageId Page ID
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function buildUrlByPageId($pageId)
    {
        return $this->getList()->getActionURL(array(self::PARAM_PAGE_ID => $pageId));
    }

    /**
     * getFrameLength 
     * 
     * @return integer 
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
     * @param boolean $shortPart Which part of frame to return OPTIONAL
     *  
     * @return integer 
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
     * @return integer 
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
     * Return ID of the first page
     * 
     * @return integer 
     * @access protected
     * @since  3.0.0
     */
    protected function getFirstPageId()
    {
        return 0;
    }

    /**
     * Return ID of the previous page
     * 
     * @return integer 
     * @access protected
     * @since  3.0.0
     */
    protected function getPreviousPageId()
    {
        return max(0, $this->getPageId() - 1);
    }

    /**
     * Return ID of the last page
     * 
     * @return integer 
     * @access protected
     * @since  3.0.0
     */
    protected function getLastPageId()
    {
        return (int)$this->getPagesCount() - 1;
    }

    /**
     * Return ID of the next page
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getNextPageId()
    {
        return min((int)$this->getPagesCount() - 1, $this->getPageId() + 1);
    }

    /**
     * Return an array with information on the pages to be displayed
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getPages()
    {
        if (is_null($this->pages)) {

            $this->pages = array();

            // Define the list of pages
            $id = $this->getPreviousPageId();

            $this->pages[] = array(
                'type'  => 'previous-page',
                'num'   => $id,
                'title' => $this->t('Previous page'),
            );
    
            $firstId = $this->getFirstPageId();

            if ($this->getFrameStartPage() > 0) {

                $this->pages[] = array(
                    'type'  => 'first-page',
                    'num'   => $firstId,
                    'title' => $this->t('First page'),
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
                    'title' => $this->t('Last page'),
                );
            }

            $id = $this->getNextPageId();
            $this->pages[] = array(
                'type'  => 'next-page',
                'num'   => $id,
                'title' => $this->t('Next page'),
            );

            // Now prepare data for the view
            foreach ($this->pages as $k => $page) {

                $num = isset($page['num']) ? $page['num'] : null;
                $type = $page['type'];

                $isItem        = !is_null($num) && ('item' === $type);
                $isOmitedItems = 'more-pages' === $type;
                $isSpecialItem = !$isItem && !$isOmitedItems;

                $isCurrent  = !is_null($num) && $this->isCurrentPage($num);
                $isSelected = $isItem && $isCurrent;
                $isDisabled = $isSpecialItem && $isCurrent;
                $isActive   = !$isSelected && !$isOmitedItems && !$isDisabled;

                $this->pages[$k]['text'] = ($isItem || ('first-page' === $type) || ('last-page' === $type))
                        ? ($num + 1) 
                        : ($isOmitedItems ? '...' : '&nbsp;');

                $this->pages[$k]['page'] = is_null($num) 
                    ? null 
                    : 'page-' . $num;

                $this->pages[$k]['href'] = (is_null($num) || $isSelected || $isDisabled) 
                    ? null 
                    : $this->buildUrlByPageId($num);

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
     * @access protected
     * @since  3.0.0
     */
    protected function isCurrentPage($pageId)
    {
        return $this->getPageId() == $pageId;
    }

    /**
     * Return ID of the current page
     * 
     * @return integer 
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
     * @return integer 
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
     * @return integer 
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
     * @return integer 
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
     * @return boolean 
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
     * @return boolean 
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
     * @return boolean 
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
     * @return boolean 
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
     * @param integer                $start Index of the first item on the page OPTIONAL
     * @param integer                $count Number of items per page OPTIONAL
     * @param \XLite\Core\CommonCell $cnd   Search condition
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
