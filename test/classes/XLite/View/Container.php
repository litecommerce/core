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
 * XLite_View_Container 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_View_Container extends XLite_View_Abstract
{
	/**
	 * Widget body default template
	 */
	const BODY_TEMPLATE = 'body.tpl';

    /**
     * Indexes in the "conditions" array
     */

    const ATTR_CONDITION = 'condition';
    const ATTR_MESSAGE   = 'text';
    const ATTR_CONTINUE  = 'continue';


    /**
     * Return title 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function getHead();

    /**
     * Return templates directory name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function getDir();
    

	/**
	 * Determines if need to display only a widget body
	 * 
	 * @return bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function isWrapped()
	{
		return $this->attributes['showWrapper'] && !XLite_Core_CMSConnector::isCMSStarted();
	}

    /**
     * Check passed conditions 
     * TODO - check if we need to move this function into the XLite_View_Abstract
     * 
     * @param array $conditions conditions to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function checkConditions(array $conditions)
    {
        $errors = array();

        foreach ($conditions as $condition) {
            if (true === $condition[self::ATTR_CONDITION]) {
                $errors[] = $condition[self::ATTR_MESSAGE];
                if (!isset($condition[self::ATTR_CONTINUE])) {
                     break;
                }
            }
        }

        return $errors;
    }

    /**
     * Set attributes and template (is needed)
     * 
     * @param array $attributes widget attributes
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes['showWrapper'] = true;

        parent::__construct($attributes);

        if (!$this->isWrapped()) {
            $this->template = $this->getDir() . LC_DS . self::BODY_TEMPLATE;
        }
    }
}

