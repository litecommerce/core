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
 * XLite_Core_WidgetDataTransport 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Core_WidgetDataTransport extends XLite_Base
{
    /**
     * handler 
     * 
     * @var    mixed
     * @access protected
     * @since  3.0.0
     */
    protected $handler = null;


    /**
     * __construct 
     * 
     * @param mixed $handler passed handler
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
	public function __construct($handler)
	{
        $this->handler = $handler;
	}

    /**
     * __call 
     * 
     * @param mixed $method ____param_comment____
     * @param array $args   ____param_comment____
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        return isset($this->handler) ? call_user_func_array(array($this->handler, $method), $args) : null;
    }
}

