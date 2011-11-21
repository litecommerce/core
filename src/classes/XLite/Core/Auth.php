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

namespace XLite\Core;

/**
 * Authorization routine
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Auth extends \XLite\Base
{
    /**
     * Result codes
     */
    const RESULT_USER_EXISTS        = 1;
    const RESULT_REGISTER_SUCCESS   = 2;
    const RESULT_ACCESS_DENIED      = 3;
    const RESULT_LAST_ADMIN_ACCOUNT = 4;

    /**
     * Session var name to keep the secret token
     */
    const SESSION_SECURE_HASH_CELL = 'secureHashCell';

    /**
     * The list of session vars that must be cleared on logoff
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $sessionVarsToClear = array(
        'profile_id',
        'anonymous',
    );

    /**
     * Profile (cache)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.13
     */
    protected $profile;


    /**
     * Encrypts password (calculates MD5 hash)
     *
     * @param string $password Password string to encrypt
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function encryptPassword($password)
    {
        return md5($password);
    }


    /**
     * Updates the specified profile on login. Saves profile to session
     *
     * @param \XLite\Model\Profile $profile Profile object
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function loginProfile(\XLite\Model\Profile $profile)
    {
        $result = $profile->isPersistent();

        if ($result) {

            // Restart session
            \XLite\Core\Session::getInstance()->restart();

            $loginTime = time();

            // Check for the fisrt time login
            if (!$profile->getFirstLogin()) {
                // Set first login date
                $profile->setFirstLogin($loginTime);
            }

            // Set last login date
            $profile->setLastLogin($loginTime);

            // Update profile
            $profile->update();

            // Save profile Id in session
            \XLite\Core\Session::getInstance()->profile_id = $profile->getProfileId();

            // Save login in cookies
            $this->rememberLogin($profile->getLogin());
        }

        return $result;
    }

    /**
     * Add variable to the list of session vars that must be cleared on logoff
     *
     * @param string $name Session variable name
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addSessionVarToClear($name)
    {
        $this->sessionVarsToClear[] = $name;
    }

    /**
     * Returns the list of session vars that must be cleared on logoff
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSessionVarsToClear()
    {
        return $this->sessionVarsToClear;
    }

    /**
     * Logs in user to cart
     *
     * @param string $login      User's login
     * @param string $password   User's password
     * @param string $secureHash Secret token OPTIONAL
     *
     * @return \XLite\Model\Profile|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function login($login, $password, $secureHash = null)
    {
        $result = self::RESULT_ACCESS_DENIED;

        // Check for the valid parameters
        if (!empty($login) && !empty($password)) {

            if (isset($secureHash)) {

                if (!$this->checkSecureHash($secureHash)) {
                    // TODO - potential attack; send the email to admin
                    $this->doDie('Trying to log in using an invalid secure hash string.');
                }

            } else {
                $password = self::encryptPassword($password);
            }

            // Initialize order Id
            $orderId = \XLite\Core\Request::getInstance()->anonymous
                ? \XLite\Model\Cart::getInstance()->getOrderId()
                : 0;

            // Try to get user profile
            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLoginPassword(
                $login,
                isset($secureHash) ? null : $password,
                $orderId
            );

            // Return profile object if it's ok
            if (isset($profile) && $this->loginProfile($profile)) {
                $result = $profile;

                $orderId = $orderId ?: \XLite\Core\Session::getInstance()->order_id;
                $order = \XLite\Core\Database::getRepo('XLite\Model\Cart')->find($orderId);
                if ($order) {
                    $order->renew();
                }
            }
        }

        // Invalidate cache
        $this->resetProfileCache();

        return $result;
    }

    /**
     * Logs off the currently logged profile
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function logoff()
    {
        $session = \XLite\Core\Session::getInstance();
        $session->last_profile_id = $session->profile_id;

        $this->clearSessionVars();

        // Invalidate cache
        $this->resetProfileCache();
    }

    /**
     * Checks whether user is logged
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isLogged()
    {
        return !is_null($this->getProfile());
    }

    /**
     * Get profile registered in session
     *
     * @param integer $profileId Profile Id OPTIONAL
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProfile($profileId = null)
    {
        if (isset($profileId)) {
            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

            return $this->checkProfile($profile) ? $profile : null;

        } else {

            if (!$this->profile['isInitialized']) {
                $this->resetProfileCache();
                $this->profile['isInitialized'] = true;

                $profileId = $this->getStoredProfileId();

                if (isset($profileId)) {
                    $this->profile['object'] = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);
                }
            }

            return $this->profile['object'];
        }
    }

    /**
     * Check if passed profile is currently logged in
     *
     * @param \XLite\Model\Profile $profile Profile to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkProfile(\XLite\Model\Profile $profile)
    {
        return $this->isLogged() && $this->checkProfileAccessibility($profile);
    }

    /**
     * Checks whether the currently logged user is an administrator
     *
     * @param \XLite\Model\Profile $profile User profile OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAdmin(\XLite\Model\Profile $profile = null)
    {
        if (!isset($profile)) {
            $profile = $this->getProfile();
        }

        return $profile && $profile->getAccessLevel() === $this->getAdminAccessLevel();
    }

    /**
     * Return access level for the passed user type
     *
     * @param string $type Profile type (see getUserTypes() for list of allowed values)
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAccessLevel($type)
    {
        return in_array($type, $this->getUserTypes())
            ? call_user_func(array($this, 'get' . $type . 'Accesslevel'))
            : null;
    }

    /**
     * Gets the access level for administrator
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAdminAccessLevel()
    {
        return 100;
    }

    /**
     * Gets the access level for a customer
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCustomerAccessLevel()
    {
        return 0;
    }

    /**
     * Returns all user types configured for this system
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getUserTypes()
    {
        return array(
            'customer' => 'Customer',
            'admin'    => 'Admin',
        );
    }

    /**
     * Return list of all allowed access level values (by default - array(0, 100))
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAccessLevelsList()
    {
        return array_map(array($this, 'getAccessLevel'), $this->getUserTypes());
    }

    /**
     * getUserTypesRaw
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getUserTypesRaw()
    {
        return array_combine($this->getAccessLevelsList(), $this->getUserTypes());
    }

    /**
     * Save the secret token in session.
     * See "checkSecureHash()" method
     *
     * @param string $hashString Hash string to save
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setSecureHash($hashString)
    {
        $cell = self::SESSION_SECURE_HASH_CELL;
        \XLite\Core\Session::getInstance()->$cell = $hashString;
    }

    /**
     * Remind recent login from cookies
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function remindLogin()
    {
        return isset($_COOKIE['recent_login']) ? $_COOKIE['recent_login'] : '';
    }

    /**
     * Logs in admin to cart.
     *
     * @param string $login    Administrator user login
     * @param string $password Administrator user password
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function loginAdministrator($login, $password)
    {
        $profile = $this->login($login, $password);

        if ($profile instanceof \XLite\Model\Profile && !$profile->isAdmin()) {

            // Logoff user from session
            $this->logoff();

            // Reset profile object
            $profile = self::RESULT_ACCESS_DENIED;

            // Send notification about failed log in attempt
            \XLite\Core\Mailer::sendFailedAdminLoginNotification(\XLite\Core\Request::getInstance()->login);
        }

        return $profile;
    }

    /**
     * Checks whether user has enough permissions to access specified resource.
     * Resource should provide access to "getAccessLevel()" method in order
     * to check authority.
     *
     * @param \XLite\Base $resource Resource
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAuthorized(\XLite\Base $resource)
    {
        // Check whether resource is valid (has getAccessLevel() method)
        if (!method_exists($resource, 'getAccessLevel')) {
            $this->doDie('Auth::isAuthorized(): Authorization failed: resource invalid');
        }

        $profile = $this->getProfile();

        $currentLevel = $profile ? $profile->getAccessLevel() : 0;

        return $currentLevel >= $resource->getAccessLevel();
    }

    /**
     * Reset default values for the "profile" property
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function resetProfileCache()
    {
        $this->profile = array('isInitialized' => false, 'object' => null);
    }

    /**
     * User can access profile only in two cases:
     * 1) he/she is an admin
     * 2) its the user's own account
     *
     * @param \XLite\Model\Profile $profile Profile to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkProfileAccessibility(\XLite\Model\Profile $profile)
    {
        return $this->isAdmin($this->getProfile()) || $this->getProfile()->getProfileId() == $profile->getProfileId();
    }

    /**
     * Clear some session variables on logout
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function clearSessionVars()
    {
        foreach ($this->getSessionVarsToClear() as $name) {
            unset(\XLite\Core\Session::getInstance()->$name);
        }
    }

    /**
     * Check if passed string is equal to the hash, previously saved in session.
     * It's the secure mechanism to login using the secret hash (e.g. login anonymous user)
     *
     * @param string $hashString String to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkSecureHash($hashString)
    {
        $result = false;

        $cell = self::SESSION_SECURE_HASH_CELL;

        if (!empty($hashString)) {
            $result = \XLite\Core\Session::getInstance()->$cell === $hashString;
        }

        // Using this method, it's not possible to log in several times
        unset(\XLite\Core\Session::getInstance()->$cell);

        return $result;
    }

    /**
     * Remember login in cookie
     *
     * @param mixed $login User's login
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function rememberLogin($login)
    {
        $options = \XLite::getInstance()->getOptions('host_details');

        $ttl = time() + 86400 * intval(\XLite\Core\Config::getInstance()->General->login_lifetime);

        foreach (array($options['http_host'], $options['https_host']) as $host) {
            @setcookie('recent_login', $login, $ttl, '/', func_parse_host($host));
        }
    }

    /**
     * Get stored profiel id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getStoredProfileId()
    {
        return \XLite\Core\Session::getInstance()->profile_id;
    }

    /**
     * Protected constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function __construct()
    {
        parent::__construct();

        $this->resetProfileCache();
    }
}
