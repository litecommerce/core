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


    /**
     * UR Lpattern border symbol
     */
    const PATTERN_BORDER_SYMBOL = '___';


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

        $this->defaultURLParams[self::SORT_CRITERION_ARG] = XLite_Model_Product::getDefaultSortCriterion();
        $this->defaultURLParams[self::SORT_ORDER_ARG] = XLite_Model_Product::getDefaultSortOrder();

        parent::init($attributes);
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
            $sessionCell = array();
        }

        $modes = $this->getDisplayModes;

        // Get default display mode from wudget attrubites
        if (
            is_array($this->attributes['widgetArguments'])
            && isset($this->attributes['widgetArguments']['displayMode'])
            && isset($modes[$this->attributes['widgetArguments']['displayMode']])
        ) {
            $this->defaultURLParams['displayMode'] = $this->attributes['widgetArguments']['displayMode'];
        }

        $this->urlParams = $this->defaultURLParams;

        foreach (array_keys($this->urlParams) as $name) {
            if (!is_null($request->$name)) {
                $this->urlParams[$name] = $request->$name;

            } elseif (isset($sessionCell[$name])) {
                $this->urlParams[$name] = $sessionCell[$name];
            }

            $sessionCell[$name] = $this->urlParams[$name];
        }

        // Override display mode if not allow visitor swicth look and feel
        if (
            is_array($this->attributes['widgetArguments'])
            && isset($this->attributes['widgetArguments']['displayModeChangable'])
            && isset($this->attributes['widgetArguments']['displayMode'])
            && !$this->attributes['widgetArguments']['displayModeChangable']
            && isset($modes[$this->attributes['widgetArguments']['displayMode']])
        ) {
            $this->urlParams['displayMode'] = $this->attributes['widgetArguments']['displayMode'];
            $sessionCell['displayMode'] = $this->urlParams['displayMode'];
        }

        $this->session->set('productsListData', $sessionCell);

        $this->urlParams = $this->getAllParams() + $this->urlParams;
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
            && is_callable($this->attributes['listFactory']);
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
        return call_user_func(
            $this->attributes['listFactory'],
            $this->urlParams[self::SORT_CRITERION_ARG],
            $this->urlParams[self::SORT_ORDER_ARG]
        );
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

        // FIXME
        $target = $params['target'];
        $action = isset($params['action']) ? $params['action'] : '';

        unset($params['target'], $params['action']);

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
        $gridColumns = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);

        return array(
            'displayMode'          => new XLite_Model_WidgetParam_List('Look and feel of a product list', XLite_View_ProductsListPage::getDefaultDisplayMode(), XLite_View_ProductsListPage::getDisplayModes()),
            'gridColumns'          => new XLite_Model_WidgetParam_List('Number of columns (for Grid mode only)', 3, $gridColumns),
            'showDescription'      => new XLite_Model_WidgetParam_Checkbox('Show product description (for List mode only)', 1),
            'showPrice'            => new XLite_Model_WidgetParam_Checkbox('Show product price', 1),
            'showAdd2Cart'         => new XLite_Model_WidgetParam_Checkbox('Show \'Add to Cart\' button', 1),
            'multipleAdd2Cart'     => new XLite_Model_WidgetParam_Checkbox('Enable multiple additions at once', 0),
            'displayModeChangable' => new XLite_Model_WidgetParam_Checkbox('Allow visitor to switch Look and feel of a product list', 1),
        );
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
    public function isDisplayModeChangable()
    {
        return !isset($this->attributes['widgetArguments']['displayModeChangable'])
            || $this->attributes['widgetArguments']['displayModeChangable'];
    }
}
