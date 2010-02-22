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
class XLite_Model_WidgetParam_List extends XLite_Model_WidgetParam_String
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
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options = array();


    /**
     * Return list of conditions to check
     *
     * @param mixed $value value to validate
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getValidaionSchema($value)
    {
        return parent::getValidaionSchema($value) + array(
            array(
                self::ATTR_CONDITION => isset($this->options[$value]),
                self::ATTR_MESSAGE   => ' unallowed param value - "' . $value . '"',
            ),
        );
    }


    /**
     * Common constructor
     * FIXME - "name" must be removed
     *
     * @param string $name    param name
     * @param string $value   param value
     * @param string $label   param text label
     * @param array  $options list options
     *
     * @return void
     * @access public
     * @since  1.0.0
     */
    public function __construct($name = null, $value = null, $label = null, array $options = array())
    {
        parent::__construct($name, $value, $label);

        $this->options = $options;
    }
}

