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

// FIXME - to revise
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
     * getData 
     * FIXME - must return the result from the XLite_Module_ProductAdviser_Model_ProductAlsoBuy class
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getData()
    {
        return array();
    }

    /**
     * Check if widget is visible
     * TODO - check if this setting is really exists
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
