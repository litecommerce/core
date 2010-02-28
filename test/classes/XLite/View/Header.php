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

class XLite_View_Header extends XLite_View_Abstract
{
	/**
	 * template 
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $template = 'header.tpl';


	/**
	 * getJSResources 
	 * 
	 * @return void
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getJSResources()
	{
		return self::getRegisteredResources(self::RESOURCE_JS);
	}

	/**
	 * getCSSResources 
	 * 
	 * @return void
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getCSSResources()
    {
		return self::getRegisteredResources(self::RESOURCE_CSS);
    }
}

