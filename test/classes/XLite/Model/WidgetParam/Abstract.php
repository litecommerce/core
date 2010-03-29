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
 * Params for exported widget
 *
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0
 */
abstract class XLite_Model_WidgetParam_Abstract extends XLite_Base
{
    /**
     * Indexes in the "conditions" array
     */

    const ATTR_CONDITION = 'condition';
    const ATTR_MESSAGE   = 'text';
    const ATTR_CONTINUE  = 'continue';


	/**
     * Param type
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $type = null;

	/**
	 * Param value 
	 * 
	 * @var    mixed
	 * @access protected
	 * @since  3.0
	 */
	protected $value = null;

	/**
	 * Param label 
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0
	 */
	protected $label = null;

    /**
     * Determines if the param will be diaplayed in CMS as widget setting
     * 
     * @var    mixed
     * @access protected
     * @since  3.0.0
     */
    protected $isSetting = false;


    /**
     * Constructor
     * 
     * @param mixed $label     param label (text)
     * @param mixed $value     default value
     * @param mixed $isSetting display this setting in CMS or not
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($label, $value = null, $isSetting = false)
    {
        $this->label     = $label;
        $this->isSetting = $isSetting;

        $this->setValue($value);
    }

    /**
     * Check passed conditions
     *
     * @param array $conditions conditions to check
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function checkConditions(array $conditions)
    {
        $messages = array();

        foreach ($conditions as $condition) {
            if (true === $condition[self::ATTR_CONDITION]) {
                $messages[] = $condition[self::ATTR_MESSAGE];
                if (!isset($condition[self::ATTR_CONTINUE])) {
                     break;
                }
            }
        }

        return $messages;
    }

    /**
     * Return list of conditions to check
     * 
     * @param mixed $value value to validate
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getValidaionSchema($value);

    /**
     * Validate passed value
     * 
     * @param mixed $value value to validate
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function validate($value)
    {
        $result = $this->checkConditions($this->getValidaionSchema($value));

        return array(empty($result), $result);
    }
    
	/**
	 * Return protected property 
	 * 
	 * @param string $name property name
	 *  
	 * @return mixed
	 * @access public
	 * @since  3.0
	 */
	public function __get($name)
	{
		return isset($this->$name) ? $this->$name : null;
	}

    /**
     * Set param value
     * 
     * @param mixed $value value to set
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Append data to param value
     * 
     * @param mixed $value value to append
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function appendValue($value)
    {
        $this->value += $value;
    }

    /**
     * setVisibility 
     * 
     * @param bool $isSetting visibility flag
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setVisibility($isSetting)
    {
        $this->isSetting = $isSetting;
    }
}

