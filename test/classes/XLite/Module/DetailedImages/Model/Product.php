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
 * @subpackage Model
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
class XLite_Module_DetailedImages_Model_Product extends XLite_Model_Product implements XLite_Base_IDecorator
{
    /**
     * Get detailed images list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDetailedImages()
    {
        $image = new XLite_Module_DetailedImages_Model_DetailedImage();

        return $image->findImages($this->get("product_id"));
    }

    /**
     * Get detailed images list count 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDetailedImagesCount()
    {
		return count($this->getDetailedImages());
    }
    
    public function delete()
    {
		foreach ($this->getDetailedImages() as $image) {
			$image->delete();
		}

        parent::delete();
    }

	public function cloneObject()
	{
		$product = parent::cloneObject();

		foreach ($this->getDetailedImages() as $image) {

			$newImage = new XLite_Module_DetailedImages_Model_DetailedImage();
			$newImage->set("alt", $image->get("alt"));
			$newImage->set("enabled", $image->get("enabled"));
			$newImage->set("order_by", $image->get("order_by"));
			$newImage->set("product_id", $product->get("product_id"));
			$newImage->create();

			$obj = $this->get("image");
			if (!method_exists($obj, " copyImageFile")) {

				// use correct image copy routine for LC version lower than 2.2
				$image->deepCopyTo($newImage->get("image_id"));

			} else {
				$obj->copyTo($newImage->get("image_id"));
			}
		}

		return $product;
	}

	/**
	 * Garbage collector
	 * 
	 * @return void
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	function collectGarbage()
	{
		parent::collectGarbage();

		$products_table = $this->db->getTableByAlias('products');
		$detailed_images_table = $this->db->getTableByAlias('images');

		$result = $this->db->getAll(
			'SELECT i.image_id FROM ' . $detailed_images_table . ' as i '
			. 'LEFT OUTER JOIN ' . $products_table .' as p ON i.product_id = p.product_id '
			. 'WHERE p.product_id IS NULL'
		);

		if (is_array($result)) {
			foreach ($result as $info) {
				$di = new XLite_Module_DetailedImages_Model_DetailedImage($info['image_id']);
				$di->delete();
			}
		}
	}

	/**
	 * Check - has product zoom image or not
	 * 
	 * @return boolean
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getHasZoom()
	{
		return !is_null($this->getZoomImage());
	}

	/**
	 * Get zoom image 
	 * 
	 * @return XLite_Module_DetailedImages_Model_DetailedImage
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getZoomImage()
	{
		$result = null;

        foreach ($this->getDetailedImages() as $image) {
            if ($image->get('is_zoom') == 'Y') {
                $result = $image;
                break;
            }
        }

		return $result;
	}
}
