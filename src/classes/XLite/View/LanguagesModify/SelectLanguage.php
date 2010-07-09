<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Select language dialog
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_LanguagesModify_SelectLanguage extends XLite_View_AView
{
	/**
	 * Translate language (cache)
	 * 
	 * @var    XLite_Model_Language
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $translateLanguage = null;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'languages/select_language.tpl';
    }

    /**
     * Get added languages 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddedLanguages()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Language')->findAddedLanguages();
    }

    /**
     * Check - is interface language or not
     * 
     * @param XLite_Model_Language $language ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isInterfaceLanguage(XLite_Model_Language $language)
    {
        return XLite_Core_Config::getInstance()->General->defaultLanguage->code == $language->code;
    }

    /**
     * Check - is translate language or not
     * 
     * @param XLite_Model_Language $language Language
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTranslateLanguage(XLite_Model_Language $language)
    {
        return $this->getTranslatedLanguage()
            && $this->getTranslatedLanguage()->code == $language->code;
    }

    /**
     * Get application default language 
     * 
     * @return XLite_Model_Language
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultLanguage()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage();
    }

    /**
     * Check - specified language can been selected or not
     * 
     * @param XLite_Model_Language $language Language
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canSelect(XLite_Model_Language $language)
    {
        return $language->code != $this->getDefaultLanguage()->code
            && (!$this->getTranslatedLanguage() || $language->code != $this->getTranslatedLanguage()->code);
    }

    /**
     * Check - specified language can been deleted or not
     * 
     * @param XLite_Model_Language $language Language
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canDelete(XLite_Model_Language $language)
    {
        return !in_array(
            $language->code,
            array($this->getDefaultLanguage()->code, XLite_Core_Config::getInstance()->General->defaultLanguage->code)
        );
    }

    /**
     * Get translated language 
     * 
     * @return XLite_Model_Language or false
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTranslatedLanguage()
    {
		if (!isset($this->translateLanguage)) {
	        if (XLite_Core_Request::getInstance()->language) {
    	        $this->translateLanguage = XLite_Core_Database::getRepo('XLite_Model_Language')->findOneByCode(
        	        XLite_Core_Request::getInstance()->language
            	);
	            if (!$this->translateLanguage || !$this->translateLanguage->added) {
					$this->translateLanguage = false;
				}
			}
        }

        return $this->translateLanguage;
    }

    /**
     * Get inactive languages 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInactiveLanguages()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Language')
            ->findInactiveLanguages();
    }

	/**
	 * Check widget visibility 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function isVisible()
	{
		return parent::isVisible()
			&& $this->getInactiveLanguages();
	}


}
