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
 * Search
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center.bottom", zone="customer")
 */
class Search extends \XLite\View\ItemsList\Product\Customer\ACustomer
{
    /**
     * Widget param names
     */
    const PARAM_SUBSTRING         = 'substring';
    const PARAM_CATEGORY_ID       = 'categoryId';
    const PARAM_SEARCH_IN_SUBCATS = 'searchInSubcats';
    const PARAM_INCLUDING         = 'including';
    const PARAM_BY_TITLE          = 'by_title';
    const PARAM_BY_DESCR          = 'by_descr';
    const PARAM_BY_SKU            = 'by_sku';

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
        $result[] = 'search';

        return $result;
    }

    /**
     * Return target to retrive this widget from AJAX
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getWidgetTarget()
    {
        return 'search';
    }

    /**
     * Return search parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getSearchParams()
    {
        return array(
            \XLite\Model\Repo\Product::P_SUBSTRING         => static::PARAM_SUBSTRING,
            \XLite\Model\Repo\Product::P_CATEGORY_ID       => static::PARAM_CATEGORY_ID,
            \XLite\Model\Repo\Product::P_SEARCH_IN_SUBCATS => static::PARAM_SEARCH_IN_SUBCATS,
            \XLite\Model\Repo\Product::P_INCLUDING         => static::PARAM_INCLUDING,
            \XLite\Model\Repo\Product::P_BY_TITLE          => static::PARAM_BY_TITLE,
            \XLite\Model\Repo\Product::P_BY_DESCR          => static::PARAM_BY_DESCR,
            \XLite\Model\Repo\Product::P_BY_SKU            => static::PARAM_BY_SKU,
        );
    }

    /**
     * Returns CSS classes for the container element
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getListCSSClasses()
    {
        return parent::getListCSSClasses() . ' products-search-result';
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
        $list[] = $this->getLocalDir() . '/style.css';

        return $list;
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
        $list = parent::getJSFiles();
        $list[] = $this->getLocalDir() . '/controller.js';

        return $list;
    }

    /**
     * We should not redefine getDir() method, so we use this
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getLocalDir()
    {
        return $this->getDir() . '/search';
    }

    /**
     * Return name of the list containing forms (e.g. search form)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getFormsListName()
    {
        return 'search';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListHead()
    {
        return static::t('X products found', array('count' => $this->getItemsCount()));
    }

    /**
     * Check if head title is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isHeadVisible()
    {
        return true;
    }

    /**
     * Check if header is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isHeaderVisible()
    {
        return 0 < $this->getItemsCount();
    }

    /**
     * Check if pager is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isPagerVisible()
    {
        return 0 < $this->getItemsCount();
    }

    /**
     * isFooterVisible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isFooterVisible()
    {
        return true;
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagerClass()
    {
        return '\XLite\View\Pager\Customer\Product\Search';
    }

    /**
     * Get URL common parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCommonParams()
    {
        return parent::getCommonParams() + array('mode' => 'search');
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
            static::PARAM_SUBSTRING         => new \XLite\Model\WidgetParam\String('Substring', ''),
            static::PARAM_CATEGORY_ID       => new \XLite\Model\WidgetParam\Int('Category ID', 0),
            static::PARAM_SEARCH_IN_SUBCATS => new \XLite\Model\WidgetParam\Bool('Search in subcats', true),
            static::PARAM_BY_TITLE          => new \XLite\Model\WidgetParam\Checkbox('Search in title', 0),
            static::PARAM_BY_DESCR          => new \XLite\Model\WidgetParam\Checkbox('Search in description', 0),
            static::PARAM_BY_SKU            => new \XLite\Model\WidgetParam\String('Search in SKU', 0),
            static::PARAM_INCLUDING         => new \XLite\Model\WidgetParam\Set(
                'Including',
                \XLite\Model\Repo\Product::INCLUDING_ANY,
                false,
                array(
                    \XLite\Model\Repo\Product::INCLUDING_ALL,
                    \XLite\Model\Repo\Product::INCLUDING_ANY,
                    \XLite\Model\Repo\Product::INCLUDING_PHRASE,
                )
            ),
        );
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

        $this->requestParams = array_merge($this->requestParams, array_values(static::getSearchParams()));
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        foreach (static::getSearchParams() as $modelParam => $requestParam) {
            $paramValue = $this->getParam($requestParam);

            // Do not change this check to the "empty()"
            if ('' !== $paramValue && 0 !== $paramValue) {
                $result->$modelParam = $paramValue;
            }
        }

        return $result;
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd, $countOnly);
    }
}
