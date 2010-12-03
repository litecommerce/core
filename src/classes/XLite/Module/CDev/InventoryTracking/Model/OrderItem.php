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

namespace XLite\Module\CDev\InventoryTracking\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderItem extends \XLite\Model\OrderItem implements \XLite\Base\IDecorator
{
    public function __construct() 
    {
        parent::__construct();
        $this->fields['product_sku'] = "";
    }

    function set($name, $value) 
    {
        $result = parent::set($name, $value);
        if (!$this->xlite->get('ProductOptionsEnabled')) return $result;
        if ($name == "options") {
            $this->assignProductSku();
        }
        return $result;
    }

    function assignProductSku() 
    {
        $this->set('product_sku', parent::get('sku'));
        if (!$this->xlite->get('ProductOptionsEnabled')) return false;
        if (!$this->getComplex('product.tracking')) return false;

        $options = (array) $this->get('productOptions');
        if (empty($options)) return false;

        $key = $this->get('key');
        $inventory = new \XLite\Module\CDev\InventoryTracking\Model\Inventory();
        $inventories = (array) $inventory->findAll("inventory_id LIKE '".$this->get('product_id')."|%'", "order_by");
        foreach ($inventories as $i) {
            if ($i->keyMatch($key)) {
                $sku = $i->get('inventory_sku');
                if (!empty($sku)) {
                    $this->set('product_sku', $sku);
                    return true;
                }
            }
        }
        return false;
    }

    function get($name) 
    {
        $value = parent::get($name);
        if ($name == 'sku') {
            $sku = parent::get('product_sku');
            if (!empty($sku)) $value = $sku;
        }
        return $value;
    }
}
