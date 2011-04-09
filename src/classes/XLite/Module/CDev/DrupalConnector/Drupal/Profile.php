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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Profile 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class Profile extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    // ------------------------------ Ancillary methods -

    /**
     * Check if current page is the "Reset password" one
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isResetPasswordPage()
    {
        $arg = arg();

        // 0, 1 - Drupal path
        // 3 - profile creation time (timestamp)
        // 4 - secret hash
        // 5 - current action
        foreach (array(0 => 'path', 1 => 'page', 3 => 'time', 4 => 'hash', 5 => 'action') as $index => $name) {
            $$name = isset($arg[$index]) ? $arg[$index] : null;
        }

        return array('user' === $path && 'reset' === $page && 'login' === $action, $time, $hash);
    }

    /**
     * Prepare main data for the LC profile
     *
     * @param \stdClass  $user            Drupal user profile
     * @param array|null $edit            data from request
     * @param bool       $addConfirmation flag; add password confirmation field or not
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProfileData(\stdClass $user, $edit, $addConfirmation = true)
    {
        $data   = array();
        $fields = array(
            'login'          => 'mail',
            'password'       => 'pass',
            'cms_profile_id' => 'uid',
        );

        $values = (is_array($edit) && isset($edit['values'])) ? $edit['values'] : array();

        foreach ($fields as $lcKey => $drupalKey) {
            // Only use data from user profile if they do not passed in request
            $data[$lcKey] = isset($values[$drupalKey]) ? $values[$drupalKey] : $user->$drupalKey;
        }

        // Initialize flag when new user is created
        $data['createNewUser'] = !isset($user->original);

        // Prepare data for access_level field
        $roles = (isset($edit['roles']) ? $edit['roles'] : (isset($user->roles) ? $user->roles : null));

        if (isset($roles)) {

            $data['access_level'] = $this->isRoleHasAdminPermission($roles)
                ? \XLite\Core\Auth::getInstance()->getAdminAccessLevel()
                : \XLite\Core\Auth::getInstance()->getCustomerAccessLevel();

            // Save drupal roles for furhter processing
            foreach ($roles as $key => $value) {
                if (!empty($value) && '0' !== $value) {
                    $data['drupal_roles'][] = $key;
                }
            }
        }

        // Prepare data for 'status' field
        if (isset($edit['status'])) {
            $data['status'] = (1 === intval($edit['status']) ? 'E' : 'D');
        }

        // Skip password on update action if it wasn't changed
        if (isset($data['password'])) {

            if (
                $addConfirmation 
                && isset($user->pass) 
                && isset($user->original) 
                && isset($user->original->pass) 
                && 0 === strcmp($user->pass, $user->original->pass)
            ) {
                unset($data['password']);
            }
        }

        if (!empty($user->passwd)) {

            // If $user->passwd is set then user updates his password
            $data['password'] = $user->passwd;
        }

        if ($addConfirmation && isset($data['password'])) {
            $data['password_conf'] = $data['password'];
        }

        return $data;
    }

    /**
     * Checks if user has Drupal's role with LiteCommerce administrator permissions
     * 
     * @param array $roles Array of user's roles in Drupal
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isRoleHasAdminPermission(array $roles)
    {
        $role_permissions = user_role_permissions($roles);

        $found = false;

        foreach ($role_permissions as $rid => $perms) {

            $found = isset($perms['lc admin']);

            if ($found) {
                break;
            }
        }

        return $found;
    }

    /**
     * Prepare data for the \XLite\Controller\Customer\Login
     *
     * @param \stdClass  $user Drupal user profile
     * @param array|null $edit data from request
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProfileDataLogin(\stdClass $user, $edit)
    {
        $data = $this->getProfileData($user, $edit, false);

        // On the "Reset password" page user can log in without entering a password.
        // It's the reason to introduce the "log in using secret token" approach
        list($result, $timestamp, $hash) = $this->isResetPasswordPage();

        // Only start LC log in procedure after Drupal hash string is checked
        if ($result && user_pass_rehash($data['password'], $timestamp, 0) === $hash) {
            $token = \XLite\Core\Converter::generateRandomToken();

            // Save token in session and pass it to LC controller. Strings must match
            $data[\XLite\Controller\Customer\Login::SECURE_TOKEN] = $token;
            \XLite\Core\Auth::getInstance()->setSecureHash($token);
        }

        return $data;
    }


    // ------------------------------ Action handlers -

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionPresave(array &$edit, \stdClass $account, $category)
    {
        return $this->runController('profile', 'validate', $this->getProfileData($account, $edit));
    }

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionInsert(array &$edit, \stdClass $account, $category)
    {
        return $this->runController('profile', 'register_basic', $this->getProfileData($account, $edit));
    }

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionUpdate(array &$edit, \stdClass $account, $category)
    {
        return $this->runController('profile', 'update_basic', $this->getProfileData($account, $edit));
    }

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionCancel(array &$edit, \stdClass $account, $method)
    {
        return $this->runController('profile', 'cancel', $this->getProfileData($account, $edit));
    }

    /**
     * Handler for certain action
     *
     * @param array     $uid User profile Id on Drupal side
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionDelete(array &$edit, \stdClass $account)
    {
        return $this->runController('profile', 'delete', $this->getProfileData($account, array()));
    }

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionLogin(array &$edit, \stdClass $account, $category)
    {
        return $this->runController('login', 'login', $this->getProfileDataLogin($account, $edit));
    }

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionLogout(array &$edit, \stdClass $account, $category)
    {
        return $this->runController('login', 'logoff');
    }

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionUpdateRoles($roles)
    {
        return $this->runController('profile', 'update_roles', array('roles' => $roles));
    }

    /**
     * Handler for certain action
     *
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performActionDeleteRole($roles)
    {
        return $this->runController('profile', 'delete_role', array('roles' => $roles));
    }

    /**
     * Common method to call action handlers
     *
     * @param string    $action   Action to perform
     * @param array     $edit     The array of form values submitted by the user
     * @param \stdClass $account  The user object on which the operation is performed
     * @param mixed     $category The active category of user information being edited
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function performAction($action, array &$edit, \stdClass $account, $category)
    {
        return call_user_func_array(array($this, __FUNCTION__ . ucfirst($action)), array(&$edit, $account, $category));
    }
}
