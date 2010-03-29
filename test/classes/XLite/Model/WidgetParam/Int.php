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
 * Integer 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_WidgetParam_Int extends XLite_Model_WidgetParam_Abstract
{
	/**
     * Param type
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $type = 'integer';

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
                self::ATTR_CONDITION => !preg_match('/^\s*[-+]?\d+\s*$/Ss', $value),
                self::ATTR_MESSAGE   => ' is not integer',
            ),
        );
    }
}

