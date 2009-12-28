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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* HTML catalog dialog.
*
* @package Module_HTMLCatalog
* @access public
* @version $Id$
*/
define('CATALOG_CLEANUP_CACHE', 0);

class XLite_Module_HTMLCatalog_Controller_Admin_Catalog extends XLite_Controller_Admin_Abstract
{
    function getCatalog()
    {
        if (is_null($this->catalog)) {
            $this->catalog = new XLite_Module_HTMLCatalog_Model_Catalog();
        }
        return $this->catalog;
    }

    function action_build()
    {
		if (CATALOG_CLEANUP_CACHE) {
			if (function_exists("func_is_locked") && !func_is_locked("cache") && !($_lock_cache = func_lock("cache"))) {
				sleep(1);
				$this->redirect($this->get("url"));
				exit;
			}

			func_cleanup_cache("classes");
			func_cleanup_cache("skins");
			if ($_lock_cache && function_exists("func_is_locked")) func_unlock("cache");
		}

        $this->set("silent", true);
        $catalog = $this->get("catalog");
        func_refresh_start();
        if (is_null($this->get("category_id"))) {
            echo "Building static HTML catalog, please wait ..<br><br>\n";
        } else {
            echo "Continue building static HTML catalog, please wait ..<br><br>\n";
        }
        func_flush();
        if (is_null($this->get("category_id"))) {
            $catalog->build($this, true);
        } else if (!is_null($this->get("category_id"))) {
            // set next page data
            $catalog->build($this);
        }
        if (!is_null($catalog->get("error"))) {
            $this->failure();
        } else if ($catalog->is("incomplete")) {
            $this->nextPage();
        } else {
            $this->success();
        }    
        func_flush();
        func_refresh_end();
    }

    function nextPage()
    {
        $catalog = $this->get("catalog");
        $url = "admin.php?target=catalog&action=build"."&category_id=".$catalog->get("category_id")."&page_id=".$catalog->get("page_id")."&product_id=".$catalog->get("product_id")."&xlite_form_id=".$this->get("xliteFormID");
?>
If you're not redirected automatically, <a href="<?php echo $url; ?>">click on this link to build the next page</a> ...
<script language="JavaScript">
document.location="<?php echo $url; ?>";
</script>
<?php
    }

    function failure()
    {
        if (!is_null($this->get("catalog.error"))) {
            print "<font color=red>" . $this->get("catalog.error") . "</font>";
        }
?>
<br><br>LiteCommerce was unabled to build HTML catalog. Possible reasons and solutions:<br>
<li> LiteCommerce install directory has no web server writable permissions. Go to LiteCommerce directory and issue the following command: chmod 0777 .
<li> 'catalog' directory has no seb server writable permissions. Go to LiteCommerce directory and issue the following command: chmod 0777 catalog
<li> server file system is full.
<br><br><a href="admin.php?target=catalog">Return to admin interface.</a><p>
<?php
    }

    function success()
    {
		if (CATALOG_CLEANUP_CACHE) {
			if (function_exists("func_is_locked") && !func_is_locked("cache") && !($_lock_cache = func_lock("cache"))) {
				sleep(1);
				$this->redirect($this->get("url"));
				exit;
			}
			func_cleanup_cache("classes");
			func_cleanup_cache("skins");
			if ($_lock_cache && function_exists("func_is_locked")) func_unlock("cache");
		}

?>
If you're not redirected automatically, <a href="admin.php?target=catalog&mode=success">click on this link to return to admin interface</a> ...
<script language="JavaScript">
document.location="admin.php?target=catalog&mode=success";
</script>
<?php
    }

    function action_clear()
    {
        $this->set("silent", true);
        print "Cleaning up static HTML catalog ... ";
        $catalog = $this->get("catalog");
        $catalog->clear();
        if (is_null($catalog->get("error"))) {
            echo "[<font color=green>OK</font>]";
        } else {
            echo "[<font color=red>FAILED: </font>" .$catalog->get("error"). "]";
        }
		print "<p><a href=\"admin.php?target=catalog&mode=removed\">Return to admin interface.</a><p>";
        print "<script language='JavaScript'>document.location='admin.php?target=catalog&mode=removed';</script>";
        func_flush();
    }

	function getMemoryLimit()
	{
		return @ini_get("memory_limit");
	}

	function isMemoryLimitChangeable()
	{
		if (version_compare($this->config->get("Version.version"), "2.2.35") <= 0) {
			@include_once("modules/HTMLCatalog/functions.php");
		}

		$memory_limit = $this->get("memoryLimit");
		if (func_check_memory_limit($memory_limit, func_convert_to_byte($memory_limit) + 1024)) {
			func_check_memory_limit(0, $memory_limit);
			return true;
		}

		return false;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
