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
class Product extends Catalog
{
    /**
     * Get product category id
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryId()
    {
        $categoryId = parent::getCategoryId();

        if (empty($categoryId) && !$this->isNew()) {
            $categoryId = $this->getProduct()->getCategoryId();
        }

        return $categoryId;
    }


    /**
     * Return current (or default) product object
     *
     * @return \XLite\Model\Product
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return $this->getProduct();
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Product
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProduct()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($this->getProductId());

        if (!isset($result)) {
            $result = new \XLite\Model\Product();
        }

        return $result;
    }

    /**
     * Return list of the CategoryProduct entities
     * 
     * @param int $productId product ID to map
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategoryProducts($productId)
    {
        $data = array();
        $product =  new \XLite\Model\Product(array('product_id' => $productId));

        foreach ($this->getPostedData('category_ids') as $categoryId) {
            $data[] = new \XLite\Model\CategoryProducts(
                array(
                    'category' => new \XLite\Model\Category(array('category_id' => $categoryId)),
                    'product'  => $product,
                )
            );
        }

        return new \Doctrine\Common\Collections\ArrayCollection($data);
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
        $data = $this->getPostedData();

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
        $id = \XLite\Core\Database::getRepo('\XLite\Model\Product')->insert(
            $this->getPostedData()
        )->getProductId();

        if (!empty($id)) {
            $this->setReturnUrl($this->buildURL('product', '', array('product_id' => $id)));
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
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->updateById(
            $this->getProductId(),
            $this->getPostedData() + array('category_products' => $this->getCategoryProducts($this->getProductId()))
        );
    }


    /**
     * Return current product Id
     * 
     * NOTE: this function is public since it's neede for widgets
     *
     * @return int
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
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isNew()
    {
        return 0 >= $this->getProduct()->getProductId();
    }



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
    	'info'         => 'Product info',
        'extra_fields' => 'Extra fields',
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
    	'info'         => 'product/info.tpl',
        'extra_fields' => 'product/extra_fields_form.tpl',
        'default'      => 'product/info.tpl'
    );









    /*function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new \XLite\Model\Product($this->product_id);
        }

        if (is_null($this->extraFields)) {
        	$this->getExtraFields();
        }

        return $this->product;
    }
    
    function getExtraFields()
    {
        $this->product->populateExtraFields();

        if (is_null($this->extraFields)) {
            $ef = new \XLite\Model\ExtraField();
            $this->extraFields = $ef->findAll("product_id=".$this->get('product_id'));
        }
        return $this->extraFields;
    }*/

    /*function action_add_field()
    {
        $ef = new \XLite\Model\ExtraField();
        $ef->set('properties', \XLite\Core\Request::getInstance()->getData());
        $ef->create();
    }


    function action_update_fields()
    {
        if (!is_null($this->get('delete')) && !is_null($this->get('delete_fields'))) {
            foreach ((array)$this->get('delete_fields') as $id) {
                $ef = new \XLite\Model\ExtraField($id);
                $ef->delete();
            }
        } elseif (!is_null($this->get('update'))) {
            foreach ((array)$this->get('extra_fields') as $id => $data) {
                $ef = new \XLite\Model\ExtraField($id);
                $ef->set('properties', $data);
                $ef->update();
            }
        }
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

        // update/create extra fields
        $extraFields = (array)$this->get('extra_fields');
        if (!empty($extraFields)) {
            foreach ($extraFields as $id => $value) {
                $fv = new \XLite\Model\FieldValue();
                $found = $fv->find("field_id=$id AND product_id=$this->product_id");
                $fv->set('value', $value);
                if ($found) {
                    $fv->update();
                } else {
                    $fv->set('field_id', $id);
                    $fv->set('product_id', $this->product_id);
                    $fv->create();
                }
            }
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
