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

namespace XLite\View\ModulesManager;

/**
 * Modules upload widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class UploadAddons extends \XLite\View\Dialog
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Upload add-on';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'modules_manager/upload_addons';
    }
}
