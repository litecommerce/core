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
        $schema = parent::getValidaionSchema($value);

        if (!$this->rootIsAllowed) {
            $schema[] = array(
                self::ATTR_CONDITION => 0 == $value,
                self::ATTR_MESSAGE   => ' is zero',
            );
        }

        $schema[] = array(
            self::ATTR_CONDITION => 0 < $value && !$this->getObject($value)->isExists(),
            self::ATTR_MESSAGE   => ' wrong ID (not found)',
        );

        return $schema;
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

