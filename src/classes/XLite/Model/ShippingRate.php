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

namespace XLite\Model;

/**
 * ____description____
 * TODO: remove class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ShippingRate extends \XLite\Model\AModel
{
    public $alias = "shipping_rates";
    public $fields = array(
        "shipping_id" => "",
        "min_weight" => 0,
        "max_weight" => 999999,
        "min_total" => 0,
        "max_total" => 999999,
        "min_items" => 0,
        "max_items" => 999999,
        "shipping_zone" => 0,
        "flat" => 0,
        "per_item" => 0,
        "percent" => 0,
        "per_lbs" => 0);
    public $primaryKey = array('shipping_id',"min_weight","min_total","min_items", "shipping_zone");
    public $defaultOrder = "shipping_id, shipping_zone, min_weight, min_total, min_items";

    public $shipping = null;
    public $rate;

    function getShipping()
    {
        if (is_null($this->shipping) && $this->get('shipping_id')) {
            $this->shipping = new \XLite\Model\Shipping($this->get('shipping_id'));
        }
        return $this->shipping;
    }

    function setShipping($shipping)
    {
        $this->shipping = $shipping;
        $this->set('shipping_id', $shipping->get('shipping_id'));
    }

}
