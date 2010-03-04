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

/**
 * XLite_Module_ProductAdviser_View_RelatedProducts
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Module_ProductAdviser_View_RelatedProducts extends XLite_View_Dialog
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
		return 'Related products';
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
        return 'modules/ProductAdviser/RelatedProducts/' . $this->getDisplayMode();
	}

	/**
	 * Define widget parameters
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function defineWidgetParams()
	{
        parent::defineWidgetParams();

		$this->widgetParams += array(
            'displayMode'        => new XLite_Model_WidgetParam_List('Display mode', $this->getDisplayMode(), $this->displayModes),
            'numberOfColumns'    => new XLite_Model_WidgetParam_List('Number of columns (for Icons mode only)', 2, range(1,5)),
            'showDescription'    => new XLite_Model_WidgetParam_Checkbox('Show product description (for List mode only)', 1),
            'showPrice'          => new XLite_Model_WidgetParam_Checkbox('Show product price', 1),
            'showAddToCart'      => new XLite_Model_WidgetParam_Checkbox('Show \'Add to Cart\' button', 1),
            'enableBulkShopping' => new XLite_Model_WidgetParam_Checkbox('Enable multiple additions at once', 0),
		);
	}

	/**
     * Get widget display mode parameter (icons | list | table)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDisplayMode()
    {
        return ($this->attributes[self::IS_EXPORTED] ? $this->attributes['displayMode'] : $this->config->ProductAdviser->rp_template);

    }

    /**
     * Get 'number of columns' parameter
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNumberOfColumns()
    {
        return ($this->attributes[self::IS_EXPORTED] ? $this->attributes['numberOfColumns'] + 1 : $this->config->ProductAdviser->rp_columns);
    }

    /**
     * Get 'show description' parameter
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getShowDescription()
    {
        if ($this->attributes[self::IS_EXPORTED]) {
            $return = (isset($this->attributes['showDescription']) && true == $this->attributes['showDescription']);

        } else {
            $return = $this->config->ProductAdviser->rp_show_descr;
        }

        return $return;
    }

    /**
     *  Get 'show price' parameter
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getShowPrice()
    {
        if ($this->attributes[self::IS_EXPORTED]) {
            $return = (isset($this->attributes['showPrice']) && true == $this->attributes['showPrice']);

        } else {
            $return = $this->config->ProductAdviser->rp_show_price;
        }

        return $return;
    }

    /**
     * Get 'show add to cart button' parameter 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getShowAddToCart()
    {
        if ($this->attributes[self::IS_EXPORTED]) {
            $return = (isset($this->attributes['showAddToCart']) && true == $this->attributes['showAddToCart']);

        } else {
            $return = $this->config->ProductAdviser->rp_show_buynow;
        }

        return $return;
    }

    /**
     * Get 'Enable multiple additions at once' parameter 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBulkShopping()
    {
        if ($this->attributes[self::IS_EXPORTED]) {
            $return = (isset($this->attributes['enableBulkShopping']) && true == $this->attributes['enableBulkShopping']);

        } else {
            $return = $this->config->ProductAdviser->rp_bulk_shopping;
        }

        return $return;
    }

    /**
     * Check if related products are available
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkRelatedProducts()
    {
        $rp = $this->getComplex('product.RelatedProducts');
        return !empty($rp);
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
        return parent::isVisible()
            && $this->config->ProductAdviser->related_products_enabled
            && $this->checkRelatedProducts()
            && empty($this->page);
    }

}
