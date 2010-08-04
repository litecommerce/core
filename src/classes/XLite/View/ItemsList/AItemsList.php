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

namespace XLite\View\ItemsList;

/**
 * Base class for all lists 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class AItemsList extends \XLite\View\Container
{
    /**
     * Widget param names
     */

    const PARAM_SORT_BY      = 'sortBy';
    const PARAM_SORT_ORDER   = 'sortOrder';

    /**
     * SQL orederby directions
     */

    const SORT_ORDER_ASC  = 'asc';
    const SORT_ORDER_DESC = 'desc';


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
     * @var    \XLite\View\PagerOrig
     * @access protected
     * @since  3.0.0
     */
    protected $pager = null;


    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getPageBodyDir();

    /**
     * Return class name for the list pager
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getPagerClass();

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       search condition
     * @param bool                   $countOnly return items list or only its size
     *
     * @return array|int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false);


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
        return 'itemsList';
    }

    /**
     * Get widget templates directory
     * NOTE: do not use "$this" pointer here (see "getBody()" and "get[CSS/JS]Files()")
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'items_list';
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     * @access protected
     * @since  3.0.0
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
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->defaultTemplate;
    }

    /**
     * Return number of items in products list
     *
     * @param \XLite\Core\CommonCell $cnd search condition
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDataCount(\XLite\Core\CommonCell $cnd)
    {
        return $this->getData($cnd, true);
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
        return $this->getDir() . '/' . $this->getPageBodyDir() . '/body.tpl';
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
            \XLite\View\Pager\APager::PARAM_ITEMS_COUNT => $this->getDataCount($this->getSearchCondition()),
            \XLite\View\Pager\APager::PARAM_LIST_REQUEST_PARAMS => $this->getCommonParams(),
        );
    }

    /**
     * Get pager
     * 
     * @return \XLite\View\PagerOrig
     * @access protected
     * @see    ____func_see____
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
     * getSearchCondition
     *
     * @return \XLite\Core\CommonCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSearchCondition()
    {
        return new \XLite\Core\CommonCell();
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
        return $this->getData($this->getPager()->getLimitCondition(null, null, $this->getSearchCondition()));
    }

    /**
     * getSortOrderModes 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSortOrderModes()
    {
        return array(
            self::SORT_ORDER_ASC  => 'Ascending',
            self::SORT_ORDER_DESC => 'Descending',
        );
    }

    /**
     * getSortByModes 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSortByModes()
    {
        return array();
    }

    /**
     * getSortOrderDefault 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSortOrderModeDefault()
    {
        return self::SORT_ORDER_ASC;
    }

    /**
     * getSortByModeDefault 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSortByModeDefault()
    {
        return null;
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

        if ($this->getSortByModes()) {
            $this->widgetParams += array(
                self::PARAM_SORT_BY => new \XLite\Model\WidgetParam\Set(
                    'Sort by', $this->getSortByModeDefault(), false, $this->getSortByModes()
                ),
                self::PARAM_SORT_ORDER => new \XLite\Model\WidgetParam\Set(
                    'Sort order', $this->getSortOrderModeDefault(), false, $this->getSortOrderModes()
                ),
            );
        }
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
        return 'ItemsList';
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
            $result[] = $name . ': \'' . addslashes($value) . '\'';
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
            $this->commonParams = array(
                'action' => '',
                self::PARAM_SESSION_CELL => $this->getSessionCell()
            ) + $this->getRequestParamsHash();
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
            self::PARAM_AJAX_TARGET => \XLite\Core\Request::getInstance()->target,
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
        return array('target' => \XLite\Core\Request::getInstance()->target) + $this->getCommonParams();
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
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getPageData();
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
        return false;
    }

    /**
     * isFooterVisisble 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isFooterVisisble()
    {
        return false;
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

        $this->requestParams[] = self::PARAM_SORT_BY;
        $this->requestParams[] = self::PARAM_SORT_ORDER;
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

        // Do not change call order
        $this->widgetParams += $this->getPager()->getWidgetParams();
        $this->requestParams = array_merge($this->requestParams, $this->getPager()->getRequestParams());
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
        $list = parent::getJSFiles();
        $list[] = 'popup/jquery.blockUI.js';
        // Static call of the non-static function
        $list[] = self::getDir() . '/items_list.js';

        return $list;
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
        $list = parent::getCSSFiles();
        // Static call of the non-static function
        $list[] = self::getDir() . '/items_list.css';

        return $list;
    }
}
