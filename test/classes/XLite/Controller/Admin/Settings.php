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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Settings
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Settings extends XLite_Controller_Admin_Abstract
{
    /**
     * List of pages with captcha 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function getCaptchaPages()
    {
        return array(
            'on_contactus'        => '',
            'on_register'         => '',
            'on_add_giftcert'     => 'GiftCertificates',
            'on_partner_register' => 'Affiliate'
        );
    }

    /**
     * Return list of enabled captcha pages 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getEnabledCaptchaPages()
    {
        $result = array();

        foreach ($this->getCaptchaPages() as $idx => $module) {
            if (XLite_Model_ModulesManager::getInstance()->isActiveModule($module)) {
                $result[] = $module;
            }
        }

        return $result;
    }

    /**
     * Check for the GDLib extension 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function isGDLibLoaded()
    {
        return extension_loaded('gd') && function_exists("gd_info");
    }




    public $params = array('target', 'page');    
    public $page = "General";    
    public $_waiting_list = null;

    function handleRequest()
    {
        if($this->get("page") == "Captcha" && ($this->getComplex('xlite.config.Security.captcha_protection_system') != "Y" || !$this->isGDLibLoaded())){
            $this->redirect("admin.php?target=settings");
        }

        parent::handleRequest();
    }

    function getSettings()
    {
        return new XLite_Model_Config();
    }

    function getTabPages()
    {
        $categories = $this->getComplex('settings.categories');
        $names = $this->getComplex('settings.categoryNames');
        $pages = array();
        for ($i = 0; $i < count($categories); $i++) {
            if((!$this->isGDLibLoaded() || $this->getComplex('xlite.config.Security.captcha_protection_system') != "Y") && $categories[$i] == "Captcha")
                continue;
            $pages[$categories[$i]] = $names[$i];
        }
        return $pages;
    }

    function getOptions()
    {
        $settings = $this->get("settings");
        return $settings->getByCategory($this->page);
    }
    
    function check_https($https_client)    
    {
        $https = new XLite_Model_HTTPS();
        $result = false;

        switch ($https_client) {
            case 'libcurl':
                $result = $https->detectLibCURL();
                break;

            case 'curl':
                $result = $https->detectCURL();
                break;

            case 'openssl':
                $result = $https->detectOpenSSL();
                break;

            default:
                $result = $https->detectSoftware()
                    ? XLite_Model_HTTPS::HTTPS_SUCCESS
                    : XLite_Model_HTTPS::HTTPS_ERROR;
        }

        return $result;
    }

    function isOpenBasedirRestriction()
    {
        $res = (string) @ini_get("open_basedir");
        return ($res != "");
    }
    
    function get($name) 
    {
        switch($name) {
            case 'phpversion'     : return phpversion(); break;
            case 'timezone_changable' : return func_is_timezone_changable(); break;
            case 'os_type'        : list($os_type, $tmp) = explode(" ", php_uname());
                                  return $os_type;
                                  break;
            case 'mysql_server'    : return mysql_get_server_info(); break;
            case 'mysql_client'    : return mysql_get_client_info(); break;
            case 'root_folder'    : return getcwd(); break;
            case 'web_server'    : if(isset($_SERVER["SERVER_SOFTWARE"])) return $_SERVER["SERVER_SOFTWARE"]; else  return ""; break;
            case 'xml_parser'    :     ob_start();
                                    phpinfo(INFO_MODULES);
                                    $php_info = ob_get_contents();
                                    ob_end_clean();
                                    if( preg_match('/EXPAT.+>([\.\d]+)/mi', $php_info, $m) )
                                        return $m[1];
                                    return function_exists("xml_parser_create")?"found":"";
                                    break;
            case 'gdlib'        :   
                                    if (!$this->is("GDLibLoaded")) {
                                        return "";
                                    } else {
                                        ob_start();
                                        phpinfo(INFO_MODULES);
                                        $php_info = ob_get_contents();
                                        ob_end_clean();
                                        if (preg_match('/GD.+>([\.\d]+)/mi', $php_info, $m)) {
                                            $gdVersion = $m[1];
                                        } else {
                                            $gdVersion = @gd_info();
                                            if (is_array($gdVersion) && isset($gdVersion["GD Version"])) {
                                                $gdVersion = $gdVersion["GD Version"];
                                            } else {
                                                $gdVersion = "unknown";
                                            }
                                        }
                                        return "found (" . $gdVersion . ")";
                                    }
                                    break;
                                  
            case 'lite_version'    : return $this->config->Version->version; break;
            case 'libcurl'        : 
                                    $libcurlVersion = curl_version();
                                    if (is_array($libcurlVersion)) {
                                        $libcurlVersion = $libcurlVersion["version"];
                                    }
                                    return $libcurlVersion;
            case 'curl'            : return $this->ext_curl_version(); break;
            case 'openssl'        : return $this->openssl_version(); break;
            case 'check_files'  :
                                    $result = array();
                                    $files = array("cart.html");
                                    foreach ($files as $file) {
                                        $mode = $this->getFilePermission($file);
                                        $modeStr = $this->getFilePermissionStr($file);
                                        $res = array("file" => $file, "error" => "");
                                        if (!is_file($file)) {
                                            $res["error"] = "does_not_exist";
                                            $result[] = $res;
                                            continue;
                                        }
                                        $perm = substr(sprintf('%o', @fileperms($file)), -4);
                                        if($perm != $modeStr){
                                            if(!@chmod($file, $mode)){
                                                $res["error"] = "cannot_chmod";
                                                $result[] = $res;
                                                continue;
                                            }
                                        } else {
                                            if($this->getComplex('xlite.suMode') != 0) {
                                                if(!@chmod($file, $mode)){
                                                    $res["error"] = "wrong_owner";
                                                    $result[] = $res;
                                                    continue;
                                                }
                                            }
                                        }
                                        $result[] = $res;
                                    }
                                    return $result;
            case 'check_dirs'    :
                                    $result = array();
                                    $dirs = array("var/run", "var/log", "var/html", "var/backup", "var/tmp", "catalog", "images", "classes/modules", "skins/default/en/modules", "skins/admin/en/modules", "skins/default/en/images/modules", "skins/admin/en/images/modules", "skins/mail/en/modules", "skins/mail/en/images/modules");
                                    foreach ($dirs as $dir) {
                                        $mode = $this->getDirPermission($dir);
                                        $modeStr = $this->getDirPermissionStr($dir);
                                        $res = array("dir" => $dir, "error" => "", "subdirs" => array());

                                        if (!is_dir($dir)) {
                                            $full_path = "";
                                            $path = explode("/", $dir);
                                            foreach ($path as $sub) {
                                                $full_path .= $sub."/";
                                                if (!is_dir($full_path)) {
                                                    if (@mkdir($full_path, $mode) !== true )
                                                        break;
                                                }
                                            }
                                        }

                                        if (!is_dir($dir)) {
                                            $res["error"] = "cannot_create";
                                            $result[] = $res;
                                            continue;
                                        }

                                        $perm = substr(sprintf('%o', @fileperms($dir)), -4);
                                        if($perm != $modeStr){
                                            if(!@chmod($dir, $mode)){
                                                $res["error"] = "cannot_chmod";
                                                $result[] = $res;
                                                continue;
                                            }
                                        } else {
                                            if($this->getComplex('xlite.suMode') != 0 || strpos($dir, "var") !== false) {
                                                if(!@chmod($dir, $mode)){
                                                    $res["error"] = "wrong_owner";
                                                    $result[] = $res;
                                                    continue;
                                                }
                                            }
                                        }

                                        $subdirs = array();
                                        if($dir != "catalog" && $dir != "images"){
                                            $this->checkSubdirs($dir, $subdirs);
                                        }

                                        if(!empty($subdirs)){
                                            $res["error"] = "cannot_chmod_subdirs";
                                            $res["subdirs"] = $subdirs;
                                            $result[] = $res;
                                            continue;
                                        }

                                        $result[] = $res;
                                    }
                                    return $result;
                                    break;
            default             : return parent::get($name);
        }    
    }

    function getDirPermission($dir)
    {
        global $options;

        if($this->getComplex('xlite.suMode') == 0){
            if(strpos($dir, "var") === false){
                $mode = 0777;
            } else {
                $mode = isset($options['filesystem_permissions']['nonprivileged_permission_dir']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_dir'], 8, 10) : 0755;
            }
        } else {
            $mode = isset($options['filesystem_permissions']['privileged_permission_dir']) ? base_convert($options['filesystem_permissions']['privileged_permission_dir'],8, 10) : 0711;
        }

        return $mode;
    }

    function getDirPermissionStr($dir = '')
    {
        $mode = (int) $this->getDirPermission($dir);
        return (string) "0" . base_convert($mode, 10, 8);
    }

    function getFilePermission($file)
    {
        global $options;

        if($this->getComplex('xlite.suMode') == 0){
            $mode = isset($options['filesystem_permissions']['nonprivileged_permission_file']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_file'], 8, 10) : 0644;
        } else {
            $mode = isset($options['filesystem_permissions']['privileged_permission_file']) ? base_convert($options['filesystem_permissions']['privileged_permission_file'],8, 10) : 0600;
        }

        return $mode;
    }

    function getFilePermissionStr($file = '')
    {
        $mode = (int) $this->getFilePermission($file);
        return (string) "0" . base_convert($mode, 10, 8);
    }

    function checkSubdirs($path, &$subdir_errors)
    {
        if (!is_dir($path))
            return;

        $mode = $this->getDirPermission($path);
        $modeStr = $this->getDirPermissionStr($path);

        $dh = @opendir($path);
        while (($file = @readdir($dh)) !== false) {
            if($file == '.' || $file == '..')
                continue;
            $fullpath = $path . DIRECTORY_SEPARATOR . $file;
            if(@is_dir($fullpath)) {
                $perm = substr(sprintf('%o', @fileperms($fullpath)), -4);
                if($perm != $modeStr){
                    if(!@chmod($fullpath, $mode)){
                        $subdir_errors[] = $fullpath;
                        continue;
                    }
                } else {
                    if($this->getComplex('xlite.suMode') != 0 || strpos($fullpath, "var") !== false) {
                        if(!@chmod($fullpath, $mode)){
                            $subdir_errors[] = $fullpath;
                            continue;
                        }
                    }
                }

                $this->checkSubdirs($fullpath, $subdir_errors);
            }
        }
    }

    function getCheckFiles()
    {
        $htaccess = new XLite_Model_Htaccess();
        return $htaccess->checkEnvironment();
    }

    function action_update_htaccess()
    {
        $ids = (array) $this->get("ind");
        foreach($ids as $id => $v){
            $htaccess = new XLite_Model_Htaccess($id);
            $htaccess->reImage();
        }
    }

    function action_restore_htaccess()
    {
        $ids = (array) $this->get("ind");
        foreach($ids as $id => $v){
            $htaccess = new XLite_Model_Htaccess($id);
            $htaccess->restoreFile();
        }
    }

    function ext_curl_version()
    {
        $curlBinary = @func_find_executable("curl");
        @exec("$curlBinary --version", $output);
        $version = @$output[0];
        if(preg_match('/curl ([^ $]+)/', $version, $ver))
                return $ver[1];
        else 
                return "";    
    }  
    
    function openssl_version()
    {
        $opensslBinary = @func_find_executable("openssl");
        return @exec("$opensslBinary version");
    }

    function httpRequest($url_request)
    {
        @ini_get('allow_url_fopen') or @ini_set('allow_url_fopen', 1);
        $handle = @fopen ($url_request, "r");

        $response = "";
        if ($handle) {
            while (!feof($handle)) {
                $response .= fread($handle, 8192);
            }

            @fclose($handle);
        } else {
            global $php_errormsg;

            // FIXME - to delete?
            $includes .= "." . DIRECTORY_SEPARATOR . "lib" . PATH_SEPARATOR;
            $includes .= "." . DIRECTORY_SEPARATOR . PATH_SEPARATOR;
            @ini_set("include_path", $includes);

            $php_errormsg = "";
            $_this->error = "";

            require_once LC_ROOT_DIR . 'lib' . LC_DS . 'PEAR.php';
            require_once LC_ROOT_DIR . 'lib' . LC_DS . 'HTTP' . LC_DS . 'Request2.php';

            $http = new HTTP_Request2($url_request);
            $http->_timeout = 3;
            $track_errors = @ini_get("track_errors");
            @ini_set("track_errors", 1);

            $result = @$http->sendRequest();
            @ini_set("track_errors", $track_errors);

            if (!($php_errormsg || PEAR::isError($result))) {
                $response = $http->getResponseBody();
            } else {
                return false;
            }
        }

        return $response;
    }

    function getAnsweredVersion()
    {
        if (isset($this->_answeredVersion)) {
            return $this->_answeredVersion;
        }

        $checkUrl = $this->xlite->getShopUrl("admin.php?target=upgrade&action=version");
        $this->_answeredVersionError = false;
        $response = $this->httpRequest($checkUrl);
        if ($this->get("lite_version") != $response) {
            $this->_answeredVersionError = true;
        }
        $this->_answeredVersion = $response;

        return $this->_answeredVersion;
    }

    function getAnsweredVersionError()
    {
        return $this->_answeredVersionError;
    }

    function action_phpinfo()
    {
        die(phpinfo());    
    } 
    
    function action_update()
    {
        $options = $this->get("options");
        for ($i=0; $i<count($options); $i++) {
            $name = $options[$i]->get("name");
            $type = $options[$i]->get("type");
            if ($type=='checkbox') {
                $val = empty(XLite_Core_Request::getInstance()->$name) ? 'N' : 'Y';
            } elseif ($type == "serialized" && isset(XLite_Core_Request::getInstance()->$name) && is_array(XLite_Core_Request::getInstance()->$name)) {
                $val = serialize(XLite_Core_Request::getInstance()->$name);
            } else {
                $val = isset(XLite_Core_Request::getInstance()->$name) ? trim(XLite_Core_Request::getInstance()->$name) : '';
            }

            if($name == "captcha_length"){
                $val = (int) $val;
                if($val < 1 || $val > 10)
                    continue;
            }

            $options[$i]->set("value", $val);
        }

        // optional validation goes here

        // write changes on success
        for ($i=0; $i<count($options); $i++) {
            $options[$i]->update();
        }
    }

    function getCountriesStates()
    {
        if (!isset($this->_profileDialog)) {
            $this->_profileDialog = new XLite_Controller_Admin_Profile();
        }
        return $this->_profileDialog->getCountriesStates();
    }

    function getWaitingList()
    {
        if(is_null($this->_waiting_list)){
            $waiting_ip = new XLite_Model_WaitingIP();
            $this->_waiting_list = (array) $waiting_ip->findAll("", "first_date");
        }

        return $this->_waiting_list;
    }

    function getCurrentIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    function isCurrentIpValid()
    {
        return $this->auth->isValidAdminIP($this, true) == IP_VALID;
    }

    function action_approve_ip()
    {
        $ids = (array) $this->get("waiting_ips");
        foreach($ids as $id){
            $waiting_ip = new XLite_Model_WaitingIP($id);
            $waiting_ip->approveIP();
            $waiting_ip->delete();
        }
        
    }

    function action_delete_ip()
    {
        $ids = (array) $this->get("waiting_ips");
        foreach($ids as $id){
            $waiting_ip = new XLite_Model_WaitingIP($id);
            $waiting_ip->delete();
        }
    }

    function getAllowedList()
    {
        return $this->getComplex('xlite.config.SecurityIP.allow_admin_ip');
    }

    function action_add_new_ip()
    {
        $ip = $this->get("byte_1") . "." . $this->get("byte_2") . "." . $this->get("byte_3") . "." . $this->get("byte_4");
        $comment = $this->get("comment");
        $valid_ips_object = new XLite_Model_Config();
        if(!$valid_ips_object->find("category = 'SecurityIP' AND name = 'allow_admin_ip'"))
            return;
        $list = unserialize($valid_ips_object->get("value"));

        if(!is_array($list) || count($list) < 1){
            $list = array();
        }
        
        foreach($list as $ip_array){
            if($ip_array['ip'] == $ip){
                $this->set("returnUrl", "admin.php?target=" . $this->get("target")
                            . "&page=" . $this->get("page") . "&ip_error=1");
                return;
            }
        }

        $list[] = array("ip" => $ip, "comment" => $comment);

        $valid_ips_object->set("value", serialize($list));
        $valid_ips_object->set("type", "serialized");
        $valid_ips_object->update();
    }

    function action_delete_allowed_ip()
    {
        $new_list = array();
        $ids = (array) $this->get("allowed_ips");
        foreach($this->getAllowedList() as $id => $ip){
            if(!in_array($id, $ids))
                $new_list[] = $ip;
        }

        if(count($new_list) < 1){
            $admin_ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "";
            $new_list[] = array("ip" => $admin_ip, "comment" => "Default admin IP");
        }

        $valid_ips_object = new XLite_Model_Config();

        if(!$valid_ips_object->find("category = 'SecurityIP' AND name = 'allow_admin_ip'"))
            return;

        $valid_ips_object->set("value", serialize($new_list));
        $valid_ips_object->update();
    }

    function action_update_allowed_ip()
    {
        $comments = (array) $this->get("comment");
        $valid_ips_object = new XLite_Model_Config();
        if(!$valid_ips_object->find("category = 'SecurityIP' AND name = 'allow_admin_ip'"))
            return;
        $list = unserialize($valid_ips_object->get("value"));
        foreach($list as $id => $ip){
            $comment = $comments[$id];
            $list[$id]["comment"] = $comment;
        }

        $valid_ips_object->set("value", serialize($list));
        $valid_ips_object->update();
    }


    function isWin()
    {
        return (LC_OS_CODE === 'win');
    }

    function getTimeZonesList()
    {
        $list = func_get_timezones();
        if (is_array($list))
            return $list;
        else
            return array("Not supported");
    }

    function getCurrentTimeZone()
    {
        $tz = func_get_timezone();
        if ($tz)
            return $tz;
        else
            return "Not supported";
    }
}

