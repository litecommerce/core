<?php

/*
* @version $Id$
*/

function FlyoutCategories_processTreeChunk(&$_this, &$chunk)
{
	static $count = 0;
	$count++;
	foreach($chunk as $chunk_item) {
		$_this->processTreeItem($chunk_item);

		if ( $count <= $_this->get("activeScheme.max_depth") ) {
			if (isset($chunk_item->subcategories)) {
				$_this->processTree($chunk_item);
			}
		}
	}

	$count--;
}

function FlyoutCategories_processTreeItem(&$_this, &$item)
{
	$_this->_categories_processed++;

	switch ($_this->categoriesMenuMode) {
		case 0:
			$_this->categoriesMenu[$_this->categoriesMenuMode][] = $item;
		break;
		case 1:
			$idx = strval($item->parent);
			if (!(isset($_this->categoriesMenu[$_this->categoriesMenuMode][$idx]) && is_array($_this->categoriesMenu[$_this->categoriesMenuMode][$idx]))) {
				$_this->categoriesMenu[$_this->categoriesMenuMode][$idx] = array();
			}
			$_this->categoriesMenu[$_this->categoriesMenuMode][$idx][] = $item;
		break;
	}
}

function FlyoutCategories_buildTree(&$_this, &$parent)
{
	if (!isset($_this->categoryClass)) {
		$_this->categoryClass = new XLite_Model_Category();
	}
	$table = $_this->categoryClass->getTable();
	$category_id = (!isset($parent)) ? 0 : $parent->category_id;
	$order_by = $_this->categoryClass->defaultOrder;
	$sql = "SELECT parent, category_id FROM $table WHERE parent=$category_id ORDER BY $order_by, parent, category_id";

	$subcategories = array();
	if ($parent->depth <= $_this->get("activeScheme.max_depth")) {
		$adminZone = $_this->xlite->is("adminZone");
		$_this->xlite->set("adminZone", false);

		foreach ($_this->db->getAll($sql) as $record) {
			$co = new XLite_Model_Category($record["category_id"]);
			if ($co->filter()) {
				$subcategories[] = $record;
			}
		}

		$_this->xlite->set("adminZone", $adminZone);
	}

	if (!isset($parent)) {
		$parent = new StdClass();
		$parent->depth = 0;
	}

	$parent->subcategories = array();
	$parent->subcategories_chunks = 1;
	$parent->number = count($subcategories);

	for($i=0; $i < count($subcategories); $i++) {
		$category = new XLite_Model_Category($subcategories[$i]["category_id"]);

		$sc = new StdClass();
		$sc->parent = $category_id;
		$sc->category_id = $subcategories[$i]["category_id"];
		$sc->is_first = ($i == 0) ? true : false;
		$sc->is_last = ($i == (count($subcategories)-1)) ? true : false;
		$sc->previous = ($sc->is_first) ? -1 : $subcategories[$i-1]["category_id"];
		$sc->next = ($sc->is_last) ? -1 : $subcategories[$i+1]["category_id"];
		$sc->depth = $parent->depth + 1;

		$subcategories_number = $_this->categoryClass->count("parent='".$sc->category_id."'");
		if ($subcategories_number > 0) {
			$sc = $_this->buildTree($sc);
		}

		if (!is_array($parent->subcategories[$parent->subcategories_chunks-1])) {
			$parent->subcategories[$parent->subcategories_chunks-1] = array();
		}

		$parent->subcategories[$parent->subcategories_chunks-1][] = $sc;

		if ($subcategories_number > 0) {
			$parent->subcategories_chunks ++;
		}
	}

	if ($parent->subcategories_chunks > count($parent->subcategories)) {
		$parent->subcategories_chunks = count($parent->subcategories);
	}

	return $parent;
}

function FlyoutCategories_isReadOnly($scheme_id)
{
	switch($scheme_id) {
		case "1":
		case "2":
		case "3":
		case "4":
		return true;
		default:
		return false;
	}
}

function FlyoutCategories_isInvariable($scheme_id)
{
	switch($scheme_id) {
		case "1":
		case "2":
		case "3":
		case "4":
		return true;
		default:
		return false;
	}
}

function FlyoutCategories_action_update(&$_this)
{
	if (!(isset($_this->schemes_list) && is_array($_this->schemes_list))) {
    	$_this->params[] = "status";
        $_this->set("status" , "update_failed");

		return;
	}

	$fNode = new XLite_Model_FileNode();
	$saved_scheme_id = $_this->scheme_id;

	$names_updated = array();
	foreach($_this->schemes_list as $scheme_id => $scheme_data) {
		$_this->scheme_id = intval($scheme_id);
		$_this->scheme = null;
		$scheme = $_this->getCurrentScheme();
		if (!is_object($scheme)) {
			$_this->scheme_id = $saved_scheme_id;
        	$_this->params[] = "status";
            $_this->set("status" , "update_failed");

			return;
		}

		if (!$_this->isReadOnly($_this->scheme_id)) {
    		$oldSchemeName = $_this->customerLayoutPath . "modules/FlyoutCategories/schemes/" . $scheme->getFileName();
    		$scheme->set("order_by", $scheme_data["order_by"]);
    		if (strcmp($scheme_data["name"] , $scheme->get("name")) != 0) {
    			$oldName = $scheme->getFileName();
            	$fNode->path = $_this->customerLayoutPath . "modules/FlyoutCategories/schemes";
            	$fNode->createDir();
            	$fNode->path = $fNode->path . "/" . $scheme->getFileName();
            	$fNode->createDir();
            	$fNode->path = $_this->customerLayoutPath . "modules/FlyoutCategories/schemes/" . $scheme->getFileName();
    			$scheme->set("name", $scheme_data["name"]);
    			$newName = $scheme->getFileName();
    			$names_updated[$oldName] = $newName;
            	$fNode->newPath = $_this->customerLayoutPath . "modules/FlyoutCategories/schemes/" . $scheme->getFileName();
            	$fNode->rename();
				$scheme->set("templates", str_replace($oldName, $newName, $scheme->get("templates")));
    		}
    	}
		if (!$_this->isInvariable($_this->scheme_id)) {
			$scheme->update();
		}
	}

    $_this->params[] = "status";
    $_this->set("status" , "updated");
	$_this->scheme_id = $saved_scheme_id;
}

function FlyoutCategories_action_delete(&$_this)
{
	if (!isset($_this->modified_scheme_id) || strlen($_this->modified_scheme_id) == 0) {
    	$_this->params[] = "status";
        $_this->set("status" , "delete_failed");

		return;
	}

	$_this->scheme_id = intval($_this->modified_scheme_id);
	$scheme = $_this->getCurrentScheme();
	if (!is_object($scheme)) {
    	$_this->params[] = "status";
        $_this->set("status" , "delete_failed");

		return;
	}

	if ($_this->isReadOnly($_this->scheme_id)) {
    	$_this->params[] = "status";
        $_this->set("status" , "delete_failed");

		return;
	}

	// Check for active scheme
	$id = $_this->get("config.FlyoutCategories.scheme");
	if ( $scheme->get("scheme_id") == $id ) {
		$cfg = new XLite_Model_Config();
		$cfg->createOption("FlyoutCategories", "scheme", 0);
		$_this->params[] = "warning";
		$_this->set("warning", "drop_scheme");
	}

	// Delete scheme files
	$fNode = new XLite_Model_FileNode();
	$fNode->path = $_this->customerLayoutPath . "modules/FlyoutCategories/schemes/" . $scheme->getFileName();
	$fNode->remove();
	$scheme->delete();

	$_this->params[] = "status";
    $_this->set("status" , "deleted");
}

function FlyoutCategories_action_fc_update_templates(&$_this)
{
	if (!$_this->isSchemeAvailable() || $_this->scheme_id == 0) {
		$_this->params[] = "status";
		$_this->set("status" , "update_failed");

		return;
	}

	$scheme = $_this->getCurrentScheme();
	if (!is_object($scheme)) {
		$_this->params[] = "status";
		$_this->set("status" , "update_failed");

		return;
	}

	if (isset($_this->templates)) {
		$scheme->set("templates", $_this->templates);
		$scheme->update();
	}

	$_this->params[] = "status";
	$_this->set("status" , "updated");
}

function FlyoutCategories_action_fc_clone(&$_this)
{
	if (!isset($_this->modified_scheme_id) || strlen($_this->modified_scheme_id) == 0) {
		$_this->params[] = "status";
		$_this->set("status" , "clone_failed");

		return;
	}

	$saved_scheme_id = $_this->scheme_id;
	$_this->scheme_id = intval($_this->modified_scheme_id);
	$scheme = $_this->getCurrentScheme();
	if (!is_object($scheme)) {
		$_this->scheme_id = $saved_scheme_id;
		$_this->params[] = "status";
		$_this->set("status" , "clone_failed");

		return;
	}


	$clone_fields = array("max_depth", "options", "explorer");
	$new_scheme = new XLite_Module_FlyoutCategories_Model_FCategoriesScheme();
	$new_scheme->set("name", $scheme->get("name") . " (clone)");
	foreach ($clone_fields as $v){
		$data = $scheme->get($v);
		$new_scheme->set($v, $data);
	}
	$new_scheme->create();

	$fNode = new XLite_Model_FileNode();
	$fNode->path = $_this->customerLayoutPath . "modules/FlyoutCategories/schemes";
	$fNode->createDir();
	$fNode->path = $fNode->path . "/" . $new_scheme->getFileName();
	$fNode->createDir();

	$new_scheme_dir = $fNode->path;
	$new_scheme->set("templates", $new_scheme_dir);
	$new_scheme->update();

	$_this->copy_scheme_nodes($fNode, $scheme, $new_scheme);
	$new_scheme->set("order_by", $scheme->get("order_by"));
	$new_scheme->update();

	$_this->params[] = "status";
	$_this->set("status" , "cloned");

	$_this->set("scheme_id", $new_scheme->get("scheme_id"));
}

function FlyoutCategories_action_rebuild_tree(&$_this)
{
	$dialog = new XLite_Controller_Admin_Categories();
	$dialog->set("silent", true);
	$dialog->action_build_categories();
	$_this->params[] = "status";
	$_this->set("status", "rebuilt");
}

function FlyoutCategories_action_delete_option(&$_this)
{
	$keyname = $_REQUEST["keyname"];
	if ( empty($keyname) )
		return;

	$scheme = $_this->get("currentScheme");
	$options = $scheme->get("options");
	unset($options["$keyname"]);

	$scheme->set("options", $options);
	$scheme->update();
}

function FlyoutCategories_action_add_option(&$_this)
{
	$scheme = $_this->get("currentScheme");
	$options = $scheme->get("options");

	$keyname = trim(preg_replace("/[^A-Za-z0-9_]+/", "_", $_this->option_keyname));
	$type = $_this->option_type;
	$description = $_this->option_description;
	$points = $_this->option_points;

	$keyname = preg_replace("/[^A-Za-z0-9_]+/", "_", $keyname);

	// Return error: This option keyname already exists
	foreach ($options as $k=>$v) {
		if ( $k == $keyname ) {
			$_this->params[] = "status";
			$_this->set("valid", false);
			$_this->set("status", "exists");
			return;
		}
	}

	// Return error: Required fields empty
	if ( empty($keyname) || $type == "select_box" && empty($points) ) {
		$_this->params[] = "status";
		$_this->set("valid", false);
		$_this->set("status", "empty_fields");
		return;
	}

	if ( $type == "select_box" ) {
		$points = explode("\n", $points);
		if ( !is_array($points) ) {
			$points = array();
		} else {
			$temp = array();
			foreach ($points as $v)
				$temp[] = trim($v);

			$points = $temp;
		}
	}

	$option = array();
	$option["type"] = $type;
	$option["description"] = $description;
	if ( $type == "select_box" )
		$option["points"] = $points;

	$option["value"] = "";

	$options["$keyname"] = $option;
	$scheme->set("options", $options);
	$scheme->update();

	$_this->params[] = "status";
	$_this->set("status", "added");
}

function FlyoutCategories_action_update_option(&$_this)
{
    $keyname = preg_replace("/[^A-Za-z0-9_]+/", "_", $_this->option_keyname);

    // get options from current scheme
    $scheme = $_this->get("currentScheme");
    $options = $scheme->get("options");
    unset($options[$_this->option_name]);

		$_this->params[] = "status";
        // check for scheme exists
        if (array_key_exists($keyname, $options)) {
            $_this->set("valid", false);
            $_this->set("status", "option_exists");
            return;
        }

        // Return error: Required fields empty
        if ( empty($_this->option_keyname) || $_this->option_type == "select_box" && empty($_this->option_points) ) {
            $_this->set("valid", false);
            $_this->set("status", "empty_fields");
            return false;
        }

        if ( $_this->option_type == "select_box" ) {
            $points = explode("\n", $_this->option_points);
            if ( !is_array($points) ) {
                $points = array();
            } else {
                $temp = array();
                foreach ($points as $v)
                    $temp[] = trim($v);

                $points = $temp;
            }
        }

        $option = array();
        $option["type"] = $_this->option_type;
        $option["description"] = $_this->option_description;
        if ( $_this->option_type == "select_box" )
            $option["points"] = $points;
        $option["value"] = "";

        // create option
        $options[$keyname] = $option;
        $scheme->set("options", $options);
        $scheme->update();

	$_this->scheme = null;

	$_this->set("status", "option_updated");
}

function FlyoutCategories_getSchemeManagerDialog(&$_this)
{
	$dialog = null;

	if ($_this->xlite->LayoutOrganizerEnabled) {
		$dialog = new XLite_Module_LayoutOrganizer_Controller_Admin_SchemeManager();
	} else {
		$dialog = new XLite_Module_FlyoutCategories_Controller_Admin_SchemeManagerFc();
	}

	return $dialog;
}

function FlyoutCategories_getDefaultScheme(&$_this)
{
	$_this->initLayout();

	$scheme = new XLite_Module_FlyoutCategories_Model_FCategoriesScheme();
	$scheme->set("scheme_id", 0);
	$scheme->set("name", $_this->getDefaultSchemeName());
	$scheme->set("order_by", 0);
	$scheme->set("templates", $_this->customerLayoutPath."modules/FlyoutCategories/schemes/Default_Flat");

	return $scheme;
}

function FlyoutCategories_getSchemes(&$_this, $all_schemes=true)
{
	$_this->schemes = null;

	$scheme = new XLite_Module_FlyoutCategories_Model_FCategoriesScheme();
	$condition = array();
	$condition[] = "scheme_id > '0'";
	$condition = implode(" AND ", $condition);
	$_this->schemes = $scheme->findAll($condition);

	if ( is_array($_this->schemes) && count($_this->schemes) == 0 )
	{
		$scheme = $_this->getDefaultScheme();
		$_this->schemes = array_merge(array($scheme), $_this->schemes);
	}
}

function FlyoutCategories_isSchemeAvailable(&$_this)
{
	if (!isset($_this->scheme_id)) {
		return false;
	}
	if (strlen($_this->scheme_id) == 0) {
		return false;
	}
	$_this->scheme_id = intval($_this->scheme_id);
	return ($_this->scheme_id >= 0) ? true : false;
}

function FlyoutCategories_getCurrentScheme(&$_this)
{
	if (!$_this->isSchemeAvailable()) {
		return null;
	}

	if (!is_null($_this->scheme)) {
		return $_this->scheme;
	}

	$_this->getSchemes();
	foreach($_this->schemes as $scheme) {
		if ($_this->scheme_id == $scheme->get("scheme_id")) {
			$_this->scheme = $scheme;
			break;
		}
	}

	return $_this->scheme;
}


function FlyoutCategories_checkUpdateCategories(&$_this)
{
	if ( $_this->get("config.FlyoutCategories.scheme") > 0 ) {
		$config = new XLite_Model_Config();
		$config->createOption("FlyoutCategories", "category_changed", 1);
	}

	// rebuild layout
	if ($_this->get("config.FlyoutCategories.category_autoupdate")) {
		$dialog = new XLite_Controller_Admin_Categories();
		$dialog->set("silent", true);
		$dialog->action_build_categories();
	}
}

function FlyoutCategories_copy_scheme_nodes(&$_this, &$fNode, &$scheme, &$new_scheme)
{
	$files = $_this->getSchemeNodesList();
	foreach($files as $file) {
		$fNode->path = $scheme->get("templates") . "/" . $file;
		$fNode->newPath = $new_scheme->get("templates") . "/" . $file;
		$fNode->copy();
	}
}

function FlyoutCategories_getSchemeNodesList()
{
	return array("categories.tpl", "cat_template.tpl", "scat_template.tpl", "body_template.tpl", "style.css", "script.js", "image.tpl", "images", "styles");
}

function FlyoutCategories_gdLibEnabled()
{
	// Check if GD lib loaded
	$gd_found = false;
	foreach (get_loaded_extensions() as $name) {
		if ($name == "gd")
			$gd_found = true;
	}

	if (!$gd_found)
		return false;

	// Check GD lib version
	$cont = gd_info();
	preg_match("/[\d\.]+/", $cont["GD Version"], $out);
	$version = array_pop($out);

	return version_compare("2", $version, "<=");
}

?>
