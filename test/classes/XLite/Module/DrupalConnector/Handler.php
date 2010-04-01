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
     * Return array of <lc_key, cms_key> pairs for user profiles
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getUserDataTranslationTable()
    {
        return parent::getUserDataTranslationTable() + array(
            'cms_profile_id' => array(
                self::USER_DATA_FIELD => 'uid',
            ),
            'login' => array(
                self::USER_DATA_FIELD => 'mail',
            ),
            'password' => array(
                self::USER_DATA_FIELD    => 'pass',
                self::USER_DATA_CALLBACK => array('XLite_Model_Auth' , 'encryptPassword'),
            ),
        );
    }


	/**
     * Method to access the singleton
     *
     * @return XLite_Module_DrupalConnector_Handler
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
     * Get previous messages
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTopMessages()
    {
        return XLite_Core_TopMessage::getInstance()->getPreviousMessages();
    }
}

