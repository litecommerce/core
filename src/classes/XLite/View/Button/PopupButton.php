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

namespace XLite\View\Button;

/**
 * Button to use with popup
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class PopupButton extends \XLite\View\Button\AButton
{
    /**
     * Several inner constants 
     */
    const TEMPLATE  = 'button/popup_button.tpl';
    const CSS_CLASS = 'popup-button';
    const JS_SCRIPT = 'button/popup_button.js';
    const URLParams = 'url_params';
    const POPUP_CSS_FILE = 'button/popup.css';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return self::TEMPLATE;
    }

    /**
     * Return content for popup button
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getButtonContent();

    /**
     * Return URL parameters to use in AJAX popup
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function prepareURLParams();

    /**
     * Return array of URL params for JS 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getURLParams()
    {
        return array(
            self::URLParams => $this->prepareURLParams(),
        );
    }

    /** 
     * getClass 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getClass()
    {   
        return self::CSS_CLASS;
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
        $list = parent::getJSFiles();

        $list[] = self::JS_SCRIPT;

        return $list;
    }   

    /**
     * Return CSS files list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = self::POPUP_CSS_FILE;

        return $list;
    }

    /**  
     * Register files from common repository
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCommonFiles()
    {    
        $list = parent::getCommonFiles();

        // popup button is using several specific popup JS
        $list['js'][] = 'js/core.popup.js';
        $list['js'][] = 'js/jquery.blockUI.js';

        return $list;
    }    
}
