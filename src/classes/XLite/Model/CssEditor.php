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
 * CSS block
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CssEditor extends \XLite\Base
{
    /**
     * RegExp result keys
     */
    const COMMENT = 1;
    const CLASS_NAME = 1;
    const STYLE = 2;

    /**
     * CSS file path
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cssFile = null;

    /**
     * Styles
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $style = null;

    /**
     * Constructor
     * 
     * @param mixed $cssFile ____param_comment____ OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($cssFile = null)
    {
        $this->set('cssFile', $cssFile);
    }

    /**
     * Get classes 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItems()
    {
        $items = array();

        $style = $this->getStyle();
        if (isset($style['style'])) {
            $items = array_keys($style['style']);
        }

        return $items;
    }

    /**
     * Get CSS file style data
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStyle()
    {
        if (is_null($this->style)) {
            $this->parseContent();
        }

        return $this->style;
    }

    /**
     * Set style 
     * 
     * @param integer $id    Class id
     * @param string  $style Class style
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setStyle($id, $style)
    {
        $result = false;

        $styles = $this->getStyle();
        if (isset($styles['style']) && isset($styles['style'][$id])) {
            $styles['style'][$id] = $style;
            $this->style = $styles;
            $result = true;
        }

        return $result;
    }

    /**
     * Parse content 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseContent()
    {
        $found = array();
        $content = @file_get_contents($this->get('cssFile'));
        $elements = explode('}', $content);

        foreach ($elements as $elm) {
            list($comment, $element, $style) = $this->parseClass($elm);
            if ($element) {
                $this->style['comment'][] = $comment;
                $this->style['element'][] = $element;
                $this->style['style'][]   = $style;
            }
        }
    }

    /**
     * Save
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function save()
    {
        $style = '';

        // update style
        $length = count($this->style['element']);
        for ($i = 0; $i < $length; $i++) {
            if (!empty($this->style['comment'][$i])) {
                if (preg_match('/(vim:.+)/Sm', $this->style['comment'][$i], $match)) {
                    $style .= '/* ' . $match[1] . '*/' . "\n\n";
                    $this->style['comment'][$i] = trim(str_replace($match[1], '', $this->style['comment'][$i]));
                }

                $comments = array_map('trim', explode("\n", $this->style['comment'][$i]));

                if (1 < count($comments)) {
                    $style .= '/**' . "\n" . ' * ' . implode("\n" . ' * ', $comments) . "\n" . ' */' . "\n";

                } else {
                    $style .= '/* ' . $this->style['comment'][$i] . ' */' . "\n";
                }
            }

            $classes = array_map('trim', explode(',', $this->style['element'][$i]));
            $style .= implode(',' . "\n" . '  ', $classes);
            $style .= 1 < count($classes) ? "\n" : ' ';
            $style .= '{' . "\n";
            $attributes = preg_grep('/^[a-zA-Z0-9\-]+:/Ss', array_map('trim', explode(';', $this->style['style'][$i])));
            $style .= '  ' . implode(';' . "\n" . '  ', $attributes) . ';' . "\n";
            $style .= '}' . "\n\n";
        }

        // save CSS file
        $file = $this->get('cssFile');
        $fp = @fopen($file, 'wb');
        if (!$fp) {
            $this->doDie('Write failed for file ' . $file . ': permission denied');
        }

        fwrite($fp, $style . "\n");
        fclose($fp);

        @chmod($file, get_filesystem_permissions(0666));
    }

    /**
     * Parse class 
     * 
     * @param string $class Class block
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseClass($class) 
    {
        $result = array('', '', '');

        if (preg_match('/\/\*(.+)\*\//Ss', $class, $found)) {
            $comment = trim($found[self::COMMENT]);
            $comment = preg_replace('/\/\*\*?/Ss', '', $comment);
            $comment = preg_replace('/\*\//Ss', '', $comment);
            $comment = trim(preg_replace('/^\s*\*[ ]*/Sm', '', $comment));
            $result[0] = $comment;

            $class = preg_replace('/\/\*(.*)\*\//Ss', '', $class);
        }

        if (
            preg_match('/([^\{]+)\{([^\}]+)/i', $class, $found)
            && isset($found[self::CLASS_NAME])
            && isset($found[self::STYLE])
        ) {

            $result[1] = trim($found[self::CLASS_NAME]);
            $result[2] = $this->removeSpaces(trim(strtr($found[self::STYLE], "\n", ' ')));
        }

        return $result;
    }

    /**
     * Restore default CSS file
     * FIXME - old code
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function restoreDefault()
    {
        /*$file = $this->get('cssFile');
        $orig = preg_replace('/^(skins)/', 'schemas/templates/' . $this->config->Skin->skin'', $file);

        if (!is_readable($orig)) {
            $this->doDie($orig . ': file not found');
        }

        if (is_writeable($file)) {
            unlink($file);
        }

        if (!copyFile($orig, $file)) {
            $this->doDie('unable to copy ' . $orig . ' to ' . $file);
        }*/
    }

    /**
     * Remove spaces 
     * 
     * @param string $source String
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function removeSpaces($source)
    {
        while (false !== strpos($source, '  ')) {
            $source = str_replace('  ', ' ', $source);
        }

        return $source;
    }
}
