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

namespace XLite\Module\CDev\ProductAdviser\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ProductRecentlyViewed extends \XLite\Model\AModel
{
    public $fields = array
    (
        "sid"			=> "",
        "product_id" 	=> 0,
        "views_number"	=> 0,
        "last_viewed"	=> 0,
    );
    public $primaryKey = array('sid', "product_id");
    public $alias = "products_recently_viewed";
    public $defaultOrder = "views_number DESC, last_viewed DESC";
    public $product = null;

    public function __construct()
    {
        parent::__construct();

        $this->collectGarbage();
    }

    function collectGarbage()
    {
        if ($this->xlite->get('RecentlyViewedCleaned')) {
            return;
        }
        $t1 = $this->db->getTableByAlias($this->alias);
        $t2 = $this->db->getTableByAlias('sessions');
        $sql = "SELECT $t1.sid FROM $t1 LEFT JOIN $t2 ON $t1.sid=$t2.id WHERE $t2.id IS NULL";
        $expired = $this->db->getAll($sql);
        if (is_array($expired) && count($expired) > 0) {
        	$hash = array();
        	foreach ($expired as $sid) {
        		$sid = $sid['sid'];
        		if (!isset($hash[$sid])) {
        			$hash[$sid] = true;
                    $sql = "DELETE FROM $t1 WHERE sid='$sid'";
        			$this->db->query($sql);
        		}
        	}
        }

        $this->xlite->set('RecentlyViewedCleaned', true);
    }

    function cleanCurrentGarbage()
    {
        if ($this->xlite->get('CurrentRecentlyViewedCleaned')) {
            return;
        }
        $t1 = $this->db->getTableByAlias($this->alias);
        $sid = $this->session->getID();
        $sql = "DELETE FROM $t1 WHERE sid='$sid'";
        $this->db->query($sql);

        $this->xlite->set('CurrentRecentlyViewedCleaned', true);
    }

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new \XLite\Model\Product($this->get('product_id'));
        }
        return $this->product;
    }

    function cleanRelations($product_id)
    {
        $objs = $this->findAll("product_id='$product_id'");
        foreach ($objs as $obj) {
            $obj->delete();
        }
    }
}
