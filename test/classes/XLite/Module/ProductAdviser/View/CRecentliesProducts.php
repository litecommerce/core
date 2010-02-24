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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* CRecentliesProducts description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_View_CRecentliesProducts extends XLite_View_SideBarBox
{	
	/**
	 * The number of products displayed by widget 
	 * 
	 * @var    integer
	 * @access public
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	public $productsNumber = 0;

	/**
	 * Flag that means if it is need to display link 'See more...'
	 * 
	 * @var    mixed
	 * @access public
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	public $additionalPresent = false;

    /**
     * Check if widget is visible 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
	{
		$return = false;

		if ( in_array($this->target, array(null, 'main', 'category', 'product', 'cart'))
			&& ($this->config->ProductAdviser->number_recently_viewed > 0) ) {
			$this->getRecentliesProducts();
			$return = ($this->productsNumber > 0) ? true : false;
		}

        return $return;
	}

	/**
	 * Get widget title 
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getHead()
	{
		return 'Recently viewed';
	}

	/**
	 * Get widget's template directory 
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getDir()
	{
		return 'modules/ProductAdviser/RecentlyViewed';
	}

    function getDialogProductId()
    {
        if (isset($this->target) && $this->target == "product" && isset($this->product_id) && intval($this->product_id) > 0) {
        	return intval($this->product_id);
        }
        return null;
    }

    function getRecentliesProducts()
    {
    	$products = $this->xlite->get("RecentliesProducts");
        if (isset($products)) {
        	$this->productsNumber = count($products);
            return $products;
        }    

		$product_id = $this->getDialogProductId();

        $maxViewed = $this->config->ProductAdviser->number_recently_viewed;
        $products = array();
        $productsStats = array();
        $statsOffset = 0;
        $stats = new XLite_Module_ProductAdviser_Model_ProductRecentlyViewed();
        $total = $stats->count("sid='".$this->session->getID()."'");
        $maxSteps = ceil($total / $maxViewed);

        for ($i=0; $i<$maxSteps; $i++) {
        	$limit = "$statsOffset, $maxViewed";
        	$productsStats = $stats->findAll("sid='".$this->session->getID()."'", null, null, $limit);
        	foreach ($productsStats as $ps) {
        		$product = new XLite_Model_Product($ps->get("product_id"));
        		$addSign = (isset($product_id) && $product->get("product_id") == $product_id) ? false : true;
				if ($addSign) {
        			$addSign &= $product->filter();
        			$addSign &= $product->is("available");
        			// additional check
        			if (!$product->is("available") || (isset($product->properties) && is_array($product->properties) && !isset($product->properties["enabled"]))) {
        				// removing link to non-existing product
        				if (intval($ps->get("product_id")) > 0) {
        					$ps->delete();
        				}
        				$addSign &= false;
        			}
				}
                if ($addSign) {
                    $product->checkSafetyMode();
                	$products[] = $product;
                	if (count($products) > $maxViewed) {
    					$this->additionalPresent = true;
						unset($products[count($products)-1]);
                		break;
                	}
                }
        	}

        	if ($this->additionalPresent) {
				break;
        	}

        	if (count($products) > $maxViewed) {
    			$this->additionalPresent = true;
				unset($products[count($products)-1]);
        		break;
        	}

            $statsOffset += $maxViewed;
        }

    	$this->productsNumber = count($products);
        $this->xlite->set("RecentliesProducts", $products);

        return $products;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
