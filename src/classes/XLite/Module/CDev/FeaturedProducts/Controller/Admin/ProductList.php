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

namespace XLite\Module\CDev\FeaturedProducts\Controller\Admin;

/**
 * \XLite\Module\CDev\FeaturedProducts\Controller\Admin\Categories
 *
 */
class ProductList extends \XLite\Controller\Admin\ProductList implements \XLite\Base\IDecorator
{
    /**
     * doActionSearch
     *
     * @return void
     */
    protected function doActionSearchFeaturedProducts()
    {
        $sessionCell    = \XLite\Module\CDev\FeaturedProducts\View\Admin\FeaturedProducts::getSessionCellName();

        $searchParams   = \XLite\View\ItemsList\Model\Product\Admin\Search::getSearchParams();

        $productsSearch = array();

        $cBoxFields     = array(
            \XLite\View\ItemsList\Model\Product\Admin\Search::PARAM_SEARCH_IN_SUBCATS
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
    }
}
