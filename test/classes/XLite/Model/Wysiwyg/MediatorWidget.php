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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Wysiwyg_MediatorWidget extends XLite_View_Abstract 
{
    public $attributes = array();
    public $attributesEvaled = array();
    public $code = "";
    public $parent = false;
    public $editing = false;
    public $parentWidget = null;
    public $templateType = null;

    protected function getDefaultTemplate()
    {
        return null;
    }

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
                    $component = new $class();
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

    function getTemplateType()
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
}
