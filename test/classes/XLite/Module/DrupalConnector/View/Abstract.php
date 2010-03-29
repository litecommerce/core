<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

/**
 * XLite_Module_DrupalConnector_View_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_DrupalConnector_View_Abstract extends XLite_View_Abstract implements XLite_Base_IDecorator
{
        /**
     * Relative path from web directory path to the XLite web directory 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected static $drupalRelativePath = null;

    /**
     * Return relative path from web directory path to the XLite web directory 
     * FIXME - it's the hack
     * TODO - check if there is a more convenient way to implement this
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected static function getDrupalRelativePath()
    {
        if (!isset(self::$drupalRelativePath)) {

            // FIXME - "base_path()" is a Drupal function declared in global scope
            $basePath  = explode('/', trim(base_path(), '/'));
            $xlitePath = explode('/', trim(XLite::getInstance()->getOptions(array('host_details', 'web_dir')), '/'));

            $basePathSize = count($basePath);
            $minPathSize  = min($basePathSize, count($xlitePath));

            for ($i = 0; $i < $minPathSize; $i++) {
                if ($basePath[$i] !== $xlitePath[$i]) {
                    break;
                } else {
                    unset($xlitePath[$i]);
                }
            }

            self::$drupalRelativePath = str_repeat('../', $basePathSize - $i) . join('/', $xlitePath) . '/';
        }

        return self::$drupalRelativePath;
    }

    /**
     * Add the relative part to the resources' URLs 
     * 
     * @param mixed $data data to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function modifyResourcePaths($data)
    {
        return is_array($data) ? array_map(array('self', __FUNCTION__), $data) : self::getDrupalRelativePath() . $data;
    }

    /**
     * Prepare resources list
     * 
     * @param mixed $data data to prepare
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected static function prepareResources($data)
    {
        $data = parent::prepareResources($data);

        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            $data = self::modifyResourcePaths($data);
        }

        return $data;
    }


    /**
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $result = parent::getJSFiles();

        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            $result[] = 'modules/DrupalConnector/drupal.js';
        }

        return $result;
    }
}

