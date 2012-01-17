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
 * @since     1.0.15
 */

namespace XLite\View\ItemsList\Admin\Product;

/**
 * Search product
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
class Search extends \XLite\View\ItemsList\Admin\Table
{
    /**
     * Allowed sort criterions
     */

    const SORT_BY_MODE_PRICE  = 'p.price';
    const SORT_BY_MODE_NAME   = 'translations.name';
    const SORT_BY_MODE_SKU    = 'p.sku';
    const SORT_BY_MODE_AMOUNT = 'i.amount';

    /**
     * Widget param names
     */
    const PARAM_SUBSTRING         = 'substring';
    const PARAM_CATEGORY_ID       = 'categoryId';
    const PARAM_SKU               = 'sku';
    const PARAM_SEARCH_IN_SUBCATS = 'searchInSubcats';
    const PARAM_BY_TITLE          = 'by_title';
    const PARAM_BY_DESCR          = 'by_descr';
    const PARAM_INVENTORY         = 'inventory';


    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $params = array())
    {
        $this->sortByModes += array(
            self::SORT_BY_MODE_PRICE  => 'Price',
            self::SORT_BY_MODE_NAME   => 'Name',
            self::SORT_BY_MODE_SKU    => 'SKU',
            self::SORT_BY_MODE_AMOUNT => 'Amount',
        );

        parent::__construct($params);
    }

    /**
     * Get a list of CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/' . $this->getPageBodyDir() . '/product/style.css';

        return $list;
    }

    /**
     * Define columns structure
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineColumns()
    {
        return array(
            'sku' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('SKU'),
            ),
            'name' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Product Name'),
                static::COLUMN_LINK  => 'product',
            ),
            'price' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Price'),
                static::COLUMN_CLASS => 'XLite\View\FormField\Inline\Input\Text\Price\Product',
            ),
            'qty' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Qty'),
                static::COLUMN_CLASS => 'XLite\View\FormField\Inline\Input\Text\Integer\ProductQuantity',
            ),
        );
    }

    /**
     * Define repository name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineRepositoryName()
    {
        return 'XLite\Model\Repo\Product';
    }

    /**
     * Get remove message
     *
     * @param integer $count Count
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getRemoveMessage($count)
    {
        return \XLite\Core\Translation::lbl('X product(s) has been removed', array('count' => $count));
    }

    /**
     * Get create message
     *
     * @param integer $count Count
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateMessage($count)
    {
        return \XLite\Core\Translation::lbl('X product(s) has been created', array('count' => $count));
    }

    // {{{ Search

    /**
     * Return search parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    static public function getSearchParams()
    {
        return array(
            \XLite\Model\Repo\Product::P_SUBSTRING         => self::PARAM_SUBSTRING,
            \XLite\Model\Repo\Product::P_CATEGORY_ID       => self::PARAM_CATEGORY_ID,
            \XLite\Model\Repo\Product::P_SKU               => self::PARAM_SKU,
            \XLite\Model\Repo\Product::P_SEARCH_IN_SUBCATS => self::PARAM_SEARCH_IN_SUBCATS,
            \XLite\Model\Repo\Product::P_BY_TITLE          => self::PARAM_BY_TITLE,
            \XLite\Model\Repo\Product::P_BY_DESCR          => self::PARAM_BY_DESCR,
            \XLite\Model\Repo\Product::P_INVENTORY         => self::PARAM_INVENTORY,
        );
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
            self::PARAM_SUBSTRING => new \XLite\Model\WidgetParam\String(
                'Substring', ''
            ),
            self::PARAM_CATEGORY_ID => new \XLite\Model\WidgetParam\Int(
                'Category ID', 0
            ),
            self::PARAM_SKU => new \XLite\Model\WidgetParam\String(
                'SKU', ''
            ),
            self::PARAM_SEARCH_IN_SUBCATS => new \XLite\Model\WidgetParam\Checkbox(
                'Search in subcategories', 0
            ),
            self::PARAM_BY_TITLE => new \XLite\Model\WidgetParam\Checkbox(
                'Search in title', 0
            ),
            self::PARAM_BY_DESCR => new \XLite\Model\WidgetParam\Checkbox(
                'Search in description', 0
            ),
            self::PARAM_INVENTORY => new \XLite\Model\WidgetParam\String(
                'Inventory', 'all'
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

        $this->requestParams = array_merge(
            $this->requestParams,
            static::getSearchParams()
        );
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

        // We initialize structure to define order (field and sort direction) in search query.
        $result->{\XLite\Model\Repo\Product::P_ORDER_BY} = $this->getOrderBy();

        foreach (static::getSearchParams() as $modelParam => $requestParam) {
            $result->$modelParam = $this->getParam($requestParam);
        }

        if (empty($result->{self::PARAM_CATEGORY_ID})) {
            unset($result->{self::PARAM_CATEGORY_ID});
            unset($result->{self::PARAM_SEARCH_IN_SUBCATS});
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

    /**
     * Return 'Order by' array.
     * array(<Field to order>, <Sort direction>)
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.6
     */
    protected function getOrderBy()
    {
        return array($this->getSortBy(), $this->getSortOrder());
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
        return self::SORT_BY_MODE_NAME;
    }

    // }}}

    // {{{ Content helpers

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Search result';
    }

    /**
     * Get container class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getContainerClass()
    {
        return parent::getContainerClass() . ' products';
    }

    /**
     * Get column cell class
     *
     * @param array                $column Column
     * @param \XLite\Model\AEntity $entity Model OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getColumnClass(array $column, \XLite\Model\AEntity $entity = null)
    {
        $class = parent::getColumnClass($column, $entity);

        if ('qty' == $column[static::COLUMN_CODE] && !$entity->getInventory()->getEnabled()) {
            $class .= ' infinity';
        }

        return $class;
    }

    /**
     * Check - has specified column attantion or not
     *
     * @param array                $column Column
     * @param \XLite\Model\AEntity $entity Model
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function hasColumnAttantion(array $column, \XLite\Model\AEntity $entity)
    {
        return parent::hasColumnAttantion($column, $entity)
            || ('qty' == $column[static::COLUMN_CODE] && $entity->getInventory()->isLowLimitReached());
    }

    // }}}

    // {{{ Behavoirs

    /**
     * Mark list as removable
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isRemoved()
    {
        return true;
    }

    /**
     * Mark list as switchyabvle (enable / disable)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isSwitchable()
    {
        return true;
    }

    // }}}

}

