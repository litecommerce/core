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

namespace XLite\Controller\Admin;

/**
 * Settings
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Settings extends AAdmin
{

    /**
     * The list of option categories displayed on General settings page 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayedCategories = array(
        'General'     => 'General',
        'Company'     => 'Company',
        'Email'       => 'Email',
        'Security'    => 'Security',
        'AdminIP'     => 'Admin IP protection',
        'Captcha'     => 'Captcha protection',
        'Environment' => 'Environment'
    );

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
            if (empty($module) || \XLite\Model\ModulesManager::getInstance()->isActiveModule($module)) {
                $result[$idx] = $module;
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
        return extension_loaded('gd') && function_exists('gd_info');
    }

    public $params = array('target', 'page');
    public $page = 'General';
    public $_waiting_list = null;

    /**
     * Denies access to Captcha category if it is disabled
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if ($this->get('page') == "Captcha" && ($this->config->Security->captcha_protection_system != 'Y' || !$this->isGDLibLoaded())){
            $this->redirect('admin.php?target=settings');
        }

        parent::handleRequest();
    }

    /**
     * Get tab names 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTabPages()
    {
        $pages = $this->displayedCategories;

        if (isset($pages['Captcha']) && !$this->isGDLibLoaded() || $this->config->Security->captcha_protection_system != 'Y') {
            unset($pages['Captcha']);
        }

        return $pages;
    }

    /**
     * Get options for current tab (category)
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Config')
            ->findByCategoryAndVisible($this->page);
    }
    
    /**
     * Get HTTPS bouncer 
     * 
     * @param string $https_client HTTPS bouncer name
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function check_https($https_client)    
    {
        $https = new \XLite\Model\HTTPS();
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
                    ? \XLite\Model\HTTPS::HTTPS_SUCCESS
                    : \XLite\Model\HTTPS::HTTPS_ERROR;
        }

        return $result;
    }

    /**
     * isOpenBasedirRestriction 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOpenBasedirRestriction()
    {
        $res = (string) @ini_get('open_basedir');
        return ($res != '');
    }
    
    /**
     * Returns value by request
     * 
     * @param string $name Type of value
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name) 
    {
        switch($name) {
            case 'phpversion':
                return PHP_VERSION;
                break;

            case 'timezone_changable':
                return func_is_timezone_changable();
                break;

            case 'os_type':
                list($os_type) = explode(' ', PHP_OS);
                return $os_type;
                break;

            case 'mysql_server':
                return mysql_get_server_info();
                break;

            case 'mysql_client':
                return mysql_get_client_info();
                break;

            case 'root_folder':
                return getcwd();
                break;

            case 'web_server':
                if (isset($_SERVER['SERVER_SOFTWARE'])) {
                    return $_SERVER['SERVER_SOFTWARE'];
                } else {
                    return "";
                }
                break;

            case 'xml_parser':
                ob_start();
                phpinfo(INFO_MODULES);
                $php_info = ob_get_contents();
                ob_end_clean();
                if (preg_match('/EXPAT.+>([\.\d]+)/mi', $php_info, $m)) {
                    return $m[1];
                }

                return function_exists('xml_parser_create')?"found":"";
                break;

            case 'gdlib'        :   
                                    if (!$this->is('GDLibLoaded')) {
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
                                            if (is_array($gdVersion) && isset($gdVersion['GD Version'])) {
                                                $gdVersion = $gdVersion['GD Version'];
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
                                        $libcurlVersion = $libcurlVersion['version'];
                                    }
                                    return $libcurlVersion;
            case 'curl'            : return $this->ext_curl_version(); break;
            case 'openssl'        : return $this->openssl_version(); break;
            case 'check_files'  :
                                    $result = array();
                                    $files = array('cart.html');
                                    foreach ($files as $file) {
                                        $mode = $this->getFilePermission($file);
                                        $modeStr = $this->getFilePermissionStr($file);
                                        $res = array("file" => $file, "error" => "");
                                        if (!is_file($file)) {
                                            $res['error'] = "does_not_exist";
                                            $result[] = $res;
                                            continue;
                                        }
                                        $perm = substr(sprintf('%o', @fileperms($file)), -4);
                                        if ($perm != $modeStr){
                                            if (!@chmod($file, $mode)){
                                                $res['error'] = "cannot_chmod";
                                                $result[] = $res;
                                                continue;
                                            }
                                        } else {
                                            if ($this->getComplex('xlite.suMode') != 0) {
                                                if (!@chmod($file, $mode)){
                                                    $res['error'] = "wrong_owner";
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
                                    $dirs = array('var/run', "var/log", "var/html", "var/backup", "var/tmp", "catalog", "images", "classes/modules", "skins/default/en/modules", "skins/admin/en/modules", "skins/default/en/images/modules", "skins/admin/en/images/modules", "skins/mail/en/modules", "skins/mail/en/images/modules");
                                    foreach ($dirs as $dir) {
                                        $mode = $this->getDirPermission($dir);
                                        $modeStr = $this->getDirPermissionStr($dir);
                                        $res = array("dir" => $dir, "error" => "", "subdirs" => array());

                                        if (!is_dir($dir)) {
                                            $full_path = "";
                                            $path = explode('/', $dir);
                                            foreach ($path as $sub) {
                                                $full_path .= $sub."/";
                                                if (!is_dir($full_path)) {
                                                    if (@mkdir($full_path, $mode) !== true )
                                                        break;
                                                }
                                            }
                                        }

                                        if (!is_dir($dir)) {
                                            $res['error'] = "cannot_create";
                                            $result[] = $res;
                                            continue;
                                        }

                                        $perm = substr(sprintf('%o', @fileperms($dir)), -4);
                                        if ($perm != $modeStr){
                                            if (!@chmod($dir, $mode)){
                                                $res['error'] = "cannot_chmod";
                                                $result[] = $res;
                                                continue;
                                            }
                                        } else {
                                            if ($this->getComplex('xlite.suMode') != 0 || strpos($dir, "var") !== false) {
                                                if (!@chmod($dir, $mode)){
                                                    $res['error'] = "wrong_owner";
                                                    $result[] = $res;
                                                    continue;
                                                }
                                            }
                                        }

                                        $subdirs = array();
                                        if ($dir != "catalog" && $dir != "images"){
                                            $this->checkSubdirs($dir, $subdirs);
                                        }

                                        if (!empty($subdirs)){
                                            $res['error'] = "cannot_chmod_subdirs";
                                            $res['subdirs'] = $subdirs;
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

    /**
     * Get directory permission
     * 
     * @param string $dir Directory path
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDirPermission($dir)
    {
        global $options;

        if ($this->getComplex('xlite.suMode') == 0){
            if (strpos($dir, "var") === false){
                $mode = 0777;
            } else {
                $mode = isset($options['filesystem_permissions']['nonprivileged_permission_dir']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_dir'], 8, 10) : 0755;
            }
        } else {
            $mode = isset($options['filesystem_permissions']['privileged_permission_dir']) ? base_convert($options['filesystem_permissions']['privileged_permission_dir'],8, 10) : 0711;
        }

        return $mode;
    }

    /**
     * getDirPermissionStr 
     * 
     * @param string $dir ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDirPermissionStr($dir = '')
    {
        $mode = (int) $this->getDirPermission($dir);
        return (string) "0" . base_convert($mode, 10, 8);
    }

    /**
     * getFilePermission 
     * 
     * @param mixed $file ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFilePermission($file)
    {
        global $options;

        if ($this->getComplex('xlite.suMode') == 0){
            $mode = isset($options['filesystem_permissions']['nonprivileged_permission_file']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_file'], 8, 10) : 0644;
        } else {
            $mode = isset($options['filesystem_permissions']['privileged_permission_file']) ? base_convert($options['filesystem_permissions']['privileged_permission_file'],8, 10) : 0600;
        }

        return $mode;
    }

    /**
     * getFilePermissionStr 
     * 
     * @param string $file ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFilePermissionStr($file = '')
    {
        $mode = (int) $this->getFilePermission($file);
        return (string) "0" . base_convert($mode, 10, 8);
    }

    /**
     * checkSubdirs 
     * 
     * @param mixed $path          ____param_comment____
     * @param mixed $subdir_errors ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkSubdirs($path, &$subdir_errors)
    {
        if (!is_dir($path))
            return;

        $mode = $this->getDirPermission($path);
        $modeStr = $this->getDirPermissionStr($path);

        $dh = @opendir($path);
        while (($file = @readdir($dh)) !== false) {
            if ($file == '.' || $file == '..')
                continue;
            $fullpath = $path . DIRECTORY_SEPARATOR . $file;
            if (@is_dir($fullpath)) {
                $perm = substr(sprintf('%o', @fileperms($fullpath)), -4);
                if ($perm != $modeStr){
                    if (!@chmod($fullpath, $mode)){
                        $subdir_errors[] = $fullpath;
                        continue;
                    }
                } else {
                    if ($this->getComplex('xlite.suMode') != 0 || strpos($fullpath, "var") !== false) {
                        if (!@chmod($fullpath, $mode)){
                            $subdir_errors[] = $fullpath;
                            continue;
                        }
                    }
                }

                $this->checkSubdirs($fullpath, $subdir_errors);
            }
        }
    }

    /**
     * getCheckFiles 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCheckFiles()
    {
        $htaccess = new \XLite\Model\Htaccess();
        return $htaccess->checkEnvironment();
    }

    /**
     * action_update_htaccess 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_update_htaccess()
    {
        $ids = \XLite\Core\Request::getInstance()->ind;

        if (is_array($ids)) {
            foreach ($ids as $id => $v){
                $htaccess = new \XLite\Model\Htaccess($id);
                $htaccess->reImage();
            }
        }
    }

    /**
     * action_restore_htaccess 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_restore_htaccess()
    {
        $ids = \XLite\Core\Request::getInstance()->ind;

        if (is_array($ids)) {
           foreach ($ids as $id => $v){
               $htaccess = new \XLite\Model\Htaccess($id);
               $htaccess->restoreFile();
           }
        }
    }

    /**
     * ext_curl_version 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function ext_curl_version()
    {
        $curlBinary = @func_find_executable('curl');
        @exec("$curlBinary --version", $output);
        $version = @$output[0];
        if (preg_match('/curl ([^ $]+)/', $version, $ver))
                return $ver[1];
        else 
                return "";
    }
    
    /**
     * openssl_version 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function openssl_version()
    {
        $opensslBinary = @func_find_executable('openssl');
        return @exec("$opensslBinary version");
    }

    /**
     * httpRequest 
     * 
     * @param mixed $url_request ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function httpRequest($url_request)
    {
        if (!@ini_get('allow_url_fopen')) {
             @ini_set('allow_url_fopen', 1);
        }

        $handle = @fopen ($url_request, "r");

        $response = "";
        if ($handle) {
            while (!feof($handle)) {
                $response .= fread($handle, 8192);
            }

            fclose($handle);

        } else {

            require_once LC_EXT_LIB_DIR . 'PEAR.php';
            require_once LC_EXT_LIB_DIR . 'HTTP' . LC_DS . 'Request2.php';

            $this->error = '';

            try {
                $http = new HTTP_Request2($url_request);
                $http->setConfig('timeout', 5);
                $response = $http->send()->getBody();

            }  catch (Exception $e) {
                $this->error = $e->getMessage();
                $response = false;
            }

        }

        return $response;
    }

    /**
     * getAnsweredVersion 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAnsweredVersion()
    {
        if (isset($this->_answeredVersion)) {
            return $this->_answeredVersion;
        }

        $checkUrl = $this->xlite->getShopUrl($this->buildUrl('upgrade', 'version'));
        $this->_answeredVersionError = false;
        $response = $this->httpRequest($checkUrl);
        if ($this->get('lite_version') != $response) {
            $this->_answeredVersionError = true;
        }
        $this->_answeredVersion = $response;

        return $this->_answeredVersion;
    }

    /**
     * getAnsweredVersionError 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAnsweredVersionError()
    {
        return $this->_answeredVersionError;
    }

    /**
     * action_phpinfo 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_phpinfo()
    {
        die(phpinfo());
    }
    
    /**
     * action_update 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_update()
    {
        $optionsToUpdate = array();
        $options = $this->getOptions();

        // Find changed options and store them in $optionsToUpdate
        foreach ($options as $key => $option) {

            $name  = $option->name;
            $type  = $option->type;
            $value = $option->value;

            if ('checkbox' == $type) {
                $newValue = empty(\XLite\Core\Request::getInstance()->$name) ? 'N' : 'Y';

            } elseif ('serialized' == $type && isset(\XLite\Core\Request::getInstance()->$name) && is_array(\XLite\Core\Request::getInstance()->$name)) {
                $newValue = serialize(\XLite\Core\Request::getInstance()->$name);

            } else {
                $newValue = isset(\XLite\Core\Request::getInstance()->$name) ? trim(\XLite\Core\Request::getInstance()->$name) : '';
            }

            if ('captcha_length' == $name) {
                $newValue = intval($newValue);
                if ($newValue < 1 || $newValue > 10) {
                    continue;
                }
            }

            if ($value != $newValue) {
                $option->value = $newValue;
                $optionsToUpdate[] = $option;
            }
        }

        // Save changed options to the database
        if (!empty($optionsToUpdate)) {

            foreach ($optionsToUpdate as $option) {
                \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                    array(
                        'category' => $option->category,
                        'name'     => $option->name,
                        'value'    => $option->value
                    )
                );
            }
        }
    }

    /**
     * getWaitingList 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWaitingList()
    {
        if (is_null($this->_waiting_list)){
            $waiting_ip = new \XLite\Model\WaitingIP();
            $this->_waiting_list = (array) $waiting_ip->findAll("", "first_date");
        }

        return $this->_waiting_list;
    }

    /**
     * getCurrentIP 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * isCurrentIpValid 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCurrentIpValid()
    {
        return $this->auth->isValidAdminIP($this, true) == \XLite\Model\Auth::IP_VALID;
    }

    /**
     * action_approve_ip 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_approve_ip()
    {
        $ids = (array) $this->get('waiting_ips');
        foreach ($ids as $id){
            $waiting_ip = new \XLite\Model\WaitingIP($id);
            $waiting_ip->approveIP();
            $waiting_ip->delete();
        }
        
    }

    /**
     * action_delete_ip 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_delete_ip()
    {
        $ids = (array) $this->get('waiting_ips');
        foreach ($ids as $id){
            $waiting_ip = new \XLite\Model\WaitingIP($id);
            $waiting_ip->delete();
        }
    }

    /**
     * getAllowedList 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAllowedList($fromDB = false)
    {
        if ($fromDB) {

            $ipsListOption = \XLite\Core\Database::getRepo('XLite\Model\Config')->findOneBy(array('category' => 'SecurityIP', 'name' => 'allow_admin_ip'));

             if (!is_null($ipsListOption)) {
                 $ipsList = unserialize($ipsListOption->value);
             }

             $result = is_array($ipsList) ? $ipsList : array();

        } else {
            $result = $this->config->SecurityIP->allow_admin_ip;
        }

        return $result;
    }

    /**
     * action_add_new_ip 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_add_new_ip()
    {
        $ip = \XLite\Core\Request::getInstance()->byte_1 . '.' .
              \XLite\Core\Request::getInstance()->byte_2 . '.' .
              \XLite\Core\Request::getInstance()->byte_3 . '.' .
              \XLite\Core\Request::getInstance()->byte_4;

        $comment = \XLite\Core\Request::getInstance()->comment;

        $ipsList = $this->getAllowedList(true);

        $ipIsAlreadyListed = false;

        foreach ($ipsList as $ipItem){
            if ($ipItem['ip'] == $ip){
                $ipIsAlreadyListed = true;
                break;
            }
        }

        if (!$ipIsAlreadyListed) {
            $ipsList[] = array('ip' => $ip, 'comment' => $comment);
            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => 'SecurityIP',
                    'name'     => 'allow_admin_ip',
                    'value'    => serialize($ipsList),
                    'type'     => 'serialized'
                )
            );

        } else {
           $this->set('returnUrl', "admin.php?target=" . $this->get('target')
               . "&page=" . $this->get('page') . "&ip_error=1");
        }
    }

    /**
     * action_delete_allowed_ip 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_delete_allowed_ip()
    {
        $ids = \XLite\Core\Request::getInstance()->allowed_ips;

        if (is_array($ids) && !empty($ids)) {

            $newList = array();

            $ipsList = $this->getAllowedList(true);

            foreach ($ipsList as $id => $ip){
                if (!in_array($id, $ids)) {
                    $newList[] = $ip;
                }
            }

            if (empty($newList)){
                $adminIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
                $newList[] = array('ip' => $adminIp, 'comment' => 'Default admin IP');
            }

            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => 'SecurityIP',
                    'name'     => 'allow_admin_ip',
                    'value'    => serialize($newList),
                    'type'     => 'serialized'
                )
            );
        }
    }

    /**
     * action_update_allowed_ip 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_update_allowed_ip()
    {
        $commentsList = \XLite\Core\Request::getInstance()->comment;

        if (!empty($commentsList) && is_array($commentsList)) {

            $ipsList = $this->getAllowedList(true);

            $needUpdate = false;

            foreach ($ipsList as $id => $ipItem) {
                if ($commentsList[$id] != $ipsList[$id]['comment']) {
                    $ipsList[$id]['comment'] = $commentsList[$id];
                    $needUpdate = true;
                }
            }

            if ($needUpdate) {
                \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                    array(
                        'category' => 'SecurityIP',
                        'name'     => 'allow_admin_ip',
                        'value'    => serialize($ipsList),
                        'type'     => 'serialized'
                    )
                );
            }
        }
    }


    /**
     * isWin 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isWin()
    {
        return (LC_OS_CODE === 'win');
    }

    /**
     * getTimeZonesList 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTimeZonesList()
    {
        $list = func_get_timezones();
        if (is_array($list))
            return $list;
        else
            return array('Not supported');
    }

    /**
     * getCurrentTimeZone 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentTimeZone()
    {
        $tz = func_get_timezone();
        if ($tz)
            return $tz;
        else
            return "Not supported";
    }
}

