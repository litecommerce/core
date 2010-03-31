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
* Wysiwyg editor
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_Controller_Admin_Wysiwyg extends XLite_Controller_Admin_Abstract
{	
    public $builder = null;	
    public $exportTemplates = array("main.tpl", "common/print_invoice.tpl");	
    public $shortcuts = array(
            "var/html/main.html"=>"Storefront (aka Main page)", 
            "var/html/register_form.html"=>"User registration form",
            "var/html/profile.html"=>"User profile form",
            "var/html/common_invoice.html"=>"Invoice (as it appears at the end of checkout process)", 
            "var/html/common_print_invoice.html"=>"Printable invoice", 
            "var/html/common_dialog.html"=>"Dialog content area (aka Dialog window)",
            "var/html/common_sidebar_box.html"=>"Sidebars menu (aka Menu window)");
    
    function getShortcuts()
    {
        $result = array();
        foreach ($this->shortcuts as $page => $description) {
            if (file_exists($page)) {
                $result[$page] = $description;
            }
        }
        return $result;
    }

    function getBuilder()
    {
        if (is_null($this->builder)) {
            $this->builder = new XLite_Model_Wysiwyg_Mediator();
        }
        return $this->builder;
    }

    function action_export()
    {
        $this->startDump();
        $this->_resetLayout();
        if (!isset(XLite_Core_Request::getInstance()->mode) || XLite_Core_Request::getInstance()->mode != "cp") {
            print "<pre>\n";
            if ($this->getBuilder()->export($this->exportTemplates)) {
                print "\n\nA set of HTML pages generated successfully.<br>The pages are located in the 'var/html' subfolder of your LiteCommerce installation.\n";
            } else {
                print "\n\n<b><form color=red>There were errors in templates; please correct them and try again. Use the <a href='admin.php?target=template_editor&editor=advanced'>Template editor</a> to change templates.</font></b>\n";
            }
            print "</pre>";
        } else {
            ob_start();
            if ($this->getBuilder()->export($this->exportTemplates)) {
                ob_end_clean();
                print "OK";
            } else {
                $contents = ob_get_contents();
                ob_end_clean();
                print strip_tags($contents);
            }
            die();
        }
    }

    function action_import()
    {
        $this->startDump();
        $this->_resetLayout();
        if (!isset(XLite_Core_Request::getInstance()->mode) || XLite_Core_Request::getInstance()->mode != "cp") { 
            print "<pre>\n";
            if ($this->getBuilder()->import()) {
                print "\n\nA set of template files generated successfully.\n";
            } else {
                print "\n\nThere were errors in html files; please correct them and try again.\n";
            }
            print "</pre>";
        } else {
            ob_start();
            if ($this->getBuilder()->import()) {
                ob_end_clean();
                print "OK";
            } else {
                $contents = ob_get_contents();
                ob_end_clean();
                print strip_tags($contents);
            }
            die();
        }
    }

    function _resetLayout()
    {
        global $options;
        // reset Layout settings to customer default
        $layout = XLite_Model_Layout::getInstance(); //::getInstance();
        $layout->set("skin", $options["skin_details"]["skin"]);
        $layout->set("locale", $options["skin_details"]["locale"]);
    }

    function _showBackLink()
    {
        print('<a href="admin.php?target=wysiwyg">Click to return to admin interface</a>');
    }

	function getPageReturnUrl()
	{
		return array('<a href="admin.php?target=wysiwyg"><u>Click to return to admin interface</u></a>');
	}

    function isMemoryLimitChangeable()
    {
        return $this->getComplex('xlite.memoryLimitChangeable');
    }

    function getMemoryLimit()
    {
        return @ini_get("memory_limit");
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
