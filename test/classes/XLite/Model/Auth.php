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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

define('USER_EXISTS', 1);
define('REGISTER_SUCCESS', 2);
define('ACCESS_DENIED', 3);
define('LAST_ADMIN_ACCOUNT', 4);

global $_reReadProfiles;
$_reReadProfiles = false;

/**
 * Authorization routine
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Auth extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Integer codes for action results
     */

    const RESULT_ACCESS_DENIED = ACCESS_DENIED;


    const IP_VALID = 1;
    const IP_INVALID = 0;


    /**
     * These session vars will be cleared on logoff
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sessionVarsToClear = array(
        'profile_id',
        'anonymous',
        // Uncomment if needed
        // 'advertise_show',
    );

    /**
     * Updates the specified profile on login. Saves profile to session 
     * 
     * @param XLite_Model_Profile $profile profile object
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function loginProfile(XLite_Model_Profile $profile)
    {
        if ($result = $profile->isPersistent) {

            $this->sessionRestart();

            // check for the fisrt time login
            if (!$profile->get('first_login')) {
                // set first login date
                $profile->set('first_login', time());
            }
            // set last login date
            $profile->set('last_login', time());

            // update profile
            $profile->update();

            // save to session
            $this->session->set('profile_id', $profile->get('profile_id'));

            $this->rememberLogin($profile->get('login'));
        }

        return $result;
    }


    /**
     * getInstance 
     * 
     * @return XLite_Model_Auth
     * @access public
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Encrypts password (calculates MD5 hash)
     * 
     * @param string $password password to encrypt
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
     * @param string $login       user's login
     * @param string $password    user's password
     * @param bool   $isEncrypted is password already encrypted
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function login($login, $password, $isEncrypted = false)
    {
        // Check for the valid parameters
        if (!empty($login) && !empty($password)) {

            if (!$isEncrypted) {
                $password = self::encryptPassword($password);
            }

            $profile = new XLite_Model_Profile();

            // Deny login if user not found
            if ($profile->findByLogin($login, $password)) {
                $this->loginProfile($profile);

                return $profile;
            }
        }

        return self::RESULT_ACCESS_DENIED;
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
        $session = XLite_Model_Session::getInstance();
        $session->set('last_profile_id', $session->get('profile_id'));

        foreach ($this->sessionVarsToClear as $name) {
            $session->set($name, null);
        }
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
     * Get profile 
     * 
     * @param int $profileId internal profile ID
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function getProfile($profileId = null)
    {
        $result = null;

        if (!isset($profileId)) {
            $profileId = XLite_Model_Session::getInstance()->get('profile_id');
        }

        if (isset($profileId)) {
            $profile = XLite_Model_CachingFactory::getObject(__METHOD__ . $profileId, 'XLite_Model_Profile', array($profileId));
            if ($profile->isValid()) {
                $result = $profile;
            }
        }

        return $result;
    }

    /**
     * Check if passed profile is currently logged in
     * 
     * @param XLite_Model_Profile $profile profile to check
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkProfile(XLite_Model_Profile $profile)
    {
        return $this->isLogged()
            && (
                $this->isAdmin($this->getProfile())
                || $this->getProfile()->get('profile_id') == $profile->get('profile_id')
            );
    }

    /**
     * Add variable to the "clear on logoff" list
     * 
     * @param string $name session variable name
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
     * Checks whether the currently logged user is an administrator
     * 
     * @param XLite_Model_Profile $profile user profile
     *  
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isAdmin(XLite_Model_Profile $profile)
    {
        return $profile->get('access_level') >= $this->getAdminAccessLevel();
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







    function _reReadProfiles($newValue = null)  // {{{
    {
        global $_reReadProfiles;
        if (is_null($newValue)) {
            return $_reReadProfiles;
        }
        $_reReadProfiles = $newValue;
    } // }}}

    function isEmptySippingInfoField(&$properties, $name)
    {
        switch($name) {
            case "shipping_state":
                return ((intval($properties[$name]) <= 0) && empty($properties["shipping_custom_state"]));
            default:
                return (empty($properties[$name]));
        }
    }
    
    /**
    * Copies profile billing info into shipping info.
    *
    * @param Profile $profile The Profile instance
    */
    function copyBillingInfo($profile) // {{{
    {
        $properties = $profile->get("properties");
        if (empty($properties["shipping_firstname"])) {
            $properties["shipping_title"] = "";
        }
        foreach ($properties as $key => $value) {
            $keywords = preg_split("/_/", $key);
            if (isset($keywords[0]) && $keywords[0] == "billing") {
                $k = "shipping_" . $keywords[1];
                if ($this->isEmptySippingInfoField($properties, $k)) {
                     $profile->setComplex($k, $value);
                }
            }
        }    
    } // }}}

    /**
    * Registers new profile to cart.
    *
    * @param Profile $profile The profile instance to register.
    * @access public
    * @return mixed Result constant
    */
    function register($profile) // {{{
    {
        // check whether the user is registered
        if ($profile->isExists($profile->get("login"))) {
            // user already exists
            return USER_EXISTS;
        }
        if (!$profile->get("status")) {
            // enable profile (set status to "E") if not enabled
            $profile->enable();
        }
        if (strlen($profile->get("password")) > 0) {
            $profile->set("password", 
            self::encryptPassword($profile->get("password")));
            $anonymous = false;
        } else {
            $this->setComplex("session.anonymous", true);
            $profile->set("isAnonymous", true);
            $anonymous = true;
        }
        $this->copyBillingInfo($profile);

        // get referer
        if (isset($_SERVER["HTTP_REFERER"])) {
            if (!isset($_COOKIE["LCReferrerCookie"])) {
                $referer = $_SERVER["HTTP_REFERER"];
                setcookie("LCReferrerCookie", $referer, time() + 3600 * 24 * 180, "/", XLite::getInstance()->getOptions(array('host_details', 'http_host')));
            } else {
                $referer = $_COOKIE["LCReferrerCookie"];
            }
            // save referer
            $profile->set("referer", $referer);
        }
        // create profile
        $profile->create();
        if (!$anonymous) {
            // send signin mail notification to regular customer
            $mailer = new XLite_Model_Mailer();
            // pass this data to the mailer
            $mailer->profile = $profile; 
            $mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
            $mailer->compose($this->getComplex('config.Company.site_administrator'),
                             $profile->get("login"),
                             "signin_notification"
                             );
            $mailer->send();
            // send new profile signin notification to admin
            $mailer->compose($this->getComplex('config.Company.site_administrator'),
                             $this->getComplex('config.Company.users_department'),
                             "signin_admin_notification"
                             );
            $mailer->send();
        }

        return REGISTER_SUCCESS;
    } // }}}

    /**
    * Modifies the specified profile data.
    *
    * @access public
    * @param Profile $profile The profile instance
    * @return mixed Result constant
    */
    function modify($profile) // {{{
    {
        // check whether another user exists with the same login
        $another = new XLite_Model_Profile();
        $login = addslashes($profile->get("login"));
        $profile_id = $profile->get("profile_id");
        if ($another->find("login='$login' AND profile_id!='$profile_id'")) {
            return USER_EXISTS;
        }
        if ($this->session->get("anonymous")) {
            $this->clearAnonymousPassword($profile);
        } else {
            $another = new XLite_Model_Profile($profile->get("profile_id"));
            if (strlen($another->get("password")) == 0) {
                $this->clearAnonymousPassword($profile);
            }
        }
        if (strlen($profile->get("password")) > 0) {
            $profile->set("password", 
            self::encryptPassword($profile->get("password")));
        } else {
            $this->clearAnonymousPassword($profile);
        }

        // fill in shipping info
        $this->copyBillingInfo($profile);

        // update current shopping cart/order data
        $cartProfile = XLite_Model_Cart::getInstance()->getProfile();
        if ($cartProfile->get('order_id')) {
            $cartProfile->modifyProperties(XLite_Core_Request::getInstance()->getData());
            $this->copyBillingInfo($cartProfile);
            $cartProfile->update();
        }

        // modify and update profile
        $profile->update();

        // check/confirm membership signup
        $this->membershipSignup($profile);

        // send mail notification to customer
        $mailer = new XLite_Model_Mailer();
        $mailer->set("profile", $profile);
        $mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mailer->compose(
                $this->getComplex('config.Company.users_department'),
                $profile->get("login"),
                "profile_modified"
                );
        $mailer->send();
        // notify administration devision (users department)
        $mailer->compose(
                $this->getComplex('config.Company.site_administrator'),
                $this->getComplex('config.Company.users_department'),
                "profile_admin_modified"
                );
        $mailer->send();
        
        return REGISTER_SUCCESS;
    } // }}}

    function clearAnonymousPassword($profile)
    {
        $profile->set("password", null);
        if (isset($_REQUEST["password"])) {
            unset($_REQUEST["password"]);
        }
    }

    /**
    * Handles membership signup.
    */
    function membershipSignup($profile) // {{{
    {
        // membership signup requested 
    } // }}}

    /**
    * Checks whtether the only one available and enabled admin exists in cart.
    * 
    * @access public
    * @return boolean
    */
    function isLastAdmin($profile) // {{{
    {
        // check whether this admin profile is not the latest available
        $where = "access_level >= %d AND status = 'E'";
        $where = sprintf($where, $this->getAdminAccessLevel());
        $found = count($profile->findAll($where));
        if ($found == 1) {
            return true;
        }
        return false;
    } // }}}

    /**
    * Deletes and unregisters the profile in cart.
    *
    * @access public
    * @param Profile $profile The profile instance to unregister
    */
    function unregister($profile) // {{{
    {
        // read profile data
        $profile->read();
        // get current profile from session
        $current = $this->getComplex('session.profile_id');
        if ($current == $profile->get("profile_id")) {
            // log off first
            $this->logoff();
        }
        // send mail notification about deleted profile to customer
        $mailer = new XLite_Model_Mailer();
        $mailer->set("profile", $profile);
        $mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mailer->compose(
                $this->getComplex('config.Company.users_department'),
                $profile->get("login"),
                "profile_deleted"
                );
        $mailer->send();
        // send mail notification about deleted profile to admin
        $mailer->compose(
                $this->getComplex('config.Company.site_administrator'),
                $this->getComplex('config.Company.users_department'),
                "profile_admin_deleted"
                );
        $mailer->send();        
        // delete profile data
        $profile->delete();
    } // }}}

    /**
    * Remember login $login in cookie
    */
    function rememberLogin($login) // {{{
    {
        $options = XLite::getInstance()->getOptions('host_details');

        foreach (array($options['http_host'], $options['https_host']) as $host) {
            @setcookie('last_login', $login, time() + 3600 * 24 * $this->config->General->login_lifetime, '/', func_parse_host($host));
        }
    } // }}}

    /**
    * Remind last login for this host
    */
    function remindLogin() // {{{
    {
        return isset($_COOKIE["last_login"]) ? $_COOKIE["last_login"] : "";
    } // }}}

    /**
     * Session restart after log-in
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function sessionRestart()
    {
        if (
            isset($_REQUEST[XLite_Model_Session::SESSION_DEFAULT_NAME])
            && !(isset($_GET[XLite_Model_Session::SESSION_DEFAULT_NAME]) || isset($_POST[XLite_Model_Session::SESSION_DEFAULT_NAME]))
        ) {

            unset($_REQUEST[XLite_Model_Session::SESSION_DEFAULT_NAME]);
            $this->xlite->session->set(
                '_' . XLite_Model_Session::SESSION_DEFAULT_NAME,
                XLite_Model_Session::SESSION_DEFAULT_NAME . '=' . $this->xlite->session->getID()
            );
            $this->xlite->session->destroy();
            $this->xlite->session->setID(SESSION_DEFAULT_ID);
            $this->xlite->session->_initialize();
        }
    }

    /**
    * Logs in admin to cart.
    *
    * @access public
    * @param string $login The admin"s login
    * @param string $password The admin"s password
    */
    function adminLogin($login, $password) // {{{
    {
        $profile = $this->login($login, $password);

        if (
            (is_int($profile) && ACCESS_DENIED === $profile)
            || ($profile instanceof Profile && !$profile->is("admin"))
        ) {

            $this->sendFailedAdminLogin($profile);

        } else {

            $this->initHtaccessFiles();
        }

        return $profile; 
    } // }}}

    function initHtaccessFiles()
    {
        $htaccess = new XLite_Model_Htaccess();
        if(!$htaccess->hasImage()){
            $htaccess->makeImage();
        }
    }

    /**
    * Sends failed administration login notification.
    *
    * @param Profile $profile The profile instance
    */
    function sendFailedAdminLogin($profile) // {{{
    {
        // send mail notification about failed login to administrator
        $mailer = new XLite_Model_Mailer();
        $mailer->set("login", isset($_POST["login"]) ? $_POST["login"] : "unknown");
        $mailer->set("REMOTE_ADDR", isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "unknown");
        $mailer->set("HTTP_X_FORWARDED_FOR", isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : "unknown");
        $mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mailer->compose(
                            $this->getComplex('config.Company.site_administrator'),
                            $this->getComplex('config.Company.site_administrator'),
                            "login_error"
                            );
        $mailer->send();
    } // }}}

    /**
     * Checks whether user has enough permissions to access specified resource.
     * Resource should provide access to "getAccessLevel()" method in order
     * to check authority.
     * 
     * @param XLite_Base $resource Resource
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAuthorized(XLite_Base $resource)
    {
        // check whether resource is valid (has getAccessLevel() method)
        if (!method_exists($resource, 'getAccessLevel'))
        {
            $this->doDie('Auth::isAuthorized(): Authorization failed: resource invalid');
        }

        $profile = $this->getProfile();

        $currentLevel = $profile ? $profile->get('access_level') : 0;

        return $currentLevel >= $resource->getAccessLevel();
    }

    public function isValidAdminIP(XLite_Base $resource, $checkOnly = false)
    {
        $ip_v4_regexp_wildcard = '/^(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)$/';

        $ip_v4_regexp = '/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';

        $admin_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        if(!preg_match($ip_v4_regexp, $admin_ip, $admin_ip_bytes)) {
            return self::IP_INVALID;
        }

        $valid_ips = $this->config->SecurityIP->allow_admin_ip;

        if (
            (!is_array($valid_ips) || 1 > count($valid_ips))
            && !$checkOnly
        ) {
            $valid_ips_object = new XLite_Model_Config();
            $admin_ip = serialize(array(array('ip' => $admin_ip, 'comment' => 'Default admin IP')));

            if ($valid_ips_object->find('category = \'SecurityIP\' AND name = \'allow_admin_ip\'')) {
                $valid_ips_object->set('value', $admin_ip);
                $valid_ips_object->set('type', 'serialized');
                $valid_ips_object->update();

            } else {
                $valid_ips_object->createOption('SecurityIP', 'allow_admin_ip', $admin_ip, 'serialized');
            }

        } else {

            $is_valid = false;

            if (is_array($valid_ips)) {
                foreach ($valid_list as $ip_array){
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
                    $waiting_list = new XLite_Model_WaitingIP();
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
                    $this->session->set('sidebar_box_statuses', null);
                    $this->session->writeClose();
                }

                return self::IP_INVALID;
            }
        }

        return self::IP_VALID;
    } //}}}

    function requestRecoverPassword($email) // {{{
    {
        $profile = new XLite_Model_Profile();
        if (!$profile->find("login='$email'")) {
            return false;
        }
        $mailer = new XLite_Model_Mailer();
        $mailer->url = $this->xlite->getShopUrl("cart.php?target=recover_password&action=confirm&email=".urlencode($profile->get("login"))."&request_id=".$profile->get("password"));
        $mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mailer->compose($this->config->getComplex('Company.users_department'),
                         $profile->get("login"),
                         "recover_request"
                         );
        $mailer->send();
        return true;
    } // }}}
    
    function recoverPassword($email, $requestID) // {{{
    {
        $profile = new XLite_Model_Profile();
        if (!$profile->find("login='$email'") || $profile->get("password") != $requestID) {
            return false;
        }

        $pass = generate_code();
        $mailer = new XLite_Model_Mailer();
        $mailer->set("email", $email);
        $mailer->set("new_password", $pass);
        $profile->set("password", md5($pass));
        $profile->update();
        $mailer->set("profile", $profile);
        $mailer->set("charset", $profile->getComplex('billingCountry.charset'));
        $mailer->compose(
                $this->getComplex('config.Company.users_department'),
                $profile->get("login"),
                "recover_recover"
                );
        $mailer->send();
        return true;
    } // }}}
}
