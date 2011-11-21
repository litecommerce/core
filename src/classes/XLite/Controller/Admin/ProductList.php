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

namespace XLite\Controller\Admin;


/**
 * Products list controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ProductList extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Get search condition parameter by name
     *
     * @param string $paramName Parameter name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Search products';
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'Search product';
    }

    /**
     * doActionUpdate
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdate()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->updateInBatchById($this->getPostedData());
    }

    /**
     * doActionDelete
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->deleteInBatchById($this->getToDelete());
    }

    /**
     * doActionSearch
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSearch()
    {
        \XLite\Core\Session::getInstance()
            ->{\XLite\View\ItemsList\Product\Admin\Search::getSessionCellName()} = $this->getSearchParams();

        $this->setReturnURL($this->buildURL('product_list', '', array('mode' => 'search')));
    }


    /**
     * Return search parameters for product list.
     * It is based on search params from Product Items list viewer
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchParamsCommon()
    {
        $productsSearchParams = array();

        foreach (
            \XLite\View\ItemsList\Product\Admin\Search::getSearchParams() as $requestParam
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchParamsCheckboxes()
    {
        $productsSearchParams = array();

        $cBoxFields = array(
            \XLite\View\ItemsList\Product\Admin\Search::PARAM_SEARCH_IN_SUBCATS,
            \XLite\View\ItemsList\Product\Admin\Search::PARAM_BY_TITLE,
            \XLite\View\ItemsList\Product\Admin\Search::PARAM_BY_DESCR,
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getConditions()
    {
        $searchParams = \XLite\Core\Session::getInstance()
            ->{\XLite\View\ItemsList\Product\Admin\Search::getSessionCellName()};

        if (!is_array($searchParams)) {

            $searchParams = array();
        }

        return $searchParams;
    }
}
