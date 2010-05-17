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

/**
 * Order 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ProductAdviser_Model_Order extends XLite_Model_Order
implements XLite_Base_IDecorator
{
    /**
     * checkedOut 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkedOut()
    {
        parent::checkedOut();

        $products = array();

        foreach ($this->getItems() as $item) {
            if ($item->isValid()) {
                $product = $item->getProduct();
                if (is_object($product)) {
                    $products[$product->get('product_id')] = true;
                }
            }
        }

        if (count($products) > 0) {

            $products = array_keys($products);
            sort($products);

            foreach ($products as $product_id_idx => $product_id) {

                for ($i = $product_id_idx + 1; $i < count($products); $i++) {

                    $statistic = new XLite_Module_ProductAdviser_Model_ProductAlsoBuy();

                    if (!$statistic->find("product_id='".$product_id."' AND product_id_also_buy='".$products[$i]."'")) {
                        $statistic->set('product_id', $product_id);
                        $statistic->set('product_id_also_buy', $products[$i]);
                        $statistic->set('counter', 1);
                        $statistic->create();

                    } else {
                        $statistic->set('counter', $statistic->get('counter')+1);
                        $statistic->update();
                    }

                    $statistic = new XLite_Module_ProductAdviser_Model_ProductAlsoBuy();

                    if (!$statistic->find("product_id='".$products[$i]."' AND product_id_also_buy='".$product_id."'")) {
                        $statistic->set('product_id', $products[$i]);
                        $statistic->set('product_id_also_buy', $product_id);
                        $statistic->set('counter', 1);
                        $statistic->create();

                    } else {
                        $statistic->set('counter', $statistic->get('counter') + 1);
                        $statistic->update();
                    }
                }
            }
        }
    }

    /**
     * Update inventory
     * 
     * @param XLite_Model_OrderItem $item Order item
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function updateInventory(XLite_Model_OrderItem $item)
    {
        $requiredAmount = $item->get('amount');

        parent::updateInventory($item);

        if (
            $this->xlite->get('PA_InventorySupport')
            && $this->config->ProductAdviser->customer_notifications_enabled
            && $item->get('outOfStock')
        ) {

            $rejectedItemInfo = new StdClass();
            $rejectedItem = $item;
            $product = $item->getProduct();
            $rejectedItemInfo->product_id = $product->get('product_id');
            $rejectedItem->set('product', $product);

            if ($this->xlite->get('ProductOptionsEnabled') && $product->hasOptions()) {

                 if (isset($this->product_options)) {
                    $rejectedItem->set('productOptions', $this->product_options);
                }

                $rejectedItemInfo->productOptions = $rejectedItem->get('productOptions');
            }

            $rejectedItemInfo->itemKey = $rejectedItem->getKey();
            $rejectedItemInfo->requiredAmount = $requiredAmount;
            $rejectedItemInfo->availableAmount = $rejectedItem->get('amount');

            $this->session->set('rejectedItem', $rejectedItemInfo);
            $this->xlite->set('rejectedItemPresented', true);
        }
    }
}
