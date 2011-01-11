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
 * Upload addons button 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class UploadAddons extends \XLite\View\Button\PopupButton
{
    /**
     *  Several specific constants
     */
    const UPLOAD_ADDONS_LABEL       = 'Upload add-ons';
    const UPLOAD_ADDON_CSS_CLASS    = 'upload-addons';
    const UPLOAD_ADDONS_WIDGET      = 'XLite\View\ModulesManager\UploadAddons';


    /** 
     * Return content for popup button
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getButtonContent() 
    {
        return $this->t(self::UPLOAD_ADDONS_LABEL);
    }

    /** 
     * Return URL parameters to use in AJAX popup
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function prepareURLParams() 
    {
        return array(
            'target' => \XLite\View\ModulesManager\UploadAddons::UPLOAD_ADDONS_TARGET,
            'action' => 'view',
            'widget' => self::UPLOAD_ADDONS_WIDGET,
        );
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

        $list[] = \XLite\View\ModulesManager\UploadAddons::JS_SCRIPT;

        return $list;
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
        return parent::getClass() . ' ' . self::UPLOAD_ADDON_CSS_CLASS;
    }

}
