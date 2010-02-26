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

/**
 * XLite_Module_ProductAdviser_View_ProductAlsoBuy
 *
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_ProductAdviser_View_ProductAlsoBuy extends XLite_View_Dialog
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
	 * Available display modes list
	 * 
	 * @var    array
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $displayModes = array(
		'list'  => 'List',
        'icons' => 'Icons',
        'table' => 'Table'
	);

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
		return 'People who buy this product also buy';
	}

	/**
	 * Get widget directory
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getDir()
	{
        return 'modules/ProductAdviser/ProductsAlsoBuy/' . $this->getDisplayMode();
	}

	/**
     * Get widget display mode parameter (menu | dialog)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDisplayMode()
    {
		return $this->config->ProductAdviser->pab_template;
    }

    /**
     * Check if recommended products are available
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkProductsAlsoBuy()
    {
		$productsAlsoBuy = $this->getComplex("product.ProductsAlsoBuy");
		return !empty($productsAlsoBuy);
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
		return parent::isVisible()
			&& $this->config->ProductAdviser->products_also_buy_enabled
			&& empty($this->page)
			&& $this->checkProductsAlsoBuy();
    }

}
