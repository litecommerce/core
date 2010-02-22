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
	 * Param name 
     * FIXME - must be removed
	 * 
	 * @var    string
	 * @access protected
	 * @since  3.0
	 */
	protected $name = null;

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
	 * Save passed data in object properties 
     * FIXME - "name" must be removed
     * FIXME - function must be removed
	 * 
	 * @param string $name  param name_
	 * @param string $value param value
	 * @param string $label param label
	 *  
	 * @return void
	 * @access protected
	 * @since  1.0.0
	 */
	protected function setCommonData($name, $value, $label)
	{
        // FIXME - must be removed
		$this->name  = $name;

		$this->value = $value;
		$this->label = $label;
	}

    /**
     * Check passed conditions
     *
     * @param array $conditions conditions to check
     *
     * @return array
     * @access protected
     * @since  3.0.0 EE
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
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function getValidaionSchema($value);


    /**
     * Validate passed value
     * 
     * @param mixed $value value to validate
     *  
     * @return mixed
     * @access public
     * @since  3.0.0 EE
     */
    public function validate($value)
    {
        $result = $this->checkConditions($this->getValidaionSchema($value));

        return array(empty($result), $result);
    }
    
    /**
     * Common constructor
     * FIXME - "name" must be removed
     *
     * @param string $name  param name
     * @param string $value param value
     * @param string $label param text label
     *
     * @return void
     * @access public
     * @since  1.0.0
     */
    public function __construct($name = null, $value = null, $label = null)
    {
        // FIXME - must be removed
        $this->name  = $name;

        $this->value = $value;
        $this->label = $label;
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
}

