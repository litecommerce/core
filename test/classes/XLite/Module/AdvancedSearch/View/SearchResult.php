<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Search result list widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Search result list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_AdvancedSearch_View_SearchResult extends XLite_View_SearchResult implements XLite_Base_IDecorator
{
    /**
     * Check current mode
     * 
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function checkMode()
    {
        return !('advanced_search' == XLite_Core_Request::getInstance()->target && 'found' != XLite_Core_Request::getInstance()->mode);
    }


    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->allowedTargets[] = 'advanced_search';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->checkMode();
    }
}

