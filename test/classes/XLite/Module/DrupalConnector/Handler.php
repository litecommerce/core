<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Handler to use in Drupal
 *  
 * @category  Litecommerce
 * @package   DrupalConnector
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Handler to use in Drupal 
 * 
 * @package    Lite Commerce
 * @subpackage DrupalConnector
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
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getUserTranslationTable()
    {
        $table = parent::getUserTranslationTable();

        $table['pass'] = 'password';

        return $table;
    }
}

