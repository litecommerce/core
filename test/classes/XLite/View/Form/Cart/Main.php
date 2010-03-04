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
 * XLite_View_Form_Cart_Main 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_View_Form_Cart_Main extends XLite_View_Form_Abstract
{
    /**
     * Current form name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getFormName()
    {
        return 'cart_form';
    }

    /**
     * Set predefined attributes 
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function defineDefaultFormAttributes()
    {
        $this->defaultFormAttributes['form_target']  = 'cart';
        $this->defaultFormAttributes['form_action']  = 'update';
        $this->defaultFormAttributes['form_params'] += array('cart_id' => 0);
    }
}

