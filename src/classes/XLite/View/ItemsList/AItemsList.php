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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\ItemsList;

/**
 * Base class for all lists
 *
 */
abstract class AItemsList extends \XLite\View\Container
{
    /**
     * Widget param names
     */
    const PARAM_SORT_BY      = 'sortBy';
    const PARAM_SORT_ORDER   = 'sortOrder';

    /**
     * SQL orderby directions
     */
    const SORT_ORDER_ASC  = 'asc';
    const SORT_ORDER_DESC = 'desc';

    /**
     * Default layout template
     *
     * @var string
     */
    protected $defaultTemplate = 'common/dialog.tpl';

    /**
     * commonParams
     *
     * @var array
     */
    protected $commonParams;

    /**
     * pager
     *
     * @var \XLite\View\Pager\APager
     */
    protected $pager;

    /**
     * itemsCount
     *
     * @var integer
     */
    protected $itemsCount;

    /**
     * sortByModes
     *
     * @var array
     */
    protected $sortByModes = array();

    /**
     * sortOrderModes
     *
     * @var array
     */
    protected $sortOrderModes = array(
        self::SORT_ORDER_ASC  => 'Ascending',
        self::SORT_ORDER_DESC => 'Descending',
    );

    /**
     * Sorting widget IDs list
     *
     * @var array
     */
    protected static $sortWidgetIds = array();

    /**
     * Return dir which contains the page body template
     *
     * @return string
     */
    abstract protected function getPageBodyDir();

    /**
     * Return class name for the list pager
     *
     * @return string
     */
    abstract protected function getPagerClass();

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     */
    abstract protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false);

    /**
     * Get session cell name for the certain list items widget
     *
     * @return string
     */
    static public function getSessionCellName()
    {
        return str_replace('\\', '', get_called_class());
    }

    /**
     * Initialize widget (set attributes)
     *
     * @param array $params Widget params
     *
     * @return void
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        // Do not change call order
        $this->widgetParams += $this->getPager()->getWidgetParams();
        $this->requestParams = array_merge($this->requestParams, $this->getPager()->getRequestParams());
    }

    /**
     * getActionURL
     *
     * @param array $params Params to modify OPTIONAL
     *
     * @return string
     */
    public function getActionURL(array $params = array())
    {
        return $this->getURL($params + $this->getURLParams());
    }

    /**
     * Get a list of JavaScript files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        // Static call of the non-static function
        $list[] = self::getDir() . '/items_list.js';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/jquery.blockUI.js';

        return $list;
    }

    /**
     * Get a list of CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        // Static call of the non-static function
        $list[] = self::getDir() . '/items_list.css';
        $list = self::preparePagerCSSFiles($list);

        return $list;
    }

    /**
     * Returns a list of CSS classes (separated with a space character) to be attached to the items list
     *
     * @return string
     */
    public function getListCSSClasses()
    {
        return 'items-list';
    }

    /**
     * Return inner head for list widgets
     *
     * @return string
     */
    protected function getListHead()
    {
        return parent::getHead();
    }

    /**
     * Return number of items in products list
     *
     * @return array
     */
    protected function getItemsCount()
    {
        if (!isset($this->itemsCount)) {
            $this->itemsCount = $this->getData($this->getSearchCondition(), true);
        }

        return $this->itemsCount;
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     */
    protected function getListName()
    {
        return 'itemsList';
    }

    /**
     * Get widget templates directory
     * NOTE: do not use "$this" pointer here (see "getBody()" and "get[CSS/JS]Files()")
     *
     * @return string
     */
    protected function getDir()
    {
        return 'items_list';
    }

    /**
     * prepare CSS file list for use with pager
     *
     * @param array $list CSS file list
     *
     * @return array
     */
    protected function preparePagerCSSFiles($list)
    {
        return array_merge($list, self::getPager()->getCSSFiles());
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getBody()
    {
        // Static call of the non-static function
        return self::getDir() . LC_DS . $this->getBodyTemplate();
    }

    /**
     * Return default template
     * See setWidgetParams()
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return $this->defaultTemplate;
    }

    /**
     * getPageBodyTemplate
     *
     * @return string
     */
    protected function getPageBodyTemplate()
    {
        return $this->getDir() . LC_DS . $this->getPageBodyDir() . LC_DS . $this->getPageBodyFile();
    }

    /**
     * getPageBodyFile
     *
     * @return string
     */
    protected function getPageBodyFile()
    {
        return 'body.tpl';
    }

    /**
     * getEmptyListTemplate
     *
     * @return string
     */
    protected function getEmptyListTemplate()
    {
        return $this->getEmptyListDir() . LC_DS . $this->getEmptyListFile();
    }

    /**
     * Return "empty list" catalog
     *
     * @return string
     */
    protected function getEmptyListDir()
    {
        return self::getDir();
    }

    /**
     * getEmptyListFile
     *
     * @return string
     */
    protected function getEmptyListFile()
    {
        return 'empty.tpl';
    }

    /**
     * isEmptyListTemplateVisible
     *
     * @return string
     */
    protected function isEmptyListTemplateVisible()
    {
        return false === $this->hasResults();
    }

    /**
     * Get pager parameters list
     *
     * @return array
     */
    protected function getPagerParams()
    {
        return array(
            \XLite\View\Pager\APager::PARAM_ITEMS_COUNT => $this->getItemsCount(),
            \XLite\View\Pager\APager::PARAM_LIST        => $this,
        );
    }

    /**
     * Get pager
     *
     * @return \XLite\View\Pager\APager
     */
    protected function getPager()
    {
        if (!isset($this->pager)) {
            $this->pager = $this->getWidget($this->getPagerParams(), $this->getPagerClass());
        }

        return $this->pager;
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     */
    protected function getSearchCondition()
    {
        return new \XLite\Core\CommonCell();
    }

    /**
     * getPageData
     *
     * @return array
     */
    protected function getPageData()
    {
        return $this->getData($this->getPager()->getLimitCondition(null, null, $this->getSearchCondition()));
    }

    /**
     * getSortOrderDefault
     *
     * @return string
     */
    protected function getSortOrderModeDefault()
    {
        return self::SORT_ORDER_ASC;
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     */
    protected function getSortByModeDefault()
    {
        return null;
    }

    /**
     * getSortBy
     *
     * @return string
     */
    protected function getSortBy()
    {
        return $this->getParam(self::PARAM_SORT_BY);
    }

    /**
     * getSortOrder
     *
     * @return string
     */
    protected function getSortOrder()
    {
        return $this->getParam(self::PARAM_SORT_ORDER);
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        if (!empty($this->sortByModes)) {

            $this->widgetParams += array(
                self::PARAM_SORT_BY => new \XLite\Model\WidgetParam\Set(
                    'Sort by', $this->getSortByModeDefault(), false, $this->sortByModes
                ),
                self::PARAM_SORT_ORDER => new \XLite\Model\WidgetParam\Set(
                    'Sort order', $this->getSortOrderModeDefault(), false, $this->sortOrderModes
                ),
            );
        }
    }

    /**
     * getJSHandlerClassName
     *
     * @return string
     */
    protected function getJSHandlerClassName()
    {
        return 'ItemsList';
    }

    /**
     * Get URL common parameters
     *
     * @return array
     */
    protected function getCommonParams()
    {
        if (!isset($this->commonParams)) {
            $this->commonParams = array(
                self::PARAM_SESSION_CELL => $this->getSessionCell()
            );
        }

        return $this->commonParams;
    }

    /**
     * Get AJAX-specific URL parameters
     *
     * @return array
     */
    protected function getAJAXSpecificParams()
    {
        return array(
            self::PARAM_AJAX_WIDGET => get_class($this),
            self::PARAM_AJAX_TARGET => \XLite\Core\Request::getInstance()->target,
        );
    }

    /**
     * getURLParams
     *
     * @return array
     */
    protected function getURLParams()
    {
        return array('target' => \XLite\Core\Request::getInstance()->target) + $this->getCommonParams();
    }

    /**
     * getURLAJAXParams
     *
     * @return array
     */
    protected function getURLAJAXParams()
    {
        return $this->getCommonParams() + $this->getAJAXSpecificParams();
    }

    /**
     * Return specific items list parameters that will be sent to JS code
     *
     * @return array
     */
    protected function getItemsListParams()
    {
        return array(
            'urlparams'     => $this->getURLParams(),
            'urlajaxparams' => $this->getURLAJAXParams(),
            'cell'          => $this->getSessionCell(),
        );
    }

    /**
     * Get sorting widget unique ID
     *
     * @param boolean $getLast Get last ID or next OPTIONAL
     *
     * @return string
     */
    protected function getSortWidgetId($getLast = false)
    {
        $class = get_called_class();

        if (!isset(static::$sortWidgetIds[$class])) {
            static::$sortWidgetIds[$class] = 0;
        }

        if (!$getLast) {
            static::$sortWidgetIds[$class]++;
        }

        return str_replace('\\', '-', $class) . '-sortby-' . static::$sortWidgetIds[$class];
    }

    /**
     * isSortByModeSelected
     *
     * @param string $sortByMode Value to check
     *
     * @return boolean
     */
    protected function isSortByModeSelected($sortByMode)
    {
        return $this->getParam(self::PARAM_SORT_BY) == $sortByMode;
    }

    /**
     * isSortOrderAsc
     *
     * @return boolean
     */
    protected function isSortOrderAsc()
    {
        return self::SORT_ORDER_ASC == $this->getParam(self::PARAM_SORT_ORDER);
    }

    /**
     * getSortOrderToChange
     *
     * @return string
     */
    protected function getSortOrderToChange()
    {
        return $this->isSortOrderAsc() ? self::SORT_ORDER_DESC : self::SORT_ORDER_ASC;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && ($this->isDisplayWithEmptyList() || $this->hasResults());
    }

    /**
     * Auxiliary method to check visibility
     *
     * @return boolean
     */
    protected function isDisplayWithEmptyList()
    {
        return false;
    }

    /**
     * Check if there are any results to display in list
     *
     * @return void
     */
    protected function hasResults()
    {
        return 0 < $this->getItemsCount();
    }

    /**
     * isHeaderVisible
     *
     * @return boolean
     */
    protected function isHeaderVisible()
    {
        return false;
    }

    /**
     * Check if head title is visible
     *
     * @return boolean
     */
    protected function isHeadVisible()
    {
        return false;
    }

    /**
     * Check if pager is visible
     *
     * @return boolean
     */
    protected function isPagerVisible()
    {
        return $this->getPager()->isVisible();
    }

    /**
     * isFooterVisible
     *
     * @return boolean
     */
    protected function isFooterVisible()
    {
        return false;
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_SORT_BY;
        $this->requestParams[] = self::PARAM_SORT_ORDER;
    }

    /**
     * Get 'More' link URL
     *
     * @return string
     */
    public function getMoreLink()
    {
        return null;
    }

    /**
     * Get 'More' link title
     *
     * @return string
     */
    public function getMoreLinkTitle()
    {
        return null;
    }

}
