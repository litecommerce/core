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
class XLite_Controller_Admin_AddProduct extends XLite_Controller_Admin_Abstract
{	
    public $params = array("target", "mode", "product_id");	
    public $product = null;

    function init()
    {
		if (!(isset(XLite_Core_Request::getInstance()->product_id) && !isset(XLite_Core_Request::getInstance()->action) && isset(XLite_Core_Request::getInstance()->mode) && XLite_Core_Request::getInstance()->mode == "notification")) {
			XLite_Core_Request::getInstance()->product_id = null;
		}

    	parent::init();
    }

    function action_add()
    {
        $product = $this->get("product");
        $properties = XLite_Core_Request::getInstance()->getData();

        // Sanitize
        if (isset($properties['clean_url'])) {
            $properties['clean_url'] = $this->sanitizeCleanURL($properties['clean_url']);
            if (!$this->checkCleanURLUnique($properties['clean_url'])) {

                // TODO - add top message
                $this->set('valid', false);
                return;
            }
        }

        $product->set("properties", $properties);
        $product->create();

        $this->action_images();
        if ($this->get("valid") == false) {
        	$product->delete();
        	return;
        }

        if (isset($this->category_id)) {
            $category = new XLite_Model_Category($this->category_id);
            $product->set("category", $category);
        }

        // update/create extra fields
        $extraFields = (array)$this->get("extra_fields");
        if (!empty($extraFields)) {
            foreach ($extraFields as $id => $value) {
                if (strlen($value)) {
                    $fv = new XLite_Model_FieldValue();
                    $found = $fv->find("field_id=$id AND product_id=".$product->get("product_id"));
                    $fv->set("value", $value);
                    if ($found) {
                        $fv->update(); 
                    } else {
                        $fv->set("field_id", $id);
                        $fv->set("product_id", $product->get("product_id"));
                        $fv->create();
                    }
                }
            }
        }

        $this->set("mode", "notification");
        $this->set("product_id", $product->get("product_id"));
    }

    function action_images()
    {
        $tn = $this->getComplex('product.thumbnail');
        if ($tn->handleRequest() != XLite_Model_Image::IMAGE_OK && $tn->_shouldProcessUpload) {
        	$this->set("valid", false);
        	$this->set("thumbnail_read_only", true);
        }

        $img = $this->getComplex('product.image'); 
        if ($img->handleRequest() != XLite_Model_Image::IMAGE_OK && $img->_shouldProcessUpload) {
        	$this->set("valid", false);
        	$this->set("image_read_only", true);
        }
    }

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new XLite_Model_Product($this->get("product_id"));
        }
        return $this->product;
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
