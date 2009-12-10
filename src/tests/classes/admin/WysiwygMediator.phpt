<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class WysiwygMediatorTest extends PHPUnit_TestCase
{
    function setUp()
    {
        $this->builder = func_new("WysiwygMediator");
        $this->builder->set("imagesDir", "tests/classes/admin/WysiwygMediator");
        $this->builder->set("widgetClass", "MyWidget");
        $this->builder->set("templateClass", "MyTemplate");
        $this->builder->set("templateEditableParams", array("subwidget.tpl"=>array("head")));
        $this->builder->set("templateReferenceParams", array("common.tpl"=>array("refparam")));
        $this->builder->set("htmlStorage", func_new("MyHtmlStorage"));
        $this->tree = func_new("MyWidget");
        $this->tree->set("template", "main.tpl");
    }

    function testWEPTranslateTemplate() // {{{
    {
        $wep = func_new("WysiwygExportParser");
        $lay =& func_get_instance("Layout");
        $lay->set("skin", "some");
        $lay->set("locale", "LA");
        $src = $wep->translateTemplate("{* comment*} skins/some/LA/style.css");
        $this->assertEquals("<!--* comment*--> style.css", $src);
    } // }}}

    function testWIPTranslateTemplate() // {{{
    {
        $wep = func_new("WysiwygImportParser");
        $lay =& func_get_instance("Layout");
        $lay->set("skin", "some");
        $lay->set("locale", "LA");
        $src = $wep->translateTemplate("<!--* comment*--> style.css");
        $this->assertEquals("{* comment*} skins/some/LA/style.css", $src);
    } // }}}

    function test_buildFullTree() // {{{
    {
        $this->builder->buildFullTree($this->tree);
        $main = $this->tree->widgets;
        $this->assertEquals(3, count($main));
        $this->assertEquals("common.tpl", $main[0]->get("template"));
        $this->assertEquals("common.tpl", $main[1]->get("template"));
        $this->assertEquals("subwidget.tpl", $main[2]->get("template"));
        $this->assertEquals(1, count($main[0]->widgets));
        $this->assertEquals("subwidget.tpl", $main[0]->widgets[0]->get("template"));
    } // }}}

    function test_replaceVal() // {{{
    {
        $this->assertEquals("here &lt;VAL&gt;", $this->builder->_replaceVal("here {widget.param}", array("param" => "<VAL>")));
    } // }}}

    function test_generateWidget() // {{{
    {
        $this->builder->buildFullTree($this->tree);
        $xxx = null;
        $this->builder->_linkTree($xxx, $this->tree);
        $this->tree->parent = true;
$html = <<<EOT
<div><a href="common-subwidget_tpl.html"><img align=absmiddle border=0 src="tests/classes/admin/WysiwygMediator/common-subwidget_tpl.gif" template="common.tpl" param="1" refparam="subwidget.tpl"></a></div>
<div><a href="common-subwidget_tpl.html"><img align=absmiddle border=0 src="tests/classes/admin/WysiwygMediator/common-subwidget_tpl.gif" template="common.tpl" param="{2}" refparam="subwidget.tpl"></a></div>
subwidget HDR
EOT;
        $this->assertEquals(trim($html), trim($this->builder->_generateWidget($this->tree)));
        $this->tree->parent = true;
        $this->tree->widgets[0]->editing = true;
//        print $this->builder->_generateWidget($this->tree);
    } // }}}

    function createParser($source)
    {
        $parser = func_new("WysiwygImportParser");
        $parser->source = $source;
        $parser->offset = 0;
        $parser->stack = array();
        $parser->tokens = array();
        $parser->widgetNames = array();
        $parser->errorMessage = '';
        $parser->phpinitcode = "<?php\n";
        $parser->imagesDir = "widgets";
        $parser->html();
        return $parser;
    }
    
    function testEqualTokenArrays()
    {
        $parser = $this->createParser('<TABLE border="0" cellpadding="2" cellspacing="0" width="100%"></table>');
        $i = 1;
        $offset = 0;
        $this->assertTrue($parser->equalTokenArrays($i, array(
            array('type'=>'attribute', 'name'=>'BORDER'),
            ), $offset));
        $this->assertEquals(2, $i);
        $this->assertEquals(17, $offset);
        $i = 0;
        $this->assertTrue($parser->equalTokenArrays($i, array(
            array('type'=>'tag'),
            array('type'=>'attribute', 'name'=>'BORDER'),
            array('type'=>'attribute-value')
            ), $offset));
        $this->assertEquals(3, $i);
        $this->assertEquals(63, $offset);

        $this->assertFalse($parser->equalTokenArrays($i, array(
            array('type'=>'tag'),
            array('type'=>'attribute', 'name'=>'BORDER'),
            array('type'=>'attribute-value')
            ), $offset));
    }

    function testisEditAreaStart()
    {
        $parser = $this->createParser('<table width=100%><tr><td><table border=0  width=100%><tr><td height=19 background="widgets/edit_area.gif" template="modules/GiftCertificates/invoice_item.tpl"></td></tr><tr><td>');
        $i = 0;
        $end = 0;
        $template = '';
        $this->assertFalse($parser->isEditAreaStart($i, $template, $end));
        $i = 5;
        $this->assertTrue($parser->isEditAreaStart($i, $template, $end));
        $this->assertEquals("modules/GiftCertificates/invoice_item.tpl", $template);
        $parser = $this->createParser($src = '</tr><tr><td colspan=10 height=19 background="widgets/edit_area.gif" template="register_success.tpl"></td></tr><tr><td>');
        $i = 0;
        $end = 0;
        $template = '';
        $this->assertFalse($parser->isEditAreaStart($i, $template, $end));
        $i++;
        $this->assertTrue($parser->isEditAreaStart($i, $template, $end));
        $this->assertEquals(strlen($src), $end);
        $this->assertEquals('register_success.tpl', $template);
    }

    function testisEditAreaEnd()
    {
        $parser = $this->createParser('</td></tr><tr><td height=9 background="widgets/end.gif"></td></tr></table>');
        $i = 0;
        $end = 0;
        $template = '';
        $this->assertTrue($parser->isEditAreaEnd($i, $template, $end));
        $parser = $this->createParser('</td></tr><tr><td colspan=10 height=9 background="widgets/end.gif"></td></tr>');
        $i = 0;
        $end = 0;
        $template = '';
        $this->assertTrue($parser->isEditAreaEnd($i, $template, $end));
        $parser = $this->createParser('<img align=absmiddle border=0 src="widgets/end_s.gif">');
        $i = 0;
        $end = 0;
        $template = '';
        $this->assertTrue($parser->isEditAreaEnd($i, $template, $end));
    }

    function testisWidgetStart()
    {
        $parser = $this->createParser('<table border=0 width=100%><tr><td height=19 background="widgets/widget.gif" template="common/sidebar_box.tpl" head="Help" dir="help"></td></tr><tr><td>');
        $i = 0;
        $end = 0;
        $params = array();
        $this->assertTrue($parser->isWidgetStart($i, $params, $end));
        $this->assertEquals(array('template' => 'common/sidebar_box.tpl',
                'head' => 'Help', 'dir' => 'help'), $params);
        $this->assertFalse($parser->isWidgetStart($i, $params, $end));
    }

    function testisWidgetEnd()
    {
        $parser = $this->createParser('</td></tr><tr><td height=9 background="widgets/widget_end.gif"></td></tr></table>');
        $i = 0;
        $end = 0;
        $this->assertTrue($parser->isWidgetEnd($i, $end));
        $this->assertFalse($parser->isWidgetEnd($i, $end));
        $parser = $this->createParser('</td></tr><tr><td colspan=10 height=9 background="widgets/widget_end.gif"></td></tr>');
        $i = 0;
        $end = 0;
        $this->assertTrue($parser->isWidgetEnd($i, $end));
    }

    function testisWidgetCall()
    {
        $parser = $this->createParser('<a href="category_description.html"><img align=absmiddle border=0 src="widgets/category_description.gif" target="category" template="category_description.tpl" visible="{category.description}"></a>');
        $i = 0;
        $end = 0;
        $params = array();
        $this->assertTrue($parser->isWidgetCall($i, $params, $end));
        $this->assertEquals(array('target' => 'category', 'template' => 'category_description.tpl', 'visible' => '{category.description}'), $params);
        $parser = $this->createParser('<img align=absmiddle border=0 src="widgets/dummy_widget.gif" template="{widget.body}">');
        $i = 0;
        $end = 0;
        $params = array();
        $this->assertTrue($parser->isWidgetCall($i, $params, $end));
        $this->assertEquals(array('template'=>'{widget.body}'), $params);
    }


    function testParseComplex() // {{{
    {
        $builder = func_new('WysiwygMediator');
        $builder->set("imagesDir", "widgets");
        $builder->importPage(file_get_contents('tests/classes/admin/WysiwygMediator/common_dialog.html'));
        $this->assertEquals('common/dialog.tpl', $builder->templateName);
        print $builder->error;
        $this->assertEquals(file_get_contents('tests/classes/admin/WysiwygMediator/dialog.tpl'), $builder->template);
    } // }}}

    function testExportImport() // {{{
    {
        $this->builder->export(array("main.tpl"));
//        print_r($this->builder->get("htmlStorage.files"));
        $this->builder->import();
        global $template_files;
        $this->assertEquals(3, count($template_files));
        foreach ($template_files as $name => $content) {
            $this->assertEquals(file_get_contents($name), $content);
        }
    } // }}}

}

func_new("WysiwygMediatorWidget");
class mywidget extends wysiwygmediatorwidget__ {
    function getTemplateFile()
    {
        return "tests/classes/admin/WysiwygMediator/" . $this->get("template");
    }

    function save()
    {
        global $template_files;
        if (!isset($template_files)) {
            $template_files = array();
        }
        $template_files[$this->get("templateFile")] = $this->get("content");
    }
}
func_new("Template");
class mytemplate extends Object__ {
    function setFile($file)
    {
        $this->file = $file;
        $this->path = str_replace("tests/classes/admin/WysiwygMediator/", "", $file);
    }
    function setPath($path)
    {
        $this->path = $path;
        $this->file = "tests/classes/admin/WysiwygMediator/".$path;
    }
    function save()
    {
        global $template_files;
        if (!isset($template_files)) {
            $template_files = array();
        }
        $template_files[$this->get("file")] = $this->get("content");
    }

}

class MyHtmlStorage extends WysiwygMediatorHtmlStorage__ {
    function save($file, $content)
    {
        $this->files[$file] = $content;
    }

    function read($file)
    {
        return $this->files[$file];
    }

    function getFileList()
    {
        return array_keys($this->files);
    }
}

$suite = new PHPUnit_TestSuite("WysiwygMediatorTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
