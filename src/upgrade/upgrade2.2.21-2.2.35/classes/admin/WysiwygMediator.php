<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

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
    'common/dialog.tpl' => array('head'),
    'common/sidebar_box.tpl' => array('head'));
$GLOBALS['TEMPLATE_REFERENCE_PARAMS'] = array(
    'common/dialog.tpl' => array('body'),
    'common/sidebar_box.tpl' => array('dir'));

/**
* This class serves as a mediator between the LiteCommerce and 
* a WYSIWYG HTML editor. It performs the following two tasks:
* 1. Prepares a set of html pages with so-called edit areas inside.
* 2. Parses the changed HTML files and fins these edit areas, compiles
* templates from the edit areas.
*
* @package $Package$
* @version $Id: WysiwygMediator.php,v 1.3 2007/05/21 11:53:25 osipov Exp $
*/
class WysiwygMediator extends Object
{
    var $widgetClass = "WysiwygMediatorWidget";
    var $templateClass = "Template";
    var $imagesDir = HTML_BUILDER_IMAGES_DIR;
    var $templateEditableParams  = null;
    var $templateReferenceParams = null;
    var $hrefPrefix = '';
    var $pageTarget = null;
    var $pageMode = null;
	var $dummy_images = array();
	//var $showMemoryUsage = true;		// uncomment this line to show memory usage
	var $__memoryUsageMax = 0;
	var $__memoryUsageMin = 0;
	var $__buildFullTreeCounter = 0;
	var $__buildFullTreeCounterMax = 0;

    function constructor()
    {
		$this->__memoryUsageMin = 1024.0 * 1024.0 * 1024.0 * 1024.0;
        parent::constructor();
        $this->templateEditableParams = $GLOBALS['TEMPLATE_EDITABLE_PARAMS'];
        $this->templateReferenceParams = $GLOBALS['TEMPLATE_REFERENCE_PARAMS'];
    }

    function _showMemoryUsage()
    {
		if ($GLOBALS['memory_usage'] > $this->__memoryUsageMax) {
			$this->__memoryUsageMax = $GLOBALS['memory_usage'];
		}
		if ($GLOBALS['memory_usage'] < $this->__memoryUsageMin) {
			$this->__memoryUsageMin = $GLOBALS['memory_usage'];
		}
     	printf(" (MEMORY - min: %.2f Mb, current: %.2f Mb, max: %.2f Mb) (CALL DEPTH - current: %d, max: %d) ", $this->__memoryUsageMin, $GLOBALS['memory_usage'], $this->__memoryUsageMax, $this->__buildFullTreeCounter, $this->__buildFullTreeCounterMax);
    }

    function buildFullTree(&$widget)
    {
        if ($this->showMemoryUsage) {
    		$this->__buildFullTreeCounter++;
    		if ($this->__buildFullTreeCounter > $this->__buildFullTreeCounterMax) {
    			$this->__buildFullTreeCounterMax = $this->__buildFullTreeCounter;
    		}
    	}

        $exportParser = func_new("WysiwygExportParser");
        $exportParser->widgetClass = $this->widgetClass;
        $exportParser->wysiwygMediator =& $this;
        if (strpos($widget->get("templateFile"), '}')) {
        	if ($this->showMemoryUsage) {
				$this->__buildFullTreeCounter--;
			}
            return;
        }
        if ($widget->get("template") && file_exists($widget->get("templateFile"))) {
            print "Processing template " . $widget->get("templateFile") . "... ";
            $exportParser->_parseTemplate($widget->get("templateFile"), $widget->get("attributesEvaled"));
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
                    if (isset($exportParser->widgets[$i]->attributes["module"])) {
                        $module =$exportParser->widgets[$i]->attributes["module"];
                        if (is_null($this->xlite->get("mm.activeModules.$module"))) {
                            continue;
                        }
                    }
                    $widget->widgets[] =& $exportParser->widgets[$i];
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
            if (isset($this->root) && $this->root->get("template") == $w->get("template")) {
                break;
            }
            $w =& $w->parentWidget;
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
            if (isset($this->root) && $this->root->get("template") == $w->get("template")){
                break;
            }
            $w =& $w->parentWidget;
        } while (!is_null($w));
        return $mode;
    }

    function _generateParentWidget(&$w, $editing = false)
    {
//        print "_generateParentWidget(".$w->get("template") . ")\n";
        // replace all <widget> with _generateWidget()
        if (!file_exists($w->get("templateFile"))) {
            return '';
        }
        $fc = func_new("WysiwygExportParser");
        $fc->source = $fc->translateTemplate(file_get_contents($w->get("templateFile")));
        for ($i=0; $i<count($w->widgets); $i++) {
            $ww =& $w->widgets[$i];
            $widgetHtmlCode = $this->_generateWidget($ww);
            // strip end of line if the widget is invisible on this page
            $endOffset = $ww->get("endOffset");
            if ($widgetHtmlCode == "" && substr($fc->source,$endOffset,1) == "\n") {
                $endOffset ++; // eat eol
            }
            $fc->subst($ww->get("startOffset"), $endOffset, $widgetHtmlCode);
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
//        print "_generateEditingWidget(".$w->get("template") . $w->get("templateType") . ")\n";
        // replace all <widget> with special code
        if (!file_exists($w->get("templateFile"))) {
            return '';
        }
        $fc = func_new("WysiwygExportParser");
        $fc->source = $fc->translateTemplate(file_get_contents($w->get("templateFile")));
        for ($i=0; $i<count($w->widgets); $i++) {
            $ww =& $w->widgets[$i];
            $fc->subst($ww->get("startOffset"), $ww->get("endOffset"), $this->_generateWidgetReference($ww, true));
        }
        $text = $fc->substitute();
        switch ($w->get("templateType")) {
        case "plain":
        case "paragraph":
            return '<table border=0 width=100%><tr><td height=19 background="' . $this->get("imagesDir") . '/' . EDIT_START_IMG . '" template="' . $w->get("template"). '"></td></tr><tr><td>' .
            $text .  
            '</td></tr><tr><td height=9 background="' . $this->get("imagesDir") . '/' . EDIT_END_IMG . '"></td></tr></table>';
        case "in-table":
            return '<tr><td colspan=10 height=19 background="' . $this->get("imagesDir") . '/' . EDIT_START_IMG . '" template="' . $w->get("template"). '"></td></tr><tr><td>' .
            $text .  
            '</td></tr><tr><td colspan=10 height=9 background="' . $this->get("imagesDir") . '/' . EDIT_END_IMG . '"></td></tr>';
        }
    }
    
    function _generateWidgetReference(&$w, $editing = false)
    {
//        print "_generateWidgetReference(".$w->get("template") . ")\n";
        if (!$w->hasDefinedTemplate() && $editing) {
            // show abstract widget image
            return '<img align=absmiddle border=0 src="' . $this->get("imagesDir") . '/' . DUMMY_WIDGET_IMG . '"' . $w->get("attributesInTag") . '>';
        }
        $t = $w->get("template");
        if (isset($this->templateEditableParams[$t])) {
            $text = $this->_generateParentWidget($w, $editing);
            if ($editing) {
                switch ($w->get("templateType")) {
                case "plain":
/*                    return '<img align=absmiddle border=0 src="' . $this->get("imagesDir") . '/' . WIDGET_START_SHORT_IMG . '"' . $w->get("attributesInTag") . '>' . $text . '<img align=absmiddle border=0 src="' . $this->get("imagesDir") . '/' . WIDGET_END_SHORT_IMG . '">';*/
                case "paragraph":
                    return '<table border=0 width=100%><tr><td height=19 background="' . $this->get("imagesDir") . '/' . WIDGET_START_IMG . '"' . $w->get("attributesInTag") . '></td></tr><tr><td>' .
                    $text . 
                    '</td></tr><tr><td height=9 background="' . $this->get("imagesDir") . '/' . WIDGET_END_IMG . '"></td></tr></table>';
                case "in-table":
                    return '<tr><td colspan=10 height=19 background="' . $this->get("imagesDir") . '/' . WIDGET_START_IMG . '"' . $w->get("attributesInTag") . '></td></tr><tr><td>' .
                    $text .  
                    '</td></tr><tr><td colspan=10 height=9 background="' . $this->get("imagesDir") . '/' . WIDGET_END_IMG . '"></td></tr>';
                }
            } else {
                return $text;
            }
        } else {
        	$imageFileName = $this->getImageFileName($w);
			$image = $this->get("imagesDir") . "/". $imageFileName;

			$postfix = "";
			if ( !is_readable(HTML_BUILDER_PATH . "widgets/" . $imageFileName) ) {
				$image = $this->get("imagesDir") . "/dummy_widget.gif";
				$path_parts = pathinfo($imageFileName);
				if (basename($path_parts["basename"], $path_parts["extension"]) != ".") {
					$this->dummy_images[] = HTML_BUILDER_PATH . "widgets/" . $imageFileName;
					$postfix = "<!--Should be: " . $this->get("imagesDir") . "/". $imageFileName . "-->";
				}
			}
            return $this->_generateWidgetLink($w, '<a href="' . $this->getWidgetLink($w) . '"><img align=absmiddle border=0 src="' . $image . '"' . $w->get("attributesInTag") . '></a>' . $postfix);
        }
    }
    
    function _generateWidgetLink($w, $img)
    {
        switch ($w->get("templateType")) {
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
        $t = $w->get("template");
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
        return '<img align=absmiddle border=0 src="' . $this->get("imagesDir") . '/' . EDIT_PARAM_START_IMG . '" name="' . $name . '" modifier="' . $modifier . '">';
    }
    function _generateEditParamEnd($name, $modifier)
    {
        return '<img align=absmiddle border=0 src="'.$this->get("imagesDir") . '/' . EDIT_PARAM_END_IMG . '">';
    }

    function getWidgetFile($w)
    {
        $template = $w->get("template");
        if (substr($template, -4) == ".tpl") {
            $template = substr($template, 0, strlen($template)-4);
        }

        if (isset($this->templateReferenceParams[$w->get("template")])) {
            foreach($this->templateReferenceParams[$w->get("template")] as $name) {
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
        $t = $tree->get("template");
        if (!isset($this->widgetMap[$t])) {
            $this->widgetMap[$t] =& $tree;
        }
        if ($this->hasParams($tree)) {
            $p = $parent->get("template");
            $pp =& $parent;
        } else {
            $p = $t;
            $pp =& $tree;
        }
        for ($i=0; $i<count($tree->widgets); $i++) {
            $tt = $tree->widgets[$i]->get("template");
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
            $tree->widgets[$i]->parentWidget =& $tree;
            $this->_linkTree($pp, $tree->widgets[$i]);
        }
        $this->linkedTemplates[$t] = true;
    }
    
    function export($templates)
    {
        unlinkRecursive(HTML_BUILDER_PATH);
        // put cart.php as a redirect
        mkdirRecursive(HTML_BUILDER_PATH);
        if (($fd = fopen(HTML_BUILDER_PATH . "/cart.php", "wb"))) {
            $url = $this->xlite->shopUrl("cart.php");
            fwrite($fd, <<<EOT
<?php
header("Location: $url?" . \$_SERVER["QUERY_STRING"]);
?>
EOT
            );
        }
        fclose($fd);
        $layout =& func_get_instance("Layout");
        $templatesDir = $layout->getPath();
        copy($templatesDir . 'style.css', HTML_BUILDER_PATH . 'style.css');
        copyRecursive($templatesDir . 'images', HTML_BUILDER_PATH . 'images');
        copyRecursive('skins/admin/images', HTML_BUILDER_PATH . 'widgets');
        $this->templateMap = array();
        $this->widgetMap = array();
        $this->linkedTemplates = array();
        $this->exportedPages = array();
        $this->errors = 0;
        foreach ($templates as $template) {
            $tree = func_new($this->widgetClass);
            $tree->set("template", $template);
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
            $template = $w->get("template");
        }
        return isset($this->templateEditableParams[$template] )|| isset($this->templateReferenceParams[$template]);
    }

    function _export(&$tree)
    {
        $t = $tree->get("template");
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
        $this->root =& $this->widgetMap[$t];
        $this->pageTarget = $this->getWidgetTarget($tree);
        $this->pageMode = $this->getWidgetMode($tree);
        $tree->editing = true;
        $this->_exportPage($this->root, $tree, $this->getTemplateLink($tree->get("template")));
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
        return strtr(str_replace(".tpl", ".html", $t), " /\\", "___");
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
        $this->call("htmlStorage.save", $file, $source);
    }
    
    function _getComment(&$root, $link = false)
    {
        $node = func_new("FileNode", $root->get("templateFile"));
        $comment = $node->get("comment");
        if (!$comment) {
            $comment = $root->get("template");
        }
        return $comment;
    }

    function _generateNavigation(&$page)
    {
        $navigation = '<b>' . $this->_getComment($page) . "</b>";
        if (!isset($this->templateMap[$page->get("template")])) {
            return $navigation;
        }
        $navigation .= "&nbsp;|&nbsp;";
        $parents = $this->templateMap[$page->get("template")];
        if (count($parents)>1) {
            $navigation .= 'Used from';
            // enumerate all widgets the given template is called from
            foreach ($parents as $parent => $qt) {
                $navigation .= ' <a href="' . $this->getTemplateLink($parent) . '">' . $parent . '</a>';
            }
            return $navigation;
        }
        $path = '';
        $t = $page->get("template");
        while (isset($this->templateMap[$t]) && count($this->templateMap[$t])==1) {
            foreach ($this->templateMap[$t] as $t => $qt) {}
            if ($path!=='') {
                $path = ' :: ' . $path;
            }
            $path = '<a href="' . $this->getTemplateLink($t) . '">' . $t . '</a>' . $path;
        }
        return $navigation . $path;
    }
    
    function &getHtmlStorage()
    {
        if (is_null($this->htmlStorage)) {
            $this->htmlStorage = func_new("WysiwygMediatorHtmlStorage");
        }
        return $this->htmlStorage;
    }

    function importPage($page)
    {
        $wysiwygImporter = func_new("WysiwygImportParser");
        $wysiwygImporter->set("source", $page);
        $wysiwygImporter->set("imagesDir", $this->get("imagesDir"));
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
        $this->layout = $layout =& func_get_instance("Layout");
		$templatesDir = $layout->getPath();
        copyRecursive(HTML_BUILDER_PATH . 'images', $templatesDir . 'images');
        @copy(HTML_BUILDER_PATH . 'style.css', $templatesDir . 'style.css');
		$fileList = $this->get("htmlStorage.fileList");
		if (!is_array($fileList) || count($fileList) == 0) {
			$this->errors++;
            print "<font color=red>WARNING: Nothing to import!</font>";
		}
        foreach ($fileList as $name) {
            $source = $this->call("htmlStorage.read", $name);
            // save template
            print "Importing from $name ";
            if ($this->importPage($source)) {
                $templateName = $this->templateName;
                print "to $templateName...";
                $template = func_new($this->templateClass);
                $template->set("path", $templateName);
                if (!file_exists($template->get("file"))) {
                    print " <font color=red>[FAILURE: no such file " . $template->get("file") . "]</font>";
                    $this->errors++;
                } else if (!is_writable($template->get("file"))) {
                    print " <font color=red>[FAILURE: file " .$template->get("file") . " is not writable]</font>";
                    $this->errors++;
                } else {
                    $templateSource = $this->template;
                    $template->set("content", $templateSource);
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
}

class WysiwygMediatorHtmlStorage extends Object // {{{
{
    function save($file, $content)
    {
        $file = HTML_BUILDER_PATH . $file;
        mkdirRecursive(dirname($file));
        $fd = fopen($file, "wb");
        fwrite($fd, $content);
        fclose($fd);
    }
    function read($file)
    {
        return file_get_contents(HTML_BUILDER_PATH . $file);
    }
    function getFileList()
    {
        $list = array();
        if ($dh = opendir(HTML_BUILDER_PATH)) {
            while (($file = readdir($dh)) !== false) {
                if ($file{0} != '.' && is_file(HTML_BUILDER_PATH . $file) && substr($file, -5)==".html") {
                    $list[] = $file;
                }
            }
            closedir($dh);
        }
        return $list;
    }
} // }}}

class WysiwygMediatorWidget extends Widget // {{{
{
    var $attributes = array();
    var $attributesEvaled = array();
    var $code = "";
    var $parent = false;
    var $editing = false;
    var $parentWidget = null;
    var $templateType = null;

    function setAttributesEvaled($params)
    {
        $this->attributesEvaled = $params;
        // read widget's template
        if (isset($params['template'])) {
            $this->set("template", $params['template']);
        } else {
            if (isset($params['class'])) {
                $class = $params['class'];
                if (func_class_exists($class)) {
                    $component = func_new($class);
                    $this->set("template", $component->get("template"));
                }
            }
        }
    }
    function hasDefinedTemplate()
    {
        if (isset($this->attributes['template'])) {
            return $this->attributes['template'] == $this->attributesEvaled['template']; // no expressions in 'template' attribute
        } else {
            return $this->get("template") && file_exists($this->get("templateFile"));
        }
    }
    function getAttributesInTag()
    {
        $result = '';
        foreach ($this->get("attributes") as $name => $val) {
            if (is_null($val)) {
                $result .= ' ' . $name;
            } else {
                $result .= ' ' . $name . '="' . $val .'"';
            }
        }
        return $result;
    }
    function &getTemplateType()
    {
        if (is_null($this->templateType)) {
            $t = $this->get("templateFile");
            if ($t && file_exists($t)) {
                $src = strtolower(file_get_contents($t));
                $tags = array('table', 'p', 'hr', 'center', 'br', 'h1', 'h2', 'h3', 'html', 'widget', 'div');
                $this->templateType = "plain";
                foreach ($tags as $tag) {
                    if (strpos($src,'<'. $tag) !== false) {
                        $this->templateType =  "paragraph";
                        break;
                    }
                }
                // find first tag
                $pos = strpos($src, '<');
                if ($pos !== false) {
                    $tag = substr($src, $pos+1, strcspn(substr($src, $pos+1), " \n\r\t>"));
                    if ($tag == 'tbody' || $tag == 'tr' || $tag == 'td') {
                        $this->templateType =  "in-table";
                    }
                }
            } else {
                $this->templateType =  "paragraph";
            }
        }
        return $this->templateType;
    }
} // }}}

class WysiwygExportParser extends FlexyCompiler // {{{
{
    var $widgetClass = null;
    var $wysiwygMediator = null;
    var $configVars = null;

    function _parseTemplate($file, $params)
    {
        $this->source = $this->translateTemplate(file_get_contents($file));
        $this->widgets = array();
        $this->params = $params;
        $this->errorMessage = '';
        $this->parse();
    }

    function translateTemplate($src)
    {
        $lay =& func_get_instance("Layout");
        return str_replace(array('{*', '*}', 'skins/' . $lay->get("skin") . '/' . $lay->get("locale") . '/style.css'), array('<!--*', '*-->', 'style.css'), $src);
    }

    function postprocess()
    {
        if ($this->errorMessage) {
            return;
        }
        $namedWidgets = array();
        $attributes = array();
        $attributesEvaled = array();
        $insideWidget = false;
        $params = array_merge($this->params, $this->_getConfigVars());
        for ($i=0; $i<=count($this->tokens); $i++) {
            if ($i<count($this->tokens)) {
                $token = $this->tokens[$i];
            } else {
                $token = array("type"=>"eof");
            }
            if ($token["type"] == "attribute" && $insideWidget) {
                $attr = $token["name"];
                if (isset($this->tokens[$i+1]) && $this->tokens[$i+1]["type"] == "attribute-value") {
                    $i++;
                    $val = $this->getTokenText($i);
                    $attributes[$attr] = $val;
                    $attributesEvaled[$attr] = $this->wysiwygMediator->_replaceVal($val, $params);
                } else {
                    $attributes[$attr] = null;
                    $attributesEvaled[$attr] = null;
                }
            }
            if ($token["type"] != "attribute" && $token["type"] != "attribute-value" && $attributes && $insideWidget) {
////                if (isset($attributes["name"]) && !isset($attributes["class"]) && !isset($attributes["template"])) {
                    // fetch by name
////                    $w =& $namedWidgets[$attributes["name"]];
////                } else {
                    $w =& func_new($this->widgetClass);
                    $w->set("attributes", $attributes);
                    $w->set("attributesEvaled", $attributesEvaled);
                    if (isset($attributes["name"])) {
						if (!$w->get("template")) {
							$tw = $namedWidgets[$attributes["name"]];
							if ($tw) {
								$w->set("template", $tw->get("template"));
							}
						}

                        $namedWidgets[$attributes["name"]] =& $w;
                    }
////                }
                $w->set("startOffset", $this->tokens[$widgetInd]["start"]);
                $w->set("endOffset", $this->tokens[$widgetInd]["end"]);
                $this->addWidget($w);
            }
            if ($token["type"] != "attribute" && $token["type"] != "attribute-value") {
                $insideWidget = false;
            }
            if ($token["type"] == "tag" || $token["type"] == "open-close-tag") {
                if (!strcasecmp($token["name"], "widget")) {
                    $attributes = $attributesEvaled = array();
                    if ($token["type"] == "open-close-tag") {
                        $attributes["open-close-tag"] = null;
                    }
                    $widgetInd = $i;
                    $insideWidget = true;
                }
            }
        }
    }
    
    function _getConfigVars()
    {
        if (is_null($this->configVars)) {
            $result = array();
            $config = func_new("Config");
            foreach ($config->findAll() as $c)
            {
                $result['config.'.$c->get("category").'.'.$c->get("name")] = $c->get("value");
            }
            $this->configVars = $result;
        }
        return $this->configVars;
    }
    
    function addWidget($w)
    {
        $this->widgets[] =& $w;
    }

    function error($message)
    {
        // count \n
        $line = $col = 1;
        for ($i=0; $i < $this->offset; $i++) {
            if ($this->source{$i} == "\n") {
                $line ++;
                $col=0;
            }
            $col++;
        }
        $this->errorMessage = "File $this->file, line $line, col $col: $message";
        return false;
    }
} // }}}

class WysiwygImportParser extends FlexyCompiler
{
    var $imagesDir = null;
    var $template = null;
    var $templateName = null;

    function postprocess() // {{{
    {
        // page ::= .* edit_area_start edit_area edit_area_end .*
        // edit_area ::= .* (widget_start .* param_edit_area_start param_value  param_edit_area_end .* widget_end .*)*
        // param_edit_area_start ::=  <img .... name="param_name">

        // find edit_area_start
        $i=0;
        $this->error = '';
        while (!$this->isEditAreaStart($i,$this->templateName,$editAreaStartOffset)) {
            if ($i == count($this->tokens)) {
                return $this->error('No edit area starting mark is found');
            }
            $i++;
            $editAreaStartPos = $i;
        }
        $editAreaEndPos = $i;
        while (!$this->isEditAreaEnd($i, $end)) {
            if ($i >= count($this->tokens)) {
                return $this->error('No edit area ending mark is found');
            }
            // find and replace widget calls
            $start = $i;
            if ($this->isWidgetCall($i, $params, $endOffset)) {
                $code = $this->compileWidgetCall($params);
                $this->subst($this->tokens[$start]['start'], $endOffset, $code);
            } else if ($this->isWidgetStart($i, $params, $endOffset)) {
                // find all param edit areas
                $end = 0;
                while (!$this->isWidgetEnd($i, $end)) {
                    // inside widget marks
                    if ($i == count($this->tokens)) {
                        return $this->error('No widget ending mark is found');
                    }
                    $peaStartOffset = 0;
                    if ($this->isParamEditAreaStart($i, $name, $modifier, $peaStartOffset)) {
                        // find end of param area
                        $peaend = $i;
                        while (!$this->isParamEditAreaEnd($i, $end)) {
                            if ($i == count($this->tokens)) {
                                return $this->error('No edit area ending mark is found for parameter ' . $name);
                            }
                            $i++;
                            $peaend = $i;
                        }
                        $value = substr($this->source, $peaStartOffset, $this->tokens[$peaend]['start'] - $peaStartOffset);
                        $params[$name] = $this->reverseModifier($value, $modifier);
                    } else {
                        $i++;
                    }
                }
                $code = $this->compileWidgetCall($params);
                $this->subst($this->tokens[$start]['start'], $end, $code);
            } else {
                $i++;
            }
            $editAreaEndPos = $i;
        }
        // remove the reminder of the page
        $editAreaEndOffset = $this->tokens[$editAreaEndPos]['start'];
        $this->source = substr($this->source, 0, $editAreaEndOffset);
        $this->template = $this->translateTemplate(substr($this->substitute(), $editAreaStartOffset));
    } // }}}

    function translateTemplate($src)
    {
        $lay =& func_get_instance("Layout");
        return str_replace(array('<!--*', '*-->', 'style.css'), array('{*', '*}', 'skins/' . $lay->get("skin") . '/' . $lay->get("locale") . '/style.css'), $src);
    }

    function compileWidgetCall($params) // {{{
    {
        $call = "<widget";
        if (isset($params["open-close-tag"])) {
            $close = '/>';
            unset($params["open-close-tag"]);
        } else {
            $close = '>';
        }
        foreach ($params as $name => $value) {
            if ($value === true) {
                $call .= ' ' . $name;
            } else {
                $call .= ' ' . $name . '="' . str_replace('"', '&quot;', $value) . '"';
            }
        }
        return $call . $close;
    } // }}}

    function reverseModifier($str, $modifier) // {{{
    {
        switch ($modifier) {
        case '': $str = func_htmldecode($str); break;
        case ':u': $str = urldecode($str); break;
        }
        return $str;
    } // }}}

    function equalTokenArrays(&$i, $tokens, &$endOffset) // {{{
    {
        $old = $i;
        $endOffset = 0;
        for ($j = 0; $j<count($tokens); $j++) {
            if ($i>=count($this->tokens) ||
              !$this->equalTokens($this->tokens[$i], $tokens[$j])) {
                $i = $old;
                return false;
            }
            if ($this->tokens[$i]['end'] > $endOffset) {
                $endOffset = $this->tokens[$i]['end'];
            }
            $i++;
        }
        return true;
    } // }}}
    
    function equalTokens($token1, $token2) // {{{
    {
        foreach ($token2 as $name=>$value) {
            if ($name == 'value') {
                if (!strcasecmp(substr($this->source, $token1['start'], $token1['end']-$token1['start']), $value)) {
                    continue;
                } else {
                    return false;
                }
            }
            if (!isset($token1[$name]) || strcasecmp($token1[$name], $value)) {
                return false;
            }
        }
        return true;
    } // }}}
   
    function isEditAreaStart(&$i, &$template, &$endOffset) // {{{
    {
        $old = $i;
        if ($this->equalTokenArrays($i, array(
                array('type'=>'tag','name'=>'table'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'tag','name'=>'tr'),
                array('type'=>'tag','name'=>'td'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute','name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . EDIT_START_IMG),
                array('type'=>'attribute'), array('type'=>'attribute-value'), // template = ?, offset 19
                array('type'=>'close-tag','name'=>'td'),
                array('type'=>'close-tag','name'=>'tr'),
                array('type'=>'tag','name'=>'tr'),
                array('type'=>'tag','name'=>'td'),
            ), $endOffset)) {
            $template = $this->getTokenText($old+12);
            return true;
        }
        if ($this->equalTokenArrays($i, array(
                array('type'=>'tag','name'=>'tr'),
                array('type'=>'tag','name'=>'td'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute','name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . EDIT_START_IMG),
                array('type'=>'attribute'), array('type'=>'attribute-value'), // template = ?, offset 19
                array('type'=>'close-tag','name'=>'td'),
                array('type'=>'close-tag','name'=>'tr'),
                array('type'=>'tag','name'=>'tr'),
                array('type'=>'tag','name'=>'td'),
            ), $endOffset)) {
            $template = $this->getTokenText($old+9);
            return true;
        }
        return false;
    } // }}}

    function isEditAreaEnd(&$i, &$endOffset) // {{{
    {
        return $this->equalTokenArrays($i, array(
                array('type'=>'close-tag','name'=>'td'),
                array('type'=>'close-tag','name'=>'tr'),
                array('type'=>'tag','name'=>'tr'),
                array('type'=>'tag','name'=>'td'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute','name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' .  EDIT_END_IMG),
                array('type'=>'close-tag','name'=>'td'),
                array('type'=>'close-tag','name'=>'tr'),
                array('type'=>'close-tag','name'=>'table'),
            ), $endOffset)  ||
            $this->equalTokenArrays($i, array(
                array('type'=>'tag','name'=>'tr'),
                array('type'=>'tag','name'=>'td'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute','name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' .  EDIT_END_IMG),
                array('type'=>'close-tag','name'=>'td'),
                array('type'=>'close-tag','name'=>'tr'),
            ), $endOffset)  ||
            $this->equalTokenArrays($i, array(
                array('type'=>'close-tag','name'=>'td'),
                array('type'=>'close-tag','name'=>'tr'),
                array('type'=>'tag','name'=>'tr'),
                array('type'=>'tag','name'=>'td'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute','name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' .  EDIT_END_IMG),
                array('type'=>'close-tag','name'=>'td'),
                array('type'=>'close-tag','name'=>'tr'),
            ), $endOffset) ||
            $this->equalTokenArrays($i, array(
                array('type'=>'tag','name'=>'img'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . EDIT_END_SHORT_IMG),
            ), $endOffset);

    } // }}}

    function isParamEditAreaStart(&$i, &$name, &$modifier, &$endOffset) // {{{
    {
        $old = $i;
        $match = false; 
        if ($this->equalTokenArrays($i, array(
                array('type'=>'tag','name'=>'img'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute'), array('type'=>'attribute-value'),
                array('type'=>'attribute'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . EDIT_PARAM_START_IMG),
                array('type'=>'attribute', 'name'=>'name'), array('type'=>'attribute-value'),
                array('type'=>'attribute', 'name'=>'modifier'), array('type'=>'attribute-value')), $endOffset)) {
             $name = $this->getTokenText($old+8);
             $modifier = $this->getTokenText($old+10);
             $match = true;
         }
         return $match;
    } // }}}
    
    function isParamEditAreaEnd(&$i, &$endOffset)  // {{{
    {
        return $this->isEditAreaEnd($i, $endOffset);
    } // }}}
    
    function isWidgetStart(&$i, &$params, &$endOffset) // {{{
    {
        $old = $i;
        $match = false; 
        if ($this->equalTokenArrays($i, array(
             array('type'=>'tag','name'=>'table'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'tag','name'=>'tr'),
             array('type'=>'tag','name'=>'td'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute', 'name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . WIDGET_START_IMG),
             ), $endOffset)) {
            $i = $old+6;
            $tag = $this->parseTag($i, $endOffset);
            if ($this->equalTokenArrays($i, array(
                 array('type'=>'close-tag','name'=>'td'),
                 array('type'=>'close-tag','name'=>'tr'),
                 array('type'=>'tag','name'=>'tr'),
                 array('type'=>'tag','name'=>'td'),
                 ), $endOffset)) {
                $match = true; 
            }
        }
        if (!$match && $this->equalTokenArrays($i, array(
             array('type'=>'tag','name'=>'tr'),
             array('type'=>'tag','name'=>'td'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute', 'name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . WIDGET_START_IMG),
             ), $endOffset)) {
            $i = $old+1;
            $tag = $this->parseTag($i, $endOffset);
            if ($this->equalTokenArrays($i, array(
                 array('type'=>'close-tag','name'=>'td'),
                 array('type'=>'close-tag','name'=>'tr'),
                 ), $endOffset)) {
                $match = true; 
            }
        }
        if ($match) {
            $params = $tag['attributes'];
            if (isset($params['height'])) {
            	unset($params['height']);
            }
            if (isset($params['background'])) {
            	unset($params['background']);
            }
            if (isset($params['colspan'])) {
            	unset($params['colspan']);
            }
        }
        return $match;
    } // }}}

    function isWidgetEnd(&$i, &$endOffset) // {{{
    {
        return $this->equalTokenArrays($i, array(
            array('type'=>'close-tag', 'name'=>'td'),
            array('type'=>'close-tag', 'name'=>'tr'),
            array('type'=>'tag', 'name'=>'tr'),
            array('type'=>'tag', 'name'=>'td'),
            array('type'=>'attribute'), array('type'=>'attribute-value'),
            array('type'=>'attribute', 'name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . WIDGET_END_IMG),
            array('type'=>'close-tag', 'name'=>'td'),
            array('type'=>'close-tag', 'name'=>'tr'),
            array('type'=>'close-tag', 'name'=>'table'),
            ), $endOffset) ||
            $this->equalTokenArrays($i, array(
            array('type'=>'close-tag', 'name'=>'td'),
            array('type'=>'close-tag', 'name'=>'tr'),
            array('type'=>'tag','name'=>'tr'),
            array('type'=>'tag','name'=>'td'),
            array('type'=>'attribute'), array('type'=>'attribute-value'), // colspan
            array('type'=>'attribute'), array('type'=>'attribute-value'), 
            array('type'=>'attribute', 'name'=>'background'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' .  WIDGET_END_IMG),
            array('type'=>'close-tag','name'=>'td'),
            array('type'=>'close-tag','name'=>'tr'),
             ), $endOffset);
    } // }}}

    function isWidgetCall(&$i, &$params, &$endOffset) // {{{
    {
        $old = $i;
        $match = false; 
        $parsedWidget = array(
             array('type'=>'tag','name'=>'a'),
             array('type'=>'attribute', 'name'=>'href'), array('type'=>'attribute-value'),
             array('type'=>'tag','name'=>'img'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute', 'name'=>'src'), array('type'=>'attribute-value'), // widget image
             );
        if ($this->equalTokenArrays($i, $parsedWidget, $endOffset)) {
            $i = $old+3;
            $tag = $this->parseTag($i, $endOffset);
            if (func_starts_with($tag['attributes']['src'], $this->imagesDir . '/') && $this->equalTokenArrays($i, array(
                 array('type'=>'close-tag','name'=>'a'),
                 ), $endOffset)) {
                $match = true; 
            }
        }
        if (!$match && $this->equalTokenArrays($i, array_merge(array(
             array('type'=>'tag', 'name'=>'div')), $parsedWidget), $endOffset)) {
            $i = $old+4;
            $tag = $this->parseTag($i, $endOffset);
            if (func_starts_with($tag['attributes']['src'], $this->imagesDir . '/') && $this->equalTokenArrays($i, array(
                 array('type'=>'close-tag','name'=>'a'),
                 array('type'=>'close-tag','name'=>'div'),
                 ), $endOffset)) {
                $match = true; 
            }
        }
        if (!$match && $this->equalTokenArrays($i, array_merge(array(
             array('type'=>'tag', 'name'=>'tr'),array('type'=>'tag', 'name'=>'td')), $parsedWidget), $endOffset)) {
            $i = $old+5;
            $tag = $this->parseTag($i, $endOffset);
            if (func_starts_with($tag['attributes']['src'], $this->imagesDir . '/') && $this->equalTokenArrays($i, array(
                 array('type'=>'close-tag','name'=>'a'),
                 array('type'=>'close-tag','name'=>'td'),
                 array('type'=>'close-tag','name'=>'tr'),
                 ), $endOffset)) {
                $match = true; 
            }
        }

        if (!$match && $this->equalTokenArrays($i, array(
             array('type'=>'tag','name'=>'img'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute'), array('type'=>'attribute-value'),
             array('type'=>'attribute', 'name'=>'src'), array('type'=>'attribute-value', 'value'=>$this->imagesDir . '/' . DUMMY_WIDGET_IMG), // widget image
              ), $endOffset)) {
            $i = $old;
            $tag = $this->parseTag($i, $endOffset);
            $match = true; 
        }
        if ($match) {
            $params = $tag['attributes'];
            if (isset($params['align'])) {
            	unset($params['align']);
            }
            if (isset($params['border'])) {
            	unset($params['border']);
            }
            if (isset($params['src'])) {
            	unset($params['src']);
            }
        }
        return $match;
    } // }}}

    function parseTag(&$i, &$endOffset) // {{{
    {
        if ($i >= count($this->tokens)) {
            return false;
        }
        if ($this->tokens[$i]['type'] == 'tag' || $this->tokens[$i]['type'] == 'open-close-tag') {
            $endOffset = $this->tokens[$i]['end'];
            $tag = $this->tokens[$i];
            $i++;
            $name = '';
            while ($i<count($this->tokens) && 
                ($this->tokens[$i]['type'] == 'attribute' || 
                 $this->tokens[$i]['type'] == 'attribute-value')) {
                if ($this->tokens[$i]['type'] == 'attribute') {
                    if ($name) {
                        $tag['attributes'][$name] = true;
                    }
                    $name = $this->tokens[$i]['name'];
                } else {
                    $tag['attributes'][$name] = $this->getTokenText($i);
                    $name = '';
                }
                $i++;
            }
            if ($name) {
                $tag['attributes'][$name] = true;
            }
            return $tag;
        }
        return false;
    } // }}}

    function error($msg) // {{{
    {
        $this->error = $msg;
        return false;
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
