<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_Module_ProductAdviser_View_RecentlyViewed 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Module_ProductAdviser_View_RecentlyViewed extends XLite_View_SideBarBox
{
	/**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('main', 'category', 'product', 'cart');


	/**
     * Get widge title
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Recently viewed';
    }

    /**
     * Get widget templates directory
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/ProductAdviser/RecentlyViewed';
    }

    /**
     * Check if there are product to display
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function checkProductsToDisplay()
    {
        return 0 < $this->config->ProductAdviser->number_recently_viewed && $this->getRecentliesProducts();
    }

	/**
	 * Return current product Id (if presented)
     * TODO - check if it's really needed
	 * 
	 * @return mixed
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getDialogProductId()
    {
		return ('product' == XLite_Core_Request::getInstance()->target) ? XLite_Core_Request::getInstance()->product_id : null;
    }


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
		return parent::isVisible() && $this->checkProductsToDisplay();
    }


     // TODO, FIXME - all of the above routines must be reviewed and refactored

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

	// FIXME - must be refactored
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

