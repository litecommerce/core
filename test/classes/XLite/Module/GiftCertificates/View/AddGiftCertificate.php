<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Add gift certificate page
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
 * Add gift certificate page
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_GiftCertificates_View_AddGiftCertificate extends XLite_View_Dialog
{
    /**
     * Title
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
    protected $head = 'Add gift certificate';

    /**
     * Widget body template
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $body = 'modules/GiftCertificates/add_gift_certificate.tpl';

    /**
     * Initilization
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
        parent::initView();

        $this->visible = $this->visible && 'add_gift_certificate' == $this->target;
    }
}

