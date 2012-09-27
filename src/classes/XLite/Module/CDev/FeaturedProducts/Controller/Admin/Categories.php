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
class Categories extends \XLite\Controller\Admin\Categories implements \XLite\Base\IDecorator
{
    /**
     * FIXME- backward compatibility
     *
     * @var array
     */
    protected $params = array('category_id');


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
     * Get featured products list
     *
     * @return array(\XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct) Objects
     */
    public function getFeaturedProductsList()
    {
        return \XLite\Core\Database::getRepo('\XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
            ->getFeaturedProducts($this->category_id);
    }


    /**
     * doActionAddFeaturedProducts
     *
     * @return void
     */
    protected function doActionAddFeaturedProducts()
    {
        if (isset(\XLite\Core\Request::getInstance()->product_ids)) {

            $pids = array_keys(\XLite\Core\Request::getInstance()->product_ids);

            $products = \XLite\Core\Database::getRepo('\XLite\Model\Product')
                ->findByIds($pids);

            if (!$this->categoryId) {

                $this->categoryId = \XLite\Core\Database::getRepo('\XLite\Model\Category')->getRootCategoryId();
            }

            $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($this->categoryId);

            // Retreive existing featured products list of that category

            $this->category_id = $this->categoryId;

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

                        \XLite\Core\TopMessage::addWarning(
                            'The product SKU#"' . $product->getSku() . '" is already set as featured for the category'
                        );

                    } else {

                        $fp = new \XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct();

                        $fp->setProduct($product);

                        if ($category) {
                            $fp->setCategory($category);
                        }

                        \XLite\Core\Database::getEM()->persist($fp);
                    }
                }
            }

            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Process action 'update_featured_products'
     *
     * @return void
     */
    protected function doActionUpdateFeaturedProducts()
    {
        // Delete featured products if it was requested
        $toDelete = \XLite\Core\Request::getInstance()->delete;

        if ($toDelete) {

            $records = \XLite\Core\Database::getRepo('\XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
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

            $records = \XLite\Core\Database::getRepo('\XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
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
     */
    protected function getConditions()
    {
        $searchParams = \XLite\Core\Session::getInstance()
            ->{\XLite\Module\CDev\FeaturedProducts\View\Admin\FeaturedProducts::getSessionCellName()};

        if (!is_array($searchParams)) {

            $searchParams = array();
        }

        return $searchParams;
    }
}
