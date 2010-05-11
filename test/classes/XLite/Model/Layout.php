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
 * Layoue manager
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Layout extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * customerAreaSkin 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $customerAreaSkin = null;

    public $skin = null;

    public $locale = null;

    /**
    * Skin templates list.
    *
    * @var Elements $elements Skin templates list.
    * @access private
    */    
    public $list = array();    

    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    public function __construct()
    {
        foreach (array('skin', 'locale') as $name) {
            if (!isset($this->$name)) {
                $this->$name = XLite::getInstance()->getOptions(array('skin_details', $name));
            }
        }

        $this->customerAreaSkin = XLite::getInstance()->getOptions(array('skin_details', 'skin'));
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
     * @param bool $forCustomerArea flag
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getPath($forCustomerArea = false)
    {
        return sprintf(
            'skins/%s/%s/',
            $forCustomerArea ? $this->customerAreaSkin : $this->skin,
            $this->locale
        );
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
