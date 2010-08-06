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
 * @version    SVN: $Id: ACustomer.php 3650 2010-08-01 14:39:12Z vvs $
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\ItemsList\Product\Customer;

/**
 * ACustomer 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class ACustomer extends \XLite\View\ItemsList\Product\AProduct
{
    /**
     * Widget param names
     */

    const PARAM_WIDGET_TYPE  = 'widgetType';
    const PARAM_DISPLAY_MODE = 'displayMode';
    const PARAM_GRID_COLUMNS = 'gridColumns';

    const PARAM_SHOW_ALL_ITEMS_PER_PAGE    = 'showAllItemsPerPage';
    const PARAM_SHOW_DISPLAY_MODE_SELECTOR = 'showDisplayModeSelector';
    const PARAM_SHOW_SORT_BY_SELECTOR      = 'showSortBySelector';

    const PARAM_SHOW_DESCR     = 'showDescription';
    const PARAM_SHOW_PRICE     = 'showPrice';
    const PARAM_SHOW_THUMBNAIL = 'showThumbnail';
    const PARAM_SHOW_ADD2CART  = 'showAdd2Cart';

    const PARAM_ICON_MAX_WIDTH = 'iconWidth';
    const PARAM_ICON_MAX_HEIGHT = 'iconHeight';

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
     * A special option meaning that a CSS layout is to be used
     */

    const DISPLAY_GRID_CSS_LAYOUT = 'css-defined';

    /**
     * Columns number range
     */

    const GRID_COLUMNS_MIN = 1;
    const GRID_COLUMNS_MAX = 5;

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
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Catalog';
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.customer.' . $this->getDisplayMode();
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
            self::PARAM_WIDGET_TYPE => new \XLite\Model\WidgetParam\Set(
                'Widget type', self::WIDGET_TYPE_CENTER, true, $this->widgetTypes
            ),
            self::PARAM_DISPLAY_MODE => new \XLite\Model\WidgetParam\Set(
                'Display mode', self::DISPLAY_MODE_GRID, true, $this->displayModes
            ),
            self::PARAM_SHOW_DISPLAY_MODE_SELECTOR => new \XLite\Model\WidgetParam\Checkbox(
                'Show "Display mode" selector', true, true
            ),
            self::PARAM_SHOW_SORT_BY_SELECTOR => new \XLite\Model\WidgetParam\Checkbox(
                'Show "Sort by" selector', true, true
            ),
            self::PARAM_GRID_COLUMNS => new \XLite\Model\WidgetParam\Set(
                'Number of columns (for Grid mode only)', 3, true, $this->getGridColumnsRange()
            ),
            self::PARAM_SHOW_DESCR => new \XLite\Model\WidgetParam\Checkbox(
                'Show product description (for List mode only)', true, true
            ),
            self::PARAM_SHOW_PRICE => new \XLite\Model\WidgetParam\Checkbox(
                'Show product price', true, true
            ),
            self::PARAM_SHOW_THUMBNAIL => new \XLite\Model\WidgetParam\Checkbox(
                'Show product thumbnail', true, true
            ),
            self::PARAM_SHOW_ADD2CART => new \XLite\Model\WidgetParam\Checkbox(
                'Show \'Add to Cart\' button', true, true
            ),
            self::PARAM_ICON_MAX_WIDTH => new \XLite\Model\WidgetParam\Int(
                'Maximal icon width', 90, true
            ),
            self::PARAM_ICON_MAX_HEIGHT => new \XLite\Model\WidgetParam\Int(
                'Maximal icon height', 90, true
            ),
            self::PARAM_SHOW_ALL_ITEMS_PER_PAGE => new \XLite\Model\WidgetParam\Checkbox(
                'Display all items on one page', false, true
            ),
            self::PARAM_SIDEBAR_MAX_ITEMS => new \XLite\Model\WidgetParam\Int(
                'The maximum number of products displayed in sidebar', 5, true
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

        $this->requestParams[] = self::PARAM_DISPLAY_MODE;
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
     * Return dir which contains the page body template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPageBodyDir()
    {
        return $this->isSideBarBox() ? 'sidebar' : $this->getDisplayMode();
    }

    /**
     * Check if pager control row is visible or not
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isPagerVisible()
    {
        return parent::isPagerVisible() 
            && !$this->getParam(self::PARAM_SHOW_ALL_ITEMS_PER_PAGE) 
            && !$this->isSideBarBox();
    }

    /**
     * isDisplayModeSelectorVisible
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isDisplayModeSelectorVisible()
    {
        return $this->getParam(self::PARAM_SHOW_DISPLAY_MODE_SELECTOR);
    }

    /**
     * isSortBySelectorVisible
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isSortBySelectorVisible()
    {
        return $this->getParam(self::PARAM_SHOW_SORT_BY_SELECTOR);
    }

    /**
     * isHeaderVisisble
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isHeaderVisisble()
    {
        return !$this->isSideBarBox()
            && ($this->isDisplayModeSelectorVisible() || $this->isSortBySelectorVisible());
    }

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
     * Return products split into rows
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getProductRows()
    {
        $rows = array_chunk($this->getPageData(), $this->getParam(self::PARAM_GRID_COLUMNS));
        $last = count($rows)-1;
        $rows[$last] = array_pad($rows[$last], $this->getParam(self::PARAM_GRID_COLUMNS), false);

        return $rows;
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
            array(self::DISPLAY_GRID_CSS_LAYOUT => self::DISPLAY_GRID_CSS_LAYOUT),
            range(self::GRID_COLUMNS_MIN, self::GRID_COLUMNS_MAX)
        );

        return array_combine($range, $range);
    }

    /**
     * Check whether a CSS layout should be used for "Grid" mode
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function isCSSLayout()
    {
        return ($this->getParam(self::PARAM_GRID_COLUMNS) == self::DISPLAY_GRID_CSS_LAYOUT);
    }

    /**
     * getSidebarMaxItems
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSidebarMaxItems()
    {
        return $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS);
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
        return $this->getData($this->getPager()->getLimitCondition(0, $this->getSidebarMaxItems()));
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

    /**
     * Show product description or not
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isShowDescription()
    {
        return $this->getParam(self::PARAM_SHOW_DESCR);
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
        return $this->getParam(self::PARAM_SHOW_THUMBNAIL)
            && \XLite\Core\Config::getInstance()->General->show_thumbnails;
    }

    /**
     * Show product price or not
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
     * Show Add to cart button or not
     *
     * @param \XLite\Model\Product $product Product
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function isShowAdd2Cart(\XLite\Model\Product $product)
    {
        return $this->getParam(self::PARAM_SHOW_ADD2CART);
    }

    /**
     * Return the maximal icon width
     *
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIconWidth()
    {
        return $this->getParam(self::PARAM_ICON_MAX_WIDTH);
    }

    /**
     * Return the maximal icon height
     *
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIconHeight()
    {
        return $this->getParam(self::PARAM_ICON_MAX_HEIGHT);
    }

    /**
     * Get table columns count
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTableColumnsCount()
    {
        return 2 + ($this->isShowPrice() ? 1 : 0) + ($this->isShowAdd2Cart() ? 1 : 0);
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
     * getJSHandlerClassName
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getJSHandlerClassName()
    {
        return 'ProductsList';
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
    }
}
