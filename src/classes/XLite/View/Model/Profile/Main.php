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
 * \XLite\View\Model\Profile\Main
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Main extends \XLite\View\Model\Profile\AProfile
{
    /**
     * Form sections
     */

    const SECTION_MAIN     = 'main';
    const SECTION_ACCESS   = 'access';


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
        return self::getRegisterMode() === \XLite\Core\Request::getInstance()->mode;
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
        return ($this->isRegisterMode() && $checkMode) ?: parent::getProfileId();
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
        return $this->getFieldsBySchema($this->accessSchema);
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
        if (!empty($data['password'])) {
            $data['password'] = \XLite\Core\Auth::encryptPassword($data['password']);

        } elseif (isset($data['password'])) {
            unset($data['password']);
        }

        parent::setModelProperties($data);
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
            self::SECTION_MAIN   => static::t('Personal info'),
            self::SECTION_ACCESS => static::t('Access information'),
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
     * Create profile
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionCreate()
    {
        // FIXME: Uncomment this after the "register" funcion of "\XLite\Core\Auth" class will be refactored
        // return !$this->checkPassword() ?: \XLite\Core\Auth::getInstance()->register($this->getModelObject());

        return $this->checkPassword() ? parent::performActionCreate() : false;
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
        return $this->checkPassword() ? parent::performActionUpdate() : false;
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
        // FIXME: Uncomment this after the "unregister" funcion of "\XLite\Core\Auth" class will be refactored
        // return \XLite\Core\Auth::getInstance()->unregister($this->getModelObject());

        return parent::performActionDelete();
    }

    /**
     * Perform certain action for the model object
     * User can modify only his own profile or create a new one
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performActionValidateInput()
    {
        $result = true;

        // Get profile by login (email)
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
            ->findByLogin($this->getModelObject()->getLogin());

        // Check if found profile is the same as a modified profile object
        if (isset($profile)) {
            $result = $profile->getProfileId() === $this->getModelObject()->getProfileId();
        }

        return $result;
    }
}
