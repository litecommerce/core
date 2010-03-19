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
*
* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4:
*/

/**
* @package Base
* @access public
* @version $Id$
*/
abstract class XLite_Controller_Admin_Abstract extends XLite_Controller_Abstract
{
	/**
     * Check if current page is accessible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function checkAccess()
    {
        return parent::checkAccess() && $this->checkXliteForm();
    }

	/**
	 * isXliteFormValid 
	 * 
	 * @return bool
	 * @access protected
	 * @since  3.0.0 EE
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
     * @since  3.0.0 EE
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
	 * @since  3.0.0 EE
	 */
	public function checkXliteForm()
    {
        return isset($this->target) || $this->isIgnoredTarget() || $this->isXliteFormValid();
    }





	protected $recentAdmins = null;

    function getCustomerZoneWarning()
    {
        return ('Y' == XLite::getInstance()->config->General->shop_closed) ? 'maintenance_mode' : null;
    }

    function getAccessLevel()
    {
        return $this->auth->get("adminAccessLevel");
    }    

    function handleRequest()
    {
        $this->checkHtaccess();

        // auto-login request
        if (!$this->auth->is("logged") && isset($_POST["login"]) && isset($_POST["password"])) {
            if($this->auth->adminLogin($_POST["login"], $_POST["password"]) === ACCESS_DENIED) {
                die("ACCESS DENIED");
            }
        }
        if (!$this->auth->isAuthorized($this)) {
			$this->xlite->session->set("lastWorkingURL", $this->get("url"));
            $this->redirect("admin.php?target=login");
            return;
        }

        if(!$this->isIgnoredTarget() && $this->getComplex('xlite.config.Security.admin_ip_protection') == "Y" && !$this->auth->isValidAdminIP($this) && !($_REQUEST['target'] == 'payment_method' && $_REQUEST['action']=='callback')){
            $this->redirect("admin.php?target=login&mode=access_denied");
            return;
        }

		if (isset($_REQUEST['no_https'])) {
            $this->session->set("no_https", true);
        }

        parent::handleRequest();
    }

    function getSecure()
    {
        if ($this->session->get("no_https")) {
            return false;
        }
        return $this->getComplex('config.Security.admin_security');
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
		return $this->xlite->config->Company->locationCountry->get("charset");
	}

	function startDump()
	{
		parent::startDump();
		if (!isset($_REQUEST["mode"]) || $_REQUEST["mode"]!="cp") {
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
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->get("charset"); ?>">
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
        <TD align=right class="displayPageHeader" valign=middle width="50%">Version: <?php echo $this->config->getComplex('Version.version'); ?>&nbsp;&nbsp;&nbsp;</TD>
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

    	$code =<<<EOT
<script language="javascript">
loaded = true;
window.scroll(0, 0);
var Element = document.getElementById("ActionPageHeader");
if (Element) {
	Element.style.display = "none";
}
</script>
EOT;
		echo $code;
    }

    function displayPageFooter()
    {
        $urls = (array)$this->get("pageReturnUrl");

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
			"upgrade" => array("version", "upgrade")
		);

		
                            
        if (
			isset($ignoreTargets[$_REQUEST['target']]) 
			&& (
				in_array("*", $ignoreTargets[$_REQUEST['target']]) 
				|| (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $ignoreTargets[$_REQUEST['target']]))
			)
		) { 
            return true;
        }

        $specialIgnoreTargets = array
        (
            "db" => array("backup", "delete"),
            "files" => array("tar", "tar_skins", "untar_skins"),
            "wysiwyg" => array("export", "import")
        );

        if(
			isset($specialIgnoreTargets[$_REQUEST['target']]) 
			&& (
				in_array("*", $specialIgnoreTargets[$_REQUEST['target']]) 
				|| (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $specialIgnoreTargets[$_REQUEST['target']]))
			) 
			&& (
				isset($_POST['login']) && isset($_POST['password'])
			)
		) {
            $login = $this->xlite->auth->getComplex('profile.login');
            $post_login = $_POST['login'];
            $post_password = $_POST['password'];

            if($login != $post_login)
                return false;

            if(!empty($post_login) && !empty($post_password)){
                $post_password = $this->xlite->auth->encryptPassword($post_password);
                $profile = new XLite_Model_Profile();
                if ($profile->find("login='".addslashes($post_login)."' AND ". "password='".addslashes($post_password)."'")) {
                    if ($profile->get("enabled") && $profile->is("admin")) {
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
}

