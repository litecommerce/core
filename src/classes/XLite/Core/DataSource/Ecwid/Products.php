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
 * @since     1.0.17
 */

namespace XLite\Core\DataSource\Ecwid;

/**
 * Ecwid products collection
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class Products extends \XLite\Core\DataSource\Base\Products
{

    /**
     * Stores current iterator position
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.17
     */
    protected $position;

    /**
     * Contains all products (though information for each is restrained)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.17
     */
    protected $allProducts;

    /**
     * An array with cached products (full info)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.17
     */
    protected $products = array();

    /**
     * Constructor 
     * 
     * @param \XLite\Core\DataSource\Ecwid $dataSource Ecwid data source
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function __construct(\XLite\Core\DataSource\Ecwid $dataSource)
    {
        parent::__construct($dataSource);

        $this->allProducts = $this->getDataSource()->callApi('products');

        $this->rewind();

        // May be neccessary to use Ecwid `Batch` method to preload products data
    }

    /**
     * Countable::count 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function count()
    {
        return count($this->allProducts);
    }

    /**
     * SeekableIterator::key 
     * Returns current product index
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * SeekableIterator::rewind
     * Sets position to the start
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * SeekableIterator::next
     * Advances position one step forward
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * SeekableIterator::valid
     * Checks if current position is valid
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function valid()
    {
        return 0 <= $this->key() && $this->key() < $this->count();
    }

    /**
     * SeekableIterator::seek
     * Seeks to the specified position
     * 
     * @param mixed $position Position to go to
     *  
     * @return void
     * @throws OutOfBoundException
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function seek($position)
    {
        $this->position = $position;

        if (!$this->valid()) {
            throw new \OutOfBoundsException("Ecwid Products: invalid seek position ($position)");
        }
    }

    /**
     * SeekableIterator::current
     * Returns current product
     * 
     * @return void
     * @throws OutOfBoundException
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new \OutOfBoundsException("Ecwid Products: invalid position ($position)");
        }

        if (!isset($this->products[$this->position])) {
            // Do a batch call to prefetch next 100 (or 15?) products (maximum)
            // (optimized for forward access)
            $max = 15;
            $num = min($max, $this->count() - $this->position);

            $params = array();
            foreach (range($this->position, $this->position + $num - 1) as $index) {
                $params[$index] = array(
                    'method' => 'product',
                    'params' => array(
                        'id' => $this->allProducts[$index]['id'],
                    )
                );
            }

            foreach ($this->getDataSource()->callBatchApi($params) as $key => $p) {
                $this->products[$key] = $this->normalizeProduct($p);
            }
        }

        return $this->products[$this->position];
    }

    /**
     * Normalize product 
     * 
     * @param array $data Raw data
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function normalizeProduct(array $data)
    {
        $product = array(
            'id'          => $data['id'],
            'categories'  => array(),
            'images'      => array(),
            'url'         => $data['url'],
            'price'       => doubleval($data['price']),
            'name'        => $data['name'],
            'description' => empty($data['description']) ? '' : $data['description'],
            'sku'         => empty($data['sku']) ? '' : $data['sku'],
            'quantity'    => empty($data['quantity']) ? 0 : intval($data['quantity']),
        );

        if (!empty($data['imageUrl'])) {
            $product['images'][] = array(
                'url'    => $data['imageUrl'],
                'main'   => true,
                'width'  => 0,
                'height' => 0,
                'type'   => '',
            );
        }

        if (!empty($data['galleryImages']) && is_array($data['galleryImages'])) {
            foreach ($data['galleryImages'] as $image) {
                $product['images'][] = array(
                    'url'    => $image['url'],
                    'main'   => false,
                    'width'  => 0,
                    'height' => 0,
                    'type'   => '',
                );
            }
        }

        if (!empty($data['categories']) && is_array($data['categories'])) {
            $product['categories'] = $data['categories'];
        }

        return $product;
    }
}
