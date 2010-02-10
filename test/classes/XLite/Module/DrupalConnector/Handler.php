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
	public static function getCMSName()
	{
		return '____DRUPAL____';
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
}

