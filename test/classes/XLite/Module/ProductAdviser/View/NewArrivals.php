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
* NewArrivals widget
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_View_NewArrivals extends XLite_View_SideBarBox
{	
	/**
	 * Available display modes list
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $display_modes = array(
		'menu'   => 'Sidebar box menu',
		'dialog' => 'Dialog box',
	);

	public $productsNumber = 0;

	public $additionalPresent = false;

	/**
	 * Get widget title
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getHead()
	{
		return 'New arrivals';
	}

	/**
	 * Get widget directory
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getDir()
	{
		return 'modules/ProductAdviser/NewArrivals/' . $this->getDisplayMode();
	}

	/**
	 * Initilization
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function initView()
	{
		parent::initView();

		$this->body = $this->getDir() . '/body.tpl';
	}

	public function isVisible()
	{
		$visible = in_array($this->target, array(null, 'main', 'category', 'product', 'cart', 'RecentlyViewed', 'NewArrivals'))
			&& ($this->config->ProductAdviser->number_new_arrivals > 0)
			&& $this->isDisplayed();

		if ($visible) {
			$this->getNewArrivalsProducts();

			$visible = ($this->productsNumber > 0) ? true : false;
		}

		return $visible;
	}

	/**
	 * Define widget parameters
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function defineWidgetParams()
	{
		parent::defineWidgetParams();

		$this->widgetParams += array(
			'use_node'     => new XLite_Model_WidgetParam_Checkbox('use_node', 0, 'Show category-specific new arrivals'),
			'display_mode' => new XLite_Model_WidgetParam_List('display_mode', 'menu', 'Display mode', $this->display_modes)
		);

	}

	/**
	 * Get widget display mode parameter (menu | dialog)
	 * 
	 * @return string
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getDisplayMode()
	{	
		if (isset($this->display_mode)) {
			$displayMode = $this->display_mode;

		} else {
			$displayMode = $this->config->ProductAdviser->new_arrivals_type;
		}

		return $displayMode;
	}

	/**
	 * Get Value of 'Show category-specific new arrivals' option
	 * 
	 * @return bool
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getCategorySpecificArrivals()
	{
		$return = (isset($this->use_node) ? 'Y' : $this->config->ProductAdviser->category_new_arrivals);
		echo $return;
		return $return;
	}

    /**
     * Check if widget could be displayed
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    function isDisplayed()
	{
		$displayMode = $this->getDisplayMode();

		// Display on CMS side
		if (empty($this->display_in)) {
			$return = true;

		// Display as a dialog on target = 'NewArrivals'
		} elseif ('center' == $this->display_in && 'dialog' == $displayMode && 'NewArrivals' == $this->target) {
			$return = true;

		// Display as a menu or dialog except target = 'NewArrivals'
		} elseif (!empty($this->display_in) && $displayMode == $this->display_in && 'NewArrivals' != $this->target) {
			$return = true;

		} else {
			$return = false;
		}

		return $return;
	}

    /**
     * Check if widget should be displayed in dialog box (not in sidebar box)
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    function isDisplayedDialog()
    {
        if ("dialog" == $this->getDisplayMode() && isset($this->dialog)) {
        	return true;
        } else {
        	return isset($this->target) ? true : false;
        }
    }

    function getDialogCategory()
    {
        if (isset($this->target) && ($this->target == "category" || $this->target == "product") && isset($this->category_id) && intval($this->category_id) > 0) {
        	$category = new XLite_Model_Category(intval($this->category_id));
        	return $category;
        }
        return null;
    }

    function getDialogProductId()
    {
        if (isset($this->target) && $this->target == "product" && isset($this->product_id) && intval($this->product_id) > 0) {
        	return intval($this->product_id);
        }
        return null;
    }

    function inCategory(&$product, $category)
    {
		$signCategory = $product->inCategory($category);
		if ($signCategory) {
			return $signCategory;
		} else {
			$subcategories = $category->getSubcategories();
			foreach($subcategories as $cat_idx => $cat) {
				$signCategory |= $this->inCategory($product, $subcategories[$cat_idx]);
				if ($signCategory) {
					return $signCategory;
				}
			}
		}
		return false;
    }

	function recursiveArrivalsSearch($_category)
	{
		if (!$this->isDisplayedDialog() && $this->additionalPresent && count($this->_new_arrival_products) >= $this->config->ProductAdviser->number_new_arrivals) {
			return true;
		}

		$timeLimit = time();
		$timeCondition = $this->config->ProductAdviser->period_new_arrivals * 3600;
		$category_id = $_category->get("category_id");

		$obj = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
		$arrival_table = $this->db->getTableByAlias($obj->alias);
		$links_table = $this->db->getTableByAlias("product_links");

		$fromSQL = array();
		$fromSQL[] = "$links_table AS links";
		$fromSQL[] = "$arrival_table AS arrivals";

		$whereSQL = array();
		$whereSQL[] = "links.product_id=arrivals.product_id";
		$whereSQL[] = "links.category_id='$category_id'";
		$whereSQL[] = "(arrivals.new='Y' OR ((arrivals.updated + '$timeCondition') > '$timeLimit'))";

		$querySQL = "SELECT arrivals.product_id, arrivals.updated FROM ".implode(", ", $fromSQL)." WHERE ".implode(" AND ", $whereSQL)." ORDER BY arrivals.updated DESC";
		$rows = $this->db->getAll($querySQL);

		foreach ((array)$rows as $row) {
			$product_id = $row["product_id"];

			$obj = new XLite_Module_ProductAdviser_Model_ProductNewArrivals($product_id);
			if ($this->checkArrivalCondition($_category, $obj)) {
				if (!$this->isDisplayedDialog() && count($this->_new_arrival_products) >= $this->config->ProductAdviser->number_new_arrivals) {
					$this->additionalPresent = true;
					return true;
				}

				if (!isset($this->_new_arrival_products[$product_id])) {
					$this->_new_arrival_products[$product_id] = new XLite_Model_Product($product_id);
					$this->_new_arrival_products_updated[$product_id] = $row["updated"];
				}
			}
		}

		// get subcategories list
		$category = new XLite_Model_Category();
		$categories = $category->findAll("parent='$category_id'");
		foreach ($categories as $category) {
			if ($this->recursiveArrivalsSearch($category))
				return true;
		}

		return false;
	}

	function checkArrivalCondition($category, $ps)
	{
		$product_id = $this->getDialogProductId();
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

		return $addSign;
	}

    function getNewArrivalsProducts()
    {
		$products = $this->xlite->NewArrivalsProducts;

        if (isset($products)) {
        	$this->productsNumber = count($products);
            return $products;
        }    

		$category = $this->getDialogCategory();
		$product_id = $this->getDialogProductId();


		// recursive search
		if ($this->getCategorySpecificArrivals()) {
			$this->_new_arrival_products = array();
			$this->additionalPresent = false;

			$categories = array();
			if (is_null($category)) {
				// deal with root category
				$obj = new XLite_Model_Category();
				$categories = $obj->findAll("parent='0'");
			} else {
				$categories[] = $category;
			}

			// recursively search new arrival products
			foreach ($categories as $cat) {
				if ($this->recursiveArrivalsSearch($cat))
					break;
			}

			if (is_array($this->_new_arrival_products_updated) && is_array($this->_new_arrival_products)) {
   				arsort($this->_new_arrival_products_updated, SORT_NUMERIC);
                // sort by keys, 'cos values are objects
                krsort($this->_new_arrival_products, SORT_NUMERIC);
			}

			$products = array_values($this->_new_arrival_products);
			$this->productsNumber = count($products);
			$this->xlite->set("NewArrivalsProducts", $products);

			return $products;
		}

        $maxViewed = $this->config->ProductAdviser->number_new_arrivals;
        $products = array();
        $productsStats = array();
        $statsOffset = 0;
        $stats = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
        $timeCondition = $this->config->ProductAdviser->period_new_arrivals * 3600;
		$timeLimit = time();
        $maxSteps = ($this->isDisplayedDialog()) ? 1 : ceil($stats->count("new='Y' OR ((updated + '$timeCondition') > '$timeLimit')") / $maxViewed);

        for ($i=0; $i<$maxSteps; $i++) {
        	$limit = ($this->isDisplayedDialog()) ? null : "$statsOffset, $maxViewed";
        	$productsStats = $stats->findAll("new='Y' OR ((updated + '$timeCondition') > '$timeLimit')", null, null, $limit);
        	foreach ($productsStats as $ps) {
				$product = new XLite_Model_Product($ps->get("product_id"));
				$addSign = $this->checkArrivalCondition($category, $ps);
                if ($addSign) {
                    $product->checkSafetyMode();
                	$products[] = $product;
                	if (count($products) > $maxViewed) {
						if (!$this->isDisplayedDialog()) {
    						$this->additionalPresent = true;
    						unset($products[count($products)-1]);
                			break;
                		}
                	}
                }
        	}

        	if ($this->additionalPresent) {
				break;
        	}

        	if (count($products) > $maxViewed) {
				if (!$this->isDisplayedDialog()) {
					$this->additionalPresent = true;
					unset($products[count($products)-1]);
        			break;
        		}
        	}

            $statsOffset += $maxViewed;
        }

    	$this->productsNumber = count($products);
        $this->xlite->set("NewArrivalsProducts", $products);

        return $products;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
