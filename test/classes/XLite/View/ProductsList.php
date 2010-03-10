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
abstract class XLite_View_ProductsList extends XLite_View_Dialog
{
    /**
     * Widget param names
     */

    const PARAM_DISPLAY_MODE   = 'displayMode';
    const PARAM_GRID_COLUMNS   = 'gridColumns';
    const PARAM_SORT_BY        = 'sortBy';
    const PARAM_SORT_ORDER     = 'sortOrder';

    const PARAM_SHOW_DESCR     = 'showDescription';
    const PARAM_SHOW_PRICE     = 'showPrice';
    const PARAM_SHOW_THUMBNAIL = 'showThumbnail';
    const PARAM_SHOW_ADD2CART  = 'showAdd2Cart';

    const PARAM_SHOW_ALL_ITEMS_PER_PAGE    = 'showAllItemsPerPage';
    const PARAM_SHOW_DISPLAY_MODE_SELECTOR = 'showDisplayModeSelector';
    const PARAM_SHOW_SORT_BY_SELECTOR      = 'showSortBySelector';

    /**
     * Allowed display modes
     */

    const DISPLAY_MODE_LIST  = 'list';
    const DISPLAY_MODE_GRID  = 'grid';
    const DISPLAY_MODE_TABLE = 'table';

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
     * Display modes
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_GRID  => 'Grid',
        self::DISPLAY_MODE_LIST  => 'List',
        self::DISPLAY_MODE_TABLE => 'Table',
    );


    /**
     * Return products list 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getData();


    /**
     * sortByModes 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sortByModes = array(
        'price' => 'Price',
        'name'  => 'Name',
    );

    /**
     * sortOrderModes 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sortOrderModes = array(
        'asc'  => 'Ascending',
        'desc' => 'Descending',
    );

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
     * getPageBodyTemplate 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPageBodyTemplate()
    {
        return $this->getDir() . '/' . $this->getParam(self::PARAM_DISPLAY_MODE) . '/body.tpl';
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
     * getPager 
     * 
     * @return XLite_View_Pager
     * @access protected
     * @since  3.0.0
     */
    protected function getPager()
    {
        return $this->isPagerVisible() ? $this->getWidget(array(), null, $this->getPagerName()) : null;
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
     * getGridColumnsRange 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getGridColumnsRange()
    {
        $range = range(self::GRID_COLUMNS_MIN, self::GRID_COLUMNS_MAX);

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

        $this->requestParams += array(
            self::PARAM_DISPLAY_MODE => self::DISPLAY_MODE_GRID,
            self::PARAM_SORT_BY      => 'price',
            self::PARAM_SORT_ORDER   => 'asc',
        );

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new XLite_Model_WidgetParam_List(
                'Display mode', $this->getRequestParamValue(self::PARAM_DISPLAY_MODE), true, $this->displayModes
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
            self::PARAM_SORT_BY => new XLite_Model_WidgetParam_List(
                'Sort by', $this->getRequestParamValue(self::PARAM_SORT_BY), false, $this->sortByModes
            ),
            self::PARAM_SORT_ORDER => new XLite_Model_WidgetParam_List(
                'Sort order', $this->getRequestParamValue(self::PARAM_SORT_ORDER), false, $this->sortOrderModes
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
     * getCommonParams 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCommonParams()
    {
        $result = array('action' => '') + $this->getRequestParams();

        if ($this->isPagerVisible()) {
            $result += $this->getPager()->getRequestParams();
        }

        return $result; 
    }

    /**
     * getAJAXSpecificParams 
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
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getURLParams()
    {
        return $this->getJSArray(array('target' => XLite_Core_Request::getInstance()->target) + $this->getCommonParams());
    }

    /**
     * getURLAJAXParams 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getURLAJAXParams()
    {
        return $this->getJSArray(array('target' => 'get_widget') + $this->getCommonParams() + $this->getAJAXSpecificParams());
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
        return $this->getParam(self::PARAM_SORT_ORDER) == 'asc';
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
       return $this->config->General->show_thumbnails && $this->getParam(self::PARAM_SHOW_THUMBNAIL);
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
        return $this->getParam(self::PARAM_SHOW_DISPLAY_MODE_SELECTOR);
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
        return $this->getParam(self::PARAM_SHOW_SORT_BY_SELECTOR);
    }

    /**
     * isPagerVisible 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isPagerVisible()
    {
        return !$this->getParam(self::PARAM_SHOW_ALL_ITEMS_PER_PAGE);
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
        return parent::isVisible() && $this->getData();
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
    public function getCSSFiles()
    {
        return array_merge(parent::getCSSFiles(), array(self::TEMPLATES_DIR . '/products_list.css'));
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
    public function getJSFiles()
    {
        return array_merge(parent::getJSFiles(), array(self::TEMPLATES_DIR . '/products_list.js', 'popup/jquery.blockUI.js'));
    }

    // ----------------------------

    /**
     * Get display mode link class name
     *
     * @param string $displayMode Display mode
     *
     * @return string
     * @access protected
     * @see    ____func_see____
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
     * Get grid item width (percent)
     *
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGridItemWidth()
    {
        return floor(100 / $this->getParam(self::PARAM_GRID_COLUMNS)) - 6;
    }

    /**
     * Check - show product price or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShowPrice()
    {
        return $this->getParam(self::PARAM_SHOW_PRICE);
    }

    /**
     * Check - show Add to cart button or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShowAdd2Cart()
    {
        return $this->getParam(self::PARAM_SHOW_ADD2CART);
    }

    /**
     * Check - show product description or not
     *
     * @return boolean
     * @access public
     * @since  3.0.0
     */
    public function isShowDescription()
    {
        return $this->getParam(self::PARAM_SHOW_DESCR);
    }
}
