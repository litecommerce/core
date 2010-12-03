<?php

/**
* 
*
* @package AOM
* @access public
* @version $Id$
*/

function aom_order_clone($_this, $clone) 
{
    if ($_this->xlite->getComplex('mm.activeModules.Promotion')) {
        foreach ($_this->getAppliedBonuses() as $specialOffer) {
            if ( function_exists('func_is_clone_deprecated') && func_is_clone_deprecated() ) {
                $cloneSpecialOffer = $specialOffer->cloneObject();
            } else {
                $cloneSpecialOffer = $specialOffer->clone();
            }
            $cloneSpecialOffer->set('order_id',$clone->get('order_id'));
            $cloneSpecialOffer->update();
        }
        $cart = new \XLite\Model\Cart($clone->get('order_id'));
        $cart->set('DC', $_this->getComplex('orderDC.peer'));
    }

    $items = $_this->get('items');
    foreach ($items as $item) {
        $product = $item->get('product');
        if (is_object($product)) {
            $price = ( $product->is('available') ) ? $product->get('price') : $item->get('price');
        } else {
            $price = $item->get('price');
        }
        $item->set('originalPrice', $price);
        $item->update();
        if (is_object($product) && $product->is('available') ) {
            $clone->addItem($item);
        } else {
            $clone->_createItem($item);
        }
    }
                            
    $clone->set('status', "T");
    $clone->set('substatus', "");
    $clone->set('orig_profile_id', 0);
    $profile = $_this->get('profile');
    if ($profile) {
        if ( function_exists('func_is_clone_deprecated') && func_is_clone_deprecated() ) {
            $clone_profile = $profile->cloneObject();
        } else {
            $clone_profile = $profile->clone();
        }
        $clone_profile->set('order_id', $clone->get('order_id'));
        $clone_profile->update();
        $clone->set('profile', $clone_profile);
        $clone->update();
    }
    return $clone;
}

function aom_get_clone_order($_this)
{
    if (is_null($_this->clone_order)) {
        $aom_orders = $_this->session->get('aom_orders');
        $found = true;
        if (is_null($aom_orders[$_this->get('order_id')])) {
            $found = false;
        } else {
            $order = new \XLite\Model\Order();
            $order->_range = "status = 'T'";
            $found = $order->find("order_id = ". $aom_orders[$_this->get('order_id')]);
        }
        if ($found) {
            $_this->clone_order = new \XLite\Model\Order($aom_orders[$_this->get('order_id')]);
        } else {
            $order = new \XLite\Model\Order($_this->get('order_id'));
            if ( function_exists('func_is_clone_deprecated') && func_is_clone_deprecated() ) {
                $_this->clone_order = $order->cloneObject();
            } else {
                $_this->clone_order = $order->clone();
            }
            $aom_orders[$_this->get('order_id')] = $_this->clone_order->get('order_id');
            $_this->session->set('aom_orders', $aom_orders);

            // if offer is applied to original order, it should apply to clone as well
            $_this->updateOrderAsCart($_this->clone_order);
        }
        $_this->clone_order->refresh('items');
    }
    return $_this->clone_order;
}

function aom_get_profile($_this)
{
    if (is_null($_this->profile)) {
        $order = new \XLite\Model\Order($_this->get('order_id'));
        $_this->profile = $order->get('profile');
        if (is_null($_this->profile)) {
            $_this->profile = new \XLite\Model\Profile();
            $_this->profile->set('order_id', $_this->get('order_id'));
            $_this->profile->create();
            $order->set('profile_id', $_this->profile->get('profile_id'));
            $order->update();
        }
    }
    return $_this->profile;
}

function aom_split_order($_this)
{
    if ($_this->get('split_items')) {
        $order = $_this->get('order');
        $splitOrder = new \XLite\Model\Order();
        $splitOrder->set('date',time());
        $splitOrder->create();
        foreach ($order->get('items') as $item) {
            if (in_array($item->get('uniqueKey'),$_this->get('split_items'))) {
                $prop = $item->get('properties');
                $newItem = new \XLite\Model\OrderItem();
                $newItem->set('properties', $prop);

                $splitOrder->addItem($newItem);
                $order->deleteItem($item);
            }
        }

        $order->calcTotal();
        $splitOrder->calcTotal();
        $splitOrder->setProfileCopy($order->get('profile'));
        $splitOrder->set('orig_profile_id', $order->get('orig_profile_id'));
        $splitOrder->set('shipping_id', $order->get('shipping_id'));
        $splitOrder->set('payment_method', $order->get('payment_method'));
        $splitOrder->calcTotal();
        $order->update();
        $_this->updateOrderAsCart($order);
        $splitOrder->update();
        $_this->updateOrderAsCart($splitOrder);
        $_this->clone_order->delete();
        $orderHistory = new \XLite\Module\CDev\AOM\Model\OrderHistory();
        $orderHistory->log($order, $splitOrder, null, "split_order");
        $orderHistory->log($splitOrder, $order, null, "split_order");
        $_this->set('returnUrl',"admin.php?target=order&page=order_edit&mode=products&order_id=".$splitOrder->get('order_id'));
    }
}

?>
