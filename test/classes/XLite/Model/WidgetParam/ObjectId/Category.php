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
 * Category id 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_WidgetParam_ObjectId_Category extends XLite_Model_WidgetParam_ObjectId
{
    /**
     * Allowed or not to  use root category id (0) 
     * 
     * @var    boolean
     * @access protected
     * @since  3.0.0
     */
    protected $rootIsAllowed = false;


    /**
     * Return object class name
     *
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected function getClassName()
    {
        return 'XLite_Model_Category';
    }

    /**
     * getIdValidCondition 
     * 
     * @param mixed $value value to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getIdValidCondition($value)
    {
        $result = parent::getIdValidCondition($value);

        if ($this->rootIsAllowed) {
            $result = array(
                self::ATTR_CONDITION => 0 > $value,
                self::ATTR_MESSAGE   => ' is a negative number',
            );
        }

        return $result;
    }

    /**
     * getObjectExistsCondition 
     * 
     * @param mixed $value value to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getObjectExistsCondition($value)
    {
        $result = parent::getIdValidCondition($value);

        $result[self::ATTR_CONDITION] = 0 < $value && $result[self::ATTR_CONDITION];

        return $result;
    }


    /**
     * Constructor
     * 
     * @param string $label         param label (text)
     * @param mixed  $value         default value
     * @param bool   $isSetting     display this setting in CMS or not
     * @param bool   $rootIsAllowed root category id (0) is allowed or not
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($label, $value = null, $isSetting = false, $rootIsAllowed = false)
    {   
        parent::__construct($label, $value, $isSetting);
        
        $this->rootIsAllowed = $rootIsAllowed;
    }
}

