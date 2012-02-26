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
 * Ecwid categories collection
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class Categories extends \Xlite\Core\DataSource\Base\Categories
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
     * Contains categories data
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.17
     */
    private $categories;

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

        $this->categories = $this->getDataSource()->doApiCall('categories');

        $this->rewind();
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
        return count($this->categories);
    }

    /**
     * SeekableIterator::key 
     * Returns current category index
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
            throw new \OutOfBoundsException("Ecwid Categories: invalid seek position ($position)");
        }
    }

    /**
     * SeekableIterator::current
     * Returns current category
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function current()
    {
        return $this->categories[$this->position];
    }
}
