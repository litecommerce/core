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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core;

/**
 * Authorization routine
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sessionVarsToClear = array(
        'profile_id',
        'anonymous',
    );

    /**
     * Updates the specified profile on login. Saves profile to session 
     * 
     * @param \XLite\Model\Profile $profile Profile object
     *  
     * @return bool
     * @access public
     * @since  3.0.0
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
     * User can access profile only in two cases:
     * 1) he/she is an admin
     * 2) its the user's own account
     * 
     * @param \XLite\Model\Profile $profile Profile to check
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkProfileAccessibility(\XLite\Model\Profile $profile)
    {
        return $this->isAdmin($this->getProfile()) || $this->getProfile()->getProfileId() == $profile->getProfileId();
    }

    /**
     * Clear some session variables on logout 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearSessionVars()
    {
        foreach ($this->getSessionVarsToClear() as $name) {
            unset(\XLite\Core\Session::getInstance()->$name);
        }
    }

    /**
     * Add variable to the list of session vars that must be cleared on logoff
     * 
     * @param string $name Session variable name
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function addSessionVarToClear($name)
    {
        $this->sessionVarsToClear[] = $name;
    }

    /**
     * Returns the list of session vars that must be cleared on logoff
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSessionVarsToClear()
    {
        return $this->sessionVarsToClear;
    }

    /**
     * Check if passed string is equal to the hash, previously saved in session.
     * It's the secure mechanism to login using the secret hash (e.g. login anonymous user)
     * 
     * @param string $hashString String to check
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * Encrypts password (calculates MD5 hash)
     * 
     * @param string $password Password string to encrypt
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public static function encryptPassword($password)
    {
        return md5($password);
    }

    /**
     * Logs in user to cart
     * 
     * @param string $login      User's login
     * @param string $password   User's password
     * @param string $secureHash Secret token
     *  
     * @return \XLite\Model\Profile|int
     * @access public
     * @since  3.0.0
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
            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
                ->findByLoginPassword($login, $password, $orderId);

            // Return profile object if it's ok
            if (isset($profile) && $this->loginProfile($profile)) {
                $result = $profile;
            }
        }

        return $result;
    }

    /**
     * Logs off the currently logged profile
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function logoff()
    {
        $session = \XLite\Core\Session::getInstance();
        $session->last_profile_id = $session->profile_id;

        $this->clearSessionVars();
    }

    /**
     * Checks whether user is logged 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isLogged()
    {
        return !is_null($this->getProfile());
    }

    /**
     * Get profile registered in session
     * 
     * @param int $profileId Profile Id
     *  
     * @return \XLite\Model\Profile
     * @access public
     * @since  3.0.0
     */
    public function getProfile($profileId = null)
    {
        $result = null;
        $isCurrent = false;

        if (!isset($profileId)) {
            $profileId = \XLite\Core\Session::getInstance()->profile_id;
            $isCurrent = true;
        }

        if (isset($profileId)) {
            
            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
                ->find($profileId);
            
            if ($isCurrent || $this->checkProfile($profile)) {
                $result = $profile;
            }
        }

        return $result;
    }

    /**
     * Check if passed profile is currently logged in
     * 
     * @param \XLite\Model\Profile $profile profile to check
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkProfile(\XLite\Model\Profile $profile)
    {
        return $this->isLogged() && $this->checkProfileAccessibility($profile);
    }

    /**
     * Checks whether the currently logged user is an administrator
     * 
     * @param \XLite\Model\Profile $profile user profile
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isAdmin(\XLite\Model\Profile $profile)
    {
        return $profile->getAccessLevel() === $this->getAdminAccessLevel();
    }

    /**
     * Return access level for the passed user type
     * 
     * @param string $type Profile type (see getUserTypes() for list of allowed values) 
     *  
     * @return int
     * @access public
     * @since  3.0.0
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
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getAdminAccessLevel()
    {
        return 100;
    }

    /**
     * Gets the access level for a customer
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCustomerAccessLevel()
    {
        return 0;
    }

    /**
     * Returns all user types configured for this system
     * 
     * @return array
     * @access public
     * @since  3.0.0
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
     * @access public
     * @since  3.0.0
     */
    public function getAccessLevelsList()
    {
        return array_map(array($this, 'getAccessLevel'), $this->getUserTypes());
    }

    /**
     * getUserTypesRaw
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getUserTypesRaw()
    {
        return array_combine($this->getAccessLevelsList(), $this->getUserTypes());
    }

    /**
     * Save the secret token in session.
     * See "checkSecureHash()" method
     * 
     * @param string $hashString hash string to save
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setSecureHash($hashString)
    {
        $cell = self::SESSION_SECURE_HASH_CELL;
        \XLite\Core\Session::getInstance()->$cell = $hashString;
    }

    /**
     * Remember login in cookie 
     * 
     * @param mixed $login User's login
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function rememberLogin($login) 
    {
        $options = \XLite::getInstance()->getOptions('host_details');
        $ttl = time() + 86400 * intval($this->config->General->login_lifetime);

        foreach (array($options['http_host'], $options['https_host']) as $host) {
            @setcookie('recent_login', $login, $ttl, '/', func_parse_host($host));
        }
    }

    /**
     * Remind recent login from cookies
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function loginAdministrator($login, $password) 
    {
        $profile = $this->login($login, $password);

        if (
            (is_int($profile) && self::RESULT_ACCESS_DENIED === $profile)
            || ($profile instanceof \XLite\Model\Profile && !$this->isAdmin($profile))
        ) {

            $profile = self::RESULT_ACCESS_DENIED;
            \XLite\Core\Mailer::sendFailedAdminLoginNotification(\XLite\Core\Request::getInstance()->login);

        } else {

            $this->initHtaccessFiles();
        }

        return $profile;
    }

    /**
     * initHtaccessFiles 
     * TODO: need to do either remove this method or refactoring of Htaccess model
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initHtaccessFiles()
    {
        $htaccess = new \XLite\Model\Htaccess();
        if (!$htaccess->hasImage()) {
            $htaccess->makeImage();
        }
    }

    /**
     * Checks whether user has enough permissions to access specified resource.
     * Resource should provide access to "getAccessLevel()" method in order
     * to check authority.
     * 
     * @param \XLite\Base $resource Resource
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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

}

