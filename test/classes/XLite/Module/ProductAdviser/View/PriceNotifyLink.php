<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Notify link widget
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
 * Notify link widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_ProductAdviser_View_PriceNotifyLink extends XLite_View_Abstract
{
    /**
     * Widget template filename
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = 'modules/ProductAdviser/PriceNotification/product_button.tpl';

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
            && $this->get('priceNotificationEnabled')
            && $this->getProduct()->get('priceNotificationAllowed');
    }
}

