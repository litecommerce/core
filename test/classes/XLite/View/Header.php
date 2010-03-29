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

class XLite_View_Header extends XLite_View_Abstract
{
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

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('header.tpl');
    }

	/**
	 * getJSResources 
	 * 
	 * @return void
	 * @access protected
	 * @since  3.0.0
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
	 * @since  3.0.0
	 */
	protected function getCSSResources()
    {
		return self::getRegisteredResources(self::RESOURCE_CSS);
    }
}

