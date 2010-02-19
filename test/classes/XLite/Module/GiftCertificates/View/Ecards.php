<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Gift certificate ecards page
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
 * Gift certificate ecards page
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_GiftCertificates_View_Ecards extends XLite_View_Dialog
{
    /**
     * Title
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
    protected $head = 'Select e-Card';

    /**
     * Widget body template
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $body = 'modules/GiftCertificates/select_ecard.tpl';

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
            && 'gift_certificate_ecards' == $this->target;
    }
}

