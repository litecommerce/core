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
 * @since      3.0.0
 */

/**
 * XLite_Controller_Admin_Categories 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Controller_Admin_Categories extends XLite_Controller_Admin_Abstract
{
    public $params = array('target', 'category_id');
    
    protected function getCategories()
    {
		return self::getCategory()->getSubcategories();
    }

    public function action_update()
    {
        $order_by = isset(XLite_Core_Request::getInstance()->category_order) 
			? XLite_Core_Request::getInstance()->category_order 
			: array();

        foreach ($order_by as $category_id => $category_order) {
            $category = new XLite_Model_Category($category_id);
            $category->set("order_by", $category_order);
            $category->update();
        }
    }

    public function action_delete()
    {
        foreach ($this->getCategories() as $category) {
            $category->delete();
        }
    }
}

