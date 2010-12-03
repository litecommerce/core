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

namespace XLite\Module\CDev\Egoods\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    protected function processed() 
    {
        $this->Egoods_processed();
        parent::processed();
    }

    function Egoods_processed()
    {
        require_once LC_MODULES_DIR . 'Egoods' . LC_DS . 'encoded.php';
        func_moduleEgoods_send_files($this);
        func_moduleEgoods_send_pins($this);
    }

    // assign pin codes to order items
    
    protected function checkedOut()
    {
        $this->Egoods_checkedOut();
        parent::checkedOut();
    }
    
    function Egoods_checkedOut()
    {
        $items = $this->get('items');
        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i]->is('pin') && $items[$i]->getComplex('product.pin_type') == "D") {
                for ($j = 0; $j < $items[$i]->get('amount'); $j++) {
                    $pin = new \XLite\Module\CDev\Egoods\Model\PinCode();
                    if ($pin->find('enabled=1 and product_id=' . $items[$i]->getComplex('product.product_id') . " and item_id='' and order_id=0")) {
                        $pin->set('item_id', $items[$i]->get('item_id'));
                        $pin->set('order_id', $this->get('order_id'));
                        $pin->update();
                    }
                }
                
                $pin_settings = new \XLite\Module\CDev\Egoods\Model\PinSettings($items[$i]->getComplex('product.product_id'));
                $pin = new \XLite\Module\CDev\Egoods\Model\PinCode();
                if ($pin->getFreePinCount($items[$i]->getComplex('product.product_id'))<= $pin_settings->get('low_available_limit') && $pin_settings->get('low_available_limit')) {
                    $mail = new \XLite\Module\CDev\Egoods\View\Mailer();
                    $mail->item = $items[$i];
                    $product = new \XLite\Model\Product();
                    $product->find("product_id = " . $items[$i]->getComplex('product.product_id'));
                    $mail->product = $product;
                    $mail->free_pins = $pin->getFreePinCount($items[$i]->getComplex('product.product_id'));
                    $mail->compose($this->config->Company->site_administrator, $this->config->Company->site_administrator, "modules/Egoods/low_available_limit");
                    $mail->send();
                }
            }
        }
    }
    
    // free assigned pin codes in case of failure
    protected function uncheckedOut()
    {
        $this->Egoods_uncheckedOut();
        parent::uncheckedOut();
    }
    
    function Egoods_uncheckedOut()
    {
        $items = $this->get('items');
        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i]->is('pin') && $items[$i]->getComplex('product.pin_type') == "D") {
                $pins = new \XLite\Module\CDev\Egoods\Model\PinCode();
                foreach ($pins->findAll("order_id='" . $this->get('order_id') . "' AND item_id='" . $items[$i]->get('item_id') . "'") as $pin) {
                    $pin->set('item_id', '');
                    $pin->set('order_id', 0);
                    $pin->update();
                }
            }
        }
    }

    // TODO: isShippingAvailable() is no more exists - rework it
    function isShippingAvailable()
    {
        $items = $this->getItems();
        $egoodsOnly = true;
        if (is_array($items)) {
            for ($i = 0; $i < count($items); $i++) {
                if (!$items[$i]->isEgood()) {
                    $egoodsOnly = false;
                    break;
                }
            }
        }
        if ($egoodsOnly) {
            return false;
        }

        return parent::isShippingAvailable();
    }

    function declined()
    {
        $this->Egoods_declined();
        parent::declined();
    }
    
    function Egoods_declined()
    {
        $items = $this->get('items');
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]->unStoreLinks();
        }
    }
}
