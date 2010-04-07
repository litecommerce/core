<?php

class XLite_Module_WholesaleTrading_View_Product extends XLite_View_Product implements XLite_Base_IDecorator
{
    /**
     * Check - available product for sale or not
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isAvailableForSale()
    {
        return $this->getProduct()->is('saleAvailable')
			? parent::isAvailableForSale()
			: false;
    }
}
