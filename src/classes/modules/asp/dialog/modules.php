<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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

/**
* Class description.
*
* @package ASPE
* @version $Id: modules.php,v 1.16 2008/10/23 12:05:46 sheriff Exp $
*/
class AspDialog_modules extends AspDialog
{
	var $success = true; // last operation status: true or false
	var $params = array('target');
	var $_sort_modules = null;
	
	function &getPageTemplate()
	{
		return "modules/asp/modules.tpl";
	}

	function action_install_subsequent()
	{
		if ($this->get("valid")) {
			exit;
		}
	}

	function action_install()
	{
		if (is_uploaded_file($_FILES["module_file"]['tmp_name'])) {
			if (!$this->xlite->mm->install()) {
				$this->set("valid", false);
			} else {
				$shops =& $this->getInstalledShops();
				if (is_array($shops) && count($shops) > 0) {
					echo "<br><pre>";
					foreach($shops as $shop) {
						$shopModules = $shop->getInstalledModules();
						if (is_array($shopModules) && count($shopModules) > 0) {
							$old_modules = $shop->get("installedModules");
                        	$new_modules = $shop->get("installedModules");
                        	$enabledModules = $shop->get("enabledModules");
                        	$module_key = array_search($this->xlite->mm->moduleName,$old_modules);
                            if ($module_key !== false) unset($old_modules[$module_key]); 
							$shop->set("installedModules", $old_modules);
                            $shop->update();
                            $shop->set("installedModules", $new_modules);
                            $shop->set("enabledModules", $enabledModules);
                            $shop->update();
                		}
					}
					echo "</pre>";
				}
			}
		}

		$this->action_install_subsequent();
	}

	function action_uninstall()
    {
        // uninstall and delete module
        $moduleName = $_REQUEST["module_name"];
        
        // cleanup clients caches
        set_time_limit(1800);
        $path = "/var/run/classes";
        $shop =& func_new("AspShop");
        $shops = $shop->findAll();
        // delete client references
		$table = $this->db->getTableByAlias("asp_modules");
        foreach ($shops as $cs) {
            $shop->db->query("DELETE FROM $table WHERE shop_id=".$cs->get("id")." AND module='$moduleName'");
            $cs->deleteModule($moduleName);
            unlinkRecursive($cs->get("path") . $path);
        }
        
        // delete module itself
        ob_start();
        $result = $this->xlite->mm->uninstallModule($moduleName);
        ob_end_clean();
        
        if (!$result) {
            $this->set("valid", false);
        }
    }

	function &getInstalledShops() // {{{
	{
		$shop =& func_new('AspShop');
        if (is_null($this->installedShops)) {
            $where = "";
            if (!is_null($this->get("filter"))) {
                $where .= "url LIKE '%".trim($this->get("filter"))."%'";
            }
            if (!is_null($this->get("enabled")) && strlen($this->get("enabled"))) {
                $where .= (empty($where)?"":" AND ") . "enabled=".$this->get("enabled");
            }
    		$this->installedShops = $shop->findAll($where);
        }
        return $this->installedShops;
	} // }}}

	function &getModules()
	{
		if (isset($this->modules)) {
			return $this->modules;
		}

		$modules =& $this->xlite->mm->get("modules");
		$this->modules = array();
		if (is_array($modules)) {
			for($i=0; $i<count($modules); $i++) {
				if ($modules[$i]->get("enabled")) {
					$this->modules[] = $modules[$i];
				}
			}
			for($i=0; $i<count($modules); $i++) {
				if (!$modules[$i]->get("enabled")) {
					$this->modules[] = $modules[$i];
				}
			}
		}

		return $this->modules;
	}

	function getSortModules($type=0)
	{

		$type = intval($type);
		if (is_null($this->_sort_modules)) {
			$this->_sort_modules = array();
		}
		if (isset($this->_sort_modules[$type]) && is_array($this->_sort_modules[$type])) {
			return $this->_sort_modules[$type];
		} else {
			$this->_sort_modules[$type] = array();
		}

		$temp =& $this->get("modules");
		foreach((array)$temp as $v) {
			if ($v->get("type") == MODULE_UNKNOWN) {
				$v->set("type", $this->xlite->mm->getPredefinedModuleType($v->get("name")));
				$v->update();
			}
			if ((($v->get("type") & $type) != 0)){
				$this->_sort_modules[$type][] = $v;
			}
		}

		return $this->_sort_modules[$type];
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
