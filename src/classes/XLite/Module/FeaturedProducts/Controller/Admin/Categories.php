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

namespace XLite\Module\FeaturedProducts\Controller\Admin;

/**
 * \XLite\Module\FeaturedProducts\Controller\Admin\Categories
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Categories extends \XLite\Controller\Admin\Categories implements \XLite\Base\IDecorator
{
    /**
     * doActionSearchFeaturedProducts
     * TODO: Rework using ItemsList
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearchFeaturedProducts()
    {
        $sessionCell    = \XLite\Module\FeaturedProducts\Model\FeaturedProduct::SESSION_CELL_NAME;
        $searchParams   = \XLite\View\ItemsList\Product\Admin\Search::getSearchParams();
        $productsSearch = array();
        $cBoxFields     = array(
            \XLite\View\ItemsList\Product\Admin\Search::PARAM_SEARCH_IN_SUBCATS
        );

        foreach ($searchParams as $modelParam => $requestParam) {
            if (isset(\XLite\Core\Request::getInstance()->$requestParam)) {
                $productsSearch[$modelParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }
        }

        foreach ($cBoxFields as $requestParam) {
            $productsSearch[$modelParam] = isset(\XLite\Core\Request::getInstance()->$requestParam)
                ? 1
                : 0;
        }

        $this->session->set($sessionCell, $productsSearch);
        $this->set('returnUrl',
            $this->buildUrl(
                'categories',
                '',
                array(
                    'mode' => 'search_featured_products',
                    'category_id' => \XLite\Core\Request::getInstance()->category_id
                )
            )
        );
    }

    /**
     * doActionAddFeaturedProducts 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAddFeaturedProducts()
    {
        if (isset(\XLite\Core\Request::getInstance()->product_ids)) {

            $pids = array_keys(\XLite\Core\Request::getInstance()->product_ids);

            $products = \XLite\Core\Database::getRepo('\XLite\Model\Product')
                ->findByIds($pids);

            $category = $this->category_id
                ? \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($this->category_id)
                : null;

            // Retreive existing featured products list of that category

            $existingLinksIds = array();
            $existingLinks = $this->getFeaturedProductsList();
            if ($existingLinks) {
                foreach ($existingLinks as $k => $v) {
                    $existingLinksIds[] = $v->getProduct()->getProductId();
                }
            }

            if ($products) {

                foreach ($products as $product) {

                    if (in_array($product->getProductId(), $existingLinksIds)) {
                        \XLite\Core\TopMessage::getInstance()->add(
                            'The product SKU#"' . $product->getSku() . '" is already set as featured for the category',
                            \XLite\Core\TopMessage::WARNING
                        );
                        continue;
                    }

                    $fp = new \XLite\Module\FeaturedProducts\Model\FeaturedProduct();
                    $fp->setProduct($product);

                    if ($category) {
                        $fp->setCategory($category);
                    }
                    \XLite\Core\Database::getEM()->persist($fp);
                }
            }

            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Get Featured products search result
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFeaturedSearchResult()
    {
        if ('search_featured_products' !== \XLite\Core\Request::getInstance()->mode) {
            return array();
        }

        $cnd = $this->session->get(\XLite\Module\FeaturedProducts\Model\FeaturedProduct::SESSION_CELL_NAME);

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);
        $this->featuredSearchResultCount = count($result);

        return $result;
    }

    /**
     * Get featured products list
     * 
     * @return array of \XLite\Module\FeaturedProducts\Model\FeaturedProduct objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFeaturedProductsList()
    {
        return \XLite\Core\Database::getRepo('\XLite\Module\FeaturedProducts\Model\FeaturedProduct')
            ->getFeaturedProducts($this->category_id);
    }

    /**
     * Process action 'update_featured_products'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateFeaturedProducts()
    {
        // Delete featured products if it was requested
        $toDelete = \XLite\Core\Request::getInstance()->delete;

        if ($toDelete) {

            $records = \XLite\Core\Database::getRepo('\XLite\Module\FeaturedProducts\Model\FeaturedProduct')
                ->findByIds(array_keys($toDelete));

            if ($records) {
                foreach ($records as $rec) {
                    \XLite\Core\Database::getEM()->remove($rec);
                }
            }
        }

        // Update order_by of featured products list is it was requested
        $orderbys = \XLite\Core\Request::getInstance()->orderbys;

        if ($orderbys) {
            $records = \XLite\Core\Database::getRepo('\XLite\Module\FeaturedProducts\Model\FeaturedProduct')
                ->findByIds(array_keys($orderbys));

            if ($records) {
                foreach ($records as $rec) {
                    $cell = array();
                    $cell['order_by'] = abs(intval($orderbys[$rec->getId()]));
                    $rec->map($cell);
                    \XLite\Core\Database::getEM()->persist($rec);
                }
            }
        }
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Get search conditions
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        $searchParamsOrig = $this->session->get(\XLite\Module\FeaturedProducts\Model\FeaturedProduct::SESSION_CELL_NAME);
        $searchSpecs      = \XLite\View\ItemsList\Product\Admin\Search::getSearchParams();
        $searchParams     = array();

        foreach ($searchSpecs as $modelParam => $requestParam) {
            $searchParams[$requestParam] = isset($searchParamsOrig[$modelParam])
                ? $searchParamsOrig[$modelParam]
                : null;
        }

        if (!is_array($searchParams)) {
            $searchParams = array();
        }

        return $searchParams;
    }

    /**
     * Get search condition parameter by name
     *
     * @param string $paramName
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
 
}
