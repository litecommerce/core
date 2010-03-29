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
 * XLite_Model_WidgetParam_File 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_WidgetParam_File extends XLite_Model_WidgetParam_Abstract
{
	/**
     * Param type
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $type = 'string';

    /**
     * Return list of conditions to check
     * TODO - add check if file exists
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
                self::ATTR_CONDITION => false,
                self::ATTR_MESSAGE   => ' file not exists',
            ),
        );
    }
}

