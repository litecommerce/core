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
 * Layoue manager
 * TODO[SINGLETON] - must extends \XLite\Model\the Base\Singleton
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Layout extends \XLite\Base
{
    public $skin = null;

    public $skinCustomer = null;

    public $locale = null;

    protected $path = null;

    protected $pathCustomer = null;

    /**
    * Skin templates list.
    *
    * @var Elements $elements Skin templates list.
    * @access private
    */    
    public $list = array();

    public function __construct()
    {
        foreach (array('skin', 'locale') as $name) {
            if (!isset($this->$name)) {
                $this->$name = \XLite::getInstance()->getOptions(array('skin_details', $name));
            }
        }

        $this->skinCustomer = \XLite::getInstance()->getOptions(array('skin_details', 'skin'));
    }

    /**
     * Return full URL by the skindir-related one
     *
     * @param string $url relative URL
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getSkinURL($url)
    {
        return $this->getPath() . $url;
    }

    /**
    * Adds layout template file for the specified widget
    *
    * @param string $widgetName The widget name
    * @param string $templateName The template file name
    * @access public
    */
    function addLayout($widgetName, $templateName)
    {
        $this->list[$widgetName] = $templateName;
    }

    /**
    * Returns the widget template file name for this layout.
    *
    * @param string $widgetName The name of widget
    * @access public
    * @return string The widget tamplate name
    */
    function getLayout($templateName)
    {
        if (isset($this->list[$templateName])) {
            $templateName = $this->list[$templateName];
        }

        return $this->get('path') . $templateName;
    }

    function hasLayout($widgetName)
    {
        return isset($this->list[$widgetName]);
    }
    
    /**
     * Returns the layout path
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getPath()
    {
        if (!isset($this->path)) {
            $this->path = sprintf('skins/%s/%s/', $this->skin, $this->locale);
        }

        return $this->path;
    }

    public function getPathCustomer()
    {
        if (!isset($this->pathCustomer)) {
            $this->pathCustomer = sprintf('skins/%s/%s/', $this->skinCustomer, $this->locale);
        }
        
        return $this->pathCustomer;
    }
    
    function getSkins($includeAdmin = false)
    {
        $list = array();
        $dir = 'skins';
        $dh = opendir($dir);

        if ($dh) {
            while (($file = readdir($dh)) !== false) {
                if (
                    is_dir($dir . LC_DS . $file)
                    && substr($file, 0, 1) != '.'
                    && ($file != 'admin' || $includeAdmin)
                    && $file != 'mail'
                    && $file != 'CVS'
                ) {
                    $list[] = $file;
                }
            }

            closedir($dh);
        }

        return $list;
    }

    function getLocales($skin)
    {
        $list = array();
        $dir = 'skins/' . $skin . '/';
        $dh = @opendir($dir);
        if ($dh) {
            while (($file = readdir($dh)) !== false) {
                if (
                    is_dir($dir . $file)
                    && substr($file, 0, 1) != '.'
                    && $file != 'CVS'
                ) {
                    $list[] = $file;
                }
            }

            closedir($dh);
        }

        return $list;
    }

}
