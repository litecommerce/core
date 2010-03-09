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
 * Products list
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_ProductsList extends XLite_View_Abstract
{
    /**
     * Input arguments names
     */
    const SORT_CRITERION_ARG   = 'sortCrit';
    const SORT_ORDER_ARG       = 'sortOrder';
    const DISPLAY_MODE_ARG     = 'displayMode';
    const ITEMS_PER_PAGE_ARG   = 'itemsPerPage';
    const CELL_NAME_ARG        = 'cellName';


    /**
     * URL pattern border symbol
     */
    const PATTERN_BORDER_SYMBOL = '___';


    /**
     * Default search data cell name
     */
    const DEFAULT_CELL_NAME = 'default';


    /**
     * Default page widget class name
     */
    const DEFAULT_PAGE_WIDGET_CLASS = 'XLite_View_ProductsListPage';


    /**
     * Widget template 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $template = 'products_list/body.tpl';

    /**
     * Current page arguments
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $urlParams = array();

    /**
     * Default URL parameters
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultURLParams = array(
        self::SORT_CRITERION_ARG      => 'price',
        self::SORT_ORDER_ARG          => 'asc',
        self::DISPLAY_MODE_ARG        => 'grid',
        self::ITEMS_PER_PAGE_ARG      => 4,
        XLite_View_Pager::PAGE_ID_ARG => 0,
    );

    /**
     * List cache 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $listCache = null;

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init(array $attributes = array())
    {
        $this->attributes['listFactory'] = false;
        $this->attributes['widgetArguments'] = array();
        $this->attributes['cellName'] = self::DEFAULT_CELL_NAME;
        $this->attributes['pageWidgetClass'] = self::DEFAULT_PAGE_WIDGET_CLASS;

        $this->defaultURLParams[self::SORT_CRITERION_ARG] = XLite_Model_Product::getDefaultSortCriterion();
        $this->defaultURLParams[self::SORT_ORDER_ARG] = XLite_Model_Product::getDefaultSortOrder();

        parent::init($attributes);
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
        if (isset($attributes['widgetArguments']) && !is_array($attributes['widgetArguments'])) {
            unset($attributes['widgetArguments']);
        }

        if (isset($attributes['cellName']) && (!is_string($attributes['cellName']) || !$attributes['cellName'])) {
            unset($attributes['cellName']);
        }

        if (isset($attributes['pageWidgetClass']) && (!is_string($attributes['pageWidgetClass']) || !class_exists($attributes['pageWidgetClass']))) {
            unset($attributes['pageWidgetClass']);
        }

        parent::setAttributes($attributes);
    }

    /**
     * Initialization
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initView()
    {
        parent::initView();

        $request = XLite_Core_Request::getInstance();
        $sessionCell = $this->session->get('productsListData');
        if (!is_array($sessionCell)) {
            $sessionCell = array($this->attributes['cellName'] => array());

        } elseif (
            !isset($sessionCell[$this->attributes['cellName']])
            || !is_array($sessionCell[$this->attributes['cellName']])
        ) {
            $sessionCell[$this->attributes['cellName']] = array();
        }

        $sessionSubCell = $sessionCell[$this->attributes['cellName']];

        $modes = $this->getDisplayModes();

        // Get default display mode from wudget attrubites
        if (
            isset($this->attributes['widgetArguments']['displayMode'])
            && isset($modes[$this->attributes['widgetArguments']['displayMode']])
        ) {
            $this->defaultURLParams['displayMode'] = $this->attributes['widgetArguments']['displayMode'];
        }

        $this->urlParams = $this->defaultURLParams;

        $cellNameField = self::CELL_NAME_ARG;
        $getFromRequest = (isset($request->$cellNameField) && $request->$cellNameField == $this->attributes['cellName'])
            || $this->attributes['cellName'] == self::DEFAULT_CELL_NAME;

        foreach (array_keys($this->urlParams) as $name) {
            if ($getFromRequest && !is_null($request->$name)) {
                $this->urlParams[$name] = $request->$name;

            } elseif (isset($sessionSubCell[$name])) {
                $this->urlParams[$name] = $sessionSubCell[$name];
            }

            $sessionSubCell[$name] = $this->urlParams[$name];
        }

        // Override display mode if not allow visitor swicth look and feel
        if (
            isset($this->attributes['widgetArguments']['displayModeAdjustable'])
            && isset($this->attributes['widgetArguments']['displayMode'])
            && !$this->attributes['widgetArguments']['displayModeAdjustable']
            && isset($modes[$this->attributes['widgetArguments']['displayMode']])
        ) {
            $this->urlParams['displayMode'] = $this->attributes['widgetArguments']['displayMode'];
            $sessionSubCell['displayMode'] = $this->urlParams['displayMode'];
        }

        $sessionCell[$this->attributes['cellName']] = $sessionSubCell;
        $this->session->set('productsListData', $sessionCell);

        $this->urlParams = $this->getAllParams() + $this->urlParams;

        $this->urlParams[$cellNameField] = $this->attributes['cellName'];
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible()
            && is_array($this->attributes['listFactory'])
            && is_callable($this->attributes['listFactory'])
            && $this->getList();
    }

    /**
     * Get list 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getList()
    {
        if (is_null($this->listCache)) {
            $this->listCache = call_user_func(
                $this->attributes['listFactory'],
                $this->urlParams[self::SORT_CRITERION_ARG],
                $this->urlParams[self::SORT_ORDER_ARG]
            );
        }

        return $this->listCache;
    }

    /**
     * Get page list 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageList()
    {
        return isset($this->widgets['pager']) ? $this->widgets['pager']->getPageData() : $this->getList();
    }

    /**
     * Get items count
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItemsCount()
    {
        return count($this->getList());
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
        return isset($this->widgets['pager']) ? $this->widgets['pager']->getPagesCount() : 1;
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
        return '{ min: ' . XLite_View_Pager::ITEMS_PER_PAGE_MIN . ', max: ' . XLite_View_Pager::ITEMS_PER_PAGE_MAX . ' }';
    }

    /**
     * Build page URL 
     * 
     * @param integer $pageId        Page number
     * @param string  $sortCriterion Sort criterion
     * @param string  $sortOrder     Sort order
     * @param string  $displayMode   Display mode
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function buildPageURL($pageId = null, $sortCriterion = null, $sortOrder = null, $displayMode = null)
    {
        $params = $this->assembleURLParams($pageId, $sortCriterion, $sortOrder, $displayMode);

        $target = 'main';
        $action = '';
        
        if (isset($params['target'])) {
            $target = $params['target'];
            unset($params['target']);
        }

        if (isset($params['action'])) {
            $action = $params['action'];
            unset($params['action']);
        }

        return $this->buildURL($target, $action, $params);
    }

    /**
     * Get current URL parameters
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURLParams()
    {
        return $this->assembleURLParams();
    }

    /**
     * Assemble URL parameters
     * 
     * @param integer $pageId        Page number
     * @param string  $sortCriterion Sort criterion
     * @param string  $sortOrder     Sort order
     * @param string  $displayMode   Display mode
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assembleURLParams($pageId = null, $sortCriterion = null, $sortOrder = null, $displayMode = null, $itemsPerPage = null)
    {
        $params = $this->urlParams;

        // Set page id
        if (!is_null($pageId) && 0 < strlen($pageId)) {
            $params['pageID'] = $pageId;

        } elseif (isset($this->widgets['pager'])) {
            $params['pageID'] = $this->widgets['pager']->get('pageID');
        }

        // Set sort criterion
        if (!is_null($sortCriterion) && 0 < strlen($sortCriterion)) {
            $params[self::SORT_CRITERION_ARG] = $sortCriterion;
        }

        // Set sort order
        if (!is_null($sortOrder) && 0 < strlen($sortOrder)) {
            $params[self::SORT_ORDER_ARG] = $sortOrder;
        }

        // Set display mode
        if (!is_null($displayMode) && 0 < strlen($displayMode)) {
            $params[self::DISPLAY_MODE_ARG] = $displayMode;
        }

        // Set items per page count
        if (!is_null($itemsPerPage) && $itemsPerPage) {
            $params[self::ITEMS_PER_PAGE_ARG] = $itemsPerPage;
        }

        return $params;
    }

    /**
     * Get display modes list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayModes()
    {
        return XLite_View_ProductsListPage::getDisplayModes();
    }

    /**
     * Get current display mode
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayMode()
    {
        return $this->urlParams[self::DISPLAY_MODE_ARG];
    }

    /**
     * Check - specified display mode is selected or not 
     * 
     * @param string $displayMode Display mode
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayModeSelected($displayMode)
    {
        return $displayMode == $this->getDisplayMode();
    }

    /**
     * Get display mode link class name 
     * 
     * @param string $displayMode Display mode
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayModeLinkClassName($displayMode)
    {
        $classes = array(
            'list-type-' . $displayMode
        );

        if ('grid' == $displayMode) {
            $classes[] = 'first';
        }

        if ('table' == $displayMode) {
            $classes[] = 'last';
        }

        if ($this->isDisplayModeSelected($displayMode)) {
            $classes[] = 'selected';
        }

        return implode(' ', $classes);
    }

    /**
     * Get sort criterions 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSortCriterions()
    {
        return XLite_Model_Product::getSortCriterions();
    }

    /**
     * Check - specified sort criterion is selected or not
     * 
     * @param string $criterion Sort criterion
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSortCriterionSelected($criterion)
    {
        return $criterion == $this->urlParams[self::SORT_CRITERION_ARG];
    }

    /**
     * Check - sort order is ascending or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSortOrderAsc()
    {
        return 'asc' == $this->urlParams[self::SORT_ORDER_ARG];
    }

    /**
     * Get sort order link class name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSortOrderLinkClassName()
    {
        $classes = array(
            'sort-order'
        );

        $classes[] = $this->isSortOrderAsc() ? 'sort-order-asc' : 'sort-order-desc';

        return implode(' ', $classes);
    }

    /**
     * Get inverted sort order link 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSortOrderInvLink()
    {
        return $this->buildPageURL(
            null,   
            null,
            $this->isSortOrderAsc() ? 'desc' : 'asc'
        );
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'products_list/products_list.js';

        return $list;
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

        $list[] = 'products_list/products_list.css';

        return $list;
    }

    /**
     * Get page URL pattern 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageURLPattern()
    {
        $params = $this->getCommonPatternParams();

        $target = $params['target'];
        $action = isset($params['action']) ? $params['action'] : '';

        unset($params['target'], $params['action']);

        return $this->buildURL($target, $action, $params);
    }

    /**
     * Get page URL pattern (for AJAX request)
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageURLPatternAJAX()
    {
        $params = $this->getCommonPatternParams();

        $params = array_merge($params, $this->getAJAXSpecificPArams($params));

        unset($params['target'], $params['action']);

        return $this->buildURL('get_widget', '', $params);
    }

    /**
     * Get common pattern parameters
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCommonPatternParams()
    {
        return $this->assembleURLParams(
            self::PATTERN_BORDER_SYMBOL . XLite_View_Pager::PAGE_ID_ARG . self::PATTERN_BORDER_SYMBOL,
            self::PATTERN_BORDER_SYMBOL . self::SORT_CRITERION_ARG . self::PATTERN_BORDER_SYMBOL,
            self::PATTERN_BORDER_SYMBOL . self::SORT_ORDER_ARG . self::PATTERN_BORDER_SYMBOL,
            self::PATTERN_BORDER_SYMBOL . self::DISPLAY_MODE_ARG . self::PATTERN_BORDER_SYMBOL,
            self::PATTERN_BORDER_SYMBOL . self::ITEMS_PER_PAGE_ARG . self::PATTERN_BORDER_SYMBOL
        );
    }

    /**
     * Get AJAX specific parameters 
     * 
     * @param array $params Parameters
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAJAXSpecificParams(array $params)
    {
        return array(
            'widget_target' => $params['target'],
            'widget_action' => isset($params['action']) ? $params['action'] : '',
            'class'         => get_class($this->attributes['listFactory'][0])
        );
    }

    /**
     * Get display modes as javascript array defination 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayModesForJS()
    {
        return '[\'' . implode('\', \'', array_keys($this->getDisplayModes())) . '\']';
    }

    /**
     * Get URL translation table 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getURLTranslationTable()
    {
        return array(
            'pageId'        => XLite_View_Pager::PAGE_ID_ARG,
            'sortCriterion' => self::SORT_CRITERION_ARG,
            'sortOrder'     => self::SORT_ORDER_ARG,
            'displayMode'   => self::DISPLAY_MODE_ARG,
            'itemsPerPage'  => self::ITEMS_PER_PAGE_ARG,
        );
    }

    /**
     * Get URL translation table as javascript object definition
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURLTranslationTableForJS()
    {
        $list = array();

        foreach ($this->getURLTranslationTable() as $key => $value) {
            $list[] = $key . ': \'' . self::PATTERN_BORDER_SYMBOL . $value . self::PATTERN_BORDER_SYMBOL . '\'';
        }

        return '{ ' . implode(', ', $list) . ' }';
    }

    /**
     * Get widget params 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    static public function getWidgetParamsList()
    {
        $list = XLite_View_ProductsListPage::getWidgetParamsList();

        $list['displayModeAdjustable'] = new XLite_Model_WidgetParam_Checkbox('Allow visitor to switch Look and feel of a product list', 1);

        $list['sortCriterionAdjustable'] = new XLite_Model_WidgetParam_Checkbox('Allow visitor to sort a product list', 1);
        $list['sortCriterion'] = new XLite_Model_WidgetParam_List(
            'Default sort criterion',
            XLite_Model_Product::getDefaultSortCriterion(),
            XLite_Model_Product::getSortCriterions()
        );
        $list['sortOrder'] = new XLite_Model_WidgetParam_List(
            'Default sort order',
            XLite_Model_Product::getDefaultSortOrder(),
            array('asc' => 'Ascending', 'desc' => 'Descending')
        );
        
        $itemsPerPageList = range(XLite_View_Pager::ITEMS_PER_PAGE_MIN, XLite_View_Pager::ITEMS_PER_PAGE_MAX);
        $itemsPerPageList = array_combine($itemsPerPageList, $itemsPerPageList);
        $list['itemsPerPageAdjustable'] = new XLite_Model_WidgetParam_Checkbox('Allow visitor to change items-per-page', 1);
        $list['itemsPerPage'] = new XLite_Model_WidgetParam_List('Default items per page', XLite_View_Pager::DEFAULT_ITEMS_PER_PAGE, $itemsPerPageList);

        $list['allItemsPerPage'] = new XLite_Model_WidgetParam_Checkbox('Show all items into one page', 0);

        return $list;
    }

    /**
     * Get inherited widget arguments
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInheritedWidgetArguments()
    {
        return $this->attributes['widgetArguments'];
    }

    /**
     * Check - show Add to cart button or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayModeAdjustable()
    {
        return !isset($this->attributes['widgetArguments']['displayModeAdjustable'])
            || $this->attributes['widgetArguments']['displayModeAdjustable'];
    }

    /**
     * Get cell name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCellName()
    {
        return $this->attributes['cellName'];
    }

    /**
     * Get container id 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getContainerId()
    {
        return $this->attributes['cellName'] . 'Container';
    }

    /**
     * Check sort criterion block visibility 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSortCriterionVisible()
    {
        return !isset($this->attributes['widgetArguments']['sortCriterionAdjustable'])
            || $this->attributes['widgetArguments']['sortCriterionAdjustable'];
    }


    /**
     * Check - items-per-page selector visible or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isItemsPerPageSelectorVisible()
    {
        return !isset($this->attributes['widgetArguments']['itemsPerPageAdjustable'])
            || $this->attributes['widgetArguments']['itemsPerPageAdjustable'];
    }

    /**
     * Check - pager row is visible or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPagerVisible()
    {
        return !isset($this->attributes['widgetArguments']['allItemsPerPage']) || !$this->attributes['widgetArguments']['allItemsPerPage'];
    }

    /**
     * Get page widget class name
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageWidgetClass()
    {
        return $this->attributes['pageWidgetClass'];
    }
}
