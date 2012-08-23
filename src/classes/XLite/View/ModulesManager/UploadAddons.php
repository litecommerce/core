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

namespace XLite\View\ModulesManager;

/**
 * Modules upload widget
 *
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class UploadAddons extends \XLite\View\ModulesManager\AModulesManager
{
    /**
     * Target that is allowed for Upload Addons widget
     */
    const UPLOAD_ADDONS_TARGET = 'addon_upload';

    /**
     * Javascript file that is used for multiadd functionality
     */
    const JS_SCRIPT = 'modules_manager/upload_addons/js/upload_addons.js';


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = self::UPLOAD_ADDONS_TARGET;

        return $result;
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return void
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = self::JS_SCRIPT;

        return $list;
    }

    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return 'Upload add-on';
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return parent::getDir() . LC_DS . 'upload_addons';
    }
}
