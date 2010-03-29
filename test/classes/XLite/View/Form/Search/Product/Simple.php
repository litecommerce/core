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
 * XLite_View_Form_Search_Product_Simple 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_View_Form_Search_Product_Simple extends XLite_View_Form_Abstract
{
	/**
	 * Current form name 
	 * 
	 * @return string
	 * @access protected
	 * @since  3.0.0
	 */
	protected function getFormName()
    {
        return 'search_form';
    }

    /**
     * getDefaultTarget 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTarget()
    {
        return 'search';
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

        $this->widgetParams[self::PARAM_FORM_METHOD]->setValue('GET');
    }
}

