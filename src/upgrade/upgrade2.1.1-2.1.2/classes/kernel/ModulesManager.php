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

    var $errorArchiveCorrupted = false;
    var $errorBrokenDependencies = false;
    var $errorDependencies = null; // array of module_name=>dependend modules
    var $error = ''; // internal archiver error message

    /**
    * Returns the list of active (enabled) modules. 
    * @access public
    * @return array The active modules.
    */
    function &getActiveModules() // {{{
    {
        $activeModules = array();
        foreach ($this->activeModules as $name => $mod) {
            $activeModules[$name] = true;
        }
        return $activeModules;
    } // }}}

    function &getModules() // {{{
    {
        return $this->modules;
    } // }}}

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
            $mod =& func_new("Module", $module->get("name"));
            $this->modules[] =& $mod;
            if ($mod->is('enabled')) {
                $deps = trim(str_replace(" ", "", $mod->get("dependencies")));
                if (!empty($deps)) {
                    $dependencies = explode(',', $deps);
                    foreach ($dependencies as $dependency) {
                        if (!isset($this->activeModules[$dependency])) {
                            func_cleanup_cache("classes");
                            $mod->set("enabled", false);
                            $mod->set("brokenDependencies", $dependencies);
                            continue 2; // continue MODULES
                        }
                    }
                }
                if (strtolower($name) == "demomode") { // turn off safe_mode
                    $this->set("safeMode", false);
                    $this->session->set("safe_mode", null);
                }
                $mod =& func_get_instance("Module", $name); // define and init
                $mod->init();                               // decorators 
                $this->activeModules[$name] =& $mod;
            }
        }
        $GLOBALS["modules_initialized"] = true;
    } // }}}

    /**
    * Attempts to initialize the ModulesManager and all active modules.
    * @access public
    */
    function init() // {{{
    {
        if ($this->xlite->get("adminZone") && isset($_GET["safe_mode"])) { 
            if ($_GET["safe_mode"] == "on") {
                func_cleanup_cache("classes");
                $this->session->set("safe_mode", true);
                $this->set("safeMode", true);
            } elseif ($_GET["safe_mode"] == "off") {    
                $this->session->set("safe_mode", null);
            }
        }
        if ($this->config->get("General.safe_mode") || $this->session->isRegistered("safe_mode")) { 
            func_cleanup_cache("classes");
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
Please ensure that you have properly configured directory access permissions (UNIX only):<br><br>
&gt; chmod 777 classes/modules
<p>Please, set write permissions and click <a href="javascript: window.location.reload()">Refresh</a> to repeat the module installation or <a href="javascript: history.go(-1)">Back</a> to return to admin interface.
EOT;
            $this->error = $msg;
            return false;
        }

        require_once "Archive/Tar.php";

        $ar =& new Archive_Tar($_FILES["module_file"]['tmp_name']);
        $files = $ar->listContent();
        if (!$files) {
            $this->error = "archive is corrupted";
            $this->errorArchiveCorrupted = true;
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
            return false;
        }
        if (!$ar->extractModify('.','')) {
            $this->error = "unable to extract archive ($ar->_error_message)";
            $this->errorArchiveCorrupted = true;
            return false;
        }
        // change file permissions
        foreach ($files as $file) {
            if ($file["typeflag"] == 0) { // file
                @chmod($file["filename"], 0644);
            } elseif ($file["typeflag"] == 5) { // directory
                @chmod($file["filename"], 0755);
            } else { // who knows?
                @chmod($file["filename"], 0777);    
            }
        }

        $dependencies = array();
        $options = parse_ini_file("classes/modules/$moduleName/MANIFEST");
        if (!empty($options["dependencies"])) {
            $dependencies = explode(",", $options["dependencies"]);
        }
        
        foreach ($dependencies as $depend) {
            $depend = trim($depend);
            $depend_module =& func_new("Module");
            if (!($depend_module->find("name='$depend'") && $depend_module->get('enabled'))) {
                $this->error = "dependency failed";
                // $this->errorDependencies = array((object)array("module"=>$moduleName, "depend"=>$dependencies));
                $this->errorDependencies[] = $depend;
                $this->moduleName = $moduleName;
                $this->errorBrokenDependencies = true;
                return false;
            }
        }
        // $module =& func_get_instance("Module", $moduleName);
        $GLOBALS["xlite_class_files"][strtolower("Module_$moduleName")] = "modules/$moduleName/$moduleName.php";
        $module =& func_get_instance("Module_$moduleName");
        $module->find("module_id=".$options["module_id"]);
        $module->set("properties", $options);
        $module->install();
        func_cleanup_cache("classes");
        return true;
    } // }}}

    function changeModuleStatus(&$module, $status) // {{{
    {
        $deps = trim(str_replace(" ", "", $module->get("dependencies")));
        if (!empty($deps)) {
            if (!$status) {
                $module->disable(); // disable module works anyway
                unset($this->activeModules[$module->get("name")]);
                return;
            }
            // check dependency modules before enabling module
            $dependencies = explode(',', $deps); // name(s) of dependency mod(s)
            foreach ($dependencies as $dependency) {
                if (isset($this->activeModules[$dependency])) {
                    $module->enable();
                } else {
                    $module->disable();
                    unset($this->activeModules[$module->get("name")]);
                }
                break;
            }
            return;
        }
        if ($status) {
            $module->enable();
        } else {
            $module->disable();
            unset($this->activeModules[$module->get("name")]);
        }
    } // }}}

    function updateModules($ids) // {{{
    {
        foreach ($this->get("modules") as $module) {
            if (in_array($module->get("module_id"), $ids)) {
                $this->changeModuleStatus($module, true);
            } else {
                $this->changeModuleStatus($module, false);
            }
            $module->update();
        }
        func_cleanup_cache("classes");
    } // }}}

    function uninstallModule($moduleName) // {{{
    {
        $this->moduleName = $moduleName;
        $module =& func_get_instance("Module", $moduleName);
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
        $module->uninstall();
        func_cleanup_cache("classes");
        return true;
    } // }}}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
