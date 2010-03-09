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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

// FIXME - to revise

/**
 * XLite_Module_FeaturedProducts_Model_Category 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_FeaturedProducts_Model_Category extends XLite_Model_Category implements XLite_Base_IDecorator
{
	/**
	 * Cached featured products list
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $featuredProducts = null;


    /**
     * Get featured products list; FIXME
     * 
     * @param string $orderby orderby string
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFeaturedProducts($orderby = null)
    {
        if (!isset($this->featuredProducts)) {
            $featuredProduct = new XLite_Module_FeaturedProducts_Model_FeaturedProduct();
            foreach ($featuredProduct->findAll('category_id = \'' . $this->get('category_id') . '\'', $orderby) as $handler) {
                $this->featuredProducts[] = $handler->getProduct();
            }
        }

        return $this->featuredProducts;
    }


    // ---------------

	function addFeaturedProducts($products)
	{
		for ($i=0; $i<count($products); $i++) {
			$fp = new XLite_Module_FeaturedProducts_Model_FeaturedProduct();
			$fp->set("category_id", $this->get("category_id"));
			$fp->set("product_id", $products[$i]->get("product_id"));
			if (!$fp->isExists()) {
				$fp->create();
			}
		}
	}

	function deleteFeaturedProducts($products)
	{
		for ($i=0; $i<count($products); $i++) {
			$fp = new XLite_Module_FeaturedProducts_Model_FeaturedProduct();
			$fp->set("category_id", $this->get("category_id"));
			$fp->set("product_id", $products[$i]->get("product_id"));
			$fp->delete();
		}	
	}

	function delete()
	{
		$this->deleteFeaturedProducts($this->getFeaturedProducts());
		parent::delete();
	}
}

