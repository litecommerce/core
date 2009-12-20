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

/**
* Admin_Dialog_LiveUpdating description.
*
* @package Module_LiveUpdating
* @access public
* @version $Id$
*/
class Admin_Dialog_LiveUpdating extends Admin_Dialog
{
    var $params = array('target', 'page', 'module_name');
    var $page = "check";

	var $_copy_files_dir = "classes/modules/LiveUpdating";
	var $_copy_files_subdir = "copy";
	var $_backup_files_subdir = "backup";

    var $pages = array
    (
    	"check"		=> "Check for New Updates",
        "manage"	=> "Manage Updates",
    );

    var $pageTemplates = array
    (
    	"check"		=> "modules/LiveUpdating/check_updates.tpl",
        "manage"	=> "modules/LiveUpdating/updates_list.tpl",
    );

	var $_lastUpdate = null;
	var $_updatesNumber = null;
	var $_updates = null;
	var $errMessage = null;

	var $updates = null;
	var $foundUpdatesNumber = 0;

	var $_allUpdatesNumber = null;
	var $_appliedUpdatesNumber = null;

	var $access_warning_files = array();
	var $unused_files_list = array();

	function init()
	{
		require_once "modules/LiveUpdating/encoded.php";
		LiveUpdating_liveupdate_dialog_init($this);

		if ($_REQUEST["page"] == "manage") {
    		$this->params[] = "sortby";
    		$this->params[] = "text";
    		$this->params[] = "status";
    		$this->params[] = "importance";
    		$this->params[] = "period";

    		if (isset($_REQUEST["importance"]) && is_array($_REQUEST["importance"])) {
    			$importance = 0;
    			foreach($_REQUEST["importance"] as $imp_str) {
    				$importance |= $this->translateImportanceStr2Number($imp_str);
    			}
    			$_REQUEST["importance"] = $importance;
    		}

    		if (!isset($_REQUEST["action"]) && !(isset($_REQUEST["text"])||isset($_REQUEST["status"])||isset($_REQUEST["importance"])||isset($_REQUEST["period"]))) {
                $config =& func_new("Config");
                if ($config->find("name='filters_preferences' AND category='LiveUpdating'")) {
        			$preferences = unserialize(stripslashes($config->get("value")));
        			if (is_array($preferences)) {
        				$this->_setParameter("text", $preferences, true);
        			 	$this->_setParameter("status", $preferences, true);
        			 	$this->_setParameter("importance", $preferences, true);
        			 	if ($this->_setParameter("period", $preferences, true)) {
                			$period = $preferences["period"];
                    		if ($period != 6) {
                    			$currentTime = getdate(time());
                        		switch ($period) {
                        			case 0:		// Today
                        				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday'],$currentTime['year']);
                    					$endDateRaw = $startDateRaw;
                        			break;
                        			case 1:		// Yesterday
                        				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-1,$currentTime['year']);
                    					$endDateRaw = $startDateRaw;
                        			break;
                        			case 2:		// Current week
                        				$wday = ($currentTime['wday'] == 0) ? 7 : $currentTime['wday'];
                        				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+1,$currentTime['year']);
                        				$endDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+7,$currentTime['year']);
                        			break;
                        			case 3:		// Previous week
                        				$wday = (($currentTime['wday'] == 0) ? 7 : $currentTime['wday']) + 7;
                        				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+1,$currentTime['year']);
                        				$endDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+7,$currentTime['year']);
                        			break;
                        			case 4:		// Current month
                        				$startDateRaw = mktime(0,0,0,$currentTime['mon'],1,$currentTime['year']);
                        				$endDateRaw = mktime(0,0,0,$currentTime['mon']+1,0,$currentTime['year']);
                        			break;
                        			case 5:		// Previous month
                        				$startDateRaw = mktime(0,0,0,$currentTime['mon']-1,1,$currentTime['year']);
                        				$endDateRaw = mktime(0,0,0,$currentTime['mon'],0,$currentTime['year']);
                        			break;
                            	}
        			 			$this->_setParameter("startDateRaw", $startDateRaw);
        			 			$this->_setParameter("endDateRaw", $endDateRaw);
                    		}
                        }
        			}
                }

            }
		}

		parent::init();

		if ($_REQUEST["page"] == "manage") {
    		if (!isset($this->action)) {
    			$changedPermissions = $this->session->get("changedPermissions");
        		if (isset($changedPermissions) && is_array($changedPermissions)) {
        			foreach($changedPermissions as $filename => $changed) {
        				$accessGood = $this->chmodWWW($filename, false);
        				if ($accessGood) {
        					$accessGood = $this->chmodFTP($filename, false);
        				}
        			}
        			$this->session->set("changedPermissions", null);
        		}
				$this->session->set("wwwAccessWarning", null);
				$this->access_warning_files = array();
        	}
        }
	}

	function getLastUpdate()
	{
		if (isset($this->_lastUpdate)) {
			return $this->_lastUpdate;
		}

        $sysupdate =& func_new("SystemUpdate");
        $updates = $sysupdate->findAll("type='core'", "update_id");
        if (!(is_array($updates) && count($updates) > 0)) {
        	$last_update = 0;
        } else {
        	$last_update = $updates[count($updates)-1]->get("update_id");
        }

        $this->_lastUpdate = $last_update;

		return $this->_lastUpdate;
	}

	function getUpdatesNumber($onlyNumber=true)
	{
		$onlyNumber = (bool) $onlyNumber;
	    if ($onlyNumber) {
	    	$this->_updatesNumber = $this->xlite->get("LU_updatesNumber");
    		if (isset($this->_updatesNumber)) {
    			return intval($this->_updatesNumber);
    		}

//    		$last_update = $this->getLastUpdate();

            require_once "modules/LiveUpdating/encoded.php";
    		return LiveUpdating_getUpdatesNumber($this);
    	} else {
    		$this->getUpdates();
			if (isset($this->_updates) && is_array($this->_updates)) {
    			$this->_updatesNumber = count($this->_updates);
    		} else {
    			$this->_updatesNumber = 0;
    		}
			
			return intval($this->_updatesNumber);
    	}
	}

	function getUpdates()
	{
		if (isset($this->_updates)) {
			return $this->_updates;
		}

//		$last_update = $this->getLastUpdate();

        require_once "modules/LiveUpdating/encoded.php";
		return LiveUpdating_getUpdates($this);
	}

	function getLicenseInfo()
	{
		if (!isset($this->_license_data)) {
			$this->getUpdates();
		}

		return $this->_license_data;
	}

	function getFreeModulesInfo()
	{
		$this->getLicenseInfo();

        $dialog =& func_new("Admin_Dialog_modules");
        $currentModulesList = $dialog->getSortModules(MODULE_FREE);
        if (!is_array($currentModulesList)) {
        	$currentModulesList = array();
        }

        $remoteModulesList = $this->_license_data["addition_list"];
		$this->set("freeModulesNumber", 0);
        if (!is_array($remoteModulesList)) {
        	return array();
        }

        foreach ($remoteModulesList as $moduleKey => $moduleData) {
        	$foundModule = false;
            foreach ($currentModulesList as $module) {
            	if ($moduleData["product_code"] == $module->get("name")) {
            		$foundModule = true;
            		break;
            	}
            }
            if ($foundModule) {
            	$remoteModulesList[$moduleKey]["new_version"] =  (version_compare($module->get("version"), $moduleData["product_version"], "<")) ? true : false;
            } else {
            	$remoteModulesList[$moduleKey]["new_version"] = false;
            }

            if ($remoteModulesList[$moduleKey]["new_version"]) {
            	$this->set("freeModulesNumber", intval($this->get("freeModulesNumber")) + 1);
            }
        }

        return $remoteModulesList;
	}

	function getCommercialModulesInfo()
	{
		$this->getLicenseInfo();

		$dialog =& func_new("Admin_Dialog_modules");
		if (defined('MODULE_COMMERCICAL_OTHER')) {
			$moduleTypes = array(MODULE_COMMERCICAL_OTHER, MODULE_COMMERCICAL_SHIPPING, MODULE_COMMERCICAL_PAYMENT);
		} else {
			$moduleTypes = array(MODULE_COMMERCIAL_OTHER, MODULE_COMMERCIAL_SHIPPING, MODULE_COMMERCIAL_PAYMENT);
		}
		$currentModulesList = array();
		$currentModulesListHash = array();
		foreach ($moduleTypes as $moduleType) {
			$modulesList = $dialog->getSortModules($moduleType);
			foreach ($modulesList as $module) {
				$moduleName = $module->get("name");
				if (!isset($currentModulesListHash[$moduleName])) {
					$currentModulesListHash[$moduleName] = true;
					$currentModulesList[] = $module;
				}
			}
		}

        if (!is_array($currentModulesList)) {
        	$currentModulesList = array();
        }

        $remoteModulesList = $this->_license_data["addition_list"];
		$this->set("commercialModulesNumber", 0);
        if (!is_array($remoteModulesList)) {
        	return array();
        }

        foreach ($remoteModulesList as $moduleKey => $moduleData) {
        	$foundModule = false;
            foreach ($currentModulesList as $module) {
            	if ($moduleData["product_code"] == $module->get("name")) {
            		$foundModule = true;
            		break;
            	}
            }
            if ($foundModule) {
            	$remoteModulesList[$moduleKey]["new_version"] =  (version_compare($module->get("version"), $moduleData["product_version"], "<")) ? true : false;
            } else {
            	$remoteModulesList[$moduleKey]["new_version"] = false;
            }

            if ($remoteModulesList[$moduleKey]["new_version"]) {
            	$this->set("commercialModulesNumber", intval($this->get("commercialModulesNumber")) + 1);
            }
        }

		return $remoteModulesList;
	}

	function getModulesInfo()
	{
		if (!$this->config->get("LiveUpdating.modules_news")) {
			return null;
		}

		if (!isset($this->_modulesInfo)) {
    		$modulesInfo = array_merge
    		(
    			$this->getFreeModulesInfo(),
    			$this->getCommercialModulesInfo()
    		);

    		$this->_modulesInfo = array();
    		foreach ($modulesInfo as $module) {
    			if ($module["new_version"]) {
    				$this->_modulesInfo[$module["product_code"]] = $module;
    			}
    		}
		}
		return $this->_modulesInfo;
	}

	function getModulesUpdatesNumber()
	{
		if (!$this->config->get("LiveUpdating.modules_news")) {
			return 0;
		}

		$this->getModulesInfo();
		return (is_array($this->_modulesInfo)) ? (intval($this->get("freeModulesNumber")) + intval($this->get("commercialModulesNumber"))) : 0;
	}

	function getUpdateName($update_id)
	{
		$sysupdate =& func_new("SystemUpdate");
        $sysupdate->set("update_id", $update_id);

        return $sysupdate->get("name");
	}

	function getUpdateData($update_id, $update_item_id=0)
	{
        require_once "modules/LiveUpdating/encoded.php";
		return LiveUpdating_getUpdateData($this, $update_id, $update_item_id);
	}

	function action_download_updates()
	{
		$errorsFound = false;

		if (!(isset($this->selected) && is_array($this->selected) && count($this->selected) > 0)) {
			return;
		}

		$this->displayPageHeader("Download updates");

		foreach($this->selected as $update_id) {
			$this->webOutput("Downloading update <B>#$update_id</B> ");
			$data = $this->getUpdateData($update_id);
			if (!(isset($data) && is_array($data))) {
				$this->webOutput("<FONT color=red><B>[ERR]</B> ($this->errMessage)</FONT>");
                $errorsFound = true;
			} else {
				$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");
				
				$this->webOutput(", storing data ");

                $update_info = $data[0];
				
				$sysupdate =& func_new("SystemUpdate", $update_info["update_id"]);
				if ($sysupdate->isExists()) {
					$this->webOutput("<FONT color=red><B>[ERR]</B> (Already exists.)</FONT>");
                    $errorsFound = true;
				} else {
                    $sysupdate->set("importance", $update_info["importance"]);
                    $sysupdate->set("date", $update_info["date"]);
                    $sysupdate->set("description", $update_info["description"]);

					$info = explode(":", $update_info["version"]);
					switch ($info[0]) {
						case "LC":
							$sysupdate->set("type", "core");
							$sysupdate->set("version", trim($info[1]));
						break;

						case "M":
						default:
							$sysupdate->set("type", "module");
							$sysupdate->set("module_name", trim($info[1]));
							$sysupdate->set("version", trim($info[2]));
						break;
					}

                	$sysupdate->create();

                	foreach($update_info["items"] as $item) {
                		$sysupdate_item =& func_new("SystemUpdateItem");
                        $sysupdate_item->set("update_id", $update_info["update_id"]);
						$sysupdate_item->set("server_file_id", $item["file_item_id"]);
						$sysupdate_item->set("server_item_id", $item["update_item_id"]);
                        $sysupdate_item->set("type", $item["type"]);
                        $sysupdate_item->set("update_data", $item["update_data"]);
						$sysupdate_item->set("data_type", $item["data_type"]);
						$sysupdate_item->set("file_name", $item["file_name"]);
                        $sysupdate_item->create();
                	}
					
					$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");
                }
			}

			$this->webOutput("<HR>");
		}

        $errorsFound = ($errorsFound) ? "check" : "manage";
$doc_form = <<<EOSTR
<TABLE border=0>
<TR>
<TD>
<FORM action="admin.php" method="GET" name="return_form">
<INPUT type="hidden" name="target" value="LiveUpdating">
<INPUT type="hidden" name="page" value="$errorsFound">
<INPUT type="submit" value=" Return ">&nbsp;Return to the Administrator Zone.
</FORM>
</TD>
</TR>
</TABLE>
EOSTR;

		$this->webOutput($doc_form);
//		$this->webOutput($doc_end);
		$this->displayPageFooter();
		$this->set("silent", true);
	}

    function &getAllParams($exeptions=null)
    {
    	$allParams = parent::getAllParams();
		if ($this->page != "manage") {
			return $allParams;
		}
    	$params = $allParams;
    	if (isset($exeptions)) {
    		$exeptions = explode(",", $exeptions);
    		if (is_array($allParams) && is_array($exeptions)) {
    			$params = array();
    			foreach($allParams as $p => $v) {
    				if (!in_array($p, $exeptions)) {
    					$params[$p] = $v;
    				}
    			}
    		}
    	}
        return $params;
    }

    function getParameter($name, &$value, $udecode=false)
    {
		$value = $this->get($name);
        if (isset($value)) {
        	if ($udecode) {
            	$value = urldecode($value);
            }
        	$value = trim($value);
        	return (strlen($value) > 0) ? true : false;
        }
        return false;
    }

    function _setParameter($name, $value, $from_array=false)
    {
    	if ($from_array && is_array($value)) {
    	 	if (isset($value[$name])) {
    	 		$value = $value[$name];
				$_REQUEST[$name] = $value;
			} else {
				$value = null;
			}
    	} else {
    	 	if (isset($value)) {
				$_REQUEST[$name] = $value;
			}
		}
		return isset($value);
    }

    function checkedImportance($importance, $check)
    {
    	return (($importance & $this->translateImportanceStr2Number($check)) != 0) ? true : false;
    }

    function translateImportanceStr2Number($importance)
    {
    	switch($importance) {
    		case "critical":
    			$importance = 1;
    		break;
    		case "bug fix":
    			$importance = 2;
    		break;
    		case "new feature":
    			$importance = 4;
    		break;
    		default:
    			$importance = 0;
    		break;
    	}

    	return $importance;
    }

	function translateImportanceStr2Visual($importance)
	{
		$value = $importance;
		switch ($importance) {
			case "critical":
				$value = "Critical update";
			break;

			case "bug fix":
				$value = "Minor update";
			break;

			case "new feature":
				$value = "New feature";
			break;
		}

		return $value;
	}

    function getAllUpdatesNumber($_type="core")
    {
		static $_last_type = "";

    	if (!is_null($this->_allUpdatesNumber) && $_last_type == $_type) {
			return $this->_allUpdatesNumber;
		}

		$conditions = array();
		if (trim($_type))
			$conditions[] = "type='$_type'";

		$sysupdate =& func_new("SystemUpdate");
		$this->_allUpdatesNumber = $sysupdate->count(implode(" AND ", $conditions));

		$_last_type = $_type;
		return $this->_allUpdatesNumber;
    }

	function getModulesUpdatesList()
	{
		$list = array();
		$modules =& $this->xlite->mm->get("modules");
		foreach ($modules as $module) {
			if (!$module->get("enabled"))
				continue;

			$sql = "type='module' AND module_name='".$module->get("name")."' AND version='".$module->get("version")."'";

			$obj =& func_new("SystemUpdate");
			if (($total = $obj->count($sql)) <= 0) {
				continue;
			}

			$info = array();
			$info["updates_count"] = $total;
			$info["name"] = $module->get("name");
			$info["module_id"] = $module->get("module_id");
			$info["version"] = $module->get("version");

			$info["updates_applied"] = $obj->count("status='A' AND ".$sql);
			$info["updates_not_applied"] = $obj->count("status='N' AND ".$sql);

			$updates = $obj->findAll($sql);
			foreach ($updates as $update) {
				$info["updates_list"][] = $update->get("properties");
			}

			$list[] = $info;
		}

		return $list;
	}

	function action_apply_modules_updates()
	{

		if (is_array($this->modules) && count($this->modules) > 0) {
			$this->set("silent", true);

			$this->module_name = implode(",", $this->modules);

			$dialog =& func_new("Admin_Dialog_LiveUpdating");

			// copy all params
			foreach($this->getAllParams() as $name => $val) {
				$dialog->$name = $val;
			}

			$dialog->action_apply_all();
		}

	}

	function action_undo_module_updates()
	{
		if (!trim($this->module_name))
			return;

		$this->set("silent", true);
		$dialog =& func_new("Admin_Dialog_LiveUpdating");

		// copy all params
		foreach($this->getAllParams() as $name => $val) {
			$dialog->$name = $val;
		}

		$dialog->action_undo_all();
	}

    function getAppliedUpdatesNumber($_type="core")
    {
		static $_last_update = "";

    	if (!is_null($this->_appliedUpdatesNumber) && $last_type == $_type) {
			return $this->_appliedUpdatesNumber;
		}

		$conditions = array("status='A'");
		if (trim($_type))
			$conditions[] = "type='$_type'";

		$sysupdate =& func_new("SystemUpdate");
		$this->_appliedUpdatesNumber = $sysupdate->count(implode(" AND ", $conditions));

		$_last_type = $_type;
		return $this->_appliedUpdatesNumber;
    }

	function getNotAppliedUpdatesNumber($_type="core")
	{
		static $_last_update = "";
		if (!is_null($this->_notAppliedUpdatesNumber) && $last_type == $_type) {
			return $this->_notAppliedUpdatesNumber;
		}

		$sysupdate =& func_new("SystemUpdate");
		$this->_notAppliedUpdatesNumber = $sysupdate->count("status='N' AND type='".$_type."'");

		$_last_type = $_type;
		return $this->_notAppliedUpdatesNumber;
	}

    function isUpToDate()
    {
    	return ($this->getAllUpdatesNumber() > 0 && $this->getAllUpdatesNumber() == $this->getAppliedUpdatesNumber());
    }

    function &getDownloadedUpdates($_type=null)
    {
        if (is_null($this->updates)) {
            $sysupdate =& func_new("SystemUpdate");
            
            $condition = array();
			
            if ($this->getParameter("text", $text)) {
				$condition[] = "(update_id LIKE '%".addslashes($text)."%' OR description LIKE '%".addslashes($text)."%')";
            }
			if (!is_null($_type)) {
				$condition[] = "type='$_type'";
			}
            if (!$this->getParameter("status", $status)) {
            	$this->set("status", "0");
            }
            if ($this->getParameter("status", $status)) {
            	if ($status != "0") {
					$condition[] = "(status='$status')";
				}
            }
            if (!$this->getParameter("importance", $importance)) {
            	$this->set("importance", "7");
            }
            if ($this->getParameter("importance", $importance)) {
            	if ($importance > 0 && $importance <= 7) {
            		$importance_condition = array();
            		if (($importance & 1) != 0) {
            			$importance_condition[] = "importance='critical'";
            		}
            		if (($importance & 2) != 0) {
            			$importance_condition[] = "importance='bug fix'";
            		}
            		if (($importance & 4) != 0) {
            			$importance_condition[] = "importance='new feature'";
            		}
            		$importance_condition = implode(" OR ", $importance_condition);
					$condition[] = "($importance_condition)";
				}
            }
            if (!$this->getParameter("period", $period)) {
            	$this->set("period", "-1");
            }
            if ($this->getParameter("period", $period)) {
            	if ($period >= 0) {
                	if ($period != 6) {
                    	$date = getdate($this->get("startDateRaw"));
    					$this->set("startDate", mktime(0,0,0,$date['mon'],$date['mday'],$date['year']));
                    	$date = getdate($this->get("endDateRaw"));
    					$this->set("endDate", mktime(0,0,0,$date['mon'],$date['mday'],$date['year']));
                		$this->params[] = "startDateRaw";
                		$this->params[] = "endDateRaw";
                	}
    				$condition[] = "(date>='".$this->get("startDate")."' AND date<'".($this->get("endDate")+24*3600)."')";
				}
            }

            $condition = implode(" AND ", $condition);
			
			$sortby = $this->get("sortby");
          	if (!(isset($sortby) && ($sortby == "update_id" || $sortby == "date" || $sortby == "importance"))) {
          		$sortby = "update_id DESC";
          		$this->sortby = "update_id";
          	} else {
          		if ($sortby == "update_id" || $sortby == "date") {
          			$sortby .= " DESC";
          		}
          	}
            
            $this->updates = $sysupdate->findAll($condition, $sortby);
            
            if (is_array($this->updates)) {
            	$this->foundUpdatesNumber = count($this->updates);
            } else {
            	$this->updates = null;
            	$this->foundUpdatesNumber = 0;
            }
        }
        return $this->updates;
    }

    function action_save_filters()
    {
		$preferences = array();
        if ($this->getParameter("text", $text)) {
			$preferences["text"] = $text;
        }
        if ($this->getParameter("status", $status)) {
			$preferences["status"] = $status;
		} else {
			$preferences["status"] = "0";
		}
        if ($this->getParameter("type", $type)) {
			$preferences["type"] = $type;
		} else {
			$preferences["type"] = "7";
		}
        if ($this->getParameter("period", $period)) {
			$preferences["period"] = $period;
        }

        $config =& func_new("Config");
        $update_config = true;
        if (!$config->find("name='filters_preferences' AND category='LiveUpdating'")) {
        	$update_config = false;
            $config->set("name", "filters_preferences");
            $config->set("category", "ProductAdviser");
            $config->set("type", "serialized");
        }

		$config->set("value", addslashes(serialize($preferences)));

        if ($update_config) {
            $config->update();
        } else {
            $config->create();
        }
    }

	function createFileCopy($filename)
	{
		$folder_name = $this->_copy_files_subdir;

		$currDir = getcwd();
		@chdir($this->_copy_files_dir);
		@mkdir($folder_name);
		@chdir($folder_name);

		if (substr(getcwd(), strlen(getcwd())-strlen($folder_name)) == $folder_name) {
			if (preg_match("/^.+\/[^\/]+$/", $filename)) { // is path
				$folders = explode("/", dirname($filename));

				if (is_array($folders) && count($folders) > 0) {
					foreach ($folders as $folder) {
						if (strlen(trim($folder)) == 0 || $folder == "." || $folder == "..")
							continue;

						@mkdir($folder);
						@chdir($folder);
						if (substr(getcwd(), strlen(getcwd())-strlen($folder)) != $folder) {
							@chdir($currDir);
							return false;
						}
					}
				}
			}

			$destDir = getcwd();
			@chdir($currDir);

			copy($filename, $destDir . "/" . basename($filename));
			return true;
		} else {
			@chdir($currDir);
			return false;
		}

	}

    function restoreFileCopy($filename)
    {
        $currDir = getcwd();
        @chdir($this->_copy_files_dir."/".$this->_copy_files_subdir);

		if (substr(getcwd(), strlen(getcwd())-strlen($this->_copy_files_subdir)) == $this->_copy_files_subdir) {
			$destDir = getcwd();
			@chdir($currDir);

			copy($destDir . "/" . $filename, $filename);
			return true;
		} else {
			@chdir($currDir);
			return false;
		}
	}

    function action_apply_all()
    {
		$updates = array();
		$update_modules = array();
		$sysupdate =& func_new("SystemUpdate");
		$conditions = array("status='N'");

		if ($this->upload_files) {
			$this->mode = "";
		}

		if (!is_null($this->module_name)) {
			$conditions[] = "type='module'";

			$temp = explode(",", $this->module_name);
			foreach ($temp as $name) {
				$module =& func_new("Module", $name);
				if (!$module->is("enabled"))
					continue;

				$info["name"] = $module->get("name");
				$info["version"] = $module->get("version");
				$update_modules[] = $info;
			}

			foreach ($update_modules as $module) {
				$obj =& func_new("SystemUpdate");
				$objs = $obj->findAll(implode(" AND ", $conditions)." AND module_name='".$module["name"]."' AND version='".$module["version"]."'", "update_id ASC");
				foreach ($objs as $object)
					$updates[] = $object;
			}
		} else {
			$conditions[] = "type='core'";
			$updates = $sysupdate->findAll(implode(" AND ", $conditions), "update_id ASC");
		}


        if (!is_array($updates)) {
        	return;
        }

		$this->set("silent", true);

		$this->displayPageHeader("Update process");

		if ($this->mode != "process" && !$this->upload_files) {
			$this->webOutput("<H2>Update - Checking phase</H2>");
		} elseif ($this->upload_files) {
			$this->webOutput("<H2>Update - Uploading files</H2>");
		} else {
			$this->webOutput("<H2>Update - Updating phase</H2>");
		}

		// Display info modules string
		if (is_array($update_modules) && count($update_modules) > 0) {
			$strs = array();
			foreach ($update_modules as $update) {
				$strs[] = $update["name"]." ".$update["version"];
			}

			$this->webOutput("<H3>Module".((count($update_modules) > 1) ? "s" :"").": ".implode(", ", $strs)."</H3>");
		}

		// Make a copy of all required files
		$filesRequired = 0;
		$errorsFound = 0;
		if ($this->mode == "process") {
			foreach ($updates as $update) {
				$update_id = $update->get("update_id");

				$updateItem =& func_new("SystemUpdateItem");
				$items = $updateItem->findAll("update_id='".$update_id."'");
				foreach ($items as $item) {
					$update_data = unserialize(stripslashes($item->get("update_data")));

					if (!in_array($update_data["type"], array("diff", "function", "file")))
						continue;

					
					if (!$this->createFileCopy($update_data["file"])) {
						$errorsFound++;
						break;
					}
				}

				if ($errorsFound != 0)
					break;
			}
		}

		if ($errorsFound == 0) {
	        foreach ($updates as $update) {
    	    	$this->update_id = $update->get("update_id");
//        		$errorsFound += $this->_action_apply();

				list($errors, $files) = $this->_action_apply();
				$errorsFound += $errors;
				$filesRequired += $files;

	        }
			$errorsFound += $filesRequired;
		}

		// Apply changes - copy changed/patched files
		if ($this->mode == "process" && $errorsFound == 0) {
			foreach ($updates as $update) {
				$update_id = $update->get("update_id");
                $updateItem =& func_new("SystemUpdateItem");

                $items = $updateItem->findAll("update_id='".$update_id."'");
                foreach ($items as $item) {
					$update_data = unserialize(stripslashes($item->get("update_data")));
					if (!$this->restoreFileCopy($update_data["file"])) {
						$errorsFound++;
						break;
					}
				}

				if ($errorsFound == 0) {
					$update->set("status", "A");
					$update->set("applied", time());
					$update->update();
				} else {
					break;
				}
			}
		}

		if ($this->mode == "" && !$this->upload_files && $filesRequired) {
			$notesSection = <<<EOSTR
<a name="notes_section"></a>
<table border="0">
<tr>
	<td><b>Notes:</b></TD>
</tr>
<tr>
	<td><FONT color=blue><b>Update can be performed by completely replacing this file. The file will be uploaded from Update Server repository. Your file will be saved and replaced with the correct file.</b></FONT></td>
</tr>
</table>
EOSTR;
		}

		// display manage buttons
		if ($this->mode != "process") {
			$fields1 = $this->prepareFormFields("");
			$fields2 = $this->prepareFormFields("action,mode");

			if ($errorsFound == 0) {
    	        $continueEnabled = <<<EOSTR
<TD>
<FORM action="admin.php" method="POST" name="process_update_form">
<INPUT type="hidden" name="action" value="apply_all">
<INPUT type="hidden" name="mode" value="process">
$fields2
&nbsp;<INPUT type="submit" value=" Continue " class="DialogMainButton">&nbsp;Continue update process.
</FORM>
</TD>
EOSTR;
			}

			if ($filesRequired > 0) {
				$filesUploadRequired = <<<EOSTR
<TD>
<FORM action="admin.php" method="POST" name="files_update_form">
<INPUT type="hidden" name="action" value="apply_all">
<INPUT type="hidden" name="mode" value="">
<INPUT type="hidden" name="upload_files" value="1">
$fields2
&nbsp;<INPUT type="submit" value="Upload files">&nbsp;Upload required files.
</FORM>
</TD>
EOSTR;
			}

			if ($errorsFound && $this->upload_files) {
				$checkAgainEnabled = <<<EOSTR
<TD>
<FORM action="admin.php" method="POST" name="recheck_update_form">
<INPUT type="hidden" name="action" value="apply_all">
<INPUT type="hidden" name="mode" value="">
$fields2
&nbsp;<INPUT type="submit" value="Check again">&nbsp;Perform the check again.
</FORM>
</TD>
EOSTR;
			}

			$doc_form = <<<EOSTR
$notesSection
<br>
<TABLE border=0>
<TR>
<TD>
<FORM action="admin.php" method="GET" name="cancel_update_form">
$fields1
<INPUT type="submit" value=" Cancel ">&nbsp;Cancel and return to the Administrator Zone.
</FORM>
</TD>
$continueEnabled
$filesUploadRequired
$checkAgainEnabled
<TD>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
</TR>
</TABLE>
EOSTR;
		} else {
			if ($this->session->get("wwwAccessWarning")) {
				$doc_warning = <<<EOSTR
<FONT color=red><B>Update manager has detected that certain file(s) can be overwritten by the web-server application.<BR>Please make sure you set secure file permissions (</B></FONT><FONT color=blue><B>chmod 0644</B></FONT><FONT color=red><B>) before you proceed with using LiteCommerce.</B></FONT>
EOSTR;
				$this->webOutput($doc_warning);

				// display Access Warning files list
				if (is_array($this->access_warning_files) && count($this->access_warning_files) > 0) {
					$list = "<pre>";
					foreach (array_unique($this->access_warning_files) as $file) {
						$file = str_replace($this->_copy_files_dir."/".$this->_copy_files_subdir."/", "", $file);
						$list .= $file . "<br>";
					}
					$list .= "</pre>";

					$this->webOutput($list);
				}

				$this->webOutput("<hr>");
			}

			$fields = $this->prepareFormFields();
$doc_form = <<<EOSTR
<TABLE border=0>
<TR>
<TD>
<FORM action="admin.php" method="GET" name="cancel_update_form">
$fields
<INPUT type="submit" value=" Continue ">&nbsp;Return to the Administrator Zone.
</FORM>
</TD>
</TR>
</TABLE>
EOSTR;
		}

		func_cleanup_cache("classes");
		func_cleanup_cache("skins");

		$this->webOutput($doc_form);

		$this->displayPageFooter();
    }

    function _action_apply()
    {
		if (!(isset($this->update_id) && !empty($this->update_id))) {
    		return;
    	}

    	$update =& func_new("SystemUpdate");
    	if (!$update->find("update_id='".$this->update_id."'")) {
    		return;
    	}
    	if ($update->get("status") != "N") {
    		return;
    	}

		if (!$update->isDependenciesResolved()) {
			return;
		}

    	$updateItem =& func_new("SystemUpdateItem");
        $updates = $updateItem->findAll("update_id='".$this->update_id."'");

    	if (count($updates) == 0) {
    		return;
    	}

		$this->showInfo($update);
		$div_id = md5($update->get("update_id"));
		$div_id_result = $div_id."_result";

		$result_table = <<<EOSTR
<table border="0">
	<tr>
		<td><a href="javascript: void(0);" OnClick="sectionShowHide('$div_id');">[+]</a></td>
		<td><div id="$div_id_result">In progress...</div></td>
	</tr>
</table>
<div id="$div_id" style="DISPLAY: none; BACKGROUND-COLOR: #f0f0f0;">
EOSTR;

		$this->webOutput($result_table);

        $errorsFound = 0;
		$filesRequired = 0;
		$backuped = array();
        foreach($updates as $ui) {
			$update_data = $ui->get("update_data");

			if (strlen($update_data) <= 0) {
				$errorsFound ++;				
				continue;
			}

			$update_data = unserialize(stripslashes($update_data));
			if (!is_array($update_data)) {
				$errorsFound ++;
				continue;
			}
			if ($update_data["type"] != $ui->get("type")) {
				$errorsFound ++;
				continue;
			}

			// skip checking (diff, function) updates if file update exists.
			if ($ui->get("server_file_id") > 0) {
				$obj =& func_new("SystemUpdateItem");
				if ($obj->find("update_id='".$this->update_id."' AND server_item_id='".$ui->get("server_file_id")."' AND type='file'")) {
//					$errorsFound ++;
					continue;
				}
			}
			if ($this->mode == "process") {
				$update_data["file"] = $this->_copy_files_dir."/".$this->_copy_files_subdir."/".$update_data["file"];
			}

			switch($update_data["type"]) {
				case "file":
				case "function":
				case "diff":
					$errorFound = false;

					$this->webOutput("File: <B>".$update_data["file"]."</B>");
					$this->webOutput(", access: ");

					// if file not exists - create it and upload content
					if ($update_data["type"] == "file" && !file_exists($update_data["file"])) {
						if ($fhandle = fopen($update_data["file"], 'w')) {
							fclose($fhandle);

							$this->updateFileData($update_data["file"], $update_data["file_content"], $ui->get("data_type"));
							$this->webOutput("<FONT color=green><B>[CREATED]</B></FONT> ");

							$ui->set("file_new", 1);
							$ui->update();
						}
					}

					$accessGood = $this->checkFileWritable($update_data["file"]);
					if (!$accessGood) {
						$accessGood = $this->chmodWWW($update_data["file"]);
    					if (!$accessGood) {
    						$this->session->set("wwwAccessWarning", false);
    						$accessGood = $this->chmodFTP($update_data["file"]);
    					} else {
							$this->access_warning_files[] = $update_data["file"];
    						$this->session->set("wwwAccessWarning", true);
    					}
					} else {
						$this->access_warning_files[] = $update_data["file"];
						$this->session->set("wwwAccessWarning", true);
					}
					$backupGood = $this->backupFile($update);
					if (!$backupGood) {
						$accessGood = false;
					}
					if ($accessGood) {
						$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");
					} else {
						$this->webOutput("<FONT color=red><B>[ERR]</B></FONT>");
						$errorFound = true;
					}

					// Check function
					if ($update_data["type"] == "function") {
						$this->webOutput(", update occurrence: ");
						if (!isset($update_data["insert_after"])) {
                    		$hunkGood = $this->checkFunctionHunk($update_data["file"], $update_data["function_name"]);
                    	} else {
                    		$hunkGood = $this->checkFunctionHunk($update_data["file"], $update_data["insert_after"]);
                    	}
    					if ($hunkGood !== false) {
    						$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");
    					} else {
    						$this->webOutput("<FONT color=red><B>[ERR]</B></FONT>");
							$errorFound = true;
    					}
                    }

					// Check diff
					if ($update_data["type"] == "diff") {
						$this->webOutput(", update occurrence: ");
                    	$hunkGood = $this->checkDiffHunk($update_data["file"], $update_data["search_data"]);
    					if ($hunkGood !== false) {
    						$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");
    					} else {
    						$this->webOutput("<FONT color=red><B>[ERR]</B></FONT>");
							$errorFound = true;
    					}
                    }

					// display warning/error messages
					if (!$this->upload_files) {
						if (!$accessGood) {
							$this->webOutput("<UL type=\"disk\"><LI>Please make sure that you set write permissions on file: <FONT color=blue><B>chmod 0666 $update_data[file]</B></FONT>");
							if (!$backupGood) {
								$this->webOutput("<LI>Please make sure that you set write permissions on directory: <FONT color=blue><B>chmod 0777 classes/modules/LiveUpdating</B></FONT>");
							}
							$this->webOutput("</UL>");
						}

						if (($update_data["type"] == "function" || $update_data["type"] == "diff") && $hunkGood === false) {
							$this->webOutput("<UL type=\"disk\"><LI>The file seems to be different from the original.</UL>");
						}

						if (in_array($update_data["type"], array("diff", "function")) && $hunkGood === false) {
							if ($update_data["type"] == "diff" && $hunkGood === false) {
								$this->webOutput("String not found:<br>");
								$code = $update_data["search_data"];
							}

							if ($update_data["type"] == "function" && $hunkGood === false) {
								$this->webOutput("Unable to find function:<br>");
								$code = $update_data["insert_after"];
							}

							$html = <<<EOSTR
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td>&nbsp;&nbsp;</td>
	<td bgcolor="white">&nbsp;</td>
	<td bgcolor="white" width="95%"><pre><br>$code</pre></td>
	<td>&nbsp;&nbsp;</td>
</tr>
</table>
EOSTR;
							$this->webOutput($html);
						}
					}

					// upload files
					if (($update_data["type"] == "function" || $update_data["type"] == "diff") && $hunkGood === false) {
						$update_item_id = $ui->get("server_file_id");

						// not NULL if update exists on update server
						if ($update_item_id > 0) {
							$filesRequired++;

							if ($this->mode == "" && !$this->upload_files) {
								$this->webOutput("<br><FONT color=blue><b>See </b></FONT><a href='#notes_section'><FONT color=blue><b><u>Notes section</u></b></FONT></a><FONT color=blue><b> for details.</b></FONT><br><br>");
							}
						} else {
							if ($this->upload_files) {
								$this->webOutput("<p>");
							}

							$this->webOutput("<br><FONT color=red><b>The file is not found in repository and cannot be replaced.</b></FONT><br><br>");
						}

						if ($update_item_id > 0 && $this->upload_files) {
							$obj =& func_new("SystemUpdateItem");

							if (!$obj->find("type='file' AND server_item_id='$update_item_id' AND update_id='".$this->update_id."'")) {
								$item = $this->getUpdateData($this->update_id, $update_item_id);

								if ($item && $item["update_data"]) {
									$file_item =& func_new("SystemUpdateItem");
									$file_item->set("server_item_id", $update_item_id);
									$file_item->set("update_id", $this->update_id);
									$file_item->set("type", "file");
									$file_item->set("data_type", $item["data_type"]);
									$file_item->set("file_name", $item["file_name"]);
									$file_item->set("update_data", $item["update_data"]);
									$file_item->create();

									$this->webOutput("<p><font color=green><b>The file was successfully downloaded from repository.</b></font><br><br>");
//									$errorFound = false;
									$filesRequired--;
								} else {
									$errorFound = true;
									$this->webOutput("<p><font color=red><b>Unable to download the file from repository.</b></font><br><br>");
								}
							}
						}
					}

					if ($errorFound) {
						$errorsFound ++;
					} elseif ($this->mode == "process") {
						$this->webOutput(", updating: ");

						$backupGood = $this->backupFile($update, ((!isset($backuped[$update_data["file"]])) ? $update_data["file"] : null));
                        $backuped[$update_data["file"]] = true;
    					if ($backupGood !== false) {
    						$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");

							// Process file
							if ($update_data["type"] == "file") {
								$this->updateFileData($update_data["file"], $update_data["file_content"], $ui->get("data_type"));
							}

							// Process function
							if ($update_data["type"] == "function") {
                                $this->updateFunctionData($update_data["file"], $hunkGood, $update_data["function_code"], isset($update_data["insert_after"]));
                            }

							// Process diff
							if ($update_data["type"] == "diff") {
                                $this->updateDiffData($update_data["file"], $hunkGood, $update_data["search_data"], $update_data["replace_data"]);
                            }

                            $uif =& func_new("SystemUpdateItemFile");
                            if (!$uif->find("update_id='".$this->update_id."' AND filename='".$update_data["file"]."'")) {
                            	$uif->set("update_id", $this->update_id);
                            	$uif->set("filename", $update_data["file"]);
                            	$uif->create();
                            }

//                            $update->set("status", "A");
//                            $update->set("applied", time());
                            $update->update();
    					} else {
    						$this->webOutput("<FONT color=red><B>[ERR]</B></FONT>");
							$errorFound = true;
    					}
					}

					$this->webOutput("<BR>");
				break;
				case "table":
                    // Check SQL
                    if ($update_data["type"] == "table") {
                        $this->webOutput("Update occurrence: ");
                        $this->webOutput("<FONT color=green><B>[OK]</B></FONT>");
						$this->webOutput("<BR><BR>");
                    }

					if ($errorFound) {
						$errorsFound ++;
					} elseif ($this->mode == "process") {
						if (!$this->updateSqlData($update_data["sql_patch"], null, true))
							$errorsFound = true;

						$update->set("status", "A");
						$update->set("applied", time());
						$update->update();
					}
				break;
			} // /switch
        } // foreach

		$this->webOutput("</div>");

		$this->webOutput("<BR>");

		$message = "";
		if ($errorsFound == 0) {
			$message = "<FONT color=green><B>No errors found.</B></FONT>";
		} else {
			$js_code = <<<EOSTR
<script type="text/javascript" language="JavaScript 1.2">
sectionShowHide('$div_id');
</script>
EOSTR;
			$this->webOutput($js_code);
			$message = "<FONT color=red><B>Some errors were found.</B></FONT> Please correct problem(s) before continue.";
		}

$js_code = <<<EOSTR
<script type="text/javascript" language="JavaScript 1.2">
sectionHeader('$div_id', '$message');
</script>
EOSTR;

		$this->webOutput($js_code);

		$this->webOutput("<br><HR><br>");

		return array($errorsFound, $filesRequired);
    }

	function action_undo_all()
	{
		$module_version = "";
		$sysupdate =& func_new("SystemUpdate");
        $conditions = array("status='A'");
        if (!is_null($this->module_name)) {
            $conditions[] = "type='module'";

			$module =& func_new("Module", $this->module_name);
			if (!$module->is("enabled"))
				return;

			$module_version = $module->get("version");
            $conditions[] = "module_name='".$this->module_name."'";
			$conditions[] = "version='".$module_version."'";
        } else {
            $conditions[] = "type='core'";
        }

		$updates = $sysupdate->findAll(implode(" AND ", $conditions), "update_id DESC");

		if (!is_array($updates)) {
			return;
		}

		$this->set("silent", true);

		$this->displayPageHeader("Undo process");

		if ($this->mode != "process") {
			$this->webOutput("<H2>Undo - Checking phase</H2>");
		} else {
			$this->webOutput("<H2>Undo - Undo phase</H2>");
		}

		if (trim($this->module_name)) {
			$this->webOutput("<H3>Module: ".$this->module_name." ".$module_version."</H3>");
		}

		$errorsFound = 0;
		foreach ($updates as $update) {
			$this->update_id = $update->get("update_id");
			$errorsFound += $this->_action_undo();
		}

		if ($errorsFound == 0) { 
			$continueEnabled = "<INPUT type=\"submit\" value=\" Continue \" class=\"DialogMainButton\">&nbsp;Continue undo process.";
		}

        if ($this->mode != "process") {
            $fields1 = $this->prepareFormFields();
            $fields2 = $this->prepareFormFields("action,mode");
$doc_form = <<<EOSTR
<TABLE border=0>
<TR>
<TD>
<FORM action="admin.php" method="GET" name="cancel_update_form">
$fields1
<INPUT type="submit" value=" Cancel ">&nbsp;Cancel and return back.
</FORM>
</TD>
<TD>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
<TD>
<FORM action="admin.php" method="POST" name="process_update_form">
<INPUT type="hidden" name="action" value="undo_all">
<INPUT type="hidden" name="mode" value="process">
$fields2
$continueEnabled
</FORM>
</TD>
</TR>
</TABLE>
EOSTR;
        } else {
            if ($this->session->get("wwwAccessWarning")) {
                $doc_warning = <<<EOSTR
<FONT color=red><B>Undo manager has detected that certain file(s) can be overwritten by the web-server application.<BR>Please make sure you set secure file permissions (</B></FONT><FONT color=blue><B>chmod 0644</B></FONT><FONT color=red><B>) before you proceed with using LiteCommerce.</B></FONT>
EOSTR;
                $this->webOutput($doc_warning);

                // display Access Warning files list
                if (is_array($this->access_warning_files) && count($this->access_warning_files) > 0) {
                    $list = "<pre>";
                    foreach (array_unique($this->access_warning_files) as $file) {
                        $list .= $file . "<br>";
                    }
                    $list .= "</pre>";

                    $this->webOutput($list);
                }

				// display list of unused files
				if (is_array($this->unused_files_list) && count($this->unused_files_list) > 0) {
					$unused_msg = <<<EOSTR
<p>
<hr>
<font color="blue">The following file(s) appear to be not used and can be removed or moved to a temporary directory.</font>
EOSTR;
					$this->webOutput($unused_msg);

					$list = "<pre>";
					foreach ($this->unused_files_list as $file) {
						$list .= "<font color=blue>$file [".@filesize($file)." bytes]</font><br>";
					}
					$list .= "</pre>";

					$this->webOutput($list);
				}


                $this->webOutput("<hr>");
            }

            $fields = $this->prepareFormFields();
$doc_form = <<<EOSTR
<TABLE border=0>
<TR>
<TD>
<FORM action="admin.php" method="GET" name="cancel_update_form">
$fields
<INPUT type="submit" value=" Continue ">&nbsp;Return to the Administrator Zone.
</FORM>
</TD>
</TABLE>
EOSTR;
        }

		func_cleanup_cache("classes");
		func_cleanup_cache("skins");

        $this->webOutput($doc_form);

        $this->displayPageFooter();
	}

    function _action_undo()
    {
    	if (!(isset($this->update_id) && !empty($this->update_id))) {
    		return;
    	}

    	$update =& func_new("SystemUpdate");
    	if (!$update->find("update_id='".$this->update_id."'")) {
    		return;
    	}
    	if ($update->get("status") != "A") {
    		return;
    	}

    	$updateItem =& func_new("SystemUpdateItem");
        $updates = $updateItem->findAll("update_id='".$this->update_id."'");

		if (count($updates) == 0) {
			return;
		}

        $this->showInfo($update);

        $div_id = md5($update->get("update_id"));
        $div_id_result = $div_id."_result";

		$result_table = <<<EOSTR
<table border="0">
    <tr>
        <td><a href="javascript: void(0);" OnClick="sectionShowHide('$div_id');">[+]</a></td>
        <td><div id="$div_id_result">In progress...</div></td>
    </tr>
</table>
<div id="$div_id" style="DISPLAY: none; BACKGROUND-COLOR: #f0f0f0;">
EOSTR;

		$this->webOutput($result_table);

        $errorsFound = 0;
        $backuped = array();
        foreach($updates as $ui) {

			$update_data = $ui->get("update_data");
			if (strlen($update_data) <= 0) {
				$errorsFound ++;
				continue;
			}
			$update_data = unserialize(stripslashes($update_data));
			if (!is_array($update_data)) {
				$errorsFound ++;
				continue;
			}
			if ($update_data["type"] != $ui->get("type")) {
				$errorsFound ++;
				continue;
			}

			// Collect all 'file_new' update items. This will be the list
			// of unused files.
			if ($this->mode == "process" && $update_data["type"] == "file"){
				$file_name_md5 = $update_data["file"];
				if ($ui->get("file_new")) {
					$this->unused_files_list[$file_name_md5] = $update_data["file"];

					$ui->set("file_new", 0);
					$ui->update();
				} else {
					unset($this->unused_files_list[$file_name_md5]);
				}
			}

			switch($update_data["type"]) {
				case "file":
				case "function":
				case "diff":
					$errorFound = false;
					$this->webOutput("File: <B>".$update_data["file"]."</B>");
					$this->webOutput(", access: ");

					$accessGood = 1;
					if (file_exists($update_data["file"])) {
						$accessGood = $this->checkFileWritable($update_data["file"]);
						if (!$accessGood) {
							$accessGood = $this->chmodWWW($update_data["file"]);
    						if (!$accessGood) {
    							$this->session->set("wwwAccessWarning", false);
    							$accessGood = $this->chmodFTP($update_data["file"]);
    						} else {
								$this->access_warning_files[] = $update_data["file"];
    							$this->session->set("wwwAccessWarning", true);
	    					}
						} else {
							$this->access_warning_files[] = $update_data["file"];
							$this->session->set("wwwAccessWarning", true);
						}
					} else {
						$this->webOutput("<FONT color=blue><B>[NOT EXISTS] </B></FONT>");
					}

					if (!isset($backuped[$update_data["file"]])) {
						$backupGood = $this->checkBackupFile($update, $update_data["file"]);
						if (!$backupGood) {
							$accessGood = false;
						} else {
							$backuped[$update_data["file"]] = true;
						}
					} else {
						$backupGood = true;
					}
					if ($accessGood) {
						$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");
					} else {
						$this->webOutput("<FONT color=red><B>[ERR]</B></FONT>");
						if (!$backupGood) {
							$this->webOutput("&nbsp;<FONT color=red>(the backuped file is not accessible)</FONT>");
						}
						$errorFound = true;
					}

					if (!$accessGood) {
						$this->webOutput("<UL type=\"disk\"><LI>Please make sure that you set write permissions on file: <FONT color=blue><B>chmod 0666 $update_data[file]</B></FONT>");
						if (!$backupGood) {
							$this->webOutput("<LI>Please make sure that you set write permissions on directory: <FONT color=blue><B>chmod 0777 classes/modules/LiveUpdating/backup</B></FONT>");
							$this->webOutput("<LI>Please&nbsp;make&nbsp;sure&nbsp;that&nbsp;you&nbsp;set&nbsp;write&nbsp;permissions&nbsp;on&nbsp;file:&nbsp;<FONT color=blue><B>chmod&nbsp;0666&nbsp;" . $this->getBackupFile($update, $update_data["file"]) . "</B></FONT>");
						}
						$this->webOutput("</UL>");
					}

					if ($errorFound) {
						$errorsFound ++;
					} elseif ($this->mode == "process") {
						$this->webOutput(", restoring: ");

                        $backupGood = $this->restoreFile($update, $update_data["file"]);
    					if ($backupGood !== false) {
    						$this->webOutput("<FONT color=green><B>[OK]</B></FONT>");

                            $update->set("status", "N");
                            $update->set("applied", 0);
                            $update->update();

                            $uif =& func_new("SystemUpdateItemFile");
                            if ($uif->find("update_id='".$this->update_id."' AND filename='".$update_data["file"]."'")) {
                            	$uif->delete();
                            }
    					} else {
    						$this->webOutput("<FONT color=red><B>[ERR]</B></FONT>");
							$errorFound = true;
    					}
					}

					$this->webOutput("<BR>");
				break;
				case "table":
					$this->webOutput("SQL, restoring: ");
					$this->webOutput("<FONT color=green><B>[SKIPED]</B></FONT>");

					if ($this->mode == "process") {
						$this->webOutput("<br><FONT color=green><B>Unable to undo DB changes.</B></FONT>");

						$update->set("status", "N");
						$update->set("applied", 0);
						$update->update();
					}
				break;
			} // switch()
        } // foreach()

		$this->webOutput("</div>");

		$this->webOutput("<BR>");

		$message = "";
		if ($errorsFound == 0) {
			$message = "<FONT color=green><B>No errors found.</B></FONT>";
		} else {
            $continueDisabled = "disabled";
			$js_code = <<<EOSTR
<script type="text/javascript" language="JavaScript 1.2">
sectionShowHide('$div_id');
</script>
EOSTR;
			$this->webOutput($js_code);
			$message = "<FONT color=red><B>Some errors were found.</B></FONT> Please correct problem(s) before continue.";
		}

$js_code = <<<EOSTR
<script type="text/javascript" language="JavaScript 1.2">
sectionHeader('$div_id', '$message');
</script>
EOSTR;

		$this->webOutput($js_code);
		$this->webOutput("<br><hr><br>");

		return $errorsFound;
    }

    function showInfo($update)
    {
		$name = $update->get("name");
		$importance = $update->get("importance");
		$descr = $update->get("description");
$doc_info = <<<EOSTR
<TABLE border=0>
<TR>
<TD><B>Update:</B>&nbsp;</TD><TD>$name</TD>
</TR>
<TR>
<TD><B>Importance:</B>&nbsp;</TD><TD>$importance</TD>
</TR>
<TR>
<TD><B>Description:</B>&nbsp;</TD><TD>$descr</TD>
</TR>
</TABLE>
EOSTR;

		$this->webOutput($doc_info);
    }

    function chmodWWW($filename, $writable=true)
    {
    	$accessOld = $this->checkFileWritable($filename);
        @chmod($filename, (($writable) ? 0666 : 0644));
    	$accessNew = $this->checkFileWritable($filename);

    	if ($writable && $accessNew && ($accessNew != $accessOld)) {
    		$changedPermissions = $this->session->get("changedPermissions");
    		if (!(isset($changedPermissions) && is_array($changedPermissions))) {
    			$changedPermissions = array();
    		}
    		$changedPermissions[$filename] = true;
    		$this->session->set("changedPermissions", $changedPermissions);
    		$this->session->writeClose();
    	}

		return $accessNew;
    }

    function chmodFTP($filename, $writable=true)
    {
    	$accessOld = $this->checkFileWritable($filename);

		if (!function_exists("ftp_connect") || !$this->config->get("LiveUpdating.use_ftp")) {
			return $accessOld;
		}

		$conn_id = @ftp_connect($this->config->get("LiveUpdating.ftp_host"), $this->config->get("LiveUpdating.ftp_port")); 
		if ($conn_id !== false) {
			@ftp_pasv($conn_id, $this->config->get("LiveUpdating.ftp_passive"));
			if (@ftp_login($conn_id, $this->config->get("LiveUpdating.ftp_login"), $this->getFtpPassword())) {
				if (@ftp_chdir($conn_id, $this->config->get("LiveUpdating.ftp_dir"))) {
            		if (!function_exists("ftp_chmod")) {
            			$chmod_cmd="CHMOD ".(($writable) ? "0666" : "0644")." $filename"; 
            			@ftp_site($conn_id, $chmod_cmd);
            		} else {
        				@ftp_chmod($conn_id, (($writable) ? 0666 : 0644), $filename);
            		}
        		}
        	}
			@ftp_close($conn_id);
        }

    	$accessNew = $this->checkFileWritable($filename);

    	if ($writable && $accessNew && ($accessNew != $accessOld)) {
    		$changedPermissions = $this->session->get("changedPermissions");
    		if (!(isset($changedPermissions) && is_array($changedPermissions))) {
    			$changedPermissions = array();
    		}
    		$changedPermissions[$filename] = true;
    		$this->session->set("changedPermissions", $changedPermissions);
    		$this->session->writeClose();
    	}

		return $accessNew;
    }

    function restoreFile($update, $source)
    {
    	$currDir = getcwd();
    	@chdir("classes/modules/LiveUpdating");
    	@mkdir($this->_backup_files_subdir);
    	@chdir($this->_backup_files_subdir);
    	if (substr(getcwd(), strlen(getcwd())-6) == $this->_backup_files_subdir) {
    		@mkdir($update->get("name"));
    		@chdir($update->get("name"));
    		if (substr(getcwd(), strlen(getcwd())-strlen($update->get("name"))) != $update->get("name")) {
    			@chdir($currDir);
    			return false;
    		}

    		if (!isset($source)) {
    			@chdir($currDir);
    			return true;
    		}

    		$destDir = getcwd();
			@chdir($currDir);

            copy($destDir . "/" . basename($source), $source);
            @unlink($destDir . "/" . basename($source));
    	} else {
			@chdir($currDir);
    		return false;
        }
    }

	function getBackupFile($update, $source)
	{
		return "classes/modules/LiveUpdating/backup/" . $update->get("name") . "/" . basename($source);
	}

    function checkBackupFile($update, $source)
    {
    	$currDir = getcwd();
    	@chdir("classes/modules/LiveUpdating");
    	@mkdir($this->_backup_files_subdir);
    	@chdir($this->_backup_files_subdir);
		if (substr(getcwd(), strlen(getcwd())-6) == $this->_backup_files_subdir) {
    		@mkdir($update->get("name"));
    		@chdir($update->get("name"));
    		if (substr(getcwd(), strlen(getcwd())-strlen($update->get("name"))) != $update->get("name")) {
				@chdir($currDir);
    			return false;
    		}

    		if (!isset($source)) {
    			@chdir($currDir);
    			return true;
    		}

    		$destDir = getcwd();
			@chdir($currDir);

            return is_readable($destDir . "/" . basename($source));
		} else {
			@chdir($currDir);
    		return false;
        }
    }

    function backupFile($update, $source=null)
    {
    	$currDir = getcwd();
    	@chdir("classes/modules/LiveUpdating");
    	@mkdir($this->_backup_files_subdir);
    	@chdir($this->_backup_files_subdir);
    	if (substr(getcwd(), strlen(getcwd())-6) == $this->_backup_files_subdir) {
    		@mkdir($update->get("name"));
    		@chdir($update->get("name"));
    		if (substr(getcwd(), strlen(getcwd())-strlen($update->get("name"))) != $update->get("name")) {
    			@chdir($currDir);
    			return false;
    		}

    		if (!isset($source)) {
    			@chdir($currDir);
    			return true;
    		}

    		$destDir = getcwd();
			@chdir($currDir);

            copy($source, $destDir . "/" . basename($source));
    	} else {
			@chdir($currDir);
    		return false;
        }
    }

	function updateSqlData($content, $connection = null, $ignoreErrors = false)
	{
		$sqls = explode("\n", stripslashes($content));

		foreach ($sqls as $line=>$sql) {
			$c = chop($sql);
			// skip comments
			if ($c{0} == '#') continue;
			if (substr($c, 0, 2) == '--') continue;

			// parse SQL statement
			$command .= $c;
			if (substr($command, -1) == ';') {
				$command = substr($command, 0, strlen($command)-1);

				$table_name = "";
				if (preg_match("/^CREATE TABLE ([_a-zA-Z0-9]*)/i", $command, $matches)) {
					$table_name = $matches[1];
					echo "Creating table [$table_name] ... "; flush();
				} elseif (preg_match("/^ALTER TABLE ([_a-zA-Z0-9]*)/i", $command, $matches)) {
					$table_name = $matches[1];
					echo "Altering table [$table_name] ... "; flush();
				} elseif (preg_match("/^DROP TABLE IF EXISTS ([_a-zA-Z0-9]*)/i", $command, $matches)) {
					$table_name = $matches[1];
					echo "Deleting table [$table_name] ... "; flush();
				} else {
					$counter ++;
				}

				// execute SQL
				if (is_resource($connection)) {
					mysql_query($command, $connection);
				} else {
					mysql_query($command);
				}
				if (is_resource($connection)) {
					$myerr = mysql_error($connection);
				} else {
					$myerr = mysql_error();
				}

				// check for errors
				if (!empty($myerr)) {
					query_upload_error($myerr, $ignoreErrors);
					echo "<br>";
					if (!$ignoreErrors) {
						break;
					}
				} elseif ($table_name != "") {
					echo "<font color=green>[OK]</font><br>\n";
				} elseif (!($counter % 20)) {
					echo "."; flush();
				}

				$command = "";
				flush();
			}

		}

		return $ignoreErrors ? true : empty($myerr);
	}

	function updateFileData($source, $content, $data_type="text")
	{
		if ($data_type == "bin") {
			$handle = fopen($source, "wb");
			if (!$handle)
				return false;

			fputs($handle, base64_decode($content));

			fclose($handle);
		} else {
	        $handle = fopen($source, "w");
    	    if (!$handle) {
        	    return false;
	        }

			fwrite($handle, $content);

			fclose($handle);
		}
	}

    function updateFunctionData($source, $hunk, $data, $insert=false)
    {
    	$data = str_replace("\r\n", "\n", $data);
        $handle = fopen($source, "r");
        if (!$handle) {
        	return false;
        }

        $fcontent = "";
        while (!feof ($handle)) 
        {
            $fcontent .= fread($handle, 2048);
        }
        fclose($handle);

		if (preg_match("/\.+php$/", $source)) { // insert log string in PHP file only
			$comment = " /*LiveUpdating: ".$this->update_id." function ".date("Y/m/d H:i:s", time())."*/ ";
			$fcontent = preg_replace('/(\<\?php)/i', "$1".$comment, $fcontent, 1);
		}

		$fcontent = explode("\n", $fcontent);

		foreach($fcontent as $line_number => $line)
		{
			$fcontent[$line_number] = str_replace("\r", "", $line);
		}

		// Prevent empty string insertion in the EOF
		if (trim($fcontent[count($fcontent)-1]) == "") {
			unset($fcontent[count($fcontent)-1]);
		}

        $handle = fopen($source, "w");
        if (!$handle) {
        	return false;
        }

		$process = false;
		foreach($fcontent as $line_number => $line)
		{
			if ($line_number >= $hunk[0] && $line_number <= $hunk[1]) {
				if ($line_number == $hunk[1]) {
					$process = true;
				}

				if ($insert) {
					fputs($handle, $line . "\n");
				}
			} else {
				if ($process) {
    				if ($insert) {
    					fputs($handle, "\n");
    				}
					fputs($handle, $data . "\n");
                    $process = false;
				}
				fputs($handle, $line . "\n");
			}
		}

        fclose($handle);
    }

    function checkFunctionHunk($source, $search)
    {
    	$search = strtolower($search);
        $handle = fopen($source, "r");
        if (!$handle) {
        	return false;
        }

        $fcontent = "";
        while (!feof ($handle)) 
        {
            $fcontent .= fread($handle, 2048);
        }
        fclose($handle);

		$fcontent = explode("\n", $fcontent);

		$comment = false;
		$result = false;
		$hunk = array();
		$brackets = 0;
		foreach($fcontent as $line_number => $line)
		{
			$line_number += 1;
			$line = str_replace("\r", "", $line);

			$spos = strpos($line, '/*');
			if ($spos !== false && substr($line, $spos-2, 5) != '"*/*"' && substr($line, $spos-2, 5) != "'*/*'") {
				$comment = true;
			}

			if ($comment)
			{
				$spos1 = strpos($line, '/*');
				$spos2 = strpos($line, '*/');
				if ($spos1 !== false) {
					if ($spos2 !== false) {
						$code_line = substr($line, 0, $spos1) . substr($line, $spos2+2);
					} else {
						$code_line = substr($line, 0, $spos1);
					}
				} elseif ($spos2 !== false) {
					$code_line = substr($line, $spos2+2);
				}
			} else {
				$code_line = $line;
			}

			$spos = strpos($line, '*/');
			if ($spos !== false && substr($line, $spos-2, 5) != '"*/*"' && substr($line, $spos-2, 5) != "'*/*'") {
				$comment = false;
			}

			$spos = strpos($code_line, '//');
			if ($spos !== false) {
				$code_line = substr($code_line, 0, $spos);
			}

			if (!(!$comment || ($comment && !($spos1 === false && $spos2 === false)))) {
				continue;
			}

			$code_line = str_replace("\t", " ", $code_line);
			while(strlen($code_line) != strlen(str_replace("  ", " ", $code_line))) {
				$code_line = str_replace("  ", " ", $code_line);
			}
            $code_line = trim($code_line);
            $code_line = strtolower($code_line);

            if (!$result) {
                if (strpos($code_line, "function $search") !== false) {
                	$result = true;
                    $hunk[0] = $line_number;
    			}
    		} else {
    			for($i=0; $i<strlen($code_line); $i++) {
    				if (substr($code_line, $i, 1) == '{') {
    					$brackets ++;
    				}
    				if (substr($code_line, $i, 1) == '}') {
    					if ($brackets == 1) {
                    		$hunk[1] = $line_number;
    					}
    					$brackets --;

    					if ($brackets == 0 && isset($hunk[1])) {
							$hunk[2] = (strlen(substr($code_line, $i+1)) > 0) ? true : false;
							return $hunk;
    					}
    				}
    			}
    		}
		}

        return false;
    }

    function updateDiffData($source, $hunk, $search, $replace)
    {
    	$search = str_replace("\r\n", "\n", $search);
    	$replace = str_replace("\r\n", "\n", $replace);

		$src = $this->fileGetContents($source);
		$src = str_replace($search, $replace, $src);

		if (preg_match("/\.+php$/", $source)) { // insert log string in PHP file only
			$comment = " /*LiveUpdating: ".$this->update_id." diff ".date("Y/m/d H:i:s", time())."*/ ";
			$src = preg_replace('/(\<\?php)/i', "$1".$comment, $src, 1);
		}

    	$this->filePutContents($source, $src);
    }

    function checkDiffHunk($source, $search)
    {
    	$search = str_replace("\r\n", "\n", $search);
    	$src = $this->fileGetContents($source);

        return (strpos($src, $search) === false) ? false : true;
    }

    function checkFileWritable($source)
    {
    	if (!is_writable($source)) {
    		return false;
    	}

        $handle = @fopen($source, "a+");
        if (!$handle) {
        	return false;
        }
        fclose($handle);

		return true;
    }

    function webOutput($content=null,$flush_output=true)
    {
    	if (isset($content))
    	{
    		echo $content;
    		if ($flush_output)
    		{
    			func_flush();
    		}
    	}
    }

    function flushOutput() 
    {
        if (preg_match("/Apache(.*)Win/", getenv("SERVER_SOFTWARE")))
        {
            echo str_repeat(" ", 2500);
        }
    	elseif (preg_match("/(.*)MSIE(.*)\)$/", getenv("HTTP_USER_AGENT")))
    	{
            echo str_repeat(" ", 256);
        }
        ob_end_flush();
    	flush();
    }

    function prepareFormFields($exeptions=null)
    {
    	$fields = array();
    	foreach($this->getAllParams($exeptions) as $name => $val) {
    		$fields[] = "<input type=\"hidden\" name=\"$name\" value=\"$val\">";
    	}
        $fields = implode("\n", $fields);

        return $fields;
    }

    function filePutContents($file, $content) 
    {
        $fp = fopen($file, "wb") or die("write failed for $file");
        fwrite($fp, $content);
        fclose($fp);
    }

    function fileGetContents($f) 
    {
        ob_start();
        $retval = @readfile($f);
        if (false !== $retval) 
        { 
        	// no readfile error
            $retval = ob_get_contents();
        }
        ob_end_clean();
        return $retval;
    }

	function text_decryption($text)
	{
        require_once "modules/LiveUpdating/encoded.php";
		return LiveUpdating_text_decryption($text);
	}

	function getFtpPassword()
	{
		return $this->text_decryption($this->config->get("LiveUpdating.ftp_password"));
	}

	function displayPageHeader($title="", $scroll_down=true)
	{
		$scroll_code = "";
		if ($scroll_down) {
			$scroll_code = <<<EOSTR
function refresh()
{
	window.scroll(0, 100000);
	if (loaded == false)
		setTimeout('refresh()', 1000);
}

loaded = false;
setTimeout('refresh()', 1000);
</script>
EOSTR;
		}

		$content = <<<EOSTR
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META http-equiv="Pragma" content="no_cache">
<TITLE>$title</TITLE>
<STYLE type=text/css>
BODY, TD, TH, TABLE, UL, OL {font-size: 10pt; font-family: Verdana,Arial,Helvetica,Tahoma; }
A:link { COLOR: #000000; TEXT-DECORATION: none;}
A:visited { COLOR: #000000; TEXT-DECORATION: none;}
A:hover { COLOR: #0000FF; TEXT-DECORATION: underline;}
A:active  { COLOR: #000000; TEXT-DECORATION: none;}
</STYLE>
</HEAD>
<BODY bgcolor=#FFFFFF link=#0000FF alink=#4040FF vlink=#800080>

<script type="text/javascript" language="JavaScript 1.2">
function sectionShowHide(id)
{
	obj = document.getElementById(id);
	if ( obj )
		obj.style.display = ( obj.style.display ) ? '' : 'none';
}

function sectionHeader(id, text)
{
	obj = document.getElementById(id+'_result');
	if ( obj ) {
		obj.innerHTML = text;
	}
}
$scroll_code
</script>
EOSTR;

		$this->webOutput($content);
	}

	function displayPageFooter()
	{
		$content = <<<EOSTR
<script type="text/javascript" language="JavaScript 1.2">
loaded = true;
</script>
</BODY>
</HTML>
EOSTR;

		$this->webOutput($content);
	}

	function isApplyAllValid()
	{
		$sysupdate =& func_new("SystemUpdate");
        $updates = $sysupdate->count("type='core' AND status='N'");
		return ($updates > 0) ? true : false;
	}

	function isUndoAllValid()
	{
		$sysupdate =& func_new("SystemUpdate");
		$updates = $sysupdate->count("type='core' AND status='A'");
		return ($updates > 0) ? true : false;
	}

	function extractUpdateType($version)
	{
		$info = explode(":", $version);
		switch ($info[0]) {
			case "M":
				return $info[1]." ".$info[2];
			break;

			case "LC":
			default:
				return "Core";
			break;
		}
	}

	function checkModuleUpdate($name, $option=null)
	{
		$dialog =& func_new("Admin_Dialog_modules_LiveUpdating");
		$dialog->getModulesInfo();
		return $dialog->checkModuleUpdate($name, $option);
	}

	function action_clear_last_check()
	{
		if ($this->auth_code != $this->xlite->options->get("installer_details.auth_code")) {
			return;
		}

		$config =& func_new("Config");
		$config->createOption("LiveUpdating", "last_checked", "", "serialized");
	}

    function action_clear_updates()
    {   
        if ($this->auth_code != $this->xlite->options->get("installer_details.auth_code")) {
			return;
		}

		$this->action_clear_last_check();

		$object = func_new("SystemUpdate");
		$table = $this->db->getTableByAlias($object->alias);
		$this->db->query("DELETE FROM $table WHERE update_id > 0");

		$object = func_new("SystemUpdateItem");
		$table = $this->db->getTableByAlias($object->alias);
		$this->db->query("DELETE FROM $table WHERE update_item_id > 0");

		$object = func_new("SystemUpdateItemFile");
		$table = $this->db->getTableByAlias($object->alias);
		$this->db->query("DELETE FROM $table WHERE update_item_id > 0");
    }
}

?>
