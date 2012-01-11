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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\ItemsList\Product\Customer;

/**
 * ACustomer
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ACustomer extends \XLite\View\ItemsList\Product\AProduct
{
    /**
     * Widget param names
     */
    const PARAM_WIDGET_TYPE  = 'widgetType';
    const PARAM_DISPLAY_MODE = 'displayMode';
    const PARAM_GRID_COLUMNS = 'gridColumns';

    const PARAM_SHOW_DISPLAY_MODE_SELECTOR = 'showDisplayModeSelector';
    const PARAM_SHOW_SORT_BY_SELECTOR      = 'showSortBySelector';

    const PARAM_ICON_MAX_WIDTH = 'iconWidth';
    const PARAM_ICON_MAX_HEIGHT = 'iconHeight';

    /**
     * Allowed widget types
     */
    const WIDGET_TYPE_SIDEBAR = 'sidebar';
    const WIDGET_TYPE_CENTER  = 'center';

    /**
     * Allowed display modes
     */
    const DISPLAY_MODE_LIST    = 'list';
    const DISPLAY_MODE_GRID    = 'grid';
    const DISPLAY_MODE_TABLE   = 'table';
    const DISPLAY_MODE_ROTATOR = 'rotator';

    const DISPLAY_MODE_STHUMB = 'small_thumbnails';
    const DISPLAY_MODE_BTHUMB = 'big_thumbnails';
    const DISPLAY_MODE_TEXTS  = 'text_links';


    /**
     * Allowed sort criterions
     */
    const SORT_BY_MODE_PRICE_ASC  = 'p.price asc';
    const SORT_BY_MODE_NAME_ASC   = 'translations.name asc';
    const SORT_BY_MODE_SKU_ASC    = 'p.sku asc';
    const SORT_BY_MODE_AMOUNT_ASC = 'i.amount asc';

    const SORT_BY_MODE_PRICE_DESC  = 'p.price desc';
    const SORT_BY_MODE_NAME_DESC   = 'translations.name desc';
    const SORT_BY_MODE_SKU_DESC    = 'p.sku desc';
    const SORT_BY_MODE_AMOUNT_DESC = 'i.amount desc';


    /**
     * A special option meaning that a CSS layout is to be used
     */
    const DISPLAY_GRID_CSS_LAYOUT = 'css-defined';

    /**
     * Columns number range
     */
    const GRID_COLUMNS_MIN = 2;
    const GRID_COLUMNS_MAX = 5;

    /**
     * Template to use for sidebars
     */
    const TEMPLATE_SIDEBAR = 'common/sidebar_box.tpl';


    /**
     * Widget types
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $widgetTypes = array(
        self::WIDGET_TYPE_SIDEBAR  => 'Sidebar',
        self::WIDGET_TYPE_CENTER   => 'Center',
    );


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = static::getWidgetTarget();

        return $result;
    }

    /**
     * Get display modes for sidebar widget type
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getSidebarDisplayModes()
    {
        return array(
            self::DISPLAY_MODE_STHUMB  => 'Cells',
            self::DISPLAY_MODE_BTHUMB  => 'List',
            self::DISPLAY_MODE_TEXTS   => 'Text links',
        );
    }

    /**
     * Get display modes for center widget type
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getCenterDisplayModes()
    {
        return array(
                self::DISPLAY_MODE_GRID  => static::t('Grid'),
                self::DISPLAY_MODE_LIST  => static::t('List'),
                self::DISPLAY_MODE_TABLE => static::t('Table'),
            );
    }

    /**
     * Get icon sizes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getIconSizes()
    {
        return array(
            self::WIDGET_TYPE_SIDEBAR . '.' . self::DISPLAY_MODE_STHUMB => array(80, 80),
            self::WIDGET_TYPE_SIDEBAR . '.' . self::DISPLAY_MODE_BTHUMB => array(160, 160),
            self::WIDGET_TYPE_CENTER . '.' . self::DISPLAY_MODE_GRID => array(160, 160),
            self::WIDGET_TYPE_CENTER . '.' . self::DISPLAY_MODE_LIST => array(160, 160),
            'other' => array(110, 110),
        );
    }

    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->sortByModes = array(
            self::SORT_BY_MODE_PRICE_ASC    => static::t('Price: low to high'),
            self::SORT_BY_MODE_PRICE_DESC   => static::t('Price: high to low'),
            self::SORT_BY_MODE_NAME_ASC     => static::t('Name: A-Z'),
            self::SORT_BY_MODE_NAME_DESC    => static::t('Name: Z-A'),
            self::SORT_BY_MODE_SKU_ASC      => static::t('SKU: a-z'),
            self::SORT_BY_MODE_SKU_DESC     => static::t('SKU: z-a'),
            self::SORT_BY_MODE_AMOUNT_ASC   => static::t('Amount: low to high'),
            self::SORT_BY_MODE_AMOUNT_DESC  => static::t('Amount: high to low'),
        );
    }

    /**
     * Initialize widget (set attributes)
     *
     * @param array $params Widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        // Modify display modes and default display mode
        $allOptions = array_merge(static::getSidebarDisplayModes(), static::getCenterDisplayModes());

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setOptions($allOptions);

        $options = $this->getDisplayModes();

        if (!isset($options[$this->getParam(self::PARAM_DISPLAY_MODE)])) {
            $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue(
                $this->isSidebar()
                    ? self::DISPLAY_MODE_STHUMB
                    : self::DISPLAY_MODE_GRID
            );
        }

        if (
            !isset($params[self::PARAM_ICON_MAX_WIDTH])
            && !isset($params[self::PARAM_ICON_MAX_HEIGHT])
            && 0 == $this->getParam(self::PARAM_ICON_MAX_WIDTH)
            && 0 == $this->getParam(self::PARAM_ICON_MAX_HEIGHT)
        ) {
            $sizes = static::getIconSizes();
            $key = $this->getParam(self::PARAM_WIDGET_TYPE) . '.' . $this->getParam(self::PARAM_DISPLAY_MODE);
            $size = isset($sizes[$key]) ? $sizes[$key] : $sizes['other'];

            $this->widgetParams[self::PARAM_ICON_MAX_WIDTH]->setValue($size[0]);
            $this->widgetParams[self::PARAM_ICON_MAX_HEIGHT]->setValue($size[1]);
        }

        // FIXME - not a good idea, but I don't see a better way
        if ($this->isWrapper() && $this->checkSideBarParams($params)) {
            $this->defaultTemplate = self::TEMPLATE_SIDEBAR;
            $this->widgetParams[self::PARAM_TEMPLATE]->setValue($this->getDefaultTemplate());
        }
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/quick_look.css';
        $list[] = 'css/cloud-zoom.css';

        return array_merge($list, $this->getPopupCSS());
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        return array_merge(parent::getJSFiles(), $this->getPopupJS());
    }

    /**
     * Return class attribute for the product cell
     *
     * @param \XLite\Model\Product $product The product to look for
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProductCellClass($product)
    {
        return 'product productid-'
            . $product->getProductId()
            . ($this->isProductAdded($product) ? ' product-added' : '')
            . ($product->getInventory()->isOutOfStock() ? ' out-of-stock' : '')
            . (!$product->isAvailable() ? ' not-available' : '');
    }

    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/cloud-zoom.min.js';

        return $list;
    }


    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return null;
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.customer';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
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
                'Display mode', self::DISPLAY_MODE_GRID, true, array()
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
            self::PARAM_ICON_MAX_WIDTH => new \XLite\Model\WidgetParam\Int(
                'Maximal icon width', 0, true
            ),
            self::PARAM_ICON_MAX_HEIGHT => new \XLite\Model\WidgetParam\Int(
                'Maximal icon height', 0, true
            ),
        );
    }

    /**
     * Get display modes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDisplayModes()
    {
        return $this->isSidebar()
            ? static::getSidebarDisplayModes()
            : static::getCenterDisplayModes();
    }

    /**
     * Return 'OrderBy' parameter
     *
     * @return \XLite\Core\CommonCell
     * @see    ____func_see____
     * @since  1.0.6
     */
    protected function getOrderBy()
    {
        $orderBy = parent::getOrderBy();

        $sortOrder = explode(' ', $orderBy[0]);

        if (count($sortOrder) > 1) {
            $orderBy = $sortOrder;
        }

        return $orderBy;
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSortByModeDefault()
    {
        return self::SORT_BY_MODE_NAME_ASC;
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_DISPLAY_MODE;
    }

    /**
     * checkSideBarParams
     *
     * @param array $params Params to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkSideBarParams(array $params)
    {
        return isset($params[self::PARAM_WIDGET_TYPE]) && $this->isSidebar();
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageBodyDir()
    {
        return $this->getParam(self::PARAM_WIDGET_TYPE) . '/' . parent::getPageBodyDir();
    }

    /**
     * Check - current widget type is sidebar
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSidebar()
    {
        return self::WIDGET_TYPE_SIDEBAR == $this->getParam(self::PARAM_WIDGET_TYPE);
    }

    /**
     * Check if pager control row is visible or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isPagerVisible()
    {
        return parent::isPagerVisible()
            && !$this->isSidebar()
            && $this->getParam(\XLite\View\Pager\APager::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR);
    }

    /**
     * isDisplayModeSelectorVisible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDisplayModeSelectorVisible()
    {
        return $this->getParam(self::PARAM_SHOW_DISPLAY_MODE_SELECTOR) && !$this->isSidebar();
    }

    /**
     * isSortBySelectorVisible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSortBySelectorVisible()
    {
        return $this->getParam(self::PARAM_SHOW_SORT_BY_SELECTOR) && !$this->isSidebar();
    }

    /**
     * isHeaderVisible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isHeaderVisible()
    {
        return $this->isDisplayModeSelectorVisible() || $this->isSortBySelectorVisible();
    }

    /**
     * getDisplayMode
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDisplayMode()
    {
        return $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * isDisplayModeSelected
     *
     * @param string $displayMode Value to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProductRows()
    {
        $data = $this->getPageData();
        $rows = array();

        if (!empty($data)) {
            $rows = array_chunk($data, $this->getParam(self::PARAM_GRID_COLUMNS));
            $last = count($rows) - 1;
            $rows[$last] = array_pad($rows[$last], $this->getParam(self::PARAM_GRID_COLUMNS), false);
        }

        return $rows;
    }

    /**
     * Get grid columns range
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCSSLayout()
    {
        return ($this->getParam(self::PARAM_GRID_COLUMNS) == self::DISPLAY_GRID_CSS_LAYOUT);
    }

    /**
     * getPageBodyFile
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageBodyFile()
    {
        if (
            $this->getParam(self::PARAM_WIDGET_TYPE) == self::WIDGET_TYPE_CENTER
            && $this->getParam(self::PARAM_DISPLAY_MODE) == self::DISPLAY_MODE_GRID
        ) {
            return $this->isCSSLayout() ? 'body-css-layout.tpl' : 'body-table-layout.tpl';
        } else {
            return parent::getPageBodyFile();
        }
    }

    /**
     * getSidebarMaxItems
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSidebarMaxItems()
    {
        return $this->getParam(\XLite\View\Pager\APager::PARAM_ITEMS_PER_PAGE);
    }

    /**
     * Get products list for sidebar widget
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSideBarData()
    {
        return $this->getData($this->getPager()->getLimitCondition(0, $this->getSidebarMaxItems()));
    }

    /**
     * Get additional list item class
     *
     * @param integer $i     Item index
     * @param integer $count List length
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAdditionalItemClass($i, $count)
    {
        $classes = array();

        if (1 == $i) {
            $classes[] = 'first';
        }

        if ($count == $i) {
            $classes[] = 'last';
        }

        if (0 == $i % 2) {
            $classes[] = 'odd';
        }

        return implode(' ', $classes);
    }

    /**
     * Get grid item width (percent)
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getGridItemWidth()
    {
        return floor(100 / $this->getParam(self::PARAM_GRID_COLUMNS)) - 6;
    }

    /**
     * Return the maximal icon width
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIconWidth()
    {
        return $this->getParam(self::PARAM_ICON_MAX_WIDTH);
    }

    /**
     * Return the maximal icon height
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIconHeight()
    {
        return $this->getParam(self::PARAM_ICON_MAX_HEIGHT);
    }

    /**
     * Get table columns count
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTableColumnsCount()
    {
        return 3 + ($this->isShowAdd2Cart() ? 1 : 0);
    }

    /**
     * Check status of 'More...' link for sidebar list
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isShowMoreLink()
    {
        return false;
    }

    /**
     * Get 'More...' link URL for sidebar list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMoreLinkURL()
    {
        return null;
    }

    /**
     * Get 'More...' link text for sidebar list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMoreLinkText()
    {
        return 'More...';
    }

    /**
     * Prepare CSS files needed for popups
     * TODO: check if there is a more convinient way to do that
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPopupCSS()
    {
        return array_merge(
            $this->getWidget(array(), '\XLite\View\Product\Details\Customer\Page\QuickLook')->getCSSFiles(),
            $this->getWidget(array(), '\XLite\View\Product\Details\Customer\Image')->getCSSFiles(),
            $this->getWidget(array(), '\XLite\View\Product\Details\Customer\Gallery')->getCSSFiles(),
            $this->getWidget(array(), '\XLite\View\Product\QuantityBox')->getCSSFiles()
        );
    }

    /**
     * Prepare JS files needed for popups
     * TODO: check if there is a more convinient way to do that
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPopupJS()
    {
        return array_merge(
            $this->getWidget(array(), '\XLite\View\Product\Details\Customer\Page\QuickLook')->getJSFiles(),
            $this->getWidget(array(), '\XLite\View\Product\Details\Customer\Image')->getJSFiles(),
            $this->getWidget(array(), '\XLite\View\Product\Details\Customer\Gallery')->getJSFiles(),
            $this->getWidget(array(), '\XLite\View\Product\QuantityBox')->getJSFiles()
        );
    }

    /**
     * Checks whether a product was added to the cart
     *
     * @param \XLite\Model\Product $product The product to look for
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isProductAdded(\XLite\Model\Product $product)
    {
        return $this->getCart()->isProductAdded($product->getProductId());
    }

    /**
     * Return list of product labels
     *
     * @param \XLite\Model\Product $product The product to look for
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getLabels(\XLite\Model\Product $product)
    {
        return array();
    }
}
