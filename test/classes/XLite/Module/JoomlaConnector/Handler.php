<?php

/* $Id$ */

/**
 * Handler to use in Joomla 
 * 
 * @package    Lite Commerce
 * @subpackage Module JoomlaConnector
 * @since      3.0
 */
class XLite_Module_JoomlaConnector_Handler extends XLite_Core_CMSConnector
{
	/**
     * Return array of <lc_key, cms_key> pairs for user profiles
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getUserDataTranslationTable()
    {
        return array();
    } 


	/**
     * Method to access the singleton
     *
     * @return XLite_Module_JoomlaConnector_Handler
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

	/**
	 * Return name of current CMS 
	 * 
	 * @return string
	 * @access public
	 * @since  1.0.0
	 */
	public function getCMSName()
	{
		return '____JOOMLA____';
	}

	/**
     * Return the default controller name 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getDefaultTarget()
    {
        return null;
    }

	/**
     * Get landing link
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    public function getLandingLink()
    {
		return null;
	}
}

