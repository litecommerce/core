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
class Products extends \Xlite\Core\DataSource\Base\Products
{

    /**
     * Stores current iterator position
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.17
     */
    private $position;

    /**
     * Contains all products (though information for each is restrained)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.17
     */
    private $all_products;

    /**
     * An array with cached products (full info)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.17
     */
    private $products = array();

    /**
     * Constructor 
     * 
     * @param Ecwid $data_source Ecwid data source
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function __construct(Ecwid $data_source)
    {
        parent::__construct($data_source);

        $this->all_products = $this->getDataSource()->doApiCall('products');

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
        return count($this->all_products);
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
        ++$this->position;
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
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function current()
    {
        if (!isset($this->products[$this->position])) {
            $this->products[$this->position] = $this->getDataSource->doApiCall(
                'product',
                array(
                    'id' => $this->all_products[$this->position]['id']
                )
            );
        }

        return $this->products[$this->position];
    }
}
