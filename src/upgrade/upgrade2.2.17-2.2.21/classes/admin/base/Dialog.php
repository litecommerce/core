<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
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
* @version $Id: Dialog.php,v 1.2 2006/08/23 10:13:00 sheriff Exp $
*/
class Admin_Dialog extends Dialog
{
    function getAccessLevel()
    {
        return $this->auth->get("adminAccessLevel");
    }    

    function handleRequest()
    {
        // auto-login request
        if (!$this->auth->is("logged") && isset($_POST["login"]) && isset($_POST["password"])) {
            if($this->auth->adminLogin($_POST["login"], $_POST["password"]) == ACCESS_DENIED) {
                die("ACCESS DENIED");
            }
        }
        if (!$this->auth->isAuthorized($this)) {
			$this->xlite->session->set("lastWorkingURL", $this->get("url"));
            $this->redirect("admin.php?target=login");
        } else {
            parent::handleRequest();
        }
    }

    function getSecure()
    {
        if ($this->session->get("no_https")) {
            return false;
        }
        return $this->get("config.Security.admin_security");
    }

    function &getRecentAdmins()
    {
        if ($this->auth->isLogged() && is_null($this->recentAdmins)) {
            $profile =& func_new("Profile");
            $this->recentAdmins =& $profile->findAll("access_level>='".$this->get("auth.adminAccessLevel")."' AND last_login>'0'", "last_login ASC", null, "0, 7");
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

	function output()
	{
		parent::output();
		if ($this->dumpStarted) {
			$this->displayPageFooter();
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
    <LINK href="skins/<?php echo $this->xlite->get("layout.skin"); ?>/en/style.css"  rel=stylesheet type=text/css>
</HEAD>
<BODY leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0"><?php
        if ($scroll_down) {
            $this->dumpStarted = true;
            func_refresh_start();
        }
?>
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
<TR>
<TD valign=top>
    <TABLE border="0" width="100%" cellpadding="0" cellspacing="0" valign=top>
    <TR class="displayPageHeader" height="18">
        <TD align=left class="displayPageHeader" valign=middle width="50%">&nbsp;&nbsp;&nbsp;LiteCommerce</TD>
        <TD align=right class="displayPageHeader" valign=middle width="50%">Version: <?php echo $this->config->get("Version.version"); ?>&nbsp;&nbsp;&nbsp;</TD>
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
<div style='FONT-SIZE: 10pt;'>
<?php
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
<?php
    }

    function getPageReturnUrl()
    {
        return array();
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
