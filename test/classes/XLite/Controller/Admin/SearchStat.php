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
class XLite_Controller_Admin_SearchStat extends XLite_Controller_Admin_Stats
{	
    public $params = array("target", "listOrder");	
    public $order = "query";	
    public $orders = array(
            "query" => "query",
            "count" => "count desc",
            "product count" => "product_count desc");

    function getPageTemplate()
    {
        return "searchStat.tpl";
    }

    function getSearchStat()
    {
        if (is_null($this->searchStat)) {
            $searchStat = new XLite_Model_SearchStat();
            $this->searchStat = $searchStat->findAll(
				null,
				isset($this->orders[$this->get("listOrder")]) ? $this->orders[$this->get("listOrder")] : null
			);
        }
        return $this->searchStat;
    }

    function action_cleanup()
    {
        $searchStat = new XLite_Model_SearchStat();
        $searchStat->cleanup(XLite_Core_Request::getInstance()->maxCount);
    }

}
