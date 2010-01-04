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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/*define('MODULE_UNKNOWN',   0);
define('MODULE_PAYMENT',   1);
define('MODULE_SHIPPING',  2);
define('MODULE_SKIN',      3);
define('MODULE_GENERAL',   4);
define('MODULE_3RD_PARTY', 5);

/*define('MODULE_UNKNOWN', 0);
define('MODULE_FREE', 1);
define('MODULE_COMMERCIAL_OTHER', 2);
define('MODULE_COMMERCIAL_SHIPPING', 4);
define('MODULE_COMMERCIAL_PAYMENT', 8);
define('MODULE_COMMERCIAL_SKIN', 16);
define('MODULE_3RD_PARTY', 4096);*/

/**
* Base Module class. Describes the standart Module extension interface.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Module extends XLite_Model_Abstract
{
	const MODULE_UNKNOWN   = 0;
	const MODULE_PAYMENT   = 1;
	const MODULE_SHIPPING  = 2;
	const MODULE_SKIN      = 3;
	const MODULE_GENERAL   = 4;
	const MODULE_3RD_PARTY = 5;

    /**
    * Module properties.
    */
    var $fields = array(
            "module_id"     => 0,
            "name"          => "",
            "description"   => "",
            "enabled"       => 0,
            "version"       => "",
            "dependencies"  => "", 
            "type"			=> self::MODULE_UNKNOWN,
            "access_date"   => 0
            );
    
    /**
    * Contains alias for modules SQL database table.
    */
    var $alias = "modules";

    var $primaryKey = array("name");

    var $defaultOrder = "module_id";

    var $minVer = "2.0"; // required LiteCommerce version to install this mod.

    var $showSettingsForm = false;

    function constructor($name = null) // {{{
    {
        parent::constructor($name);
        if (!is_null($name)) {
            $this->read();
        }
    } // }}}

    /**
    * Abstract method to initialize concrete module instance.
    * @access public
    */
    function init() { // {{{
        func_read_classes("modules/" . $this->get("name"));
    } // }}}

	function clearModuleType($moduleType=null)
	{
		$this->xlite->_paymentMethodRegistered = 0;
		$this->xlite->_shippingMethodRegistered = 0;

		if (isset($moduleType)) {
			$this->set("type", $moduleType);
		}
	}

	function checkModuleType() // {{{
	{
		if ($this->xlite->_shippingMethodRegistered == 1) {
			$moduleType |= MODULE_COMMERCIAL_SHIPPING;
		}

		if ($this->xlite->_paymentMethodRegistered == 1) {
			$moduleType |= MODULE_COMMERCIAL_PAYMENT;
		}

		if ($this->isFree) {
			$moduleType |= MODULE_FREE;
		} else {
			if (!$this->xlite->mm->get("safeMode")) {
    			if (($moduleType && (MODULE_COMMERCIAL_SHIPPING | MODULE_COMMERCIAL_PAYMENT)) == 0) {
    				$moduleType |= MODULE_COMMERCIAL_OTHER;
    			}
    		}
		}

		$this->set("type", $this->get("type") | $moduleType);

		$this->clearModuleType();
	} // }}}

    /**
    * Adds decorator description class name for the specified class name.
    *
    * @param string $decorated The class-to-decorate name.
    * @param string $decorator The decorator class name.
    * @access public
    */
    function addDecorator($decorated, $decorator) // {{{
    {
        func_add_decorator($decorated, $decorator);
    } // }}}

    function addLayout($oldTemplate, $newTemplate) // {{{
    {
         $lay = XLite_Model_Layout::getInstance();
         $lay->addLayout($oldTemplate, $newTemplate);
    } // }}}

	/**
	* Starts installation of module; usage from inherited classes:
	* function install() {
	* 	parent::install();
	* 	// continue installation
	*   ...
	* }
	*/
    function install() // {{{
    {
        print "<pre>";
        // check module version
        $version = $this->get("minVer");
        $name = $this->get("name");
        if (version_compare($this->config->get("Version.version"), $version) < 0) {
            $this->print_msg("$name module can be installed only on LiteCommerce version $version or higher.<br>Please upgrade your shopping cart to verions $version<br>");
            $this->failure();
            return;
        }
        // execute PHP install code
        @include "classes/modules/$name/install.php";
        // execute SQL install code
        $sql = "classes/modules/$name/install.sql";
        if (is_readable($sql)) {
            if (!query_upload($sql, $this->db->connection, true)) {
                $this->failure();
                return;
            }
        }
        // execute PHP post-install code
        @include "classes/modules/$name/post-install.php";

        // complete
        $this->success();
    } // }}}

// print_msg {{{
    function print_msg($msg)
    {
        print "$msg\n";
        func_flush();
    }
// }}}

// failure {{{
    function failure()
    {
        $this->print_msg("</pre><p><b><font color=red>Failed to install module " . $this->get("name") . "</font></b><br><br>");
        $this->_add_link();
    }
// }}}    

// success {{{
	/**
	* Called from install() when installation is complete. Insert a module
	* row in the xilte_modules table and show the success message.
	*/
    function success()
    {
        if ($this->isExists()) {
            $this->update();
        } else {
            $this->create();
        }
        $this->cleanupCompileCache();

        $this->print_msg("</pre><p><b>Module " . $this->get("name") . " has been installed.</b><br><br>" );
        $this->_add_link();
    }
// }}}

    function cleanupCompileCache() // {{{
    {
        func_cleanup_cache("classes");
		func_cleanup_cache("skins");
    } // }}}

    function getZone() // {{{
    {
        if (is_null($this->zone)) {
            $this->zone = $this->get("xlite.options.skin_details.skin");
        }
        return $this->zone;
    } // }}}

// uninstall {{{
    function uninstall()
    {
        // FIXME disable module before uninstall it

        $name = $this->get("name");
        // execute SQL uninstall code
        $sql = "classes/modules/$name/uninstall.sql";
        if (is_readable($sql)) {
            if (!query_upload($sql, $this->db->connection, true)) {
                $this->failure();
                return;
            }
        }
        $zone = $this->get("zone");
        // remove module files
        unlinkRecursive("classes/modules/$name");
        unlinkRecursive("skins/admin/en/modules/$name");
        unlinkRecursive("skins/admin/en/images/modules/$name");
        unlinkRecursive("skins/$zone/en/modules/$name");
        unlinkRecursive("skins/$zone/en/images/modules/$name");
        unlinkRecursive("skins/mail/en/modules/$name");
        $this->delete();
        $this->print_msg("The module $name has been successfully uninstalled<br>");
        $this->_add_link();
        $this->cleanupCompileCache();
    }
// }}}    

// _add_link {{{
    function _add_link()
    {
        $this->print_msg("<a href='admin.php?target=modules'>Click here to return to admin zone</a>");
    }
// }}}    
    
// isDependsOn {{{
    function isDependsOn($moduleName)
    {
        $dependencies = $this->get("dependencies");
        if (empty($dependencies)) {
            return false;
        }    
        
        $dependencies = explode(",", $dependencies);
        for ($i = 0; $i < count($dependencies); $i ++) {
            $dependencies[$i] = trim($dependencies[$i]);
        }
        
        if (in_array($moduleName, $dependencies)) {
            return true;
        }
        return false;
    }

// }}}    

	function createConfig($name, $comment, $value, $category, $orderby, $type) // {{{
	{
		$up = new XLite_Model_Upgrade();
		return $up->createConfig($name, $comment, $value, $category, $orderby, $type);
	} // }}}

	function dropConfig($name, $category) // {{{
	{
		$up = new XLite_Model_Upgrade();
        return $up->dropConfig($name, $category);
	} // }}}

	function patchTemplate($zone, $template, $patch, $re = '') // {{{
	{
		$up = new XLite_Model_Upgrade();
		return $up->patchTemplate($zone, $template, $patch, $re);
	} // }}}

    function _updateProperties(array $properties = array()) // {{{
    {
        parent::_updateProperties($properties);

        if (!empty($properties["enabled"]) && !$this->session->isRegistered("safe_mode")) {
            $moduleName = $properties["name"];
            $classFile  = "modules/$moduleName/$moduleName.php";
            $className  = "Module_$moduleName";
            global $xlite_class_files;
            $xlite_class_files[strtolower($className)] = $classFile;
			// FIXME - for PHP5 compatibility
            // $this = new $className();
            parent::_updateProperties($properties);
        }
    } // }}}

    function getSettingsForm() // {{{
    {
        $name = $this->get("name");
        if ($this->showSettingsForm) {
            return "admin.php?target=module&page=$name";
        }
        return "";
    } // }}}

    function isNeedUpdate() // {{{
    {
        $dependencies = $this->get('dependencies');
        if (empty($dependencies)) return false;
        $modules = $this->xlite->mm->get('modules');
        foreach ($modules as $module) {
            if ($module->get('module_id') == $this->get('module_id')) continue;
            if ($this->isDependsOn($module->get('name')) &&
                (int)$module->get('access_date') > (int)$this->get('access_date')) return true;
        }
        return false;
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
