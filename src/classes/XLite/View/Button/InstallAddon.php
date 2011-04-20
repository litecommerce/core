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
 * Install addon popup button 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class InstallAddon extends \XLite\View\Button\PopupButton
{
    /**
     * Button label
     */
    const INSTALL_ADDON_LABEL = 'Install';

    /**
     * Widget class to show
     */
    const INSTALL_ADDON_WIDGET = 'XLite\View\ModulesManager\ModuleLicense';

    const PARAM_MODULEID = 'moduleId';

    /** 
     * Return content for popup button
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getButtonContent() 
    {
        return self::INSTALL_ADDON_LABEL;
    }

    /** 
     * Return URL parameters to use in AJAX popup
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function prepareURLParams()
    {
        return array(
            'target'    => \XLite\View\ModulesManager\ModuleLicense::MODULE_LICENSE_TARGET,
            'action'    => 'view',
            'widget'    => self::INSTALL_ADDON_WIDGET,
            'moduleId'  => $this->getParam(self::PARAM_MODULEID),
        );
    }

    /** 
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {   
        $list = parent::getCSSFiles();
        // TODO must be taken from LICENSE module widget!!!
        $list[] = 'modules_manager/license/style.css';

        return $list;
    }   


    /** 
     * Register JS files
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {   
        $list = parent::getJSFiles();

        // TODO must be taken from LICENSE module widget
        $list[] = 'modules_manager/license/js/switch-button.js';

        return $list;
    }   


    /**
     * Define widgets parameters
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {   
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_MODULEID => new \XLite\Model\WidgetParam\String('ModuleId', '', true),
        );  
    }
}
