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
 * XLite_Model_WidgetParam 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_Model_WidgetParam_ObjectId extends XLite_Model_WidgetParam_Int
{
    /**
     * Return object class name 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function getClassName();

    /**
     * Return object ID
     * 
     * @param int $id object ID
     *  
     * @return int
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getId($id = null)
    {
        return isset($id) ? $id : $this->value;
    }

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
        $schema = parent::getValidaionSchema($value);

        $schema[] = array(
            self::ATTR_CONDITION => 0 > $value,
            self::ATTR_MESSAGE   => ' is a negative number',
        );

        return $schema;
    }

    /**
     * Return object with passed/predefined ID
     *
     * @param int $id object ID
     *
     * @return XLite_Base
     * @access public
     * @since  3.0.0 EE
     */
    public function getObject($id = null)
    {
        return XLite_Model_CachingFactory::getObject($this->getClassName(), $this->getId($id));
    }
}

