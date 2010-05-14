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

define('EDIT_START_IMG', 'edit_area.gif');
define('EDIT_START_SHORT_IMG', 'edit_area_s.gif');
define('EDIT_END_IMG', 'end.gif');
define('EDIT_END_SHORT_IMG', 'end_s.gif');
define('EDIT_PARAM_START_IMG', 'edit_area_s.gif');
define('EDIT_PARAM_END_IMG', 'end_s.gif');
define('WIDGET_START_IMG', 'widget.gif');
define('WIDGET_END_IMG', 'widget_end.gif');
define('WIDGET_START_SHORT_IMG', 'widget_s.gif');
define('WIDGET_END_SHORT_IMG', 'widget_s_end.gif');
define('DUMMY_WIDGET_IMG', 'dummy_widget.gif');
define('HTML_BUILDER_PATH', 'var/html/');
define('HTML_BUILDER_IMAGES_DIR', 'widgets');
$GLOBALS['TEMPLATE_EDITABLE_PARAMS'] = array(
    'common/dialog.tpl'      => array('head'),
    'common/sidebar_box.tpl' => array('head')
);
$GLOBALS['TEMPLATE_REFERENCE_PARAMS'] = array(
    'common/dialog.tpl'      => array('body'),
    'common/sidebar_box.tpl' => array('dir')
);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Wysiwyg_Mediator extends XLite_Base
{
    public $widgetClass = "WysiwygMediatorWidget";
    public $templateClass = "Template";
    public $imagesDir = HTML_BUILDER_IMAGES_DIR;
    public $templateEditableParams  = null;
    public $templateReferenceParams = null;
    public $hrefPrefix = '';
    public $pageTarget = null;
    public $pageMode = null;
    public $dummy_images = array();
    //var $showMemoryUsage = true;		// uncomment this line to show memory usage	
    public $__memoryUsageMax = 0;
    public $__memoryUsageMin = 0;
    public $__buildFullTreeCounter = 0;
    public $__buildFullTreeCounterMax = 0;

    protected $htmlStorage = null;

    public function __construct()
    {
        $this->__memoryUsageMin = 1024.0 * 1024.0 * 1024.0 * 1024.0;
        $this->templateEditableParams = $GLOBALS['TEMPLATE_EDITABLE_PARAMS'];
        $this->templateReferenceParams = $GLOBALS['TEMPLATE_REFERENCE_PARAMS'];
    }

    function _showMemoryUsage()
    {
        $memoryUsage = memory_get_usage() / 1024 / 1024;

        if ($memoryUsage > $this->__memoryUsageMax) {
            $this->__memoryUsageMax = $memoryUsage;
        }
        if ($memoryUsage < $this->__memoryUsageMin) {
            $this->__memoryUsageMin = $memoryUsage;
        }
     	printf(" (MEMORY - min: %.2f Mb, current: %.2f Mb, max: %.2f Mb) (CALL DEPTH - current: %d, max: %d) ", $this->__memoryUsageMin, $memoryUsage, $this->__memoryUsageMax, $this->__buildFullTreeCounter, $this->__buildFullTreeCounterMax);
    }

    function buildFullTree(&$widget)
    {
        $this->increase_memory_limit();

        if ($this->showMemoryUsage) {
    		$this->__buildFullTreeCounter++;
    		if ($this->__buildFullTreeCounter > $this->__buildFullTreeCounterMax) {
    			$this->__buildFullTreeCounterMax = $this->__buildFullTreeCounter;
    		}
    	}

        $exportParser = new XLite_Model_Wysiwyg_ExportParser();
        $exportParser->widgetClass = $this->widgetClass;
        $exportParser->wysiwygMediator = $this;
        if (strpos($widget->get('templateFile'), '}')) {
        	if ($this->showMemoryUsage) {
                $this->__buildFullTreeCounter--;
            }
            return;
        }
        if ($widget->get('template') && file_exists($widget->get('templateFile'))) {
            print "Processing template " . $widget->get('templateFile') . "... ";
            $exportParser->_parseTemplate($widget->get('templateFile'), $widget->get('attributesEvaled'));
            if ($exportParser->errorMessage) {
                print "<font color=red>[FAILURE: $exportParser->errorMessage]</font>";
                if ($this->showMemoryUsage && function_exists('memory_get_usage')) {
                	$this->_showMemoryUsage();
                }
                print "\n";
                $this->error = $exportParser->errorMessage;
                $this->errors++;
            } else {
                print "<font color=green>[OK]</font>";
                if ($this->showMemoryUsage && function_exists('memory_get_usage')) {
                	$this->_showMemoryUsage();
                }
                print "\n";
                $widget->widgets = array();
                for ($i=0; $i<count($exportParser->widgets); $i++) {
                    if (isset($exportParser->widgets[$i]->attributes['module'])) {
                        $module =$exportParser->widgets[$i]->attributes['module'];
                        if (is_null($this->xlite->get("mm.activeModules.$module"))) {
                            continue;
                        }
                    }
                    $widget->widgets[] = $exportParser->widgets[$i];
                    $this->buildFullTree($exportParser->widgets[$i]);
                }
            }
        }

        if ($this->showMemoryUsage) {
            $this->__buildFullTreeCounter--;
        }
    }
   
    function _replaceVal($src, $params, $openF = '', $closeF = '')
    {
        if (!is_array($params)) {
            func_die("Wrong params type:" . gettype($params));
        }
        foreach ($params as $name => $value) {
            foreach (array('', ':r', ':h') as $modifier) {
                if ($openF) {
                    $open = $this->$openF($name,$modifier);
                } else {
                    $open = '';
                }
                if ($closeF) {
                    $close = $this->$closeF($name,$modifier);
                } else {
                    $close = '';
                }
                switch ($modifier) {
                case '': $replaceWith = $open . htmlspecialchars($value) . $close; break;
                case ':r': $replaceWith = $open . str_replace('\"', '&quot;', $value) . $close; break;
                case ':h': $replaceWith = $open . $value . $close; break;
                }
                $src = str_replace('{'.$name.$modifier.'}', $replaceWith, $src);
                $src = str_replace('{widget.'.$name.$modifier.'}', $replaceWith, $src);
            }
        }
        return $src;
    }

    function _generateWidget(&$w)
    {
        $target = $this->getWidgetTarget($w);
        if (!is_null($target) && isset($this->pageTarget)) {
            if (!in_array($this->pageTarget, explode(',', $target)) && $this->pageTarget != $target) {
                return;
            }
        }
        $mode = $this->getWidgetMode($w);
        if (!is_null($mode) && isset($this->pageMode)) {
            if (!in_array($this->pageMode, explode(',', $mode)) && $this->pageMode != $mode) {
                return;
            }
        }
        if ($w->parent) {
            $result = $this->_generateParentWidget($w);
        } else if ($w->editing) {
            $result = $this->_generateEditingWidget($w);
        } else {
            $result = $this->_generateWidgetReference($w);
        }
        return $result;
    }

    function getWidgetTarget(&$w)
    {
        $target = null;
        do {
            if (isset($w->attributes['target'])) {
                $target = $w->attributes['target'];
            }
            if (isset($this->root) && $this->root->get('template') == $w->get('template')) {
                break;
            }
            $w = $w->parentWidget;
        } while (!is_null($w));
        return $target;
    }

    function getWidgetMode(&$w)
    {
        $mode = null;
        do {
            if (isset($w->attributes['mode'])) {
                $mode = $w->attributes['mode'];
            }
            if (isset($this->root) && $this->root->get('template') == $w->get('template')){
                break;
            }
            $w = $w->parentWidget;
        } while (!is_null($w));
        return $mode;
    }

    function _generateParentWidget(&$w, $editing = false)
    {
//        print "_generateParentWidget(".$w->get('template') . ")\n";
        // replace all <widget> with _generateWidget()
        if (!file_exists($w->get('templateFile'))) {
            return '';
        }
        $fc = new XLite_Model_Wysiwyg_ExportParser();
        $fc->source = $fc->translateTemplate(file_get_contents($w->get('templateFile')));
        for ($i=0; $i<count($w->widgets); $i++) {
            $ww = $w->widgets[$i];
            $widgetHtmlCode = $this->_generateWidget($ww);
            // strip end of line if the widget is invisible on this page
            $endOffset = $ww->get('endOffset');
            if ($widgetHtmlCode == "" && substr($fc->source,$endOffset,1) == "\n") {
                $endOffset ++; // eat eol
            }
            $fc->subst($ww->get('startOffset'), $endOffset, $widgetHtmlCode);
        }
        $src = $fc->substitute();
        if ($editing) {
            $src = $this->_replaceVal($src, $this->_getEditableParams($w), '_generateEditParamStart', '_generateEditParamEnd');
        } else {
            $src = $this->_replaceVal($src, $this->_getEditableParams($w));
        }
        return $src;
    }

    function _generateEditingWidget(&$w)
    {
//        print "_generateEditingWidget(".$w->get('template') . $w->get('templateType') . ")\n";
        // replace all <widget> with special code
        if (!file_exists($w->get('templateFile'))) {
            return '';
        }
        $fc = new XLite_Model_Wysiwyg_ExportParser();
        $fc->source = $fc->translateTemplate(file_get_contents($w->get('templateFile')));
        for ($i=0; $i<count($w->widgets); $i++) {
            $ww = $w->widgets[$i];
            $fc->subst($ww->get('startOffset'), $ww->get('endOffset'), $this->_generateWidgetReference($ww, true));
        }
        $text = $fc->substitute();
        switch ($w->get('templateType')) {
        case "plain":
        case "paragraph":
            return '<table border=0 width=100%><tr><td height=19 background="' . $this->get('imagesDir') . '/' . EDIT_START_IMG . '" template="' . $w->get('template'). '"></td></tr><tr><td>' .
            $text .  
            '</td></tr><tr><td height=9 background="' . $this->get('imagesDir') . '/' . EDIT_END_IMG . '"></td></tr></table>';
        case "in-table":
            return '<tr><td colspan=10 height=19 background="' . $this->get('imagesDir') . '/' . EDIT_START_IMG . '" template="' . $w->get('template'). '"></td></tr><tr><td>' .
            $text .  
            '</td></tr><tr><td colspan=10 height=9 background="' . $this->get('imagesDir') . '/' . EDIT_END_IMG . '"></td></tr>';
        }
    }
    
    function _generateWidgetReference(&$w, $editing = false)
    {
//        print "_generateWidgetReference(".$w->get('template') . ")\n";
        if (!$w->hasDefinedTemplate() && $editing) {
            // show abstract widget image
            return '<img align=absmiddle border=0 src="' . $this->get('imagesDir') . '/' . DUMMY_WIDGET_IMG . '"' . $w->get('attributesInTag') . '>';
        }
        $t = $w->get('template');
        if (isset($this->templateEditableParams[$t])) {
            $text = $this->_generateParentWidget($w, $editing);
            if ($editing) {
                switch ($w->get('templateType')) {
                case "plain":
/*                    return '<img align=absmiddle border=0 src="' . $this->get('imagesDir') . '/' . WIDGET_START_SHORT_IMG . '"' . $w->get('attributesInTag') . '>' . $text . '<img align=absmiddle border=0 src="' . $this->get('imagesDir') . '/' . WIDGET_END_SHORT_IMG . '">';*/
                case "paragraph":
                    return '<table border=0 width=100%><tr><td height=19 background="' . $this->get('imagesDir') . '/' . WIDGET_START_IMG . '"' . $w->get('attributesInTag') . '></td></tr><tr><td>' .
                    $text . 
                    '</td></tr><tr><td height=9 background="' . $this->get('imagesDir') . '/' . WIDGET_END_IMG . '"></td></tr></table>';
                case "in-table":
                    return '<tr><td colspan=10 height=19 background="' . $this->get('imagesDir') . '/' . WIDGET_START_IMG . '"' . $w->get('attributesInTag') . '></td></tr><tr><td>' .
                    $text .  
                    '</td></tr><tr><td colspan=10 height=9 background="' . $this->get('imagesDir') . '/' . WIDGET_END_IMG . '"></td></tr>';
                }
            } else {
                return $text;
            }
        } else {
        	$imageFileName = $this->getImageFileName($w);
            $image = $this->get('imagesDir') . "/". $imageFileName;

            $postfix = "";
            if ( !is_readable(HTML_BUILDER_PATH . "widgets/" . $imageFileName) ) {
                $image = $this->get('imagesDir') . "/dummy_widget.gif";
                $path_parts = pathinfo($imageFileName);
                if (basename($path_parts['basename'], $path_parts['extension']) != ".") {
                    $this->dummy_images[] = HTML_BUILDER_PATH . "widgets/" . $imageFileName;
                    $postfix = "<!--Should be: " . $this->get('imagesDir') . "/". $imageFileName . "-->";
                }
            }
            return $this->_generateWidgetLink($w, '<a href="' . $this->getWidgetLink($w) . '"><img align=absmiddle border=0 src="' . $image . '"' . $w->get('attributesInTag') . '></a>' . $postfix);
        }
    }
    
    function _generateWidgetLink($w, $img)
    {
        switch ($w->get('templateType')) {
        case "plain":
            return $img;
        case "paragraph":
            return "<div>$img</div>";
        case "in-table":
            return "<tr><td>$img</td></tr>";
        }
    }
    
    function _getEditableParams($w)
    {
        $t = $w->get('template');
        $params = array();
        if (isset($this->templateEditableParams[$t])){
        foreach ($this->templateEditableParams[$t] as $name) {
            if (isset($w->attributes[$name])) {
                $params[$name] = $w->attributes[$name];
            }
        }
        }
        return $params;
    }
    function _generateEditParamStart($name, $modifier)
    {
        return '<img align=absmiddle border=0 src="' . $this->get('imagesDir') . '/' . EDIT_PARAM_START_IMG . '" name="' . $name . '" modifier="' . $modifier . '">';
    }
    function _generateEditParamEnd($name, $modifier)
    {
        return '<img align=absmiddle border=0 src="'.$this->get('imagesDir') . '/' . EDIT_PARAM_END_IMG . '">';
    }

    function getWidgetFile($w)
    {
        $template = $w->get('template');
        if (substr($template, -4) == ".tpl") {
            $template = substr($template, 0, strlen($template)-4);
        }

        if (isset($this->templateReferenceParams[$w->get('template')])) {
            foreach ($this->templateReferenceParams[$w->get('template')] as $name) {
                $template .= '-'.strtr($w->attributesEvaled[$name], " /\\.", "____");
            }
        }
        $template = strtr($template, " /\\", "___");
        return $template;
    }

    function getImageFileName($w)
    {
        return $this->getWidgetFile($w) . '.gif';
    }

    function getWidgetLink($w)
    {
        return $this->getWidgetFile($w) . '.html';
    }

    function _linkTree(&$parent, &$tree)
    {
        $t = $tree->get('template');
        if (!isset($this->widgetMap[$t])) {
            $this->widgetMap[$t] = $tree;
        }
        if ($this->hasParams($tree)) {
            $p = $parent->get('template');
            $pp = $parent;
        } else {
            $p = $t;
            $pp = $tree;
        }
        for ($i=0; $i<count($tree->widgets); $i++) {
            $tt = $tree->widgets[$i]->get('template');
            if (!isset($this->linkedTemplates[$p])) {
                if (!isset($this->templateMap[$tt])) {
                    $this->templateMap[$tt] = array();
                }
                if (isset($this->templateMap[$tt][$p])) {
                    $this->templateMap[$tt][$p] ++;
                } else {
                    $this->templateMap[$tt][$p] = 1;
                }
            }
            $tree->widgets[$i]->parentWidget = $tree;
            $this->_linkTree($pp, $tree->widgets[$i]);
        }
        $this->linkedTemplates[$t] = true;
    }
    
    function export($templates)
    {
        unlinkRecursive(HTML_BUILDER_PATH);
        // put cart.php as a redirect
        mkdirRecursive(HTML_BUILDER_PATH);
        if (($fd = @fopen(HTML_BUILDER_PATH . "/cart.php", "wb"))) {
            $url = $this->xlite->getShopUrl('cart.php');
            @fwrite($fd, <<<EOT
<?php
header("Location: $url?" . \$_SERVER['QUERY_STRING']);
?>
EOT
            );
        }
        @fclose($fd);
        $layout = XLite_Model_Layout::getInstance();
        $templatesDir = $layout->getPath();
        copyFile($templatesDir . 'style.css', HTML_BUILDER_PATH . 'style.css');
        copyRecursive($templatesDir . 'images', HTML_BUILDER_PATH . 'images');
        copyRecursive('skins/admin/images', HTML_BUILDER_PATH . 'widgets');
        $this->templateMap = array();
        $this->widgetMap = array();
        $this->linkedTemplates = array();
        $this->exportedPages = array();
        $this->errors = 0;
        foreach ($templates as $template) {
            $tree = new $this->widgetClass;
            $tree->set('template', $template);
            $this->buildFullTree($tree);
            if ($this->errors) {
                return false;
            }
            $xxx = null;
            $this->_linkTree($xxx, $tree);
            $this->_export($tree);
        }

/*
        if ( is_array($this->dummy_images) && count($this->dummy_images) > 0 ) {
            print("\n<font color='red'>Warning: Can't find the following image" . ((count($this->dummy_images) > 1 ) ? "s" : "") .":</font>\n");
            $this->dummy_images = array_unique($this->dummy_images);
            $this->dummy_images = array_values($this->dummy_images);
            $line = 1;
            foreach ($this->dummy_images as $v) {
                print($line . ") "  . $v."\n");
                $line++;
            }
        }
//*/

        return true;
    }

    function hasParams(&$w)
    {
        if (is_scalar($w)) {
            $template = $w;
        } else {
            $template = $w->get('template');
        }
        return isset($this->templateEditableParams[$template] )|| isset($this->templateReferenceParams[$template]);
    }

    function _export(&$tree)
    {
        $t = $tree->get('template');
        if (!$t) {
            return;
        }
        while (isset($this->templateMap[$t]) && count($this->templateMap[$t]) == 1) {
            foreach ($this->templateMap[$t] as $parent => $qt) {}
            if ($qt>1) {
                break;
            }
            $t = $parent;
        }
        $this->root = $this->widgetMap[$t];
        $this->pageTarget = $this->getWidgetTarget($tree);
        $this->pageMode = $this->getWidgetMode($tree);
        $tree->editing = true;
        $this->_exportPage($this->root, $tree, $this->getTemplateLink($tree->get('template')));
        $tree->editing = false;
        $tree->parent = true;
        // create pages for subwidgets
        for ($i=0; $i<count($tree->widgets); $i++) {
            $this->_export($tree->widgets[$i]);
        }
        $tree->parent = false;
    }
    function getTemplateLink($t)
    {
        return strtr(str_replace('.tpl', ".html", $t), " /\\", "___");
    }
    function _exportPage(&$root, &$page, $file)
    {
        if (isset($this->exportedPages[$file])) {
            return ;
        }
        if (strpos($file, '{') !== false) {
            return ;
        }
        print "Exporting $file\n";
        $this->exportedPages[$file] = true;
        $navigation = $this->_generateNavigation($page);

        $widget = $this->_generateWidget($root);
        if (!stristr($widget, '<html>')) {
            $source = <<<EOT
<html><head><link href="style.css" rel="stylesheet" type="text/css"></head><body>$navigation<hr width=100%>$widget</body></html>
EOT;
        } else {
            $source = <<<EOT
$navigation<hr width=100%>$widget
EOT;
        }
        $this->getHtmlStorage()->save($file, $source);
    }
    
    function _getComment(&$root, $link = false)
    {
        $node = new XLite_Model_FileNode($root->get('templateFile'));
        $comment = $node->get('comment');
        if (!$comment) {
            $comment = $root->get('template');
        }
        return $comment;
    }

    function _generateNavigation(&$page)
    {
        $navigation = '<b>' . $this->_getComment($page) . "</b>";
        if (!isset($this->templateMap[$page->get('template')])) {
            return $navigation;
        }
        $navigation .= "&nbsp;|&nbsp;";
        $parents = $this->templateMap[$page->get('template')];
        if (count($parents)>1) {
            $navigation .= 'Used from';
            // enumerate all widgets the given template is called from
            foreach ($parents as $parent => $qt) {
                $navigation .= ' <a href="' . $this->getTemplateLink($parent) . '">' . $parent . '</a>';
            }
            return $navigation;
        }
        $path = '';
        $t = $page->get('template');
        while (isset($this->templateMap[$t]) && count($this->templateMap[$t])==1) {
            foreach ($this->templateMap[$t] as $t => $qt) {}
            if ($path!=='') {
                $path = ' :: ' . $path;
            }
            $path = '<a href="' . $this->getTemplateLink($t) . '">' . $t . '</a>' . $path;
        }
        return $navigation . $path;
    }
    
    function getHtmlStorage()
    {
        if (is_null($this->htmlStorage)) {
            $this->htmlStorage = new XLite_Model_Wysiwyg_MediatorHtmlStorage();
        }
        return $this->htmlStorage;
    }

    function importPage($page)
    {
        $wysiwygImporter = new XLite_Model_Wysiwyg_ImportParser();
        $wysiwygImporter->set('source', $page);
        $wysiwygImporter->set('imagesDir', $this->get('imagesDir'));
        $wysiwygImporter->parse();
        $this->templateName = $wysiwygImporter->templateName;
        if (isset($this->layout->list[$this->templateName])) {
        	$this->templateName = $this->layout->list[$this->templateName];
        }
        $this->error = $wysiwygImporter->error;
        $this->template = $wysiwygImporter->template;
        return !$wysiwygImporter->error;
    }

    function import()
    {
        $this->errors = 0;
        $this->layout = $layout = XLite_Model_Layout::getInstance();
        $templatesDir = $layout->getPath();

        $code = copyRecursive(HTML_BUILDER_PATH . 'images', $templatesDir . 'images');
        if ($code == 1 || !is_readable(HTML_BUILDER_PATH . 'images')){
            print "<font color=red>WARNING: Directory " . HTML_BUILDER_PATH . 'images'  . " does not exist or is not readable.</font><br />";
        }

        copyFile(HTML_BUILDER_PATH . 'style.css', $templatesDir . 'style.css');
        $fileList = $this->getComplex('htmlStorage.fileList');
        if (!is_array($fileList) || count($fileList) == 0) {
            $this->errors++;
            print "<font color=red>WARNING: Nothing to import or directory " . HTML_BUILDER_PATH . 'images'  . " does not exist or is not readable! </font>";
        }
        foreach ($fileList as $name) {
            $this->increase_memory_limit();

            $source = $this->getHtmlStorage()->read($name);

            // save template
            print "Importing from $name ";
            if ($this->importPage($source)) {
                $templateName = $this->templateName;
                print "to $templateName...";
                $template = new $this->templateClass;
                $template->set('path', $templateName);
                if (!file_exists($template->get('file'))) {
                    print " <font color=red>[FAILURE: no such file " . $template->get('file') . "]</font>";
                    $this->errors++;
                } else if (!is_writable($template->get('file'))) {
                    print " <font color=red>[FAILURE: file " .$template->get('file') . " is not writable]</font>";
                    $this->errors++;
                } else {
                    $templateSource = $this->template;
                    $template->set('content', $templateSource);
                    print " <font color=green>[OK]</font>";
                    $template->save();
                }
            } else {
                print " <font color=red>[FAILURE: " . $this->error . "]</font>";
            }

            if ($this->showMemoryUsage && function_exists('memory_get_usage')) {
                $this->_showMemoryUsage();
            }
            print "\n";
        }
        return !$this->errors;
    }

    function increase_memory_limit($amount = 8)
    {
        //increase memory limit dynamically
        if (!$this->getComplex('xlite.memoryLimitChangeable'))
            return;

        $amount = (int) $amount;
        $current_limit = @ini_get('memory_limit');
        $current_limit_byte = func_convert_to_byte($current_limit);
        if (function_exists('memory_get_usage') && ($current_limit_byte - memory_get_usage() < $amount * 1024 * 1024)) {
            @ini_set('memory_limit', $current_limit_byte + (int) $amount * 1024 * 1024);
        }
    }
}
