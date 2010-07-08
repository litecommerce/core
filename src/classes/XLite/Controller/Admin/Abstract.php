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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Controller_Admin_Abstract 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Controller_Admin_Abstract extends XLite_Controller_Abstract
{
    /**
     * Check if current page is accessible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkAccess()
    {
        return (parent::checkAccess() || $this->isPublicZone())
            && $this->checkXliteForm();
    }

    /**
     * isXliteFormValid 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isXliteFormValid()
    {
        if (!$this->xlite->config->Security->form_id_protection) {
            return true;
        }

        if ('payment_method' == $this->target && 'callback' == $this->action) {
            return true;
        }

        $form = new XLite_Model_XliteForm();
        $result = $form->find('form_id = \'' . addslashes($this->xlite_form_id) . '\' AND session_id = \'' . XLite_Model_Session::getInstance()->getID() . '\'');

        if (!$result) {
            $form->collectGarbage();
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
     * checkXliteForm 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkXliteForm()
    {
        return $this->getTarget() || $this->isIgnoredTarget() || $this->isXliteFormValid();
    }

    /**
     * Get current language code
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentLanguage()
    {
        $currentCode = XLite_Core_Request::getInstance()->language;

        return $currentCode ? $currentCode : XLite_Core_Translation::getCurrentLanguageCode();;
    }




    protected $recentAdmins = null;

    function getCustomerZoneWarning()
    {
        return ('Y' == XLite::getInstance()->config->General->shop_closed) ? 'maintenance_mode' : null;
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
        $this->checkHtaccess();

        // auto-login request
/*
        if (!$this->auth->is('logged') && isset(XLite_Core_Request::getInstance()->login) && isset(XLite_Core_Request::getInstance()->password)) {
            if ($this->auth->adminLogin(XLite_Core_Request::getInstance()->login, XLite_Core_Request::getInstance()->password) === ACCESS_DENIED) {
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

        } elseif (
            !$this->isIgnoredTarget()
            && 'Y' == $this->config->Security->admin_ip_protection
            && !$this->auth->isValidAdminIP($this)
            && !(XLite_Core_Request::getInstance()->target == 'payment_method' && XLite_Core_Request::getInstance()->action == 'callback')
        ) {

            // IP check

            $this->redirect(
                $this->buildUrl('login', '', array('mode' => 'access_denied'))
            );

        } else {

            if (isset(XLite_Core_Request::getInstance()->no_https)) {
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
        $request = XLite_Core_Request::getInstance();

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
            $profile = new XLite_Model_Profile();
            $this->recentAdmins = $profile->findAll("access_level>='".$this->getComplex('auth.adminAccessLevel')."' AND last_login>'0'", "last_login ASC", null, "0, 7");
        }
        return $this->recentAdmins;
    }

    function getCharset()
    {
        return $this->config->Company->locationCountry->charset;
    }

    function startDump()
    {
        parent::startDump();
        if (!isset(XLite_Core_Request::getInstance()->mode) || XLite_Core_Request::getInstance()->mode != "cp") {
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
    <LINK href="skins/<?php echo $this->xlite->getComplex('layout.skin'); ?>/en/style.css"  rel=stylesheet type=text/css>
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

    // FIXME - check this function carefully
    function isIgnoredTarget()
    {
        $ignoreTargets = array
        (
            "image" => array("*"),
            "callback" => array("*"),
            "upgrade" => array('version', "upgrade")
        );

        
                            
        if (
            isset($ignoreTargets[XLite_Core_Request::getInstance()->target]) 
            && (
                in_array("*", $ignoreTargets[XLite_Core_Request::getInstance()->target]) 
                || (isset(XLite_Core_Request::getInstance()->action) && in_array(XLite_Core_Request::getInstance()->action, $ignoreTargets[XLite_Core_Request::getInstance()->target]))
            )
        ) {
            return true;
        }

        $specialIgnoreTargets = array
        (
            "db" => array('backup', "delete"),
            "files" => array('tar', "tar_skins", "untar_skins"),
            "wysiwyg" => array('export', "import")
        );

        if (
            isset($specialIgnoreTargets[XLite_Core_Request::getInstance()->target]) 
            && (
                in_array("*", $specialIgnoreTargets[XLite_Core_Request::getInstance()->target]) 
                || (isset(XLite_Core_Request::getInstance()->action) && in_array(XLite_Core_Request::getInstance()->action, $specialIgnoreTargets[XLite_Core_Request::getInstance()->target]))
            ) 
            && (
                isset(XLite_Core_Request::getInstance()->login) && isset(XLite_Core_Request::getInstance()->password)
            )
        ) {
            $login = $this->xlite->auth->getComplex('profile.login');
            $post_login = XLite_Core_Request::getInstance()->login;
            $post_password = XLite_Core_Request::getInstance()->password;

            if ($login != $post_login)
                return false;

            if (!empty($post_login) && !empty($post_password)){
                $post_password = $this->xlite->auth->encryptPassword($post_password);
                $profile = new XLite_Model_Profile();
                if ($profile->find("login='".addslashes($post_login)."' AND ". "password='".addslashes($post_password)."'")) {
                    if ($profile->get('enabled') && $profile->is('admin')) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    // FIXME - check if it's needed
    function getSidebarBoxStatus($boxHead = null)
    {
        $dialog = new XLite_Controller_Admin_Sbjs();
        $dialog->sidebar_box_id = $this->strMD5($boxHead);

        return $dialog->getSidebarBoxStatus();
    }

    // FIXME - move it to the appropriate class (or remove)
    function strMD5($string)
    {
        return strtoupper(md5(strval($string)));
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
        return substr(trim(preg_replace('/[^a-z0-9 \/\.]+/Sis', '', $cleanUrl)), 0, 200);
    }

    /**
     * Return Viewer object
     * 
     * @return XLite_View_Controller
     * @access public
     * @since  3.0.0
     */
    public function getViewer()
    {
        if (
            XLite_Core_Request::getInstance()->isAJAX()
            && XLite_Core_Request::getInstance()->widget
        ) {

            $params = array();

            foreach (array(self::PARAM_SILENT, self::PARAM_DUMP_STARTED) as $name) {
                $params[$name] = $this->get($name);
            }

            $class = XLite_Core_Request::getInstance()->widget;
            $viewer = new $class($params, $this->getViewerTemplate());

        } else {
            $viewer = parent::getViewer();
        }

        return $viewer;
    }

}

