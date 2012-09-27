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

namespace XLite\Core\DataSource\Importer;

/**
 * Product importer
 * 
 */
abstract class Product extends \XLite\Base
{
    /**
     * Product fields 
     * 
     * @var array
     */
    protected $productFields = array(
        'sku'         => 'sku',
        'name'        => 'name',
        'description' => 'description',
        'price'       => 'price',
        'amount'      => 'quantity',
    );

    /**
     * Collection 
     * 
     * @var \XLite\Core\DataSource\Base\Products
     */
    protected $collection;

    /**
     * Adding count 
     * 
     * @var integer
     */
    protected $addCount = 0;

    /**
     * Updating count 
     * 
     * @var integer
     */
    protected $updateCount = 0;

    /**
     * Constructor
     * 
     * @param \XLite\Core\DataSource\Base\Products $collection Products collection
     *  
     * @return void
     */
    public function __construct(\XLite\Core\DataSource\Base\Products $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get adding count 
     * 
     * @return integer
     */
    public function getAddCount()
    {
        return $this->addCount;
    }

    /**
     * Get updating count 
     * 
     * @return integer
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
     */
    public function run()
    {
        $this->runImport();
    }

    /**
     * Run import 
     * 
     * @return void
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
     */
    protected function checkStep()
    {
        return $this->collection->valid();
    }

    /**
     * Get cell 
     * 
     * @return array
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
     */
    protected function detectProduct(array $cell)
    {
        $product = null;
        $repo = \XLite\Core\Database::getRepo('XLite\Model\Product');

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
     */
    protected function createProduct(array $cell)
    {
        $product = new \XLite\Model\Product;
        $product->setInventory($product->getInventory());
        $product->getInventory()->setProduct($product);
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
     */
    protected function update(\XLite\Model\Product $product, array $cell)
    {
        foreach ($this->productFields as $field => $name) {
            if (isset($cell[$name])) {
                $product->$field = $cell[$name];
            }
        }

        if (isset($cell['images'])) {
            $this->updateImages($product, $cell['images']);
        }

        if (isset($cell['categories'])) {
            $this->updateCategories($product, $cell['categories']);
        }
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
     */
    protected function createImage(\XLite\Model\Product $product, array $image)
    {
        $model = new \XLite\Model\Image\Product\Image;
        $model->setProduct($product);

        if ($model->loadFromURL($image['url'], $this->isLoadImageToLocalFileSystem())) {
            $product->addImages($model);
            \XLite\Core\Database::getEM()->persist($model);

        } else {
            \XLite\Logger::getInstance()->log(
                'The picture of the product (' . $image['url'] . ') was not imported.',
                LOG_ERR
            );
        }

        return $model;
    }

    /**
     * Load image to local file system or not
     * 
     * @return boolean
     */
    protected function isLoadImageToLocalFileSystem()
    {
        return false;
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
     */
    protected function updateCategories(\XLite\Model\Product $product, array $categories)
    {
    }

    // }}}

}

