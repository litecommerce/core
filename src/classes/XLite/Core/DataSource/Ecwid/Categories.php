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

namespace XLite\Core\DataSource\Ecwid;

/**
 * Ecwid categories collection
 * 
 */
class Categories extends \XLite\Core\DataSource\Base\Categories
{
    /**
     * Stores current iterator position
     * 
     * @var integer
     */
    protected $position;

    /**
     * Contains categories data
     * 
     * @var array
     */
    protected $categories;

    /**
     * Constructor 
     * 
     * @param \XLite\Core\DataSource\Ecwid $dataSource Ecwid data source
     *  
     * @return void
     */
    public function __construct(\XLite\Core\DataSource\Ecwid $dataSource)
    {
        parent::__construct($dataSource);

        $this->categories = $this->getDataSource()->callApi('categories');

        $this->rewind();
    }

    /**
     * Countable::count 
     * 
     * @return integer
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
     * @throws OutOfBoundException
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new \OutOfBoundsException("Ecwid Products: invalid position ($position)");
        }

        return $this->categories[$this->position];
    }
}
