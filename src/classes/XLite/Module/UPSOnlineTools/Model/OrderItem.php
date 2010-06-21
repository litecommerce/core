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

require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
    protected $packItem = null;

    function getDeclaredValue()
    {
        return $this->getComplex('product.declaredValue') * $this->get('amount');
    }

    function getPackItem()
    {
        if (isset($this->packItem)) {
            return $this->packItem;
        }

        $p = $this->get('product');

        // dimension
        $this->packItem = new XLite_Module_UPSOnlineTools_Model_PackItem();
        foreach (array('width', "height", "length") as $field) {
            $this->packItem->setComplex($field, $p->get("ups_".$field));
        }

        // weight
        $weight = UPSOnlineTools_convertWeight($p->get('weight'), $this->config->General->weight_unit, "lbs", 2);
        if ($weight === false) {
            $weight = $this->packItem->getComplex('product.weight');
        }
        $this->packItem->set('weight', $weight);

        // declared_value
        $declared_value = $p->get('declaredValue');

        // misc
        $this->packItem->set('handle_care', $p->get('ups_handle_care'));
        $this->packItem->set('OrderItemId', $this->get('item_id'));
        $this->packItem->set('packaging', $p->get('ups_packaging'));
        $this->packItem->set('declaredValue', $declared_value);
        $this->packItem->set('additional_handling', $p->get('ups_add_handling'));

        return $this->packItem;
    }

}
