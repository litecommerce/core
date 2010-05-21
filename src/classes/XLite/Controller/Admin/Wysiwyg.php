<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Wysiwyg extends XLite_Controller_Admin_Abstract
{
    public $builder = null;
    public $exportTemplates = array('main.tpl', "common/print_invoice.tpl");
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
        $layout->set('skin', $options['skin_details']["skin"]);
        $layout->set('locale', $options['skin_details']["locale"]);
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
        return @ini_get('memory_limit');
    }
}
