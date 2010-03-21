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
 * XLite_View_Button_NewTab 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_View_Button_NewTab extends XLite_View_Button_Link
{
    /**
     * JavaScript: this code will be used by default 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDefaultJSCode($action = null)
    {
        return 'window.open(\'' . $this->getParam(self::PARAM_LOCATION) . '\', \'_blank\');';
    }
}

