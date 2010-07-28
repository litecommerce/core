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

namespace XLite\Module\DrupalConnector\View\Model\Profile;

/**
 * \XLite\Module\DrupalConnector\View\Model\Profile\Checkout
 *
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class Checkout extends \XLite\View\Model\Profile\Checkout implements \XLite\Base\IDecorator
{
	/**
     * Comment for the "Username" field
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getUsernameFieldComment()
    {
        return 'Leave this field empty if you do not want to create Drupal account.';
    }

    /**
     * Return description for the "Username" field
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getUsernameField()
    {
        return array(
            'username' => array(
                self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
                self::SCHEMA_LABEL    => 'Username',
                self::SCHEMA_REQUIRED => false,
                self::SCHEMA_COMMENT  => $this->getUsernameFieldComment(),
            ),
        );
    }

    /**
     * Add the "Username" field
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addUsernameField()
    {
        if (!user_is_logged_in() && !\XLite\Model\Auth::getInstance()->isLogged()) {
            $this->mainSchema = $this->getUsernameField() + $this->mainSchema;
        }
    }

	/**
     * Modify form schema to use for Drupal checkout
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareMainSchema()
    {
        unset($this->mainSchema['password']);
        unset($this->mainSchema['password_conf']);

        $this->addUsernameField();
    }

	/**
	 * isDrupalRegistrationNeeded
	 *
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function isDrupalRegistrationNeeded()
    {
        return \XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            && !$this->isAnonymousUser()
            && !user_is_logged_in()
            && !\XLite\Model\Auth::getInstance()->isLogged();
    }

    /**
     * isEmailChangeNeeded
     *
     * @return bool
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isEmailChangeNeeded()
    {
        return \XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            && $this->isPassedEmailDifferent()
            && user_is_logged_in();
    }

	/**
     * Populate LC profile with the data received from Drupal
     *
     * @param stdClass $user just registered Drupal account
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareLCProfile(\stdClass $user)
    {
        $this->getModelObject()->set('cms_profile_id', $user->uid);
        $this->setPasswords($user->password);
    }

	/**
     * Data emulate Drupal form structure
     *
     * @param string $type operation type
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDrupalProfileData($type)
    {
        $data = array(
            'mail' => $this->getRequestData('login'),
        );

        switch ($type) {
            case 'register':
                $data['name'] = $this->getRequestData('username');
                break;

            case 'edit':
                $data['_account'] = user_uid_optional_load();
                break;

            default:
                // ...
        }

        return array('values' => $data);
    }

    /**
     * Perform some actions before start registration process
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function startDrupalProfileModifications()
    {
        // Suppress hooks
        \XLite\Module\DrupalConnector\Handler::getInstance()->disableHooks();
    }

	/**
     * Call the Drupal functions to validate and modify profile
     *
     * @param string $type functions type
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function modifyDrupalProfile($type)
    {
        $data = $this->getDrupalProfileData($type);
        $result = true;

        foreach (array('validate', 'submit') as $suffix) {
            $function = 'user_' . $type . '_' . $suffix;
            $function(array(), $data);

            $result = $result && !form_get_errors();
            if (!$result) {
                break;
            }
        }

        return array($result, $data['user']);
    }

	/**
     * Log into Drupal
     *
     * @param stdClass $user just registered Drupal account
     *
     * @return stdClass|null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function loginDrupalProfile(\stdClass $user)
    {
        return user_authenticate(array('name' => $user->name, 'pass' => $user->password));
    }

    /**
     * Handle occured Drupla errors
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function handleDrupalProfileModificationsErrors()
    {
        $messages = form_get_errors();
        // TODO: add top messages

        return false;
    }

	/**
     * Check if we will proceed to checkout as anonymous user
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isAnonymousUser()
    {
        $result = parent::isAnonymousUser();

        if (\XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            // Do not register Drupal account if the "Username" field is not filled
            $result = $result && ('' == $this->getRequestData('username'));
        }

        return $result;
    }

	/**
     * Create/update user profile during checkout
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionModify()
    {
        if ($this->isDrupalRegistrationNeeded() || $this->isEmailChangeNeeded()) {
            if ($this->performAction('validateInput')) {

                $this->startDrupalProfileModifications();
                if ($this->isDrupalRegistrationNeeded()) {
                    list($result, $user) = $this->modifyDrupalProfile('register');

                    if ($result && $this->loginDrupalProfile($user)) {
                        $this->prepareLCProfile($user);
                    }
                } else {
                    list($result, $user) = $this->modifyDrupalProfile('edit');
                }

                if (!$result) {
                    $this->handleDrupalProfileModificationsErrors();

                    return false;
                }
            }
        }

        return parent::performActionModify();
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
        parent::__construct($params, $sections);

        if (\XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $this->prepareMainSchema();
            $this->formFieldNames[] = $this->composeFieldName('password');
        }
    }
}
