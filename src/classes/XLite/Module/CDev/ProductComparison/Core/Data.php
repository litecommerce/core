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

namespace XLite\Module\CDev\ProductComparison\Core;

/**
 * Data class
 *
 */
class Data extends \XLite\Base\Singleton
{
    /**
     * Products count
     *
     * @var integer 
     */
    protected $productsCount;

    /**
     * Product ids 
     *
     * @var array 
     */
    protected $productIds;

    /**
     * Get products count 
     *
     * @return integer
     */
    public function getProductsCount()
    {
        if (!isset($this->productsCount)) {
            $this->productsCount = count($this->getProductIds());
        }

        return $this->productsCount;
    }

    /**
     * Add product id
     *
     * @param integer $productId Product id
     *
     * @return void
     */
    public function addProductId($productId)
    {
        $ids = $this->getProductIds();
        $ids[$productId] = $productId;
        $this->productIds = $ids;
        \XLite\Core\Session::getInstance()->productComparisonIds = $ids;
    }

    /**
     * Delete product id
     *
     * @param integer $productId Product id
     *
     * @return void
     */
    public function deleteProductId($productId)
    {
        $ids = $this->getProductIds();
        if (isset($ids[$productId])) {
            unset($ids[$productId]);
        }
        $this->productIds = $ids;
        \XLite\Core\Session::getInstance()->productComparisonIds = $ids;
    }

    /**
     * Get product ids 
     *
     * @return array 
     */
    protected function getProductIds()
    {
        if (!isset($this->productIds)) {
            $this->productIds = \XLite\Core\Session::getInstance()->productComparisonIds;
        }

        return is_array($this->productIds)
            ? $this->productIds
            : array();
    }
}
