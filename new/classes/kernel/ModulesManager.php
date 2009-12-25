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

// Constants {{{
define("MM_ARCHIVE_CORRUPTED", 1);
define("MM_BROKEN_DEPENDENCIES", 2);
define("MM_OK", 0);
// }}}


/**
* Class ModulesManager implements the mechanism for modules initialization
* and management. All classes which are the subject for extending with 
* modules functionality should be instantiated using the ModulesManager
* controlling mechanism.
*
* @package Kernel
* @access public
* @version $Id$
*/
class ModulesManager extends Object
{
    /**
    * Contains the available Modules list.
    */
    var $modules = array();

    /**
    * Contains the active (enabled) Modules list.
    */
    var $activeModules = array();
    var $activeModulesNumber = 0;
    var $activeModulesHash = null;

    var $errorArchiveCorrupted = false;
    var $errorBrokenDependencies = false;
    var $errorDependencies = null; // array of module_name=>dependend modules
    var $error = ''; // internal archiver error message

    /**
    * Returns the list of active (enabled) modules. 
    * @access public
    * @return array The active modules.
    */
    function getActiveModules() // {{{
    {
    	if (!isset($this->activeModulesHash) || $this->activeModulesNumber != count($this->activeModules)) {
            $this->activeModulesHash = array();
            $this->activeModulesNumber = count($this->activeModules);
            foreach ($this->activeModules as $name => $mod) {
                $this->activeModulesHash[$name] = true;
            }
        }
        return $this->activeModulesHash;
    } // }}}

    function getActiveModulesNumber()
    {
    	return ((is_array($this->activeModules)) ? count($this->activeModules) : 0);
    }

    function getModules() // {{{
    {
        return $this->modules;
    } // }}}

    function getModulesNumber()
    {
    	return ((is_array($this->modules)) ? count($this->modules) : 0);
    }

    /**
    * Attempts to initialize the active (enabled) modules.
    * @access public
    */
    function initModules() // {{{
    {
    	// see PaymentMethod.php
		// registering initial payment methods
        global $_registered_methods;
        $_registered_methods = array("phone_ordering", "fax_ordering", "purchase_order", "credit_card", "echeck", "cod", "money_ordering");

        $module = func_new("Module");
        $result = $module->iterate();
        // MODULES:
        while ($module->next($result)) {
            $name = $module->get("name");
            $modProperties = $module->getProperties();
            $mod = func_new("Module");
            $mod->setProperties($modProperties);
            $mod->isRead = true;
            if ($mod->is("enabled")) {
            	$origType = $mod->get("type");
                $deps = trim(str_replace(" ", "", $mod->get("dependencies")));
                if (!empty($deps)) {
                    $dependencies = explode(',', $deps);
                    $invalidDependencies = array();
                    foreach ($dependencies as $dependency) {
                        if (!isset($this->activeModules[$dependency])) {
                        	$invalidDependencies[] = $dependency;
                        }
                    }
                    if (count($invalidDependencies) > 0) {
                        $mod->set("enabled", false);
                        $mod->set("brokenDependencies", $invalidDependencies);
                        $this->set("brokenDependencies", true);
                    }
                }

	            $mod->clearModuleType($this->getPredefinedModuleType($mod->get("name")));
	            $moduleType = $mod->get("type");
            	if ($mod->is("enabled") && !$this->get("safeMode")) {
	            	$mod = func_get_instance("Module_$name"); // define and init
                    $mod->setProperties($modProperties);
                    $mod->isRead = true;
                	$mod->init();
                }
				$mod->set("type", $moduleType);
            	if ($mod->is("enabled")) {
                	$this->activeModules[$name] = $mod;
                }
            }
			$this->modules[] = $mod;
        }

        $GLOBALS["modules_initialized"] = true;
    } // }}}

    /**
    * Attempts to initialize the ModulesManager and all active modules.
    * @access public
    */
    function init() // {{{
    {
		if (!empty($_REQUEST['target']) && !empty($_REQUEST['action'])) {
			if ('upgrade' === $_REQUEST['target'] && ('upgrade' === $_REQUEST['action'] || 'upgrade_force' === $_REQUEST['action'])) {
				return;
			}
		}

        if ($this->xlite->get("adminZone") && isset($_GET["safe_mode"])) {
        	$auth_code = $this->xlite->get("options.installer_details.auth_code");
            if (empty($auth_code) || (!empty($auth_code) && $_GET["auth_code"] == $auth_code)) {
                if ($_GET["safe_mode"] == "on") {
                	$this->cleanupCompileCache(true, false);
                    $this->session->set("safe_mode", true);
                    $this->set("safeMode", true);
                } elseif ($_GET["safe_mode"] == "off") {    
                    $this->session->set("safe_mode", null);
                }
            }
        }
        if ($this->config->get("General.safe_mode") || $this->session->isRegistered("safe_mode")) { 
        	$this->cleanupCompileCache(true, false);
            $this->set("safeMode", true);
            // do NOT initialize modules
        }
        $this->initModules();
    } // }}}

    function install() // {{{
    {
        if (!is_writable("classes/modules")) {
            $msg =<<<EOT
<br><font color=red>FAILED!</font><br>
Permission denied: directory "classes/modules" is write-protected.<br>
Please ensure that you have properly configured directory access permissions.<br><br>
<p>Please, set write permissions and click <a href="javascript: window.location.reload()">Refresh</a> to repeat the module installation or <a href="javascript: history.go(-1)">Back</a> to return to admin interface.
EOT;
            $this->error = $msg;
            return false;
        }

        $upload = func_new('Upload', $_FILES['module_file']);
        $dest_file = 'var/tmp/'.$upload->getName();
        if (!$upload->move($dest_file)) {
            $this->error = $upload->getErrorMessage();
            return false;
        }
        
        require_once "Archive/Tar.php";
		
		$ar = new Archive_Tar($dest_file);
        $files = $ar->listContent();
        if (!$files) {
            $this->error = "archive is corrupted";
            $this->errorArchiveCorrupted = true;
            @unlink($dest_file);
            return false;
        }
        // Get module name
        foreach ($files as $file) {
            if (preg_match('/classes\/modules\/([^\/]*)\//', 
                        $file['filename'], $res)) {
                // if the filename starts with classes/modules
                $moduleName =  $res[1];
                break;
            }
        }
        if (empty($moduleName)) {
            $this->error = "Archive is empty";
            $this->errorArchiveCorrupted = true;
            @unlink($dest_file);
            return false;
        }
        if (!$ar->extractModify('.','')) {
            $this->error = "unable to extract archive ($ar->_error_message)";
            $this->errorArchiveCorrupted = true;
            @unlink($dest_file);
            return false;
        }
        // change file permissions
        foreach ($files as $file) {
            if ($file["typeflag"] == 0) { // file
                @chmod($file["filename"], get_filesystem_permissions(0644));
            } elseif ($file["typeflag"] == 5) { // directory
                @chmod($file["filename"], get_filesystem_permissions(0755));
            } else { // who knows?
                @chmod($file["filename"], get_filesystem_permissions(0777));    
            }
        }

		@unlink($dest_file);

        $dependencies = array();
        $options = parse_ini_file("classes/modules/$moduleName/MANIFEST");
        $options['access_date'] = time();
        if (!empty($options["dependencies"])) {
            $dependencies = explode(",", $options["dependencies"]);
        }
        
        foreach ($dependencies as $depend) {
            $depend = trim($depend);
            $depend_module = func_new("Module");
            if (!($depend_module->find("name='$depend'") && $depend_module->get('enabled'))) {
                $this->error = "dependency failed";
                // $this->errorDependencies = array((object)array("module"=>$moduleName, "depend"=>$dependencies));
                $this->errorDependencies[] = $depend;
                $this->moduleName = $moduleName;
                $this->errorBrokenDependencies = true;
                return false;
            }
        }
        $GLOBALS["xlite_class_files"][strtolower("Module_$moduleName")] = "modules/$moduleName/$moduleName.php";
        $module = func_get_instance("Module_$moduleName");
        $moduleInstalled = $module->find("module_id=".$options["module_id"]);
        if ($moduleInstalled) {
        	$moduleEnabled = $module->get("enabled");
        }
        $module->set("properties", $options);
        if ($moduleInstalled) {
        	$module->set("enabled", $moduleEnabled);
        }

        if (!func_is_locked("cache") && !($_lock_cache = func_lock("cache"))) {
            $this->errorLockingCache = true;
            return false;
        }
        $module->install();
        $this->cleanupCompileCache();
        if ($_lock_cache) func_unlock("cache");

		$this->moduleName = $moduleName;
        return true;
    } // }}}

    function changeModuleStatus(&$module, $status) // {{{
    {
        if ($status) {
            $module->enable();
        } else {
            $module->disable();
            if (isset($this->activeModules[$module->get("name")])) {
            	unset($this->activeModules[$module->get("name")]);
            }
        }
    } // }}}

    function updateModules($ids) // {{{
    {
        if (!func_is_locked("cache") && !($_lock_cache = func_lock("cache"))) {
            $this->errorLockingCache = true;
            return false;
        }

        foreach ($this->get("modules") as $module) {
            if (in_array($module->get("module_id"), $ids)) {
                $this->changeModuleStatus($module, true);
            } else {
                $this->changeModuleStatus($module, false);
            }
            $module->update();
        }
        $this->cleanupCompileCache();
        if ($_lock_cache) func_unlock("cache");
        return true;
    } // }}}

    function uninstallModule($moduleName) // {{{
    {
        $this->moduleName = $moduleName;
        $module = func_get_instance("Module_$moduleName", $moduleName);
        if (!is_object($module)) {
            $module = func_new("Module", $moduleName);
        }
        foreach ($this->getModules() as $currentModule) {
            if ($currentModule->isDependsOn($moduleName) === true) {
                $depend[]= $currentModule->get("name");
            }
        }
        if (!empty($depend)) {
            $this->errorBrokenDependencies = true;
            $this->errorDependencies = $depend;
            return false;
        }

        if (!func_is_locked("cache") && !($_lock_cache = func_lock("cache"))) {
            $this->errorLockingCache = true;
            return false;
        }
        if (is_object($module)) {
        	$module->uninstall();
        }
        $this->cleanupCompileCache();
        if ($_lock_cache) func_unlock("cache");

		return true;
    } // }}}

    function cleanupCompileCache($cleanupClasses=true,$cleanupSkins=true) // {{{
    {
        func_cleanup_cache("classes", (bool)$this->get("verboseCleanup"));
		func_cleanup_cache("skins", (bool)$this->get("verboseCleanup"));
    } // }}}

	public function getPredefinedModuleType($moduleName)
	{
		switch($moduleName) {
			case "2CheckoutCom":
			case "AuthorizeNet":
			case "BankOfAmerica":
			case "CyberSource":
			case "Echo":
			case "ePDQ":
			case "eProc":
			case "eSelect":
			case "eWAYxml":
			case "HSBC":
			case "LinkPoint":
			case "NetRegistry":
			case "Netbilling":
			case "NetworkMerchants":
			case "Nochex":
			case "Ogone":
			case "PHPCyberSource":
			case "ParentPay":
			case "PayPal":
			case "PayPalPro":
			case "PaySystems":
			case "PlugnPay":
			case "ProtxDirect":
			case "Protx":
			case "SagePay":
			case "SecureTrading":
			case "SkipJack":
			case "TrustCommerce":
			case "VeriSign":
			case "VerisignLink":
			case "PayFlowPro":
			case "PayFlowLink":
			case "WellsFargo":
			case "WorldPay":
		    case "ChronoPay":
		    case "BeanStream":
			case "ProtxForm":
				return Module__::MODULE_PAYMENT;

			case "AustraliaPost":
			case "CanadaPost":
			case "Intershipper":
			case "UPS":
			case "USPS":
		    case "UPSOnlineTools":
				return Module__::MODULE_SHIPPING;

			case "AOM":
			case "Affiliate":
			case "AutoUpdateCatalog":
			case "CardinalCommerce":
			case "EcommerceReports":
			case "Egoods":
			case "InventoryTracking":
			case "LayoutOrganizer":
			case "Newsletters":
			case "ProductAdviser":
			case "ProductOptions":
			case "WholesaleTrading":
			case "WishList":
		    case "FlyoutCategories":
			case "GiftCertificates":
			case "GoogleCheckout":
			case "AdvancedSearch":
			case "AdvancedSecurity":
			case "AntiFraud":
			case "Bestsellers":
			case "DemoMode":
			case "DetailedImages":
			case "FeaturedProducts":
			case "Froogle":
			case "GreetVisitor":
			case "HTMLCatalog":
			case "MultiCategories":
			case "MultiCurrency":
			case "Promotion":
			case "ShowcaseOrganizer":
			case "SnsIntegration":
			case "AccountingPackage":
			case "XCartImport":
				return Module__::MODULE_GENERAL;

		    case "FashionBoutique":
		    case "GiftsShop":
		    case "SummerSports":
		    case "WinterSports":
			    return Module__::MODULE_SKIN;

			default:
				return Module__::MODULE_3RD_PARTY;
		}
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
