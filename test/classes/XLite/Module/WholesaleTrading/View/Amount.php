<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Product amount widget
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
 * Product amount widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_WholesaleTrading_View_Amount extends XLite_View_Abstract
{
    /**
     * Javascript library path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $jsLibraryPath = 'modules/WholesaleTrading/amount.js';

    /**
     * Widget template filename
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = 'modules/WholesaleTrading/amount.tpl';

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
            && $this->get('product')->isPriceAvailable()
            && XLite::$controller->isAvailableForSale();
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

        return $list;
    }

}

