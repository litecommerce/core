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
     * It's the defeult valueof items to display per page 
     */

    const ITEMS_PER_PAGE_DEFAULT = 10;

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
     * itemsTotal 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $itemsTotal;

    /**
     * itemsPerPage 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $itemsPerPage;

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
     * pagesPerFrame 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $pagesPerFrame = 5;


    /**
     * Return list of items to display on the current page 
     * 
     * @param int $start index of the first item on the page
     * @param int $count number of items per page
     *  
     * @return array|\Doctrine\ORM\PersistentCollection
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getDataFrame($start, $count);


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/pager.tpl';
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
        if (!isset($this->itemsTotal)) {
            $this->itemsTotal = count($this->getParam(self::PARAM_DATA));
        }

        return $this->itemsTotal;
    }

    /**
     * getItemsPerPageDefault 
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageDefault()
    {
        return self::ITEMS_PER_PAGE_DEFAULT;
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
            $current = intval($this->getParam(self::PARAM_ITEMS_PER_PAGE));
            $this->itemsPerPage = max(
                min(self::ITEMS_PER_PAGE_MAX, $current),
                max(self::ITEMS_PER_PAGE_MIN, $current)
            );
        }

        return $this->itemsPerPage;
    }

    /**
     * Get pages count 
     * 
     * @return integer
     * @access public
     * @since  3.0.0
     */
    public function getPagesCount()
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
            self::PARAM_ITEMS_PER_PAGE => new \XLite\Model\WidgetParam\Int(
                'Items per page', $this->getItemsPerPageDefault(), true
            ),
            self::PARAM_DATA => new \XLite\Model\WidgetParam\Collection(
                'Data', array()
            ),
            self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR => new \XLite\Model\WidgetParam\Checkbox(
                'Show "Items per page" selector', true, true
            ),
        );

        $this->requestParams[] = self::PARAM_PAGE_ID;
        $this->requestParams[] = self::PARAM_ITEMS_PER_PAGE;
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
        return array(self::PARAM_PAGE_ID => $pageId) + $this->getRequestParams();
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
        $pageId = $this->getPageId() - ceil($this->pagesPerFrame / 2);

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

        $this->pageURLs = array_slice($this->pageURLs, $this->getFrameStartPage(), $this->pagesPerFrame, true);
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
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getData();
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
        $list[] = 'common/pager.css';

        return $list;
    }

    /**
     * Return page data 
     * 
     * @return array|\Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getData()
    {
        return $this->getDataFrame($this->getStartItem(), $this->getItemsPerPage());
    }
}
