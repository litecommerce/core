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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Product 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Controller\Admin\AAdmin
{
    /**
     * FIXME- backward compatibility 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'product_id', 'page', 'backURL');

    /**
     * FIXME- backward compatibility
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $page = 'info';

    /**
     * FIXME- backward compatibility
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $pages = array(
        'info'      => 'Product info',
        'images'    => 'Product images',
        'inventory' => 'Inventory tracking',
    );

    /**
     * FIXME- backward compatibility 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $pageTemplates = array(
        'info'      => 'product/info.tpl',
        'default'   => 'product/info.tpl',
        'images'    => 'product/product_images.tpl',
        'inventory' => 'product/inventory.tpl'
    );


    /**
     * Alias
     *
     * @return \XLite\Model\Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProduct()
    {
        if (!$this->isNew()) {
            $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($this->getProductId());
        }

        if (!isset($result)) {
            $result = new \XLite\Model\Product();
        }

        return $result;
    }

    /**
     * Return the current page title (for the content area)
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return $this->getProduct()->getName();
    }

    /**
     * getInventory 
     * 
     * @return \XLite\Model\Inventory
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInventory()
    {
        return $this->getProduct()->getInventory();
    }

    /**
     * Get product category id
     *
     * @return integer 
     * @access public
     * @since  3.0.0
     */
    public function getCategoryId()
    {
        $categoryId = parent::getCategoryId();

        if (empty($categoryId) && !$this->isNew()) {
            $categoryId = $this->getProduct()->getCategoryId();
        }

        return $categoryId;
    }

    /**
     * Return current product Id
     * 
     * NOTE: this function is public since it's neede for widgets
     *
     * @return integer 
     * @access public
     * @since  3.0.0
     */
    public function getProductId()
    {
        return intval(\XLite\Core\Request::getInstance()->product_id);
    }

    /**
     * Check if we need to create new product or modify an existsing one
     *
     * NOTE: this function is public since it's neede for widgets
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isNew()
    {
        return 0 >= $this->getProductId();
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->getProduct()->getName();
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Search products', $this->buildURL('product_list'));
    }

    /**
     * Return list of the CategoryProduct entities
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategoryProducts(\XLite\Model\Product $product)
    {
        $data = array();

        foreach ((array) $this->getPostedData('category_ids') as $categoryId) {
            $data[] = new \XLite\Model\CategoryProducts(
                array(
                    'product_id'  => $product->getProductId(),
                    'category_id' => $categoryId,
                    'category'    => \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($categoryId),
                    'product'     => $product,
                )
            );
        }

        return array('categoryProducts' => new \Doctrine\Common\Collections\ArrayCollection($data));
    }

    /**
     * getClasses 
     * 
     * @param \XLite\Model\Product $product ____param_comment____
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClasses(\XLite\Model\Product $product)
    {
        $data = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ((array) $this->getPostedData('class_ids') as $classId) {

            $class = \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->findOneById($classId);

            if (!$class->getProducts()->contains($product)) {

                $class->getProducts()->add($product);
            }

            $data->add($class);
        }

        return array('classes' => $data);
    }

    /**
     * Set error
     * 
     * @param string $cleanURL Clean URL
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setCleanURLError($cleanURL)
    {
        \XLite\Core\TopMessage::addError(
            'The "{{clean_url}}" clean URL is already defined',
            array('clean_url' => $data['clean_url'])
        );
    }

    /**
     * Check if specified clean URL is unique or not
     * 
     * @param string $cleanURL Clean URL
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkCleanURL($cleanURL)
    {
        $result = empty($cleanURL);

        if (!$result) {
            $entity = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByCleanURL($cleanURL);
            $result = !isset($entity) || $entity->getProductId() === $this->getProductId();

            if (!$result) {
                $this->setCleanURLError($cleanURL);
            }
        }

        return $result;
    }


    /**
     * doActionModify 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionModify()
    {
        if ($this->checkCleanURL($this->getPostedData('clean_url'))) {
            $this->isNew() ? $this->doActionAdd() : $this->doActionUpdate();
        }
    }

    /**
     * doActionAdd 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {
        // Insert record into main table
        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->insert($this->getPostedData());
        
        if ($product) {

            // Create associations (categories and images)
            \XLite\Core\Database::getRepo('\XLite\Model\Product')->update(
                $product,
                $this->getCategoryProducts($product)
            );

            \XLite\Core\TopMessage::addInfo(
                'New product has been successfully added'
            );

            // Add the ID of created product to the return URL
            $this->setReturnURL($this->buildURL('product', '', array('product_id' => $product->getProductId())));
        }
    }

    /**
     * doActionUpdate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $product = $this->getProduct();

        // Clear all category associates
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->deleteInBatch(
            $product->getCategoryProducts()
        );

        $product->getClasses()->clear();

        $data = $this->getCategoryProducts($product) + $this->getClasses($product) + $this->getPostedData();

        // Update all data
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->update(
            $product,
            $data
        );

        \XLite\Core\TopMessage::addInfo(
            'Product info has been successfully updated'
        );
    }

    /**
     * Add detailed image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAddImage()
    {
        $img = new \XLite\Model\Image\Product\Image();

        if ($img->loadFromRequest('postedData', 'image')) {

            $data = \XLite\Core\Request::getInstance()->getData();

            $img->map($data);

            $img->setProduct($this->getProduct());
            $this->getProduct()->getImages()->add($img);

            \XLite\Core\Database::getEM()->persist($img);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The detailed image has been successfully added'
            );

        } else {
            \XLite\Core\TopMessage::addError(
                'The detailed image has not been successfully added'
            );
        }
    }

    /**
     * Delete detailed image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteImage()
    {
        $img = \XLite\Core\Database::getRepo('\XLite\Model\Image\Product\Image')
            ->find(\XLite\Core\Request::getInstance()->image_id);

        if ($img) {
            $img->getProduct()->getImages()->removeElement($img);
            \XLite\Core\Database::getEM()->remove($img);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The detailed image has been deleted'
            );

        } else {

            \XLite\Core\TopMessage::addError(
                'The detailed image has not been deleted'
            );
        }
    }

    /**
     * Update image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateImages()
    {
        $zoomId = 0;
        if (isset(\XLite\Core\Request::getInstance()->is_zoom)) {
            $keys = array_keys(\XLite\Core\Request::getInstance()->is_zoom);
            $zoomId = array_shift($keys);
        }

        foreach (\XLite\Core\Request::getInstance()->alt as $imageId => $alt) {
            $img = \XLite\Core\Database::getRepo('\XLite\Model\Image\Product\Image')
                ->find($imageId);

            if ($img) {
                $img->setAlt($alt);
                $img->setOrderby(\XLite\Core\Request::getInstance()->orderby[$imageId]);

                \XLite\Core\Database::getEM()->persist($img);
            }
        }

        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\TopMessage::addInfo(
            'The detailed images have been successfully updated'
        );
    }

    /**
     * Update inventory 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateInventory()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Inventory')->updateById(
            $this->getProductId(),
            $this->getPostedData()
        );
    }
}
