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
 * Abstarct admin-zone controller 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AAdmin extends \XLite\Controller\AController
{
    /**
     * Check if current page is accessible
     *
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    public function checkAccess()
    {
        return (parent::checkAccess() || $this->isPublicZone())
            && $this->checkFormId();
    }

    /**
     * Check - form id is valid or not
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
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
     * This function called after template output
     *
     * @return void
     * @access public
     * @since  3.0.0
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
     * @access public
     * @since  3.0.0
     */
    public function checkFormId()
    {
        return $this->getTarget() || $this->isIgnoredTarget() || $this->isFormIdValid();
    }

    /**
     * Get current language code
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentLanguage()
    {
        $currentCode = \XLite\Core\Request::getInstance()->language;

        return $currentCode ? $currentCode : \XLite\Core\Translation::getCurrentLanguageCode();
    }




    protected $recentAdmins = null;

    function getCustomerZoneWarning()
    {
        return ('Y' == \XLite::getInstance()->config->General->shop_closed) ? 'maintenance_mode' : null;
    }

    /**
     * Get access level 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAccessLevel()
    {
        return $this->auth->getAdminAccessLevel();
    }

    function handleRequest()
    {
        // auto-login request
/*
        if (!$this->auth->is('logged') && isset(\XLite\Core\Request::getInstance()->login) && isset(\XLite\Core\Request::getInstance()->password)) {
            if ($this->auth->loginAdministrator(\XLite\Core\Request::getInstance()->login, \XLite\Core\Request::getInstance()->password) === \XLite\Core\Auth::RESULT_ACCESS_DENIED) {
                die('ACCESS DENIED');
            }
        }
*/
        if (
            !$this->auth->isAuthorized($this)
            && !$this->isPublicZone()
        ) {

            // Check - current user is logged and has right access level

            $this->session->set('lastWorkingURL', $this->get('url'));
            $this->redirect(
                $this->buildUrl('login')
            );

        } else {

            if (isset(\XLite\Core\Request::getInstance()->no_https)) {
                $this->session->set('no_https', true);
            }

            parent::handleRequest();
        }
    }

    /**
     * Check - current place is public or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isPublicZone()
    {
        $request = \XLite\Core\Request::getInstance();

        return 'login' == $request->target;
    }

    function getSecure()
    {
        if ($this->session->get('no_https')) {
            return false;
        }
        return $this->config->Security->admin_security;
    }

    function getRecentAdmins()
    {
        if ($this->auth->isLogged() && is_null($this->recentAdmins)) {
            $this->recentAdmins = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findRecentAdmins();
        }
        return $this->recentAdmins;
    }

    function startDump()
    {
        parent::startDump();
        if (!isset(\XLite\Core\Request::getInstance()->mode) || \XLite\Core\Request::getInstance()->mode != "cp") {
            $this->displayPageHeader();
        }
    }

    function displayPageHeader($title="", $scroll_down=false)
    {
?>
<HTML>
<HEAD>
<?php
        if (!empty($title)) {
?>  <title><?php echo $title; ?></title>
<?php   }
?>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->get('charset'); ?>">
    <LINK href="skins/<?php echo \XLite\Model\Layout::getInstance()->getSkin(); ?>/en/style.css"  rel=stylesheet type=text/css>
</HEAD>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0"><?php
        if ($scroll_down) {
            $this->dumpStarted = true;
            func_refresh_start();
        }
?>
<div id="ActionPageHeader" style="display:;">
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
<TR>
<TD valign=top>
    <TABLE border="0" width="100%" cellpadding="0" cellspacing="0" valign=top>
    <TR class="displayPageHeader" height="18">
        <TD align=left class="displayPageHeader" valign=middle width="50%">&nbsp;&nbsp;&nbsp;LiteCommerce</TD>
        <TD align=right class="displayPageHeader" valign=middle width="50%">Version: <?php echo $this->config->Version->version; ?>&nbsp;&nbsp;&nbsp;</TD>
    </TR>
    </TABLE>
</TD>
</TR>
<TR>
<TD height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<TR>
<TD class="displayPageHeader" height="1"><TABLE height="1" border="0" cellspacing="0" cellpadding="0"><TD></TD></TABLE></TD>
</TR>
<tr>
    <td>&nbsp;</td>
</tr>
</TABLE>
</div>
<div style='FONT-SIZE: 10pt;'>
<?php
    }

    function hidePageHeader()
    {
        $this->silent = false;

        $code = <<<EOT
<script type="text/javascript">
<!--
loaded = true;
window.scroll(0, 0);
var Element = document.getElementById('ActionPageHeader');
if (Element) {
    Element.style.display = "none";
}
-->
</script>
EOT;
        echo $code;
    }

    function displayPageFooter()
    {
        $urls = (array)$this->get('pageReturnUrl');

        foreach ($urls as $url) {
            echo "<br>".$url."<br>";
        }
?>
</div>
<br>
</BODY>
</HTML>
<?php
    }

    function getPageReturnUrl()
    {
        return array();
    }

    /**
     * Check - curent target and action is ignored (form id validation is disabled) or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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

                if (!empty($postLogin) && !empty($postPassword)){
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
     * Define common ingnored targets 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineSpecialIgnoredTargets()
    {
        return array(
            'db'      => array('backup', 'delete'),
            'files'   => array('tar', 'tar_skins', 'untar_skins'),
        );
    }

    /**
     * Check - rule is exists with current targe and action or not
     * 
     * @param array $rules Rules
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $cleanUrl Clean URL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sanitizeCleanURL($cleanUrl)
    {
        return substr(trim(preg_replace('/[^a-z0-9 \/\._-]+/Sis', '', $cleanUrl)), 0, 200);
    }

    /**
     * getRequestDataByPrefix 
     * 
     * @param string $prefix Index in the request array
     * @param string $field  Name of the field to retrieve OPTIONAL
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRequestDataByPrefix($prefix, $field = null)
    {
        $data = \XLite\Core\Request::getInstance()->$prefix;

        if (!is_array($data)) {
            $data = array();
        }

        return isset($field) ? (isset($data[$field]) ? $data[$field] : null) : $data;
    }

    /**
     * getPostedData 
     * 
     * @param string $field Name of the field to retrieve OPTIONAL
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPostedData($field = null)
    {
        return $this->getRequestDataByPrefix($this->getPrefixPostedData(), $field);
    }

    /**
     * getToDelete 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getToDelete()
    {
        return $this->getRequestDataByPrefix($this->getPrefixToDelete());
    }



    /**
     * FIXME - to remove
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTested()
    {
        return in_array(
            get_class($this),
            array(
                'XLite\Controller\Admin\Login',
                'XLite\Controller\Admin\Main',
                'XLite\Controller\Admin\Category',
                'XLite\Controller\Admin\Categories',
                'XLite\Controller\Admin\Product',
                'XLite\Controller\Admin\ProductList',
                'XLite\Controller\Admin\Profile',
                'XLite\Controller\Admin\Users',
                'XLite\Controller\Admin\AddressBook',
                'XLite\Controller\Admin\Order',
                'XLite\Controller\Admin\OrderList',
                'XLite\Controller\Admin\Settings',
                'XLite\Controller\Admin\Module',
                'XLite\Controller\Admin\Modules',
                'XLite\Controller\Admin\PaymentMethod',
                'XLite\Controller\Admin\PaymentMethods',
                'XLite\Controller\Admin\ShippingSettings',
                'XLite\Controller\Admin\ShippingMethods',
                'XLite\Controller\Admin\ShippingRates',
                'XLite\Controller\Admin\ShippingZones',
                'XLite\Controller\Admin\Aupost',
                'XLite\Controller\Admin\Taxes',
                'XLite\Controller\Admin\States',
                'XLite\Controller\Admin\Countries',
                'XLite\Controller\Admin\Memberships',
                'XLite\Controller\Admin\Languages',
                'XLite\Controller\Admin\CacheManagement',
                'XLite\Controller\Admin\RecentLogin',
            )
        );
    }
}
