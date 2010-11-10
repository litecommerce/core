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
    /**
     * Repository paths constants 
     */
    const COMMON_REPOSITORY_PATH = 'common';
    const SKIN_REPOSITORY_PATH = 'skins';

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

    /**
     * __construct 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
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
     * Return full URL by the common repository-related one
     *
     * @param string $url relative URL
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getCommonRepositoryURL($url)
    {
        return $this->getCommonPath() . $url;
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

        return $this->getPath() . $templateName;
    }

    /**
     * hasLayout 
     * 
     * @param string $widgetName name of widget
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    function hasLayout($widgetName)
    {
        return isset($this->list[$widgetName]);
    }

    /**
     * getShortPath 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShortPath()
    {
        return $this->skin . '/' . $this->locale . '/';
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
        return self::SKIN_REPOSITORY_PATH . '/' . $this->getShortPath();
    }

    /**
     * Returns the layout path
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getCommonPath()
    {   
        return self::SKIN_REPOSITORY_PATH . '/' . self::COMMON_REPOSITORY_PATH . '/';
    }

    /**
     * Return customer path
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPathCustomer()
    {
        if (!isset($this->pathCustomer)) {
            $this->pathCustomer = sprintf(self::SKIN_REPOSITORY_PATH . '/%s/%s/', $this->skinCustomer, $this->locale);
        }
        
        return $this->pathCustomer;
    }
    
    function getSkins($includeAdmin = false)
    {
        $list = array();
        $dir = self::SKIN_REPOSITORY_PATH;
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
        $dir = self::SKIN_REPOSITORY_PATH . '/' . $skin . '/';
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
