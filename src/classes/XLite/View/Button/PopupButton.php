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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Button;

/**
 * Button to use with popup
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class PopupButton extends \XLite\View\Button\AButton
{
    /**
     * Several inner constants 
     */
    const TEMPLATE       = 'button/popup_button.tpl';
    const CSS_CLASS      = 'popup-button';
    const JS_SCRIPT      = 'button/js/popup_button.js';
    const URLParams      = 'url_params';
    const POPUP_CSS_FILE = 'button/css/popup.css';


    /**
     * Return content for popup button
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getButtonContent();

    /**
     * Return URL parameters to use in AJAX popup
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function prepareURLParams();

    /**
     * Return array of URL params for JS 
     * 
     * @return array
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
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonFiles()
    {    
        $list = parent::getCommonFiles();

        // popup button is using several specific popup JS
        $list['js'][] = 'js/core.popup.js';

        return $list;
    }


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return self::TEMPLATE;
    }

    /** 
     * getClass 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClass()
    {   
        return self::CSS_CLASS;
    }   
}
