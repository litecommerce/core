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

namespace XLite\Controller\Customer;

/**
 * Products search
 *
 */
class Search extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Get search condition parameter by name TODO refactor with XLite\Controller\Admin\ProductList::getCondition()
     *
     * @param string $paramName Name of parameter
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
     * Return 'checked' attribute for parameter.
     *
     * @param string $paramName Name of parameter
     * @param mixed  $value     Value to check with OPTIONAL
     *
     * @return string
     */
    public function getChecked($paramName, $value = 'Y')
    {
        return $value === $this->getCondition($paramName) ? 'checked' : '';
    }


    /**
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return 'Search results';
    }

    /**
     * doActionSearch TODO refactor with XLite\Controller\Admin\ProductList::doActionSearch()
     *
     * @return void
     */
    protected function doActionSearch()
    {
        $sessionCell    = \XLite\View\ItemsList\Product\Customer\Search::getSessionCellName();
        $searchParams   = \XLite\View\ItemsList\Product\Customer\Search::getSearchParams();

        $productsSearch = array();

        $cBoxFields     = array(
            \XLite\View\ItemsList\Product\Customer\Search::PARAM_SEARCH_IN_SUBCATS
        );

        foreach ($searchParams as $modelParam => $requestParam) {

            if (isset(\XLite\Core\Request::getInstance()->$requestParam)) {

                $productsSearch[$requestParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }
        }

        foreach ($cBoxFields as $requestParam) {

            $productsSearch[$requestParam] = isset(\XLite\Core\Request::getInstance()->$requestParam)
                ? 1
                : 0;
        }

        \XLite\Core\Session::getInstance()->{$sessionCell} = $productsSearch;

        $this->setReturnURL($this->buildURL('search', '', array('mode' => 'search')));
    }

    /**
     * Get search conditions TODO refactor with XLite\Controller\Admin\ProductList::getConditions()
     *
     * @return array
     */
    protected function getConditions()
    {
        $searchParams = \XLite\Core\Session::getInstance()
            ->{\XLite\View\ItemsList\Product\Customer\Search::getSessionCellName()};

        if (!is_array($searchParams)) {

            $searchParams = array();
        }

        return $searchParams;
    }
}
