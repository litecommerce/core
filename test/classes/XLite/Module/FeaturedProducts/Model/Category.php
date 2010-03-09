<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package Module_FeaturedProducts
* @access public
* @version $Id$
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
     * Get featured products list
     * 
     * @param string $orderby Order by string
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getFeaturedProducts($orderby = null)
    {
        if (is_null($this->featuredProducts)) {
            $fp = new XLite_Module_FeaturedProducts_Model_FeaturedProduct();
            $this->featuredProducts = $fp->findAll('category_id = \'' . $this->get('category_id') . '\'', $orderby);

			foreach ($this->featuredProducts as $i => $product) {
                $categories = $product->product->get('categories');
                if (!empty($categories)) {
                	$this->featuredProducts[$i]->product->category_id = $categories[0]->get('category_id');
                }    
            }
        }

        return $this->featuredProducts;
    }

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

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
