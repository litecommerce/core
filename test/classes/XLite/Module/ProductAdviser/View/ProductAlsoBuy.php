<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

// FIXME - related templates must be deleted

/**
 * XLite_Module_ProductAdviser_View_ProductAlsoBuy
 *
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_ProductAdviser_View_ProductAlsoBuy extends XLite_View_ProductsList
{	
	/**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('product');


	/**
	 * Get widget title
	 * 
	 * @return string
	 * @access public
	 * @since  3.0.0
	 */
	protected function getHead()
	{
		return 'People who buy this product also buy';
	}

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue($this->config->ProductAdviser->pab_template);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue($this->config->ProductAdviser->pab_columns);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue($this->config->ProductAdviser->pab_show_descr);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue($this->config->ProductAdviser->pab_show_price);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue($this->config->ProductAdviser->pab_show_buynow);

        $this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SORT_BY]->setValue('Name');
        $this->widgetParams[self::PARAM_SORT_ORDER]->setValue('asc');

        foreach ($this->getHiddenParamsList() as $param) {
            $this->widgetParams[$param]->setVisibility(false);
        }
    }

    /**
     * Get the list of parameters that are hidden on the settings page 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getHiddenParamsList()
    {
        return array(
            self::PARAM_SHOW_DISPLAY_MODE_SELECTOR,
            self::PARAM_SHOW_SORT_BY_SELECTOR,
            self::PARAM_SORT_BY,
            self::PARAM_SORT_ORDER,
            self::PARAM_SHOW_ALL_ITEMS_PER_PAGE,
        );
    }

    /**
     * Get products list 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getData()
    {
        return $this->getProduct()->getProductsAlsoBuy();
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    public function isVisible()
    {
		return parent::isVisible() && $this->config->ProductAdviser->products_also_buy_enabled;
    }
}
