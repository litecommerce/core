<?php

/* $Id$ */

/**
 * Some string
 *
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0
 */
class XLite_Model_WidgetParam_Checkbox extends XLite_Model_WidgetParam_Abstract
{
	/**
     * Param type
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $type = 'checkbox';

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
        return array(
            array(
                self::ATTR_CONDITION => !in_array($value, array(0, 1)),
                self::ATTR_MESSAGE   => ' only available values are (checked,unchecked)',
            ),
        );
    }
}

