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
* @package Module_AutoUpdateCatalog
* @access public
* @version $Id$
*
*/
class Admin_Dialog_autoupdate_catalog extends Admin_Dialog
{
    var $params = array("target", "returnUrl");

    function getCatalog() // {{{
    {
        if (is_null($this->catalog)) {
            $this->catalog = func_new("Catalog");
        }
        return $this->catalog;
    } // }}}
    
    function getProductUrl($product_id, $category_id) // {{{
    {
        $catalog = $this->get("catalog");
        return $catalog->getProductUrl($product_id, $category_id);
    } // }}}

    function getCategoryUrl($category_id) // {{{
    {
        $catalog = $this->get("catalog");
        return $catalog->getCategoryUrl($category_id);
    } // }}}
    
    function action_update() // {{{
    {
        // initalize catalog instance
        $catalog = $this->get("catalog");
        if (!$catalog->is("built")) {
            $url = $this->get("returnUrl");
?>
<font color=red>There is no static HTML catalog found.</font><br>
<br>
<?php
echo '<a href="admin.php?target=catalog&action=build&xlite_form_id='.$this->get("xliteFormID").'"><u>Build HTML catalog</u></a><br>';
?>
<br>
<a href="<?php echo $url; ?>"><u>Return to admin zone</u></a>
<?php
            exit();
        }
        $catalog->set("recursive", false);

        require_once "modules/AutoUpdateCatalog/encoded.php"; // functions

        func_refresh_start();
        echo "Updating HTML catalog, please wait ..<br><br>\n";
        func_flush();

        // request & map data from the previous POST
        $this->post = unserialize($this->session->get("post"));
        $this->session->set("post", null); // reset 
        if (!is_null($this->post)) {
            $this->mapRequest($this->post);
        }
        /*
        echo "mapped properties<br>";
        foreach (get_object_vars($this) as $name => $value) {
            if (!is_object($this->$name)) {
                echo "$name = $value<br>";
            }
        }
        die();
        */
        $action = "action_" . $this->post["target"];
        if (method_exists($this, $action)) {
        	if ($this->session->isRegistered("processedSteps")) {
        		$this->xlite->processedSteps = unserialize($this->session->get("processedSteps"));
        	} else {
            	$this->xlite->processedSteps = array
            	(
            		"counter"			=> 1,
            		"processedUrl" 		=> array(),
            	);
            }

            $this->$action();

        	$this->session->set("processedSteps", null);
        	$this->session->writeClose();
        }

        echo "<br><hr><b>Finished, redirecting ...</b><br>\n";
        func_flush();
        func_refresh_end();
    } // }}}

    function redirect() // {{{
    {
        $url = $this->get("returnUrl");
?>
If you are not redirected automatically, <a href="<?php echo $url; ?>">click on this link to return to admin zone</a><br>
<script language="JavaScript">
document.location="<?php echo $url; ?>";
</script>
<?php        
    } // }}}

    function rebuildFlyoutCategories()
    {
    	if ($this->xlite->get("FlyoutCategoriesEnabled") && $this->get("config.FlyoutCategories.force_js_in_layout")) {
    		$dialog = func_new("Admin_Dialog_module_FlyoutCategories");
    		$dialog->action_rebuild_tree();

        	if ($this->get("config.FlyoutCategories.force_js_in_layout")) {
    			$catalog = $this->get("catalog");
                $catalog->buildFCJS();
        	}
    	}
    }

    function action_categories() // {{{
    {
        $topID = $this->get("xlite.factory.Category.topCategory.category_id");
        if ($this->post["action"] == "delete") {
            if ($this->category_id == $topID) {
            	if ($this->config->get("HTMLCatalog.drop_catalog")) {
                    // delete all categories & product pages
                    $catalog = $this->get("catalog");
                    $catalog->clear();
                }
                // rebuild the whole catalog
                $this->set("returnUrl", "admin.php?target=catalog&action=build&xlite_form_id=".$this->get('xliteFormID'));
                return;
            } else {    
                $category = func_new("Category", $category_id);
                $sub = $this->updateLog["categories"];
            	if ($this->config->get("HTMLCatalog.drop_catalog")) {
                    // delete subcategories
                    for ($i = 0; $i < count($sub); $i++) {
                        func_categories_delete($this, $sub[$i]);
                    }
                }
                // rebuild parent's category page
                if ($category->is("enabled")) {
                    func_categories_update($this, $this->category_id);
                }
            }    
        } else if ($this->post["action"] == "update" || $this->post["action"] == "add_featured_products" || $this->post["action"] == "update_featured_products") {
    		$this->rebuildFlyoutCategories();

            func_categories_update($this, $this->category_id);
        }
    } // }}}

    function action_category() // {{{
    {
    	$this->rebuildFlyoutCategories();

        if ($this->post["action"] == "modify" || $this->post["action"] == "update_fields") { // update
            // category_id is a modified category ID
            $category = func_new("Category", $this->category_id);
            $parent = $category->get("parent");
            $topID = $this->get("xlite.factory.Category.topCategory.category_id");
            // top category updated - rebuild the whole catalog
            if ($topID == $parent) {
            	if ($this->config->get("HTMLCatalog.drop_catalog")) {
                    // delete all categories & product pages
                    $catalog = $this->get("catalog");
                    $catalog->clear();
                }
                // rebuild the whole catalog
                $this->set("returnUrl", "admin.php?target=catalog&action=build&xlite_form_id=".$this->get('xliteFormID'));
                return;
            }
            if ($category->is("enabled")) {
            	// update category recursively
            	func_category_update($this, $this->category_id, null, true);
                // update parent page (category orderby could has been changed)
                func_categories_update($this, $parent, null, true);
            } else { // status set to "disabled"
            	if ($this->config->get("HTMLCatalog.drop_catalog")) {
                	func_categories_delete($this, $this->category_id);
                }
                // update parent
                func_categories_update($this, $parent);
            }    
        } else if ($this->post["action"] == "add") {
            // category_id is a parent category ID
            $new_category_id = $this->updateLog["category_id"];
            $category = func_new("Category", $new_category_id);
            if ($category->is("enabled")) {
                func_category_add($this, $new_category_id);
                func_category_add($this, $this->category_id);
            }
        } else if ($this->post["action"] == "delete") {
            // category_id is a deleting category ID
            if (isset($this->updateLog["parent"][$this->category_id])) {
                $parent = $this->updateLog["parent"][$this->category_id];
                // delete category and related pages (including parent page)
                func_categories_delete($this, $this->category_id);
                // restore parent page
                func_category_add($this, $parent);
            } 
        }
    } // }}}

    function action_add_product() // {{{
    {
        $product_id = $this->updateLog["product_id"];
        $product = func_new("Product", $product_id);
        // do not add disabled product
        if (!$product->is("enabled")) {
            return;
        }
        if (isset($this->category_id)) {
            $category = func_new("Category", $this->category_id);
            if ($category->is("enabled")) {
				func_product_update($this, $product_id, $this->category_id, true);
                func_category_update($this, $this->category_id, $product_id, true);
            }
        } elseif (isset($this->product_categories)) {
            foreach ((array)$this->product_categories as $category_id) {
                $category = func_new ("Category", $category_id);
                if ($category->is("enabled")) {
					func_product_update($this, $product_id, $category_id, true);
                    func_category_update($this, $category_id, $product_id, true);
                }    
            }
        }
    } // }}}

    function action_product() // {{{
    {
        $deleteCategory = $this->updateLog["deleteCategory"];
        if (!is_array($deleteCategory)) {
            $deleteCategory = array();
        }
        $addCategory = $this->updateLog["addCategory"];
        if (!is_array($addCategory)) {
            $addCategory = array();
        }
        $deleteOnly = array_diff($deleteCategory, $addCategory);
        $addOnly = array_diff($addCategory, $deleteCategory);
        // delete product pages
        foreach ($deleteOnly as $category_id) {
            func_product_delete($this, $this->product_id, $category_id, true);
            func_category_update($this, $category_id, $this->product_id, true, true);
        }
        foreach ($addOnly as $category_id) {
            $category = func_new ("Category", $category_id);
            if ($category->is("enabled")) {
                func_category_update($this, $category_id, $this->product_id, true, true);
            }
        }

        // only product information updated
        // remove product pages
        foreach ($deleteCategory as $category_id) {
			if (array_search($category_id, $addCategory) === false) {
				if (is_array($deleteOnly) && !(array_search($category_id, $deleteOnly) === false)) {
					continue;
				}
            	func_product_delete($this, $this->product_id, $category_id, true);
            }
        }
        // add product pages
        foreach ($addCategory as $category_id) {
			if (is_array($deleteOnly) && !(array_search($category_id, $deleteOnly) === false)) {
				continue;
			}
			if (is_array($addOnly) && !(array_search($category_id, $addOnly) === false)) {
				continue;
			}
            func_category_update($this, $category_id, $this->product_id, true, true);
        }

        if ($this->post["action"] == "clone") {
        	$this->product_id = $this->updateLog["product_id"];
        }
        $product = func_new("Product", $this->product_id);
        foreach ($product->get("categories") as $category) {
        	$category_id = $category->get("category_id");
            func_product_update($this, $this->product_id, $category_id, true);
        }
    } // }}}

    function action_product_list() // {{{
    {
        $updateCategories = array();
        if ($this->post["action"] == "update") {
            foreach ($this->product_orderby as $product_id => $num) {
                $product = func_new("Product", $product_id);
                $categories = $product->get("categories");
                for ($i = 0; $i < count($categories); $i ++) {
                    func_product_update($this, $product_id, $categories[$i]->get("category_id"), true);
                    $updateCategories[] = $categories[$i]->get("category_id");
                }    
            }
        } elseif ($this->post["action"] == "delete") {
            if (!isset($this->product_ids)) {
                return;
            }
            // $this->updateLog is a product categories
            foreach ($this->product_ids as $product_id) {
                $categories = $this->updateLog[$product_id];
                $updateCategories = array_merge($updateCategories, $categories);
                for ($i = 0; $i < count($categories); $i ++) {
                    func_product_delete($this, $product_id, $categories[$i], true);
                }    
            }
        }
        $categories = array_unique($updateCategories);
        foreach ($categories as $category_id) {
            func_category_update($this, $category_id, 0, true);
        }
    } // }}}

    function action_extra_fields() // {{{
    {
		$category_ids = array();

    	switch ($this->post["action"]) {
    		case "update_fields":
    			$deleteMode = (isset($this->post["delete"])) ? true : false;
    			$deleteConfirmed = ($deleteMode && isset($this->post["delete_fields"]) && is_array($this->post["delete_fields"])) ? true : false;
    			if (!(isset($this->post["extra_fields"]) && is_array($this->post["extra_fields"]))) {
    				break;
    			}
    			foreach ($this->post["extra_fields"] as $extra_field_id => $extra_field_info) {
    				if (!(isset($extra_field_info["categories"]) && is_array($extra_field_info["categories"]))) {
    					continue;
    				}
    				if ($deleteConfirmed && array_search($extra_field_id, $this->post["delete_fields"]) === false) {
    					continue;
    				}
        			foreach ($extra_field_info["categories"] as $category_id) {
        				$category_ids[$category_id] = true;
        			}
    			}
				$allCatalog = (count($category_ids) == 0) ? true : false;
				if ($deleteMode && !$deleteConfirmed) {
					$allCatalog = false;
                    $category_ids = array();
				}
    		break;
    		case "add_field":
    			$allCatalog = (isset($this->post["add_categories"])) ? false : true;
        		if ($allCatalog) {
        			break;
        		}
    			if (!(isset($this->post["add_categories"]) && is_array($this->post["add_categories"]))) {
    				break;
    			}
    			foreach ($this->post["add_categories"] as $category_id) {
    				$category_ids[$category_id] = true;
    			}
    		break;
    	}

        // rebuild the whole catalog
        if ($allCatalog) {
            // delete all categories & product pages
            $catalog = $this->get("catalog");
            $catalog->clear();
            // rebuild the whole catalog
            $this->set("returnUrl", "admin.php?target=catalog&action=build&xlite_form_id=".$this->get('xliteFormID'));
            return;
        }
    	if (count($category_ids) > 0) {
            foreach ($category_ids as $category_id => $foo) {
                $category = func_new("Category", $category_id);
                $parent = $category->get("parent");
                $topID = $this->get("xlite.factory.Category.topCategory.category_id");
                // top category updated - rebuild the whole catalog
                if ($topID == $parent) {
                    // delete all categories & product pages
                    $catalog = $this->get("catalog");
                    $catalog->clear();
                    // rebuild the whole catalog
                    $this->set("returnUrl", "admin.php?target=catalog&action=build&xlite_form_id=".$this->get('xliteFormID'));
                    return;
                }
            }
            foreach ($category_ids as $category_id => $foo) {
                func_category_update($this, $category_id, null, true);
            }
        }
    }

	function action_global_product_options()
	{
        if (!(isset($this->post["categories"]) && is_array($this->post["categories"]))) {
        	return;
        }

        foreach ($this->post["categories"] as $category_id) {
            $category = func_new("Category", $category_id);
            $parent = $category->get("parent");
            $topID = $this->get("xlite.factory.Category.topCategory.category_id");
            // top category updated - rebuild the whole catalog
            if ($topID == $parent) {
                // delete all categories & product pages
                $catalog = $this->get("catalog");
                $catalog->clear();
                // rebuild the whole catalog
                $this->set("returnUrl", "admin.php?target=catalog&action=build&xlite_form_id=".$this->get('xliteFormID'));
                return;
            }
        }
        foreach ($this->post["categories"] as $category_id) {
            func_category_update($this, $category_id, null, true);
        }
	}

	function catalog_callback($url=null)
	{
		if (isset($url)) {
			if (isset($this->xlite->processedSteps["processedUrl"][$url])) {
				return true;
			} else {
				$this->xlite->processedSteps["processedUrl"][$url] = true;
				return false;
			}
		}

        // check for building limit
        if ($this->xlite->processedSteps["counter"]++ % ($this->get("config.HTMLCatalog.catalog_pages_count")) == 0) {
			$catalog = $this->get("catalog");
            $catalog->goAdmin();
die("!!!");
            $this->set("returnUrl", "admin.php?target=autoupdate_catalog&action=update&xlite_form_id=".$this->get('xliteFormID')."&returnUrl=" . urlencode($this->get("returnUrl")));
        	$this->session->set("post", serialize($this->post));
            $this->session->set("processedSteps", serialize($this->xlite->processedSteps));
    		$this->session->writeClose();
            $this->redirect();
            exit;
        }
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
