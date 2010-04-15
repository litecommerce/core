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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

define('QUERY_SEARCH_STAT_LENGTH', 64);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_SearchStat extends XLite_Model_Abstract
{	
    public $fields = array(
        "query" => "",
        "product_count" => "0",
        "count" => "0");	
    public $alias = "search_stat";	
    public $primaryKey = array("query");
    
    function add($query, $foundCount) {
    	$query = strtolower($query);
    	if (strlen($query) > QUERY_SEARCH_STAT_LENGTH) {
    		$query = substr($query, 0, QUERY_SEARCH_STAT_LENGTH);
    	}
        $this->set("query", $query);
        $this->set("product_count", $foundCount);
        if ($this->is("exists")) {
            $this->set("count", $this->get("count")+1);
            $this->update();
        } else {
            $this->set("count", 1);
            $this->create();
        }
    }
    /**
    * Cleanup queries that were requested less or equal than $maxCount
    */
    function cleanup($maxCount) {
        $table = $this->db->getTableByAlias($this->alias);
        $this->db->query("DELETE FROM $table WHERE count<=$maxCount");
    }

    function set($name, $val)
    {
        if ($name=="query" && $val === "") {
            parent::set($name, " "); // replace empty strings with space
        } else {
            parent::set($name, $val);
        }
    }
}
