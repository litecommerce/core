<?php

/**
* @package Module_WishList
* @access private
* @version $Id$
*/

    function Module_WishList_action_add($_this) // {{{
    {

		if (!$_this->auth->is("logged"))	{
			 $_this->set("returnUrl","cart.php?target=login&mode=wishlist");
			 $_this->session->set("wishlist_url","cart.php?target=wishlist&action=add&product_id=".$_this->product_id);
	  		 return;
		}
        
        $product = new XLite_Model_Product($_this->product_id);
        
		// alternative way to set product options
		if ($_this->xlite->get("ProductOptionsEnabled") && isset($_REQUEST["OptionSetIndex"][$product->get("product_id")])) {
			$options_set = $product->get("expandedItems");
			foreach($options_set[$_REQUEST["OptionSetIndex"][$product->get("product_id")]] as $_opt) {
				$_this->product_options[$_opt->class] = $_opt->option_id;	
			}
		}
        
        if ($_this->xlite->get("ProductOptionsEnabled")&&$product->hasOptions()&&!isset($_this->product_options)) {
            $_this->set("returnUrl","cart.php?target=product&product_id=".$_this->product_id);
            return;
        }

        $wishlist = $_this->get("wishList");
        $wishlist_product = new XLite_Module_WishList_Model_WishListProduct();
        
        $wishlist_product->set("product_id",$_this->get("product_id"));

        $wishlist_product->set("wishlist_id",$wishlist->get("wishlist_id"));
        $orderItem  = $wishlist_product->get("orderItem");
        if (isset($_this->product_options)) {
            $wishlist_product->setProductOptions($_this->product_options);
            if (version_compare(PHP_VERSION, '5.0.0')===-1) $orderItem->setProductOptions($_this->product_options);
        }
		$wishlist_product->set("item_id",$orderItem->get("key"));
        $found = $wishlist_product->find("item_id ='" . addslashes($wishlist_product->get("item_id")) . "' AND wishlist_id='" . $wishlist->get("wishlist_id"). "'");

        $amount = $wishlist_product->get("amount");
        isset($_this->amount) ? $amount += $_this->amount : $amount += 1;

        $wishlist_product->set("amount",$amount);

        if ($found) {
        	$wishlist_product->update();
        } else {
        	$wishlist_product->create();
        }
    } // }}} 

?>
