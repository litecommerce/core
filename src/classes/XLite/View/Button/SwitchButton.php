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
 * Switch button (register two onclick callbacks JS functions)
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class SwitchButton extends \XLite\View\Button\AButton
{
    /**
     * Several inner constants 
     */
    const JS_SCRIPT = 'button/js/switch-button.js';
    const SWITCH_CSS_FILE = 'button/css/switch-button.css';

    /**
     * Widget parameters to use 
     */
    const PARAM_FIRST  = 'first';
    const PARAM_SECOND = 'second';

    /** 
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return array
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
        $list[] = self::SWITCH_CSS_FILE;

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
        return 'button/switch-button.tpl';
    }

    /** 
     * Define widget params 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_FIRST  => new \XLite\Model\WidgetParam\String('First callback', ''),
            self::PARAM_SECOND => new \XLite\Model\WidgetParam\String('Second callback', ''),
        );
    }

    /**
     * Return JS callbacks to use with onclick event
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCallbacks()
    {
        return array(
            'callbacks' => array (
                'first'  => $this->getParam(self::PARAM_FIRST),
                'second' => $this->getParam(self::PARAM_SECOND),
            ),
        );
    }
}
