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
 * XLite_View_Model_Profile_Main 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class XLite_View_Model_Profile_Main extends XLite_View_Model_Profile_Abstract
{
    /**
     * Form sections 
     */

    const SECTION_MAIN     = 'main';
    const SECTION_ACCESS   = 'access';


    /**
     * Schema of the "E-mail & Password" section
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $mainSchema = array(
        'login' => array( 
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'E-mail',
            self::SCHEMA_REQUIRED => true,
        ),
        'password' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Password',
            self::SCHEMA_LABEL    => 'Password',
            self::SCHEMA_REQUIRED => false,
        ),
        'password_conf' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Password',
            self::SCHEMA_LABEL    => 'Confirm password',
            self::SCHEMA_REQUIRED => false,
        ),
    );

    /**
     * Schema of the "User access" section
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $accessSchema = array(
        'access_level' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_AccessLevel',
            self::SCHEMA_LABEL    => 'Access level',
            self::SCHEMA_REQUIRED => true,
        ),
        'status' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_AccountStatus',
            self::SCHEMA_LABEL    => 'Account status',
            self::SCHEMA_REQUIRED => true,
        ),
        'membership' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_Membership',
            self::SCHEMA_LABEL    => 'Membership',
            self::SCHEMA_REQUIRED => false,
        ),
    );


    /**
     * Return name of web form widget class
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormClass()
    {
        return 'XLite_View_Form_Profile_Main';
    }

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Profile details';
    }

    /**
     * Return fields list by the corresponding schema
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    protected function getFormFieldsForSectionMain()
    {
        // Create new profile - password is required
        if (!$this->getModelObject()->isPersistent) {
            foreach (array('password', 'password_conf') as $field) {
                if (isset($this->mainSchema[$field])) {
                    $this->mainSchema[$field][self::SCHEMA_REQUIRED] = true;
                }
            }
        }

        return $this->getFieldsBySchema($this->mainSchema);
    }

    /**
     * Return fields list by the corresponding schema
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    protected function getFormFieldsForSectionAccess()
    {
        return $this->getFieldsBySchema($this->accessSchema);
    }

    /**
     * Populate model object properties by the passed data
     * 
     * @param array $data data to set
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function setModelProperties(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = XLite_Model_Auth::encryptPassword($data['password']);
        }

        parent::setModelProperties($data);
    }

    /**
     * Check password and its confirmation
     * TODO: simplify
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkPassword()
    {
        $result = true;
        $data = $this->getRequestData();

        if (isset($this->sections[self::SECTION_MAIN]) && (!empty($data['password']) || !empty($data['password_conf']))) {

            if (!($result = $data['password'] === $data['password_conf'])) {
                XLite_Core_TopMessage::getInstance()->addError('Password and its confirmation do not match');
            }

        } else {

            $this->getModelObject()->unsetProperty('password');
            $this->getModelObject()->unsetProperty('password_conf');
        }

        return $result;
    }

    /**
     * Return list of the class-specific sections 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProfileMainSections()
    {
        return array(
            self::SECTION_MAIN   => 'Personal info',
            self::SECTION_ACCESS => 'Access information',
        );
    }

    /**
     * Return error message for the "validateInput" action
     * 
     * @param string $login profile login
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getErrorActionValidateInputMessage($login)
    {
        return 'The <i>' . $login . '</i> profile is already registered '
            . 'in LiteCommerce database. Please, try some other email address.';
    }

    /**
     * Process the errors occured during the "validateInput" action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessErrorActionValidateInput()
    {
        XLite_Core_TopMessage::getInstance()->add(
            $this->getErrorActionValidateInputMessage($this->getRequestData('login')),
            XLite_Core_TopMessage::ERROR
        );
    }


    /**
     * Create profile 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionCreate()
    {
        // FIXME: Uncomment this after the "register" funcion of "XLite_Model_Auth" class will be refactored
        // return !$this->checkPassword() ?: XLite_Model_Auth::getInstance()->register($this->getModelObject());

        return $this->checkPassword() ? parent::performActionCreate() : false;
    }

    /**
     * Update profile 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionUpdate()
    {
        return $this->checkPassword() ? parent::performActionUpdate() : false;
    }

    /**
     * Perform certain action for the model object
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionDelete()
    {
        // FIXME: Uncomment this after the "unregister" funcion of "XLite_Model_Auth" class will be refactored
        // return XLite_Model_Auth::getInstance()->unregister($this->getModelObject());

        return parent::performActionDelete();
    }

    /**
     * Perform certain action for the model object
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionValidateInput()
    {
        $result = true;

        $profile = new XLite_Model_Profile();
        if ($profile->findByLogin($this->getModelObject()->get('login'))) {
            $result = $profile->get('profile_id') === $this->getModelObject()->get('profile_id');
        }

        return $result;
    }


    /**
     * Return value for the "register" mode param
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getRegisterMode()
    {
        return XLite_Controller_Admin_Profile::getRegisterMode();
    }


    /**
     * The "mode" parameter used to determine if we create new or modify existing profile
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isRegisterMode()
    {
        return self::getRegisterMode() === XLite_Core_Request::getInstance()->mode;
    }

    /**
     * Return current profile ID
     *
     * @param bool $checkMode check mode or not
     *
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProfileId($checkMode = true)
    {
        return ($this->isRegisterMode() && $checkMode) ?: parent::getProfileId();
    }

    /**
     * Check for the form errors 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isValid()
    {
        return ('validateInput' === $this->currentAction) ?: parent::isValid();
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/profile/main.css';

        return $list;
    }

    /**
     * Save current form reference and initialize the cache
     *
     * @param array $params   widget params
     * @param array $sections sections list
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        $this->sections = $this->getProfileMainSections() + $this->sections;

        parent::__construct($params, $sections);
    }
}
