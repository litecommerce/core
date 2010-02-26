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
 * Category id 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Model_WidgetParam_ObjectId_Category extends XLite_Model_WidgetParam_ObjectId
{
    /**
     * Allow use root category id (0) 
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $rootIsAllow = false;

    /**
     * Constructor
     *
     * @param string  $label       param text label
     * @param string  $value       param value
     * @param boolean $rootIsAllow Root category id (0) is allow or not
     *
     * @return void
     * @access public
     * @since  1.0.0
     */
    public function __construct($label, $value = null, $rootIsAllow = false)
    {
        parent::__construct($label, $value);

        $this->rootIsAllow = $rootIsAllow;
    }

    /**
     * Return object class name
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getClassName()
    {
        return 'XLite_Model_Category';
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

        if (!$this->rootIsAllow) {
            $schema[] = array(
                self::ATTR_CONDITION => 0 == $value,
                self::ATTR_MESSAGE   => ' is a zero',
            );
        }

        $schema[] = array(
            self::ATTR_CONDITION => 0 < $value && !$this->getObject($value)->isExists(),
            self::ATTR_MESSAGE   => ' is a wrong category id (category can not found)',
        );

        return $schema;
    }

}

