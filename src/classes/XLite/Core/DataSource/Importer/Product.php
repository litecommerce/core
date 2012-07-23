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
     * Product fields 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.21
     */
    protected $productFields = array(
        'sku'         => 'sku',
        'name'        => 'name',
        'description' => 'description',
        'price'       => 'price',
        'weight'      => 'weight',
        'amount'      => 'quantity',
    );

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
     * Import count 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.24
     */
    protected $importCount = 0;

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

    /**
     * Log operation 
     * 
     * @param string $operation Operation name
     * @param float  $duration  Duration (seconds)
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function logOperation($operation, $duration)
    {
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
        $this->importCount = 0;

        $ts = microtime(true);
        while ($this->checkStep()) {
            $ts2 = microtime(true);
            $cell = $this->getCell();
            $this->logOperation('Read product info (eid: ' . $cell['id'] . ')', microtime(true) - $ts2);

            $ts2 = microtime(true);
            $this->import($this->getCell());
            $this->logOperation('Import product (eid: ' . $cell['id'] . ')', microtime(true) - $ts2);

            $ts2 = microtime(true);
            $this->collection->next();
            $this->logOperation('Go to next product (eid: ' . $cell['id'] . ')', microtime(true) - $ts2);
            $this->importCount++;
        }
        $duration = microtime(true) - $ts;
        \XLite\Logger::getInstance()->log(
            'Product import from data source statistics:'
            . ' duration: ' . round($duration, 4) . 's.;'
            . ' duration per product: ' . round($duration / $this->importCount, 4) . 's.'
        );

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
        $ts = microtime(true);
        $product = $this->detectProduct($cell);
        $this->logOperation(
            'Detect product (' . ($product ? 'detected' : 'undetected') . ')',
            microtime(true) - $ts
        );

        if ($product) {
            $this->updateCount++;

        } else {
            $this->addCount++;
            $ts = microtime(true);
            $product = $this->createProduct($cell);
            $this->logOperation(
                'Create product (eid: ' . $cell['id'] . ')',
                microtime(true) - $ts
            );
        }

        $ts = microtime(true);
        $this->update($product, $cell);
        $this->logOperation(
            'Update product (eid: ' . $cell['id'] . ')',
            microtime(true) - $ts
        );
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
     * @see    ____func_see____
     * @since  1.0.19
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
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function update(\XLite\Model\Product $product, array $cell)
    {
        foreach ($this->productFields as $field => $name) {
            if (isset($cell[$name])) {
                $product->$field = $cell[$name];
            }
        }

        if (isset($cell['images'])) {
            $ts = microtime(true);
            $this->updateImages($product, $cell['images']);
            $this->logOperation(
                'Update images (' . count($cell['images']) . ')',
                microtime(true) - $ts
            );
        }

        if (isset($cell['categories'])) {
            $ts = microtime(true);
            $this->updateCategories($product, $cell['categories']);
            $this->logOperation(
                'Update categories (' . count($cell['categories']) . ')',
                microtime(true) - $ts
            );
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
        $hash = $this->getImageHash($image);

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
     * Get image hash 
     * 
     * @param array $image Image data
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getImageHash(array $image)
    {
        if (empty($image['md5'])) {
            $request = new \XLite\Core\HTTP\Request($image['url']);
            $request->verb = 'head';
            $response = $request->sendRequest();
            if (
                200 == $response->code
                && $response->headers->ETag
                && preg_match('/"(\w{32})"/Ss', $response->headers->ETag, $match)
            ) {
                $image['md5'] = $match[1];
            }
        }

        return !empty($image['md5']) ? $image['md5'] : \Includes\Utils\FileManager::getHash($image['url']);
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
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function isLoadImageToLocalFileSystem()
    {
        return false;
    }

    // }}}

    // {{{ Update categories

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

