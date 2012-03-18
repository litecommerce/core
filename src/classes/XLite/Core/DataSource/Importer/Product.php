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
 * @since     1.0.19
 */

namespace XLite\Core\DataSource\Importer;

/**
 * Product importer
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
abstract class Product extends \XLite\Base
{
    /**
     * Collection 
     * 
     * @var   \XLite\Core\DataSource\Base\Products
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $collection;

    /**
     * Adding count 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $addCount = 0;

    /**
     * Updating count 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $updateCount = 0;

    /**
     * Constructor
     * 
     * @param \XLite\Core\DataSource\Base\Products $collection Products collection
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function __construct(\XLite\Core\DataSource\Base\Products $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get adding count 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function getAddCount()
    {
        return $this->addCount;
    }

    /**
     * Get updating count 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function getUpdateCount()
    {
        return $this->updateCount;
    }

    // {{{ Import

    /**
     * Run importer
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function run()
    {
        $this->runImport();
    }

    /**
     * Run import 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function runImport()
    {
        $this->addCount = 0;
        $this->updateCount = 0;

        while ($this->checkStep()) {
            $this->import($this->getCell());
            $this->collection->next();
        }

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Check step 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function checkStep()
    {
        return $this->collection->valid();
    }

    /**
     * Get cell 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getCell()
    {
        return $this->collection->current();
    }

    /**
     * Import 
     * 
     * @param array $cell CEll
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function import(array $cell)
    {
        $product = $this->detectProduct($cell);
        if ($product) {
            $this->updateCount++;

        } else {
            $this->addCount++;
            $product = $this->createProduct($cell);
        }

        $this->update($product, $cell);
    }

    /**
     * Detect product 
     * 
     * @param array $cell Cell
     *  
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function detectProduct(array $cell)
    {
        $product = null;
        $repo = \XLite\Core\Database::getRepo('XLite\Mo9del\Product');

        if (!empty($cell['sku'])) {
            $product = $repo->findOneBy(array('sku' => $cell['sku']));
        }

        return $product;
    }

    /**
     * Create product 
     * 
     * @param array $cell Cell
     *  
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function createProduct(array $cell)
    {
        $product = new \XLite\Model\Product;
        \XLite\Core\Database::getEM()->persist($product);

        return $product;
    }

    /**
     * Update product
     * 
     * @param \XLite\Model\Product $product Product
     * @param array                $cell    Cell
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function update(\XLite\Model\Product $product, array $cell)
    {
        $product->setSku($cell['sku']);
        $product->setName($cell['name']);
        $product->setDescription($cell['description']);
        $product->setPrice($cell['price']);
        $product->setAmount($cell['quantity']);

        $this->updateImages($product, $data['images']);
        $this->updateCategories($product, $data['categories']);
    }

    // }}}

    // {{{ Update images

    /**
     * Update images 
     * 
     * @param \XLite\Model\Product $product Product
     * @param array                $images  Images
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function updateImages(\XLite\Model\Product $product, array $images)
    {
        $ids = array();
        foreach ($product->getImages() as $image) {
            $ids[$image->getId()] = $image;
        }

        foreach ($images as $image) {
            $model = $this->detectImage($product, $images);

            if ($model) {
                unset($ids[$model->getId()]);

            } else {
                $this->createImage($product, $image);
            }
        }

        foreach ($ids as $image) {
            \XLite\Core\Database::getEM()->remove($image);
            $product->getImages()->removeElement($image);
        }
    }

    /**
     * Detect image 
     * 
     * @param \XLite\Model\Product $product Product
     * @param array                $image   Image info
     *  
     * @return \XLite\Model\Image\Product\Image
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function detectImage(\XLite\Model\Product $product, array $image)
    {
        $hash = \Includes\Utils\FileManager::getHash($image['url']);

        $model = null;
        if ($hash) {
            foreach ($product->getImages() as $oldImage) {
                if ($oldImage->getHash() == $hash) {
                    $model = $oldImage;
                    break;
                }
            }
        }

        return $model;
    }

    /**
     * Create image
     *
     * @param \XLite\Model\Product $product Product
     * @param array                $image   Image info
     *
     * @return \XLite\Model\Image\Product\Image
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function createImage(\XLite\Model\Product $product, array $image)
    {
        $model = new \XLite\Model\Image\Product\Image;
        $model->setProduct($product);

        if ($model->loadFromURL($image['url'])) {
            $product->addImages($model);
            \XLite\Core\Database::getEM()->persist($model);
        }

        return $model;
    }

    // }}}

    // {{{ update categories

    /**
     * Update categories 
     * 
     * @param \XLite\Model\Product $product    Product
     * @param array                $categories Categories
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function updateCategories(\XLite\Model\Product $product, array $categories)
    {
    }

    // }}}

}

