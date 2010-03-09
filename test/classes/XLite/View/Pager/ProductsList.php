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
 * @subpackage ____sub_package____
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
class XLite_View_Pager_ProductsList extends XLite_View_Pager
{
    /**
     * pagesPerFrame 
     * 
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $pagesPerFrame = 5;


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

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('products_list/pager.tpl');
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
        return 'javascript: productsList.showPage(' . $pageId . ');';
    }

    /**
     * Get first page URL
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFirstPageUrl()
    {
        return $this->buildUrlByPageId(0);
    }

    /**
     * Get previous page URL
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPreviousPageUrl()
    {
        return $this->buildUrlByPageId(max(0, $this->getParam(self::PARAM_PAGE_ID) - 1));
    }

    /**
     * Get next page URL
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getNextPageUrl()
    {
        return $this->buildUrlByPageId(min($this->getPagesCount() - 1, $this->getParam(self::PARAM_PAGE_ID) + 1));
    }

    /**
     * Get last page URL
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLastPageUrl()
    {
        return $this->buildUrlByPageId($this->getPagesCount() - 1);
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
        $pageId = $this->getParam(self::PARAM_PAGE_ID) - ceil($this->pagesPerFrame / 2);
        $minFramePageId = $this->getPagesCount() - $this->pagesPerFrame;

        return (0 > $pageId) ? 0 : (($minFramePageId < $pageId) ? $minFramePageId : $pageId);
    }

    /**
     * definePageURLs
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function definePageURLs()
    {
        parent::definePageURLs();

        $this->pageURLs = array_slice(
            $this->pageURLs,
            $this->getFrameStartPage(),
            $this->pagesPerFrame,
            true
        );
    }

    /**
     * isFurthermostPage 
     * 
     * @param string $type link type (first / previous / next / last)_
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isFurthermostPage($type)
    {
        $pageId = $this->getParam(self::PARAM_PAGE_ID);

        return (0 >= $pageId && in_array($type, array('first', 'previous')))
            || ($this->getPagesCount() - 1 <= $pageId && in_array($type, array('last', 'next')));
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
     * Get page begin record number
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getBeginRecordNumber()
    {
        return $this->getParam(self::PARAM_PAGE_ID) * $this->getItemsPerPage() + 1;
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
     * Get items-per-page range as javascript object definition
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageRange()
    {
        return '{ min: ' . self::ITEMS_PER_PAGE_MIN . ', max: ' . self::ITEMS_PER_PAGE_MAX . ' }';
    }
}

