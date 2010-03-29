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
 * Product id
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_WidgetParam_ObjectId_Product extends XLite_Model_WidgetParam_ObjectId
{
    /**
     * Return object class name
     *
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected function getClassName()
    {
        return 'XLite_Model_Product';
    }

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
                self::ATTR_CONDITION => 0 >= $value,
                self::ATTR_MESSAGE   => ' is a non-positive number',
            ),
            array(
                self::ATTR_CONDITION => !$this->getObject($value)->isExists(),
                self::ATTR_MESSAGE   => ' record with such ID does not exist',
            ),
        ) + parent::getValidaionSchema($value);
    }
}

