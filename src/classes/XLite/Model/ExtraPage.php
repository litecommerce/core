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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ExtraPage extends \XLite\Base
{
    public $templatePrefix; // = "skins/$zone/en/";
    public $page; // a page identifier	
    public $title;
    public $content; // a page content	
    public $template; // the page's template	
    public $pageLine = "<widget template=\"common/dialog.tpl\" body=\"%s.tpl\" head=\"%s\" visible=\"{page=#%s#}\">";

    public $isRead = false;

    protected $zone = null;

    protected $locale = null;

    public function __construct()
    {
        $this->pagesTemplate = $this->get('templatePrefix') . "pages.tpl";
        $this->locationTemplate = $this->get('templatePrefix') . "location.tpl";
        //$this->menuTemplate = $this->get('templatePrefix') . "help/body.tpl";
        $this->menuTemplate = $this->get('templatePrefix') . "help/pages_links.tpl";
        $this->menuTemplateDef = $this->get('templatePrefix') . "help/pages_links_def.tpl";
        $this->linksTemplate = $this->get('templatePrefix') . "pages_links.tpl";
    }

    function getLocale() 
    {
        if (is_null($this->locale)) {
            $this->locale = \XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }
        return $this->locale;
    }

    function getZone()
    {
        if (is_null($this->zone)) {
            $this->zone = \XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        }
        return $this->zone;
    }

    function getTemplatePrefix()
    {
        if (is_null($this->templatePrefix)) {
            $zone   = $this->get('zone');
            $locale = $this->get('locale');
            $this->templatePrefix = "skins/$zone/$locale/";
        }
        return $this->templatePrefix;
    }

    /**
    * Returns an array of ExtraPage.
    */
    function getPages()
    {
        $pages = array();
        $pagesList = @file($this->pagesTemplate);
        if (is_array($pagesList)) {
            foreach (file($this->pagesTemplate) as $line) {
                //
                // Example:
                //   <widget template="common/dialog.tpl" body="test_page.tpl" head="Test page" visible="{page=#test_page#}">
                //
                // OLD: if (preg_match("/{([^.]*)\.display\(#([^#]*)#\)/", $line, $matches)) {
                if (preg_match("/<widget (\S+) body=\"(\w+)\.tpl\" head=\"([^\"]+)\" (\S+)/", $line, $matches)) {
                    
                    list($line, $template, $page, $title) = $matches;
                    $p = new \XLite\Model\ExtraPage();
                    $p->page = $page;
                    $p->title = func_htmldecode($title);
                    $fd = @fopen($this->get('templatePrefix') . $page . ".tpl", "rb");
                    if ($fd) {
                    	fclose($fd);
                    	$p->template = new \XLite\Model\Template($page . ".tpl");
                    }
                    $pages[] = $p;
                }
            }
        }
        return $pages;
    }

    /**
    * Find a page by ID
    */
    function findPage($id)
    {
        foreach ($this->getPages() as $page) {
            if ($page->page == $id) {
                return $page;
            }
        }
    }

    function getPageLinkAttributes()
    {
    	return array('page_link', "page_title");
    }

    function getPageLinkContent($tpl = null)
    {
        $title = htmlspecialchars($this->title);
        $title = preg_replace("/{/S", "&#123;", $title);
        $title = preg_replace("/}/S", "&#125;", $title);
        switch($tpl) {
            case "location":
                $line = "<span IF=\"target=#main#&page=#$this->page#\" class=\"NavigationPath\">&nbsp;::&nbsp;$title</span>";
                break;
            case "pages":
                $line = sprintf($this->pageLine, $this->page, $title, $this->page);
                break;
            case "menu":
                $this->getCustomerLayout();
                $template = new \XLite\Base();
                $template->set('templateFile', $this->menuTemplateDef);
                $template->set('template', $this->getRelativeTemplatePath($this->menuTemplateDef));
                $template->set('skinPath', $this->customerLayout->getPath());
                $template->set('page_link', "cart.php?page=" . $this->page);
                $template->set('page_title', $this->title);
                $line = $this->compile($template);
                $line = preg_replace("/{/S", "&#123;", $line);
                $line = preg_replace("/}/S", "&#125;", $line);
                break;
            case "links":
                $line = "| <a href=\"cart.php?page=$this->page\"><font class=\"BottomMenu\">$title</font></a>";
                break;
            default:
                $line = "";
                break;
        }
        return $line;
    }

    function getPageLinkPattern($tpl = null)
    {
        switch($tpl) {
            case "location":
                $line = "/IF=\"target=#main#&page=#$this->page#\"/";
                break;
            case "pages":
                $line = "/body=\"$this->page\.tpl\"/";
                break;
            case "menu":
                $line = "/cart\\.php\\?page=$this->page\"/";
                break;
            case "links":
                $line = "/cart\\.php\\?page=$this->page\"/";
                break;
            default:
                $line = "";
                break;
        }
        return $line;
    }

    /**
    * Add the extra page.
    */
    function add()
    {
        $this->page = $this->_createUniqueName($this->title, trim($this->page));
        $this->addLine($this->getPageLinkContent('location'), $this->locationTemplate);
        $this->addLine($this->getPageLinkContent('pages'), $this->pagesTemplate);
        $this->addLine($this->getPageLinkContent('menu'), $this->menuTemplate);
        $this->addLine($this->getPageLinkContent('links'), $this->linksTemplate);
        $this->createFile($this->get('templatePrefix') . $this->page . ".tpl", $this->content);
    }

    /**
    * Modify the extra page.
    */
    function modify()
    {
        $this->replaceLine($this->getPageLinkPattern('location'), $this->getPageLinkContent('location'), $this->locationTemplate);
        $this->replaceLine($this->getPageLinkPattern('pages'), $this->getPageLinkContent('pages'), $this->pagesTemplate);
        $this->replaceLine($this->getPageLinkPattern('menu'), $this->getPageLinkContent('menu'), $this->menuTemplate);
        $this->replaceLine($this->getPageLinkPattern('links'), $this->getPageLinkContent('links'), $this->linksTemplate);
        $this->createFile($this->get('templatePrefix') . $this->page . ".tpl", $this->content);
    }

    function remove()
    {
        $this->replaceLine($this->getPageLinkPattern('location'), "", $this->locationTemplate);
        $this->replaceLine($this->getPageLinkPattern('pages'), "", $this->pagesTemplate);
        $this->replaceLine($this->getPageLinkPattern('menu'), "", $this->menuTemplate);
        $this->replaceLine($this->getPageLinkPattern('links'), "", $this->linksTemplate);
        @unlink($this->get('templatePrefix') . $this->page . ".tpl");
    }

    function addLine($line, $file)
    {
        $c = @file_get_contents($file);
        if (substr($c, -1) != "\n") {
            $line = "\n$line";
        }
        $fd = @fopen($file, "ab");
        if ($fd) {
            fwrite($fd, $line . "\n");
            fclose($fd);
            @chmod($file, get_filesystem_permissions(0666));
        }
    }

    function replaceLine($pattern, $line, $file)
    {
        $lines = @file($file);
        if (is_array($lines)) {
            // find a line 
            for ($i=0; $i<count($lines); $i++) {
                if (preg_match($pattern, $lines[$i])) {
                    // replace and stop looking
                    $lines[$i] = $line . "\n";
                    if ($line === '') {
                    	if (isset($lines[$i])) {
                        	unset($lines[$i]); // remove the line
                        }
                    }
                    break;
                }
            }
            $lines = join('', $lines);
        }
        // write the file
        $this->createFile($file, $lines);
    }

    function createFile($file, $content = '')
    {
        $this->writePermitted = false;
        $fd = @fopen($file, "wb");
        if ($fd) {
        	fwrite($fd, str_replace("\r", '', $content));
        	fclose($fd);
        	@chmod($file, get_filesystem_permissions(0666));
        } else {
        	$this->writePermitted = true;
        }
    }
    
    function _createUniqueName($title, $pname = "")
    {
        if ($pname == "") {
            $name = strtolower($title);
            $name = preg_replace("/[^a-zA-Z0-9_]/", "_", $name);
            $name = preg_replace("/^([0-9])/", "_\\1", $name);
        } else {
            $name = $pname;
        }
        $pages = $this->getPages();
        // ensure the page is unique
        $suff = '';
        do {
            $found = false;
            foreach ($pages as $page) {
                if ($page->page == $name.$suff) {
                    $found = true;
                }
            }
            if ($found) {
                $suff = $suff + 1;
            }
        } while ($found);
        return $name.$suff;
    }

    function getCustomerLayout()
    {
        if (!is_null($this->customerLayout)) {
            return $this->customerLayout;
        }

        $this->customerLayout = \XLite\Model\Layout::getInstance();

        // FIXME - to delete
        /*$this->xlite->set('adminZone', false);
        $this->customerLayout->initFromGlobals();
        $this->xlite->set('adminZone', true);*/

        return $this->customerLayout;
    }

    // FIXME
    function compile($template)
    {
        // replace layout with customer layout
     	/*$layout = \XLite\Model\Layout::getInstance();
        $skin = $layout->get('skin');
        $layout->set('skin', $this->customerLayout->get('skin'));*/

        $component = new \XLite\View\ExtraPage(
            array(
                \XLite\View\ExtraPage::PARAM_TEMPLATE => $template->get('templateFile'),
                \XLite\View\ExtraPage::PARAM_DATA     => $template,
            )
        );
        $component->init();

        $attributes = $this->getPageLinkAttributes();
        foreach ($attributes as $attr) {
            $component->set($attr, $template->get($attr));
        }

        $text = $component->getContent();

        // restore old skin
        // $layout->set('skin', $skin);
            
        return $text;
    }

    function getRelativeTemplatePath($file)
    {
        $skin_details = \XLite::getInstance()->getOptions('skin_details');

        return str_replace('skins/' . $skin_details['skin'] . '/' . $skin_details['locale'] . '/', '', $file);
    }
}
