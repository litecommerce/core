<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

define("USER_EXISTS", 1);
define("REGISTER_SUCCESS", 2);
define("ACCESS_DENIED", 3);
define("LAST_ADMIN_ACCOUNT", 4);
define("IP_VALID", 1);
define("IP_INVALID", 0);

global $_reReadProfiles;
$_reReadProfiles = false;

/**
* Authentication buisness logic (register, login/logoff). 
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Auth extends XLite_Base_Singleton
{
	/**
     * Return pointer to the single instance of current class
     *
     * @param string $className name of derived class
     *
     * @return XLite_Base_Singleton
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getInstance($className = __CLASS__)
    {
        return parent::getInstance(__CLASS__);
    }

    function _reReadProfiles($newValue = null)  // {{{
    {
        global $_reReadProfiles;
        if (is_null($newValue)) {
            return $_reReadProfiles;
        }
        $_reReadProfiles = $newValue;
    } // }}}

    /**
    * Encrypts password (calculates MD5 hash).
    *
    * @param string $password
    * @return string MD5 hash
    */
    function encryptPassword($password) // {{{
    {
        return md5($password);
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
    function copyBillingInfo(&$profile) // {{{
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
                     $profile->set($k, $value);
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
    function register(&$profile) // {{{
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
            $this->encryptPassword($profile->get("password")));
            $anonymous = false;
        } else {
            $this->set("session.anonymous", true);
            $profile->set("isAnonymous", true);
            $anonymous = true;
        }
        $this->copyBillingInfo($profile);

        // get referer
        if (isset($_SERVER["HTTP_REFERER"])) {
            if (!isset($_COOKIE["LCReferrerCookie"])) {
                global $options;

            	$referer = $_SERVER["HTTP_REFERER"];
                setcookie("LCReferrerCookie", $referer, time() + 3600 * 24 * 180, "/", $options["host_details"]["http_host"]);
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
            $mailer->compose($this->get("config.Company.site_administrator"),
                             $profile->get("login"),
                             "signin_notification"
                             );
            $mailer->send();
            // send new profile signin notification to admin
            $mailer->compose($this->get("config.Company.site_administrator"),
                             $this->get("config.Company.users_department"),
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
    function modify(&$profile) // {{{
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
            $this->encryptPassword($profile->get("password")));
        } else {
            $this->clearAnonymousPassword($profile);
        }

        // fill in shipping info
        $this->copyBillingInfo($profile);

        // update current shopping cart/order data
        $cart = func_get_instance("Cart");
        if ($cart->get("profile.order_id")) {
            $cart->call("profile.modifyProperties", $_REQUEST);
            $this->copyBillingInfo($cart->get("profile"));
            $cart->call("profile.update");
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
                $this->get("config.Company.users_department"),
                $profile->get("login"),
                "profile_modified"
                );
        $mailer->send();
        // notify administration devision (users department)
        $mailer->compose(
                $this->get("config.Company.site_administrator"),
                $this->get("config.Company.users_department"),
                "profile_admin_modified"
                );
        $mailer->send();
        
        return REGISTER_SUCCESS;
    } // }}}

    function clearAnonymousPassword(&$profile)
    {
		$profile->set("password", null);
		if (isset($_REQUEST["password"])) {
			unset($_REQUEST["password"]);
		}
    }

    /**
    * Handles membership signup.
    */
    function membershipSignup(&$profile) // {{{
    {
        // membership signup requested 
    } // }}}

    /**
    * Checks whtether the only one available and enabled admin exists in cart.
    * 
    * @access public
    * @return boolean
    */
    function isLastAdmin(&$profile) // {{{
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
    function unregister(&$profile) // {{{
    {
        // read profile data
        $profile->read();
        // get current profile from session
        $current = $this->get("session.profile_id");
        if ($current == $profile->get("profile_id")) {
            // log off first
            $this->logoff();
        }
        // send mail notification about deleted profile to customer
        $mailer = new XLite_Model_Mailer();
        $mailer->set("profile", $profile);
		$mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mailer->compose(
                $this->get("config.Company.users_department"),
                $profile->get("login"),
                "profile_deleted"
                );
        $mailer->send();
        // send mail notification about deleted profile to admin
        $mailer->compose(
                $this->get("config.Company.site_administrator"),
                $this->get("config.Company.users_department"),
                "profile_admin_deleted"
                );
        $mailer->send();        
        // delete profile data
        $profile->delete();
    } // }}}

    /**
    * Updates the specified profile on login. Saves profile to session.
    *
    * @param Profile $profile The profile instance
    */
    function loginProfile(&$profile) // {{{
    {
        // check for the fisrt time login
        if (!$profile->get("first_login")) {
            // set first login date
            $profile->set("first_login", time());
        }
        // set last login date
        $profile->set("last_login", time());
        
        // update profile
        $profile->update();
        // save to session
        $this->set("session.profile_id", $profile->get("profile_id"));

        $this->rememberLogin($profile->get("login"));
    } // }}}

    /**
    * Remember login $login in cookie
    */
    function rememberLogin($login) // {{{
    {
        $hosts = array($this->get("xlite.options.host_details.http_host"), $this->get("xlite.options.host_details.https_host"));
        foreach ($hosts as $host) {
            $domain = func_parse_host($host);
            @setcookie("last_login", $login, time()+3600*24*$this->get("config.General.login_lifetime"), "/", $domain);
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
    * Logs in user to cart. 
    *
    * @access public
    * @param string $login The user"s login
    * @param  string $password The user"s password
    */
    function login($login, $password) // {{{
    {
        $password = $this->encryptPassword($password);
        // check for the valid parameters
        if (empty($login) || empty($password)) {
            return ACCESS_DENIED;
        }
        // read profile data
        $profile = new XLite_Model_Profile();
        // deny login if user not found
        if (!$profile->find("login='".addslashes($login)."' AND ". "password='".addslashes($password)."'")) {
        	if ($profile->find("login='".addslashes($login)."'")) {
        		$this->set("forgotten_profile", $profile);
        	}
            return ACCESS_DENIED;
        }
        // check whether the user account is enabled or not
        if (!$profile->get("enabled")) {
            return ACCESS_DENIED;
        }

    	if (isset($_REQUEST[SESSION_DEFAULT_NAME]) && !(isset($_GET[SESSION_DEFAULT_NAME]) || isset($_POST[SESSION_DEFAULT_NAME]))) {
    		unset($_REQUEST[SESSION_DEFAULT_NAME]);
		    $this->xlite->session->set("_".SESSION_DEFAULT_NAME, SESSION_DEFAULT_NAME."=".$this->xlite->session->getID());
		    $this->xlite->session->destroy();
		    $this->xlite->session->setID(SESSION_DEFAULT_ID);
		    $this->xlite->session->_initialize();
        }
        // log in
        $this->loginProfile($profile);
        return $profile; 
    } // }}}

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
    function sendFailedAdminLogin(&$profile) // {{{
    {
        // send mail notification about failed login to administrator
        $mailer = new XLite_Model_Mailer();
        $mailer->set("login", isset($_POST["login"]) ? $_POST["login"] : "unknown");
        $mailer->set("REMOTE_ADDR", isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "unknown");
        $mailer->set("HTTP_X_FORWARDED_FOR", isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : "unknown");
		$mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mailer->compose(
                            $this->get("config.Company.site_administrator"),
                            $this->get("config.Company.site_administrator"),
                            "login_error"
                            );
        $mailer->send();
    } // }}}

    /**
    * Logs off the currently logged profile.
    * 
    * @access public
    */
    function logoff() // {{{
    {
        $this->set("session.last_profile_id", $this->get("session.profile_id"));
        $this->set("session.profile_id", null);
        $this->set("session.anonymous", null);
    } // }}}
   
    /**
    * Checks whether user is logged
    *
    * @access public
    * @return boolean
    * @static
    */
    function isLogged() // {{{
    {
        if (is_null($this->session->get("profile_id"))) {
        	return false;
        } else {
        	$profile = $this->getProfile($this->session->get("profile_id"));
        	return (is_object($profile)) ? true : false;
        }
    } // }}}

    function getProfile($profile_id = null) // {{{
    {
        static $profiles;
        if (!isset($profiles) || $this->_reReadProfiles()) {
            $profiles = array();
            $this->_reReadProfiles(false);
        }

        if (is_null($profile_id)) {
            $profile_id = intval($this->get("session.profile_id"));
        }
        if (is_null($profile_id) || $profile_id <= 0) {
            return null; // not logged
        }
        if (!isset($profiles[$profile_id])) {
        	$profile = new XLite_Model_Profile($profile_id);
        	if (!$profile->isValid()) {
        		$this->session->set("profile_id", 0);
            	return null; // not logged
        	}
            $profiles[$profile_id] = $profile;
        }    
        return $profiles[$profile_id];
    } // }}}

    /**
    * Checks whether the currently logged user is an administrator
    *
    * @access public
    * @param Profile $profile The user profile
    */
    function isAdmin(&$profile) // {{{
    {
        return $profile->get("access_level") >= $this->getAdminAccessLevel();
    } // }}}

    function getAccessLevel($user) // {{{
    {
        $method = "get{$user}accesslevel";
        return method_exists($this, $method) ? $this->$method() : null;
    } // }}}

    /**
    * Gets the access level for administrator.
    */
    function getAdminAccessLevel() // {{{
    {
        return 100;
    } // }}}
    
    /**
    * Gets the access level for a customer.
    */
    function getCustomerAccessLevel() // {{{
    {
        return 0;
    } // }}}
    
    /**
    * Returns all user types configured for this system.
    */
    function getUserTypes() // {{{
    {
        return array("customer" => "Customer", "admin" => "Admin");
    } // }}}

    /**
    * Checks whether user has enough permissions to access specified resource.
    * Resource should provide access to "getAccessLevel()" method in order
    * to check authority.
    *
    * @param  mixed $resource
    * @access public
    * @return boolean
    * @static
    */
    function isAuthorized(&$resource) // {{{
    {
        // check whether resource is valid (has getAccessLevel() method)
        if (!is_object($resource) || !method_exists($resource, "getAccessLevel"))
        {
            $this->_die("Auth::isAuthorized(): Authorization failed: resource invalid");
        }
        return $this->get('profile.access_level') >= $resource->get("accessLevel");
    } // }}}


    function isValidAdminIP(&$resource, $checkOnly=false) // {{{
    {
        if (!is_object($resource)){
            $this->_die("Auth::isValidAdminIP(): Validation Admin IP failed: resource invalid");
        }

        $ip_v4_regexp_wildcard = "/^(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|\*|[01]?[0-9][0-9]?)$/";

        $ip_v4_regexp = "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";        

        $admin_ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "";
        if(!preg_match($ip_v4_regexp,$admin_ip, $admin_ip_bytes)){
            return IP_INVALID;
        }

        $valid_ips = $this->get("xlite.config.SecurityIP.allow_admin_ip");
        if((!is_array($valid_ips) || count($valid_ips) < 1) && !$checkOnly){
            $valid_ips_object = new XLite_Model_Config();
			$admin_ip = serialize(array(array("ip" => $admin_ip, "comment" => "Default admin IP")));
            if($valid_ips_object->find("category = 'SecurityIP' AND name = 'allow_admin_ip'")){
                $valid_ips_object->set("value", $admin_ip);
                $valid_ips_object->set("type", "serialized");
                $valid_ips_object->update();
            } else {
            	$valid_ips_object->createOption("SecurityIP", "allow_admin_ip", $admin_ip, "serialized");
            }
        } else {
            $is_valid = false;
            $valid_list = $valid_ips;
            foreach($valid_list as $ip_array){
                $ip = $ip_array["ip"];
                if(!preg_match($ip_v4_regexp_wildcard, $ip, $ip_bytes)) break;

                $allow = true;
                for($i = 1; $i <=4; $i++){
                    if($ip_bytes[$i] != "*" && ($ip_bytes[$i] != $admin_ip_bytes[$i])){
                        $allow = false;
                        break;
                    }
                }

                if($allow){
                    $is_valid = true;
                    break;
                }
            }

            if(!$is_valid){
                if ($checkOnly) return IP_INVALID;
                $waiting_list = new XLite_Model_WaitingIP();
                preg_match($ip_v4_regexp,$admin_ip, $admin_ip_bytes);
                $admin_ip = isset($admin_ip_bytes[0]) ? $admin_ip_bytes[0] : '';
                if(!$waiting_list->find("ip = '$admin_ip'")){
                    $admin_ip = $admin_ip_bytes[0];
                    $waiting_list->addNew($admin_ip);
                    $waiting_list->notifyAdmin();
                } else { 
                    if($waiting_list->canNotify()){
                        $waiting_list->notifyAdmin();
                    }
                    $waiting_list->set("count", (int)$waiting_list->get("count") + 1);
                    $waiting_list->set("last_date", time());
                    $waiting_list->update();
                }

                //logoff action
                $this->logoff();
                $this->session->set("sidebar_box_statuses", null);
                $this->session->writeClose();
                
                return IP_INVALID;
            }
        }

        return IP_VALID;
    } //}}}

    function requestRecoverPassword($email) // {{{
    {
        $profile = new XLite_Model_Profile();
        if (!$profile->find("login='$email'")) {
            return false;
        }
        $mailer = new XLite_Model_Mailer();
        $mailer->url = $this->xlite->shopURL("cart.php?target=recover_password&action=confirm&email=".urlencode($profile->get("login"))."&request_id=".$profile->get("password"));
		$mailer->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mailer->compose($this->config->get('Company.users_department'),
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
		$mailer->set("charset", $profile->get("billingCountry.charset"));
        $mailer->compose(
                $this->get("config.Company.users_department"),
                $profile->get("login"),
                "recover_recover"
                );
        $mailer->send();
        return true;
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
