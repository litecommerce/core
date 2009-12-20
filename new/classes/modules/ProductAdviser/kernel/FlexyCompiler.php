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

/**
* FlexyCompiler_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class FlexyCompiler_ProductAdviser extends FlexyCompiler
{
	function flexyExpression(&$str)
	{
		$result = parent::flexyExpression($str);

        if ($str{0} == '|') { // OR
            $str = substr($str, 1);
        	$result .= '||' . $this->flexyExpression($str);
        }

        return $result;
	}

    function flexySimpleExpression(&$str)
    {
		if ($str{0} == "#") {
			// find next #
			$pos = strpos($str, "#", 1);
			if ($pos===false) $this->error("No closing #");
			$result = '"' . substr($str, 1, $pos-1) . '"';
			$str = substr($str, $pos+1);
			return $result;
		}
		if ($str{0}>='0' && $str{0} <='9' || $str{0} == '-' || $str{0} == '.') { // numeric constant
			$len = strspn($str, '0123456789-.');
			$result = substr($str, 0, $len);
			$str = substr($str, $len);
			return $result;
		}
		$len = strcspn($str, '=&|,)(:');
		if ($len<strlen($str) && $str{$len} == '(') { // method call
			$result = '$t->call(\'' . substr($str, 0, $len) . '\'';
			$str = substr($str, $len);
            if ($str{1} != ')') {
    			while ($str{0} != ')') {
	    			$str = substr($str,1); // eat , or (
		    		if (strlen($str) == 0) $this->error("No closing )");
			    	$result .= ',' . $this->flexyExpression($str);
    			}
  	    	    $str = substr($str,1); // eat )
            } else {
                $str = substr($str,2); // eat ()
            }
			return $result . ")";
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

	function postprocess()
	{
		for ($i=0; $i<count($this->tokens); $i++) {
			$token = $this->tokens[$i];
			if ($token["type"] == "tag" || $token["type"] == "open-close-tag") {
				if ($this->findAttr($i+1, "if", $pos)) {
					if ($this->findClosingTag($i, $pos1)) {
						$expr = $this->flexyCondition($this->getTokenText($pos+1));
						$this->subst($token["start"], 0, "<?php if($expr){?>");
						$this->subst($this->tokens[$pos]["start"], $this->tokens[$pos]["end"], '');
						$this->subst($this->tokens[$pos1]["end"]-1, $this->tokens[$pos1]["end"], "><?php }?>");
					}
				} else if ($this->findAttr($i+1, "foreach", $pos)) {
					if ($this->findClosingTag($i, $pos1)) {
						list($expr,$k,$forvar) = $this->flexyForeach($this->getTokenText($pos+1));
                        $exprNumber = "$forvar"."ArraySize";
                        $exprCounter = "$forvar"."ArrayPointer";
						$this->subst($token["start"], 0, "<?php \$$forvar = \$t->$forvar; \$_foreach_var = $expr; if (!is_null(\$_foreach_var)) { \$t->$exprNumber=count(\$_foreach_var); \$t->$exprCounter=0; } if (!is_null(\$_foreach_var)) foreach(\$_foreach_var as $k){ \$t->$exprCounter++; ?>");
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
            }
            if ($token["type"] == "tag" || $token["type"] == "open-close-tag") {
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
                    $this->processWidgetAttrs($attrs);
                    $this->subst($token["start"], $token["end"]-1, "<?php " . $this->widgetDisplayCode($attrs) . " ?");
                    $this->phpinitcode .= $this->widgetInitCode($attrs);
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
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
