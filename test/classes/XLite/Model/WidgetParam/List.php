<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * List
 *  
 * @category  Litecommerce
 * @package   Model
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * List
 *
 * @package    Model
 * @subpackage Widget parameter
 * @since      3.0
 */
class XLite_Model_WidgetParam_List extends XLite_Model_WidgetParam
{
	/**
     * Param type
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $type = 'list';

    /**
     * Options 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $options = array();
}

