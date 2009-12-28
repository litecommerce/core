<?php

/*
* * Hidden methods
* *
* * @version $Id$
* */  

function LayoutOrganizer_updateChildrenTemplates(&$_this, $only_categories = false)
{
	if (!$only_categories) {
    	$products = $_this->getProducts("custom_template < '0'");
    	$p_custom_template = $_this->getTemplate("p_custom_template");
    	foreach($products as $product) {
    		if ($product->get("parent.category_id") == $_this->get("category_id")) {
            	$product->set("template_name", $p_custom_template);
                $product->update();
    		}
    	}
    }

	$subcategories = $_this->getSubcategories("(custom_template < '0' OR sc_custom_template < '0')");
	if (is_array($subcategories)) {
		$custom_template = $_this->getTemplate("custom_template");
		$sc_custom_template = $_this->getTemplate("sc_custom_template");
		$p_custom_template = $_this->getTemplate("p_custom_template");
		foreach($subcategories as $sc) {
			if ($sc->get("custom_template") < 0) {
				$sc->updateTemplate("custom_template", $custom_template);
			}
			if ($sc->get("sc_custom_template") < 0) {
				$sc->updateTemplate("sc_custom_template", $sc_custom_template);
			}
			if ($sc->get("p_custom_template") < 0) {
				$sc->updateTemplate("p_custom_template", $p_custom_template);
			}
			$sc->update();
			$sc->updateChildrenTemplates();
		}
	}
}

function LayoutOrganizer_enableChildren(&$_this, $only_categories = false)
{
	if (!$only_categories) {
        $kernelVersion = $_this->xlite->config->get("Version.version");
        if (version_compare($kernelVersion, "2.2", ">=")) {
            $productIDs = $_this->getProductIDs("custom_template < '0'");
            $enabled = $_this->get("p_custom_template_enabled");
            foreach($productIDs as $productID) {
                // KOI8-R comment: потому что $productID - это не чисто int значение,
                // это "немножко" массив
                $product = new XLite_Model_Product((int) $productID["data"]["product_id"]);
                if ($product->get("parent.category_id") == $_this->get("category_id")) {
                    $product->set("custom_template_enabled", $enabled);
                    $product->update();
                }
            }
        } else {
            $products = $_this->getProducts("custom_template < '0'");
            $enabled = $_this->get("p_custom_template_enabled");
            foreach($products as $product) {
                if ($product->get("parent.category_id") == $_this->get("category_id")) {
                    $product->set("custom_template_enabled", $enabled);
                    $product->update();
                }
            }
        }
    }

	$subcategories = $_this->getSubcategories("(custom_template < '0' OR sc_custom_template < '0')");
	if (is_array($subcategories)) {
    	$enabled = $_this->get("custom_template_enabled");
    	$sc_enabled = $_this->get("sc_custom_template_enabled");
    	$p_enabled = $_this->get("p_custom_template_enabled");
		foreach($subcategories as $sc) {
			if ($sc->get("custom_template") < 0) {
				$sc->set("custom_template_enabled", $enabled);
			}
			if ($sc->get("sc_custom_template") < 0) {
				$sc->set("sc_custom_template_enabled", $sc_enabled);
			}
			if ($sc->get("p_custom_template") < 0) {
				$sc->set("p_custom_template_enabled", $p_enabled);
			}
			$sc->update();
			$sc->enableChildren();
		}
	}
}

function LayoutOrganizer_isReadOnly($scheme_id)
{
	switch($scheme_id) {
		case "0":
		case "1":
		case "2":
		case "3":
		return true;
		default:
		return false;
	}
}

function LayoutOrganizer_isInvariable($scheme_id)
{
	switch($scheme_id) {
		case "0":
		return true;
		default:
		return false;
	}
}

function LayoutOrganizer_action_update(&$_this)
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

		$enabled = (isset($scheme_data["enabled"])) ? (($scheme_data["enabled"] == 1) ? 1 : 0) : 0;
		$scheme->set("enabled", $enabled);
		if (!$_this->isInvariable($_this->scheme_id)) {
    		$enabled = ($enabled) ? "1" : "0";
    		$categories_list = new XLite_Model_Category();
    		$categories_list = $categories_list->findAll("(custom_template='$scheme_id' OR sc_custom_template='$scheme_id' OR p_custom_template='$scheme_id')");
    		if (is_array($categories_list)) {
    			foreach($categories_list as $cat) {
    				if ($cat->get("custom_template") == $scheme_id) {
    					$cat->set("custom_template_enabled", $enabled);
    				}
    				if ($cat->get("sc_custom_template") == $scheme_id) {
    					$cat->set("sc_custom_template_enabled", $enabled);
    				}
    				if ($cat->get("p_custom_template") == $scheme_id) {
    					$cat->set("p_custom_template_enabled", $enabled);
    				}
                	$cat->update();
					$cat->enableChildren();
    			}
    		}
    		$products_list = new XLite_Model_Product();
    		$products_list = $products_list->findAll("custom_template='$scheme_id' AND custom_template_enabled<>'$enabled'");
    		if (is_array($products_list)) {
    			foreach($products_list as $prod) {
                	$prod->set("custom_template_enabled", $enabled);
                	$prod->update();
    			}
    		}
    	}

		if (!$_this->isReadOnly($_this->scheme_id)) {
    		$oldSchemeName = $_this->customerLayoutPath . "modules/LayoutOrganizer/schemes/" . $scheme->getFileName();
    		$scheme->set("order_by", $scheme_data["order_by"]);
    		if (strcmp($scheme_data["name"] , $scheme->get("name")) != 0) {
    			$oldName = $scheme->getFileName();
            	$fNode->path = $_this->customerLayoutPath . "modules/LayoutOrganizer/schemes";
            	$fNode->createDir();
            	$fNode->path = $fNode->path . "/" . $scheme->getFileName();
            	$fNode->createDir();
            	$fNode->path = $_this->customerLayoutPath . "modules/LayoutOrganizer/schemes/" . $scheme->getFileName();
    			$scheme->set("name", $scheme_data["name"]);
    			$newName = $scheme->getFileName();
    			$names_updated[$oldName] = $newName;
            	$fNode->newPath = $_this->customerLayoutPath . "modules/LayoutOrganizer/schemes/" . $scheme->getFileName();
            	$fNode->rename();
            	$scheme->set("cat_template", str_replace($oldName, $newName, $scheme->get("cat_template")));
            	$scheme->set("scat_template", str_replace($oldName, $newName, $scheme->get("scat_template")));
            	$scheme->set("prod_template", str_replace($oldName, $newName, $scheme->get("prod_template")));

    		}
    	}
		if (!$_this->isInvariable($_this->scheme_id)) {
			$scheme->update();
		}
	}
	if (count($names_updated) > 0) {
		foreach($names_updated as $oldName => $newName) {
    		$schemes_list = new XLite_Module_LayoutOrganizer_Model_TemplatesScheme();
    		$oldNameSql = str_replace("_", "\\_", $oldName);
    		$schemes_list = $schemes_list->findAll("(cat_template LIKE '%/$oldNameSql/%') OR (scat_template LIKE '%/$oldNameSql/%') OR (prod_template LIKE '%/$oldNameSql/%')");
    		if (is_array($schemes_list)) {
    			foreach($schemes_list as $sch) {
                	$sch->set("cat_template", str_replace("/$oldName/", "/$newName/", $sch->get("cat_template")));
                	$sch->set("scat_template", str_replace("/$oldName/", "/$newName/", $sch->get("scat_template")));
                	$sch->set("prod_template", str_replace("/$oldName/", "/$newName/", $sch->get("prod_template")));
                	$sch->update();
    			}
    		}
    		$categories_list = new XLite_Model_Category();
    		$categories_list = $categories_list->findAll("(template_name LIKE '%/$oldNameSql/%') OR (sc_template_name LIKE '%/$oldNameSql/%') OR (p_template_name LIKE '%/$oldNameSql/%')");
    		if (is_array($categories_list)) {
    			foreach($categories_list as $cat) {
                	$cat->set("template_name", str_replace("/$oldName/", "/$newName/", $cat->get("template_name")));
                	$cat->set("sc_template_name", str_replace("/$oldName/", "/$newName/", $cat->get("sc_template_name")));
                	$cat->set("p_template_name", str_replace("/$oldName/", "/$newName/", $cat->get("p_template_name")));
                	$cat->update();
    			}
    		}
    		$products_list = func_new("Product");
    		$products_list = $products_list->findAll("template_name LIKE '%/$oldNameSql/%'");
    		if (is_array($products_list)) {
    			foreach($products_list as $prod) {
                	$prod->set("template_name", str_replace("/$oldName/", "/$newName/", $prod->get("template_name")));
                	$prod->update();
    			}
    		}
    	}
	}

    $_this->params[] = "status";
    $_this->set("status" , "updated");
	$_this->scheme_id = $saved_scheme_id;
}

function LayoutOrganizer_action_delete(&$_this)
{
	if (!isset($_this->modified_scheme_id)) {
    	$_this->params[] = "status";
        $_this->set("status" , "delete_failed");

		return;
	}
	if (strlen($_this->modified_scheme_id) == 0) {
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

	$oldName = $scheme->getFileName();
	$oldNameSql = str_replace("_", "\\_", $oldName);
	$cat_dialog = new XLite_Module_LayoutOrganizer_Controller_Admin_Category();
	$categories_list = func_new("Category");
	$categories_list = $categories_list->findAll("(template_name LIKE '%/$oldNameSql/%') OR (sc_template_name LIKE '%/$oldNameSql/%') OR (p_template_name LIKE '%/$oldNameSql/%')");
	if (is_array($categories_list)) {
		foreach($categories_list as $cat) {
			if ($cat->get("custom_template") > 0 && strpos($cat->get("template_name"), "/".$oldName."/") !== false) {
				$cat_dialog->updateCategoryTemplate($cat, -1, "custom_template");
			}
			if ($cat->get("sc_custom_template") > 0 && strpos($cat->get("sc_template_name"), "/".$oldName."/") !== false) {
				$cat_dialog->updateCategoryTemplate($cat, -1, "sc_custom_template");
			}
			if ($cat->get("p_custom_template") > 0 && strpos($cat->get("p_template_name"), "/".$oldName."/") !== false) {
				$cat_dialog->updateCategoryTemplate($cat, -1, "p_custom_template");
			}
        	$cat->update();
		}
	}

	$products_list = func_new("Product");
	$products_list = $products_list->findAll("template_name LIKE '%/$oldNameSql/%'");
	if (is_array($products_list)) {
		foreach($products_list as $prod) {
			$parent = $prod->get("parent");
			$prod->set("custom_template", -1);
			$prod->set("template_name", $parent->getTemplate("p_custom_template"));
        	$prod->update();
		}
	}

	$fNode = new XLite_Model_FileNode();
	$fNode->path = $_this->customerLayoutPath . "modules/LayoutOrganizer/schemes/" . $scheme->getFileName();
	$fNode->remove();
	$scheme->delete();

	$_this->params[] = "status";
    $_this->set("status" , "deleted");
}

?>
