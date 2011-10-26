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
 * @since     1.0.12
 */

namespace XLite\Module\CDev\XMLSitemap\Logic;

/**
 * Sitemap links iterator 
 * 
 * @see   ____class_see____
 * @since 1.0.12
 */
class SitemapIterator extends \XLite\Base implements \SeekableIterator, \Countable
{
    /**
     * Position 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.12
     */
    protected $position = 0;

    /**
     * Categories length 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.12
     */
    protected $categoriesLength;

    /**
     * Products length 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.12
     */
    protected $productsLength;

    /**
     * Constructor
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get current data
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function current()
    {
        $data = null;

        if ($this->position < $this->getCategoriesLength()) {

            $data = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneAsSitemapLink($this->position, 1);
            $data = $this->assembleCategoryData($data);

        } elseif ($this->position < $this->getCategoriesLength() + $this->getProductsLength()) {

            $data =  \XLite\Core\Database::getRepo('XLite\Model\Product')->findFrame($this->position - $this->getCategoriesLength(), 1);
            $data = $this->assembleProductData($data[0]);

        }

        return $data;
    }

    /**
     * Get current key 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Go to next record
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Rewind position
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function rewind()
    {
        $this->position = 0;
        $this->categoriesLength = null;
        $this->productsLength = null;
    }

    /**
     * Check current position
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function valid()
    {
        return $this->position < $this->count();
    }

    /**
     * Seek 
     * 
     * @param integer $position New position
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function seek($position)
    {
        $this->position = $position;
    }

    /**
     * Get length 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function count()
    {
        return $this->getCategoriesLength() + $this->getProductsLength();
    }

    /**
     * Get categories length 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function getCategoriesLength()
    {
        if (!isset($this->categoriesLength)) {
            $this->categoriesLength = \XLite\Core\Database::getRepo('XLite\Model\Category')
                ->countCategoriesAsSitemapsLinks();
        }

        return $this->categoriesLength;
    }

    /**
     * Get products length
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function getProductsLength()
    {
        if (!isset($this->productsLength)) {
            $this->productsLength = \XLite\Core\Database::getRepo('XLite\Model\Product')->count();
        }

        return $this->productsLength;
    }

    /**
     * Assemble category data 
     * 
     * @param \XLite\Model\Category $category Category
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function assembleCategoryData(\XLite\Model\Category $category)
    {
        return array(
            'loc'        => array('target' => 'category', 'category_id' => $category->getCategoryId()),
            'lastmod'    => time(),
            'changefreq' => 'daily',
            'priority'   => 0.5,
        );
    }

    /**
     * Assemble product data 
     * 
     * @param \XLite\Model\Product $product Product
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function assembleProductData(\XLite\Model\Product $product)
    {
        return array(
            'loc'        => array('target' => 'product', 'product_id' => $product->getProductId()),
            'lastmod'    => time(),
            'changefreq' => 'daily',
            'priority'   => 0.4,
        );
    }

}

