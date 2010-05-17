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
 * Profile model widget (Modify profile page)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Model_Profile_Modify extends XLite_View_Model_Profile_Abstract
{
    /**
     * Return name of web form widget class
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormClass()
    {
        return 'XLite_View_Form_Profile_Modify';
    }


    /**
     * Check if profile ID is passed in request
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkRequestProfileId()
    {
        return !empty(XLite_Core_Request::getInstance()->profile_id);
    }

    /**
     * Return ID of current profile
     * 
     * @return int 
     * @access public
     * @since  3.0.0
     */
    public function getProfileId()
    {
        return $this->checkRequestProfileId() 
            ? XLite_Core_Request::getInstance()->profile_id 
            : XLite_Model_Session::getInstance()->get('profile_id');
    }

    /**
     * Perform certain action for the model object
     *
     * @param array $data model properties
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionUpdate(array $data = array())
    {
        if ($this->checkRequestProfileId()) {
            $this->setReturnUrlParams(array('profile_id' => $this->getProfileId()));
        }

        return parent::performActionUpdate($data);
    }
}
