<?php

abstract class XLite_Controller_Customer_Abstract extends XLite_Controller_Abstract
{
	public function __construct()
    {
		$this->cart = XLite_Model_CachingFactory::getObject('XLite_Model_Cart');

		// cleanup processed cart for non-checkout pages
		$target = isset($this->target) ? $this->target : '';
		if ($target != 'checkout' && ($this->cart->is('processed') || $this->cart->is('queued'))) {
			$this->cart->clear();
		}
    }

	public function getTemplate()
    {
		return $this->getComplex('config.General.add_on_mode') ? '../../../cart.html' : parent::getTemplate();
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
		return ($fc = $this->config->getComplex('Security.full_customer_security')) ? 
					$this->xlite->shopURL($url, $fc) : parent::shopURL($url, $secure);
    }

	public function getLoginURL()
    {
        return $this->shopUrl($this->getComplex('xlite.script'), $this->getComplex('config.Security.customer_security'));
    }

	public function isSecure()
    {
		$result = parent::isSecure();

		if ($this->getComplex('config.Security.full_customer_security')) {
			$result = $this->xlite->get('HTMLCatalogWorking');
		} elseif (!is_null($this->get('feed')) && $this->get('feed') == 'login') {
			$result = $this->getComplex('config.Security.customer_security');
		}

		return $result;
    }
}

