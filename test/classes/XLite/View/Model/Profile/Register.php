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
 * XLite_View_Model_Profile_Register 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_View_Model_Profile_Register extends XLite_View_Model_Profile_Abstract
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
        return 'XLite_View_Form_Profile_Register';
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
    protected function performActionCreate(array $data = array())
    {
        $result = parent::performActionCreate($data);

        if ($result) {
            $this->setReturnUrlParams(array('profile_id' => $this->getModelObject()->get('profile_id')));
        }

        return $result;
    }


    /**
     * Perform some action for the model object
     *
     * @param string $action action to perform
     * @param array  $data   form data
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function performAction($action, array $data = array())
    {
        $this->setReturnUrlParams(array(self::PARAM_MODE => 'register'));

        return parent::performAction($action, $data);
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
        return null;
    }
}
