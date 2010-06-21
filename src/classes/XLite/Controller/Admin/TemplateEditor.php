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
class XLite_Controller_Admin_TemplateEditor extends XLite_Controller_Admin_Abstract
{
    protected $locale = null;

    protected $zone = null;
    
    public $params = array('target', 'editor', 'zone');
    public $editor = "basic";
    public $basicTemplatesRO = array();

    public $pages = array(
            'basic' => 'Basic templates',
            'mail' => 'Mail templates',
            'extra_pages' => 'User-defined pages',
            'advanced' => 'Advanced templates'
            );
    
    public $pageTemplates = array(
            "advanced" => "template_editor/advanced.tpl",
            "extra_pages" => "template_editor/extra_pages.tpl",
            "basic" => "template_editor/basic.tpl",
            "mail" => "template_editor/mail.tpl",
            );

    public $welcome_page = array(
            "3-columns_classic",
            "3-columns_modern"
            );

    public $default_templates = array(
            "welcome.tpl",
            "news.tpl",
            "phones.tpl",
            "privacy_statement.tpl",
            "terms_conditions.tpl",
            "checkout/success_message.tpl"
            );

    /**
     * getExtraPageContent 
     * 
     * @return string|null
     * @access public
     * @since  3.0.0
     */
    public function getExtraPageContent()
    {
        return $this->getExtraPage()->template ? $this->getExtraPage()->template->getContent() : $this->get('content');
    }

    function getLocale() 
    {
        if (is_null($this->locale)) {
            $this->locale = XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }
        return $this->locale;
    }

    function getZone() 
    {
        if (is_null($this->zone)) {
            $this->zone = XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        }
        return $this->zone;
    }
    
    function getTreePages() 
    {
        $zone = XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        if (is_null($this->treePages)) {
            $this->treePages = array(
                    $zone => "Customer zone",
                    "mail" => "E-mail templates",
                    "admin" => "Admin zone"
                    );
        }
        return $this->treePages;
    }

    function getPageTemplate()
    {
        return isset($this->pageTemplates[$this->get('editor')]) ? $this->pageTemplates[$this->get('editor')] : null;
    }
    
    function getUrl(array $params = array()) 
    {
        if ($this->get('editor') != "advanced") {
            $this->set('zone', null);
            $this->set('locale', null);
        }
        return parent::getUrl($params);
    }

    // BASIC templates editor methods {{{

    /**
    */
    function getBasicTemplates() 
    {
        $zone   = $this->get('zone');
        $locale = $this->get('locale');
        $templates = array();
        if (is_readable("skins/$zone/$locale/templates.ini")) {
            $templates_ini = parse_ini_file("skins/$zone/$locale/templates.ini", true);
            foreach ($templates_ini as $key=>$val) {
                $filename = trim($val['filename']);
                if (!empty($filename))
                    $templates[] = $filename;
            }
        } else {
            $templates = $this->default_templates;
        }

        $pages = array();
        foreach ($templates as $name) {
            $pages[] = new XLite_Model_FileNode("skins/$zone/$locale/".$name, null, SHOW_FULL_PATH);
        }

        if ($this->get('valid') == false) {
            foreach ($pages as $page_idx => $page) {
            	if (isset($this->basicTemplatesRO[$page->path]) && $this->basicTemplatesRO[$page->path]) {
            		$pages[$page_idx]->set('read_only_access', true);
            	}
            }
        }

        return $pages;
    }

    function getShortcuts() 
    {
        $shortcuts = array();
        $zone   = $this->get('zone');
        $locale = $this->get('locale');
        if ($zone == "mail") {
            $shortcuts = array( new XLite_Model_FileNode("skins/mail/$locale/order_processed/body.tpl", null, SHOW_FULL_PATH), 
                    );
        } elseif ($zone == "admin") {
        } else {
            $shortcuts = array(
                new XLite_Model_FileNode("skins/$zone/$locale/main.tpl", null, SHOW_FULL_PATH),
                new XLite_Model_FileNode("skins/$zone/$locale/category_products.tpl", null, SHOW_FULL_PATH),
                new XLite_Model_FileNode("skins/$zone/$locale/category_subcategories.tpl", null, SHOW_FULL_PATH),
                new XLite_Model_FileNode("skins/$zone/$locale/product_details.tpl", null, SHOW_FULL_PATH),
                new XLite_Model_FileNode("skins/$zone/$locale/checkout", "Checkout pages", SHOW_FULL_PATH),
                new XLite_Model_FileNode("skins/$zone/$locale/common/invoice.tpl", "Invoice form template", SHOW_FULL_PATH),
                new XLite_Model_FileNode("skins/$zone/$locale/common/print_invoice.tpl", "Printable Invoice form template", SHOW_FULL_PATH),
                new XLite_Model_FileNode('cart.html', null, SHOW_FULL_PATH),
                new XLite_Model_FileNode('shop_closed.html', "Shop is closed warning template", SHOW_FULL_PATH),
            );
        }
        return $shortcuts;
    }

    /**
    * Updates basic templates from the "Basic templates" tab
    */
    function action_update_templates() 
    {
        foreach (XLite_Core_Request::getInstance()->template as $path => $content) {
            $t = new XLite_Model_FileNode($path);
            $t->set('content', $content);
            $t->update();
        	if ($t->writePermitted) {
                $this->set('valid', false);
                $this->basicTemplatesRO[$path] = true;
        	}
        }
    }

    

    // MAIL templates editor methods {{{

    function getMailTemplates()
    {
        $node = XLite_Core_Request::getInstance()->node;
        $path = XLite_Core_Request::getInstance()->path;
        $data['subject'] = new XLite_Model_FileNode("$node/subject.tpl");
        $data['body'] = new XLite_Model_FileNode("$node/body.tpl");
        $data['signature'] = new XLite_Model_FileNode("$path/signature.tpl");
        return $data;
    }

    function action_update_mail()
    {
    	$writePermitted = false;
        $node = XLite_Core_Request::getInstance()->node;
        $path = XLite_Core_Request::getInstance()->path;
        $s = new XLite_Model_FileNode("$node/subject.tpl");
        $s->set('content', XLite_Core_Request::getInstance()->subject);
        $s->update();
        if ($s->writePermitted) {
            $writePermitted = true;
        	$this->set('subjectWriteError', true);
        }
        $b = new XLite_Model_FileNode("$node/body.tpl");
        $b->set('content', XLite_Core_Request::getInstance()->body);
        $b->update();
        if ($b->writePermitted) {
            $writePermitted = true;
        	$this->set('bodyWriteError', true);
        }
        $sig = new XLite_Model_FileNode("$path/signature.tpl");
        $sig->set('content', XLite_Core_Request::getInstance()->signature);
        $sig->update();
        if ($sig->writePermitted) {
            $writePermitted = true;
        	$this->set('signatureWriteError', true);
        }

        if ($writePermitted) {
            $this->set('mode', "edit");
            $this->set('valid', false);
        }
    }
    
    
    // USER-DEFINED pages editor methods {{{

    function getExtraPage() 
    {
        if (is_null($this->extraPage)) {
            $this->extraPage = new XLite_Model_ExtraPage();
            if (isset(XLite_Core_Request::getInstance()->page) && !empty(XLite_Core_Request::getInstance()->page)) {
                $this->extraPage = $this->extraPage->findPage(XLite_Core_Request::getInstance()->page);
            }
        }
        return $this->extraPage;
    }
    
    function getExtraPages() 
    {
        if (is_null($this->extraPages)) {
            $this->extraPages = $this->getComplex('extraPage.pages');
        }
        return $this->extraPages;
    }

    function action_reupdate_pages() 
    {
    	$this->getExtraPages();
    	if (is_array($this->extraPages)) {
            foreach ($this->extraPages as $ep) {
                $page = $ep->get('page');
                $title = $ep->get('title');
                $content = $ep->getComplex('template.content');

                XLite_Core_Request::getInstance()->page = $page;
                $this->extraPage = null;
                $this->getExtraPage();
                $this->action_page_remove();

                XLite_Core_Request::getInstance()->page = "";
                XLite_Core_Request::getInstance()->title = $title;
                XLite_Core_Request::getInstance()->content = $content;
                $this->extraPage = null;
                $this->getExtraPage();
                $this->action_update_page();
            }
    	}
    }

    function action_update_page() 
        {
        $page = trim(XLite_Core_Request::getInstance()->page);
        $this->extraPage = new XLite_Model_ExtraPage();
        $this->setComplex('extraPage.page', $page);
        $this->setComplex('extraPage.title', trim(XLite_Core_Request::getInstance()->title));
        $this->setComplex('extraPage.content', trim(XLite_Core_Request::getInstance()->content));
        if ($this->new_page) {
            $this->extraPage->add();
        } else {
            $this->extraPage->modify();
        }

        if ($this->extraPage->writePermitted) {
            $this->set('valid', false);
            $this->set('read_only_access', true);
        	if (!$page) {
                $this->extraPage->remove();
                $this->extraPage = null;
            } else {
                $this->set('mode', "page_edit");
            }
            $this->extraPages = null;
        }
    }

    function action_page_remove() 
    {
        $extraPage = $this->get('extraPage');
        if (!is_null($extraPage) && is_object($extraPage)) {
            $extraPage->remove();
        }
    }

    

    // ADVANCED templates editor methods 

    function getFile() 
    {
        $path = isset(XLite_Core_Request::getInstance()->file) ? XLite_Core_Request::getInstance()->file : null;
        $file = new XLite_Model_FileNode($path);
        if (isset(XLite_Core_Request::getInstance()->content)) {
            $file->set('content', XLite_Core_Request::getInstance()->content);
        }
        return $file;
    }

    function action_advanced_update() 
    {
        $file = $this->get('file');
        $file->update();
        $this->afterAdvanced();
        $this->set('returnUrl', $this->get('url') . "&mode=edit&file=" . $file->get('path'));
        if ($file->writePermitted) {
            $this->set('valid', false);
            $this->set('mode', "edit");
            $this->set('error', "writePermitted");
        }
    }
    
    function action_remove() 
    {
        $file = new XLite_Model_FileNode(XLite_Core_Request::getInstance()->selected_file);
        $file->remove();
        $this->afterAdvanced();
    }

    function action_copy() 
    {
        $file = new XLite_Model_FileNode(XLite_Core_Request::getInstance()->selected_file);
        $basename = dirname($file->path);
        $file->set('newPath', $basename . "/" . XLite_Core_Request::getInstance()->new_name);
        $file->copy();
        $this->afterAdvanced();
    }

    function action_rename() 
    {
        $file = new XLite_Model_FileNode(XLite_Core_Request::getInstance()->selected_file);
        $basename = dirname($file->path);
        $file->set('newPath', $basename . "/" . XLite_Core_Request::getInstance()->new_name);
        $file->rename();
        $this->afterAdvanced();
    }

    function action_new_file() 
    {
        $file = $this->get('file');
        $path   = $file->get('path');
        $zone   = $this->get('zone');
        $locale = $this->get('locale');
        if ($file->get('node') == ".") {
            $path = (is_null($this->node) || $this->node == "") ?
                                   "skins/$zone/$locale/$path" :
                                            "$this->node/$path";
            $file->set('path', $path);
        }
        $file->create();
        $this->set('returnUrl', $this->get('url') . "&mode=edit&file=" . $path);
    }

    function action_new_dir() 
    {
        $file = $this->get('file');
        $path   = $file->get('path');
        $zone   = $this->get('zone');
        $locale = $this->get('locale');
        if ($file->get('node') == ".") {
            $path = (is_null($this->node) || $this->node == "") ? 
                                   "skins/$zone/$locale/$path" :
                                            "$this->node/$path";
            $file->set('path', $path);
        }
        $file->createDir();
        $this->afterAdvanced();
    }

    function action_restore_all() 
    {
        $file = $this->get('file');
        $file->set('path', "skins_original");
        $file->set('newPath', "skins");
        $file->copy();
        $file->set('path', sprintf("schemas/templates/%s",$this->config->Skin->skin));
        $file->set('newPath', "skins");
        $file->copy();
        $this->afterAdvanced();
    }

    function action_restore() 
    {
        $file = $this->get('file');
        $to = XLite_Core_Request::getInstance()->selected_file;
        $schema_file = preg_replace("/^(skins)/", sprintf("schemas/templates/%s",$this->config->Skin->skin), $to);
        $from = (file_exists($schema_file) ? $schema_file : preg_replace("/^(skins)/", "skins_original", $to));
        copyRecursive($from, $to);
        $this->afterAdvanced();
    }

    function afterAdvanced() 
    {
        if (!is_null($this->node) && trim($this->node) != "") {
            $this->set('returnUrl', $this->get('url') . "&node=$this->node");
        }
    }

    
}
