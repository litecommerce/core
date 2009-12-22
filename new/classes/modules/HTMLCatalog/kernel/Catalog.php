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

define('CATALOG_DIR', 'catalog');
define('CATALOG_PARENT_DIR', '../');
define('CATALOG_PATH', realpath(CATALOG_DIR)); 
define('CATALOG_INDEX_PAGE', 'index.html');
define('CATALOG_MAX_FILE_NAME_LENGTH', 255);
define('CATALOG_INDEX', CATALOG_PATH . '/pages.idx');
define('CATALOG_INDEX_RECSIZE', 65535);

set_time_limit(0); // building catalog is a long-time operation..

/**
* Class Catalog builds the static HTML catalog.
*
* @package Module_HTMLCatalog
* @access public
* @version $Id$
*/
class Catalog extends FlexyCompiler
{
    var $verbose     = true; // show process diagnostic
    var $recursive   = true;
    var $total       = 1;
    var $incomplete  = false;
    var $pageUrls    = array(); // store catalog URL's found in current page
    var $storeUrls   = array(); // store all found catalog links
    var $urlsProceed = array();  // save already processed catalog URL

    // experimental params
    var $category_id = null; // start with this category
    var $page_id     = null;       
    var $product_id  = null;  // start with this product
    var $buildCategories = null; // true || false
    var $buildProducts   = null; // -- // --
    var $topCategory = null;

    function getCatalogDir() // {{{
    {
        return CATALOG_DIR;
    } // }}}

    function getCatalogParentDir() // {{{
    {
        return CATALOG_PARENT_DIR;
    } // }}}

    function getCatalogPath() // {{{
    {
        return CATALOG_PATH;
    } // }}}

    function getCatalogIndex() // {{{
    {
        return CATALOG_INDEX;
    } // }}}

    function getCatalogIndexPage() // {{{
    {
        return CATALOG_INDEX_PAGE;
    } // }}}

    function getCatalogIndexRecSize() // {{{
    {
        return CATALOG_INDEX_RECSIZE;
    } // }}}

    function getUrlPrefix() // {{{
    {
		$prefix = $this->xlite->shopURL("");
        return $prefix;
    } // }}}
    
    function getURL() // {{{
    {
        return $this->get("urlPrefix") . $this->getCatalogDir() . '/' . $this->getCatalogIndexPage();
    } // }}}

	function getCustomerLayoutPath() // {{{
	{
		static $fcSrc = null;

		if (!is_null($fcSrc))
			return $fcSrc;

		if (!isset($this->customerLayout)) {
			$this->customerLayout = func_new("Layout");
			$adminZone = $this->xlite->get("adminZone");
			$this->xlite->set("adminZone", false);
			$this->customerLayout->initFromGlobals();
			$this->xlite->set("adminZone", $adminZone);
		}

		$fcSrc = $this->customerLayout->getPath();
		if (substr($fcSrc, strlen($fcSrc)-1, 1) == "/") {
			$fcSrc = substr($fcSrc, 0, strlen($fcSrc)-1);
		}

		return $fcSrc;
	} // }}}

    function getParentList($url) // {{{
    {
        $result = array();
        if (!empty($url)) {
            if ($fd = @fopen($this->getCatalogIndex(), "rb")) {
                while (!feof($fd)) {
                    $line = fgets($fd, $this->getCatalogIndexRecSize());
                    if (trim(chop($line)) == "") {
                        continue;
                    }
                    $line = chop($line);
                    if ((strpos($line, "$url,") || func_ends_with($line, $url)) && !func_starts_with($line, $url))
                    {
                        list($parent) = split(':', $line);
                        $result[] = $parent;
                    }
                }
                fclose($fd);
            }
        }
        return $result; 
    } // }}}

    function getChildList($url) // {{{
    {
        $children = array();
        if ($fd = @fopen($this->getCatalogIndex(), "rb")) {
            while (!feof($fd)) {
                $line = fgets($fd, $this->getCatalogIndexRecSize());
                if (trim(chop($line)) == "") {
                    continue;
                }
                list($parent, $filename, $links) = split(':', $line);
                if ($parent == $url || substr($parent, 0, strlen($url) + 1) == ($url . "&")) {
                    $children = array_merge($children, split(',', $links));
                }
            }
            fclose($fd);
        }
        return array_unique($children);
    } // }}}
    
    function getFileNameByURL($url) // {{{
    {
        if ($fd = @fopen($this->getCatalogIndex(), "rb")) {
            while (!feof($fd)) {
                $line = fgets($fd, $this->getCatalogIndexRecSize());
                if (trim(chop($line)) == "") {
                    continue;
                }
                list($parent, $filename, $children) = split(':', $line);
                if ($parent == $url) {
                    return $filename;
                }
            }
            fclose($fd);
        }
        return ""; 
    } // }}}

    function delete($url) // {{{
    {
        if (is_object($this->get("parentCaller"))) {
        	$parentCaller = $this->get("parentCaller");
        	$parentCallerAction = "catalog_callback";
        	if (method_exists($parentCaller, $parentCallerAction)) {
            	if ($parentCaller->$parentCallerAction($url)) {
                    return;
            	}
            }
        }

        $filename = $this->getFileNameByURL($url);
        if (!empty($filename)) {
            @unlink($filename);
            // delete index for this url
            $this->deleteIndex($url);
        }

        if (is_object($this->get("parentCaller"))) {
        	if (method_exists($parentCaller, $parentCallerAction)) {
            	$parentCaller->$parentCallerAction();
            }
        }
    } // }}}
    
    function deleteIndex($url) // {{{
    {
        // open index file for reading; look for $url record
        if ($fd = @fopen($this->getCatalogIndex(), "r+")) {
            // open temporary file for writing index data
            $temp = tmpfile();
            while (!feof($fd)) {
                $line = fgets($fd, $this->getCatalogIndexRecSize());
                if (trim(chop($line)) == "") {
                    continue;
                }
                list($parent, $filename, $children) = split(':', $line);
                if ($parent != $url) {
                    fwrite($temp, $line);
                }    
            }
            // truncate original index file to zero
            ftruncate($fd, 0);
            // rewind both temporary and index file pointers
            rewind($temp);
            rewind($fd);
            // copy temp => index
            while (!feof($temp)) {
                fwrite($fd, fgets($temp, $this->getCatalogIndexRecSize()));
            }
            fclose($fd);
            fclose($temp); // remove temp file
            @chmod($this->getCatalogIndex(), 0666);
        }
    } // }}}

    function updateIndex($url, $filename, $urls) // {{{
    {
        if (empty($urls)) return;
        // open index file for reading; look for $url record
        if ($fd = @fopen($this->getCatalogIndex(), "r+")) {
            $found = false;
            $list = implode(',', $urls);
            $record = "$url:".$this->getCatalogDir()."/$filename:$list\n";
            // open temporary file for writing index data
            $temp = tmpfile();
            while (!feof($fd)) {
                $line = fgets($fd, $this->getCatalogIndexRecSize());
                if (trim(chop($line)) == "") {
                    continue;
                }
                list($parent, $filename, $children) = split(':', $line);
                if ($parent == $url) {
                    // update index record
                    fwrite($temp, $record);
                    $found = true;
                } else {
                    fwrite($temp, $line);
                }
            }
            // add new record if no previous found
            if (!$found) {
                fwrite($temp, $record);
            }
            // truncate original index file to zero
            ftruncate($fd, 0);
            // rewind both temporary and index file pointers
            rewind($temp);
            rewind($fd);
            // copy temp => index
            while (!feof($temp)) {
                fwrite($fd, fgets($temp, $this->getCatalogIndexRecSize()));
            }
            fclose($fd);
            fclose($temp); // remove temp file
            @chmod($this->getCatalogIndex(), 0666);
        }
    } // }}}

    /**
    * Checks whether catalog has been built already or not
    */
    function isBuilt() // {{{
    {
        return file_exists($this->getCatalogPath() . '/' . $this->getCatalogIndexPage()) &&
               file_exists($this->getCatalogIndex());
    } // }}}
    
	function substFCHref($matches)
	{
		$this->substitutionStart = array();
		$this->substitutionEnd = array();
		$this->substitutionValue = array();
		$this->source = $matches[0];
		$this->substHref(strpos($matches[0], $matches[1]), strlen($matches[0]) - 3);

		return $this->substitute();
	}

    function _substituteFCJSHref($content)
    {
    	$pattern = "/var catHref='([^']*)'; /sU";
    	return preg_replace_callback($pattern, array($this, "substFCHref"), $content);
    }

    function _rebuildFCJSCache($fcSrc, $jsName)
    {
		$content = '';
		$filename = $fcSrc . "/" . $jsName . ".js";

		if (!file_exists($filename))
			return false;

		if ($fd1 = @fopen($filename, "rb")) {
			while (!feof($fd1)) {
				$content .= @fread($fd1, 8192);
			}
			fclose($fd1);
		}

		if ($content) {
			if ($fd2 = @fopen($filename, "wb") ) {
				$content = str_replace("\"".$this->getCustomerLayoutPath()."/modules/FlyoutCategories/", "\"FlyoutCategories/", $content);
				$content = str_replace("'".$this->getCustomerLayoutPath()."/modules/FlyoutCategories/", "'FlyoutCategories/", $content);
				$content = $this->_substituteFCJSHref($content);

				@fwrite($fd2, $content);
				@fwrite($fd2, "\n");
				fclose($fd2);

				return true;
			}
		}

		return false;
    }

    function buildFCJS()
    {
        if ($this->xlite->get("FlyoutCategoriesEnabled")) {
			$list = array("style.css", "images");
			foreach ($list as $node) {
				$dst = $this->getCatalogDir() . "/" . $node;
				$src = $this->getCustomerLayoutPath() . "/" . $node;
				copyRecursive($src, $dst, 0644, 0755);
			}

			mkdirRecursive($this->getCatalogDir()."/FlyoutCategories", 0755);

			$list = array("menumanagement.js", "layerslib.js", "layerslibvar.js", "catalog");
			foreach ($list as $node) {
				$dst = $this->getCatalogDir()."/FlyoutCategories/$node";
				$src = $this->getCustomerLayoutPath()."/modules/FlyoutCategories/$node";
				copyRecursive($src, $dst, 0644, 0755);
			}

			$fcSrc = $this->getCatalogDir()."/FlyoutCategories";
			$files = array("catalog/body_header", "catalog/body_footer");
			foreach ($files as $file) {
				$this->_rebuildFCJSCache($fcSrc, $file);
			}

		}
    }

    function build(&$dialog, $initRequired = false) // {{{
    {
        if ($initRequired) {
            $this->init();

            $this->buildFCJS();
        } else {
            $this->set("category_id", $dialog->get("category_id"));
            $this->set("page_id", $dialog->get("page_id"));
            $product_id = $dialog->get("product_id");
            if (!is_null($product_id) && !empty($product_id)) {
                $this->set("product_id", $product_id);
            } 
        }

		$category_id = $dialog->get("category_id");
		$product_id = $dialog->get("product_id");
		if (!((isset($category_id) && intval($category_id) >= 0) || (isset($product_id) && intval($product_id) > 0))) {
			$this->xlite->session->set("categoriesAlreadyGenerated", null);
			$this->xlite->session->writeClose();
		}

        $this->xlite->set("HTMLCatalogWorking", true);
        $this->goCustomer();
		$id = $this->get("category_id");
		if (isset($id)) {
			$this->set("dest_category_id", $id);
		}
		$id = $this->get("page_id");
		if (isset($id)) {
			$this->set("dest_page_id", $id);
		}
        $this->create();
        $this->goAdmin();
        $this->xlite->set("HTMLCatalogWorking", false);
    } // }}}
    
    /**
    * Start building catalog from this Category
    */
    function getTopCategory() // {{{
    {
        if (!is_null($this->topCategory)) {
            $id = $this->topCategory;
        } else {
            $id = $this->get("config.HTMLCatalog.catalog_category");
        }    
        if (!empty($id)) {
            $category = func_new("Category", $id);
        } else {
            $category = $this->get("xlite.factory.Category.topCategory");
        }    
        return $category;
    } // }}}

	/**
	 * Builds new static catalog
	 * @access public
	 */
    function create($category = null) // {{{
    {
        if ($this->is("incomplete")) {
            return;
        }
        if (is_null($category)) {
            // start with a root category
            $category = $this->get("topCategory");
        }

		$this->goAdmin();
		$alreadyGenerated = $this->xlite->session->get("categoriesAlreadyGenerated");
		$this->goCustomer();
		if (!(is_array($alreadyGenerated) && isset($alreadyGenerated[$category->get("category_id")]))) {
            // get category products
            $products = $category->get("products");

            // build category HTML page(s) (subcategories & products)
            if ($this->get("config.HTMLCatalog.catalog_pages") == "both" || $this->get("config.HTMLCatalog.catalog_pages") == "categories") {
                $pages = ceil(count($products) / $this->get("config.General.products_per_page"));
                $pages = $pages == 0 ? 1 : $pages;

				// check if the category is visible
				$category_valid = true;
				$_category_id = $category->get("category_id");
				if ($_category_id > 0) {
					$category_valid = $category->filter();
					if ($category->get("category_id") != $_category_id) {
						$category->set("category_id", $_category_id);
						$category->isPersistent = true;
						$category->isRead = true;
					}
				}

				if ((!$this->get("all_pages")) && ($category_valid)) {
                    for ($id = 0; $id < $pages; $id++) {
                        // skip to next category?
                        if (!$this->is("incomplete") && (!is_null($this->get("category_id")) && $this->get("category_id") != $category->get("category_id"))) {
        					if (!$this->get("skipUnmatched")) {
                            	continue;
                            }
                        }
                        // skip to next category page?
                        elseif (!is_null($this->get("category_id")) && $this->get("category_id") == $category->get("category_id") && !is_null($this->get("page_id")) && $id <= $this->get("page_id")) {
        					if (!$this->get("skipUnmatched")) {
                            	continue;
                            }
                        }

                        $url = $this->getCategoryUrl($category->get("category_id"), $id);
                        $this->process($url);
                        if ($this->is("incomplete")) { // && $id < ($pages-1)) {
                            // store current category ID
                            $this->set("category_id", $category->get("category_id"));
                            $this->set("page_id", $id);
                            $this->set("all_pages", true);
                            return;
                        }
                    }
                }
                // ALL subcategories passed. Reset category & page ID.
                if (!$this->is("incomplete") && !is_null($this->get("category_id")) && $this->get("category_id") == $category->get("category_id")) {
                    $this->set("category_id", null);
                    $this->set("page_id", null);
                }
            }    
            // build category products page
			global $old_class;
			global $object;
    		if (is_null($this->get("onlyProduct")) || !(!is_null($this->get("onlyProduct")) && $this->get("onlyProduct") == 0)) {
                for ($i = 0; $i < count($products); $i++) {
                    if ($this->get("config.HTMLCatalog.catalog_pages") == "both" || $this->get("config.HTMLCatalog.catalog_pages") == "products") {
                        if (is_array($products[$i]) && isset($products[$i]["class"]) && isset($products[$i]["data"])) {
                        	if ($old_class != $products[$i]["class"] || !is_object($object)) {
                        		$old_class = $products[$i]["class"];
                        		$object = func_new($products[$i]["class"]);
                        	}
                            $object->isPersistent = true;
                            $object->isRead = false;
                            $object->properties = $products[$i]["data"];
                            $this->goAdmin();
                            $alreadyGenerated = $this->xlite->session->get("productsAlreadyGenerated");
                            $this->goCustomer();
                            if (!is_array($alreadyGenerated) || (is_array($alreadyGenerated) && !isset($alreadyGenerated[$products[$i]["data"]["product_id"]]))) {
                            	if (!is_array($alreadyGenerated)) {
                            		$alreadyGenerated = array();
                            	}
                                $alreadyGenerated[$products[$i]["data"]["product_id"]] = true;
                                $this->goAdmin();
                                $this->xlite->session->set("productsAlreadyGenerated", $alreadyGenerated);
                                $this->xlite->session->writeClose();
                                $this->goCustomer();
                                $products[$i] = $object;
                                $is_new_product = true;
                                $this->set("product_id", $products[$i]["data"]["product_id"]);
                            } else {
                            	continue;
                            }
                        }
 
                        // check that product_id is set (catalog next page)
                        if (!$this->is("incomplete") && ((!is_null($this->get("product_id")) && $this->get("product_id") != $products[$i]->get("product_id")) || (!is_null($this->get("product_id")) && $this->get("product_id") == $products[$i]->get("product_id") && !is_null($this->get("category_id")) && $this->get("category_id") != $category->get("category_id")))) {
                            continue;
                        } elseif (!$this->is("incomplete") && !is_null($this->get("product_id")) && $this->get("product_id") == $products[$i]->get("product_id")) {
                            // reset product ID
                            $this->set("product_id", null);
                            continue;
                        } elseif (!is_null($this->get("category_id")) && $this->get("category_id") != $category->get("category_id")) {
                            continue;
                        } else {
                            // reset product ID
                            $this->set("product_id", null);
                        }
                        $url = $this->getProductUrl($products[$i]->get("product_id"), $category->get("category_id"));

        				if (!is_null($this->get("onlyProduct")) && $this->get("onlyProduct") != $products[$i]->get("product_id")) {
        					continue;
        				}

                        $this->process($url);
                        if ($this->is("incomplete")) {
                            // store current category ID
                            $this->set("category_id", $category->get("category_id"));
                            $this->set("page_id", $id);
                            $this->set("product_id", $products[$i]->get("product_id"));
                            if (isset($products)) {
                            	unset($products);
                            }
                            return;
                        }
                    }    
                }
    		}
    	}

        $this->goAdmin();
        $alreadyGenerated = $this->xlite->session->get("categoriesAlreadyGenerated");
        if (!is_array($alreadyGenerated)) {
            $alreadyGenerated = array();
        }
        if (!isset($alreadyGenerated[$category->get("category_id")])) {
            $alreadyGenerated[$category->get("category_id")] = true;
            $this->xlite->session->set("categoriesAlreadyGenerated", $alreadyGenerated);
            $this->xlite->session->set("productsAlreadyGenerated", null);
            $this->xlite->session->writeClose();
            $this->goCustomer();
            $this->set("incomplete", true);
            if (is_object($category)) {
                $this->set("category_id", $category->get("category_id"));
              }
              $this->set("page_id", $id);
            if (is_object($products[$i])) {
                $this->set("product_id", $products[$i]->get("product_id"));
              }
            return;
          }
        $this->goCustomer();

        if ($this->is("recursive") && !$this->is("incomplete")) {
            foreach ($category->get("subcategories") as $c) {
                if ($this->get("dest_category_id") != $c->get("category_id")) {
                	$this->set("page_id", null);
                } else {
                	$this->set("page_id", $this->get("dest_page_id"));
                }
                $this->set("category_id", $c->get("category_id"));
                $this->create($c);
                if ($this->is("incomplete")) {
                	break;
                }
            }
        }
    } // }}}

    function getCategoryUrl($category_id, $page_id = 0) // {{{
    {
        $topID = $this->get("xlite.factory.Category.topCategory.category_id");
        $pageID = "";
        if ($category_id == $topID) {
            return CART_SELF;
        } elseif ($page_id != 0) {
            $pageID = "&pageID=".$page_id;
        }    
        return CART_SELF . "?target=category&category_id=$category_id$pageID";
    } // }}}

    function getProductUrl($product_id, $category_id) // {{{
    {
        return CART_SELF . "?target=product&product_id=$product_id&category_id=$category_id";
    } // }}}

	function init() // {{{
	{
		$noCatalog = false;
        // remove entire catalog if configured
        if ($this->get("config.HTMLCatalog.drop_catalog")) {
            unlinkRecursive($this->getCatalogPath());
			@mkdir($this->getCatalogPath(), 0777);
			$noCatalog = true;
        }
        if (!file_exists($this->getCatalogPath())) {
			@mkdir($this->getCatalogPath(), 0777);
			$noCatalog = true;
        }
        if ($noCatalog && (!file_exists($this->getCatalogPath()) || !is_dir($this->getCatalogPath()))) {
			$this->set("error", "could not create catalog directory ".$this->getCatalogPath()."<br>Permission denied.");
            return;
		}	
        // reset catalog index
        if (file_exists($this->getCatalogIndex())) {
            @unlink($this->getCatalogIndex());
        }
        // create index file
        @touch($this->getCatalogIndex());
        @chmod($this->getCatalogIndex(), 0666);
        if (version_compare($this->config->get("Version.version"), "2.2.39", "ge")) {
            $htaccess = func_new("Htaccess");
            if ($htaccess->find("filename='catalog/.htaccess'"))
                $htaccess->restoreFile();
        }
	} // }}}

	/**
	 * Clears existing static catalog
	 * @access public
	 */
	function clear() // {{{
	{
		if (!is_dir($this->getCatalogPath())) {
            $this->set("error", "static HTML catalog not found");
			return;
        }
        if ($dh = opendir($this->getCatalogPath())) { 
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    @unlink($this->getCatalogPath() . '/'. $file);
                }
            } 
            closedir($dh);
        }
        if (version_compare($this->config->get("Version.version"), "2.2.39", "ge")) {
            $htaccess = func_new("Htaccess");
            if ($htaccess->find("filename='catalog/.htaccess'")) 
                $htaccess->restoreFile();
        }
	} // }}}

    function process($url) // {{{
    {
		if (version_compare($this->config->get("Version.version"), "2.2.35") <= 0) {
			@include_once("modules/HTMLCatalog/functions.php");
		}

		$current_limit = @ini_get("memory_limit");
		$current_limit_byte = func_convert_to_byte($current_limit);
		if (function_exists("memory_get_usage") && $current_limit_byte - memory_get_usage() < 8 * 1024 * 1024) {
			func_check_memory_limit($current_limit_byte, $current_limit_byte + 8 * 1024 * 1024);
		}

        if (is_object($this->get("parentCaller"))) {
        	$parentCaller = $this->get("parentCaller");
        	$parentCallerAction = "catalog_callback";
        	if (method_exists($parentCaller, $parentCallerAction)) {
            	if ($parentCaller->$parentCallerAction($url)) {
                    // save URL to the list of processed URL's and increment URL's counter
                    $this->urlsProceed[] = $url;
                    $this->total++;
                    $filename = $this->createFileName($url);
                    $this->updateIndex($url, $filename, $this->pageUrls[$url]);
                    // check for building limit
                    if ($this->total % ($this->get("config.HTMLCatalog.catalog_pages_count") + 1) == 0) {
                        $this->set("incomplete", true);
                    }

                    return;
            	}
            }
        }

        // skip if error
        if (isset($this->error)) {
            return;
        }
        // fetch HTML page content
        $content = $this->fetch($url); 
        // parse content, make substitutions and get catalog links
        $this->url = $url;
        $content = $this->parsePage($content);
        // save static HTML page
        $filename = $this->createFileName($url);
        $result = $this->save($filename, $content);
        if ($this->verbose) {
            $this->log($result);
        }
        // save URL to the list of processed URL's and increment URL's counter
        $this->urlsProceed[] = $url;
        $this->total++;
        $this->updateIndex($url, $filename, $this->pageUrls[$url]);
        // check for building limit
        if ($this->total % ($this->get("config.HTMLCatalog.catalog_pages_count") + 1) == 0) {
            $this->set("incomplete", true);
        }

        if (is_object($this->get("parentCaller"))) {
        	if (method_exists($parentCaller, $parentCallerAction)) {
            	$parentCaller->$parentCallerAction();
            }
        }
    } // }}}

    /**
    * parses url into array, cart.php?target=category&category_id=120
    * returns array("target" => "category", "category_id" => "120"); 
    */
    function parseUrl($url) // {{{
    {
    	$url = str_replace("&amp;", "&", $url);
        $href = func_parse_url($url);
        parse_str($href["query"], $request);
        return $request;
    } // }}}

    function goCustomer() // {{{
    {
        if (!$this->xlite->get("adminZone")) {
          return;
        }

        // save current (admin) environment and build new (customer)
        $this->_REQUEST = $_REQUEST;
        $this->_GET     = $_GET;
        $this->_POST    = $_POST;
        $this->_COOKIE  = $_COOKIE;
        $this->_SERVER  = $_SERVER;

        // reset autoglobals
        $_REQUEST = array();
        $_GET     = array();
        $_POST    = array();
        $_COOKIE  = array();

        $_SERVER["REQUEST_METHOD"] = "GET";
        // fake http
        if (isset($_SERVER['HTTPS'])) {
        	unset($_SERVER['HTTPS']);
        }
        $_SERVER['SERVER_PORT'] = "80";

        // reset session content
        $this->_sessionData = $this->session->_data;
        $this->session->_data = array();

        // switch layout to customer's zone
        $layout = func_get_instance("Layout");
        $layout->set("skin", "default");

        // empty cart
        $cart = func_get_instance("Cart");
        $cart = null;

        // switch XLite to customer's zone
        $this->xlite->set("adminZone", false);

        $this->xlite->set("ignoreCustomerSecurity", true);
    } // }}}

    function goAdmin() // {{{
    {
        if ($this->xlite->get("adminZone")) {
          return;
        }

        // switch XLite back to admin's zone
        $this->xlite->set("adminZone", true);
        $this->session->_data = $this->_sessionData;

        $this->xlite->set("ignoreCustomerSecurity", false);
    } // }}}

    function fetch($url) // {{{
    {
        $_REQUEST = $this->parseUrl($url);
        $_GET     = $_REQUEST;

        $content = "";

        ob_start();
        $this->xlite->run();
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    } // }}}

    function parsePage($content) // {{{
    {
        $this->source = $content;
        $this->_ignorePHPtag = true;
        $this->parse();
        $this->_ignorePHPtag = false;
        return $this->result;
    } // }}}

	function phptag()
	{
		if ($this->_ignorePHPtag) {
			return false;
		}

		return parent::phptag();
	}

    function postprocess() // {{{
    {
        // parse static HTML tags/attributes 
        // replace relative pathes to full
        for ($i = 0; $i < count($this->tokens); $i++) {
            $token = $this->tokens[$i];
            if ($token["type"] == "attribute") {
                // get tab name for the further processing
                $name = strtolower($token["name"]);
            } elseif ($token["type"] == "attribute-value") {
                $val = $this->getTokenText($i);
                // process attribute
                if ($name == 'style') {
                    $pos = strpos($val, 'url(');
                    if ($pos !== false) {
                        $this->substImage($pos + 5 + $token["start"], strpos($val, ')') + $token["start"] - 1);
                    }
                } elseif ($name == 'background' || $name == 'src') {
                    $this->substImage($token["start"], $token["end"]);
                } elseif ($name == 'href' || $name == 'action') {
                    $this->substHref($token["start"], $token["end"]);
                }
                $name = '';
            } else {
                $name = '';
            }
        }
        $this->result = $this->substitute();

        if ($this->xlite->get("FlyoutCategoriesEnabled")) {
			$fcSrc = $this->getCustomerLayoutPath();

			$this->result = str_replace("\"" . $fcSrc . "/modules/FlyoutCategories/", "\"FlyoutCategories/", $this->result);
			$this->result = str_replace("'" . $fcSrc . "/modules/FlyoutCategories/", "'FlyoutCategories/", $this->result);
			$fcSrc = $this->getCatalogParentDir() . $fcSrc;
			$this->result = str_replace("\"" . $fcSrc . "/modules/FlyoutCategories/", "\"FlyoutCategories/", $this->result);
			$this->result = str_replace("'" . $fcSrc . "/modules/FlyoutCategories/", "'FlyoutCategories/", $this->result);
        	$this->result = str_replace("var static_catalog_path = \"\";", "var static_catalog_path = \"" . $this->getCatalogParentDir() . "\";", $this->result);

			$this->result = $this->_substituteFCJSHref($this->result);

    		$this->result = str_replace("\"" . $this->getCatalogParentDir() . $this->getCustomerLayoutPath() . "/style.css", "\"style.css", $this->result);
    		$this->result = str_replace("\"" . $this->getCatalogParentDir() . $this->getCustomerLayoutPath() . "/images", "\"images", $this->result);
        }
    } // }}}

    function substImage($start, $end) // {{{
    {
        $img = substr($this->source, $start, $end - $start);
        if ($img{0} != '/' && strcasecmp(substr($img, 0, 7), 'http://') &&
                              strcasecmp(substr($img, 0, 8), 'https://'))
        {
            $img = $this->getCatalogParentDir() . $img;
            $this->subst($start, $end,  $img);
        }
    } // }}}

    function substHref($start, $end) // {{{
    {
    	$prefixes = array
    	(
    		"http://",
    		"https://",
    		"ftp://",
    		"javascript:",
    		"mailto:",
    	);

        $href = substr($this->source, $start, $end - $start);
        $prefix_found = false;
        foreach($prefixes as $prefix) {
        	if (strcasecmp(substr($href, 0, strlen($prefix)), $prefix) == 0) {
        		$prefix_found = true;
        		break;
        	}
        }

        if (!$prefix_found)
        {
			if ($this->xlite->get("FlyoutCategoriesEnabled")) {
				$pos = strpos($href, $this->getCustomerLayoutPath()."/modules/FlyoutCategories/");
				if ($pos !== false && $pos == 0) {
					$filename = "FlyoutCategories/".substr($href, strlen($this->getCustomerLayoutPath()."/modules/FlyoutCategories/"));
					$this->subst($start, $end, $filename);
					return;
				}
			}
            if ($this->isCatalogLink($href)) {
                // record page catalog links
                if (!isset($this->pageUrls[$this->url])) {
                    $this->pageUrls[$this->url] = array();
                }
                if (!in_array($href, $this->pageUrls[$this->url])) {
                    $this->pageUrls[$this->url][] = $href;
                }    
                // add link to all catalog links store
                if (!in_array($href, $this->storeUrls)) {
                    $this->storeUrls[] = $href;
                }    
                // get link substitution (file name)
                $filename = $this->createFileName($href);
                $this->subst($start, $end, $filename);
            } elseif ($this->isHomeLink($href)) {
                // home page link
                $this->subst($start, $end, $this->get("url"));
            } elseif ($href{0} != '/') { // do not rewirite site-root links
                // link href to parent directory
                $href = $this->getCatalogParentDir() . $href;
                $this->subst($start, $end, $href);
            }
        }
    } // }}}

    function isHomeLink($href) // {{{
    {
        return $href == CART_SELF || $href == $this->xlite->shopUrl(CART_SELF);
    } // }}}
    
    /**
    * cart.php?target=catalog
    * cart.php?target=product
    */
    function isCatalogLink($href) // {{{
    {
        return ($this->isCategoryLink($href) || $this->isProductLink($href)) && !preg_match("/action=buynow/i", $href);
    } // }}}

    function isCategoryLink($href) // {{{
    {
         return preg_match("/".CART_SELF."\?target=category/i", $href) && ($this->get("config.HTMLCatalog.catalog_pages") == "both" || $this->get("config.HTMLCatalog.catalog_pages") == "categories");
    } // }}}
    
    function isProductLink($href) // {{{
    {
        return preg_match("/".CART_SELF."\?target=product/i", $href) && ($this->get("config.HTMLCatalog.catalog_pages") == "both" || $this->get("config.HTMLCatalog.catalog_pages") == "products");
    } // }}}
    
    function getObjectName(&$obj, $field=null)
    {
    	$field = (!isset($field)) ? "name" : $field;
        $name = substr($obj->get($field), 0, CATALOG_MAX_FILE_NAME_LENGTH);
        $name =preg_replace("/[ \/]/", "_", $name);
        $name = preg_replace("/[^A-Za-z0-9_]+/", "", $name);

        return $name;
    }

    function getCategoryFileName(&$request, &$category)
    {
    	$catname = $this->config->get("HTMLCatalog.category_name_format");
    	$catname = str_replace("%cid", $category->get("category_id"), $catname);
    	$catname = str_replace("%cname", $this->getObjectName($category), $catname);
        if (isset($request["pageID"]) && $request["pageID"] != 0) {
    		$pagename = str_replace("%page", $request["pageID"], $this->config->get("HTMLCatalog.category_page_format"));
        } else {
    		$pagename = "";
        }
    	$catname = str_replace("%cpage", $pagename, $catname);

		return $catname;
    }

    function getProductFileName(&$request, &$category, &$product)
    {
    	$prodname = $this->config->get("HTMLCatalog.product_name_format");
    	$prodname = str_replace("%cid", $category->get("category_id"), $prodname);
    	$prodname = str_replace("%cname", $this->getObjectName($category), $prodname);
        if (isset($request["pageID"]) && $request["pageID"] != 0) {
    		$pagename = str_replace("%page", $request["pageID"], $this->config->get("HTMLCatalog.category_page_format"));
        } else {
    		$pagename = "";
        }
    	$prodname = str_replace("%pid", $product->get("product_id"), $prodname);
    	$prodname = str_replace("%pname", $this->getObjectName($product), $prodname);
    	$prodname = str_replace("%psku", $this->getObjectName($product, "sku"), $prodname);

		return $prodname;
    }

    function createFileName($url) // {{{
    {
        $request = $this->parseUrl($url);
        if (empty($request)) {
            $url = $this->get("url");
            $href = "<a href=\"$url\" target=\"_blank\"><u>index page</u></a>";
            $this->logMessage = "catalog $href ... ";
            return $this->getCatalogIndexPage();
        }    
        if (isset($request["category_id"])) {
        	// avoiding modules influence
        	$adminZone = $this->xlite->get("adminZone");
            $this->xlite->set("adminZone", true);

            $category = func_new("Category", $request["category_id"]);
            $category->read();

            $this->xlite->set("adminZone", $adminZone);
        }
        if (isset($request["product_id"])) {
        	// avoiding modules influence
        	$adminZone = $this->xlite->get("adminZone");
            $this->xlite->set("adminZone", true);

            $product = func_new("Product", $request["product_id"]);
            $product->read();

            if (!isset($request["category_id"])) {
            	$pc = $product->getCategories();

                $category = func_new("Category", $pc[0]->get("category_id"));
            	$category->read();
            }

            $this->xlite->set("adminZone", $adminZone);
        }
        if (isset($request["pageID"]) && $request["pageID"] != 0) {
            $pagename = "_page_" . $request["pageID"];
            $page = ", page " . $request["pageID"];
        }
        $target = $request["target"];
        if ($target == "category") {
            $filename = $this->getCategoryFileName($request, $category); // "category_" . $category->get("category_id") . "_" . $catname . $pagename . ".html";
            $catPath = $category->get("stringPath") . $page;
            $fullname = $this->getCatalogDir() . '/' . $filename;
            $url = "<a href=\"$fullname\" target=\"_blank\"><u>$catPath</u></a>";
            $this->logMessage = "category $url ... ";
        } elseif ($target == "product") {
            $name = $product->get("name");
            $filename = $this->getProductFileName($request, $category, $product); // "product_" . $product->get("product_id") . "_" . $prodname . "_cat_" . $category->get("category_id") . ".html";
            $fullname = $this->getCatalogDir() . '/' . $filename;
            $url = "<a href=\"$fullname\" target=\"_blank\"><u>$name</u></a>";
            $categoryFilename = $this->getCategoryFileName($request, $category); // "category_" . $category->get("category_id") . "_" . $catname . $pagename . ".html";
            $catPath = $category->get("stringPath") . $page;
            $categoryFullname = $this->getCatalogDir() . '/' . $categoryFilename;
            $categoryUrl = "<a href=\"$categoryFullname\" target=\"_blank\"><u>$catPath</u></a>";
            $this->logMessage = "product $url ($categoryUrl) ... ";
        } else {
            $this->logMessage = null;
        }
        return $filename;
    } // }}}

    function save($filename, $content) // {{{
    {
        $filename = $this->getCatalogPath() . DIRECTORY_SEPARATOR . $filename;
        $fd = @fopen($filename, "wb");
        if (!$fd) {
            $this->set("error", "could not create file " . $filename);
            return false; 
        }
        fwrite($fd, $content);
        fclose($fd);
        @chmod($filename, 0666);

        return true;
    } // }}}

    function log($result) // {{{
    {
        if (isset($this->logMessage)) {
            echo $this->logMessage;
            if ($result) {
                echo "[<font color=green>OK</font>]";
            } else {
                echo "[<font color=red>FAILED!</font>]";
            }
            if ($this->get("config.HTMLCatalog.catalog_memory") && function_exists('memory_get_usage')) {
                printf(" (%.2f Mb used)", $GLOBALS['memory_usage']);
				// $maxMemoryUsage = $this->config->get("HTMLCatalog.memory_usage");
				// if ($maxMemoryUsage < $GLOBALS['memory_usage']) {
				// 	if (!is_object($this->cfg)) {
            	// 		$this->cfg = func_new("Config");
				// 	}
                // 	$this->cfg->createOption("HTMLCatalog", "memory_usage", $GLOBALS['memory_usage'], "text");
                //     // re-read config data
                // 	$this->config = $this->cfg->readConfig();
                // }
            }
            echo "<br>\n";
            func_flush();
        }
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
