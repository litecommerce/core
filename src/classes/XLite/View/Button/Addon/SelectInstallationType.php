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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\Button\Addon;

/**
 * Install addon popup button
 *
 */
class SelectInstallationType extends \XLite\View\Button\APopupButton
{
    /**
     * Widget param names
     */
    const PARAM_MODULEID = 'moduleId';

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules_manager/installation_type/css/style.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'button/js/select_installation_type.js';

        return $list;
    }

    /**
     * Return content for popup button
     *
     * @return string
     */
    protected function getDefaultLabel()
    {
        return 'Install';
    }

    /**
     * Return URL parameters to use in AJAX popup
     *
     * @return array
     */
    protected function prepareURLParams()
    {
        return array(
            'target'   => 'addon_install',
            'action'   => 'select_installation_type',
            'widget'   => '\XLite\View\ModulesManager\InstallationType',
            'moduleId' => $this->getParam(self::PARAM_MODULEID),
        );
    }

    /**
     * Define widgets parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_MODULEID => new \XLite\Model\WidgetParam\String('ModuleId', '', true),
        );
    }

    /**
     * Return CSS classes
     *
     * @return string
     */
    protected function getClass()
    {
        return parent::getClass() . ' select-installation-type-button';
    }
}
