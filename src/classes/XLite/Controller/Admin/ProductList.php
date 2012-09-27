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

namespace XLite\Controller\Admin;


/**
 * Products list controller
 *
 */
class ProductList extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage catalog');
    }

    /**
     * Get search condition parameter by name
     *
     * @param string $paramName Parameter name
     *
     * @return mixed
     */
    public function getCondition($paramName)
    {
        $searchParams = $this->getConditions();

        if (isset($searchParams[$paramName])) {

            $return = $searchParams[$paramName];
        }

        return isset($searchParams[$paramName])
            ? $searchParams[$paramName]
            : null;
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Search for products';
    }

    /**
     * Check - search panel is visible or not
     *
     * @return boolean
     */
    public function isSearchVisible()
    {
        return 0 < \XLite\Core\Database::getRepo('XLite\Model\Product')->count();
    }

    /**
     * doActionUpdate
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $list = new \XLite\View\ItemsList\Model\Product\Admin\Search();
        $list->processQuick();
    }

    /**
     * doActionDelete
     *
     * @return void
     */
    protected function doActionDelete()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->deleteInBatchById($this->getSelected());
    }

    /**
     * doActionSearch
     *
     * @return void
     */
    protected function doActionSearch()
    {
        \XLite\Core\Session::getInstance()
            ->{\XLite\View\ItemsList\Model\Product\Admin\Search::getSessionCellName()} = $this->getSearchParams();

        $this->setReturnURL($this->buildURL('product_list', '', array('mode' => 'search')));
    }


    /**
     * Return search parameters for product list.
     * It is based on search params from Product Items list viewer
     *
     * @return array
     */
    protected function getSearchParams()
    {
        return $this->getSearchParamsCommon()
            + $this->getSearchParamsCheckboxes();
    }

    /**
     * Return search parameters for product list from Product Items list viewer
     *
     * @return array
     */
    protected function getSearchParamsCommon()
    {
        $productsSearchParams = array();

        foreach (
            \XLite\View\ItemsList\Model\Product\Admin\Search::getSearchParams() as $requestParam
        ) {
            if (isset(\XLite\Core\Request::getInstance()->$requestParam)) {

                $productsSearchParams[$requestParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }
        }

        return $productsSearchParams;
    }


    /**
     * Return search parameters for product list given as checkboxes: (0, 1) values
     *
     * @return array
     */
    protected function getSearchParamsCheckboxes()
    {
        $productsSearchParams = array();

        $cBoxFields = array(
            \XLite\View\ItemsList\Model\Product\Admin\Search::PARAM_SEARCH_IN_SUBCATS,
            \XLite\View\ItemsList\Model\Product\Admin\Search::PARAM_BY_TITLE,
            \XLite\View\ItemsList\Model\Product\Admin\Search::PARAM_BY_DESCR,
        );

        foreach ($cBoxFields as $requestParam) {

            $productsSearchParams[$requestParam] = isset(\XLite\Core\Request::getInstance()->$requestParam) ? 1 : 0;
        }

        return $productsSearchParams;
    }


    /**
     * Get search conditions
     *
     * @return array
     */
    protected function getConditions()
    {
        $searchParams = \XLite\Core\Session::getInstance()
            ->{\XLite\View\ItemsList\Model\Product\Admin\Search::getSessionCellName()};

        if (!is_array($searchParams)) {

            $searchParams = array();
        }

        return $searchParams;
    }
}
