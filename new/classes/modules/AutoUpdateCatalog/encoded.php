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

function func_categories_update(&$dialog, $category_id) // {{{
{
    $catalog = $dialog->get("catalog");
    // switch catalog to customer's zone
    $catalog->goCustomer();

    $catalog = $dialog->get("catalog");
    // rebuild categories parent page
    $catalog->set("topCategory", $category_id);
    $catalog->set("recursive", false);
    for (;;) {    
        $catalog->create();
        if ($catalog->is("incomplete")) {
            $catalog->set("incomplete", false); // reset incomplete flag
            continue;
        } else {
            break;
        }
    }
    // switch back to admin's context
    $catalog->goAdmin();
} // }}}

function func_category_add(&$dialog, $category_id) // {{{
{
    $catalog = $dialog->get("catalog");
    // switch catalog to customer's zone
    $catalog->goCustomer();

    $url = $dialog->getCategoryUrl($category_id);
    $catalog->process($url);

    // switch back to admin's context
    $catalog->goAdmin();
} // }}}

function func_categories_delete(&$dialog, $category_id, $deleteParents = true) // {{{
{
    $catalog = $dialog->get("catalog");
    // switch catalog to customer's zone
    $catalog->goCustomer();
    $url = $dialog->getCategoryUrl($category_id);
    $parents = $catalog->getParentList($url); // referencing pages
    $catalog->delete($url);
    if ($deleteParents) {
        foreach ($parents as $parent) {
            // get parents of deleting page
            $links = $catalog->getParentList($parent);
            $catalog->delete($parent);
            // category product can be referenced as a featured/bestseller
            if ($catalog->isProductLink($parent)) {
                $extraParents = array_diff($links, $parents);
                foreach ($extraParents as $p) {
                    $catalog->process($p);
                }
            }
        }
    }

    // switch back to admin's context
    $catalog->goAdmin();
} // }}}

function func_category_update(&$dialog, $category_id, $product_id=null, $callback=false, $ignoreAlreadyGenerated=false) // {{{
{
    if ($ignoreAlreadyGenerated) {
		$dialog->xlite->session->set("categoriesAlreadyGenerated", null);
		$dialog->xlite->session->set("productsAlreadyGenerated", null);
		$dialog->xlite->session->writeClose();
	}

    $catalog = $dialog->get("catalog");
    // switch catalog to customer's zone
    $catalog->goCustomer();

    // rebuild category with product added
    $catalog->set("topCategory", $category_id);
    $catalog->set("skipUnmatched", true);
    for (;;) {    
        if (isset($product_id)) {
        	$catalog->set("onlyProduct", $product_id);
        }
        if ($callback) {
        	$catalog->set("parentCaller", $dialog);
        }
        $catalog->create();
        if ($catalog->is("incomplete")) {
            $catalog->set("incomplete", false); // reset incomplete flag
            continue;
        } else {
            break;
        }
    }
    
    // switch back to admin's context
    $catalog->goAdmin();
} // }}}

function func_product_update(&$dialog, $product_id, $category_id, $callback=false) // {{{
{
    $catalog = $dialog->get("catalog");
    // switch catalog to customer's zone
    $catalog->goCustomer();

    // update product page
    $url = $dialog->getProductUrl($product_id, $category_id);
    $parents = $catalog->getParentList($url); // old referencing pages
    if ($callback) {
    	$catalog->set("parentCaller", $dialog);
    }

	$product = func_new("Product", $product_id);
	if ($product->filter()) {
	    $catalog->process($url); // rebuild product page and index
	} else {
		if ($dialog->get("config.HTMLCatalog.drop_catalog")) {
			$catalog->delete($url);
		}	
	}
    // update this product related pages
    foreach ($parents as $parent) {
        $catalog->process($parent);
    }   
    // switch back to admin's context
    $catalog->goAdmin();
} // }}}

function func_product_delete(&$dialog, $product_id, $category_id, $callback=false) // {{{
{
    $catalog = $dialog->get("catalog");
    // switch catalog to customer's zone
    $catalog->goCustomer();

    $url = $dialog->getProductUrl($product_id, $category_id);
    $parents = $catalog->getParentList($url); // old referencing pages
    if ($callback) {
    	$catalog->set("parentCaller", $dialog);
    }
	$filename = $catalog->getFileNameByURL($url);
	if (!empty($filename)) {
        $category = func_new("Category", $category_id);
        $catname = $category->get("stringPath");
    	$catfilename = $catalog->getFileNameByURL($catalog->getCategoryUrl($category_id));
        $product = func_new("Product", $product_id);
        $prodname = $product->get("name");
		if (empty($prodname)) {
			$prodname = "DELETED PRODUCT #$product_id";
		}
        $href = "<a href=\"$filename\" target=\"_blank\"><u>$prodname</u></a> <a href=\"$catfilename\" target=\"_blank\"><u>($catname)</u></a>";
        $catalog->logMessage = "product $href ... ";
    }
    $catalog->delete($url);
	if (!empty($filename)) {
    	$catalog->log(true);
    }
    // update this product related pages
    foreach ($parents as $parent) {
        $catalog->process($parent);
    }    
    // switch back to admin's context
    $catalog->goAdmin();
} // }}}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
