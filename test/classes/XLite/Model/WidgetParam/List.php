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
    protected $options = null;


    /**
     * Return list of conditions to check
     *
     * @param mixed $value value to validate
     *
     * @return void
     * @access protected
     * @since  3.0.0
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
     * Constructor
     * 
     * @param mixed $label     param label (text)
     * @param mixed $value     default value
     * @param mixed $isSetting display this setting in CMS or not
     * @param array $options   options list
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($label, $value = null, $isSetting = false, array $options = array())
    {
        parent::__construct($label, $value, $isSetting);

        // TODO - check if there are more convinient way to extend this class
        if (!isset($this->options)) {
            $this->options = $options;
        }
    }
}

