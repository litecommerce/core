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
class XLite_View_Pager extends XLite_View_Abstract
{
    /**
     * Input arguments names
     */
    const PAGE_ID_ARG = 'pageID';


    /**
     * Items-per-page range
     */
    const ITEMS_PER_PAGE_MIN = 1;
    const ITEMS_PER_PAGE_MAX = 100; 


    /**
     * Data 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $_data = array();

    /**
     * Items count 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $itemsTotal = 0;

    /**
     * Pages count 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $_pagesCount = 0;

    /**
     * First pages frame page id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $firstFramePage = 0;

    /**
     * Base object 
     * FIXME - WTF!!!?
     * 
     * @var    XLite_Model_Abstract
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $_baseObj = null;

    /**
     * Widget template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $template = 'common/pager.tpl';

    /**
     * Page id (start with 0)
     * 
     * @var    integer
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $pageID = 0;

    /**
     * Widget parameters list 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array(self::PAGE_ID_ARG);

    /**
     * Items-per-page count 
     * 
     * @var    integer
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $itemsPerPage = 4;

    /**
     * Pages per pages frame 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pagesPerFrame = 5;

    /**
     * Constructor
     * 
     * @param array $attributes widget params
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $attributes = array())
    {
        $fieldName = self::PAGE_ID_ARG;
        $this->pageID = XLite_Core_Request::getInstance()->$fieldName;

        $this->attributes['urlParams'] = array();
        $this->attributes['data'] = array();
        $this->attributes['itemsPerPage'] = $this->itemsPerPage;

        parent::__construct($attributes);
    }

    /**
     * Set properties
     *
     * @param array $attributes params to set
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function setAttributes(array $attributes)
    {
        if (isset($attributes['urlParams'])) {

            if (isset($attributes['urlParams'][XLite_View_ProductsList::ITEMS_PER_PAGE_ARG])) {
                $this->itemsPerPage = min(
                    self::ITEMS_PER_PAGE_MAX,
                    max(
                        self::ITEMS_PER_PAGE_MIN,
                        intval($attributes['urlParams'][XLite_View_ProductsList::ITEMS_PER_PAGE_ARG])
                    )
                );
            }

            if (isset($attributes['urlParams'][self::PAGE_ID_ARG])) {
                $this->pageID = $attributes['urlParams'][self::PAGE_ID_ARG];
            }
        }

        if (isset($attributes['data'])) {
            $this->setData($attributes['data']);
            unset($attributes['data']);
        }

        parent::setAttributes($attributes);
    }

    /**
     * Check widget visibility 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->_data;
    }

    /**
     * Initialization 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
        parent::initView();

        $frameCenterPage = ceil($this->pagesPerFrame / 2);

        // Calculate pages frame start
        if ($this->get('pageID') + 1 > $frameCenterPage && $this->_pagesCount > $this->pagesPerFrame) {
            $this->firstFramePage = min(
                $this->get('pageID') + 1 - $frameCenterPage,
                $this->_pagesCount - $this->pagesPerFrame
            );
        }
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
        /* TODO - WTF?
        if (!isset($this->_baseObj)) {
            $this->_baseObj = new XLite_Model_Abstract();
        }

        if ($this->_baseObj->isObjectDescriptor(current($this->_data))) {
            foreach ($this->_data as &$object) {
                $object = $this->_baseObj->descriptorToObject($object);
            }
        }
        */

        return $this->_data;
    }

    /**
     * Get page URL list 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageUrls()
    {
        $result = array();

        $end = min(
            $this->firstFramePage + $this->pagesPerFrame,
            $this->_pagesCount
        );

        for ($i = $this->firstFramePage; $i < $end; $i++) {
            $result[$i + 1] = $this->buildUrlByPageId($i);
        }

        return $result;
    }

    /**
     * Build page URL by page id 
     * 
     * @param integer $pageId Page id
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function buildUrlByPageId($pageId)
    {
        $dialog = $this->getDialog();

        if (!isset($this->attributes['urlParams'])) {
            $this->attributes['urlParams'] = $dialog->get('allParams');
        }

        $params = $this->attributes['urlParams'];
        $params[self::PAGE_ID_ARG] = $pageId;

        return $dialog->getUrl($params);
    }

    /**
     * Check - specified page is current or not 
     * 
     * @param integer $num Page id
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCurrentPage($num)
    {
        return $this->get('pageID') + 1 == $num;
    }

    /**
     * Get class name for page item 
     * 
     * @param integer $i Page id
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageClassName($i)
    {
        $classes = array(
            'page-item',
            'page-' . $i
        );

        if ($this->isCurrentPage($i)) {
            $classes[] = 'selected';
        }

        return implode(' ', $classes);
    }

    /**
     * Get border link class name 
     * 
     * @param string $type Link type (first / previous / next / last)
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBorderLinkClassName($type)
    {
        $classes = array(
            $type
        );

        if (
            (0 >= $this->get('pageID') && in_array($type, array('first', 'previous')))
            || ($this->_pagesCount - 1 <= $this->get('pageID') && in_array($type, array('last', 'next')))
        ) {
            $classes[] = $type . '-disabled';
            $classes[] = 'disabled';
        }

        return implode(' ', $classes);
    }

    /**
     * Get first page URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFirstPageUrl()
    {
        return $this->buildUrlByPageId(0);
    }

    /**
     * Get previous page URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPreviousPageUrl()
    {
        return $this->buildUrlByPageId(max(0, $this->get('pageID') - 1));
    }

    /**
     * Get next page URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNextPageUrl()
    {
        return $this->buildUrlByPageId(min($this->_pagesCount - 1, $this->get('pageID') + 1));
    }

    /**
     * Get last page URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLastPageUrl()
    {
        return $this->buildUrlByPageId($this->_pagesCount - 1);
    }

    /**
     * Set pager data 
     * 
     * @param array $value Data
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setData(array $value)
    {
        $this->itemsTotal = count($value);
        $this->_pagesCount = ceil($this->itemsTotal / $this->getItemsPerPage());

        if ($this->get('pageID') === '') {
            $this->set('pageID', 0);

        } elseif ($this->get('pageID') && $this->_pagesCount <= $this->get('pageID')) {
            $this->set('pageID', $this->_pagesCount - 1);
        }

        $this->_data = array_slice(
            $value,
            $this->get('pageID') * $this->getItemsPerPage(),
            $this->getItemsPerPage()
        );
    }

    /**
     * Get page begin record number 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBeginRecordNumber()
    {
        return $this->get('pageID') * $this->getItemsPerPage() + 1;
    }

    /**
     * Get page end record number 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEndRecordNumber()
    {
        return min(
            $this->getBeginRecordNumber() + $this->getItemsPerPage() - 1,
            $this->itemsTotal
        );
    }

    /**
     * Get items count
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItemsTotal()
    {
        return $this->itemsTotal;
    }

    /**
     * Get items count per page 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'common/pager.css';

        return $list;
    }

    /**
     * Get pages count 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPagesCount()
    {
        return $this->_pagesCount;
    }

    /**
     * Get items-per-page range as javascript object definition 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItemsPerPageRange()
    {
        return '{ min: ' . self::ITEMS_PER_PAGE_MIN . ', max: ' . self::ITEMS_PER_PAGE_MAX . ' }';
    }

    /**
     * Get pager class name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPagerVisible()
    {
        return 1 < $this->_pagesCount;
    }
}
