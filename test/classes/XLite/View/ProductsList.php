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
 * Abstract product list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_View_ProductsList extends XLite_View_Container
{
    /**
     * Widget param names
     */

    const PARAM_WIDGET_TYPE  = 'widgetType';
    const PARAM_DISPLAY_MODE = 'displayMode';
    const PARAM_GRID_COLUMNS = 'gridColumns';
    const PARAM_SORT_BY      = 'sortBy';
    const PARAM_SORT_ORDER   = 'sortOrder';

    const PARAM_SHOW_DESCR     = 'showDescription';
    const PARAM_SHOW_PRICE     = 'showPrice';
    const PARAM_SHOW_THUMBNAIL = 'showThumbnail';
    const PARAM_SHOW_ADD2CART  = 'showAdd2Cart';

    const PARAM_SHOW_ALL_ITEMS_PER_PAGE    = 'showAllItemsPerPage';
    const PARAM_SHOW_DISPLAY_MODE_SELECTOR = 'showDisplayModeSelector';
    const PARAM_SHOW_SORT_BY_SELECTOR      = 'showSortBySelector';

    /*
     * The maximum number of items (products) displayed in the sidebar widget
     */

    const PARAM_SIDEBAR_MAX_ITEMS = 'sidebarMaxItems';

    /*
     * Allowed widget types
     */

    const WIDGET_TYPE_SIDEBAR = 'sidebar';
    const WIDGET_TYPE_CENTER  = 'center';

    /**
     * Allowed display modes
     */

    const DISPLAY_MODE_LIST  = 'list';
    const DISPLAY_MODE_GRID  = 'grid';
    const DISPLAY_MODE_TABLE = 'table';

    /**
     * Allowed sort criterions
     */

    const SORT_BY_MODE_PRICE = 'price';
    const SORT_BY_MODE_NAME  = 'name';
    const SORT_BY_MODE_SKU   = 'sku';

    /**
     * SQL orederby directions
     */

    const SORT_ORDER_ASC  = 'asc';
    const SORT_ORDER_DESC = 'desc';

    /**
     * Columns number range
     */

    const GRID_COLUMNS_MIN = 1;
    const GRID_COLUMNS_MAX = 5;

    /**
     * Top-level directory with widget templates
     */

    const TEMPLATES_DIR = 'products_list';

    /**
     * Template to use for sidebars
     */

    const TEMPLATE_SIDEBAR = 'common/sidebar_box.tpl';


    /**
     * Widget types 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $widgetTypes = array(
        self::WIDGET_TYPE_SIDEBAR  => 'Sidebar',
        self::WIDGET_TYPE_CENTER   => 'Center',
    );

    /**
     * Display modes
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_GRID  => 'Grid',
        self::DISPLAY_MODE_LIST  => 'List',
        self::DISPLAY_MODE_TABLE => 'Table',
    );

    /**
     * sortByModes 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sortByModes = array(
        self::SORT_BY_MODE_PRICE => 'Price',
        self::SORT_BY_MODE_NAME  => 'Name',
        self::SORT_BY_MODE_SKU   => 'SKU',
    );

    /**
     * sortOrderModes 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sortOrderModes = array(
        self::SORT_ORDER_ASC  => 'Ascending',
        self::SORT_ORDER_DESC => 'Descending',
    );


    /**
     * defaultTemplate
     *
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $defaultTemplate = 'common/dialog.tpl';

    /**
     * commonParams
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $commonParams = null;

    /**
     * pager
     *
     * @var    XLite_View_Pager
     * @access protected
     * @since  3.0.0
     */
    protected $pager = null;


    /**
     * Return products list
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getData();


    /**
     * getDisplayMode 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDisplayMode()
    {
        return $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Return default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->defaultTemplate;
    }

    /**
     * getPageBodyTemplate 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPageBodyTemplate()
    {
        return $this->getDir() . '/' . ($this->isSideBarBox() ? 'sidebar' : $this->getDisplayMode()) . '/body.tpl';
    }

    /**
     * getPagerClass 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPagerClass()
    {
        return 'XLite_View_Pager_ProductsList';
    }

    /**
     * getPagerName 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPagerName()
    {
        return 'pager';
    }

    /**
     * Get pager parameters list
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getPagerParams()
    {
        return array(
            self::PARAM_SESSION_CELL     => $this->getSessionCell(),
            XLite_View_Pager::PARAM_DATA => $this->getData()
        );
    }

    /**
     * Get pager 
     * 
     * @return XLite_View_Pager
     * @access protected
     * @since  3.0.0
     */
    protected function getPager()
    {
        if (!isset($this->pager)) {
            $this->pager = $this->getWidget($this->getPagerParams(), $this->getPagerClass());
        }

        return $this->pager;
    }

    /**
     * Check - pager control row is visible or not
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isPagerVisible()
    {
        return !$this->getParam(self::PARAM_SHOW_ALL_ITEMS_PER_PAGE) && !$this->isSideBarBox();
    }

    /**
     * Check - pages list is visible or not
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isPagesListVisible()
    {
        return 1 < $this->getPager()->getPagesCount();
    }

    /**
     * getPageData 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getPageData()
    {
        return $this->isPagerVisible() ? $this->getPager()->getPageData() : $this->getData();
    }

    /**
     * Get products list for sidebar widget 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSideBarData()
    {
        return array_slice($this->getData(), 0, $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS));
    }

    /**
     * Check status of 'More...' link for sidebar list
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isShowMoreLink()
    {
        return false;
    }

    /**
     * Get 'More...' link URL for sidebar list
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkURL()
    {
        return null;
    }

    /**
     * Get 'More...' link text for sidebar list
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkText()
    {
        return 'More...';
    }

    /**
     * Get widget templates directory
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return self::TEMPLATES_DIR;
    }

    /**
     * Get grid columns range 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getGridColumnsRange()
    {
        $range = array_merge(
            array('css-defined' => 'css-defined'),
            range(self::GRID_COLUMNS_MIN, self::GRID_COLUMNS_MAX)
        );

        return array_combine($range, $range);
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
            self::PARAM_WIDGET_TYPE => new XLite_Model_WidgetParam_List(
                'Widget type', self::WIDGET_TYPE_CENTER, true, $this->widgetTypes
            ),
            self::PARAM_DISPLAY_MODE => new XLite_Model_WidgetParam_List(
                'Display mode', self::DISPLAY_MODE_GRID, true, $this->displayModes
            ),
            self::PARAM_GRID_COLUMNS => new XLite_Model_WidgetParam_List(
                'Number of columns (for Grid mode only)', 3, true, $this->getGridColumnsRange()
            ),
            self::PARAM_SHOW_DESCR => new XLite_Model_WidgetParam_Checkbox(
                'Show product description (for List mode only)', true, true
            ),
            self::PARAM_SHOW_PRICE => new XLite_Model_WidgetParam_Checkbox(
                'Show product price', true, true
            ),
            self::PARAM_SHOW_THUMBNAIL => new XLite_Model_WidgetParam_Checkbox(
                'Show product thumbnail', true, true
            ),
            self::PARAM_SHOW_ADD2CART => new XLite_Model_WidgetParam_Checkbox(
                'Show \'Add to Cart\' button', true, true
            ),
            self::PARAM_SIDEBAR_MAX_ITEMS => new XLite_Model_WidgetParam_Int(
                'The maximum number of products displayed in sidebar', 5, true
            ),
            self::PARAM_SORT_BY => new XLite_Model_WidgetParam_List(
                'Sort by', 'price', false, $this->sortByModes
            ),
            self::PARAM_SORT_ORDER => new XLite_Model_WidgetParam_List(
                'Sort order', 'asc', false, $this->sortOrderModes
            ),
            self::PARAM_SHOW_ALL_ITEMS_PER_PAGE => new XLite_Model_WidgetParam_Checkbox(
                'Display all items on one page', false, true
            ),
            self::PARAM_SHOW_DISPLAY_MODE_SELECTOR => new XLite_Model_WidgetParam_Checkbox(
                'Show "Display mode" selector', true, true
            ),
            self::PARAM_SHOW_SORT_BY_SELECTOR => new XLite_Model_WidgetParam_Checkbox(
                'Show "Sort by" selector', true, true
            ),
        );

        $this->requestParams[] = self::PARAM_DISPLAY_MODE;
        $this->requestParams[] = self::PARAM_SORT_BY;
        $this->requestParams[] = self::PARAM_SORT_ORDER;
    }

    /**
     * isSideBarBox 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isSideBarBox()
    {
        return self::WIDGET_TYPE_SIDEBAR == $this->getParam(self::PARAM_WIDGET_TYPE);
    }

    /**
     * getJSArray 
     * 
     * @param array $params params to use
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getJSArray(array $params)
    {
        $result = array();

        foreach ($params as $name => $value) {
            $result[] = $name . ': \'' . $value . '\'';
        }

        return '{' . implode(', ', $result) . '}';
    }

    /**
     * Get URL common parameters
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCommonParams()
    {
        if (!isset($this->commonParams)) {
            $this->commonParams = array('action' => '') + $this->getRequestParams();
        }

        return $this->commonParams; 
    }

    /**
     * Get AJAX-specific URL parameters
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getAJAXSpecificParams()
    {
        return array(
            self::PARAM_AJAX_TARGET => XLite_Core_Request::getInstance()->target,
            self::PARAM_AJAX_ACTION => '',
            self::PARAM_AJAX_CLASS  => get_class($this),
        );
    }

    /**
     * getURLParams 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getURLParams()
    {
        return array('target' => XLite_Core_Request::getInstance()->target) + $this->getCommonParams();
    }

    /**
     * getURLAJAXParams 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getURLAJAXParams()
    {
        return array('target' => 'get_widget') + $this->getCommonParams() + $this->getAJAXSpecificParams();
    }

    /**
     * getURLParams 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getURLParamsJS()
    {
        return $this->getJSArray($this->getURLParams());
    }

    /**
     * getURLAJAXParams 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getURLAJAXParamsJS()
    {
        return $this->getJSArray($this->getURLAJAXParams());
    }

    /**
     * getActionURL 
     * 
     * @param array $params params to modify
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getActionURL(array $params = array())
    {
        return $this->getUrl($params + $this->getURLParams());
    }

    /**
     * isDisplayModeSelected 
     * 
     * @param string $displayMode value to check
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isDisplayModeSelected($displayMode)
    {
        return $this->getParam(self::PARAM_DISPLAY_MODE) == $displayMode;
    }

    /**
     * isSortByModeSelected 
     * 
     * @param string $sortByMode value to check
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isSortByModeSelected($sortByMode)
    {
        return $this->getParam(self::PARAM_SORT_BY) == $sortByMode;
    }

    /**
     * isSortOrderAsc 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isSortOrderAsc()
    {
        return self::SORT_ORDER_ASC == $this->getParam(self::PARAM_SORT_ORDER);
    }

    /**
     * isShowThumbnail 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isShowThumbnails()
    {
        return $this->config->General->show_thumbnails
            && $this->getParam(self::PARAM_SHOW_THUMBNAIL);
    }

    /**
     * isDisplayModeAdjustable 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isDisplayModeAdjustable()
    {
        return $this->getParam(self::PARAM_SHOW_DISPLAY_MODE_SELECTOR) && !$this->isSideBarBox();
    }

    /**
     * isSortBySelectorVisible 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isSortBySelectorVisible()
    {
        return $this->getParam(self::PARAM_SHOW_SORT_BY_SELECTOR) && !$this->isSideBarBox();
    }

    /**
     * Check - show product price or not
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function isShowPrice()
    {
        return $this->getParam(self::PARAM_SHOW_PRICE);
    }

    /**
     * Check - show Add to cart button or not
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function isShowAdd2Cart()
    {
        return $this->getParam(self::PARAM_SHOW_ADD2CART);
    }

    /**
     * Check - show product description or not
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function isShowDescription()
    {
        return $this->getParam(self::PARAM_SHOW_DESCR);
    }

    /**
     * getSortOrderToChange
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getSortOrderToChange()
    {
        return $this->isSortOrderAsc() ? self::SORT_ORDER_DESC : self::SORT_ORDER_ASC;
    }

    /**
     * Get display mode link class name
     * TODO - simplify
     *
     * @param string $displayMode Display mode
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDisplayModeLinkClassName($displayMode)
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
     * checkSideBarParams 
     * 
     * @param array $params params to check
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkSideBarParams(array $params)
    {
        return isset($params[self::PARAM_WIDGET_TYPE]) && self::WIDGET_TYPE_SIDEBAR == $params[self::PARAM_WIDGET_TYPE];
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
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        return array_merge(
            parent::getCSSFiles(), 
            array(self::TEMPLATES_DIR . '/products_list.css'),
            $this->getPager()->getCSSFiles()
        );
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        return array_merge(
            parent::getJSFiles(),
            array(self::TEMPLATES_DIR . '/products_list.js', 'popup/jquery.blockUI.js'),
            $this->getPager()->getJSFiles()
        );
    }

    /**
     * Initialize widget (set attributes)
     *
     * @param array $params widget params
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        // FIXME - not a good idea, but I don't see a better way
        if ($this->isWrapper() && $this->checkSideBarParams($params)) {
            $this->defaultTemplate = self::TEMPLATE_SIDEBAR;
            $this->widgetParams[self::PARAM_TEMPLATE]->setValue($this->getDefaultTemplate());
        }

        // Do not change call order
        $this->widgetParams += $this->getPager()->getWidgetParams();
        $this->requestParams = array_merge($this->requestParams, array_keys($this->getPager()->getRequestParams()));
    }

    /**
     * Get table columns count 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTableColumnsCount()
    {
        return 2 + ($this->isShowPrice() ? 1 : 0) + ($this->isShowAdd2Cart() ? 1 : 0);
    }

    /** 
     * Get grid item width (percent) 
     * 
     * @return integer 
     * @access protected 
     * @since  3.0.0 
     */ 
    protected function getGridItemWidth() 
    { 
        return floor(100 / $this->getParam(self::PARAM_GRID_COLUMNS)) - 6; 
    } 
}
