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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_View_Model_Profile_Trigger 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_View_Model_Profile_Trigger extends XlIte_Base implements XLite_Base_ISingleton
{
	/**
	 * Register mode
	 * 
	 * @return string
	 * @access protected
	 * @since  3.0.0
	 */
	protected function getRegisterMode()
	{
		return 'register';
	}


	/**
	 * Return class name of the register form widget
	 * 
	 * @return string
	 * @access public
	 * @since  3.0.0
	 */
	public function getProfileFormClass()
	{
		return 'XLite_View_Model_Profile_' 
			. ($this->getRegisterMode() === XLite_Core_Request::getInstance()->mode ? 'Register' : 'Modify');
	}


    /**
     * Use this function to get a reference to this class object
     *
     * @return XLite
     * @access public
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }
}
