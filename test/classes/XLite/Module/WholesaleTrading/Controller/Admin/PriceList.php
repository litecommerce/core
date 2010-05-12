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
 * @subpackage Controller
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
class XLite_Module_WholesaleTrading_Controller_Admin_PriceList extends XLite_Controller_Admin_Abstract
{
    public $params = array('target', 'mode', 'category', 'include_subcategories', 'membership');
    
    protected $_priceList = array();
    protected $wholesale_pricing = array();

    /**
     * getRegularTemplate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRegularTemplate()
    {
        if ('print' == XLite_Core_Request::getInstance()->mode) {
            $return = "modules/WholesaleTrading/pl_print.tpl";

        } else {
            $return = parent::getRegularTemplate();
        }

        return $return;
    }

    function fillPriceList($category_id, $include_subcategories)
    {
        if ($category_id == "") {
            $category_id = 0;
            $include_subcategories = true;
        }
        $cat = new XLite_Model_Category($category_id);
        $this->_priceList[] = $cat;
        
        if ($include_subcategories == true) {
            foreach ($cat->get('subcategories') as $sc) {
                $this->fillPriceList($sc->get('category_id'), true);
            }
        }
    }
    
    function getPriceList()
    {
        $this->fillPriceList(isset($_REQUEST['category']) ? $_REQUEST['category'] : '', isset($_REQUEST['include_subcategories']));

        return $this->_priceList;
    }

    function getWholesalePricing($product_id)
    {
        if (!isset($this->wholesale_pricing[$product_id])) {
            $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
            $where = "product_id=" . $product_id;
            if ($_REQUEST["membership"] != 'all') {
                $where .= " and (membership='all' or membership='" . $_REQUEST["membership"] . "')";
            }
            $this->wholesale_pricing[$product_id] = $wp->findAll($where);
        }
        return $this->wholesale_pricing[$product_id];
    }

    function getWholesaleCount($product_id)
    {
        return count($this->getWholesalePricing($product_id));
    }
}
