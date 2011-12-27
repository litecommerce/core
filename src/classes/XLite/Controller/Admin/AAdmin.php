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

namespace XLite\Controller\Admin;

/**
 * Abstract admin-zone controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AAdmin extends \XLite\Controller\AController
{
    /**
     * Name of temporary variable to store time
     * of last request to marketplace
     */
    const MARKETPLACE_LAST_REQUEST_TIME = 'marketplaceLastRequestTime';


    /**
     * List of recently logged in administrators
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $recentAdmins = null;

    /**
     * Check if current page is accessible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkAccess()
    {
        return (parent::checkAccess() || $this->isPublicZone()) && $this->checkFormId();
    }

    /**
     * This function called after template output
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function postprocess()
    {
        parent::postprocess();

        if ($this->dumpStarted) {

            $this->displayPageFooter();
        }
    }

    /**
     * Check form id
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkFormId()
    {
        return $this->getTarget() || $this->isIgnoredTarget() || $this->isFormIdValid();
    }

    /**
     * Returns 'maintenance_mode' string if frontend is closed or null otherwise
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCustomerZoneWarning()
    {
        return ('Y' == \XLite\Core\Config::getInstance()->General->shop_closed) ? 'maintenance_mode' : null;
    }

    /**
     * Get access level
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAccessLevel()
    {
        return \XLite\Core\Auth::getInstance()->getAdminAccessLevel();
    }

    /**
     * Handles the request to admin interface
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function handleRequest()
    {
        // Check if user is logged in and has a right access level
        if (
            !\XLite\Core\Auth::getInstance()->isAuthorized($this)
            && !$this->isPublicZone()
        ) {
            \XLite\Core\Session::getInstance()->lastWorkingURL = $this->get('url');

            $this->redirect($this->buildURL('login'));

        } else {

            if (isset(\XLite\Core\Request::getInstance()->no_https)) {

                \XLite\Core\Session::getInstance()->no_https = true;
            }

            parent::handleRequest();
        }
    }

    /**
     * Get recently logged in admins
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRecentAdmins()
    {
        if (
            \XLite\Core\Auth::getInstance()->isLogged()
            && is_null($this->recentAdmins)
        ) {
            $this->recentAdmins = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findRecentAdmins();
        }

        return $this->recentAdmins;
    }

    /**
     * Check if upgrade or update is available on Marketplace.
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isUpgradeEntryAvailable()
    {
        \XLite\Upgrade\Cell::getInstance()->clear();

        return (bool) array_filter(
            \Includes\Utils\ArrayManager::getObjectsArrayFieldValues(
                \XLite\Upgrade\Cell::getInstance()->getEntries(),
                'isEnabled'
            )
        );
    }

    /**
     * Check if form id is valid or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isFormIdValid()
    {
        \XLite\Core\Database::getRepo('XLite\Model\FormId')->removeExpired();

        $request = \XLite\Core\Request::getInstance();
        $result = true;

        if (\Xlite\Core\Config::getInstance()->Security->form_id_protection) {

            if (!isset($request->xlite_form_id) || !$request->xlite_form_id) {
                $result = false;

            } else {

                $form = \XLite\Core\Database::getRepo('XLite\Model\FormId')->findOneBy(
                    array(
                        'form_id'    => $request->xlite_form_id,
                        'session_id' => \XLite\Core\Session::getInstance()->getID(),
                    )
                );
                $result = isset($form);
                if ($form) {
                    $form->detach();
                }
            }
        }

        return $result;
    }

    /**
     * Check - is current place public or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isPublicZone()
    {
        return 'login' == \XLite\Core\Request::getInstance()->target;
    }

    /**
     * Start simplified page to display progress of some process
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function startDump()
    {
        parent::startDump();

        if (!isset(\XLite\Core\Request::getInstance()->mode) || 'cp' != \XLite\Core\Request::getInstance()->mode) {
            $this->displayPageHeader();
        }
    }

    /**
     * Display header of simplified page
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function displayPageHeader($title = '', $scrollDown = false)
    {
        $output = <<<OUT
<html>
<head>
    <title>$title</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body>

OUT;

        if ($scrollDown) {
            $this->dumpStarted = true;
            $output .= func_refresh_start(false);
        }

        $output .= <<<OUT

<div style='font-size: 12px;'>

OUT;

        echo ($output);
    }

    /**
     * displayPageFooter
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function displayPageFooter()
    {
        $urls = (array)$this->getPageReturnURL();

        foreach ($urls as $url) {
            echo ('<br />' . $url . '<br />');
        }

        $output = <<<OUT

</div>

</body>
</html>

OUT;

        echo ($output);
    }

    /**
     * getPageReturnURL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageReturnURL()
    {
        return array();
    }

    /**
     * Check - current target and action is ignored (form id validation is disabled) or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isIgnoredTarget()
    {
        $result = false;

        if ($this->isRuleExists($this->defineIngnoredTargets())) {
            $result = true;

        } else {

            $request = \XLite\Core\Request::getInstance();

            if (
                $this->isRuleExists($this->defineSpecialIgnoredTargets())
                && isset($request->login)
                && isset($request->password)
                && \XLite\Core\Auth::getInstance()->isLogged()
                && \XLite\Core\Auth::getInstance()->getProfile()->getLogin() == $request->login
            ) {
                $login = \XLite\Core\Auth::getInstance()->getProfile()->getLogin();
                $postLogin = $request->login;
                $postPassword = $request->password;

                if (!empty($postLogin) && !empty($postPassword)) {
                    $postPassword = \XLite\Core\Auth::getInstance()->encryptPassword($postPassword);
                    $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
                        ->findByLoginPassword($postLogin, $postPassword, 0);

                    if (isset($profile)) {
                        $profile->detach();
                        if ($profile->isEnabled() && \XLite\Core\Auth::getInstance()->isAdmin($profile)) {
                            $result = true;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Define common ignored targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineIngnoredTargets()
    {
        return array(
            'callback'       => '*',
            'payment_method' => 'callback',
        );
    }

    /**
     * Define special ignored targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineSpecialIgnoredTargets()
    {
        return array(
            'files'          => array('tar', 'tar_skins', 'untar_skins'),
        );
    }

    /**
     * Check - rule is exists with current target and action or not
     *
     * @param array $rules Rules
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isRuleExists(array $rules)
    {
        $request = \XLite\Core\Request::getInstance();

        return isset($rules[$request->target])
            && (
                in_array('*', $rules[$request->target])
                || (isset($request->action) && in_array($request->action, $rules[$request->target]))
            );

    }

    /**
     * Sanitize Clean URL
     *
     * @param string $cleanURL Clean URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function sanitizeCleanURL($cleanURL)
    {
        return substr(trim(preg_replace('/[^a-z0-9 \/\._-]+/Sis', '', $cleanURL)), 0, 200);
    }

    // {{{ Multilanguage support

    /**
     * Get current language code
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCurrentLanguage()
    {
        return \XLite\Core\Session::getInstance()->editLanguage ?: \XLite\Core\Translation::getCurrentLanguageCode();
    }

    /**
     * Change language common action 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function doActionChangeLanguage()
    {
        $code = \XLite\Core\Request::getInstance()->language;
        $language = \XLite\Core\Database::getRepo('\XLite\Model\Language')->findOneBy(array('code' => $code));
        if ($language && $language->getEnabled()) {
            \XLite\Core\Session::getInstance()->editLanguage = $code;
        }
    }

    // }}}

    // {{{ Methods to work with the received data

    /**
     * getRequestDataByPrefix
     *
     * @param string $prefix Index in the request array
     * @param string $field  Name of the field to retrieve OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestDataByPrefix($prefix, $field = null)
    {
        return \Includes\Utils\ArrayManager::getIndex((array) \XLite\Core\Request::getInstance()->$prefix, $field);
    }

    /**
     * getPostedData
     *
     * @param string $field Name of the field to retrieve OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPostedData($field = null)
    {
        return $this->getRequestDataByPrefix($this->getPrefixPostedData(), $field);
    }

    /**
     * getToDelete
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getToDelete()
    {
        return $this->getRequestDataByPrefix($this->getPrefixToDelete());
    }

    // }}}

    // {{{ Updates logging and error handling

    /**
     * Log upgrade error and show top message
     *
     * @param string $action  Current action
     * @param string $message Message to log and show OPTIONAL
     * @param array  $args    Arguments to subsistute OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function showError($action, $message = null, array $args = array())
    {
        $this->showCommon('Error', $action, $message, $args);
    }

    /**
     * Log upgrade warning and show top message
     *
     * @param string $action  Current action
     * @param string $message Message to log and show OPTIONAL
     * @param array  $args    Arguments to subsistute OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function showWarning($action, $message = null, array $args = array())
    {
        $this->showCommon('Warning', $action, $message, $args);
    }

    /**
     * Log upgrade info and show top message
     *
     * @param string $action  Current action
     * @param string $message Message to log and show OPTIONAL
     * @param array  $args    Arguments to subsistute OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function showInfo($action, $message = null, array $args = array())
    {
        $this->showCommon('Info', $action, $message, $args);
    }

    /**
     * Log upgrade info and show top message
     *
     * @param string $method  Method to call
     * @param string $action  Current action
     * @param string $message Message to log and show
     * @param array  $args    Arguments to subsistute
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function showCommon($method, $action, $message, array $args)
    {
        if (!isset($message)) {
            $message = implode('; ', \XLite\Upgrade\Cell::getInstance()->getErrorMessages()) ?: 'unknown error';
        }

        if (isset($action) && LC_DEVELOPER_MODE) {
            $message = 'Action "' . get_class($this) . '::' . $action . '", ' . lcfirst($message);
        }

        \XLite\Upgrade\Logger::getInstance()->{'log' . $method}($message, $args, true);
    }

    // }}}
}
