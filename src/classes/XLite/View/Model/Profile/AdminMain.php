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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Model\Profile;

/**
 * Administrator profile model widget. This widget is used in the admin interface
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class AdminMain extends \XLite\View\Model\AModel
{
    /**
     * Form sections
     */
    const SECTION_SUMMARY  = 'summary';
    const SECTION_MAIN     = 'main';
    const SECTION_ACCESS   = 'access';


    /**
     * Schema of the "Account summary" section
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $summarySchema = array(
        'referer' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL    => 'Referer',
            self::SCHEMA_REQUIRED => false,
        ),
        'added' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL    => 'Added',
            self::SCHEMA_REQUIRED => false,
        ),
        'last_login' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL    => 'Last login',
            self::SCHEMA_REQUIRED => false,
        ),
        'language' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL    => 'Language',
            self::SCHEMA_REQUIRED => false,
        ),
        'orders_count' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL    => 'Orders count',
            self::SCHEMA_REQUIRED => false,
        ),
    );

    /**
     * Schema of the "E-mail & Password" section
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $mainSchema = array(
        'login' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'E-mail',
            self::SCHEMA_REQUIRED => true,
        ),
        'password' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Password',
            self::SCHEMA_LABEL    => 'Password',
            self::SCHEMA_REQUIRED => false,
        ),
        'password_conf' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Password',
            self::SCHEMA_LABEL    => 'Confirm password',
            self::SCHEMA_REQUIRED => false,
        ),
    );

    /**
     * Schema of the "User access" section
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $accessSchema = array(
        'access_level' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\AccessLevel',
            self::SCHEMA_LABEL    => 'Access level',
            self::SCHEMA_REQUIRED => true,
        ),
        'status' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\AccountStatus',
            self::SCHEMA_LABEL    => 'Account status',
            self::SCHEMA_REQUIRED => true,
        ),
        'membership_id' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\Membership',
            self::SCHEMA_LABEL    => 'Membership',
            self::SCHEMA_REQUIRED => false,
        ),
        'pending_membership_id' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Label',
            self::SCHEMA_LABEL    => 'Pending membership',
            self::SCHEMA_REQUIRED => false,
        ),
    );


    /**
     * Return value for the "register" mode param
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getRegisterMode()
    {
        return \XLite\Controller\Admin\Profile::getRegisterMode();
    }


    /**
     * Save current form reference and initialize the cache
     *
     * @param array $params   Widget params OPTIONAL
     * @param array $sections Sections list OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        $this->sections = $this->getProfileMainSections() + $this->sections;

        parent::__construct($params, $sections);
    }

    /**
     * The "mode" parameter used to determine if we create new or modify existing profile
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isRegisterMode()
    {
        return \XLite\Controller\Admin\Profile::getInstance()->isRegisterMode();
    }

    /**
     * Return current profile ID
     *
     * @param boolean $checkMode Check mode or not OPTIONAL
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProfileId($checkMode = true)
    {
        return ($this->isRegisterMode() && $checkMode) ?:
            ($this->getRequestProfileId()) ?: \XLite\Core\Session::getInstance()->get('profile_id');
    }

    /**
     * getRequestProfileId
     *
     * @return integer|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRequestProfileId()
    {
        return \XLite\Core\Request::getInstance()->profile_id;
    }

    /**
     * Check for the form errors
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isValid()
    {
        return ('validateInput' === $this->currentAction) ?: parent::isValid();
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/profile/main.css';

        return $list;
    }

    /**
     * getDefaultFieldValue
     *
     * @param string $name Field name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDefaultFieldValue($name)
    {
        $value = parent::getDefaultFieldValue($name);

        switch ($name) {

            case 'added':
            case 'last_login':
                if (0 < $value) {
                    $value = date('r', $value);

                } else {
                    $value = static::t('never');
                }

                break;

            case 'referer':
                $value = $value ?: static::t('unknown');
                break;

            case 'language':
                $lng = \XLite\Core\Database::getRepo('XLite\Model\Language')->findOneByCode($value);
                $value = isset($lng) ? $lng->getName() : $value;
                break;

            case 'pending_membership_id':
                $value = 0 < $value ? $this->getModelObject()->getPendingMembership()->getName() : static::t('none');
                break;

            default:
        }

        return $value;
    }


    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        if ($this->isRegisterMode()) {
            $obj = new \XLite\Model\Profile();

        } else {
            $obj = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($this->getProfileId());
        }

        return $obj;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Profile\Main';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return static::t('Profile details');
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormFieldsForSectionMain()
    {
        // Create new profile - password is required
        if (!$this->getModelObject()->isPersistent()) {
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormFieldsForSectionAccess()
    {
        if ($this->isRegisterMode()) {
            unset($this->accessSchema['pending_membership_id']);
        }

        return $this->getFieldsBySchema($this->accessSchema);
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormFieldsForSectionSummary()
    {
        return !$this->isRegisterMode() ? $this->getFieldsBySchema($this->summarySchema) : array();
    }

    /**
     * Populate model object properties by the passed data
     *
     * @param array $data Data to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setModelProperties(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = \XLite\Core\Auth::encryptPassword($data['password']);
        }

        parent::setModelProperties($data);
    }

    /**
     * Prepare request data for mapping profile object
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareDataForMapping()
    {
        $data = parent::prepareDataForMapping();

        if (isset($data['membership_id']) && 0 < intval($data['membership_id'])) {

            $membership = \XLite\Core\Database::getRepo('XLite\Model\Membership')->find($data['membership_id']);

            if (isset($membership)) {
                $data['membership'] = $membership;
            }
        }

        if (!isset($data['membership'])) {
            $data['membership'] = null;
        }

        return $data;
    }

    /**
     * Check password and its confirmation
     * TODO: simplify
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkPassword()
    {
        $result = true;
        $data = $this->getRequestData();

        if (
            isset($this->sections[self::SECTION_MAIN])
            && (!empty($data['password']) || !empty($data['password_conf']))
        ) {

            if ($data['password'] != $data['password_conf']) {
                $result = false;
                \XLite\Core\TopMessage::addError('Password and its confirmation do not match');
            }

        } else {
            $this->excludeField('password');
            $this->excludeField('password_conf');
        }

        return $result;
    }

    /**
     * Check profile data
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkProfileData()
    {
        $result = $this->checkPassword();

        if ($result) {
            // Check if profile with specified login is already exists
            $sameProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
                ->findUserWithSameLogin($this->getModelObject());

            if (isset($sameProfile)) {
                $formFields = $this->getFormFields();
                $this->addErrorMessage(
                    'login',
                    'User with specified email is already registered',
                    $formFields[self::SECTION_MAIN]
                );
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Return list of the class-specific sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProfileMainSections()
    {
        return array(
            self::SECTION_SUMMARY => static::t('Account summary'),
            self::SECTION_MAIN    => static::t('Email &amp; password'),
            self::SECTION_ACCESS  => static::t('Access information'),
        );
    }

    /**
     * Return error message for the "validateInput" action
     *
     * @param string $login Profile login
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocessErrorActionValidateInput()
    {
        \XLite\Core\TopMessage::addError(
            $this->getErrorActionValidateInputMessage($this->getRequestData('login'))
        );
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocessSuccessActionCreate()
    {
        \XLite\Core\TopMessage::addInfo('Profile has been created successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocessSuccessActionUpdate()
    {
        \XLite\Core\TopMessage::addInfo('Profile has been updated successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocessSuccessActionModify()
    {
        \XLite\Core\TopMessage::addInfo('Profile has been modified successfully');
    }

    /**
     * Perform some actions on success
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocessSuccessActionDelete()
    {
        \XLite\Core\TopMessage::addInfo('Profile has been deleted successfully');
    }

    /**
     * Create profile
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionCreate()
    {
        return $this->checkProfileData() ? parent::performActionCreate() : false;
    }

    /**
     * Update profile
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionUpdate()
    {
        return $this->checkProfileData() ? parent::performActionUpdate() : false;
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionDelete()
    {
        return parent::performActionDelete();
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionValidateInput()
    {
        $result = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findUserWithSameLogin($this->getModelObject());

        return $result;
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubmitButtonLabel()
    {
        return $this->isRegisterMode() ? static::t('Create account') : static::t('Update');
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubmitButtonStyle()
    {
        return 'profile-form';
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();
        $result['submit'] = new \XLite\View\Button\Submit(
            array(
                \XLite\View\Button\AButton::PARAM_LABEL => $this->getSubmitButtonLabel(),
                \XLite\View\Button\AButton::PARAM_STYLE => $this->getSubmitButtonStyle(),
            )
        );

        return $result;
    }
}
