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
 * @since      3.0.0 EE
 */

// FIXME - must be completely revised; see ProductsAlsoBuy.php; recommended to derive from one class
// FIXME - related templates must be deleted

/**
 * XLite_Module_ProductAdviser_View_RelatedProducts
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Module_ProductAdviser_View_RelatedProducts extends XLite_View_ProductsList
{
	/**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('product');

	/**
	 * Get widget title
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getHead()
	{
		return 'Related products';
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

        // FIXME - correct the ProductAdviser module SQL dump and uncomment this line
        // $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue($this->config->ProductAdviser->rp_template);

        // TODO - check if these LC opions is really needed (or at least exist :) )
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue($this->config->ProductAdviser->rp_columns);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue($this->config->ProductAdviser->rp_show_descr);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue($this->config->ProductAdviser->rp_show_price);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue($this->config->ProductAdviser->rp_show_buynow);
    
        // TODO - check if there is the "bulk shopping" param is needed
        // ...
    }

    /**
     * Return products list
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getData()
    {
        // FIXME - unable to find a function which returns a products list here!!!! 
        return array();
    }


    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->config->ProductAdviser->related_products_enabled;
    }
}
