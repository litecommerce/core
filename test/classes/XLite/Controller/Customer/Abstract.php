<?php

abstract class XLite_Controller_Customer_Abstract extends XLite_Controller_Abstract
{
	public function __construct()
    {
        parent::__construct();

		$this->cart = XLite_Model_Cart::getInstance();
		// cleanup processed cart for non-checkout pages
		$target = isset($_REQUEST['target']) ? $_REQUEST['target'] : '';
		if ($target != 'checkout' && ($this->cart->is('processed') || $this->cart->is('queued'))) {
			$this->cart->clear();
		}
    }

	public function getTemplate()
    {
		return $this->get('config.General.add_on_mode') ? '../../../cart.html' : parent::getTemplate();
    }

	public function getProduct()
    {
		$product = parent::getProduct();

		if (!is_null($product) && !$product->get('enabled')) {
			$product = $this->product = null;
		}

		return $product;
    }

	public function shopURL($url, $secure = false, $pure_url = false)
    {
		return ($fc = $this->config->get('Security.full_customer_security')) ? 
					$this->xlite->shopURL($url, $fc) : parent::shopURL($url, $secure);
    }

	public function getLoginURL()
    {
        return $this->shopUrl($this->get('xlite.script'), $this->get('config.Security.customer_security'));
    }

	public function isSecure()
    {
		$result = parent::isSecure();

		if ($this->get('config.Security.full_customer_security')) {
			$result = $this->xlite->get('HTMLCatalogWorking');
		} elseif (!is_null($this->get('feed')) && $this->get('feed') == 'login') {
			$result = $this->get('config.Security.customer_security');
		}

		return $result;
    }
}

