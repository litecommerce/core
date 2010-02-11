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
* Returns the category for the bestsellers list.
*
* @package Module_Bestsellers
* @access public
* @version $Id$
*/
class XLite_Module_Bestsellers_View_Bestsellers extends XLite_View_SideBarBox
{
	/**
	 * Title
	 * 
	 * @var    string
	 * @access protected
	 * @since  1.0.0
	 */
	protected $head = 'Bestsellers';

	/**
	 * Directory contains sidebar content
	 * 
	 * @var    string
	 * @access protected
	 * @since  1.0.0
	 */
	protected $dir = 'modules/Bestsellers/menu';

    /**
     * Bestsellers list (cache)
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $bestsellers = null;	

    /**
     * Subcategories ids 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $ids = array();

    /**
     * Category root id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0 EE
     */
    protected $rootid = 0;

	/**
	 * Use current category 
	 * 
	 * @var    boolean
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $use_node = true;

    /**
     * Check - visible widget or not 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    function isVisible()
    {
        return $this->config->Bestsellers->bestsellers_menu && $this->getBestsellers();
    }

    /**
     * Get bestsellers list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBestsellers()
    {
		if (is_null($this->bestsellers)) {

	        $category = $this->get("category");
    	    $cat_id = $category->get("category_id");

	        $bestsellersCategories = $this->xlite->get("BestsellersCategories");
    	    if (!(isset($bestsellersCategories) && is_array($bestsellersCategories))) {
        		$bestsellersCategories = array();
	        }

    	    if (isset($bestsellersCategories[$cat_id])) {
        		$this->bestsellers = $bestsellersCategories[$cat_id];

	        } else {
				$this->calculateBestsellers($cat_id, $category);

           		$bestsellersCategories[$cat_id] = $this->bestsellers;
            	$this->xlite->set("BestsellersCategories", $bestsellersCategories);
			}
		}

		return $this->bestsellers;
	}

	/**
	 * Calculate bestsellers list
	 * 
	 * @param integer $cat_id Category id
	 *  
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function calculateBestsellers($cat_id, $category)
	{
		$this->bestsellers = array();

        $products = "";
        if ($cat_id != $category->getComplex('topCategory.category_id')) {

            // get all subcategories ID
			$this->ids = array();
            $this->getSubcategories($category);

            if (!empty($this->ids)) {
	            $categories = join(',', $this->ids);
				$table = $this->db->getTableByAlias("product_links");
        	    $sql = "SELECT product_id FROM $table WHERE category_id IN ($categories)";
            
	            $ids = $category->db->getAll($sql);    
    	        foreach ($ids as $id) {
        	        $array[] = $id["product_id"];        
            	}

	            if (!empty($array)) {
		            $products = join(',', $array);
    		        $products = "AND items.product_id IN ($products)";

				} else {
					$products = false;
				}

			} else {
				$products = false;
			}
        }

        // build SQL query to select bestsellers
		if (false !== $products) {
	        $order_items_table = $this->db->getTableByAlias("order_items");
    	    $orders_table = $this->db->getTableByAlias("orders");
        	$products_table = $this->db->getTableByAlias("products");
		
			$limit = 0;
    	    if (
				!is_null($this->getComplex('config.Bestsellers.number_of_bestsellers')) && 
	            is_numeric($this->getComplex('config.Bestsellers.number_of_bestsellers'))
			) {
	            $limit = $this->getComplex('config.Bestsellers.number_of_bestsellers');
    	    } else {
	        	$limit = 5;
    	    }

			$limit = max($limit, 0);
	        $limitGrace = $limit * 10;

	        $sql =<<<EOT
		   	    SELECT items.product_id, sum(items.amount) as amount
		        FROM $order_items_table items
		        LEFT OUTER JOIN $orders_table orders ON items.order_id=orders.order_id
		        LEFT OUTER JOIN $products_table products ON items.product_id=products.product_id
		        WHERE (orders.status='P' OR orders.status='C') AND products.enabled=1
        		$products
		        GROUP BY items.product_id
        		ORDER BY amount DESC
		        LIMIT $limitGrace
EOT;

	        // fill bestsellers array with product instances
    	    $best = $category->db->getAll($sql);
	        foreach ($best as $p) {
    	        $product = new XLite_Model_Product($p["product_id"]);
	            $categories = $product->get("categories");
    	        if (!empty($categories) && $product->filter()) {
        	        $product->category_id = $categories[0]->get("category_id");
            	    $this->bestsellers[] = $product;
                	if (count($this->bestsellers) == $limit) {
	                	break;
    	            }
        	    }
	        }
		}
    }

    /**
     * Get category 
     * 
     * @return XLite_Model_Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategory()
    {
        $category = new XLite_Model_Category();

        if ($this->use_node && isset($_REQUEST["category_id"])) {
            $category = new XLite_Model_Category($_REQUEST["category_id"]); 

        } elseif (0 < $this->rootid) {
			$category = new XLite_Model_Category($this->rootid);

		} else {
            $category = $category->get("topCategory");
        }
        return $category;
    }

    /**
     * Get subcategories 
     * 
     * @param mixed $category ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubcategories($category)
    {
        $this->ids[] = $category->get("category_id");

        $categories = $category->getSubcategories();

        for ($i = 0; $i < count($categories); $i++) {
            $this->getSubcategories($categories[$i]);
        }
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            new XLite_Model_WidgetParam_Checkbox('use_node', 1, 'Use current category id'),
            new XLite_Model_WidgetParam_String('rootid', 0, 'Category root Id'),
        );
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attributes attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attributes)
    {
        $errors = parent::validateAttributes($attributes);

		// Category root id
		if (!isset($attributes['rootid']) || !is_numeric($attributes['rootid'])) {
			$errors['rootid'] = 'Category Id is not numeric!';
		} else {
			$attributes['rootid'] = intval($attributes['rootid']);
		}

        if (!$errors && 0 > $attributes['rootid']) {
            $errors['rootid'] = 'Category Id must be positive integer!';
		}

		if (!$errors && !$attributes['use_node']) {
			$category = new XLite_Model_Category($attributes['rootid']);

			if (!$category->isPersistent) {
				$errors['rootid'] = 'Category with category Id #' . $attributes['rootid'] . ' can not found!';
			}
		}

		return $errors;
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
