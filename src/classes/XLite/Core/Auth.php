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

    const IP_VALID = 1;
    const IP_INVALID = 0;

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
     * @access protected
     * @since  3.0.0
     */
    protected function loginProfile(\XLite\Model\Profile $profile)
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
            \XLite\Core\Session::getInstance()->set($name, null);
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

        if (!empty($hashString)) {
            $result = \XLite\Core\Session::getInstance()->get(self::SESSION_SECURE_HASH_CELL) === $hashString;
        }

        // Using this method, it's not possible to log in several times
        \XLite\Core\Session::getInstance()->set(self::SESSION_SECURE_HASH_CELL, null);

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
            $orderId = \XLite\Core\Request::getInstance()->anonymous ? \XLite\Model\Cart::getInstance()->getOrderId() : 0;

            // Try to get user profile
            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLoginPassword($login, $password, $orderId);

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
        $session->set('last_profile_id', $session->get('profile_id'));

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
            $profileId = \XLite\Core\Session::getInstance()->get('profile_id');
            $isCurrent = true;
        }

        if (isset($profileId)) {
            
            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);
            
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
        return $profile->getAccessLevel() >= $this->getAdminAccessLevel();
    }

    /**
     * Return access level for the passed user type
     * 
     * @param string $type profile type
     *  
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getAccessLevel($type)
    {
        return in_array($type, $this->getUserTypes()) ? call_user_func(array($this, 'get' . $type . 'Accesslevel')) : null;
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
        return array('customer' => 'Customer', 'admin' => 'Admin');
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
        \XLite\Core\Session::getInstance()->set(self::SESSION_SECURE_HASH_CELL, $hashString);
    }


    function isEmptySippingInfoField(&$properties, $name)
    {
        switch($name) {
            case "shipping_state":
                return ((intval($properties[$name]) <= 0) && empty($properties['shipping_custom_state']));
            default:
                return (empty($properties[$name]));
        }
    }
    
    /**
     * Copies profile billing info into shipping info.
     * TODO: to remove
     *
     * @param Profile $profile The Profile instance
     */
    function copyBillingInfo($profile) 
    {
        $properties = $profile->get('properties');
        if (empty($properties['shipping_firstname'])) {
            $properties['shipping_title'] = "";
        }
        foreach ($properties as $key => $value) {
            $keywords = preg_split('/_/', $key);
            if (isset($keywords[0]) && $keywords[0] == "billing") {
                $k = "shipping_" . $keywords[1];
                if ($this->isEmptySippingInfoField($properties, $k)) {
                     $profile->setComplex($k, $value);
                }
            }
        }
    }

    /**
    * Registers new profile to cart.
    *
    * @param Profile $profile The profile instance to register.
    * @access public
    * @return mixed Result constant
    */
    function register($profile) 
    {
        // check whether the user is registered
        if ($profile->isExists($profile->getLogin())) {
            // user already exists
            return self::RESULT_USER_EXISTS;
        }
        if (!$profile->getStatus()) {
            // enable profile (set status to "E") if not enabled
            $profile->enable();
        }
        if (strlen($profile->getPassword()) > 0) {
            $profile->setPassword(self::encryptPassword($profile->getPassword()));
            $anonymous = false;
        } else {
            $this->setComplex('session.anonymous', true);
            $profile->set('isAnonymous', true);
            $anonymous = true;
        }
        $this->copyBillingInfo($profile);

        // get referer
        if (isset($_SERVER['HTTP_REFERER'])) {
            if (!isset($_COOKIE['LCReferrerCookie'])) {
                $referer = $_SERVER['HTTP_REFERER'];
                setcookie('LCReferrerCookie', $referer, time() + 3600 * 24 * 180, "/", \XLite::getInstance()->getOptions(array('host_details', 'http_host')));
            } else {
                $referer = $_COOKIE['LCReferrerCookie'];
            }
            // save referer
            $profile->setReferer($referer);
        }
        // create profile
        $profile->create();
        if (!$anonymous) {

            // send signin mail notification to regular customer
            $mailer = new \XLite\Model\Mailer();

            // pass this data to the mailer
            $mailer->profile = $profile;
            $mailer->set('charset', $this->xlite->config->Company->locationCountry->charset);
            $mailer->compose(
                $this->config->Company->site_administrator,
                $profile->getLogin(),
                'signin_notification'
            );
            $mailer->send();

            // send new profile signin notification to admin
            $mailer->compose(
                $this->config->Company->site_administrator,
                $this->config->Company->users_department,
                'signin_admin_notification'
            );
            $mailer->send();
        }

        return self::RESULT_REGISTER_SUCCESS;
    }

    /**
    * Modifies the specified profile data.
    *
    * @access public
    * @param Profile $profile The profile instance
    * @return mixed Result constant
    */
    function modify($profile) 
    {
        // check whether another user exists with the same login
        if (\XLite\Core\Database::getRepo('XLite\Model\Profile')->findUserWithSameLogin($profile)) {
            return self::RESULT_USER_EXISTS;
        }

        if ($this->session->get('anonymous')) {
            $this->clearAnonymousPassword($profile);
        
        } else {
            // TODO: recheck it
            $another = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profile->getProfileId());
            
            if (strlen($another->getPassword()) == 0) {
                $this->clearAnonymousPassword($profile);
            }
        }
        if (strlen($profile->getPassword()) > 0) {
            $profile->setPassword(self::encryptPassword($profile->getPassword()));
        } else {
            $this->clearAnonymousPassword($profile);
        }

        // fill in shipping info
        $this->copyBillingInfo($profile);

        // update current shopping cart/order data
        $cartProfile = \XLite\Model\Cart::getInstance()->getProfile();
        if ($cartProfile->getOrderId()) {
            $cartProfile->modifyCustomerProperties(\XLite\Core\Request::getInstance()->getData());
            $this->copyBillingInfo($cartProfile);
            $cartProfile->update();
        }

        // modify and update profile
        $profile->update();

        // check/confirm membership signup
        $this->membershipSignup($profile);

        // send mail notification to customer
        $mailer = new \XLite\Model\Mailer();
        $mailer->set('profile', $profile);
        $mailer->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mailer->compose(
            $this->config->Company->users_department,
            $profile->getLogin(),
            'profile_modified'
        );
        $mailer->send();
        // notify administration devision (users department)
        $mailer->compose(
            $this->config->Company->site_administrator,
            $this->config->Company->users_department,
            'profile_admin_modified'
        );
        $mailer->send();
        
        return self::RESULT_REGISTER_SUCCESS;
    }

    function clearAnonymousPassword($profile)
    {
        $profile->setPassword(null);
        if (isset($_REQUEST['password'])) {
            unset($_REQUEST['password']);
        }
    }

    /**
    * Handles membership signup.
    */
    function membershipSignup($profile) 
    {
        // membership signup requested 
    }

    /**
    * Checks whtether the only one available and enabled admin exists in cart.
    * 
    * @access public
    * @return boolean
    */
    function isLastAdmin($profile) 
    {
        // check whether this admin profile is not the latest available
        $where = "access_level >= %d AND status = 'E'";
        $where = sprintf($where, $this->getAdminAccessLevel());
        $found = count($profile->findAll($where));
        if ($found == 1) {
            return true;
        }
        return false;
    }

    /**
    * Deletes and unregisters the profile in cart.
    *
    * @access public
    * @param Profile $profile The profile instance to unregister
    */
    function unregister($profile) 
    {
        // read profile data
        $profile->read();
        // get current profile from session
        $current = $this->getComplex('session.profile_id');
        if ($current == $profile->getProfileId()) {
            // log off first
            $this->logoff();
        }

        // send mail notification about deleted profile to customer
        $mailer = new \XLite\Model\Mailer();
        $mailer->set('profile', $profile);
        $mailer->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mailer->compose(
            $this->config->Company->users_department,
            $profile->getLogin(),
            'profile_deleted'
        );
        $mailer->send();

        // send mail notification about deleted profile to admin
        $mailer->compose(
            $this->config->Company->site_administrator,
            $this->config->Company->users_department,
            'profile_admin_deleted'
        );
        $mailer->send();
        // delete profile data
        $profile->delete();
    }

    /**
     * Remember login $login in cookie 
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
        $ttl = time() + 3600 * 24 * intval($this->config->General->login_lifetime);

        foreach (array($options['http_host'], $options['https_host']) as $host) {
            @setcookie('recent_login', $login, $ttl, '/', func_parse_host($host));
        }
    }

    /**
    * Remind last login for this host
    */
    function remindLogin() 
    {
        return isset($_COOKIE['recent_login']) ? $_COOKIE['recent_login'] : "";
    }

    /**
    * Logs in admin to cart.
    *
    * @access public
    * @param string $login The admin"s login
    * @param string $password The admin"s password
    */
    function adminLogin($login, $password) 
    {
        $profile = $this->login($login, $password);

        if (
            (is_int($profile) && self::RESULT_ACCESS_DENIED === $profile)
            || ($profile instanceof Profile && !$this->isAdmin($profile))
        ) {

            $this->sendFailedAdminLogin($profile);

        } else {

            $this->initHtaccessFiles();
        }

        return $profile;
    }

    function initHtaccessFiles()
    {
        $htaccess = new \XLite\Model\Htaccess();
        if (!$htaccess->hasImage()){
            $htaccess->makeImage();
        }
    }

    /**
    * Sends failed administration login notification.
    *
    * @param Profile $profile The profile instance
    */
    function sendFailedAdminLogin($profile) 
    {
        // send mail notification about failed login to administrator
        $mailer = new \XLite\Model\Mailer();
        $mailer->set('login', isset($_POST['login']) ? $_POST['login'] : "unknown");
        $mailer->set('REMOTE_ADDR', isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "unknown");
        $mailer->set('HTTP_X_FORWARDED_FOR', isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "unknown");
        $mailer->set('charset', $this->xlite->config->Company->locationCountry->charset);
        $mailer->compose(
            $this->config->Company->site_administrator,
            $this->config->Company->site_administrator,
            'login_error'
        );
        $mailer->send();
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
        // check whether resource is valid (has getAccessLevel() method)
        if (!method_exists($resource, 'getAccessLevel'))
        {
            $this->doDie('Auth::isAuthorized(): Authorization failed: resource invalid');
        }

        $profile = $this->getProfile();

        $currentLevel = $profile ? $profile->getAccessLevel() : 0;

        return $currentLevel >= $resource->getAccessLevel();
    }

    public function isValidAdminIP(\XLite\Base $resource, $checkOnly = false)
    {
        $ip_v4_regexp_wildcard = '/^(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)$/';

        $ip_v4_regexp = '/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';

        $admin_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        if (!preg_match($ip_v4_regexp, $admin_ip, $admin_ip_bytes)) {
            return self::IP_INVALID;
        }

        $valid_ips = $this->config->SecurityIP->allow_admin_ip;

        if (
            (!is_array($valid_ips) || 1 > count($valid_ips))
            && !$checkOnly
        ) {
            $admin_ip = serialize(array(array('ip' => $admin_ip, 'comment' => 'Default admin IP')));

            \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                array(
                    'category' => 'SecurityIP',
                    'name'     => 'allow_admin_ip',
                    'value'    => $admin_ip,
                    'type'     => 'serialized'
                )
            );

        } else {

            $is_valid = false;

            if (is_array($valid_ips)) {
                foreach ($valid_ips as $ip_array){
                    $ip = $ip_array['ip'];
                    if (!preg_match($ip_v4_regexp_wildcard, $ip, $ip_bytes)) {
                        break;
                    }

                    $allow = true;
                    for ($i = 1; $i <= 4; $i++) {
                        if ($ip_bytes[$i] != "*" && ($ip_bytes[$i] != $admin_ip_bytes[$i])) {
                            $allow = false;
                            break;
                        }
                    }

                    if ($allow){
                        $is_valid = true;
                        break;
                    }
                }
            }

            if (!$is_valid) {
                if (!$checkOnly) {
                    $waiting_list = new \XLite\Model\WaitingIP();
                    preg_match($ip_v4_regexp,$admin_ip, $admin_ip_bytes);
                    $admin_ip = isset($admin_ip_bytes[0]) ? $admin_ip_bytes[0] : '';

                    if (!$waiting_list->find('ip = \'' . $admin_ip . '\'')) {
                        $admin_ip = $admin_ip_bytes[0];
                        $waiting_list->addNew($admin_ip);
                        $waiting_list->notifyAdmin();

                    } else {
                        if ($waiting_list->canNotify()){
                            $waiting_list->notifyAdmin();
                        }
                        $waiting_list->set('count', intval($waiting_list->get('count') + 1));
                        $waiting_list->set('last_date', time());
                        $waiting_list->update();
                    }

                    // logoff action
                    $this->logoff();
                    unset($this->session->sidebar_box_statuses);
                }

                return self::IP_INVALID;
            }
        }

        return self::IP_VALID;
    } //}}}

    function requestRecoverPassword($email) 
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin($email);
        
        if (!isset($profile)) {
            return false;
        }
        
        $mailer = new \XLite\Model\Mailer();
        
        $mailer->url = $this->xlite->getShopUrl(
            'cart.php?target=recover_password&action=confirm&email=' . 
            urlencode($profile->getLogin()) . 
            '&request_id=' . 
            $profile->getPassword()
        );
        
        $mailer->set('charset', $this->xlite->config->Company->locationCountry->charset);
        
        $mailer->compose(
            $this->config->Company->users_department,
            $profile->getLogin(),
            'recover_request'
        );

        $mailer->send();

        return true;
    }
    
    function recoverPassword($email, $requestID) 
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin($email);
        
        if (!isset($profile) || $profile->getPassword() != $requestID) {
            return false;
        }

        $pass = generate_code();
        $mailer = new \XLite\Model\Mailer();
        $mailer->set('email', $email);
        $mailer->set('new_password', $pass);
        $profile->setPassword(md5($pass));
        $profile->update();
        $mailer->set('profile', $profile);
        $mailer->set('charset', $profile->getComplex('billingCountry.charset')); // TODO: replace to language.charset
        $mailer->compose(
                $this->config->Company->users_department,
                $profile->getLogin(),
                "recover_recover"
                );
        $mailer->send();
        return true;
    }

}

