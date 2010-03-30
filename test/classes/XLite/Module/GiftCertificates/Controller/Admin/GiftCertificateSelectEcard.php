<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category widget
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
 * Gift Certificate E-Card selection controller
 *
 * @package    Controller
 * @subpackage Admin
 * @since      3.0
 */
class XLite_Module_GiftCertificates_Controller_Admin_GiftCertificateSelectEcard extends XLite_Controller_Admin_Abstract
{	
    public $params = array("target", "gcid");

    /**
     * Do action 'update'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
	    $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate(XLite_Core_Request::getInstance()->gcid);
	    $gc->set("ecard_id", XLite_Core_Request::getInstance()->ecard_id);
	    $gc->update();
    }

    /**
     * Prepare return URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getReturnURL()
    {
        return $this->buildUrl('add_gift_certificate', '', array('gcid' => XLite_Core_Request::getInstance()->gcid));
    }
}

