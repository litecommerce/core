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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Admin;

/**
 * Settings. TODO FULL REFACTOR!!!
 *
 */
class Settings extends \XLite\Controller\Admin\AAdmin
{
    /**
     * params
     * FIXME
     *
     * @var array
     */
    public $params = array('target', 'page');

    /**
     * page
     * FIXME
     *
     * @var string
     */
    public $page = 'General';

    /**
     * _waiting_list
     * FIXME
     *
     * @var mixed
     */
    public $_waiting_list = null;


    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'General settings';
    }

    // {{{ Pages

    /**
     * Get tab names
     *
     * @return array
     */
    public function getPages()
    {
        $list = parent::getPages();
        $list['General']     = 'General';
        $list['Company']     = 'Company';
        $list['Email']       = 'Email';
        $list['Security']    = 'Security';
        $list['Environment'] = 'Environment';
        $list['Performance'] = 'Performance';

        return $list;
    }

    /**
     * Get pages templates
     *
     * @return array
     */
    protected function getPageTemplates()
    {
        $list = parent::getPageTemplates();

        foreach ($this->getPages() as $name => $title) {
            $list[$name] = 'settings/base.tpl';
        }

        $list['Environment'] = 'settings/summary/body.tpl';

        return $list;
    }

    // }}}

    /**
     * Get options for current tab (category)
     *
     * @return array
     */
    public function getOptions()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Config')->findByCategoryAndVisible($this->page);
    }

    /**
     * getModelFormClass
     *
     * @return string
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Settings';
    }

    // {{{ Additional methods

    /**
     * Check for the GDLib extension
     *
     * @return void
     */
    public function isGDLibLoaded()
    {
        return extension_loaded('gd') && function_exists('gd_info');
    }

    /**
     * isOpenBasedirRestriction
     *
     * @return void
     */
    public function isOpenBasedirRestriction()
    {
        $res = (string) @ini_get('open_basedir');

        return ('' != $res);
    }

    /**
     * Returns value by request
     *
     * @param string $name Type of value
     *
     * @return string
     */
    public function get($name)
    {
        $return = '';

        switch($name) {

            case 'phpversion':
                $return = PHP_VERSION;
                break;

            case 'os_type':
                list($osType) = explode(' ', PHP_OS);
                $return = $osType;
                break;

            case 'mysql_server':
                $return = \Includes\Utils\Database::getDbVersion();
                break;

            case 'innodb_support':
                $return = \Includes\Utils\Database::isInnoDBSupported();
                break;

            case 'root_folder':
                $return = getcwd();
                break;

            case 'web_server':

                if (isset($_SERVER['SERVER_SOFTWARE'])) {
                    $return = $_SERVER['SERVER_SOFTWARE'];

                } else {
                    $return = '';
                }
                break;

            case 'xml_parser':

                ob_start();
                phpinfo(INFO_MODULES);
                $phpInfo = ob_get_contents();
                ob_end_clean();

                if (preg_match('/EXPAT.+>([\.\d]+)/mi', $phpInfo, $m)) {
                    $return = $m[1];

                } else {
                    $return = function_exists('xml_parser_create') ? 'found' : '';
                }

                break;

            case 'gdlib':

                if (!$this->is('GDLibLoaded')) {
                    $return = '';

                } else {

                    ob_start();

                    phpinfo(INFO_MODULES);

                    $phpInfo = ob_get_contents();

                    ob_end_clean();

                    if (preg_match('/GD.+>([\.\d]+)/mi', $phpInfo, $m)) {
                        $gdVersion = $m[1];

                    } else {

                        $gdVersion = @gd_info();

                        if (is_array($gdVersion) && isset($gdVersion['GD Version'])) {
                            $gdVersion = $gdVersion['GD Version'];

                        } else {
                            $gdVersion = 'unknown';
                        }
                    }

                    $return = 'found (' . $gdVersion . ')';
                }

                break;

            case 'core_version':
                $return = \XLite::getInstance()->getVersion();
                break;

            case 'libcurl':

                $libcurlVersion = curl_version();

                if (is_array($libcurlVersion)) {
                    $libcurlVersion = $libcurlVersion['version'];
                }

                $return = $libcurlVersion;

                break;

            case 'check_files':

                $result = array();
                $files = array();

                foreach ($files as $file) {

                    $mode = $this->getFilePermission($file);
                    $modeStr = $this->getFilePermissionStr($file);
                    $res = array('file' => $file, 'error' => '');

                    if (!is_file($file)) {
                        $res['error'] = 'does_not_exist';
                        $result[] = $res;
                        continue;
                    }

                    $perm = substr(sprintf('%o', @fileperms($file)), -4);

                    if ($perm != $modeStr) {

                        if (!@chmod($file, $mode)) {
                            $res['error'] = 'cannot_chmod';
                            $result[] = $res;
                            continue;
                        }

                    } else {

                        if ($this->getComplex('xlite.suMode') != 0) {

                            if (!@chmod($file, $mode)) {
                                $res['error'] = 'wrong_owner';
                                $result[] = $res;
                                continue;
                            }
                        }
                    }

                    $result[] = $res;
                }

                $return = $result;

                break;

            // :FIXME: checng to the constants
            case 'check_dirs':

                $result = array();

                $dirs = array(
                    'var/run',
                    'var/log',
                    'var/html',
                    'var/backup',
                    'var/tmp',
                    'catalog',
                    'images',
                    'classes/modules',
                    'skins/default/en/modules',
                    'skins/admin/en/modules',
                    'skins/default/en/images/modules',
                    'skins/admin/en/images/modules',
                    'skins/mail/en/modules',
                    'skins/mail/en/images/modules'
                );

                foreach ($dirs as $dir) {

                    $mode = $this->getDirPermission($dir);
                    $modeStr = $this->getDirPermissionStr($dir);

                    $res = array(
                        'dir' => $dir,
                        'error' => '',
                        'subdirs' => array()
                    );

                    if (!is_dir($dir)) {

                        $fullPath = '';
                        $path = explode('/', $dir);

                        foreach ($path as $sub) {

                            $fullPath .= $sub . '/';

                            if (!is_dir($fullPath) && @mkdir($fullPath, $mode) !== true) {
                                    break;
                            }
                        }
                    }

                    if (!is_dir($dir)) {
                        $res['error'] = 'cannot_create';
                        $result[] = $res;
                        continue;
                    }

                    $perm = substr(sprintf('%o', @fileperms($dir)), -4);

                    if ($perm != $modeStr) {

                        if (!@chmod($dir, $mode)) {
                            $res['error'] = 'cannot_chmod';
                            $result[] = $res;
                            continue;
                        }

                    } else {

                        if ($this->getComplex('xlite.suMode') != 0 || strpos($dir, 'var') !== false) {

                            if (!@chmod($dir, $mode)) {
                                $res['error'] = 'wrong_owner';
                                $result[] = $res;
                                continue;
                            }
                        }
                    }

                    $subdirs = array();

                    if ('catalog' != $dir && 'images' != $dir) {
                        $this->checkSubdirs($dir, $subdirs);
                    }

                    if (!empty($subdirs)) {
                        $res['error'] = 'cannot_chmod_subdirs';
                        $res['subdirs'] = $subdirs;
                        $result[] = $res;
                        continue;
                    }

                    $result[] = $res;
                }

                $return = $result;

                break;

            default:
                $return = parent::get($name);
        }

        return $return;
    }

    /**
     * Get directory permission
     *
     * @param string $dir Directory path
     *
     * @return integer
     */
    public function getDirPermission($dir)
    {
        global $options;

        if ($this->getComplex('xlite.suMode') == 0) {

            if (strpos($dir, 'var') === false) {
                $mode = 0777;

            } else {
                $mode = isset($options['filesystem_permissions']['nonprivileged_permission_dir'])
                    ? base_convert($options['filesystem_permissions']['nonprivileged_permission_dir'], 8, 10)
                    : 0755;
            }

        } else {
            $mode = isset($options['filesystem_permissions']['privileged_permission_dir'])
                ? base_convert($options['filesystem_permissions']['privileged_permission_dir'], 8, 10)
                : 0711;
        }

        return $mode;
    }

    /**
     * getDirPermissionStr
     *
     * @param string $dir ____param_comment____ OPTIONAL
     *
     * @return void
     */
    public function getDirPermissionStr($dir = '')
    {
        $mode = (int) $this->getDirPermission($dir);

        return (string) '0' . base_convert($mode, 10, 8);
    }

    /**
     * getFilePermission
     *
     * @param mixed $file ____param_comment____
     *
     * @return void
     */
    public function getFilePermission($file)
    {
        global $options;

        if ($this->getComplex('xlite.suMode') == 0) {
            $mode = isset($options['filesystem_permissions']['nonprivileged_permission_file'])
                ? base_convert($options['filesystem_permissions']['nonprivileged_permission_file'], 8, 10)
                : 0644;

        } else {
            $mode = isset($options['filesystem_permissions']['privileged_permission_file'])
                ? base_convert($options['filesystem_permissions']['privileged_permission_file'], 8, 10)
                : 0600;
        }

        return $mode;
    }

    /**
     * getFilePermissionStr
     *
     * @param string $file ____param_comment____ OPTIONAL
     *
     * @return void
     */
    public function getFilePermissionStr($file = '')
    {
        $mode = (int) $this->getFilePermission($file);

        return (string) '0' . base_convert($mode, 10, 8);
    }

    /**
     * checkSubdirs
     *
     * @param mixed $path          ____param_comment____
     * @param mixed &$subdirErrors ____param_comment____
     *
     * @return void
     */
    public function checkSubdirs($path, &$subdirErrors)
    {
        if (is_dir($path)) {

            $mode = $this->getDirPermission($path);
            $modeStr = $this->getDirPermissionStr($path);

            $dh = @opendir($path);

            while (($file = @readdir($dh)) !== false) {

                if ('.' != $file && '..' != $file) {

                    $fullpath = $path . DIRECTORY_SEPARATOR . $file;

                    if (@is_dir($fullpath)) {

                        $perm = substr(sprintf('%o', @fileperms($fullpath)), -4);

                        if ($perm != $modeStr) {

                            if (!@chmod($fullpath, $mode)) {
                                $subdirErrors[] = $fullpath;
                                continue;
                            }

                        } else {

                            if ($this->getComplex('xlite.suMode') != 0 || strpos($fullpath, 'var') !== false) {

                                if (!@chmod($fullpath, $mode)) {
                                    $subdirErrors[] = $fullpath;
                                    continue;
                                }
                            }
                        }

                        $this->checkSubdirs($fullpath, $subdirErrors);
                    }
                }
            }
        }
    }

    /**
     * doActionPhpinfo
     *
     * @return void
     */
    public function doActionPhpinfo()
    {
        die(phpinfo());
    }

    /**
     * Re-generate safe mode access key
     *
     * @return void
     */
    public function doActionSafeModeKeyRegen()
    {
        \Includes\SafeMode::regenerateAccessKey();
        \XLite\Core\TopMessage::addInfo('Safe mode access key has been re-generated');

        $this->setReturnURL($this->buildURL($this->get('target'), '', array('page' => 'Security')));
    }

    /**
     * Clean aggregation cache directory
     *
     * @return void
     */
    public function doActionCleanAggregationCache()
    {
        \Includes\Utils\FileManager::unlinkRecursive(LC_DIR_CACHE_RESOURCES);

        \XLite\Core\TopMessage::addInfo('Aggregation cache has been cleaned');

        $this->setReturnURL($this->buildURL($this->get('target'), '', array('page' => 'Performance')));
    }

    /**
     * doActionUpdate
     *
     * @return void
     */
    public function doActionUpdate()
    {
        $this->getModelForm()->performAction('update');
    }

    /**
     * isWin
     *
     * @return void
     */
    public function isWin()
    {
        return (LC_OS_CODE === 'win');
    }

    /**
     * getStateById
     *
     * @param mixed $stateId ____param_comment____
     *
     * @return void
     */
    public function getStateById($stateId)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\State')->find($stateId);
    }

    /**
     * Get safe mode access key
     *
     * @return string
     */
    public function getSafeModeKey()
    {
        return \Includes\SafeMode::getAccessKey();
    }

    /**
     * Get Hard Reset URL
     *
     * @return string
     */
    public function getHardResetURL()
    {
        return \Includes\SafeMode::getResetURL();
    }

    /**
     * Get Soft Reset URL
     *
     * @return string
     */
    public function getSoftResetURL()
    {
        return \Includes\SafeMode::getResetURL(true);
    }

    /**
     * Flag to has email error
     *
     * @return string
     */
    public function hasTestEmailError()
    {
        return '' !== (string)\XLite\Core\Session::getInstance()->test_email_error;
    }

    /**
     * Return error test email sending
     *
     * @return string
     */
    public function getTestEmailError()
    {
        $error = (string)\XLite\Core\Session::getInstance()->test_email_error;

        \XLite\Core\Session::getInstance()->test_email_error = '';

        return $error;
    }

    // }}}

    /**
     * Action to send test email notification
     *
     * @return void
     */
    protected function doActionTestEmail()
    {
        $request = \XLite\Core\Request::getInstance();

        $error = \XLite\Core\Mailer::getInstance()->sendTestEmail(
            $request->test_from_email_address,
            $request->test_to_email_address,
            $request->test_email_body
        );

        \XLite\Core\Session::getInstance()->test_email_error = $error;

        $this->setReturnURL(
            $this->buildURL('settings', '', array('page' => 'Email'))
        );
    }

}
