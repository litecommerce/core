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

namespace XLite\Module\ProductAdviser\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class RelatedProduct extends \XLite\Model\AModel
{
    public $fields = array
    (
        "product_id" 				=> 0,
        "related_product_id" 		=> 0,
        "order_by" 					=> 0
    );
    public $primaryKey = array('related_product_id', "product_id");
    public $defaultOrder = "order_by";
    public $alias = "related_products";
    public $product = null;

    function filter()
    {
    	if (is_null($this->product)) {
        	$this->product = new \XLite\Model\Product($this->get('product_id'));
        }
    	if (is_object($this->product)) {
        	return $this->product->filter();
        } else {
        	return false;
        }
    }

    function cleanRelations($product_id)
    {
        $objs = $this->findAll("product_id='$product_id' OR related_product_id='$product_id'");
        foreach ($objs as $obj) {
            $obj->delete();
        }
    }
}
