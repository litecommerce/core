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
abstract class APager extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_PAGE_ID             = 'pageId';
    const PARAM_ITEMS_COUNT         = 'itemsCount';
    const PARAM_ONLY_PAGES          = 'onlyPages';
    const PARAM_LIST_REQUEST_PARAMS = 'listRequestParams';

    /**
     * Page short names
     */

    const PAGE_FIRST    = 'first';
    const PAGE_PREVIOUS = 'previous';
    const PAGE_NEXT     = 'next';
    const PAGE_LAST     = 'last';


    /**
     * pageId 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $pageId;

    /**
     * pagesCount 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $pagesCount;

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
    abstract protected function getItemsPerPage();

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
            self::PARAM_LIST_REQUEST_PARAMS => new \XLite\Model\WidgetParam\Collection(
                'Parent list request params', array()
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
    }

    /**
     * getPageIndexNotations 
     * 
     * @param mixed $index page notation
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
     * Return list of page URL params 
     * 
     * @param int $pageId page ID
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getPageURLParams($pageId)
    {
        return array(self::PARAM_PAGE_ID => $pageId) 
            + $this->getRequestParamsHash() 
            + $this->getParam(self::PARAM_LIST_REQUEST_PARAMS);
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
        return $this->getUrl($this->getPageURLParams($this->getPageIdByNotation($pageId)));
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
        $pageId = $this->getPageId() - ceil($this->getPagesPerFrame() / 2);

        return (0 > $pageId) ? 0 : $pageId;
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

        $this->pageURLs = array_slice($this->pageURLs, $this->getFrameStartPage(), $this->getPagesPerFrame(), true);
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
        $pageId = $this->getPageId();

        return (0 >= $pageId && in_array($type, array(self::PAGE_FIRST, self::PAGE_PREVIOUS)))
            || ($this->getPagesCount() - 1 <= $pageId && in_array($type, array(self::PAGE_LAST, self::PAGE_NEXT)));
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
     * getLinkClassName 
     * 
     * @param mixed $index page notation
     *  
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getLinkClassName($index)
    {
        return $this->getPageIdByNotation($index);
    }

    /**
     * Get border link class name
     *
     * @param string $type link type (first / previous / next / last)
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getBorderLinkClassName($type)
    {
        return $type . ' ' . ($this->isFurthermostPage($type) ? $type . '-disabled disabled' : '');
    }

    /**
     * getPageClassName 
     * 
     * @param int $pageId current page ID
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPageClassName($pageId)
    {
        return 'page-item page-' . $pageId . ' ' . ($this->isCurrentPage($pageId) ? 'selected' : '');
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
        if (!isset($this->pageId)) {
            $this->pageId = min($this->getParam(self::PARAM_PAGE_ID), $this->getPagesCount() - 1);
        }

        return $this->pageId;
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
        return true;
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
