<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * CMS connector
 *  
 * @category  Litecommerce
 * @package   Core
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Singleton to connect to a CMS
 *                         
 * @package    Core
 * @since      3.0                   
 */
abstract class XLite_Module_ProductAdviser_Core_CMSConnector extends XLite_Core_CMSConnector implements XLite_Base_IDecorator
{
    /**
     * Constructor
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function __construct()
    {
		parent::__construct();

        $this->widgetsList['XLite_Module_ProductAdviser_View_NewArrivals']     = 'New arrivals';
        $this->widgetsList['XLite_Module_ProductAdviser_View_RecentlyViewed']  = 'Recently viewed products';
        $this->widgetsList['XLite_Module_ProductAdviser_View_RelatedProducts'] = 'Related products';
        $this->widgetsList['XLite_Module_ProductAdviser_View_ProductAlsoBuy']  = 'People who buy this product also buy';

        $this->pageTypes['recently_viewed'] = 'Recently viewed products';
        $this->pageTypes['new_arrivals']    = 'New arrivals';
    }
}
