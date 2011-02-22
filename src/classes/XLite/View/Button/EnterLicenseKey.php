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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Button;

/**
 * Enter license key popup text 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class EnterLicenseKey extends \XLite\View\Button\APopupButton
{
    /**
     * Button label
     */
    const TEXT_LABEL = 'Enter license key';

    /**
     * Widget class to show
     */
    const ENTER_KEY_WIDGET = 'XLite\View\ModulesManager\AddonKey';


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
        return $this->t(static::TEXT_LABEL);
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
            'target' => 'addon_key',
            'action' => 'view',
            'widget' => static::ENTER_KEY_WIDGET,
        );
    }

    /** 
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {   
        $list = parent::getCSSFiles();

        return $list;
    }   


    /** 
     * Register JS files
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        return $list;
    }   
}
