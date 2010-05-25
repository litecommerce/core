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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Product extends XLite_Controller_Admin_Abstract
{
    public $params = array('target', 'product_id', 'page', 'backUrl');
    public $page = "info";
    public $backUrl = "admin.php?target=product_list";

    public $pages = array
    (
    	'info'  => 'Product info',
        'extra_fields' => 'Extra fields',
        'links' => 'HTML links',
    );

    public $pageTemplates = array
    (
    	'info'    => 'product/info.tpl',
        'extra_fields' => 'product/extra_fields_form.tpl',
        'links'   => 'product/links.tpl',
        'default' => 'product/info.tpl'
    );

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new XLite_Model_Product($this->product_id);
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
            $ef = new XLite_Model_ExtraField();
            $this->extraFields = $ef->findAll("product_id=".$this->get('product_id'));
        }
        return $this->extraFields;
    }

    function action_add_field()
    {
        $ef = new XLite_Model_ExtraField();
        $ef->set('properties', XLite_Core_Request::getInstance()->getData());
        $ef->create();
    }


    function action_update_fields()
    {
        if (!is_null($this->get('delete')) && !is_null($this->get('delete_fields'))) {
            foreach ((array)$this->get('delete_fields') as $id) {
                $ef = new XLite_Model_ExtraField($id);
                $ef->delete();
            }
        } elseif (!is_null($this->get('update'))) {
            foreach ((array)$this->get('extra_fields') as $id => $data) {
                $ef = new XLite_Model_ExtraField($id);
                $ef->set('properties', $data);
                $ef->update();
            }
        }
    }
 
    function action_info()
    {
        // update product properties
        $product = new XLite_Model_Product($this->product_id);
        $properties = XLite_Core_Request::getInstance()->getData();

        // Sanitize
        if (isset($properties['clean_url'])) {
            $properties['clean_url'] = $this->sanitizeCleanURL($properties['clean_url']);
            if (
                0 < strlen($properties['clean_url'])
                && !$this->checkCleanURLUnique($properties['clean_url'])
            ) {

                XLite_Core_TopMessage::getInstance()->add(
                    'The Clean URL you specified is already in use. Please specify another Clean URL',
                    XLite_Core_TopMessage::ERROR
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
            $category = new XLite_Model_Category($this->category_id);
            $product->set('category', $category);
        }

        // update/create extra fields
        $extraFields = (array)$this->get('extra_fields');
        if (!empty($extraFields)) {
            foreach ($extraFields as $id => $value) {
                $fv = new XLite_Model_FieldValue();
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
        if ($tn->handleRequest() != XLite_Model_Image::IMAGE_OK && $tn->_shouldProcessUpload) {
        	$this->set('valid', false);
        	$this->set('thumbnail_read_only', true);
        }

        $img = $this->getComplex('product.image');
        if ($img->handleRequest() != XLite_Model_Image::IMAGE_OK && $img->_shouldProcessUpload) {
        	$this->set('valid', false);
        	$this->set('image_read_only', true);
        }
    }

    function action_clone()
    {
        $p_product = new XLite_Model_Product($this->product_id);
        $product = $p_product->cloneObject();
        foreach ($p_product->get('categories') as $category) {
            $product->addCategory($category);
        }
        $product->set('name', $product->get('name') . " (CLONE)");
        $product->update();
        $this->set('returnUrl', 'admin.php?target=product&product_id=' . $product->get('product_id'));
    }

    /**
     * Check - specified clean URL unique or not
     * 
     * @param string $cleanURL Clean URL
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkCleanURLUnique($cleanURL)
    {
        $product = new XLite_Model_Product();

        return !$product->find('clean_url = \'' . $cleanURL . '\'');
    }

}
