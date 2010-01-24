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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Flexy compiler.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_FlexyCompiler extends XLite_Base
{
	protected $_internalDisplayCode = false;
	protected $_internalInitCode = false;

	protected $initializedWidgets = array();

	// protected $mainAttributes = array('module', 'target');
	
	public $source; // Flexy template source code	
	public $phpcode;	
    public $phpinitcode;	
	public $file = ''; // file name	
    public $widgetCounter = 0;	
    public $substitutionStart = array();	
	public $substitutionEnd = array();	
	public $substitutionValue = array();

	function parse()
	{
		$this->offset = 0;
		$this->stack = array();
		$this->tokens = array();
        $this->widgetNames = array();
		$this->errorMessage = '';
        $this->phpinitcode = "<?php\n";
		$this->html();
		$this->substitutionStart = array();
		$this->substitutionEnd = array();
		$this->substitutionValue = array();
		$this->postprocess();
        $this->phpinitcode .= " ?>";
	}
	function savePosition($offs = 0)
	{
		array_push($this->stack, $this->offset+$offs);
		array_push($this->stack, count($this->tokens));
	}
	function rollback()
	{
		$count = array_pop($this->stack);
		array_splice($this->tokens, $count);
		$this->offset = array_pop($this->stack);
		return false;
	}
	function commit()
	{
		array_pop($this->stack);
		array_pop($this->stack);
		return true;
	}
	function startOffset()
	{
		return $this->stack[count($this->stack)-2];
	}
	function error($message)
	{
		// count \n
		$line = $col = 1;
		for ($i=0; $i < $this->offset; $i++) {
			if (substr($this->source, $i, 1) == "\n") {
				$line ++;
				$col=0;
			}
			$col++;
		}
		$this->_die("File $this->file, line $line, col $col: $message");
	}
	function isEos()
	{
		return $this->offset >= strlen($this->source) || $this->errorMessage;
	}
	// html ::= ([text] tag | [text] comment | [text] flexy)* [text]
	function html()
	{
		while ($this->phptag() || $this->tag() || $this->comment() || $this->flexyComment() || $this->flexy() || $this->anyChar()) {
		}
		return true;
	}
	// tag ::= open-tag | close-tag | open-close-tag
	// open-tag ::= '<' tagname (space+ attribute-definition)* space* '>'
	// open-close-tag ::= '<' tagname (space+ attribute-definition)* space* '/>'
	// close-tag ::= '</' tagname  '>'
	function tag()
	{
		if ($this->char('<')) {
		    $this->savePosition(-1);
			$n = count($this->tokens);
			if ($this->char('/')) {
				$this->tokens[] = array("type" => "close-tag", "start" => $this->startOffset());
				if (!$this->tagname()) return $this->rollback();
				if (!$this->char('>')) return $this->rollback();
			} else {
				$this->tokens[] = array("type" => "tag", "start" => $this->startOffset());
				if (!$this->tagname()) return $this->rollback();
				while($this->space() && $this->attribute_definition()) {
				}
				if ($this->char('/')) {
					$this->tokens[$n]["type"] = "open-close-tag";
				}
				if (!$this->char('>')) return $this->rollback();
			}
			$this->tokens[$n]["end"] = $this->offset;
			return $this->commit();
		}
	}
	// attribute-definition ::= attributename [ '=' attribute-value ]
	// attribute-value ::= '\'' attribute-text '\'' | '"' attribute-text '"' | [^ \t\n\r/>]+
	function attribute_definition()
	{
		$this->savePosition();
		$i = count($this->tokens);
		$this->tokens[] = array("type" => "attribute", "start" => $this->offset);
		if ($this->attributename()) {
			if ($this->char('=')) {
				// read attribute value
				$n = count($this->tokens);
				if ($this->char('\'')) {
					$this->tokens[] = array("type" => "attribute-value", "start" => $this->offset);
					while (!$this->char('\'') && !$this->isEos()) {
						$this->attribute_text();
					}
					$this->tokens[$n]["end"] = $this->offset-1;
				} else if ($this->char('"')) {
					$this->tokens[] = array("type" => "attribute-value", "start" => $this->offset);
					while (!$this->char('"') && !$this->isEos()) {
						$this->attribute_text();
					}
					$this->tokens[$n]["end"] = $this->offset-1;
				} else {
					$this->tokens[] = array("type" => "attribute-value", "start" => $this->offset);
					while ($this->notChars(" \t\n\r/>") && !$this->isEos()) {
					}
					$this->tokens[$n]["end"] = $this->offset;
				}
				if ($this->isEos()) { // unexpected end of file
					return $this->error("unexpected end of file");
				}
			}
			$this->tokens[$i]["end"] = $this->offset;
			return $this->commit();
		} else {
			return $this->rollback();
		}
	}
	function attribute_text()
	{
		if ($this->char('{')) { // skip till closing }
			while (!$this->char('}') && $this->anyChar()) {
			}
			return true;
		} else {
			return $this->anyChar();
		}
	}
	// comment ::= '<!--' text '-->'
	function comment()
	{
        if ($this->offset<strlen($this->source) && substr($this->source, $this->offset, 1) == '<') {
            if (substr($this->source, $this->offset, 4) == '<!--') {
                $pos = strpos($this->source, '-->', $this->offset+4);
                if ($pos===FALSE) {
                    return $this->error("Comment is not closed with -->");
                }
                $this->offset = $pos+3;
                return true;
            }
        }
		return false;
	}

	// php tag ::= '< ?' php code '? >'
	function phptag()
	{
        if ($this->offset<strlen($this->source) && substr($this->source, $this->offset, 1) == '<') {
            if (substr($this->source, $this->offset, 2) == '<?') {
                $this->_die("&lt;?php&gt; tags are not allowed in templates");
            }
        }
		return false;
	}


	// flexy ::= '{' flexy-text '}'
	function flexy()
	{
		if ($this->char('{')) { // skip till closing }
		    $this->savePosition(-1);
			if ($this->notChars(" \t\n\r}")) {
				$this->tokens[] = array("type"=>"flexy", "start" => $this->offset-2);
				while (!$this->char('}')) {
					if (!$this->anyChar()) {
						$this->error("No closing }");
					}
				}
				$this->tokens[count($this->tokens)-1]["end"] = $this->offset;
				return $this->commit();
			} else {
				return $this->rollback();
			}
		}
	}

	function flexyComment()
	{
		if ($this->offset<strlen($this->source) && substr($this->source, $this->offset, 1) == '{') {
			if (substr($this->source, $this->offset, 2) == '{*') {
				$this->tokens[] = array("type"=>"flexy", "start" => $this->offset);
				$pos = strpos($this->source, '*}', $this->offset + 2);
				if ($pos===FALSE) {
					return $this->error("Comment is not closed with *}");
				}
				$this->offset = $pos + 2;
				$this->tokens[count($this->tokens)-1]["end"] = $this->offset;
				return true;
			}
		}
		return false;

	}

	// space ::= ' ' | '\t' | '\n' | '\r'
	function space()
	{
		$result = false;
		while ($this->char(' ') || $this->char("\n") || $this->char("\r") || $this->char("\t")) {
			$result = true;
		}
		return $result;
	}
	function char($c)
	{
		if (strlen($this->source) > $this->offset && substr($this->source, $this->offset, 1) == $c) {
			$this->offset ++;
			return true;
		}
		return false;
	}
	function notChars($str)
	{
		if (strpos($str, substr($this->source, $this->offset, 1)) === false) {
			$this->offset ++;
			return true;
		}
		return false;
	}
	function anyChar()
	{
		if ($this->isEos()) return false;
		$this->offset ++;
		return true;
	}
	function tagname()
	{
		$tagname = $c = '';
		do {
			$tagname .= $c;
			$c = substr($this->source, $this->offset++, 1);
		} while ($c >= 'a' && $c <= 'z' || $c >= 'A' && $c <= 'Z' || $c >= '0' && $c <= '9' || $c == '_' || $c == ':' || $c=='-');
		$this->offset--;
		if (strlen($tagname)) {
			$this->tokens[count($this->tokens)-1]["name"] = $tagname;
			return true;
		}
		return false;
	}
	function attributename()
	{
		return $this->tagname();
	}

	// Flexy substitutions
	function postprocess()
	{
		for ($i=0; $i<count($this->tokens); $i++) {
			$token = $this->tokens[$i];

			$this->attachFormID($i);

			if ($token["type"] == "tag" || $token["type"] == "open-close-tag") {

				if ($this->findAttr($i+1, 'if', $pos)) {
                    if ($this->findClosingTag($i, $pos1)) {
                        $expr = $this->flexyCondition($this->getTokenText($pos+1));
                        $this->subst($token['start'], 0, '<?php if (' . $expr . '): ?>');
                        $this->subst($this->tokens[$pos]['start'], $this->tokens[$pos]['end'], '');
                        $this->subst($this->tokens[$pos1]['end']-1, $this->tokens[$pos1]['end'], '><?php endif; ?>');
                    }
                } elseif ($this->findAttr($i+1, "iff", $pos)) {
                    $expr = $this->flexyCondition($this->getTokenText($pos+1));
                    $this->subst($token["start"], 0, "<?php if($expr){?>");
                    $this->subst($this->tokens[$pos]["start"], $this->tokens[$pos]["end"], '');
                    $this->subst($this->tokens[$i]["end"]-1, $this->tokens[$i]["end"], "><?php }?>");
                } else if ($this->findAttr($i+1, "foreach", $pos)) {
                    if ($this->findClosingTag($i, $pos1)) {
                        list($expr,$k,$forvar) = $this->flexyForeach($this->getTokenText($pos+1));
                        $exprNumber = "$forvar"."ArraySize";
                        $exprCounter = "$forvar"."ArrayPointer";
                        $this->subst($token["start"], 0, "<?php \$$forvar = isset(\$t->$forvar) ? \$t->$forvar : null; \$_foreach_var = $expr; if (!is_null(\$_foreach_var)) { \$t->$exprNumber=count(\$_foreach_var); \$t->$exprCounter=0; } if (!is_null(\$_foreach_var)) foreach(\$_foreach_var as $k){ \$t->$exprCounter++; ?>");
                        $this->subst($this->tokens[$pos]["start"], $this->tokens[$pos]["end"], '');
                        $this->subst($this->tokens[$pos1]["end"]-1, $this->tokens[$pos1]["end"], "><?php } \$t->$forvar = \$$forvar; ?>");
                    } else {
                        $this->error("No closing tag for foreach");
                    }
                }

				if ($this->findAttr($i+1, "selected", $pos)) {
					if (isset($this->tokens[$pos+1]["type"]) && $this->tokens[$pos+1]["type"] == "attribute-value") {
						$expr = $this->flexyCondition($this->getTokenText($pos+1));
						$this->subst(
                            $this->tokens[$pos]["start"], 
                            $this->tokens[$pos]["end"], 
                            "<?php if($expr) echo 'selected';?>");
					}
				}
				if ($this->findAttr($i+1, "checked", $pos)) {
					if (isset($this->tokens[$pos+1]["type"]) && $this->tokens[$pos+1]["type"] == "attribute-value") {
						$expr = $this->flexyCondition($this->getTokenText($pos+1));
						$this->subst($this->tokens[$pos]["start"], $this->tokens[$pos]["end"], "<?php if($expr) echo 'checked';?>");
					}
				}

                if (!strcasecmp($token["name"], "widget")) {
                    $attrs = array();
                    // widget display code
                    while(++$i<count($this->tokens)) {
                        $token1 = $this->tokens[$i];
                        if ($token1["type"] == "attribute") {
                            $attr = $token1["name"];
                            $attrs[$attr] = true;
                        } else if ($token1["type"] == "attribute-value") {
                            $attrs[$attr] = $this->getTokenText($i);
                        } else {
                            $i--;
                            break;
                        }
                    }

                    list($target, $module, $name) = $this->processWidgetAttrs($attrs);
					$this->phpinitcode .= $this->widgetInitCode($attrs, $target, $module, $name);
					$this->subst($token["start"], $token["end"]-1, "<?php " . $this->widgetDisplayCode($attrs, $name) . " ?");
                }
			}
            if ($token["type"] == "flexy") {
				$expr = $this->flexyEcho($this->getTokenText($i));
				$this->subst($token["start"], $token["end"], $expr);
			} else if ($token["type"] == "attribute") {
				if (!strcasecmp($token["name"], "src") || !strcasecmp($token["name"], "background")) {
					if (list ($start, $end, $replacement) = $this->urlRewrite($this->getTokenText($i+1))) {
						$this->subst($this->tokens[$i+1]["start"]+$start, $this->tokens[$i+1]["start"]+$end, $replacement);
					}
				}
			} else if ($token["type"] == "attribute-value") {
				$str = $this->getTokenText($i);
				// find all {...}
				$pos = 0;
				while (($pos = strpos($str, "{", $pos)) !== false) {
					$pos1 = strpos($str, "}", $pos);
					if ($pos1 !== false) {
						$echo = $this->flexyEcho(substr($str, $pos, $pos1-$pos));
						$this->subst($token["start"]+$pos, $token["start"]+$pos1+1, $echo);
					} else {
						break;
					}
					$pos = $pos1;
				}
			}
		}
		$this->phpcode = $this->substitute();
	}

    function processWidgetAttrs(&$attrs)
    {
		$target = isset($attrs['target']) ? $attrs['target'] : null;
		$module = isset($attrs['module']) ? $attrs['module'] : null;

		unset($attrs['target']);
		unset($attrs['module']);

		$name = isset($attrs['name']) ? $attrs['name'] : 'widget->_' . $this->widgetCounter++;
		unset($attrs['name']);

        if (!isset($attrs['class'])) {
            $attrs['class'] = 'XLite_View_Abstract';
        }

		if (isset($attrs['if'])) {
			$attrs['IF'] = $attrs['if'];
			unset($attrs['if']);
		}

		return array($target, $module, $name);
    }

    function widgetDisplayCode($attrs, $name)
    {
		return 'isset($t->' . $name . ') && $t->' . $name . '->display();';
    }

    function widgetInitCode(array $attrs, $target, $module, $name)
    {
		$result = '';

		if (
			!isset($this->initializedWidgets[$name]) 
			&& (is_null($module) || XLite_Model_ModulesManager::getInstance()->isActiveModule($module))
		) {

			$intend = array('main' => '', 'cnd' => '');

			if ($checkTarget = !is_null($target)) {

				$result .= 'if ($t->isInitRequired(array(\'' 
						. str_replace(',', '\',\'', preg_replace('/[^\w,]+/', '', $target)) . '\'))):' . "\n";

				$intend['main'] .= '  ';
			}

			$condition = array();
			foreach (array('IF', 'visible') as $attr) {
				if (isset($attrs[$attr])) {
					$condition[] = $this->flexyCondition($attrs[$attr]);
				}
			}
			unset($attrs['IF']);

			if ($checkCondition = !empty($condition)) {
				$intend['cnd'] = $intend['main'];
				$result .= $intend['cnd'] . 'if (' . implode(' && ', $condition) . '):' . "\n";
				$intend['main'] .= '  ';
			}

            $class = $attrs['class'];

            $result .= $intend['main'] . '$t->' . $name . ' = new ' . $class . '();' . "\n";
            $result .= $intend['main'] . '$t->' . $name . '->component = $t;' . "\n";
            $result .= $intend['main'] . '$t->widget->addWidget($t->' . $name . ');' . "\n";
            if (class_exists($class, false) && is_subclass_of($class, 'XLite_View')) {
                $result .= $intend['main'] . '$t->addComponent($t->' . $name . ');' . "\n";
            }

			if (!empty($attrs)) {
                $result .= $intend['main'] . '$t->' . $name . '->setAttributes(array(';
                foreach ($attrs as $key => $value) {
                    $result .= '\'' . $key . '\'=>' . $this->flexyAttribute($value) . ',';
                }
                $result .= '));' . "\n";
            }

			if (isset($attrs['hidden'])) {
                $result .= ' $t->' . $name . '->set(\'visible\', false);' . "\n";
            }

            $result .= $intend['main'] . '$t->' . $name . '->init();' . "\n" 
					. ($checkCondition ? $intend['cnd'] . 'endif;' . "\n" : '') 
					. ($checkTarget ? 'endif;' . "\n" : '') . "\n";

			$this->initializedWidgets[$name] = true;
		}

        return $result;
    }
    
    function substitute()
    {
		// sort substitutions
		array_multisort($this->substitutionStart, $this->substitutionEnd, $this->substitutionValue);
		$lastEnd = 0;
		$result = '';
		for ($i=0; $i<count($this->substitutionStart); $i++) {
//			print  $this->substitutionStart[$i] ." replace " .  substr($this->source, $this->substitutionStart[$i], $this->substitutionEnd[$i]-$this->substitutionStart[$i]) . " with " . $this->substitutionValue[$i];
			if ($lastEnd <= $this->substitutionStart[$i]) {
//			print " OK\n";
				$result .= substr($this->source, $lastEnd, $this->substitutionStart[$i]-$lastEnd);
				$result .= $this->substitutionValue[$i];
				$lastEnd = $this->substitutionEnd[$i];
			}
		}
		$result .= substr($this->source, $lastEnd);
        return $result;
    }

	function urlRewrite($url)
	{
		$urls = explode(';', $this->url_rewrite);
		foreach($urls as $u) {
			list ($find, $replace) = explode(':', $u);
			if (substr($url, 0, strlen($find)) == $find) {
				return array(0, strlen($find), $replace);
			}
		}
		return false;
	}
	function subst($start, $end, $value)
	{
		if ($end==0) $end = $start;
		$this->substitutionStart[] = $start;
		$this->substitutionEnd[] = $end;
		$this->substitutionValue[] = $value;
	}
	function findAttr($offset, $attr, &$pos)
	{
		$pos = $offset;
		while($pos<count($this->tokens) && ($this->tokens[$pos]["type"] == "attribute" ||$this->tokens[$pos]["type"] == "attribute-value")) {
			if ($this->tokens[$pos]["type"] == "attribute"  && !strcasecmp($this->tokens[$pos]["name"], $attr)) {
				return true;
			}
			$pos ++;
		}
		return false;
	}
	function findClosingTag($i, &$pos)
	{
		$pos = $i;
		$stack = array();
		while ($pos<count($this->tokens)) {
			if ($this->tokens[$pos]["type"] == "tag" || $this->tokens[$pos]["type"] == "open-close-tag") {
				array_push($stack,$this->tokens[$pos]["name"]);
			}
			if ($this->tokens[$pos]["type"] == "close-tag" || $this->tokens[$pos]["type"] == "open-close-tag") {
				$k = count($stack)-1;
				while ($k >= 0 && strcasecmp($stack[$k], $this->tokens[$pos]["name"])) {
					$k--;
				}
				if ($k == 0) return true;
				if ($k >= 0) {
					// opening tag is found
					array_splice($stack, $k);
				}
			}
			$pos ++;
		}
		return false;
	}
	function getTokenText($n)
	{
		$t = $this->tokens[$n];
		$this->offset = $t["start"];
		return substr($this->source, $t["start"], $t["end"] - $t["start"]);
	}
	function flexyCondition($str)
	{
		$str = $this->removeBraces($str);
		$this->condition = '';
		if (substr($str, 0, 1) == '!') {
			$str = substr($str,1);
			$res = $this->flexyExpression($str);
			$not = "!";
		} else {
			$res = $this->flexyExpression($str);
			$not = "";
		}
		if ($this->condition) {
			$res = "$this->condition && $res";
		}
		if ($not) {
			return "!($res)";
		} else {
			return $res;
		}
	}
	function flexyEcho($str)
	{
		if (substr($str, 0, 9) == '{foreach:') {
			list($expr,$k,$forvar) = $this->flexyForeach(substr($str, 9));
			$exprNumber = "$forvar"."ArraySize";
			$exprCounter = "$forvar"."ArrayPointer";
			return "<?php \$_foreach_var = $expr; if (!is_null(\$_foreach_var)) { \$t->$exprNumber=count(\$_foreach_var); \$t->$exprCounter=0; } if (!is_null(\$_foreach_var)) foreach(\$_foreach_var as $k){ \$t->$exprCounter++; ?>";
		}
		if (substr($str, 0, 4) == '{if:') {
			$expr = $this->flexyCondition(substr($str, 4));
			return "<?php if($expr){?>";
		}
		if ($str == '{end:}') {
			return "<?php }?>";
		}
		if ($str == '{else:}') {
			return "<?php }else{ ?>";
		}
		if (substr($str, 0, 2) == "{*") {
			$str = '';
			return "";
		}
		$this->condition = '';
		$expr = $this->flexyExpression($str);
		switch ($str) {
			case ":h":	// will display variable "as is"
			break;
			case "":	// default display
				$expr = "htmlspecialchars($expr)";
			break;
			case ":r":
				$expr = "str_replace('\"', '&quot;',$expr)";
			break;
			case ":u":
				$expr = "urlencode($expr)";
			break;
			case ":t":
				$expr = "htmlentities($expr)";
			break;
			default:
				$this->error("Unknown modifier '$str'");
			break;
		}

		if ($this->condition) return "<?php if($this->condition) echo $expr;?>";
		return "<?php echo $expr;?>";
	}
	function flexyExpression(&$str)
	{
		$str = $this->removeBraces($str);
        if (substr($str, 0, 1) == '!') { // NOT
            $str = substr($str, 1);
            return '!(' . $this->flexyExpression($str) . ')';
        }
        $result = $this->flexySimpleExpression($str);

		// var_dump($result);

        if (substr($str, 0, 1) == '=') { // comparision
            $str = substr($str, 1);
            $result .= '==' . $this->flexyExpression($str);
        }
        if (substr($str, 0, 1) == '&') { // AND
            $str = substr($str, 1);
            $result .= '&&' . $this->flexyExpression($str);
        }
        if (substr($str, 0, 1) == '|') { // OR
            $str = substr($str, 1);
            $result .= '||' . $this->flexyExpression($str);
        }
        return $result;
	}

    function flexySimpleExpression(&$str)
    {
		if (substr($str, 0, 1) == "#") {
			// find next #
			$pos = strpos($str, "#", 1);
			if ($pos===false) $this->error("No closing #");
			$result = '"' . substr($str, 1, $pos-1) . '"';
			$str = substr($str, $pos+1);
			return $result;
		}
		if (substr($str, 0, 1)>='0' && substr($str, 0, 1) <='9' || substr($str, 0, 1) == '-' || substr($str, 0, 1) == '.') { // numeric constant
			$len = strspn($str, '0123456789-.');
			$result = substr($str, 0, $len);
			$str = substr($str, $len);
			return $result;
		}

		$len = strcspn($str, '=&|,)(:');
		if ($len < strlen($str) && substr($str, $len, 1) == '(') { // method call

			$token  = substr($str, 0, $len);
			$method = (false !== ($dotPos = strrpos($token, '.'))) ? substr($token, $dotPos + 1) : $token;

			$result = '$t->' . ((false === $dotPos) ? '' : 'get(\'' . substr($str, 0, $dotPos) . '\')->') . $method;

			$str = substr($str, $len);
			$params = array();

            if (substr($str, 1, 1) != ')') {
    			while (substr($str, 0, 1) != ')') {
	    			$str = substr($str,1); // eat , or (
		    		if (strlen($str) == 0) $this->error("No closing )");
			    	$params[] = $this->flexyExpression($str);
    			}
  	    	    $str = substr($str,1); // eat )
            } else {
                $str = substr($str,2); // eat ()
            }
			return $result . '(' . implode(',', $params) . ')';
		}
        if ($len) {
    		// field
            $result = '$t->get(\'' . substr($str, 0, $len) . "')";
        } else {
            $result = "";
        }
        $str = substr($str, $len);
        return $result;
    }

    function flexyAttribute($str)
    {
        if ($str === "") {
            return "''";
        }
        // find all {..} in $str and replace with flexyExpression()
        $result = "";
        $find = array("'", '&quot;');
        $replace = array("\'", '"');
        while(strlen($str)) {
            if (substr($str, 0, 1) == "{") {
                $pos = strpos($str, "}");
                if ($pos === false) {
                    $this->error("} not found");
                    return "";
                }
				$tmp = substr($str, 0, $pos+1);
                $s = $this->flexyExpression($tmp);
                $str = substr($str, $pos+1);
            } else {
                $pos = strpos($str, "{");
                if ($pos === false) {
                    $pos = strlen($str);
                }
                $s = "'" . str_replace($find, $replace, substr($str, 0, $pos)) . "'";
                $str = substr($str, $pos);
            }
            if ($result === "") {
                $result = $s;
            } else {
                $result .= "." . $s; // catenation
            }
        }
        return $result;
    }

	function flexyForeach($str)
	{
		$expr = $this->flexyExpression($str);
		if (substr($str, 0, 1) != ',') {
			$this->error("No comma in foreach expression");
		}
		$str = substr($str, 1);
		$list = explode(",", $str);
		if (count($list) == 2) {
			list ($k, $v) = $list;
            $forvar = $v;
			$key = "\$t->$k => \$t->$v";
		} else {
			$key = "\$t->$list[0]";
            $forvar = $list[0];
		}
		return array($expr, $key, $forvar);
	}
	function removeBraces($str)
	{
		if (substr($str, 0, 1) == '{') {
			$str = substr($str, 1);
		}
		if ($str{strlen($str)-1} == '}') {
			$str = substr($str, 0, strlen($str)-1);
		}
		return $str;
	}

	function getXliteFormIDText()
	{
		if (!isset($this->xlite->_xlite_form_id_text) || is_null($this->xlite->_xlite_form_id_text)) {
			$this->xlite->_xlite_form_id_text = $this->flexyEcho("{xliteFormID}");
		}
		return $this->xlite->_xlite_form_id_text;
	}

	function attachFormID($token_index)
	{
		if (!$this->xlite->is("adminZone")) return;

		$token = $this->tokens[$token_index];
		$token['name'] = empty($token['name']) ? '' : strtolower($token['name']);

		// sign each form with generated form_id
		if (($token["type"] == "tag") && ($token['name'] == 'form')) {
			$gen_form_id = $this->getXliteFormIDText();
			$this->subst($token["end"], 0, "<input type='hidden' name='xlite_form_id' value=\"$gen_form_id\" />");
		}

		// attach form_id to all links inside attributes (in case they contain javascript links)
		if ($token["type"] == "attribute-value") {
			$str = $this->getTokenText($token_index);
			$this->_addFormIdToActions($str, $token["start"]);
		}

		// attach form_id to all links inside scripts
		static $script_start = null;
		if (($token["type"] == "tag") && ($token['name'] == "script")) {
			$script_start = $token["end"];
		}
		if (($token["type"] == "close-tag") && ($token['name'] == "script") && (!is_null($script_start))) {
			$script_end = $token["start"];
			$script_body = substr($this->source, $script_start, $script_end-$script_start);
			$this->_addFormIdToActions($script_body, $script_start);
			$script_start = null;
		}
	}

	function _addFormIdToActions($text, $text_start)
	{
		$blocks = array();
		$search_text = "action=";
		$prev_pos = 0;
		while ($pos = strpos($text, $search_text, $prev_pos)) {
			$blocks[] = array(
				"start" => $prev_pos, 
				"end" => $pos + strlen($search_text),
				"body" => substr($text, $prev_pos, $pos + strlen($search_text)-$prev_pos)
			);
			$prev_pos = $pos + strlen($search_text);
		}

		foreach ($blocks as $block) {
			// exclude links to customer zone
			if (preg_match("/cart\.php/", $block["body"])) continue;

			if (!preg_match("/(\?|&)action=/", $block["body"], $matches)) continue;
			$action_text = $matches[0];
			$link_symbol = $matches[1];
			$pos = strpos($block["body"], $action_text);
			if ($pos !== false) {
				$gen_form_id = $this->getXliteFormIDText();
				$echo = $link_symbol."xlite_form_id=$gen_form_id&action=";
				$this->subst($text_start+$block["start"]+$pos, $text_start+$block["start"]+$pos+strlen($action_text), $echo);
			}
		}
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
