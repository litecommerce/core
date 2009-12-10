<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class FlexyCompilerTest extends PHPUnit_TestCase
{
    function FlexyCompilerTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }
    function testSpanForeach()
    {
        $fc = func_new("FlexyCompiler");
        $fc->set("source", "<span foreach=\"function(arg1,dialog.get(#hi#)),v\">asd</span>");
        $fc->set("url_rewrite", "images:../images");
        $fc->parse();

        $this->assertEquals(Array("0" => Array("type" => "tag", "start" => 0, "name" => "span", "end" => 50), "1" => Array("type" => "attribute", "start" => 6, "name" => "foreach", "end" => 49), "2" => Array("type" => "attribute-value", "start" => 15, "end" => 48), "3" => Array("type" => "close-tag", "start"=>53,"name" => "span", "end" => 60)), $fc->get("tokens"));
        $this->assertEquals('<?php $_foreach_var = $t->call(\'function\',$t->get(\'arg1\'),$t->call(\'dialog.get\',"hi")); if (!is_null($_foreach_var)) foreach($_foreach_var as $t->v){?><span >asd</span><?php }?>', $fc->phpcode);
        
        $fc->set("source", "{if:!condition()}<img src=\"images/img.gif\">{else:}NO{end:}");
        $fc->parse();
        $this->assertEquals('<?php if(!($t->call(\'condition\'))){?><img src="../images/img.gif"><?php }else{ ?>NO<?php }?>',  $fc->phpcode);
    }

    function test_flexyExpression()
    {
        $fc = func_new("FlexyCompiler");
        $str = 'var1=var2.method(!nested_method(123,#constant#))&1';
        $this->assertEquals('$t->get(\'var1\')==$t->call(\'var2.method\',!($t->call(\'nested_method\',123,"constant")))&&1', $fc->flexyExpression($str));
    }

    function test_flexyAttribute()
    {
        $fc = func_new("FlexyCompiler");
        $this->assertEquals('$t->get(\'widget.dir\').\'/head.tpl\'', $fc->flexyAttribute("{widget.dir}/head.tpl"));
        $this->assertEquals('\'head.tpl\'', $fc->flexyAttribute("head.tpl"));
        $this->assertEquals('\'\'', $fc->flexyAttribute(""));
        $this->assertEquals('\'"\\\'\'', $fc->flexyAttribute("&quot;'"));
    }
    function test_widgetInitCode()
    {
        $fc = func_new("FlexyCompiler");
        $this->assertEquals('if($t->isInitRequired(array("name"=>\'asd\',"class"=>\'clazz\',"target"=>\'target\',"disabled"=>\'1\',))){'."\n".'$t->asd = func_new(\'clazz\');'."\n".'$t->asd->component =& $t;'."\n".'$t->widget->addWidget($t->asd);'."\n".'$t->set(\'asd.disabled\',\'1\');'."\n".'$t->call(\'asd.init\');'."\n".'}', $fc->widgetInitCode(array("name"=>"asd", "class"=>"clazz", "target"=>"target", "disabled"=>true)));
        $attrs = array("template" => "common/dialog.tpl");
        $fc->file = 'common/dialog.tpl';
        $fc->processWidgetAttrs($attrs);
        $this->assertEquals(<<<EOT
if(\$t->isInitRequired(array("template"=>'common/dialog.tpl',"name"=>'widget->_0',"class"=>'Widget',))){
\$t->widget->_0 = func_new('Widget');
\$t->widget->_0->component =& \$t;
\$t->widget->addWidget(\$t->widget->_0);
\$t->set('widget._0.template','common/dialog.tpl');
\$t->call('widget._0.init');
}
EOT
, $fc->widgetInitCode($attrs));
        $attrs = array("template" => "{widget.dir}/dialog.tpl");
        $fc->processWidgetAttrs($attrs);
        $this->assertEquals(<<<EOT
if(\$t->isInitRequired(array("template"=>\$t->get('widget.dir').'/dialog.tpl',"name"=>'widget->_1',"class"=>'Widget',))){
\$t->widget->_1 = func_new('Widget');
\$t->widget->_1->component =& \$t;
\$t->widget->addWidget(\$t->widget->_1);
\$t->set('widget._1.template',\$t->get('widget.dir').'/dialog.tpl');
\$t->call('widget._1.init');
}
EOT
, $fc->widgetInitCode($attrs));
    }
}

$suite = new PHPUnit_TestSuite("FlexyCompilerTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
