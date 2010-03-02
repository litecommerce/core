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
 * XLite_Module_ProductAdviser_View_NewArrivals 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Module_ProductAdviser_View_NewArrivals extends XLite_View_SideBarBox
{
	/**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('main', 'category', 'product', 'cart', 'recently_viewed', 'new_arrivals');

	/**
	 * Available display modes list
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $displayModes = array(
		'menu'   => 'Sidebar box menu',
		'dialog' => 'Dialog box',
	);


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
		return 'modules/ProductAdviser/NewArrivals/' . $this->attributes['displayMode'];
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
		return $this->config->ProductAdviser->new_arrivals_type;
    }

    /**
     * Check passed attributes againest current display mode
     * 
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function checkDisplayMode()
    {
        return $this->attributes[self::IS_EXPORTED]
            || $this->isContentDialog()
            || ( ($this->getDisplayMode() == $this->attributes['displayMode'])
                && !('menu' == $this->attributes['displayMode'] && 'new_arrivals' == XLite_Core_Request::getInstance()->target) );
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
        return 0 < $this->config->ProductAdviser->number_new_arrivals && $this->getNewArrivalsProducts();
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
			'useNode'     => new XLite_Model_WidgetParam_Checkbox('Show category-specific new arrivals', 0),
			'displayMode' => new XLite_Model_WidgetParam_List('Display mode', $this->getDisplayMode(), $this->displayModes),
		);
	}

    /**
     * Check if widget must be displayed on 'new_arrivals' target page
     * 
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function isContentDialog()
    {
        return ( 'new_arrivals' == XLite_Core_Request::getInstance()->target
            && ('dialog' == $this->attributes['displayMode'] || $this->attributes[self::IS_EXPORTED]) );
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->checkDisplayMode() && $this->checkProductsToDisplay();
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
        if ($this->attributes[self::IS_EXPORTED]) {
            $return = (isset($this->attributes['useNode']) && true == $this->attributes['useNode']);

        } else {
            $return = ('Y' == $this->config->ProductAdviser->category_new_arrivals);
        }

		return $return;
	}

    /**
     * Get current category
     * 
     * @return XLite_Model_Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getDialogCategory()
    {
        $category = null;

        if (('category' == $this->target || 'product' == $this->target) && intval($this->category_id) > 0) {
            $category = new XLite_Model_Category(intval($this->category_id));
        }

        return $category;
    }

    /**
     * Get current product Id
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getDialogProductId()
    {
        if ('product' == $this->target && intval($this->product_id) > 0) {
        	return intval($this->product_id);
        }
        return null;
    }


    // TODO, FIXME - all of the above routines must be reviewed and refactored


	public $productsNumber = 0;

    public $additionalPresent = false;


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
		if ($this->isContentDialog() && $this->additionalPresent && count($this->_new_arrival_products) >= $this->config->ProductAdviser->number_new_arrivals) {
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

		foreach ($rows as $row) {
			$product_id = $row["product_id"];

			$obj = new XLite_Module_ProductAdviser_Model_ProductNewArrivals($product_id);
			if ($this->checkArrivalCondition($_category, $obj)) {
				if (!$this->isContentDialog() && count($this->_new_arrival_products) >= $this->config->ProductAdviser->number_new_arrivals) {
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
            $this->additionalPresent = $this->xlite->NewArrivalsAdditionalPresent;
            return $products;
        }    

		$category = $this->getDialogCategory();
		$product_id = $this->getDialogProductId();

		// Recursive search
        if ($this->getCategorySpecificArrivals()) {

            $this->_new_arrival_products = array();
            $this->_new_arrival_products_updated = array();
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
            $this->xlite->set("NewArrivalsAdditionalPresent", $this->additionalPresent);

			return $products;
		}

        $maxViewed = $this->config->ProductAdviser->number_new_arrivals;
        $products = array();
        $productsStats = array();
        $statsOffset = 0;
        $stats = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
        $timeCondition = $this->config->ProductAdviser->period_new_arrivals * 3600;
		$timeLimit = time();
        $maxSteps = ($this->isContentDialog()) ? 1 : ceil($stats->count("new='Y' OR ((updated + '$timeCondition') > '$timeLimit')") / $maxViewed);

        for ($i=0; $i<$maxSteps; $i++) {
        	$limit = ($this->isContentDialog()) ? null : "$statsOffset, $maxViewed";
            $productsStats = $stats->findAll("new='Y' OR ((updated + '$timeCondition') > '$timeLimit')", null, null, $limit);
        	foreach ($productsStats as $ps) {
				$product = new XLite_Model_Product($ps->get("product_id"));
                $addSign = $this->checkArrivalCondition($category, $ps);
                if ($addSign) {
                    $product->checkSafetyMode();
                    $products[] = $product;
                	if (count($products) == $maxViewed) {
						if (!$this->isContentDialog()) {
    						$this->additionalPresent = true;
                			break;
                		}
                	}
                }
            }

        	if ($this->additionalPresent) {
				break;
        	}

        	if (count($products) == $maxViewed) {
				if (!$this->isContentDialog()) {
					$this->additionalPresent = true;
        			break;
        		}
        	}

            $statsOffset += $maxViewed;
        }

    	$this->productsNumber = count($products);
        $this->xlite->set("NewArrivalsProducts", $products);
        $this->xlite->set("NewArrivalsAdditionalPresent", $this->additionalPresent);

        return $products;
	}
}

