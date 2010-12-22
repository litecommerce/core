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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Product 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Product extends \XLite\Controller\Admin\AAdmin
{
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

        $this->addLocationNode('Search products', $this->buildURL('products'));
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
            $entity = \XLite\Core\Database::getRepo('\XLite\Model\Product')->findOneByCleanUrl($cleanURL);
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
        if ($product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->insert($this->getPostedData())) {

            // Create associations (categories and images)
            \XLite\Core\Database::getRepo('\XLite\Model\Product')->update(
                $product,
                $this->getCategoryProducts($product)
            );

            // Add the ID of created product to the return URL
            $this->setReturnUrl($this->buildURL('product', '', array('product_id' => $product->getProductId())));
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

        // Update all data
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->update(
            $product,
            $this->getCategoryProducts($product) + $this->getPostedData()
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

            \XLite\Core\TopMessage::getInstance()->add(
                'The detailed image has been successfully added'
            );

        } else {
            \XLite\Core\TopMessage::getInstance()->add(
                'The detailed image has not been successfully added',
                 \XLite\Core\TopMessage::ERROR
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

            \XLite\Core\TopMessage::getInstance()->add(
                'The detailed image has been deleted'
            );

        } else {

            \XLite\Core\TopMessage::getInstance()->add(
                'The detailed image has not been deleted',
                \XLite\Core\TopMessage::ERROR
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

        \XLite\Core\TopMessage::getInstance()->add(
            'The detailed images have been successfully updated'
        );
    }


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
     * FIXME- backward compatibility 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'product_id', 'page', 'backUrl');

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
    	'info'            => 'Product info',
        'images'          => 'Product images',
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
    	'info'            => 'product/info.tpl',
        'default'         => 'product/info.tpl',
        'images'          => 'product/product_images.tpl',
    );







    /*function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new \XLite\Model\Product($this->product_id);
        }

        return $this->product;
    }
    
    function action_info()
    {
        // update product properties
        $product = new \XLite\Model\Product($this->product_id);
        $properties = \XLite\Core\Request::getInstance()->getData();

        // Sanitize
        if (isset($properties['clean_url'])) {
            $properties['clean_url'] = $this->sanitizeCleanURL($properties['clean_url']);
            if (
                0 < strlen($properties['clean_url'])
                && !$this->checkCleanURLUnique($properties['clean_url'])
            ) {

                \XLite\Core\TopMessage::getInstance()->add(
                    'The Clean URL you specified is already in use. Please specify another Clean URL',
                    \XLite\Core\TopMessage::ERROR
                );
                $this->set('valid', false);
                return;
            }
        }

        $product->set('properties', $properties);
        $product->update();
        
        // update product image and thumbnail
        $this->action_images();

        // link product category(ies)
        if (isset($this->category_id)) {
            $category = new \XLite\Model\Category($this->category_id);
            $product->set('category', $category);
        }
    }

    function action_images()
    {
        $tn = $this->getComplex('product.thumbnail');
        if ($tn->handleRequest() != \XLite\Model\Image::IMAGE_OK && $tn->_shouldProcessUpload) {
        	$this->set('valid', false);
        	$this->set('thumbnail_read_only', true);
        }

        $img = $this->getComplex('product.image');
        if ($img->handleRequest() != \XLite\Model\Image::IMAGE_OK && $img->_shouldProcessUpload) {
        	$this->set('valid', false);
        	$this->set('image_read_only', true);
        }
    }

    function action_clone()
    {
        $p_product = new \XLite\Model\Product($this->product_id);
        $product = $p_product->cloneObject();
        foreach ($p_product->get('categories') as $category) {
            $product->addCategory($category);
        }
        $product->set('name', $product->get('name') . " (CLONE)");
        $product->update();
        $this->set('returnUrl', 'admin.php?target=product&product_id=' . $product->get('product_id'));
    }*/
}
