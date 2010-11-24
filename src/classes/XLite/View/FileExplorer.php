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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;


/**
 * File explorer dialog
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class FileExplorer extends \XLite\View\ColumnList
{
    /*
     * Widget parameters names
     */
    const PARAM_FORM_SELECTION_NAME = 'formSelectionName';
    const PARAM_MODIFIER = 'modifier';
    const PARAM_DSN = 'dsn';


    /**
     * locale 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $locale = null;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/file_explorer.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_FORM_SELECTION_NAME => new \XLite\Model\WidgetParam\String('Form selection name', ''),
            self::PARAM_MODIFIER            => new \XLite\Model\WidgetParam\String('Modifier', null),
            self::PARAM_DSN                 => new \XLite\Model\WidgetParam\String('DSN', null),
        );
    }

    /**
     * getLocale 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocale()
    {
        if (is_null($this->locale)) {
            $this->locale = \XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }

        return $this->locale;
    }

    /**
     * getPath 
     * 
     * @param string $zone ____param_comment____ OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPath($zone = 'default')
    {
        return 'skins' . LC_DS . $zone . LC_DS . $this->getLocale();
    }

    /**
     * getData 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData()
    {
        $result = null;

        if (!is_null($this->children)) {

            // search for cached result
            $result = $this->children;

        } elseif (!is_null($this->getParam(self::PARAM_DSN))) {

            // if dialog DSN is specified, use it to get the list of files to edit
            $result = $this->get($this->getParam(self::PARAM_DSN));

        } elseif (is_null($this->getParam(self::PARAM_MODIFIER))) {

            // check for zone otherwise
            $result = array();

        } else {
            $modifier = $this->getParam(self::PARAM_MODIFIER);

            $zone = isset(\XLite\Core\Request::getInstance()->$modifier)
                ? \XLite\Core\Request::getInstance()->$modifier
                : 'default';
            $path = $this->getPath($zone);

            // check for node
            if (isset(\XLite\Core\Request::getInstance()->node)) {
                $path = \XLite\Core\Request::getInstance()->node;
            }

            $childrenDirs = array();
            $childrenFiles = array();

            $handle = @opendir($path);
            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    if ($file{0} != '.') {
                        if (is_file($path . '/' . $file)) {
                            $childrenFiles[] = $file;

                        } else {
                            $childrenDirs[] = $file;
                        }
                    }
                }
                closedir($handle);
            }

            array_multisort($childrenDirs);
            $this->dir_count = count($childrenDirs);
            array_multisort($childrenFiles);
            $children = array_merge($childrenDirs, $childrenFiles);

            for ($i = 0; count($children) > $i; $i++) {
                $children[$i] = new \XLite\Model\FileNode($path . '/' . $children[$i]);
            }

            if (preg_match('/^(.*\/.*\/.*)\/[^\/]+$/', $path, $matches)) {
                $parNode = new \XLite\Model\FileNode($matches[1]);
                $parNode->name = '..';
                array_unshift($children, $parNode);
            }

            $this->children = $children;

            $result = $this->children;
        }

        return $result;
    }
}

