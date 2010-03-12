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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * New arrivals widget
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_Module_ProductAdviser_View_NewArrivals extends XLite_View_ProductsList
{
     /*
     * Parameter specifies that widget is displayed as a page content
     */
   
    const PARAM_PAGE_CONTENT = 'pageContent';

    /**
     * Parameter specifies if new arrivals must be prepared for current category or for all catalog
     */

    const PARAM_USE_NODE = 'useNode';

	/**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('main', 'category', 'product', 'cart', 'recently_viewed', 'new_arrivals');

	/**
	 * Flag that means if it is need to display link 'See more...'
	 * 
	 * @var    bool
	 * @access public
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
    public $additionalPresent = false;

	/**
	 * Get widget title
	 * 
	 * @return string
	 * @access public
	 * @since  3.0.0
	 */
	protected function getHead()
	{
		return 'New arrivals';
	}

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        // pageContent - is a service parameter for displaying widget as a page content
        $this->widgetParams += array(
            self::PARAM_PAGE_CONTENT => new XLite_Model_WidgetParam_Checkbox(
                'Widget is displayed as page content', false, false
            ),
            self::PARAM_USE_NODE     => new XLite_Model_WidgetParam_Checkbox(
                'Show category-specific new arrivals', ('Y' == $this->config->ProductAdviser->category_new_arrivals), true
            ),
        );

        $this->widgetParams[self::PARAM_WIDGET_TYPE]->setValue($this->config->ProductAdviser->new_arrivals_type);
        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue(self::DISPLAY_MODE_LIST);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue(true);
        $this->widgetParams[self::PARAM_SIDEBAR_MAX_ITEMS]->setValue($this->config->ProductAdviser->number_new_arrivals);

        $this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SORT_BY]->setValue('Name');
        $this->widgetParams[self::PARAM_SORT_ORDER]->setValue('asc');

        $this->widgetParams[self::PARAM_PAGE_CONTENT]->setValue(false);

        foreach ($this->getHiddenParamsList() as $param) {
            $this->widgetParams[$param]->setVisibility(false);
        }
    }

    /**
     * Get the list of parameters that are hidden on the settings page 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getHiddenParamsList()
    {
        return array(
            self::PARAM_SHOW_DISPLAY_MODE_SELECTOR,
            self::PARAM_SHOW_SORT_BY_SELECTOR,
            self::PARAM_SORT_BY,
            self::PARAM_SORT_ORDER,
            self::PARAM_SHOW_ALL_ITEMS_PER_PAGE,
            self::PARAM_PAGE_CONTENT
        );
    }

    /**
     * Check if there are product to display 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkProductsToDisplay()
    {
        return 0 < $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS) && $this->getNewArrivalsProducts();
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    public function isVisible()
    {
        $visibility = parent::isVisible() && $this->checkProductsToDisplay();

        if ($visibility && !$this->getParam(self::PARAM_PAGE_CONTENT)) {

            // Do not display widget on page with target='new_arrivals' if it's not page content widget
            if ('new_arrivals' == XLite_Core_Request::getInstance()->target) {
                $visibility = false;

            // Do not display widget in standalone mode if it is passed widgetType argument different from setting in the config
            } elseif (!$this->getParam(self::PARAM_IS_EXPORTED) && $this->getParam(self::PARAM_WIDGET_TYPE) != $this->config->ProductAdviser->new_arrivals_type) {
                $visibility = false;
            }
        }

        if ($visibility) {
            if (self::WIDGET_TYPE_CENTER == $this->getParam(self::PARAM_WIDGET_TYPE) && 'new_arrivals' == XLite_Core_Request::getInstance()->target) {
                // Display pager if widget is a page content widget
                $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(false);
            }
        }

        return $visibility;
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
		return $this->getParam(self::PARAM_USE_NODE);
	}

    /**
     * Return products list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData()
    {
        return $this->getNewArrivalsProducts();
    }

    /**
     * Check status of 'More...' link for sidebar list
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isShowMoreLink()
    {
        return $this->additionalPresent;
    }

    /**
     * Get 'More...' link URL for sidebar list
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkURL()
    {
        return $this->buildURL('new_arrivals');
    }

    /**
     * Get 'More...' link text for sidebar list
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkText()
    {
        return 'All new arrivals...';
    }

    /**
     * Get current category
     * 
     * @return XLite_Model_Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDialogCategory()
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDialogProductId()
    {
        if ('product' == $this->target && intval($this->product_id) > 0) {
        	return intval($this->product_id);
        }
        return null;
    }

    /**
     * Check if widget displayed as a page content 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isContentDialog()
    {
        return ($this->getParam(self::PARAM_PAGE_CONTENT) && 'new_arrivals' == XLite_Core_Request::getInstance()->target);
    }

    /**
     * Check if product is in the category or its subcategories
     * 
     * @param mixed $product  XLite_Model_Product object
     * @param mixed $category XLite_Model_Category object
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function inCategory(&$product, $category)
    {
        $return = false;

        $signCategory = $product->inCategory($category);

		if ($signCategory) {
            $return = true;

		} else {
			$subcategories = $category->getSubcategories();
			foreach($subcategories as $cat_idx => $cat) {
				$signCategory |= $this->inCategory($product, $subcategories[$cat_idx]);
				if ($signCategory) {
                    $return = true;
                    break;
				}
			}
		}
		return $return;
    }

    /**
     * Recursive search of products in current category and its subcategories
     * 
     * @param mixed $_category XLite_Model_Category object
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
	protected function recursiveArrivalsSearch($_category)
    {
		if ($this->isContentDialog() && $this->additionalPresent && count($this->_new_arrival_products) >= $this->getParam(self::PARAM_SHOW_ALL_ITEMS_PER_PAGE)) {
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

    /**
     * Check if product is available
     * 
     * @param mixed $category XLite_Model_Category object
     * @param mixed $ps       XLite_Model_Product object
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
	protected function checkArrivalCondition($category, $ps)
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

    /**
     * Get the list of new arrival products
     * 
     * @return array of XLite_Model_Product objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNewArrivalsProducts()
    {
		$products = $this->xlite->NewArrivalsProducts;

        if (isset($products)) {
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

