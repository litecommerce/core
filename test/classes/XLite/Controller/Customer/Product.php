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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Customer_Product extends XLite_Controller_Customer_Abstract
{	
    public $params = array("target", "product_id", "category_id");

	function handleRequest()
	{
		$result = null;

		if ($this->isComplex('product.exists')) {
			$result = parent::handleRequest();

		} elseif ($this->isComplex('category.exists') && $this->isComplex('category.enabled')) {
			$result = $this->redirect($this->buildURL('category', '', array('category_id' => $this->getCategory()->get('category_id'))));

		} else {
			$result = $this->redirect($this->buildURL('main'));
		}

		return $result;
	}

    function action_buynow()
    {
		$this->set('returnUrl', $this->buildURL('cart', 'add', array('product_id' => $this->product_id, 'category_id' => $this->category_id)));
    }

    function getLocationPath()
    {
        if($this->config->General->add_on_mode){
            return array(
				$this->getComplex('product.name') => $this->get("url")
			);
        }

        $result = array();
        $path = $this->getCategory()->getPath();
        if (!is_null($path)) {
            foreach ($path as $category) {
                $name = $category->get("name");
				if ($name) {
	                while (isset($result[$name])) {
    	            	$name .= " ";
        	        }
            	    $result[$name] = $this->buildURL('category', '', array('category_id' => $category->get('category_id')));
				}
            }
        }    

        $name = $this->getProduct()->get('name');
        while (isset($result[$name])) {
        	$name .= " ";
        }

        $result[$name] = $this->getUrl();

        return $result;
    }

	/**
	 * Get category 
	 * 
	 * @return XLIte_Model_Category
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getCategory()
	{
		if (is_null($this->category_id)) {
			$list = $this->getProduct()->getCategories();
			if ($list) {
				$category = array_shift($list);
				$this->category_id = $category->get('category_id');

			} else {
				$this->category_id = 0;
			}
		}

		return parent::getCategory();
	}

	function getTitle()
	{
		$metaTitle = $this->getProduct()->get('.meta_title');

		return $metaTitle ? $metaTitle : $this->getProduct()->get('name');
	}
	
    function getDescription()
    {
        $description = $this->getProduct()->get('description');

		return $description ? $description : $this->getProduct()->get('brief_description');
    }

	function getMetaDescription()
	{
		$metaDesc = $this->getProduct()->get('meta_desc');

		return $metaDesc ? $metaDesc : $this->getDescription();
	}

    function getKeywords()
    {
		return $this->getProduct()->get('meta_tags');
    }

    /**
     * Check - available product for sale or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAvailableForSale()
    {
        return true;
    }

    /**
     * Define page type parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function definePageTypeParams()
    {
		parent::definePageTypeParams();

        $this->pageTypeParams['product_id'] = new XLite_Model_WidgetParam_ObjectId_Product('Product Id', 0);
    }

    /**
     * Check - page instance visible or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPageInstanceVisible()
    {
		$product = new XLite_Model_Product($this->product_id);

        return $product->isPersistent;
    }

    /**
     * Get page instance data (name and URL)
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceData()
    {
		$product = new XLite_Model_Product($this->product_id);

		$this->target = 'product';

        return array(
			$product->get('name'),
			$this->getUrl(),
		);
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
