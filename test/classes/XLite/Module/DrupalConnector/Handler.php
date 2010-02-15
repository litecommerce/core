<?php

/* $Id$ */

/**
 * Handler to use in Drupal 
 * 
 * @package    Lite Commerce
 * @subpackage Module DrupalConnector
 * @since      3.0
 */
class XLite_Module_DrupalConnector_Handler extends XLite_Core_CMSConnector
{
	/**
	 * Semaphore 
	 * 
	 * @var    bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected static $isRequestRemapped = false;

	/**
     * Method to access the singleton
     *
     * @return XLite_Module_DrupalConnector_Handler
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
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
		return '____DRUPAL____';
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
		$link = new XLite_Module_DrupalConnector_Model_LandingLink();
		$link->create();

		return $link->getLink();
    }

	/**
     * Get translation table for prfile data
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    protected function getUserTranslationTable()
    {
        $table = parent::getUserTranslationTable();

        $table['pass'] = 'password';

        return $table;
    }

    /**
     * Prepare call
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function prepareCall()
	{
		if (!self::$isRequestRemapped) {
			XLite_Core_Request::getInstance()->remapRequest();
			self::$isRequestRemapped = true;
		}
	}
}

