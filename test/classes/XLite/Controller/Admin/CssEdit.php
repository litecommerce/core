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
class XLite_Controller_Admin_CssEdit extends XLite_Controller_Admin_Abstract
{
	protected $locale = null;

	protected $zone = null;
	
    public $params = array('target', 'mode', 'style_id', 'status');

	protected function getStyleAttribute($attr, $index)
	{
		$style = $this->getEditor()->getStyle();

		return isset($style[$attr][$index]) ? $style[$attr][$index] : null;
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

    function getEditor()
    {
        if (isset($this->editor)) {
            return $this->editor;
        }
        $this->editor = new XLite_Model_CssEditor($this->get("cssFile"));
        return $this->editor;
    }

    function getCssFile()
    {
        $skin   = $this->get("zone");
        $locale = $this->get("locale");
        return "skins/$skin/$locale/style.css";
    }

    function action_save()
    {
        $editor = $this->get("editor");
        $editor->setComplex("style.style.$this->style_id", $this->style);

        $editor->save();
        $this->set("status", "updated");
    }
    
    function action_restore_default()
    {
        $editor = $this->get("editor");
        $editor->restoreDefault();
    }

    function css_style($index)
    {
		return $this->getStyleAttribute('style', $index);
    }

    function css_class($index)
    {
		return $this->getStyleAttribute('element', $index);
    }

    function css_comment($index)
    {
		return $this->getStyleAttribute('comment', $index);
    }

}
