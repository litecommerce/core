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

namespace XLite\Controller\Admin;

/**
 * CSS editor
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CssEdit extends AAdmin
{
    /**
     * Editor 
     * 
     * @var    \XLite\Modl\CssEditor
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $editor = null;

    /**
     * Locale code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $locale = null;

    /**
     * Zone code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $zone = null;
    
    public $params = array('target', 'mode', 'style_id', 'status');

    /**
     * Get style attribute 
     * 
     * @param string  $attr  Attribute type
     * @param integer $index Element index
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getStyleAttribute($attr, $index)
    {
        $style = $this->getEditor()->getStyle();

        return (isset($style[$attr]) && isset($style[$attr][$index]))
            ? $style[$attr][$index]
            : null;
    }

    /**
     * Get locale code
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLocale() 
    {
        if (is_null($this->locale)) {
            $this->locale = \XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }

        return $this->locale;
    }

    /**
     * Get skin zone code
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZone() 
    {
        if (is_null($this->zone)) {
            $this->zone = \XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        }

        return $this->zone;
    }

    /**
     * Get editor 
     * 
     * @return \XLite\Model\CssEditor
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEditor()
    {
        if (!isset($this->editor)) {
            $this->editor = new \XLite\Model\CssEditor($this->getCssFile());
        }

        return $this->editor;
    }

    /**
     * Get CSS file path
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCssFile()
    {
        return 'skins/' . $this->getZone() . '/' . $this->getLocale() . '/style.css';
    }

    /**
     * Save CSS
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSave()
    {
        $editor = $this->getEditor();
        if ($editor->setStyle($this->style_id, $this->style)) {
            $editor->save();
            \XLite\Core\TopMessage::getInstance()->add('CSS modifications saved');

        } else {
            \XLite\Core\TopMessage::getInstance()->add(
                sprintf('Failed to save CSS modifications. Check %s file permissions.', $this->getCssFile()),
                \XLite\Core\TopMessage::ERROR
            );
        }
    }
    
    /**
     * Restore default CSS
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionRestoreDefault()
    {
        $this->getEditor()->restoreDefault();
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
        $comment = $this->getStyleAttribute('comment', $index);
        if (preg_match('/@copyright/Ss', $comment)) {
            $comment = '';
        }

        return $comment;
    }

}
