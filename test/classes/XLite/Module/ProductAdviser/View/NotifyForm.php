<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Notify form widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Notify form widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_ProductAdviser_View_NotifyForm extends XLite_View_Abstract
{
    /**
     * Javascript library path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $jsLibraryPath = 'modules/ProductAdviser/OutOfStock/notify_form.js';

    /**
     * BlockUI library path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $blockUILibraryPath = 'popup/jquery.blockUI.js';

    /**
     * BlockUI-based popup library path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $blockUIExtLibraryPath = 'popup/popup.js';

    /**
     * BlockUI-based popup CSS path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $blockUIExtCSSPath = 'popup/popup.css';

    /**
     * Widget template filename
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = 'modules/ProductAdviser/OutOfStock/notify_form.tpl';

    /**
     * Check visibility 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->xlite->get('PA_InventorySupport')
            && $this->get('productNotificationEnabled')
            && $this->get('rejectedItem');
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

        $list[] = $this->jsLibraryPath;
        $list[] = $this->blockUILibraryPath;
        $list[] = $this->blockUIExtLibraryPath;

        return $list;
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

        $list[] = $this->blockUIExtCSSPath;

        return $list;
    }

    /**
     * Get current URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentURL()
    {
        return urlencode(urlencode($this->get('url')));
    }
}

