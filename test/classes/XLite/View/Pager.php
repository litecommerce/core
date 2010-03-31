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

/**
 * Pager 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Pager extends XLite_View_Abstract
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
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $pageId = null;

    /**
     * Data 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $itemsTotal = null;

    /**
     * itemsPerPage 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $itemsPerPage = null;

    /**
     * pagesCount 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $pagesCount = null;

    /**
     * pageURLs 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $pageURLs = null;


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
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PAGE_ID => new XLite_Model_WidgetParam_Int(
                'Page ID', 0
            ),
            self::PARAM_ITEMS_PER_PAGE => new XLite_Model_WidgetParam_Int(
                'Items per page', intval($this->config->General->products_per_page), true
            ),
            self::PARAM_DATA => new XLite_Model_WidgetParam_Array(
                'Data', array()
            ),
            self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR => new XLite_Model_WidgetParam_Checkbox(
                'Show "Items per page" selector', true, true
            ),
        );

        $this->requestParams[] = self::PARAM_PAGE_ID;

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('common/pager.tpl'); 

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
        return $this->getUrl($this->getPageURLParams($pageId));
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
     * Get currenct page data
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getPageData();
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
        return array_merge(parent::getCSSFiles(), array('common/pager.css'));
    }
}
