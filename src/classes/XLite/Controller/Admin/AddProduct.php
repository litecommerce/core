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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AddProduct extends AAdmin
{
    public $params = array('target', "mode", "product_id");
    public $product = null;

    function init()
    {
        if (!(isset(\XLite\Core\Request::getInstance()->product_id) && !isset(\XLite\Core\Request::getInstance()->action) && isset(\XLite\Core\Request::getInstance()->mode) && \XLite\Core\Request::getInstance()->mode == "notification")) {
            \XLite\Core\Request::getInstance()->product_id = null;
        }

    	parent::init();
    }

    function action_add()
    {
        $product = $this->get('product');
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
        $product->create();

        $this->action_images();
        if ($this->get('valid') == false) {
        	$product->delete();
        	return;
        }

        if (isset($this->category_id)) {
            $category = new \XLite\Model\Category($this->category_id);
            $product->set('category', $category);
        }

        // update/create extra fields
        $extraFields = (array)$this->get('extra_fields');
        if (!empty($extraFields)) {
            foreach ($extraFields as $id => $value) {
                if (strlen($value)) {
                    $fv = new \XLite\Model\FieldValue();
                    $found = $fv->find("field_id=$id AND product_id=".$product->get('product_id'));
                    $fv->set('value', $value);
                    if ($found) {
                        $fv->update();
                    } else {
                        $fv->set('field_id', $id);
                        $fv->set('product_id', $product->get('product_id'));
                        $fv->create();
                    }
                }
            }
        }

        $this->set('mode', "notification");
        $this->set('product_id', $product->get('product_id'));
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

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new \XLite\Model\Product($this->get('product_id'));
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
        $product = new \XLite\Model\Product();

        return !$product->find('clean_url = \'' . $cleanURL . '\'');
    }

}
