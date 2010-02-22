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

// FIXME - must be removed

/**
 * Params for exported widget
 *
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0
 */
class XLite_Model_WidgetParam extends XLite_Model_WidgetParam_Abstract
{
	/**
	 * Common constructor 
     * FIXME - "name" must be removed 
	 * 
	 * @param string $name  param name
	 * @param string $value param value
	 * @param string $label param text label
	 *  
	 * @return void
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct($name = null, $value = null, $label = null)
	{
		$this->setCommonData($name, $value, $label);
	}
}

